<?php
declare(strict_types=1);

namespace Atelier;

/**
 * Die Mail an das Paar: Dank, Bild, und die drei Links.
 *
 * "Email e linki atabilir ilaveten ... Mail bos olmasin kaliteli olsun
 * resimli felan birde tesekkur yazili" (Ayhan, 10.10.2026). Bis dahin
 * standen die privaten Links nur auf der Erfolgsseite - wer sie schloss,
 * hatte sie verloren.
 *
 * Zwei Anlaesse, eine Mail: direkt nach dem Veroeffentlichen (eine
 * Einladung, mit Dank) und auf "Links verloren" (alle Einladungen dieser
 * Adresse, ohne Dank). bauen() ist rein - kein Versand, keine Datenbank -
 * damit die Tests sehen, was hinausgeht.
 *
 * Gestaltet fuer Mailprogramme, nicht fuer Browser: Tabellen, Stile im
 * Element, keine Webfonts, nichts, was Outlook verschluckt. Die Farben sind
 * die der Website (Papier, Tinte, Gold).
 */
final class EinladungsMail
{
    private const PAPIER = '#faf7f2';
    private const SAND   = '#e8e0d4';
    private const TINTE  = '#2b2622';
    private const LEISE  = '#7a7068';
    private const GOLD   = '#b08d57';

    /**
     * @param list<array{slug:string,data:array<string,mixed>,links:array<string,mixed>,bild:string}> $einladungen
     * @return array{subject:string,html:string,text:string}
     */
    public static function bauen(array $einladungen, string $sprache, bool $wiederholt = false): array
    {
        $w = self::woerter($sprache === 'en' ? 'en' : 'de');

        $erste = $einladungen[0] ?? ['data' => [], 'links' => [], 'bild' => '', 'slug' => ''];
        $paar  = self::paar($erste['data']);

        $subject = $wiederholt
            ? $w['betreffWieder']
            : sprintf($w['betreff'], $paar !== '' ? $paar : $w['euch']);

        $html = [];
        $text = [];

        if ($wiederholt) {
            $html[] = self::ueberschrift($w['wiederTitel']);
            $html[] = self::absatz($w['wiederText']);
            $text[] = $w['wiederTitel'];
            $text[] = '';
            $text[] = $w['wiederText'];
        } else {
            $titel = sprintf($w['danke'], $paar !== '' ? $paar : $w['euch']);
            $html[] = self::ueberschrift($titel);
            $html[] = self::absatz($w['dankText']);
            $text[] = $titel;
            $text[] = '';
            $text[] = $w['dankText'];
        }

        foreach ($einladungen as $nr => $ein) {
            $links = $ein['links'];
            $daten = $ein['data'];

            $html[] = $nr > 0 || $wiederholt ? self::linie() : '';

            if ($wiederholt) {
                $html[] = self::zwischentitel(self::paar($daten) !== '' ? self::paar($daten) : '/' . $ein['slug']);
            }

            if (!$wiederholt && $ein['bild'] !== '') {
                $html[] = '<tr><td style="padding:8px 0 24px">'
                    . '<a href="' . e($links['gast']['url']) . '">'
                    . '<img src="' . e($ein['bild']) . '" width="536" alt="' . e($paar) . '"'
                    . ' style="display:block;width:100%;max-width:536px;height:auto;border:0"></a></td></tr>';
            }

            $wann = self::wannWo($daten, $sprache);
            if ($wann !== '') {
                $html[] = '<tr><td style="padding:0 0 20px;font-family:Georgia,serif;font-size:15px;color:' . self::LEISE . ';text-align:center">'
                    . e($wann) . '</td></tr>';
                $text[] = '';
                $text[] = $wann;
            }

            $html[] = self::etikett($w['fuerGaeste']);
            $html[] = self::knopf($links['gast']['url'], $w['ansehen'], true);
            $text[] = '';
            $text[] = $w['fuerGaeste'] . ':';
            $text[] = $links['gast']['url'];

            if (($links['antworten'] ?? null) !== null && ($links['bearbeiten'] ?? null) !== null) {
                $html[] = self::etikett($w['nurEuch']);
                $html[] = self::knopf($links['antworten']['url'], $w['antworten'], false);
                $html[] = self::knopf($links['bearbeiten']['url'], $w['bearbeiten'], false);
                $html[] = self::absatz($w['geheim'], true);

                $text[] = '';
                $text[] = $w['nurEuch'] . ':';
                $text[] = $w['antworten'] . ': ' . $links['antworten']['url'];
                $text[] = $w['bearbeiten'] . ': ' . $links['bearbeiten']['url'];
                $text[] = $w['geheim'];
            }
        }

        $verloren = Config::url() . I18n::path('/v2/meine-links', $sprache === 'en' ? 'en' : 'de');
        $html[] = self::linie();
        $html[] = '<tr><td style="padding:0;font-family:Georgia,serif;font-size:13px;line-height:1.6;color:' . self::LEISE . ';text-align:center">'
            . e($w['aufbewahren']) . '<br>'
            . e($w['verloren']) . ' <a href="' . e($verloren) . '" style="color:' . self::GOLD . '">' . e($verloren) . '</a></td></tr>';

        $text[] = '';
        $text[] = $w['aufbewahren'];
        $text[] = $w['verloren'] . ' ' . $verloren;
        $text[] = '';
        $text[] = 'Han Studio';

        return [
            'subject' => $subject,
            'html'    => self::rahmen(implode("\n", array_filter($html, static fn (string $z): bool => $z !== '')), $subject),
            'text'    => implode("\n", $text),
        ];
    }

