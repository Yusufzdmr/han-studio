<?php
declare(strict_types=1);

namespace Atelier;

/**
 * Ausgehende Post.
 *
 * Wenig, aber an drei Stellen dasselbe: Absender aus der config.php, Betreff
 * nach RFC 2047 kodiert (sonst steht „Ã¼“ im Posteingang), Text als UTF-8.
 * Fehlschläge sind absichtlich stumm – was verschickt werden sollte, steht
 * vorher schon in der Datenbank, und eine Fehlerseite hilft dem Absender an
 * dieser Stelle nicht.
 */
final class Mail
{
    /**
     * @param list<string> $lines Textzeilen der Nachricht
     */
    public static function send(string $to, string $subject, array $lines, string $replyTo = ''): bool
    {
        $to = Security::singleLine($to);
        $from = Security::singleLine(Config::str('mail_from'));

        if ($from === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $headers = [
            'From: ' . self::ABSENDER . ' <' . $from . '>',
            'Content-Type: text/plain; charset=UTF-8',
            'MIME-Version: 1.0',
        ];

        $replyTo = Security::singleLine($replyTo);
        if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        return @mail(
            $to,
            '=?UTF-8?B?' . base64_encode(Security::singleLine($subject)) . '?=',
            implode("\n", $lines),
            implode("\r\n", $headers)
        );
    }

    /**
     * Eine gestaltete Mail an einen Kunden: HTML mit Textfassung.
     *
     * Ueber Resend, sobald im Panel (Integrationen) ein RESEND_API_KEY steht:
     * eine Mail mit Bild, die vom VPS ueber mail() hinausgeht, landet sonst
     * meist im Spam. Ohne Schluessel bleibt mail() - lieber im Spam als gar
     * nicht. Antworten gehen an den Betrieb (mail_to).
     */
    public static function html(string $to, string $subject, string $html, string $text): bool
    {
        self::$fehler = '';
        $to      = Security::singleLine($to);
        $from    = Security::singleLine(Config::str('mail_from'));
        $replyTo = Security::singleLine(Config::str('mail_to'));

        if ($from === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            self::$fehler = $from === '' ? 'mail_from fehlt in config.php' : 'Empfaenger ungueltig';
            return false;
        }

        $schluessel = Integrations::value('RESEND_API_KEY');
        if ($schluessel !== '') {
            return self::resend($schluessel, self::resendBody($to, $subject, $html, $text, $from, $replyTo));
        }

        $grenze = 'hs-' . bin2hex(random_bytes(8));
        $headers = [
            'From: ' . self::ABSENDER . ' <' . $from . '>',
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $grenze . '"',
        ];
        if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        $koerper = '--' . $grenze . "\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n"
            . chunk_split(base64_encode($text)) . "\r\n"
            . '--' . $grenze . "\r\n"
            . "Content-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n"
            . chunk_split(base64_encode($html)) . "\r\n"
            . '--' . $grenze . "--\r\n";

        $ok = @mail(
            $to,
            '=?UTF-8?B?' . base64_encode(Security::singleLine($subject)) . '?=',
            $koerper,
            implode("\r\n", $headers)
        );
        if (!$ok) {
            self::$fehler = 'mail() hat abgelehnt';
        }

        return $ok;
    }

    /** Der Absendername in jeder Mail. */
    public const ABSENDER = 'Han Studio';

    /** Warum der letzte html()-Versand scheiterte - fuer den Test im Panel. */
    private static string $fehler = '';

    public static function letzterFehler(): string
    {
        return self::$fehler;
    }

    /** 'resend' oder 'mail' - fuer Panel und Systemcheck. */
    public static function weg(): string
    {
        return Integrations::value('RESEND_API_KEY') !== '' ? 'resend' : 'mail';
    }

    /**
     * Der Rumpf fuer Resends POST /emails. Eigene Funktion, damit die Tests
     * sehen, was hinausgeht, ohne dass etwas hinausgeht.
     */
    public static function resendBody(string $to, string $subject, string $html, string $text, string $from, string $replyTo): string
    {
        $rumpf = [
            'from'    => self::ABSENDER . ' <' . $from . '>',
            'to'      => [$to],
            'subject' => Security::singleLine($subject),
            'html'    => $html,
            'text'    => $text,
        ];
        if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $rumpf['reply_to'] = $replyTo;
        }

        return (string) json_encode($rumpf, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private static function resend(string $schluessel, string $rumpf): bool
    {
        if (!function_exists('curl_init')) {
            self::$fehler = 'kein curl';
            error_log('[mail] Resend: kein curl');
            return false;
        }

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $rumpf,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $schluessel,
                'Content-Type: application/json',
            ],
            // Resend weist Anfragen ohne User-Agent ab.
            CURLOPT_USERAGENT      => 'han-studio/1.0',
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);
        $antwort = curl_exec($ch);
        $status  = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $netz    = curl_error($ch);
        curl_close($ch);

        if ($status < 200 || $status >= 300) {
            // Der Schluessel steht nicht im Log, die Antwort schon: sie sagt,
            // was fehlt (Domain nicht bestaetigt, Schluessel falsch, ...).
            $meldung = json_decode((string) $antwort, true);
            self::$fehler = 'Resend ' . $status . ': '
                . (is_array($meldung) && isset($meldung['message']) ? (string) $meldung['message'] : ($netz !== '' ? $netz : substr((string) $antwort, 0, 300)));
            error_log('[mail] ' . self::$fehler);
            return false;
        }

        return true;
    }

    /** Die Adresse des Betriebs – Kontaktformular, Galerieauswahl. */
    public static function toStudio(string $subject, array $lines, string $replyTo = ''): bool
    {
        return self::send(Config::str('mail_to'), $subject, $lines, $replyTo);
    }
}
