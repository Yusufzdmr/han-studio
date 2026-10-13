<?php
declare(strict_types=1);

namespace Atelier;

/**
 * Hochgeladene Bilder.
 *
 * Auf Vercel lag das in einem Objektspeicher, hier liegt es im Dateisystem
 * des Webspace. Verkleinert wird serverseitig mit GD: was aus einer Kamera
 * kommt, hat 6000 px – das will niemand ausliefern und der Speicherplatz
 * eines Webhosting-Pakets ist endlich.
 */
final class Media
{
    private const MAX_WIDTH = 1600;
    private const QUALITY = 82;

    /**
     * Was eine Pruefung nicht angenommen hat - fuer die Meldung danach.
     *
     * @var list<array{name:string,art:string}>
     */
    private static array $abgelehnt = [];

    /**
     * Eine Datei annehmen - und sich merken, wenn sie nicht durchkommt.
     *
     * An jeder Aufrufstelle stand bisher "if ($pfad !== null)", und der
     * andere Fall war eine leere Zeile. Wer eine HEIC vom iPhone waehlte
     * oder ein Bild ueber sechs Megabyte, sah danach genau dasselbe wie
     * vorher: nichts. "Harita kismina resim atim." - "Olmadi."
     *
     * Der Sammler steht HIER und nicht in den Steuerungen: durch diese Tuer
     * geht jeder Upload des Hauses, und zwei Steuerungen mit derselben
     * kleinen Liste waeren zwei Orte, an denen sie kaputtgehen kann.
     *
     * Die Pruefung bleibt, wo sie war, und sieht weiter in die Datei und
     * nicht auf den Namen. Neu ist nur, dass ihr Nein ankommt.
     *
     * @param array<string,mixed> $datei
     * @param callable(array<string,mixed>):?string $pruefung
     */
    public static function nimm(array $datei, string $art, callable $pruefung): ?string
    {
        $pfad = $pruefung($datei);

        if ($pfad === null) {
            // Eine Zeile ohne Datei ist keine Ablehnung: das Formular schickt
            // jedes Dateifeld mit, auch die leeren.
            $fehler = (int) ($datei['error'] ?? UPLOAD_ERR_NO_FILE);
            if ($fehler !== UPLOAD_ERR_NO_FILE) {
                self::$abgelehnt[] = [
                    'name' => (string) ($datei['name'] ?? ''),
                    'art'  => $art,
                ];
            }
        }

        return $pfad;
    }

    /**
     * Die abgelehnten Dateien dieses Aufrufs.
     *
     * @return list<array{name:string,art:string}>
     */
    public static function abgelehnt(): array
    {
        return self::$abgelehnt;
    }

    /** Ordner im Dateisystem, in dem die Uploads liegen. */
    public static function dir(string $sub = ''): string
    {
        $base = dirname(__DIR__) . '/public/' . trim(Config::str('upload_dir', 'uploads'), '/');
        $path = $sub === '' ? $base : $base . '/' . trim($sub, '/');

        if (!is_dir($path)) {
            @mkdir($path, 0755, true);
        }

        return $path;
    }

    /** Öffentliche Adresse einer gespeicherten Datei. */
    public static function url(string $relative): string
    {
        return '/' . trim(Config::str('upload_dir', 'uploads'), '/') . '/' . ltrim($relative, '/');
    }

    /**
     * Dateiname für die Ablage: möglichst der ursprüngliche, damit der
     * Fotograf seine eigenen Dateien wiedererkennt – „6100b2262e0736c4.jpg“
     * sagt niemandem etwas, „IMG-Hochzeit-042.jpg“ schon.
     *
     * Auf den Namen selbst ist trotzdem kein Verlass: nur unbedenkliche
     * Zeichen bleiben übrig, alles andere (Leerzeichen, Umlaute, Emoji,
     * Pfadteile) wird zu einem Bindestrich. Bleibt nichts übrig, oder
     * kollidiert der Name mit einer schon abgelegten Datei im selben
     * Ordner, springt ein Zähler oder – im Notfall – der alte Zufallsname
     * ein.
     */
    private static function safeStem(string $originalName, string $folder): string
    {
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $clean = preg_replace('/[^A-Za-z0-9_-]+/', '-', $base) ?? '';
        $clean = trim($clean, '-');
        $clean = mb_substr($clean, 0, 80);

        if ($clean === '') {
            return bin2hex(random_bytes(8));
        }

        $dir = self::dir($folder);
        $stem = $clean;
        for ($i = 2; is_file($dir . '/' . $stem . '.jpg'); $i++) {
            $stem = $clean . '-' . $i;
        }

        return $stem;
    }

