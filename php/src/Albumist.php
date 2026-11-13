<?php
declare(strict_types=1);

namespace Atelier;

/**
 * Anmeldung des Albumherstellers.
 *
 * Ein gemeinsamer Zugang aus config.php, kein eigenes Benutzerkonto pro
 * Firma – genau wie beim Adminbereich (Admin::login). Eigene Sitzung,
 * eigenes Sitzungsfeld: ein Admin-Login öffnet hier nichts, und umgekehrt.
 */
final class Albumist
{
    /** Nach so langer Untaetigkeit ist Schluss. */
    private const IDLE = 4 * 3600;

    /** Und spaetestens dann in jedem Fall, auch bei Betrieb. */
    private const LIFETIME = 12 * 3600;

    public static function isLoggedIn(): bool
    {
        Security::session();
        if (empty($_SESSION['albumist'])) {
            return false;
        }

        $now = time();
        $seen = (int) ($_SESSION['albumistSeen'] ?? 0);
        $since = (int) ($_SESSION['albumistSince'] ?? 0);

        if (($now - $seen) > self::IDLE || ($now - $since) > self::LIFETIME) {
            self::logout();
            return false;
        }

        $_SESSION['albumistSeen'] = $now;
        return true;
    }

    /** @return bool true bei erfolgreicher Anmeldung */
    public static function login(string $user, string $password): bool
    {
        if (Security::throttle('albumist-login', 8, 900)) {
            return false;
        }

        // Panelde kayıtlı değerler varsa o geçer, yoksa config.php.
        $expectedUser = Credentials::albumistUser();
        $expectedKey = Credentials::albumistKey();
        $user = trim($user);
        $password = trim($password);

        if ($expectedUser === '' || $expectedKey === '' || $user === '' || $password === '') {
            return false;
        }

        if (!hash_equals($expectedUser, $user)) {
            return false;
        }

        // Steht in der config.php ein Hash (password_hash), wird er geprueft;
        // sonst der Klartext, zeitkonstant – gleiche Regel wie Admin::login.
        $ok = str_starts_with($expectedKey, '$2y$') || str_starts_with($expectedKey, '$argon2')
            ? password_verify($password, $expectedKey)
            : hash_equals($expectedKey, $password);

        if (!$ok) {
            return false;
        }

        Security::session();
        session_regenerate_id(true);
        $_SESSION['albumist'] = true;
        $_SESSION['albumistSince'] = time();
        $_SESSION['albumistSeen'] = time();

        return true;
    }

    public static function logout(): void
    {
        Security::session();
        unset($_SESSION['albumist'], $_SESSION['albumistSince'], $_SESSION['albumistSeen']);
        session_regenerate_id(true);
    }

    /**
     * "Şifremi unuttum" — tek kullanımlık token, mail'de link. Parola ancak
     * kullanıcı linke tıklayıp yeni parolayı girince değişir.
     * Spam korumasi: IP başına saatte 2.
     */
    public static function handleForgot(string $locale): never
    {
        if (!Security::checkCsrf($_POST['csrf'] ?? null)) {
            http_response_code(403);
            exit('Sitzung abgelaufen. Bitte die Seite neu laden.');
        }

        if (Security::throttle('albumist-forgot', 2, 3600)) {
            header('Location: ' . I18n::path('/albumcu', $locale) . '?parola=gec', true, 303);
            exit;
        }

        $token = PasswordReset::create('albumist');
        $link = Config::url() . I18n::path('/albumcu/parola-sifirla', $locale) . '?token=' . $token;

        $de = $locale === 'de';
        $subject = $de ? 'Albumhersteller-Passwort zuruecksetzen' : 'Albümcü parola sıfırlama';
        $lines = [
            $de
                ? 'Für den Albumhersteller-Zugang wurde eine Passwortruecksetzung angefordert. Ueber diesen Link setzt du ein neues Passwort:'
                : 'Albümcü girişi için parola sıfırlama talep edildi. Bu bağlantıdan yeni parola belirleyebilirsin:',
            '',
            $link,
            '',
            $de
                ? 'Der Link ist 60 Minuten gueltig und kann genau einmal verwendet werden. Bis du ihn benutzt, bleibt das alte Passwort gueltig.'
                : 'Bağlantı 60 dakika geçerli, tek kullanımlık. Tıklayana kadar eski parola geçerli kalır.',
        ];

        $sent = Mail::toStudio($subject, $lines);

        header('Location: ' . I18n::path('/albumcu', $locale) . ($sent ? '?parola=link' : '?parola=hata'), true, 303);
        exit;
    }

    /**
     * Reset linkinin arka tarafı. GET: formu göster. POST: tokeni tüket,
     * yeni albümcü parolasını kaydet, giriş ekranına yönlendir.
     */
    public static function handleReset(string $locale): never
    {
        $token = (string) ($_GET['token'] ?? $_POST['token'] ?? '');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!Security::checkCsrf($_POST['csrf'] ?? null)) {
                http_response_code(403);
                exit('Sitzung abgelaufen. Bitte die Seite neu laden.');
            }

            $pass = (string) ($_POST['password'] ?? '');
            $pass2 = (string) ($_POST['password_repeat'] ?? '');

            if ($pass === '' || $pass !== $pass2 || mb_strlen($pass) < 6) {
                self::renderReset($locale, $token, 'mismatch');
            }

            $kind = PasswordReset::consume($token);
            if ($kind !== 'albumist') {
                self::renderReset($locale, '', 'invalid');
            }

            Credentials::save(null, null, $pass);

            header('Location: ' . I18n::path('/albumcu', $locale) . '?parola=degisti', true, 303);
            exit;
        }

        $kind = PasswordReset::peek($token);
        self::renderReset($locale, $token, $kind === 'albumist' ? '' : 'invalid');
    }

    /** @return never */
    private static function renderReset(string $locale, string $token, string $status): void
    {
        View::page('pages/albumist-reset', [
            'locale' => $locale,
            'path'   => I18n::path('/albumcu/parola-sifirla', $locale),
            'meta'   => ['title' => 'Albümcü', 'noindex' => true, 'bare' => true],
            'token'  => $token,
            'status' => $status,
            'csrf'   => Security::csrf(),
        ]);
        exit;
    }

    /**
     * Wird von AlbumistController als Erstes aufgerufen. Ohne Anmeldung wird
     * das Anmeldeformular gezeigt und die Ausführung beendet.
     */
    public static function requireLogin(string $locale): void
    {
        if (self::isLoggedIn()) {
            return;
        }

        $error = false;
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['password'])) {
            if (Security::checkCsrf($_POST['csrf'] ?? null) && self::login((string) ($_POST['user'] ?? ''), (string) $_POST['password'])) {
                $back = (string) ($_SERVER['REQUEST_URI'] ?? '');
                if ($back === '' || !str_starts_with($back, '/') || str_starts_with($back, '//')) {
                    $back = I18n::path('/albumcu', $locale);
                }
                header('Location: ' . $back, true, 303);
                exit;
            }
            $error = true;
        }

        View::page('pages/albumist-login', [
            'locale' => $locale,
            'path'   => I18n::path('/albumcu', $locale),
            'meta'   => ['title' => 'Albümcü', 'noindex' => true, 'bare' => true],
            'error'  => $error,
            'csrf'   => Security::csrf(),
        ]);
        exit;
    }
}
