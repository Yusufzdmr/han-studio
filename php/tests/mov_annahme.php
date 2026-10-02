<?php
declare(strict_types=1);

/*
 * Jedes Dateifeld, das einen Film nimmt, nimmt auch .mov.
 *
 * Gemeldet am 02.10.2026: "5 · Görseller", neue Ebene als Video, die .mov
 * liess sich gar nicht erst auswaehlen - die .mp4 schon. Media::storeVideo
 * nimmt video/quicktime seit jeher an; nur das accept-Attribut des Feldes
 * zaehlte es nicht auf, und der Dateidialog graute die Datei aus. Dasselbe
 * stand an drei weiteren Feldern "Bild oder Film".
 *
 * Wieder ein Test am Dateitext: die Eigenschaft ist, dass keine accept-Liste
 * video/webm nennt und video/quicktime vergisst.
 */

foreach (['admin/design-edit-sections.php', 'admin/design-edit-tafeln.php'] as $vorlage) {
    $quelle = (string) file_get_contents(__DIR__ . '/../templates/' . $vorlage);
    preg_match_all('/accept="([^"]*)"/', $quelle, $treffer);

    foreach ($treffer[1] as $liste) {
        if (str_contains($liste, 'video/webm')) {
            assert_contains($liste, 'video/quicktime', $vorlage . ': ein Filmfeld nimmt auch .mov');
        }
    }
}
