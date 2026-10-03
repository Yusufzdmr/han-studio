<?php
/**
 * "Arka planı şeffaf yap" fuer einen Film: nichts, schwarzer oder gruener
 * Grund. Eine Liste fuer jede Stelle, an der ein Film stehen kann - die
 * Videoebenen (5b) haben ihre eigene, laengere Fassung mit Hinweis.
 *
 * Steht immer da, auch neben einem Bild: das Feld daneben nimmt beides, und
 * ob es ein Film wird, entscheidet erst die Datei. An einem Bild ignoriert
 * Design::stanze() den Wert.
 *
 * @var string $name
 * @var string $wert
 * @var bool   $tr
 * @var string $label
 * @var string $feld
 */

use function Atelier\e;
?>
<label class="<?= $label ?>"><?= $tr ? 'video ise arka planı şeffaf yap' : 'bei einem Film: Grund durchsichtig' ?>
  <select name="<?= e($name) ?>" class="<?= $feld ?>">
    <?php foreach ([
        ''        => $tr ? 'hayır' : 'nein',
        'schwarz' => $tr ? 'siyah zemin silinsin' : 'schwarzen Grund entfernen',
        'gruen'   => $tr ? 'yeşil ekran silinsin' : 'Greenscreen entfernen',
    ] as $w => $wort) : ?>
      <option value="<?= e($w) ?>" <?= $wert === $w ? 'selected' : '' ?>><?= e($wort) ?></option>
    <?php endforeach; ?>
  </select></label>
