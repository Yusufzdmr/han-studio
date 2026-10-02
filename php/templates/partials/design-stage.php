<?php
/**
 * Die Buehne: Seite, Karte, Vorspannfilm - und was sich dabei bewegt.
 *
 * Zwei Seiten zeigen dieselbe Buehne: die Vorschau eines Designs (mit
 * Beispieldaten) und eine echte Einladung (mit den Daten des Paares). Der
 * Unterschied steht nur in der Leiste darunter, und die druckt die
 * aufrufende Seite selbst.
 *
 * Ueber die Kernwerte hinaus (design, scope, styles, seite, karte, locale)
 * braucht die Buehne noch warnings - das betrifft die konkrete Vorlage.
 * ratio, tempo, karteAn und introMs stammen zwar aus $design, werden hier
 * aber unveraendert von der aufrufenden Seite uebernommen, statt ein zweites
 * Mal berechnet zu werden - eine Berechnung, eine Quelle der Wahrheit.
 *
 * Ein gezeichnetes Kuvert gibt es nicht mehr. "zarfı kaldır ya komple zaten
 * video koyacaz" (28.09.2026): die Oeffnung ist der Film. Ohne Film liegt
 * die Karte einfach da.
 *
 * @var array<string,mixed> $design
 * @var string $scope
 * @var string $styles
 * @var string $seite
 * @var string $karte
 * @var string $locale
 * @var string $ratio
 * @var int $tempo
 * @var string $karteAn
 * @var int $introMs
 * @var list<array{kind:string,element:string,detail:string}> $warnings
 * @var bool $fest
 * @var string $introVideo   Oeffnungsfilm der Vorlage, leer = keiner
 * @var string $introPoster
 */

use function Atelier\e;
use Atelier\Design;
?>
<style><?= $styles ?></style>

<?php /*
   Die Hoehe der Buehne.

   Bis hierher stand hier min-h-screen, also glatte 100vh - und ALLES darin
   ist absolute inset-0. Die Karte konnte ihre Hoehe also gar nicht an die
   Buehne weitergeben, und die blieb blind einen ganzen Bildschirm hoch.
   Am Telefon faellt das auseinander: die Karte haengt an der Fensterbreite
   (w-full, max-w-2xl, festes Seitenverhaeltnis), die Buehne nicht. Gemessen
   an einer echten Einladung:

       1920 px breit : Karte 521 von 911 px  ->  43 % leer
        414 px breit : Karte 284 von 844 px  ->  66 % leer
        390 px breit : Karte 265 von 844 px  ->  69 % leer
        360 px breit : Karte 242 von 844 px  ->  71 % leer

   Je schmaler das Geraet, desto schlimmer - der Fehler waechst genau dorthin,
   wo die meisten Gaeste die Einladung oeffnen. Und das Argument, das die
   volle Hoehe rechtfertigt, traegt am Telefon nicht. Auf dem Schreibtisch
   liest die Weite als Luft, am Telefon als Fehler.

   Deshalb: die Karte laeuft im Fluss und gibt der Buehne ihre Hoehe. Alles
   andere bleibt absolute inset-0 und legt sich darueber - Zeichnung und
   Ebenen, unveraendert. Ab 768 px kommt die volle Hoehe zurueck, weil sie
   dort tut, was sie soll.

   100dvh mit 100vh davor: am Telefon zaehlt vh den Streifen hinter der
   Adressleiste mit, die Seite ist also hoeher als das Sichtbare - dieselbe
   Beschwerde, zweite Ursache. Die erste Zeile ist der Ersatz fuer Browser,
   die dvh nicht kennen.

   Von Hand geschrieben und nicht als Klasse: style.css ist FERTIG gebaut,
   min-h-[100dvh] steht dort nicht und taete still gar nichts.
*/ ?>
<style>
  .d-stage { display: flex; align-items: center; }

  /* Der einzige Knoten im Fluss. z-10 haelt ihn ueber der Zeichnung
     (auto) und unter dem Vorspannfilm (z-40). */
  .d-stage-mitte {
    position: relative;
    z-index: 10;
    width: 100%;
    /* Luft um die Karte, damit sie nicht an die Kanten der Buehne stoesst.

       Als eigene Regel und nicht als py-12: die Klasse steht NICHT in der
       gebauten style.css (py-10 und py-16 schon) - sie taete still gar
       nichts, und genau das ist hier passiert, bevor nachgemessen wurde. */
    padding-block: 3rem;
  }

  @media (min-width: 768px) {
    .d-stage--fluss { min-height: 100vh; min-height: 100dvh; }
  }
</style>

