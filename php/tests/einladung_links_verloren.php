<?php
declare(strict_types=1);

/*
 * "Links verloren": die Adresse eingeben, die Links kommen per Mail.
 *
 * Die Seite verraet nie, ob es zu einer Adresse eine Einladung gibt - sonst
 * waere sie ein Werkzeug, um herauszufinden, wer bei uns heiratet. Dieselbe
 * Antwort fuer jede gueltige Adresse; die Links gehen nur an die Adresse
 * selbst, nie auf den Bildschirm.
 */

$routen = (string) file_get_contents(__DIR__ . '/../public/index.php');
assert_contains($routen, "'/{locale}/v2/meine-links'", 'Route: /v2/meine-links');

$steuer = (string) file_get_contents(__DIR__ . '/../src/Controllers/InviteV2Controller.php');
assert_contains($steuer, 'public function meineLinks(', 'Steuerung: meineLinks()');
assert_contains($steuer, 'Security::checkCsrf(', 'meineLinks: mit CSRF');
assert_contains($steuer, "Security::throttle('v2-links'", 'meineLinks: gebremst je Absender');
assert_contains($steuer, "Security::throttle('v2-links-adresse-'", 'meineLinks: gebremst je Adresse');
assert_contains($steuer, 'InvitationsV2::byEmail(', 'meineLinks: sucht ueber die Adresse');
assert_contains($steuer, 'EinladungsMail::senden($email, $gefunden', 'meineLinks: schickt die Sammelmail');

$liste = (string) file_get_contents(__DIR__ . '/../src/InvitationsV2.php');
assert_contains($liste, 'public static function byEmail(', 'InvitationsV2: byEmail()');
assert_contains($liste, "JSON_EXTRACT(data, '$.email')", 'byEmail: sucht in data.email');

$seite = (string) file_get_contents(__DIR__ . '/../templates/pages/invite-v2-links.php');
assert_contains($seite, 'type="email" name="email"', 'Seite: Feld fuer die Adresse');
assert_contains($seite, 'name="csrf"', 'Seite: CSRF-Feld');
assert_not_contains($seite, 'manageKey', 'Seite: zeigt nie einen Schluessel');
assert_not_contains($seite, '$gefunden', 'Seite: weiss nicht, ob etwas gefunden wurde');

// Die Antwort haengt nicht davon ab, ob etwas gefunden wurde.
$start = strpos($steuer, 'public function meineLinks(');
$rumpf = substr($steuer, (int) $start, 3500);
assert_not_contains($rumpf, "'gefunden' =>", 'meineLinks: das Ergebnis geht nicht an die Seite');

$wizard = (string) file_get_contents(__DIR__ . '/../templates/pages/invite-v2-wizard.php');
assert_contains($wizard, "I18n::path('/v2/meine-links')", 'Assistent: verweist auf "Links verloren"');

$dict = require __DIR__ . '/../data/dict.php';
foreach (['de', 'en', 'tr'] as $sprache) {
    foreach (['linksTitle', 'linksLead', 'linksButton', 'linksSent', 'linksForgot'] as $schluessel) {
        assert_true(isset($dict[$sprache]['invitation2'][$schluessel]), "dict $sprache: $schluessel");
    }
}
