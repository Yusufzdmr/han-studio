<?php
declare(strict_types=1);

use Atelier\Design;

/*
 * Yeşil ekran: ein Film, dessen Gruen der Browser herausnimmt.
 *
 * Gemeldet am 02.10.2026 mit einem Handyvideo vom Bildschirm: ein Rosenbogen
 * als Greenscreen-.mov, und in der Karte stand eine gruene Flaeche statt des
 * Papiers. Ein Film mit echtem Alphakanal (webm) war vorher durchsichtig
 * gewesen - der hier hat keinen, das Gruen IST das Bild. Also schaltet der
 * Grafiker an der Ebene "yeşili sil" ein, und gruen.js stanzt es im Browser.
 */

/* --- Das Feld gibt es, und es ist aus, bis jemand es einschaltet --- */

$film = Design::completeElement(['id' => 'bogen', 'type' => 'video', 'src' => '/uploads/designs/b.mov']);
assert_same(false, $film['gruen'], 'gruen: ohne Angabe aus');

$an = Design::completeElement(['id' => 'bogen', 'type' => 'video', 'gruen' => 1]);
assert_same(true, $an['gruen'], 'gruen: wird zum Wahrheitswert');

/* --- Im Markup steht es als Marke am Film --- */

$doc = ['id' => 'x', 'layers' => [
    ['id' => 'bogen', 'type' => 'video', 'src' => '/uploads/designs/b.mov', 'gruen' => true],
    ['id' => 'rauch', 'type' => 'video', 'src' => '/uploads/designs/r.webm'],
]];
$html = Design::html($doc, [], 'de');

assert_contains($html, '<video class="d-el d-el-bogen d-spot-card" src="/uploads/designs/b.mov" data-gruen',
    'gruen: der Film traegt die Marke');
assert_contains($html, 'src="/uploads/designs/r.webm" muted', 'gruen: der andere Film nicht');

/* --- Das Formular: der Haken ist die Wahrheit, wie bei den Spiegelungen --- */

$basis = Design::complete(['id' => 'p', 'slug' => 'p', 'layers' => [
    ['id' => 'bogen', 'type' => 'video', 'spot' => 'card', 'gruen' => true],
    ['id' => 'blatt', 'type' => 'image', 'spot' => 'card'],
]]);

$ein = Design::fromPost($basis, ['gruen_bogen' => '1', 'gruen_blatt' => '1']);
assert_same(true, $ein['layers'][0]['gruen'], 'gruen: Haken an, Film an');
assert_same(false, $ein['layers'][1]['gruen'], 'gruen: ein Bild hat kein Gruen zum Stanzen');

$aus = Design::fromPost($basis, []);
assert_same(false, $aus['layers'][0]['gruen'], 'gruen: Haken weg, Film aus');

/* --- Das Panel fragt danach, und jede Seite mit Ebenen laedt das Skript --- */

$panel = (string) file_get_contents(__DIR__ . '/../templates/admin/design-edit-sections.php');
assert_contains($panel, 'name="gruen_<?= e($ebene[\'id\']) ?>"', 'Panel: der Haken steht bei den Videos');

foreach ([
    'src/Controllers/DesignController.php',
    'src/Controllers/InviteV2Controller.php',
    'src/Controllers/DesignAdminController.php',
] as $datei) {
    $quelle = (string) file_get_contents(__DIR__ . '/../' . $datei);
    assert_contains($quelle, "'/assets/gruen.js'", $datei . ': laedt gruen.js');
}

assert_true(is_file(__DIR__ . '/../public/assets/gruen.js'), 'gruen.js liegt da');

// gruen.js nimmt dem Film die Klasse d-el ab und gibt sie der Leinwand.
// invitation.js muss ihn trotzdem noch finden, sonst startet er nie.
$einladung = (string) file_get_contents(__DIR__ . '/../public/assets/invitation.js');
assert_contains($einladung, 'video.d-el, video.d-gruen-film', 'invitation.js: startet auch den gestanzten Film');