<?php /*
  Vollflaechig, nicht als Kaestchen mit Ueberschrift. Die erste Fassung zeigt
  unter /designs/{thema} die echte Einladungsseite ueber den ganzen Bildschirm
  (InviteController::designPreview rendert pages/invitation). Ein Vorschau-
  kaestchen von 384 px daneben zu stellen und "sieht es gleich aus?" zu fragen
  waere keine Frage, auf die es eine Antwort geben kann.

  Die Kenndaten stehen deshalb in einer kleinen Leiste unten, ausserhalb der
  Buehne, wo sie den Vergleich nicht stoeren.
*/ ?>

  <?php if ($warnings !== []): ?>
    <ul class="fixed bottom-6 left-4 z-[60] max-w-xs border border-gold bg-cream p-3 text-xs text-ink-soft">
      <?php foreach ($warnings as $warning): ?>
        <li><?= e($warning['kind']) ?> — <?= e($warning['element']) ?><?php
          if ($warning['detail'] !== '') {
            echo ' (', e($warning['detail']), ')';
          }
        ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

<?php /*
  Zwei Rollen, eine Buehne. Im Schaufenster liegt sie ueber allem: dort ist
  die Karte das Einzige, was zaehlt. Auf einer echten Einladung steht sie im
  Fluss, damit die Abschnitte darunter scrollen koennen - fixed inset-0
  liesse darunter nichts zu. Alles INNERHALB ist absolute inset-0, also an
  der Buehne aufgehaengt und nicht am Fenster: beide Rollen funktionieren
  ohne weitere Aenderung.
*/ ?>
  <div class="<?= e($scope) ?> d-stage <?= $fest ? 'fixed inset-0 z-50' : 'relative d-stage--fluss' ?> overflow-hidden"
       style="background: var(--d-bg, #EFE7DC);">

      <!-- Die Seite: Hintergrund und Zeichnung, immer sichtbar. -->
      <div class="d-page absolute inset-0"><?= $seite ?></div>

      <?php /*
        Die Karte behaelt ihr Seitenverhaeltnis und ihre Breite - wie beim
        Original, wo sie mitten auf der Buehne liegt.

        container-type steht hier ein zweites Mal, und das ist kein Versehen:
        cqw rechnet gegen den NAECHSTEN Kasten mit container-type. Stuende es
        nur auf der Buehne, waeren 11 cqw elf Prozent der Fensterbreite statt
        elf Prozent der Karte - die Namen kaemen dreifach zu gross heraus.
        Die Schriftgroessen sind an der Karte gemessen, also muss die Karte
        der Bezug sein.
      */ ?>
      <div class="d-stage-mitte flex items-center justify-center px-6">
        <div class="d-card t-card relative w-full max-w-2xl overflow-hidden"
             data-speed="<?= $tempo ?>"
             style="aspect-ratio: <?= e($ratio) ?>; background: var(--d-paper);
                    container-type: inline-size;
                    /* Die Ebenen der Karte tragen eigene z-index-Werte aus
                       Design::css(). Ohne eigenen Stapelkontext klettern sie
                       aus der Karte heraus und legen sich ueber den
                       Vorspannfilm. */
                    isolation: isolate;"><?= $karte ?></div>
      </div>
      <?php
        $introFilm = Design::safeSrc((string) ($introVideo ?? ''));
        $introBild = Design::safeSrc((string) ($introPoster ?? ''));
      ?>
      <?php if ($introFilm !== '') : ?>
        <?php /*
           Der Vorspann - die einzige Oeffnung.

           Ueber dem ganzen Bildschirm (fixed, nicht absolute: die Buehne ist
           nicht so hoch wie das Fenster; gefahrlos, weil die Seite waehrend
           des Vorspanns ohnehin stillsteht). object-contain: der Film steht
           vollstaendig im Bild, was bleibt, traegt die Grundfarbe der
           Vorlage ("cok buyuk video kucult").

           Er deckt von Anfang an: ein durchsichtiger Kasten liesse die Karte
           sekundenlang sehen, bevor der Film sie zudeckt. Kein Knopf, kein
           "Tippen zum Oeffnen" - invitation.js startet ihn selbst (stumm und
           playsinline, das laesst jeder Browser ohne Fingerdruck zu).

           Er hat drei Auswege: der Film endet, der Film meldet einen Fehler,
           oder die Zeit laeuft ab - jeder davon blendet ihn aus. Genau daran
           hing die weisse Flaeche vom 30. August, und genau deshalb steht es
           hier.

           Der Kasten traegt Art der Kartenbewegung und Dauer des Vorspanns;
           data-sofort sagt dem Skript: hier wartet niemand auf einen Finger.
        */ ?>
        <div class="fixed inset-0 z-40 flex items-center justify-center"
             style="background: var(--d-bg, #0b0a09);
                    opacity:1;
                    pointer-events:auto;
                    transition: opacity 600ms ease;"
             data-intro-video
             data-sofort data-animation="<?= e($karteAn) ?>" data-intro-ms="<?= $introMs ?>">
          <video class="h-full w-full cursor-pointer object-contain" data-intro-film
                 src="<?= e($introFilm) ?>"
                 <?= $introBild !== '' ? 'poster="' . e($introBild) . '"' : '' ?>
                 muted playsinline preload="auto"></video>
        </div>
      <?php endif; ?>
  </div>
