<?php
/**
 * Albümcü girişi. Admin girişiyle aynı görsel desen, ayrı bir bölüm.
 *
 * @var string $locale
 * @var bool $error
 * @var string $csrf
 */

use function Atelier\e;
use Atelier\Http;
use Atelier\I18n;

$de = $locale === 'de';
$flash = (string) ($_GET['parola'] ?? '');
?>
<div class="mx-auto max-w-sm px-5 py-14 sm:py-20">
  <div class="border border-sand-deep p-8">
    <h2 class="font-display text-xl text-ink"><?= $de ? 'Albumhersteller-Login' : 'Albümcü girişi' ?></h2>
    <p class="mt-2 text-[0.82rem] leading-relaxed text-muted">
      <?= $de
          ? 'Dieser Bereich ist nicht öffentlich. Bitte anmelden.'
          : 'Bu bölüm herkese açık değildir. Lütfen giriş yapın.' ?>
    </p>

    <?php if ($flash === 'gonderildi') : ?>
      <p class="mt-5 border border-gold/60 bg-gold/10 px-4 py-3 text-[0.82rem] text-ink">
        <?= $de
            ? 'Ein neues Passwort wurde per E-Mail verschickt. Posteingang prüfen.'
            : 'Yeni parola e-posta ile gönderildi. Gelen kutunuzu kontrol edin.' ?>
      </p>
    <?php elseif ($flash === 'gec') : ?>
      <p class="mt-5 border border-red-700/60 bg-red-100/40 px-4 py-3 text-[0.82rem] text-ink">
        <?= $de
            ? 'Zu viele Versuche – bitte in einer Stunde erneut versuchen.'
            : 'Çok fazla deneme — bir saat sonra tekrar deneyin.' ?>
      </p>
    <?php elseif ($flash === 'hata') : ?>
      <p class="mt-5 border border-red-700/60 bg-red-100/40 px-4 py-3 text-[0.82rem] text-ink">
        <?= $de
            ? 'E-Mail-Versand fehlgeschlagen.'
            : 'E-posta gönderilemedi.' ?>
      </p>
    <?php endif; ?>

    <form method="post" class="mt-7 space-y-6">
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

      <div>
        <label class="block text-[0.6rem] uppercase tracking-[0.18em] text-muted" for="user">
          <?= $de ? 'Benutzername' : 'Kullanıcı adı' ?>
        </label>
        <input id="user" name="user" type="text" required autocomplete="username" autofocus
               class="w-full border-b border-sand-deep bg-transparent px-0 py-2.5 text-[0.92rem] text-ink outline-none focus:border-gold">
      </div>

      <div>
        <label class="block text-[0.6rem] uppercase tracking-[0.18em] text-muted" for="password">
          <?= $de ? 'Passwort' : 'Parola' ?>
        </label>
        <div class="relative">
          <input id="password" name="password" type="password" required autocomplete="current-password"
                 class="w-full border-b border-sand-deep bg-transparent px-0 py-2.5 pr-10 text-[0.92rem] text-ink outline-none focus:border-gold">
          <button type="button" id="pw-toggle" aria-label="<?= $de ? 'Passwort anzeigen' : 'Parolayı göster' ?>"
                  class="absolute right-0 top-1/2 -translate-y-1/2 px-2 py-1 text-[0.68rem] uppercase tracking-[0.14em] text-muted hover:text-gold">
            <?= $de ? 'zeigen' : 'göster' ?>
          </button>
        </div>
      </div>

      <?php if ($error) : ?>
        <p class="text-sm text-red-700"><?= $de ? 'Zugangsdaten falsch.' : 'Giriş bilgileri hatalı.' ?></p>
      <?php endif; ?>

      <button class="w-full bg-ink px-7 py-3.5 text-[0.68rem] uppercase tracking-[0.2em] text-cream transition-colors hover:bg-gold">
        <?= $de ? 'Anmelden' : 'Giriş yap' ?>
      </button>
    </form>

    <form method="post" action="<?= e(I18n::path('/albumcu/parola-unuttum', $locale)) ?>" class="mt-6 text-center">
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
      <button class="text-[0.76rem] text-muted underline decoration-sand-deep underline-offset-4 hover:text-gold">
        <?= $de ? 'Passwort vergessen? Per E-Mail neu setzen' : 'Şifremi unuttum — e-posta ile yenile' ?>
      </button>
    </form>
  </div>
</div>

<script nonce="<?= e(Http::nonce()) ?>">
  (() => {
    const inp = document.getElementById('password');
    const btn = document.getElementById('pw-toggle');
    if (!inp || !btn) return;
    const labels = {
      show: <?= $de ? "'zeigen'" : "'göster'" ?>,
      hide: <?= $de ? "'verbergen'" : "'gizle'" ?>,
    };
    btn.addEventListener('click', () => {
      const show = inp.type === 'password';
      inp.type = show ? 'text' : 'password';
      btn.textContent = show ? labels.hide : labels.show;
      btn.setAttribute('aria-label', show ? btn.textContent : labels.show);
    });
  })();
</script>
