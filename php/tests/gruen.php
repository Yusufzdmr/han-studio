<?php
declare(strict_types=1);

use Atelier\Design;

/*
 * Freistellen: ein Film, dessen Grund der Browser herausnimmt.
 *
 * Gemeldet am 02.10.2026 mit einem Handyvideo vom Bildschirm: ein Rosenbogen
 * als Greenscreen-.mov, und in der Karte stand eine gruene Flaeche statt des
 * Papiers. Am selben Tag der naechste Film: H.264 ohne Alpha, innen 0,0,0 -
 * die Karte zeigte Schwarz statt ihrer Farbe. Ein webm mit Alpha waere auf
 * dem iPhone schwarz geblieben, HEVC mit Alpha auf Android. Also waehlt der
 * Grafiker an der Ebene, was weg soll, und gruen.js stanzt es im Browser -
 * eine Datei, jedes Geraet.
 */

/* --- Das Feld gibt es, und es ist leer, bis jemand etwas waehlt --- */

$film = Design::completeElement(['id' => 'bogen', 'type' => 'video', 'src' => '/uploads/designs/b.mov']);
assert_same('', $film['stanze'], 'stanze: ohne Angabe leer');

$schwarz = Design::completeElement(['id' => 'bogen', 'type' => 'video', 'stanze' => 'schwarz']);
assert_same('schwarz', $schwarz['stanze'], 'stanze: schwarz bleibt');

$quatsch = Design::completeElement(['id' => 'bogen', 'type' => 'video', 'stanze' => 'blau']);
assert_same('', $quatsch['stanze'], 'stanze: Unbekanntes faellt weg');

$bild = Design::completeElement(['id' => 'blatt', 'type' => 'image', 'stanze' => 'schwarz']);
assert_same('', $bild['stanze'], 'stanze: ein Bild hat nichts zum Stanzen');

// Einen Tag lang war es ein Haken namens gruen.
$alt = Design::completeElement(['id' => 'bogen', 'type' => 'video', 'gruen' => true]);
assert_same('gruen', $alt['stanze'], 'stanze: der alte Haken wird zu gruen');
assert_true(!array_key_exists('gruen', $alt), 'stanze: und das alte Feld verschwindet');

/* --- Im Markup steht es als Marke am Film --- */

$doc = ['id' => 'x', 'layers' => [
    ['id' => 'bogen', 'type' => 'video', 'src' => '/uploads/designs/b.mov', 'stanze' => 'schwarz'],
    ['id' => 'rauch', 'type' => 'video', 'src' => '/uploads/designs/r.webm'],
]];
$html = Design::html($doc, [], 'de');

assert_contains($html, '<video class="d-el d-el-bogen d-spot-card" src="/uploads/designs/b.mov" data-stanze="schwarz"',
    'stanze: der Film traegt die Marke');
assert_contains($html, 'src="/uploads/designs/r.webm" muted', 'stanze: der andere Film nicht');

/* --- Das Formular --- */

$basis = Design::complete(['id' => 'p', 'slug' => 'p', 'layers' => [
    ['id' => 'bogen', 'type' => 'video', 'spot' => 'card', 'stanze' => 'gruen'],
    ['id' => 'blatt', 'type' => 'image', 'spot' => 'card'],
]]);

$neu = Design::fromPost($basis, ['stanze_bogen' => 'schwarz', 'stanze_blatt' => 'schwarz']);
assert_same('schwarz', $neu['layers'][0]['stanze'], 'stanze: die Auswahl landet am Film');
assert_same('', $neu['layers'][1]['stanze'], 'stanze: am Bild nicht');

$aus = Design::fromPost($basis, ['stanze_bogen' => '']);
assert_same('', $aus['layers'][0]['stanze'], 'stanze: "Nein" schaltet aus');

$ohne = Design::fromPost($basis, []);
assert_same('gruen', $ohne['layers'][0]['stanze'], 'stanze: ein Formular ohne das Feld laesst es stehen');

/* --- Das Panel fragt danach, und jede Seite mit Ebenen laedt das Skript --- */

$panel = (string) file_get_contents(__DIR__ . '/../templates/admin/design-edit-sections.php');
assert_contains($panel, 'name="stanze_<?= e($ebene[\'id\']) ?>"', 'Panel: die Auswahl steht bei den Videos');

foreach ([
    'src/Controllers/DesignController.php',
    'src/Controllers/InviteV2Controller.php',
    'src/Controllers/DesignAdminController.php',
] as $datei) {
    $quelle = (string) file_get_contents(__DIR__ . '/../' . $datei);
    assert_contains($quelle, "'/assets/gruen.js'", $datei . ': laedt gruen.js');
}

$skript = (string) file_get_contents(__DIR__ . '/../public/assets/gruen.js');
assert_contains($skript, 'video[data-stanze]', 'gruen.js: sucht die Marke, die Design::html schreibt');
assert_contains($skript, '"schwarz"', 'gruen.js: kennt den schwarzen Grund');

// gruen.js nimmt dem Film die Klasse d-el ab und gibt sie der Leinwand.
// invitation.js muss ihn trotzdem noch finden, sonst startet er nie.
$einladung = (string) file_get_contents(__DIR__ . '/../public/assets/invitation.js');
assert_contains($einladung, 'video.d-el, video.d-gruen-film', 'invitation.js: startet auch den gestanzten Film');
