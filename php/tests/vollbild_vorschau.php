<?php
declare(strict_types=1);

/*
 * Die ganze Einladung als Vollbild im Assistenten.
 *
 * "gelinle damadin o olustururken ki musterinin onizlemesi tam gorsel olsa
 * daha guzel olur, kagit gibi gozukmesin" (03.10.2026). Rechts stand nur die
 * Karte, ohne die Seite dahinter. Jetzt holt ein Knopf die echte Seite aus
 * dem Formular - ueber DIESELBE Zeichnung wie die verschickte Einladung,
 * damit Vorschau und Gast nicht auseinanderlaufen.
 */

$c = (string) file_get_contents(__DIR__ . '/../src/Controllers/InviteV2Controller.php');

assert_contains($c, "=== 'ganzseite'", 'Vollbild: der Assistent kennt die Anfrage');
assert_contains($c, '$this->zeichneEinladung($doc, $einladung[\'data\'], (string) $einladung[\'slug\'], $locale, $gesendet, false);',
    'Vollbild: die Einladung des Gastes zeichnet ueber dieselbe Funktion');
assert_contains($c, "\$this->zeichneEinladung(\$doc, \$data, 'vorschau', I18n::locale(), false, true);",
    'Vollbild: die Vorschau auch, als Vorschau');

// Eine Vorschau schreibt nichts: keine Dateien, kein Eintrag, kein Teilbild.
$vorschau = substr($c, (int) strpos($c, 'private function ganzeVorschau'), 2200);
assert_contains($vorschau, '$_FILES = [];', 'Vollbild: Dateien kommen gar nicht erst an');
assert_contains($vorschau, 'checkCsrf', 'Vollbild: nur mit Zeichen');
assert_not_contains($vorschau, 'InvitationsV2::create', 'Vollbild: legt keine Einladung an');
assert_not_contains($vorschau, 'mitDateien', 'Vollbild: und keine Dateien ab');
assert_contains($c, '$teilen = $vorschau ? [] :', 'Vollbild: kein Teilbild fuer die Vorschau');

$seite = (string) file_get_contents(__DIR__ . '/../templates/pages/invite-v2-wizard.php');
assert_contains($seite, "View::partial('partials/vollbild-vorschau')", 'Vollbild: der Knopf steht unter der Karte');
$teil = (string) file_get_contents(__DIR__ . '/../templates/partials/vollbild-vorschau.php');
assert_contains($teil, 'data-ganz-zeigen', 'Vollbild: der Knopf');
assert_contains($teil, 'data-vollbild hidden', 'Vollbild: die Ebene ist zu, bis jemand klickt');

$js = (string) file_get_contents(__DIR__ . '/../public/assets/invite-v2.js');
assert_contains($js, "daten.set('was', 'ganzseite');", 'invite-v2.js: fragt die ganze Seite an');
assert_contains($js, "removeItem('al-kuvert-offen:srcdoc')", 'invite-v2.js: das Kuvert ist jedes Mal zu');

foreach (['de', 'en', 'tr'] as $sprache) {
    $dict = require __DIR__ . '/../data/dict.php';
    assert_true(isset($dict[$sprache]['invitation2']['fullPreview']), 'dict: fullPreview auf ' . $sprache);
    assert_true(isset($dict[$sprache]['invitation2']['fullClose']), 'dict: fullClose auf ' . $sprache);
}

// Die Ebene steht im Markup in der sticky-Spalte - ihr z-index galt nur dort,
// und der feste Kopf der Seite lag darueber (03.10.2026, im Browser gesehen).
assert_contains($js, 'document.body.appendChild(ebene);', 'invite-v2.js: das Vollbild haengt direkt unter body');
