<?php
/**
 * "Davetiyeyi tam gor": der Knopf unter der Karte und die Vollbildebene.
 *
 * "gelinle damadin o olustururken ki musterinin onizlemesi tam gorsel olsa
 * daha guzel olur, kagit gibi gozukmesin" (03.10.2026). Im Assistenten und
 * auf der Bearbeiten-Seite dasselbe - deshalb hier und nicht zweimal.
 * invite-v2.js fuellt die Ebene mit der echten Seite
 * (InviteV2Controller::ganzeVorschau / ganzeVorschauBearbeiten).
 *
 * Von Hand gestylt und nicht mit Tailwind: die PHP-Fassung laedt ein
 * fertig gebautes style.css, kein JIT.
 */

use Atelier\I18n;
use function Atelier\e;

$t = static fn (string $key): string => I18n::t('invitation2.' . $key);
?>
<style>
.wz-ganz { display: block; width: 100%; max-width: 20rem; margin: 1.25rem auto 0; padding: 0.8rem 1rem;
           background: var(--color-ink, #1c1a17); color: var(--color-cream, #faf7f2); border: 1px solid var(--color-ink, #1c1a17);
           font-size: 0.68rem; letter-spacing: 0.16em; text-transform: uppercase; cursor: pointer; }
.wz-ganz:hover { background: transparent; color: var(--color-ink, #1c1a17); }
.wz-ganz[aria-busy="true"] { opacity: 0.6; cursor: progress; }
.wz-vollbild { position: fixed; inset: 0; z-index: 200; background: #000; }
.wz-vollbild[hidden] { display: none; }
.wz-vollbild iframe { width: 100%; height: 100%; border: 0; display: block; background: #fff; }
.wz-vollbild-zu { position: absolute; top: max(0.75rem, env(safe-area-inset-top)); right: 0.75rem; z-index: 1;
                  padding: 0.55rem 0.9rem; background: rgba(28, 26, 23, 0.82); color: #faf7f2; border: 0;
                  font-size: 0.66rem; letter-spacing: 0.16em; text-transform: uppercase; cursor: pointer; }
</style>
<?php /* type=button: er steht ausserhalb des Formulars, soll aber auch darin
         nie etwas absenden. */ ?>
<button type="button" class="wz-ganz" data-ganz-zeigen><?= e($t('fullPreview')) ?></button>
<div class="wz-vollbild" data-vollbild hidden role="dialog" aria-modal="true" aria-label="<?= e($t('fullPreview')) ?>">
  <button type="button" class="wz-vollbild-zu" data-vollbild-zu><?= e($t('fullClose')) ?> ✕</button>
</div>
