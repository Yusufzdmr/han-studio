<?php
declare(strict_types=1);

namespace Atelier;

/**
 * Giriş parolaları: admin ve albümcü. Panelden değiştirilebilir, böylece
 * WebFTP ile config.php açmaya gerek kalmıyor.
 *
 * Aynı kural Integrations ile aynı: panelde bir değer varsa o geçer, yoksa
 * config.php'ye düşer. Böylece ilk kurulumda config.php çalışır, panelden
 * değiştirince DB kazanır. Hash olarak saklıyoruz — config.php düz metin
 * kabul ediyor diye DB'de de düz metin tutmak için sebep yok.
 */
final class Credentials
{
    /** @var array<string,string>|null */
    private static ?array $cache = null;

    /** @return array{admin_key:string,albumist_user:string,albumist_key:string,admin_email:string} */
    private static function all(): array
    {
        if (self::$cache !== null) {
            /** @var array{admin_key:string,albumist_user:string,albumist_key:string,admin_email:string} */
            return self::$cache;
        }

        $row = [];
        try {
            $raw = Db::json('SELECT data FROM credentials WHERE id = 1');
            if (is_array($raw)) {
                $row = $raw;
            }
        } catch (\Throwable $_) {
            // Tablo henüz oluşturulmamış: panel eski config.php üzerinden çalışmaya devam eder.
        }

        self::$cache = [
            'admin_key'     => (string) ($row['admin_key'] ?? ''),
            'albumist_user' => (string) ($row['albumist_user'] ?? ''),
            'albumist_key'  => (string) ($row['albumist_key'] ?? ''),
            'admin_email'   => (string) ($row['admin_email'] ?? ''),
        ];
        return self::$cache;
    }

    /**
     * Panelde kayıtlı admin anahtarı (hash); yoksa config.php'deki değer.
     * Dönen değer `password_verify` ile kontrol edilebilecek bir dize — hash veya
     * düz metin olabilir. Admin::login hangisinin olduğunu kendi anlar.
     */
    public static function adminKey(): string
    {
        $stored = self::all()['admin_key'];
        return $stored !== '' ? $stored : Config::str('admin_key', '');
    }

    public static function albumistUser(): string
    {
        $stored = self::all()['albumist_user'];
        return $stored !== '' ? $stored : Config::str('albumist_user', '');
    }

    public static function albumistKey(): string
    {
        $stored = self::all()['albumist_key'];
        return $stored !== '' ? $stored : Config::str('albumist_key', '');
    }

    /** Panelde kayıt var mı? Uyarı metni için. */
    public static function hasStoredAdminKey(): bool
    {
        return self::all()['admin_key'] !== '';
    }

    public static function hasStoredAlbumistKey(): bool
    {
        return self::all()['albumist_key'] !== '';
    }

    /**
     * Admin bildirimlerinin gideceği e-posta. Panelde set edilen değer kazanır,
     * yoksa config.php'deki `mail_to`. Mail::toStudio bunu okuyor.
     */
    public static function adminEmail(): string
    {
        $stored = self::all()['admin_email'];
        return $stored !== '' ? $stored : Config::str('mail_to', '');
    }

    public static function hasStoredAdminEmail(): bool
    {
        return self::all()['admin_email'] !== '';
    }

    /**
     * Parolayı güvenli biçimde saklar. Boş parola = alanı değiştirme.
     *
     * Kullanıcı adı boş gelirse de dokunulmaz — "sadece admin parolasını değiştir"
     * gibi kısmi güncellemeler bu sayede mümkün. Admin e-posta ayrı argüman.
     */
    public static function save(?string $adminKey, ?string $albumistUser, ?string $albumistKey, ?string $adminEmail = null): void
    {
        $current = self::all();

        if ($adminKey !== null && $adminKey !== '') {
            $current['admin_key'] = password_hash($adminKey, PASSWORD_DEFAULT);
        }
        if ($albumistUser !== null && trim($albumistUser) !== '') {
            $current['albumist_user'] = trim($albumistUser);
        }
        if ($albumistKey !== null && $albumistKey !== '') {
            $current['albumist_key'] = password_hash($albumistKey, PASSWORD_DEFAULT);
        }
        if ($adminEmail !== null && trim($adminEmail) !== '') {
            $current['admin_email'] = trim($adminEmail);
        }

        self::ensureTable();

        Db::run(
            'INSERT INTO credentials (id, data) VALUES (1, ?) ON DUPLICATE KEY UPDATE data = VALUES(data)',
            [Db::encode($current)]
        );
        self::$cache = $current;
    }

    /**
     * Canlıda schema.sql'i çalıştırma erişimi olmayabilir (SSH yok, phpMyAdmin
     * zahmetli). İlk kaydetmede tablo yoksa kendimiz oluşturuyoruz — idempotent,
     * her çağrıda güvenli.
     */
    private static function ensureTable(): void
    {
        Db::run(
            'CREATE TABLE IF NOT EXISTS credentials (
                id         TINYINT UNSIGNED NOT NULL PRIMARY KEY DEFAULT 1,
                data       LONGTEXT NOT NULL CHECK (JSON_VALID(data)),
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }
}
