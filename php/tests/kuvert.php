<?php

declare(strict_types=1);

use Atelier\Design;

/*
 * Kein Umschlag mehr - die Oeffnung ist der Film.
 *
 * "zarfı kaldır ya komple zaten video koyacaz" (28.09.2026). Davor gab es
 * einen Haken "Zarf açılışı göster", und eine neue Vorlage fing mit ihm an:
 * wer einen Film hinlegte, bekam trotzdem erst ein gezeichnetes Kuvert und
 * danach den Film. Jetzt gibt es kein Kuvert und keinen Haken.
 */

/* --- Das Dokument kennt kein Kuvert --- */

$leer = Design::complete(['id' => 'p', 'slug' => 'p']);
assert_true(!array_key_exists('kuvert', $leer['intro']), 'Dokument: kein Kuvertfeld');

// Alte Dokumente tragen das Feld noch - es faellt beim Normalisieren weg.
$alt = Design::complete(['id' => 'p', 'slug' => 'p',
    'intro' => ['video' => '/uploads/designs/auf.mp4', 'kuvert' => true]]);
assert_true(!array_key_exists('kuvert', $alt['intro']), 'Dokument: ein altes Kuvertfeld faellt weg');
assert_same('/uploads/designs/auf.mp4', $alt['intro']['video'], 'Dokument: der Film bleibt');

$post = Design::fromPost($leer, ['intro_video' => '/uploads/designs/auf.mp4', 'intro_kuvert' => '1']);
assert_true(!array_key_exists('kuvert', $post['intro']), 'Formular: ein alter Haken bringt es nicht zurueck');

/* --- Die Buehne druckt keins --- */

$buehne = (string) file_get_contents(__DIR__ . '/../templates/partials/design-stage.php');

assert_not_contains($buehne, 'data-envelope', 'Buehne: kein Kuvert, kein Anklickpunkt');
assert_not_contains($buehne, 'mitKuvert', 'Buehne: und keine Weiche dafuer');
assert_not_contains($buehne, '$kuvert', 'Buehne: und keine Kuvertebenen');

/*
 * Der Filmkasten traegt Art und Dauer selbst und deckt von Anfang an - ein
 * durchsichtiger Kasten liesse die Karte sehen, bevor der Film sie zudeckt.
 */
assert_contains($buehne, 'data-sofort data-animation="<?= e($karteAn) ?>" data-intro-ms="<?= $introMs ?>"',
    'Buehne: der Filmkasten traegt Art und Dauer');
assert_contains($buehne, 'opacity:1;', 'Buehne: der Film deckt sofort');

// Gelesen wird der <video>-Tag selbst, nicht die Prosa drumherum.
$tag = strstr((string) strstr($buehne, '<video '), '</video>', true);
assert_true($tag !== false && $tag !== '', 'Buehne: der Filmknoten steht im Markup');
assert_contains((string) $tag, 'muted', 'Buehne: stumm, sonst startet ihn kein Browser von allein');
assert_contains((string) $tag, 'playsinline', 'Buehne: und im Bild, nicht im Vollbild');

/* --- Das Skript startet den Film selbst --- */

$skript = (string) file_get_contents(__DIR__ . '/../public/assets/invitation.js');

assert_contains($skript, 'var sofort = document.querySelector("[data-intro-video][data-sofort]");',
    'Skript: es kennt den Kasten ohne Kuvert');
assert_contains($skript, 'film.addEventListener("loadedmetadata", reveal, { once: true });',
    'Skript: gestartet wird, wenn die Laenge bekannt ist');
assert_contains($skript, 'setTimeout(reveal, 2500);',
    'Skript: und spaetestens dann, wenn sie nie bekannt wird');

// Drei Auswege, sonst haengt der Gast vor einem deckenden Kasten fest.
assert_contains($skript, 'introFilm.addEventListener("ended", schliessen, { once: true });', 'Skript: Ausweg 1, der Film endet');
assert_contains($skript, 'introFilm.addEventListener("error", schliessen, { once: true });', 'Skript: Ausweg 2, der Film scheitert');
assert_contains($skript, 'setTimeout(schliessen, deckel);', 'Skript: Ausweg 3, die Zeit laeuft ab');

/* --- Nach dem RSVP kein zweiter Vorspann im selben Besuch --- */

assert_contains($skript, 'var kuvertSchluessel = "al-kuvert-offen:" + location.pathname;',
    'Skript: es merkt sich, unter welcher Adresse schon geoeffnet war');
assert_contains($skript, 'if (quelle && !schonOffen) {',
    'Skript: schon offen ueberspringt den Vorspann');

/* --- Das Panel bietet keinen Haken mehr an --- */

$tafel = (string) file_get_contents(__DIR__ . '/../templates/admin/design-edit-sections.php');

assert_not_contains($tafel, 'intro_kuvert', 'Panel: kein Haken fuer ein Kuvert');
assert_not_contains($tafel, 'Zarf açılışı göster', 'Panel: und kein Satz dazu');