    /**
     * Bauen und hinausschicken. Fehlschlaege sind stumm wie ueberall in Mail:
     * die Links stehen auf der Erfolgsseite und im Panel, eine Fehlerseite
     * nach dem Veroeffentlichen hilft dem Paar nicht.
     *
     * @param list<array{slug:string,data:array<string,mixed>,links:array<string,mixed>,bild:string}> $einladungen
     */
    public static function senden(string $an, array $einladungen, string $sprache, bool $wiederholt = false): bool
    {
        if ($einladungen === []) {
            return false;
        }

        $mail = self::bauen($einladungen, $sprache, $wiederholt);

        return Mail::html($an, $mail['subject'], $mail['html'], $mail['text']);
    }

    /** @param array<string,mixed> $daten */
    private static function paar(array $daten): string
    {
        return trim(((string) ($daten['bride'] ?? '')) . ' & ' . ((string) ($daten['groom'] ?? '')), ' &');
    }

    /** @param array<string,mixed> $daten */
    private static function wannWo(array $daten, string $sprache): string
    {
        $teile = [];
        $datum = trim((string) ($daten['date'] ?? ''));
        if ($datum !== '') {
            $teile[] = Dates::long($datum, $sprache);
        }
        $ort = trim((string) ($daten['venue'] ?? ''));
        if ($ort !== '') {
            $teile[] = $ort;
        }

        return implode(' · ', $teile);
    }

    private static function rahmen(string $zeilen, string $titel): string
    {
        return '<!doctype html><html><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width,initial-scale=1">'
            . '<title>' . e($titel) . '</title></head>'
            . '<body style="margin:0;padding:0;background:' . self::SAND . '">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:' . self::SAND . '">'
            . '<tr><td align="center" style="padding:32px 12px">'
            . '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%;max-width:600px;background:' . self::PAPIER . '">'
            . '<tr><td style="padding:28px 32px 0;font-family:Georgia,serif;font-size:11px;letter-spacing:4px;text-transform:uppercase;color:' . self::GOLD . ';text-align:center">Han Studio</td></tr>'
            . '<tr><td style="padding:12px 32px 36px">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $zeilen . '</table>'
            . '</td></tr></table></td></tr></table></body></html>';
    }

