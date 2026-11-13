<?php
/**
 * Giriş parolaları: admin ve albümcü.
 *
 * Boş alan = "değiştirme". Admin parolası yanlış tuşa basma ihtimaline karşı
 * iki kez istenir; albümcü parolası tek kutuda — oraya girişi zaten albümcüye
 * telefondan okuyacaksınız.
 *
 * @var string $locale
 * @var bool $hasAdminKey
 * @var bool $hasAlbumistKey
 * @var string $albumistUser
 * @var string $csrf
 * @var string $flash
 */

use function Atelier\e;

$de = $locale === 'de';
$input = 'w-full border-b border-sand-deep bg-transparent px-0 py-2.5 text-[0.92rem] text-ink outline-none focus:border-gold';
$label = 'block text-[0.6rem] uppercase tracking-[0.18em] text-muted';
$hint = 'mt-2 text-[0.72rem] leading-relaxed text-muted';
$box = 'border border-sand-deep p-6';
?>
<div class="space-y-10">
  <div>
    <h2 class="font-display text-xl text-ink"><?= $de ? 'Zugangsdaten' : 'Parolalar' ?></h2>
    <p class="mt-2 max-w-3xl text-sm leading-relaxed text-muted">
      <?= $de
          ? 'Passwörter für die Anmeldung zum Adminbereich und für den Albümcü-Zugang. Was hier eingetragen wird, hat Vorrang vor config.php; ein leeres Feld lässt die bisherige Einstellung unverändert.'
          : 'Yönetim paneli ve albümcü girişi parolaları. Buraya yazdığınız değer config.php içindekini geçersiz kılar; alan boşsa mevcut parola değişmez.' ?>
    </p>
    <p class="<?= $hint ?>">
      <?= $de
          ? 'Passwörter werden als Hash gespeichert – im Klartext stehen sie nirgends, auch nicht in einer Sicherung. Deshalb sieht man ein gesetztes Passwort hier auch nicht zurück; vergessen heißt neu setzen.'
          : 'Parolalar hash olarak saklanır — hiçbir yerde düz metin yazmaz, yedekte bile görünmez. O yüzden kayıtlı parolayı buradan okuyamazsınız; unuttuysanız yeniden ayarlarsınız.' ?>
    </p>
  </div>

  <?php if ($flash === 'ok') : ?>
    <div class="border border-gold/60 bg-gold/10 px-5 py-3 text-sm text-ink">
      <?= $de ? 'Gespeichert.' : 'Kaydedildi.' ?>
    </div>
  <?php elseif ($flash === 'mismatch') : ?>
    <div class="border border-red-700/60 bg-red-100/40 px-5 py-3 text-sm text-ink">
      <?= $de
          ? 'Die beiden Admin-Passwörter waren nicht gleich – nichts wurde geändert.'
          : 'İki admin parolası aynı değildi — hiçbir şey değiştirilmedi.' ?>
    </div>
  <?php endif; ?>

  <form method="post" class="space-y-10" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

    <!-- ------------------------------ Admin ------------------------------ -->
    <section class="<?= $box ?>">
      <h3 class="font-display text-xl text-ink"><?= $de ? 'Admin-Passwort' : 'Yönetici parolası' ?></h3>
      <p class="<?= $hint ?>">
        <?php if ($hasAdminKey) : ?>
          <?= $de ? 'Ein Passwort ist im Panel gesetzt.' : 'Panelde kayıtlı bir parola var.' ?>
        <?php else : ?>
          <?= $de ? 'Noch keins im Panel gesetzt – es gilt der Wert aus config.php.' : 'Panelde henüz kayıt yok — config.php içindeki değer geçerli.' ?>
        <?php endif; ?>
        <?= $de
            ? ' Mindestens zwölf Zeichen; keine Leerzeichen am Rand.'
            : ' En az on iki karakter; başında/sonunda boşluk olmasın.' ?>
      </p>

      <div class="mt-6 grid gap-7 md:grid-cols-2">
        <div>
          <label class="<?= $label ?>" for="admin_key"><?= $de ? 'Neues Passwort' : 'Yeni parola' ?></label>
          <input id="admin_key" name="admin_key" type="password" autocomplete="new-password" minlength="8" class="<?= $input ?>">
        </div>
        <div>
          <label class="<?= $label ?>" for="admin_key_repeat"><?= $de ? 'Noch einmal' : 'Tekrar' ?></label>
          <input id="admin_key_repeat" name="admin_key_repeat" type="password" autocomplete="new-password" minlength="8" class="<?= $input ?>">
        </div>
      </div>
    </section>

    <!-- ----------------------------- Albümcü ----------------------------- -->
    <section class="<?= $box ?>">
      <h3 class="font-display text-xl text-ink"><?= $de ? 'Albümcü-Zugang' : 'Albümcü girişi' ?></h3>
      <p class="<?= $hint ?>">
        <?= $de
            ? 'Ein gemeinsamer Zugang für den Albumhersteller – kein eigenes Konto pro Firma. Login: /albumcu.'
            : 'Albüm yapan için ortak giriş — her firmaya ayrı hesap değil. Giriş adresi: /albumcu.' ?>
        <?php if ($hasAlbumistKey) : ?>
          <?= $de ? ' Ein Passwort ist im Panel gesetzt.' : ' Panelde kayıtlı bir parola var.' ?>
        <?php else : ?>
          <?= $de ? ' Noch keins im Panel gesetzt – es gilt der Wert aus config.php.' : ' Panelde henüz kayıt yok — config.php içindeki değer geçerli.' ?>
        <?php endif; ?>
      </p>

      <div class="mt-6 grid gap-7 md:grid-cols-2">
        <div>
          <label class="<?= $label ?>" for="albumist_user"><?= $de ? 'Benutzername' : 'Kullanıcı adı' ?></label>
          <input id="albumist_user" name="albumist_user" class="<?= $input ?>"
                 value="<?= e($albumistUser) ?>" autocomplete="off">
          <p class="<?= $hint ?>">
            <?= $de
                ? 'Standardmäßig „albumcu“. Zu ändern, falls jemand den Namen bereits kennt.'
                : 'Varsayılan „albumcu“. Adı birisi öğrendiyse değiştirebilirsiniz.' ?>
          </p>
        </div>
        <div>
          <label class="<?= $label ?>" for="albumist_key"><?= $de ? 'Neues Passwort' : 'Yeni parola' ?></label>
          <input id="albumist_key" name="albumist_key" type="password" autocomplete="new-password" minlength="6" class="<?= $input ?>">
          <p class="<?= $hint ?>">
            <?= $de
                ? 'Sagt man dem Albumhersteller einmalig – idealerweise per Telefon, nicht per E-Mail.'
                : 'Albümcüye bir kez söylersiniz — tercihen telefondan, e-postadan değil.' ?>
          </p>
        </div>
      </div>
    </section>

    <div class="flex justify-end">
      <button class="border border-ink px-6 py-3 text-[0.66rem] uppercase tracking-[0.18em] text-ink transition-colors hover:bg-ink hover:text-cream">
        <?= $de ? 'Speichern' : 'Kaydet' ?>
      </button>
    </div>
  </form>
</div>