    /**
     * Datei aus $_FILES übernehmen: prüfen, verkleinern, speichern.
     *
     * @param array{name?:string,type?:string,tmp_name?:string,error?:int,size?:int} $file
     * @return string|null Öffentliche URL oder null bei Fehler
     */
    public static function store(array $file, string $folder, string $stanze = ''): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            return null;
        }

        // Auf den Dateinamen ist kein Verlass – der Inhalt entscheidet.
        $info = @getimagesize($tmp);
        if ($info === false) {
            return null;
        }

        [$width, $height] = $info;
        $mime = (string) ($info['mime'] ?? '');

        // Nicht jeder Webspace hat GD. Dann wird nicht verkleinert, sondern die
        // geprüfte Datei unverändert abgelegt – lieber ein großes Bild als eine
        // Fehlerseite beim Hochladen.
        if (!function_exists('imagecreatefromjpeg')) {
            return self::keep($tmp, $mime, $folder);
        }

        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($tmp),
            'image/png'  => @imagecreatefrompng($tmp),
            'image/webp' => @imagecreatefromwebp($tmp),
            'image/gif'  => @imagecreatefromgif($tmp),
            default      => false,
        };

        if ($image === false) {
            return null;
        }

        // Seitenverhältnis behalten, nur verkleinern – nie vergrößern.
        if ($width > self::MAX_WIDTH) {
            $newWidth = self::MAX_WIDTH;
            $newHeight = (int) round($height * (self::MAX_WIDTH / $width));
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        $stem = self::safeStem((string) ($file['name'] ?? ''), $folder);

        if (in_array($stanze, self::STANZEN, true)) {
            // Ein herausgestanzter Grund braucht einen Alphakanal - JPEG kann
            // keinen tragen. Also WebP (mit Alpha), sonst PNG.
            self::freistellen($image, $stanze);
            $webp = function_exists('imagewebp');
            $name = $stem . ($webp ? '.webp' : '.png');
            $relative = trim($folder, '/') . '/' . $name;
            $target = self::dir($folder) . '/' . $name;
            $ok = $webp ? imagewebp($image, $target, self::QUALITY) : imagepng($image, $target, 6);
        } else {
            $name = $stem . '.jpg';
            $relative = trim($folder, '/') . '/' . $name;
            $target = self::dir($folder) . '/' . $name;

            // Einheitlich JPEG: kleiner als PNG und überall darstellbar.
            $ok = imagejpeg($image, $target, self::QUALITY);
        }
        imagedestroy($image);

        if (!$ok) {
            return null;
        }

        // Das Original daneben legen – siehe keepOriginal().
        self::keepOriginal($tmp, $mime, $folder, $stem);

        return self::url($relative);
    }

    /** Unterordner neben den verkleinerten Bildern. */
    public const ORIGINALS = 'original';

    /**
     * Das unveraenderte Bild neben dem verkleinerten aufheben.
     *
     * Die Galerie zeigt 1600 Pixel – richtig fuer den Browser, zu wenig fuer
     * den Albumdruck. Wenn das Paar seine Bilder ausgesucht hat, soll der
     * Drucker die vollen Dateien bekommen und nicht der Fotograf noch einmal
     * suchen, hochladen und schicken muessen.
     *
     * Gleicher Dateiname wie das verkleinerte Bild, nur in einem Unterordner:
     * so ist das Original aus der Adresse des kleinen ableitbar und die
     * gespeicherte Bildliste bleibt, was sie war.
     */
    private static function keepOriginal(string $tmp, string $mime, string $folder, string $stem): void
    {
        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => null,
        };

        if ($extension === null) {
            return;
        }

        $target = self::dir(trim($folder, '/') . '/' . self::ORIGINALS) . '/' . $stem . '.' . $extension;

        // move_uploaded_file waere hier falsch: die Datei wird anschliessend
        // noch als verkleinerte Fassung gebraucht.
        @copy($tmp, $target);
    }

    /**
     * Zu einem verkleinerten Bild das Original finden – als Pfad relativ zum
     * Upload-Ordner, ohne dass originalPath()/originalUrl() dieselbe Suche
     * zweimal schreiben.
     *
     * @return string|null null wenn es keines gibt (Bilder von vor dieser
     *                      Änderung, oder Platzhalter)
     */
    private static function originalRelative(string $url): ?string
    {
        $prefix = '/' . trim(Config::str('upload_dir', 'uploads'), '/') . '/';
        if (!str_starts_with($url, $prefix)) {
            return null;
        }

        $relative = substr($url, strlen($prefix));
        if (str_contains($relative, '..')) {
            return null;
        }

        $folder = dirname($relative);
        $stem = pathinfo($relative, PATHINFO_FILENAME);
        $base = trim($folder, '/') . '/' . self::ORIGINALS . '/' . $stem;

        foreach (['jpg', 'png', 'webp', 'gif'] as $extension) {
            if (is_file(self::dir() . '/' . $base . '.' . $extension)) {
                return $base . '.' . $extension;
            }
        }

        return null;
    }

    /**
     * Zu einem verkleinerten Bild das Original finden – als Plattenpfad,
     * für ZipArchive und andere Serverseitige Dateizugriffe (Albumcu-ZIP).
     */
    public static function originalPath(string $url): ?string
    {
        $relative = self::originalRelative($url);
        return $relative === null ? null : self::dir() . '/' . $relative;
    }

    /**
     * Zu einem verkleinerten Bild das Original finden – als öffentliche
     * Adresse, zum direkten Herunterladen im Browser (das Paar selbst,
     * nicht nur der Albumhersteller). Die Galerie zeigt 1600 Pixel, aber
     * wer sein eigenes Bild herunterlädt, soll nicht schlechter wegkommen
     * als der Drucker.
     */
    public static function originalUrl(string $url): ?string
    {
        $relative = self::originalRelative($url);
        return $relative === null ? null : self::url($relative);
    }

    /**
     * Rückfall ohne GD: die geprüfte Datei unverändert übernehmen.
     *
     * Die Endung kommt aus dem erkannten Typ, nicht aus dem Dateinamen – so
     * landet nichts Ausführbares im Upload-Ordner.
     */
    private static function keep(string $tmp, string $mime, string $folder): ?string
    {
        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => null,
        };

        if ($extension === null) {
            return null;
        }

        $name = bin2hex(random_bytes(8)) . '.' . $extension;
        $target = self::dir($folder) . '/' . $name;

        return move_uploaded_file($tmp, $target)
            ? self::url(trim($folder, '/') . '/' . $name)
            : null;
    }

    /** Groesser als ein Lied braucht eine Einladung nicht. */
    private const MAX_AUDIO = 12 * 1024 * 1024;

    /**
     * Hintergrundmusik der Einladung.
     *
     * Frueher stand hier ein Feld fuer eine Adresse, und wer – wie jeder – den
     * YouTube-Link seines Lieds hineinkopierte, bekam ein <audio>-Element mit
     * einer HTML-Seite darin. Das kann nicht klingen. Ein Video-Einbett-Player
     * waere der falsche Ausweg: er laedt beim Oeffnen der Karte bei einem
     * Fremden, und genau das soll auf dieser Seite ohne Einwilligung nie
     * passieren. Also liegt die Datei bei uns.
     *
     * Wie bei Bildern entscheidet der Inhalt, nicht der Dateiname, und die
     * Endung vergeben wir selbst.
     *
     * @param array{name?:string,type?:string,tmp_name?:string,error?:int,size?:int} $file
     */
    public static function storeAudio(array $file, string $folder): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp) || (int) ($file['size'] ?? 0) > self::MAX_AUDIO) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo !== false ? (string) finfo_file($finfo, $tmp) : '';
        if ($finfo !== false) {
            finfo_close($finfo);
        }

        // mp4/m4a meldet je nach Server audio/x-m4a oder audio/mp4.
        $extension = match ($mime) {
            'audio/mpeg', 'audio/mp3'   => 'mp3',
            'audio/mp4', 'audio/x-m4a'  => 'm4a',
            'audio/ogg', 'application/ogg' => 'ogg',
            'audio/wav', 'audio/x-wav'  => 'wav',
            default                     => null,
        };

        if ($extension === null) {
            return null;
        }

        $name = bin2hex(random_bytes(8)) . '.' . $extension;
        $target = self::dir($folder) . '/' . $name;

        return move_uploaded_file($tmp, $target)
            ? self::url(trim($folder, '/') . '/' . $name)
            : null;
    }

    /** Kurzer Beispielfilm einer Leistung (mp4/webm/mov). 100 MB Deckel. */
    private const MAX_VIDEO = 100 * 1024 * 1024;

    /**
     * Video übernehmen: Endung nach Inhalt, nicht nach dem Dateinamen.
     *
     * Wir transkodieren nicht – das kann ein Webhosting nicht in Ruhe. Wer
     * grosse Kameradateien laedt, sieht sie so wie sie sind; im Panel steht
     * daher der Hinweis „vorher komprimieren".
     *
     * @param array{name?:string,type?:string,tmp_name?:string,error?:int,size?:int} $file
     */
    public static function storeVideo(array $file, string $folder): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp) || (int) ($file['size'] ?? 0) > self::MAX_VIDEO) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo !== false ? (string) finfo_file($finfo, $tmp) : '';
        if ($finfo !== false) {
            finfo_close($finfo);
        }

        $extension = match ($mime) {
            'video/mp4', 'video/x-m4v'    => 'mp4',
            'video/webm'                  => 'webm',
            'video/quicktime', 'video/mov' => 'mov',
            default                        => null,
        };

        if ($extension === null) {
            return null;
        }

        $name = bin2hex(random_bytes(8)) . '.' . $extension;
        $target = self::dir($folder) . '/' . $name;

        return move_uploaded_file($tmp, $target)
            ? self::url(trim($folder, '/') . '/' . $name)
            : null;
    }

    /**
     * Schmuckelemente: Blume, Rahmen, Monogramm.
     *
     * Anders als bei Fotos darf hier nichts nach JPEG umgewandelt werden – ein
     * Rahmen ohne durchsichtigen Hintergrund ist kein Rahmen, sondern ein
     * weisses Rechteck. Ausgegeben wird deshalb WebP mit Alphakanal (kleiner
     * als PNG bei gleicher Qualitaet) oder, wo GD das nicht kann, PNG.
     *
     * SVG wird durchgereicht: es wird ausschliesslich in einem <img> gezeigt,
     * und dort fuehren Browser kein Skript aus. Zusaetzlich raeumt
     * cleanSvg() auf, und der Upload-Ordner erlaubt per .htaccess ohnehin
     * nichts Ausfuehrbares.
     *
     * @param array{name?:string,type?:string,tmp_name?:string,error?:int,size?:int} $file
     */
    public static function storeGraphic(array $file, string $folder, string $stanze = ''): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp) || filesize($tmp) > 6 * 1024 * 1024) {
            return null;
        }

        $raw = (string) file_get_contents($tmp);

        // SVG erkennt getimagesize() nicht – es ist Text, kein Rasterbild.
        if (preg_match('/^\s*(<\?xml|<svg)/i', $raw) === 1) {
            $clean = self::cleanSvg($raw);
            if ($clean === null) {
                return null;
            }
            $name = bin2hex(random_bytes(8)) . '.svg';
            $target = self::dir($folder) . '/' . $name;
            return file_put_contents($target, $clean) === false
                ? null
                : self::url(trim($folder, '/') . '/' . $name);
        }

        return self::grafikAblegen($tmp, $folder, $stanze, true);
    }

    /**
     * Ein schon abgelegtes Bild nachtraeglich freistellen.
     *
     * "fotograflardaki green screen duzeltmemissin" (12.10.2026): das Blatt
     * eines Abschnitts lag mit Gruen schon auf dem Server, als es dort noch
     * keine Wahl gab. Statt "noch einmal hochladen" reicht jetzt: Wahl
     * treffen, speichern. Es entsteht eine NEUE Datei - die alte bleibt
     * liegen, denn eine andere Vorlage oder Einladung kann sie noch tragen.
     *
     * Nur Rasterbilder im Upload-Ordner; alles andere (SVG, fremde Adresse,
     * Platzhalter) gibt null, und das Pfadfeld bleibt, wie es war.
     */
    public static function freistellenAbgelegt(string $url, string $folder, string $stanze): ?string
    {
        if (!in_array($stanze, self::STANZEN, true)) {
            return null;
        }

        $prefix = '/' . trim(Config::str('upload_dir', 'uploads'), '/') . '/';
        if (!str_starts_with($url, $prefix)) {
            return null;
        }

        $relative = substr($url, strlen($prefix));
        if ($relative === '' || str_contains($relative, '..')) {
            return null;
        }

        $pfad = self::dir() . '/' . $relative;
        if (!is_file($pfad) || filesize($pfad) > 12 * 1024 * 1024) {
            return null;
        }

        return self::grafikAblegen($pfad, $folder, $stanze, false);
    }

    /**
     * Ein Rasterbild als WebP/PNG mit Alphakanal ablegen - aus einem frischen
     * Upload ($hochgeladen) oder aus einer Datei, die schon im Ordner liegt.
     */
    private static function grafikAblegen(string $tmp, string $folder, string $stanze, bool $hochgeladen): ?string
    {
        $info = @getimagesize($tmp);
        if ($info === false) {
            return null;
        }

        [$width, $height] = $info;
        $mime = (string) ($info['mime'] ?? '');

        if (!function_exists('imagecreatefrompng')) {
            // Ohne GD laesst sich nichts freistellen; ein Upload wird wie
            // bisher unveraendert abgelegt, eine abgelegte Datei bleibt.
            return $hochgeladen ? self::keep($tmp, $mime, $folder) : null;
        }

        $image = match ($mime) {
            'image/png'  => @imagecreatefrompng($tmp),
            'image/webp' => @imagecreatefromwebp($tmp),
            'image/gif'  => @imagecreatefromgif($tmp),
            'image/jpeg' => @imagecreatefromjpeg($tmp),
            default      => false,
        };

        if ($image === false) {
            return null;
        }

        // Schmuck braucht keine Fotoaufloesung.
        $max = 1400;
        if ($width > $max) {
            $newWidth = $max;
            $newHeight = (int) round($height * ($max / $width));
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagefill($resized, 0, 0, imagecolorallocatealpha($resized, 0, 0, 0, 127));
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        // Der Grund eines Fotos (gruen oder schwarz) verschwindet schon hier
        // und nicht erst im Browser: so sieht die Datei ueberall gleich aus -
        // in der Karte, in der Galerie, im Standbild - auch ohne gruen.js.
        if (in_array($stanze, self::STANZEN, true)) {
            self::freistellen($image, $stanze);
        }

        $webp = function_exists('imagewebp');
        $name = bin2hex(random_bytes(8)) . ($webp ? '.webp' : '.png');
        $target = self::dir($folder) . '/' . $name;

        $ok = $webp ? imagewebp($image, $target, 88) : imagepng($image, $target, 6);
        imagedestroy($image);

        return $ok ? self::url(trim($folder, '/') . '/' . $name) : null;
    }

    /** Was ein Foto herausstanzen kann: gruener oder schwarzer Grund. */
    public const STANZEN = ['gruen', 'schwarz'];

    /**
     * Den Grund eines Fotos durchsichtig machen.
     *
     * Dieselbe Rechnung wie gruen.js fuer Filme, nur einmal beim Hochladen und
     * nicht in jedem Browser: ein Studiogruen wird zu Durchsichtigkeit, ein auf
     * Schwarz gedrehtes Bild traegt seine Deckung als Helligkeit. Weil das
     * Ergebnis schon auf der Platte durchsichtig liegt, zeigt es sich ueberall
     * gleich - ohne WebGL, auch in der Kachel einer Galerie.
     *
     * Ohne GD kommt diese Funktion nie zum Zug: store()/storeGraphic() legen
     * die Datei dann unveraendert ab, mit ihrem Grund. Lieber ein sichtbarer
     * Hintergrund als ein Loch.
     *
     * @param \GdImage|resource $image
     */
    public static function freistellen($image, string $stanze): void
    {
        if (!in_array($stanze, self::STANZEN, true)) {
            return;
        }

        // GIF kommt als Palette herein: imagecolorat() gaebe dort einen
        // Palettenindex und kein RGB. Erst auf Truecolor umstellen.
        if (function_exists('imagepalettetotruecolor') && !imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        $breit   = imagesx($image);
        $hoch    = imagesy($image);
        $schwarz = $stanze === 'schwarz';

        imagealphablending($image, false);
        imagesavealpha($image, true);

        for ($y = 0; $y < $hoch; $y++) {
            for ($x = 0; $x < $breit; $x++) {
                $farbe = imagecolorat($image, $x, $y);
                $a = ($farbe >> 24) & 0x7F;   // 0 deckend, 127 durchsichtig
                $r = ($farbe >> 16) & 0xFF;
                $g = ($farbe >> 8) & 0xFF;
                $b = $farbe & 0xFF;

                if ($schwarz) {
                    $h = max($r, $g, $b);
                    // Ab halber Helligkeit ist der Punkt deckend - die
                    // allermeisten: ein Sprung statt eines Schreibvorgangs.
                    if ($h >= 128) {
                        continue;
                    }
                    $neu = min(1.0, ($h / 255.0) / 0.5);
                } else {
                    $d = $g - max($r, $b);
                    // Kein nennenswertes Gruen: unberuehrt lassen. Deckt den
                    // Grossteil eines Fotos ab und spart das Schreiben.
                    if ($d <= 30) {
                        continue;
                    }
                    $neu = 1.0 - self::smoothstep(0.12, 0.30, $d / 255.0);
                    // Saum: an den halbdurchsichtigen Raendern traegt das Bild
                    // noch Gruen vom Grund. Gruen auf Rot/Blau deckeln, je
                    // durchsichtiger desto mehr - ein echtes gruenes Blatt
                    // bleibt so gruen.
                    $rb   = max($r, $b);
                    $minG = min($g, $rb);
                    $t    = $neu * $neu;
                    $g    = (int) round($minG * (1.0 - $t) + $g * $t);
                }

                // Was schon durchsichtig war, kann nicht deckender werden.
                $neu *= 1.0 - $a / 127.0;

                $gdA = (int) round((1.0 - $neu) * 127.0);
                if ($gdA < 0) {
                    $gdA = 0;
                } elseif ($gdA > 127) {
                    $gdA = 127;
                }

                imagesetpixel($image, $x, $y, imagecolorallocatealpha($image, $r, $g, $b, $gdA));
            }
        }
    }

    /** Weiche Blende zwischen zwei Schwellen - wie GLSL smoothstep. */
    private static function smoothstep(float $e0, float $e1, float $x): float
    {
        $t = ($x - $e0) / ($e1 - $e0);
        if ($t < 0.0) {
            $t = 0.0;
        }
        if ($t > 1.0) {
            $t = 1.0;
        }

        return $t * $t * (3.0 - 2.0 * $t);
    }

    /**
     * SVG entschaerfen.
     *
     * Im <img> laeuft ohnehin kein Skript; das hier ist die zweite Reihe fuer
     * den Fall, dass die Datei doch einmal direkt aufgerufen wird.
     */
    private static function cleanSvg(string $svg): ?string
    {
        if (mb_strlen($svg) > 400 * 1024 || !str_contains(strtolower($svg), '<svg')) {
            return null;
        }

        $svg = preg_replace('#<script\b.*?</script>#is', '', $svg) ?? '';
        $svg = preg_replace('#<foreignObject\b.*?</foreignObject>#is', '', $svg) ?? '';
        $svg = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $svg) ?? '';
        $svg = preg_replace('/(href|xlink:href)\s*=\s*("|\')\s*(javascript|data):[^"\']*("|\')/i', '', $svg) ?? '';
        $svg = preg_replace('#<!ENTITY.*?>#is', '', $svg) ?? '';

        return trim($svg) === '' ? null : $svg;
    }

    /**
     * Mehrere Dateien eines Formularfelds (multiple).
     *
     * @return list<string>
     */
    public static function storeMany(string $field, string $folder, int $max = 60): array
    {
        $files = $_FILES[$field] ?? null;
        if (!is_array($files) || !isset($files['name'])) {
            return [];
        }

        $urls = [];
        $names = (array) $files['name'];

        foreach (array_keys($names) as $i) {
            if (count($urls) >= $max) {
                break;
            }
            $url = self::store([
                'name'     => (string) ($files['name'][$i] ?? ''),
                'type'     => (string) ($files['type'][$i] ?? ''),
                'tmp_name' => (string) ($files['tmp_name'][$i] ?? ''),
                'error'    => (int) ($files['error'][$i] ?? UPLOAD_ERR_NO_FILE),
                'size'     => (int) ($files['size'][$i] ?? 0),
            ], $folder);

            if ($url !== null) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    /** Datei löschen – nur innerhalb des Upload-Ordners. */
    public static function delete(string $url): void
    {
        $prefix = '/' . trim(Config::str('upload_dir', 'uploads'), '/') . '/';
        if (!str_starts_with($url, $prefix)) {
            return;
        }

        $relative = substr($url, strlen($prefix));
        // Kein Ausbrechen aus dem Ordner
        if (str_contains($relative, '..')) {
            return;
        }

        $path = self::dir() . '/' . $relative;
        if (is_file($path)) {
            @unlink($path);
        }

        // Sonst bleibt das Original als Karteileiche liegen – und Originale
        // sind das Vielfache dessen, was das verkleinerte Bild wiegt.
        $original = self::originalPath($url);
        if ($original !== null) {
            @unlink($original);
        }
    }
}
