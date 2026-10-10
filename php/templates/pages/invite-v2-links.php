<?php
/**
 * "Links verloren": Adresse eingeben, die Links kommen per Mail.
 *
 * Die Seite weiss nicht, ob etwas gefunden wurde, und soll es auch nicht
 * wissen: sie bekommt nur, ob die Anfrage angenommen war. So kann sie es
 * nicht aus Versehen verraten (InviteV2Controller::meineLinks).
 *
 * @var string $locale
 * @var string $csrf
 * @var string $status  '' | 'gesendet' | 'email' | 'csrf'
 * @var string $email
 */

use function Atelier\e;
use Atelier\I18n;

$t = static fn (string $key): string => I18n::t('invitation2.' . $key);
?>
<?php /* pt-32 wie die Antwortenseite: fixierter Kopf, kein Hero. */ ?>
<section class="mx-auto max-w-xl px-6 pb-24 pt-32">
  <h1 class="font-display text-3xl text-ink"><?= e($t('linksTitle')) ?></h1>

  <?php if ($status === 'gesendet') : ?>
    <p class="mt-6 border border-sand-deep px-5 py-4 text-sm leading-relaxed text-ink"><?= e($t('linksSent')) ?></p>
  <?php else : ?>
    <p class="mt-4 text-sm leading-relaxed text-muted"><?= e($t('linksLead')) ?></p>

    <?php if ($status === 'email' || $status === 'csrf') : ?>
      <p class="mt-6 border border-ink px-5 py-4 text-sm text-ink">
        <?= e($t($status === 'email' ? 'errorEmail' : 'linksCsrf')) ?>
      </p>
    <?php endif; ?>

    <form method="post" class="mt-8 space-y-5">
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
      <div>
        <label class="text-[0.62rem] uppercase tracking-[0.18em] text-muted" for="f-email"><?= e($t('fieldEmail')) ?></label>
        <input id="f-email" type="email" name="email" required autocomplete="email" maxlength="160"
               value="<?= e($email) ?>"
               class="mt-2 w-full border border-sand-deep bg-cream px-4 py-3 text-sm text-ink">
      </div>
      <button type="submit" class="border border-ink px-8 py-4 text-[0.66rem] uppercase tracking-[0.16em] text-ink transition-colors hover:bg-ink hover:text-cream">
        <?= e($t('linksButton')) ?>
      </button>
    </form>
  <?php endif; ?>
</section>
