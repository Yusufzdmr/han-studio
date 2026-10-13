<?php
declare(strict_types=1);

use Atelier\Media;

/*
 * Freistellen fuer das Blatt eines Abschnitts, den eigenen Rahmen und das
 * eigene Kartenbild - und fuer ein Bild, das schon auf dem Server liegt.
 *
 * "fotograflardaki green screen duzeltmemissin" (12.10.2026): eine
 * Pergamentrolle mit Gruen drumherum als Blatt von "Wo wir feiern". Dort
 * gab es die Wahl nicht, und die Datei lag schon oben.
 */

if (!function_exists('imagecreatetruecolor') || !function_exists('imagewebp')) {
    return;
}

$ordner = 'test-stanze-' . bin2hex(random_bytes(4));
$im = imagecreatetruecolor(3, 1);
imagesetpixel($im, 0, 0, imagecolorallocate($im, 0, 255, 0));
imagesetpixel($im, 1, 0, imagecolorallocate($im, 200, 170, 120));
imagesetpixel($im, 2, 0, imagecolorallocate($im, 0, 255, 0));
imagepng($im, Media::dir($ordner) . '/rolle.png');
imagedestroy($im);

$alt = Media::url($ordner . '/rolle.png');
$neu = Media::freistellenAbgelegt($alt, $ordner, 'gruen');
assert_true($neu !== null && $neu !== $alt, 'abgelegt: eine neue Datei entsteht');
assert_true(is_file(Media::dir($ordner) . '/rolle.png'), 'abgelegt: die alte Datei bleibt liegen');

$pfad = Media::dir() . '/' . substr((string) $neu, strlen(Media::url('')));
$bild = imagecreatefromwebp($pfad);
assert_true(((imagecolorat($bild, 0, 0) >> 24) & 0x7F) > 120, 'abgelegt: Gruen wird durchsichtig');
assert_same(0, (imagecolorat($bild, 1, 0) >> 24) & 0x7F, 'abgelegt: Pergament bleibt deckend');
imagedestroy($bild);

assert_same(null, Media::freistellenAbgelegt($alt, $ordner, ''), 'abgelegt: ohne Wahl nichts');
assert_same(null, Media::freistellenAbgelegt('https://example.com/a.png', $ordner, 'gruen'), 'abgelegt: fremde Adresse nicht');
assert_same(null, Media::freistellenAbgelegt(Media::url('../config.php'), $ordner, 'gruen'), 'abgelegt: kein Ausbrechen');

foreach (glob(Media::dir($ordner) . '/*') ?: [] as $f) {
    @unlink($f);
}
@rmdir(Media::dir($ordner));

/* --- Das Panel fragt an allen drei Stellen --- */

$tafeln = (string) file_get_contents(__DIR__ . '/../templates/admin/design-edit-tafeln.php');
assert_contains($tafeln, "'sec_bg_stanze_'", 'Panel: Blatt hat die Wahl');
$feld = (string) file_get_contents(__DIR__ . '/../templates/partials/einstellung-feld.php');
assert_contains($feld, "'sec_setstanze_'", 'Panel: Rahmen/Kartenbild haben die Wahl');

$controller = (string) file_get_contents(__DIR__ . '/../src/Controllers/DesignAdminController.php');
assert_contains($controller, "'sec_bg_stanze_'", 'Controller: liest die Wahl am Blatt');
assert_contains($controller, "'sec_setstanze_'", 'Controller: liest die Wahl an Bild-Einstellungen');
