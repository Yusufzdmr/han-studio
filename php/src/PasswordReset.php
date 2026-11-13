<?php
declare(strict_types=1);

namespace Atelier;

/**
 * "Şifremi unuttum" akışı — token tabanlı.
 *
 * Eski yol (parolayı anında değiştir + düz metin maille) tehlikeliydi: mail
 * gitmezse kullanıcı kilitlenir. Yeni yol: tek-kullanımlık token üretilir,
 * e-posta yoluyla linkin kendisi gönderilir. Parola ancak kullanıcı linke
 * tıklayıp yeni parolayı girince değişir.
 *
 * - Token DB'de SHA-256 hash olarak saklanır; çerez gibi düz metin bırakmıyoruz.
 * - 60 dakika geçerli, tek kullanımlık — kullandıktan sonra silinir.
 * - Tür alanı var: 'admin' ve 'albumist' ayrımı — admin linki albümcü için işe yaramaz.
 */
final class PasswordReset
{
    private const TTL = 3600; // 60 dakika

    /**
     * Yeni token üret, DB'ye kaydet, düz metni döndür (mail içinde yollanacak).
     * Çağıran mail gönderimi yapar — mail başarısızsa token yine durur ama
     * kimse elinde değildir, zaten 60 dk sonra silinir.
     */
    public static function create(string $kind): string
    {
        self::ensureTable();
        self::cleanupExpired();

        $raw = bin2hex(random_bytes(32));
        $hash = hash('sha256', $raw);
        $expires = date('Y-m-d H:i:s', time() + self::TTL);

        Db::run(
            'INSERT INTO password_resets (token_hash, kind, expires_at) VALUES (?, ?, ?)',
            [$hash, $kind, $expires]
        );

        return $raw;
    }

    /**
     * Token geçerliyse türünü ("admin"|"albumist") döndürür; değilse null.
     * Tüketmez — sadece bakar. GET isteğinde form gösterirken kullanılır.
     */
    public static function peek(string $token): ?string
    {
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            return null;
        }

        try {
            $hash = hash('sha256', $token);
            $row = Db::one(
                'SELECT kind FROM password_resets WHERE token_hash = ? AND expires_at > NOW()',
                [$hash]
            );
            return $row === null ? null : (string) $row['kind'];
        } catch (\Throwable $_) {
            return null;
        }
    }

    /**
     * Token geçerliyse türünü döndür VE sil (tek kullanım). POST isteğinde,
     * yeni parolayı kaydettikten hemen önce çağır — başarı dönerse kaydet,
     * aksi halde "token geçersiz" göster.
     */
    public static function consume(string $token): ?string
    {
        $kind = self::peek($token);
        if ($kind === null) {
            return null;
        }

        try {
            Db::run(
                'DELETE FROM password_resets WHERE token_hash = ?',
                [hash('sha256', $token)]
            );
        } catch (\Throwable $_) {
            // Silme hatasını yutma — en kötü ihtimalde token tekrar kullanılabilir,
            // 60 dk sonra kendi kendine siliniyor.
        }

        return $kind;
    }

    private static function cleanupExpired(): void
    {
        try {
            Db::run('DELETE FROM password_resets WHERE expires_at < NOW()');
        } catch (\Throwable $_) {
        }
    }

    private static function ensureTable(): void
    {
        Db::run(
            'CREATE TABLE IF NOT EXISTS password_resets (
                token_hash CHAR(64) NOT NULL PRIMARY KEY,
                kind       VARCHAR(16) NOT NULL,
                expires_at DATETIME NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX pw_reset_expires (expires_at)
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }
}