    private static function ueberschrift(string $text): string
    {
        return '<tr><td style="padding:12px 0 12px;font-family:Georgia,serif;font-size:28px;line-height:1.25;color:' . self::TINTE . ';text-align:center">'
            . e($text) . '</td></tr>';
    }

    private static function zwischentitel(string $text): string
    {
        return '<tr><td style="padding:0 0 16px;font-family:Georgia,serif;font-size:20px;color:' . self::TINTE . ';text-align:center">'
            . e($text) . '</td></tr>';
    }

    private static function absatz(string $text, bool $leise = false): string
    {
        return '<tr><td style="padding:0 0 20px;font-family:Georgia,serif;font-size:' . ($leise ? '13' : '16') . 'px;line-height:1.6;color:'
            . ($leise ? self::LEISE : self::TINTE) . ';text-align:center">' . e($text) . '</td></tr>';
    }

    private static function etikett(string $text): string
    {
        return '<tr><td style="padding:12px 0 10px;font-family:Georgia,serif;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:' . self::LEISE . ';text-align:center">'
            . e($text) . '</td></tr>';
    }

    private static function knopf(string $url, string $wort, bool $voll): string
    {
        $stil = $voll
            ? 'background:' . self::TINTE . ';color:' . self::PAPIER . ';border:1px solid ' . self::TINTE
            : 'background:' . self::PAPIER . ';color:' . self::TINTE . ';border:1px solid ' . self::TINTE;

        return '<tr><td align="center" style="padding:0 0 12px">'
            . '<a href="' . e($url) . '" style="display:inline-block;padding:14px 28px;font-family:Georgia,serif;font-size:13px;letter-spacing:2px;text-transform:uppercase;text-decoration:none;' . $stil . '">'
            . e($wort) . '</a></td></tr>';
    }

    private static function linie(): string
    {
        return '<tr><td style="padding:20px 0"><div style="border-top:1px solid ' . self::SAND . ';height:1px;line-height:1px">&nbsp;</div></td></tr>';
    }

    /** @return array<string,string> */
    private static function woerter(string $sprache): array
    {
        return $sprache === 'en' ? [
            'betreff'       => 'Your invitation is live, %s',
            'betreffWieder' => 'Your invitation links',
            'euch'          => 'you two',
            'danke'         => 'Thank you, %s!',
            'dankText'      => 'Your invitation is online. We are delighted you created it with us and wish you a wonderful day. Here are your links, all in one place.',
            'wiederTitel'   => 'Your links',
            'wiederText'    => 'You asked for your invitation links. Here they are.',
            'fuerGaeste'    => 'For your guests',
            'ansehen'       => 'View invitation',
            'nurEuch'       => 'Just for you',
            'antworten'     => 'See replies',
            'bearbeiten'    => 'Edit invitation',
            'geheim'        => 'These two links belong to you alone. Please do not pass them on.',
            'aufbewahren'   => 'Keep this e-mail safe.',
            'verloren'      => 'Lost your links?',
        ] : [
            'betreff'       => 'Eure Einladung ist online, %s',
            'betreffWieder' => 'Eure Einladungslinks',
            'euch'          => 'ihr zwei',
            'danke'         => 'Danke, %s!',
            'dankText'      => 'Eure Einladung ist online. Schön, dass ihr sie mit uns gestaltet habt – wir wünschen euch einen wunderbaren Tag. Hier sind eure Links, alle an einem Ort.',
            'wiederTitel'   => 'Eure Links',
            'wiederText'    => 'Ihr habt eure Einladungslinks angefordert. Hier sind sie.',
            'fuerGaeste'    => 'Für eure Gäste',
            'ansehen'       => 'Einladung ansehen',
            'nurEuch'       => 'Nur für euch',
            'antworten'     => 'Antworten ansehen',
            'bearbeiten'    => 'Einladung bearbeiten',
            'geheim'        => 'Diese beiden Links gehören nur euch. Gebt sie nicht weiter.',
            'aufbewahren'   => 'Hebt diese E-Mail gut auf.',
            'verloren'      => 'Links verloren?',
        ];
    }
}
