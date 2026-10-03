<?php
declare(strict_types=1);

/*
 * Eine Schriftmarke sagt, wo sie gilt - gleich bei der Marke.
 *
 * Gemeldet am 03.10.2026 (Ayhan, mit zwei Bildschirmfotos): "yazi tiplerinde
 * yazilari secince canli degisiklik olsun". Die Vorschau war nicht kaputt.
 * In "deneme" standen drei Marken (camd, camdal, mehmet), alle sechs
 * Textrollen auf "erben", und keine Marke war irgendwo gewaehlt - eine
 * Familie zu wechseln konnte nichts bewegen, weder sofort noch nach dem
 * Speichern. Die Verbindung zur Schrift war in 3b, einen Abschnitt weiter,
 * und von hier aus nicht zu sehen.
 *
 * Also stehen unter jeder Marke die sechs Rollen als Haken, und wo die Marke
 * nirgends gilt, sagt es ein Satz. Die Haken haben KEINEN Namen: sie stellen
 * nur die Liste in 3b um (typo_<rolle>_font), die bleibt die einzige
 * Wahrheit. Zwei Felder fuer dieselbe Frage liefen beim ersten gleichzeitigen
 * Aendern auseinander.
 */

$tafel = (string) file_get_contents(__DIR__ . '/../templates/admin/design-edit-sections.php');

assert_contains($tafel, 'data-marke-rollen="<?= e($marke) ?>"', 'Schriften: jede Marke hat ihre Rollenhaken');
assert_contains($tafel, '<input type="checkbox" data-marke-rolle="<?= e((string) $rolle) ?>"',
    'Schriften: ein Haken je Rolle, ohne name - er schickt nichts ab');
assert_contains($tafel, 'data-marke-hinweis="<?= e($marke) ?>"', 'Schriften: der Hinweis fuer eine Marke ohne Ort');

$editor = (string) file_get_contents(__DIR__ . '/../public/assets/design-editor.js');
assert_contains($editor, '[data-marke-rollen]', 'design-editor.js: liest die Rollenhaken');
assert_contains($editor, 'select[data-typo="\' + rolle + \'"][data-typo-feld="font"]',
    'design-editor.js: und stellt damit die Liste in 3b um');
