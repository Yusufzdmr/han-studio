<?php
declare(strict_types=1);

use Atelier\InvitationsV2;

/*
 * Die drei Links einer Einladung - an einer Stelle gebaut.
 *
 * "davetiye olusturuldugunda iki tane link geliyor orda linki kopyalamasam
 * kayboluyor" (Ayhan, 10.10.2026). Bis dahin baute publish() die Links von
 * Hand, und der Link zum Bearbeiten stand nirgends: der Schluessel oeffnet
 * ihn, aber kein Paar kannte die Adresse. Erfolgsseite, Panel, E-Mail und
 * "Links verloren" brauchen dieselben drei - also eine Funktion.
 */

$links = InvitationsV2::links('ayse-mehmet', ['manageKey' => 'abc123', 'locale' => 'de']);

assert_same('/de/v2/einladung/ayse-mehmet', $links['gast']['path'], 'links: Gastlink');
assert_same('/de/v2/einladung/ayse-mehmet/abc123', $links['antworten']['path'], 'links: Antworten');
assert_same('/de/v2/einladung/ayse-mehmet/abc123/bearbeiten', $links['bearbeiten']['path'], 'links: Bearbeiten');
assert_true(str_ends_with($links['gast']['url'], '/de/v2/einladung/ayse-mehmet'), 'links: volle Adresse');
assert_true(str_starts_with($links['gast']['url'], 'http'), 'links: mit Host');

// Die Sprache der Einladung, nicht die des gerade offenen Panels: der Link in
// der Mail an das Paar soll in seiner Sprache aufgehen.
$en = InvitationsV2::links('x', ['manageKey' => 'k', 'locale' => 'en']);
assert_same('/en/v2/einladung/x', $en['gast']['path'], 'links: Sprache der Einladung');

// Ohne Schluessel gibt es nur den Gastlink - ein ".../einladung/x/" waere
// eine kaputte Adresse, kein privater Link.
$ohne = InvitationsV2::links('x', ['locale' => 'de']);
assert_same('/de/v2/einladung/x', $ohne['gast']['path'], 'links: ohne Schluessel Gastlink');
assert_same(null, $ohne['antworten'], 'links: ohne Schluessel keine Antworten');
assert_same(null, $ohne['bearbeiten'], 'links: ohne Schluessel kein Bearbeiten');

/* --- Die Erfolgsseite zeigt alle drei, jeden mit Kopierknopf --- */

$seite = (string) file_get_contents(__DIR__ . '/../templates/pages/invite-v2-wizard.php');
assert_contains($seite, "\$done['links']['bearbeiten']", 'Erfolgsseite: der Link zum Bearbeiten steht da');
assert_contains($seite, 'data-copy="<?= e($zeile[\'link\'][\'url\']) ?>"', 'Erfolgsseite: jede Zeile hat einen Kopierknopf');
foreach (['gast', 'antworten', 'bearbeiten'] as $art) {
    assert_contains($seite, "\$done['links']['$art']", "Erfolgsseite: Zeile $art");
}

$app = (string) file_get_contents(__DIR__ . '/../public/assets/app.js');
assert_contains($app, '[data-copy]', 'app.js: Kopierknoepfe funktionieren auch ausserhalb des Panels');

$steuer = (string) file_get_contents(__DIR__ . '/../src/Controllers/InviteV2Controller.php');
assert_contains($steuer, 'InvitationsV2::links(', 'publish: die Links kommen aus links()');

/* --- Das Panel zeigt die privaten Links: ein verlorener Link ist ein Anruf, keine SQL-Abfrage --- */

$liste = (string) file_get_contents(__DIR__ . '/../src/InvitationsV2.php');
assert_contains($liste, "JSON_EXTRACT(i.data, \'$.manageKey\')", 'all(): holt den Schluessel mit');

$panel = (string) file_get_contents(__DIR__ . '/../templates/admin/invitations.php');
assert_contains($panel, 'InvitationsV2::links($slug', 'Panel: Links aus links()');
assert_contains($panel, "\$links['bearbeiten']", 'Panel: Link zum Bearbeiten');
assert_contains($panel, 'data-copy=', 'Panel: Kopierknopf');
