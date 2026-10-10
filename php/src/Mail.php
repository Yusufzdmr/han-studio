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
     * Ueber Brevo, sobald im Panel (Integrationen) ein BREVO_API_KEY steht:
     * eine Mail mit Bild, die vom VPS ueber mail() hinausgeht, landet sonst
     * meist im Spam. Ohne Schluessel bleibt mail() - lieber im Spam als gar
     * nicht. Antworten gehen an den Betrieb (mail_to).
     */
    public static function html(string $to, string $subject, string $html, string $text): bool
    {
        $to      = Security::singleLine($to);
        $from    = Security::singleLine(Config::str('mail_from'));
        $replyTo = Security::singleLine(Config::str('mail_to'));

        if ($from === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $schluessel = Integrations::value('BREVO_API_KEY');
        if ($schluessel !== '') {
            return self::brevo($schluessel, self::brevoBody($to, $subject, $html, $text, $from, $replyTo));
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

        return @mail(
            $to,
            '=?UTF-8?B?' . base64_encode(Security::singleLine($subject)) . '?=',
            $koerper,
            implode("\r\n", $headers)
        );
    }

    /** Der Absendername in jeder Mail. */
    public const ABSENDER = 'Han Studio';

    /**
     * Der Rumpf fuer Brevos /v3/smtp/email. Eigene Funktion, damit die Tests
     * sehen, was hinausgeht, ohne dass etwas hinausgeht.
     */
    public static function brevoBody(string $to, string $subject, string $html, string $text, string $from, string $replyTo): string
    {
        $rumpf = [
            'sender'      => ['name' => self::ABSENDER, 'email' => $from],
            'to'          => [['email' => $to]],
            'subject'     => Security::singleLine($subject),
            'htmlContent' => $html,
            'textContent' => $text,
        ];
        if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $rumpf['replyTo'] = ['email' => $replyTo];
        }

        return (string) json_encode($rumpf, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private static function brevo(string $schluessel, string $rumpf): bool
    {
        if (!function_exists('curl_init')) {
            error_log('[mail] Brevo: kein curl');
            return false;
        }

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $rumpf,
            CURLOPT_HTTPHEADER     => [
                'api-key: ' . $schluessel,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);
        $antwort = curl_exec($ch);
        $status  = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($status < 200 || $status >= 300) {
            // Der Schluessel steht nicht im Log, die Antwort schon: sie sagt,
            // was fehlt (Absender nicht bestaetigt, Kontingent, ...).
            error_log('[mail] Brevo ' . $status . ': ' . substr((string) $antwort, 0, 300));
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
