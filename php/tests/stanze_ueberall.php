<?php
declare(strict_types=1);

use Atelier\Design;

/*
 * Freistellen ueberall, wo ein Film stehen kann - nicht nur an den Ebenen.
 *
 * "mov ile yaptigimiz her sey tum alanlara gelebiliyor dimi ... countdown
 * sayfasinda falan" (03.10.2026). Bis dahin nur in 5b: ein Zeichen, ein
 * Countdown-Schmuck oder ein Abschnittsschmuck mit schwarzem Grund stand als
 * schwarzes Kaestchen da. gruen.js kann jeden Film stanzen, der data-stanze
 * traegt - es fehlte nur das Feld.
 */

/* --- Zeichen (3c) --- */

$icons = Design::icons(['icons' => ['pasta' => ['video' => '/uploads/designs/t.mov', 'stanze' => 'schwarz']]]);
assert_same('schwarz', $icons['pasta']['stanze'], 'Zeichen: stanze bleibt');

$bildIcon = Design::icons(['icons' => ['pasta' => ['src' => '/uploads/designs/t.png', 'stanze' => 'schwarz']]]);
assert_same('', $bildIcon['pasta']['stanze'], 'Zeichen: ein Bild hat nichts zu stanzen');

$quatsch = Design::icons(['icons' => ['pasta' => ['video' => '/uploads/designs/t.mov', 'stanze' => 'lila']]]);
assert_same('', $quatsch['pasta']['stanze'], 'Zeichen: Unbekanntes faellt weg');

$basis = Design::complete(['id' => 'p', 'slug' => 'p']);
$post = Design::fromPost($basis, ['icons_da' => '1', 'icon_src_pasta' => '/uploads/designs/t.mov', 'icon_stanze_pasta' => 'gruen']);
assert_same('gruen', Design::icons($post)['pasta']['stanze'], 'Zeichen: aus dem Formular');

/* --- Countdown- und Abschnittsschmuck: dieselbe Liste (freieElemente) --- */

$zeilen = Design::freieElemente([
    ['video' => '/uploads/designs/a.mov', 'anchor' => 'days', 'stanze' => 'schwarz'],
    ['src' => '/uploads/designs/b.png', 'anchor' => 'days', 'stanze' => 'schwarz'],
], ['days']);
assert_same('schwarz', $zeilen[0]['stanze'], 'Schmuck: stanze am Film');
assert_same('', $zeilen[1]['stanze'], 'Schmuck: nicht am Bild');

$cd = Design::fromPost($basis, [
    'cdicons_da' => '1', 'cd_n_default' => '1',
    'cd_default_0_src' => '/uploads/designs/a.mov', 'cd_default_0_stanze' => 'schwarz',
]);
$cdZeilen = $cd['countdownIcons']['default'] ?? [];
assert_same('schwarz', (string) ($cdZeilen[0]['stanze'] ?? ''), 'Countdown: aus dem Formular');

/* --- Gedruckt: der Film traegt die Marke, wie bei den Ebenen --- */

$quelle = (string) file_get_contents(__DIR__ . '/../src/DesignSections.php');
assert_true(substr_count($quelle, "data-stanze=\"' . e(") >= 2, 'DesignSections: Zeichen und Schmuck schreiben data-stanze');

/* --- Das Panel fragt danach --- */

$panel = (string) file_get_contents(__DIR__ . '/../templates/admin/design-edit-sections.php');
assert_contains($panel, "'icon_stanze_'", 'Panel: Zeichen');
assert_contains($panel, "'cd_' . \$gestalt . '_' . \$i . '_stanze'", 'Panel: Countdown');
$tafeln = (string) file_get_contents(__DIR__ . '/../templates/admin/design-edit-tafeln.php');
assert_contains($tafeln, "\$dn . 'stanze'", 'Panel: Abschnittsschmuck');

/* --- gruen.js gibt der Leinwand die Marken des Films mit --- */

$js = (string) file_get_contents(__DIR__ . '/../public/assets/gruen.js');
assert_contains($js, 'film.attributes', 'gruen.js: kopiert die data-Marken auf die Leinwand');

/* --- Und wirklich gezeichnet: ein Countdown mit einem schwarzgrundigen Film --- */

$uhr = \Atelier\DesignSections::complete([
    'id' => 'probe', 'slug' => 'probe',
    'countdownIcons' => ['uhr' => [['video' => '/uploads/designs/kerze.mov', 'anchor' => 'days', 'stanze' => 'schwarz']]],
    'sections' => [['id' => 'zeit', 'type' => 'countdown', 'variant' => 'uhr']],
]);
$uhrHtml = \Atelier\DesignSections::html($uhr, ['date' => '2099-06-20', 'time' => '15:00'], 'de', '2026-01-01');
assert_contains($uhrHtml, 'src="/uploads/designs/kerze.mov" data-stanze="schwarz"', 'Countdown: der Film traegt data-stanze');
