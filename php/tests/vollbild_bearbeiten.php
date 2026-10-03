<?php
declare(strict_types=1);

use Atelier\Controllers\InviteV2Controller;

/*
 * Das Vollbild auch auf der Bearbeiten-Seite (03.10.2026, "ekle").
 *
 * Dort steht schon etwas auf der Platte: Fotos und Musik der Abschnitte.
 * Beim Speichern traegt mitDateien() sie hinueber - und LOESCHT, was als
 * "weg" angekreuzt ist. Eine Vorschau darf das nicht: wer den Haken setzt
 * und sich die Seite nur ansieht, haette sein Foto verloren. Also eine
 * eigene, reine Fassung: dieselbe Auswahl, keine Platte.
 */

$darf = ['sections' => [
    'musik'   => ['inputs' => ['track' => ['type' => 'audio']]],
    'galerie' => ['inputs' => ['photos' => ['type' => 'photos', 'max' => 6]]],
]];
$alt = ['sections' => [
    'musik'   => ['track' => '/uploads/einladungen/v2/p/lied.mp3'],
    'galerie' => ['photos' => ['/uploads/einladungen/v2/p/a.jpg', '/uploads/einladungen/v2/p/b.jpg']],
]];

$bleibt = InviteV2Controller::bisherigeDateien([], $darf, $alt, []);
assert_same('/uploads/einladungen/v2/p/lied.mp3', $bleibt['sections']['musik']['track'], 'Vorschau: die Musik bleibt');
assert_same(['/uploads/einladungen/v2/p/a.jpg', '/uploads/einladungen/v2/p/b.jpg'], $bleibt['sections']['galerie']['photos'],
    'Vorschau: die Fotos bleiben');

$weg = InviteV2Controller::bisherigeDateien([], $darf, $alt, [
    'sec_ton_weg_musik'     => '1',
    'sec_photo_weg_galerie' => ['/uploads/einladungen/v2/p/a.jpg'],
]);
assert_true(!isset($weg['sections']['musik']['track']), 'Vorschau: abgewaehlte Musik fehlt in der Vorschau');
assert_same(['/uploads/einladungen/v2/p/b.jpg'], $weg['sections']['galerie']['photos'], 'Vorschau: abgewaehltes Foto fehlt');

// Und die Dateien selbst? Die Funktion kennt Media gar nicht.
$c = (string) file_get_contents(__DIR__ . '/../src/Controllers/InviteV2Controller.php');
$rein = substr($c, (int) strpos($c, 'public static function bisherigeDateien'), 1800);
assert_not_contains($rein, 'Media::', 'Vorschau: bisherigeDateien fasst keine Datei an');

// Die Bearbeiten-Seite beantwortet die Anfrage, bevor sie irgendetwas speichert.
$anfang = (int) strpos($c, 'public function edit(array');
$ganz   = strpos($c, "=== 'ganzseite'", $anfang);
$saveIt = strpos($c, '$this->saveEdit(', $anfang);
assert_true($ganz !== false && $saveIt !== false && $ganz < $saveIt, 'Bearbeiten: ganzseite vor saveEdit');

$seite = (string) file_get_contents(__DIR__ . '/../templates/pages/invite-v2-edit.php');
assert_contains($seite, "View::partial('partials/vollbild-vorschau')", 'Bearbeiten: der Knopf steht unter der Karte');
$teil = (string) file_get_contents(__DIR__ . '/../templates/partials/vollbild-vorschau.php');
assert_contains($teil, 'data-ganz-zeigen', 'Bearbeiten: der Knopf');
assert_contains($teil, 'data-vollbild hidden', 'Bearbeiten: die Ebene ist zu, bis jemand klickt');
