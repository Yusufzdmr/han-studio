<?php
/**
 * Albümcü parola sıfırlama — e-posta linkinden girilir.
 *
 * @var string $locale
 * @var string $token
 * @var string $status   '' | 'invalid' | 'mismatch'
 * @var string $csrf
 */

use function Atelier\e;
use Atelier\Http;

$de = $locale === 'de';
?>
<div class="mx-auto max-w-sm px-5 py-14 sm:py-20">
  <div class="border border-sand-deep p-8">
    <h2 class="font-display text-xl text-ink"><?= $de ? 'Neues Passwort setzen' : 'Yeni parola belirle' ?></h2>

    <?php if ($status === 'invalid') : ?>
      <p class="mt-5 border border-red-700/60 bg-red-100/40 px-4 py-3 text-[0.82rem] text-ink">
        <?= $de
            ? 'Dieser Link ist abgelaufen oder wurde bereits verwendet.'
            : 'Bu bağlantı süresi dolmuş ya da kullanılmış.' ?>
      </p>
    <?php else : ?>
      <p class="mt-2 text-[0.82rem] leading-relaxed text-muted">
        <?= $de
            ? 'Neues Passwort zweimal eingeben. Mindestens sechs Zeichen.'
            : 'Yeni parolayı iki kere yaz. En az altı karakter.' ?>
      </p>

      <?php if ($status === 'mismatch') : ?>
        <p class="mt-5 border border-red-700/60 bg-red-100/40 px-4 py-3 text-[0.82rem] text-ink">
          <?= $de
              ? 'Die Passwörter waren nicht gleich oder zu kurz.'
              : 'Parolalar eşleşmedi ya da çok kısa.' ?>
        </p>
      <?php endif; ?>

      <form method="post" class="mt-7 space-y-6">
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="token" value="<?= e($token) ?>">

        <div>
          <label class="block text-[0.6rem] uppercase tracking-[0.18em] text-muted" for="password">
            <?= $de ? 'Neues Passwort' : 'Yeni parola' ?>
          </label>
          <div class="relative">
            <input id="password" name="password" type="password" required autocomplete="new-password" minlength="6" autofocus
                   class="w-full border-b border-sand-deep bg-transparent px-0 py-2.5 pr-10 text-[0.92rem] text-ink outline-none focus:border-gold">
            <button type="button" data-pw-toggle="password" class="absolute right-0 top-1/2 -translate-y-1/2 px-2 py-1 text-[0.68rem] uppercase tracking-[0.14em] text-muted hover:text-gold"><?= $de ? 'zeigen' : 'göster' ?></button>
          </div>
        </div>

        <div>
          <label class="block text-[0.6rem] uppercase tracking-[0.18em] text-muted" for="password_repeat">
            <?= $de ? 'Noch einmal' : 'Tekrar' ?>
          </label>
          <div class="relative">
            <input id="password_repeat" name="password_repeat" type="password" required autocomplete="new-password" minlength="6"
                   class="w-full border-b border-sand-deep bg-transparent px-0 py-2.5 pr-10 text-[0.92rem] text-ink outline-none focus:border-gold">
            <button type="button" data-pw-toggle="password_repeat" class="absolute right-0 top-1/2 -translate-y-1/2 px-2 py-1 text-[0.68rem] uppercase tracking-[0.14em] text-muted hover:text-gold"><?= $de ? 'zeigen' : 'göster' ?></button>
          </div>
        </div>

        <button class="w-full bg-ink px-7 py-3.5 text-[0.68rem] uppercase tracking-[0.2em] text-cream transition-colors hover:bg-gold">
          <?= $de ? 'Passwort setzen' : 'Parolayı belirle' ?>
        </button>
      </form>
    <?php endif; ?>
  </div>
</div>

<script nonce="<?= e(Http::nonce()) ?>">
  (() => {
    const labels = { show: <?= $de ? "'zeigen'" : "'göster'" ?>, hide: <?= $de ? "'verbergen'" : "'gizle'" ?> };
    document.querySelectorAll('button[data-pw-toggle]').forEach((btn) => {
      const inp = document.getElementById(btn.getAttribute('data-pw-toggle'));
      if (!inp) return;
      btn.addEventListener('click', () => {
        const show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        btn.textContent = show ? labels.hide : labels.show;
      });
    });
  })();
</script>
