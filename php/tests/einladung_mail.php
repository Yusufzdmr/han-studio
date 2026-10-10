<?php
declare(strict_types=1);

use Atelier\EinladungsMail;
use Atelier\InvitationsV2;
use Atelier\Mail;

/*
 * Die Mail an das Paar nach dem Veroeffentlichen.
 *
 * "Email e linki atabilir ilaveten ... Mail bos olmasin kaliteli olsun
 * resimli felan birde tesekkur yazili" (Ayhan, 10.10.2026). Die Links gingen
 * bis dahin verloren, sobald das Paar die Erfolgsseite schloss.
 */

$daten = ['bride' => 'Ayşe', 'groom' => 'Mehmet', 'date' => '2027-06-12', 'venue' => 'Schloss Elmau',
          'manageKey' => 'k3y', 'locale' => 'de', 'email' => 'paar@example.com'];
$links = InvitationsV2::links('ayse-mehmet', $daten);
$bild  = 'https://gidonla.com/uploads/og/ayse-mehmet-1234abcd.jpg';

$mail = EinladungsMail::bauen([['slug' => 'ayse-mehmet', 'data' => $daten, 'links' => $links, 'bild' => $bild]], 'de');

assert_contains($mail['subject'], 'Ayşe & Mehmet', 'Mail: Betreff nennt das Paar');
assert_contains($mail['html'], 'Danke', 'Mail: bedankt sich');
assert_contains($mail['html'], 'Ayşe &amp; Mehmet', 'Mail: Namen, maskiert');
assert_contains($mail['html'], 'src="' . $bild . '"', 'Mail: mit Bild');
assert_contains($mail['html'], 'href="' . $links['gast']['url'] . '"', 'Mail: Gastlink');
assert_contains($mail['html'], 'href="' . $links['antworten']['url'] . '"', 'Mail: Antworten');
assert_contains($mail['html'], 'href="' . $links['bearbeiten']['url'] . '"', 'Mail: Bearbeiten');
assert_contains($mail['html'], 'Schloss Elmau', 'Mail: der Ort');
assert_contains($mail['html'], '/de/v2/meine-links', 'Mail: wohin, wenn die Links wieder weg sind');
assert_contains($mail['text'], $links['bearbeiten']['url'], 'Mail: Textfassung traegt die Links');
assert_not_contains($mail['text'], '<', 'Mail: Textfassung ohne HTML');

// Kein Teilbild (kein Foto in der Vorlage): keine leere Bildzeile.
$ohneBild = EinladungsMail::bauen([['slug' => 'x', 'data' => $daten, 'links' => $links, 'bild' => '']], 'de');
assert_not_contains($ohneBild['html'], '<img', 'Mail: ohne Bild kein img');

// Was das Paar tippt, kommt maskiert an.
$boese = EinladungsMail::bauen([['slug' => 'x', 'data' => ['bride' => '<script>x</script>'] + $daten,
    'links' => $links, 'bild' => '']], 'de');
assert_not_contains($boese['html'], '<script>', 'Mail: Namen maskiert');

// Englisch fuer eine englische Einladung.
$en = EinladungsMail::bauen([['slug' => 'x', 'data' => ['locale' => 'en'] + $daten, 'links' => $links, 'bild' => '']], 'en');
assert_contains($en['html'], 'Thank you', 'Mail: englisch');

// "Links verloren" schickt alle Einladungen einer Adresse in einer Mail.
$zwei = EinladungsMail::bauen([
    ['slug' => 'a', 'data' => $daten, 'links' => InvitationsV2::links('a', $daten), 'bild' => ''],
    ['slug' => 'b', 'data' => $daten, 'links' => InvitationsV2::links('b', $daten), 'bild' => ''],
], 'de', true);
assert_contains($zwei['html'], '/de/v2/einladung/a/k3y/bearbeiten', 'Sammelmail: erste Einladung');
assert_contains($zwei['html'], '/de/v2/einladung/b/k3y/bearbeiten', 'Sammelmail: zweite Einladung');

/* --- Der Weg hinaus: Brevo, sonst mail() --- */

$koerper = json_decode(Mail::brevoBody('paar@example.com', 'Betreff', '<p>hi</p>', 'hi', 'web@example.com', 'hallo@example.com'), true);
assert_same('paar@example.com', $koerper['to'][0]['email'] ?? null, 'Brevo: Empfaenger');
assert_same('web@example.com', $koerper['sender']['email'] ?? null, 'Brevo: Absender');
assert_same('Han Studio', $koerper['sender']['name'] ?? null, 'Brevo: Absendername');
assert_same('<p>hi</p>', $koerper['htmlContent'] ?? null, 'Brevo: HTML');
assert_same('hi', $koerper['textContent'] ?? null, 'Brevo: Text');
assert_same('hallo@example.com', $koerper['replyTo']['email'] ?? null, 'Brevo: Antworten gehen an den Betrieb');

$mailQuelle = (string) file_get_contents(__DIR__ . '/../src/Mail.php');
assert_contains($mailQuelle, "Integrations::value('BREVO_API_KEY')", 'Mail: Brevo, wenn ein Schluessel da ist');
assert_contains($mailQuelle, 'multipart/alternative', 'Mail: sonst mail() mit HTML und Text');

/* --- Der Assistent fragt nach der Adresse und schickt die Mail --- */

$seite = (string) file_get_contents(__DIR__ . '/../templates/pages/invite-v2-wizard.php');
assert_contains($seite, 'type="email" name="email"', 'Assistent: Feld fuer die Adresse');
assert_contains($seite, 'required', 'Assistent: Pflichtfeld');
assert_contains($seite, "\$done['email']", 'Erfolgsseite: sagt, wohin die Mail ging');

$steuer = (string) file_get_contents(__DIR__ . '/../src/Controllers/InviteV2Controller.php');
assert_contains($steuer, "['error' => 'email']", 'publish: ohne gueltige Adresse kein Veroeffentlichen');
assert_contains($steuer, 'EinladungsMail::senden(', 'publish: schickt die Mail');

$dict = require __DIR__ . '/../data/dict.php';
foreach (['de', 'en', 'tr'] as $sprache) {
    foreach (['fieldEmail', 'emailNote', 'errorEmail', 'doneMailSent'] as $schluessel) {
        assert_true(isset($dict[$sprache]['invitation2'][$schluessel]), "dict $sprache: $schluessel");
    }
}
