<?php
declare(strict_types=1);

use Atelier\Design;

/*
 * Eine Schriftmarke wieder loswerden.
 *
 * Gemeldet am 03.10.2026: "mehmed diye yeni yazi tipi olusturdum ama
 * sabitlendi oraya silinmiyor". fromPost() konnte eine Marke anlegen und
 * aendern, aber nie entfernen - ein Tippfehler in der Kennung blieb fuer
 * immer in der Vorlage stehen.
 *
 * Geloescht wird am Ende von fromPost(): dasselbe Formular schickt die
 * Schriftwahl jeder Ebene und jedes Abschnitts mit, und die zeigt vielleicht
 * noch auf die Marke. Wer sie mitloescht, laesst diese Stellen erben, statt
 * sie auf eine Variable ohne Wert zeigen zu lassen.
 */

$basis = Design::complete([
    'id' => 'p', 'slug' => 'p',
    'fonts' => [
        'display' => ['family' => 'Playfair Display'],
        'mehmed'  => ['family' => 'Great Vibes'],
    ],
    'typo' => ['title' => ['font' => 'mehmed']],
    'layers' => [
        ['id' => 'namen', 'type' => 'text', 'spot' => 'card', 'style' => ['font' => 'mehmed']],
        ['id' => 'ort', 'type' => 'text', 'spot' => 'card', 'style' => ['font' => 'display']],
    ],
]);

assert_true(isset($basis['fonts']['mehmed']), 'Schrift: die Marke steht vorher da');

// Das Formular schickt die Ebene noch mit der alten Marke - wie im Panel.
$weg = Design::fromPost($basis, ['font_loesch_mehmed' => '1', 'style_font_namen' => 'mehmed']);

assert_true(!isset($weg['fonts']['mehmed']), 'Schrift: der Haken loescht die Marke');
assert_true(isset($weg['fonts']['display']), 'Schrift: die anderen bleiben');
assert_same('', $weg['layers'][0]['style']['font'], 'Schrift: die Ebene erbt jetzt');
assert_same('display', $weg['layers'][1]['style']['font'], 'Schrift: eine andere Ebene bleibt bei ihrer');
assert_same('', $weg['typo']['title']['font'], 'Schrift: die Textrolle erbt jetzt');
assert_same([], array_values(array_filter(Design::warnings($weg), static fn (array $w): bool => $w['kind'] === 'unknown_font')),
    'Schrift: und keine Warnung ueber eine verschwundene Marke');

$bleibt = Design::fromPost($basis, []);
assert_true(isset($bleibt['fonts']['mehmed']), 'Schrift: ohne Haken bleibt sie');

$panel = (string) file_get_contents(__DIR__ . '/../templates/admin/design-edit-sections.php');
assert_contains($panel, 'name="font_loesch_<?= e($marke) ?>"', 'Panel: jede Marke hat ihren Loeschhaken');
