<?php
/**
 * Einmalige Bereinigung: DB-Venues leeren.
 *
 * Nach dem Krumbach-Umschwenken stand im Live-DB noch das alte Stuttgart-
 * Venue-Set (Schloss Solitude, SI-Centrum, Alte Kelter, Villa Berg,
 * Schloss Hohenheim). Die Stadtseiten sind längst Krumbach-Bezug; diese
 * Locations gehören nicht mehr dazu.
 *
 * Nach Erfolg: Datei sofort vom Server löschen.
 */
declare(strict_types=1);

if (($_GET['token'] ?? '') !== 'KRUMBACH-2026-11-04') {
    http_response_code(403);
    echo "verboten\n";
    exit;
}

require __DIR__ . '/../src/bootstrap.php';

use Atelier\Content;

Content::mutate(function (array $c): array {
    $c['venues'] = [];
    return $c;
});

echo "OK — venues[] leer.\nBitte diese Datei JETZT vom Server löschen.\n";
