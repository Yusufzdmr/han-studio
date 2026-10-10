<?php
/**
 * "Arka planı şeffaf yap": nichts, schwarzer oder gruener Grund. Eine Liste
 * fuer jede Stelle, deren Dateifeld Bild ODER Film nimmt - die Ebenen (5,
 * 5b) haben ihre eigene, laengere Fassung mit Hinweis.
 *
 * Beim Film stanzt gruen.js im Browser, jedes Mal. Beim Bild stanzt
 * Media::freistellen() einmal beim Hochladen - deshalb wirkt eine geaenderte
 * Wahl an einem Bild erst, wenn es neu hochgeladen wird.
 *
 * @var string $name
 * @var string $wert
 * @var bool   $tr
 * @var string $label
 * @var string $feld
 */

use function Atelier\e;
?>
<label class="<?= $label ?>"><?= $tr ? 'arka planı şeffaf yap' : 'Grund durchsichtig machen' ?>
  <select name="<?= e($name) ?>" class="<?= $feld ?>">
    <?php foreach ([
        ''        => $tr ? 'hayır' : 'nein',
        'schwarz' => $tr ? 'siyah zemin silinsin' : 'schwarzen Grund entfernen',
        'gruen'   => $tr ? 'yeşil ekran silinsin' : 'Greenscreen entfernen',
    ] as $w => $wort) : ?>
      <option value="<?= e($w) ?>" <?= $wert === $w ? 'selected' : '' ?>><?= e($wort) ?></option>
    <?php endforeach; ?>
  </select>
  <span class="mt-1 block text-[0.7rem] normal-case tracking-normal opacity-70">
    <?= $tr ? 'resimde: seçtikten sonra resmi yeniden yükleyin' : 'bei einem Bild: danach neu hochladen' ?>
  </span></label>
