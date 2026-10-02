<?php
declare(strict_types=1);

use Atelier\Controllers\DesignAdminController;

/*
 * Deneme verisi: im Editor eintippen, was das Paar eintippen kann.
 *
 * "gelin ve damadin adini yazip nasil oldugunu gorebilmeliyim" /
 * "musterinin yazabildigi her seyi test edebilmeliyim"
 *
 * Gehalten wird zweierlei: die Uebersetzung in die Form einer Einladung
 * (wie InviteV2Controller::sammleAngaben), und dass die Felder NICHT zur
 * Vorlage gehoeren - mit name reisten sie beim Speichern mit, und jeder
 * Buchstabe loeste das Speichern nebenbei aus.
 */

$probe = (new ReflectionMethod(DesignAdminController::class, 'probeDaten'))->getClosure(null);

$d = $probe([
    'bride' => 'Ayşe', 'groom' => 'Mehmet', 'date' => '2027-06-05',
    'family_bride' => '', 'family_groom' => '',
    'prog_time_0' => '19:00', 'prog_title_0' => 'Nikah', 'prog_icon_0' => 'nikah',
    'prog_time_1' => '', 'prog_title_1' => '', 'prog_icon_1' => '',
    'sec' => ['dress-1' => ['code' => 'Siyah', 'note' => '']],
]);

assert_same('Ayşe', $d['bride'], 'Probe: der getippte Name kommt an');
assert_same('', $d['venue'], 'Probe: ein nicht gesendetes Feld ist leer, nicht das Beispiel');
assert_true(!isset($d['families']), 'Probe: leere Familien setzen nichts - wie beim Paar');
assert_same(1, count($d['program'] ?? []), 'Probe: eine leere Zeile des Ablaufs faellt weg');
assert_same(['code' => 'Siyah'], $d['sections']['dress-1'] ?? null, 'Probe: Abschnittsfelder unter ihrer Kennung, leere weg');

$vorlage = (string) file_get_contents(__DIR__ . '/../templates/admin/design-edit.php');
$js      = (string) file_get_contents(__DIR__ . '/../public/assets/design-editor.js');

assert_contains($vorlage, 'data-probe-kasten', 'Editor: der Kasten steht da');
assert_not_contains($vorlage, 'name="probe', 'Editor: die Felder tragen keinen name - sie gehoeren nicht zur Vorlage');
assert_contains($js, 'stopPropagation', 'Skript: Tippen im Kasten loest kein Speichern nebenbei aus');
assert_contains($js, 'data-probe-werte', 'Skript: liest die Kartenwerte aus der Vorschau');

/*
 * Der Rahmen (Telefon/Tablet/Masaustu) zeigt den Stand im Formular, nicht
 * den gespeicherten: "kaydetmeden onizlemeyi gorebilsin sonra kaydederse".
 */
$router  = (string) file_get_contents(__DIR__ . '/../public/index.php');
$steuer  = (string) file_get_contents(__DIR__ . '/../src/Controllers/DesignAdminController.php');
$seite   = substr($steuer, (int) strpos($steuer, 'public function seite('), 1400);

assert_contains($router, "/admin/designs/{slug}/seite'", 'Router: der Rahmen hat einen eigenen Weg');
assert_contains($seite, 'Design::fromPost', 'Rahmen: zeichnet aus dem Formular');
assert_not_contains($seite, 'Design::save', 'Rahmen: speichert nichts');
assert_contains($vorlage, 'data-seite-adresse', 'Editor: der Rahmen kennt den Weg');
assert_contains($js, 'srcdoc', 'Skript: legt die Seite in den Rahmen, ohne die gespeicherte Adresse');
assert_contains($js, '"rahmen-geladen"', 'Skript: meldet, wenn der neue Rahmen steht');
