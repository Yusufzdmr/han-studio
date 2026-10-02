<?php
declare(strict_types=1);

/**
 * "Roseraie" — thedigitalyes.com referansındaki beyaz çiçek + balmumu mühür
 * estetiğinde tek seferlik bir tasarım. Ayhan'ın WhatsApp'tan gönderdiği
 * dört görselden ikisi (çelenk motifleri + kemer fotoğraf çerçevesi)
 * işlenip (siyah zemin → gerçek şeffaflık) buraya, public/assets/designs/
 * altına konuldu.
 *
 *   php bin/seed-roseraie.php          yoksa oluşturur
 *   php bin/seed-roseraie.php --neu    varsa da üzerine yazar
 *   php bin/seed-roseraie.php --dry    sadece gösterir
 */

require __DIR__ . '/../src/bootstrap.php';

use Atelier\Design;

if (PHP_SAPI !== 'cli') {
    exit('Nur über die Kommandozeile.');
}

$dry = in_array('--dry', $argv, true);
$neu = in_array('--neu', $argv, true);

$palette = [
    'paper'  => ['value' => '#FBF6EF', 'label' => ['de' => 'Papier',   'tr' => 'Kağıt'],   'customer' => false],
    'bg'     => ['value' => '#F3EAE0', 'label' => ['de' => 'Seite',    'tr' => 'Sayfa'],    'customer' => false],
    'fg'     => ['value' => '#4B4038', 'label' => ['de' => 'Schrift',  'tr' => 'Yazı'],     'customer' => false],
    'soft'   => ['value' => '#9C8A78', 'label' => ['de' => 'Gedämpft', 'tr' => 'Soluk'],    'customer' => false],
    'accent' => ['value' => '#C97B5A', 'label' => ['de' => 'Blüte',    'tr' => 'Çiçek'],    'customer' => true],
];

$fonts = [
    'script'  => ['family' => 'Great Vibes', 'size' => 100, 'weight' => 400,
                  'tracking' => 0, 'lineHeight' => 106, 'customer' => false],
    'label'   => ['family' => 'Jost', 'size' => 100, 'weight' => 400,
                  'tracking' => 28, 'lineHeight' => 150, 'customer' => false],
    'display' => ['family' => 'Cormorant Garamond', 'size' => 100, 'weight' => 300,
                  'tracking' => 12, 'lineHeight' => 130, 'customer' => false],
];

$sections = array_map(
    static fn (array $a): array => $a + ['enabled' => true, 'permissions' => ['edit' => true, 'hide' => true]],
    [
        ['id' => 'ort',      'type' => 'location', 'title' => ['de' => 'Wo und wann',      'en' => 'Where and when']],
        ['id' => 'ablauf',   'type' => 'program',  'title' => ['de' => 'Ablauf des Tages', 'en' => 'The day']],
        ['id' => 'wort',     'type' => 'text',     'title' => ['de' => 'Ein Wort von uns', 'en' => 'A word from us']],
        ['id' => 'zaehlung', 'type' => 'countdown','title' => ['de' => 'Noch',             'en' => 'Countdown']],
        ['id' => 'familien', 'type' => 'family',   'title' => ['de' => 'Familien',         'en' => 'Families']],
        ['id' => 'zusage',   'type' => 'rsvp',     'title' => ['de' => 'Kommt ihr?',       'en' => 'Are you coming?']],
    ]
);

/*
 * Kart 768:1376 — film/bild/video ile aynı oran, aynı sebep: altında
 * bölümler var, sadece açılış görünümü değil tüm kart karşılaştırılabilir
 * olsun.
 *
 * Sıra = z-index. Önce kağıt, sonra kemer çerçevesinin ARKASINDAKİ fotoğraf,
 * sonra çerçevenin kendisi, sonra çelenkler, en üstte yazı.
 */
$layers = [
    [
        'id' => 'kagit', 'label' => 'Kağıt', 'type' => 'shape', 'spot' => 'card',
        'box' => ['x' => 0, 'y' => 0, 'w' => 100, 'h' => 100, 'opacity' => 100],
        'style' => ['color' => 'paper'],
        'permissions' => [],
    ],
    [
        // Fotoğraf + çerçeve boyu 62/65'ten 40/42'ye indirildi: isimler
        // zarf açılır açılmaz görünen ilk ekranda olsun istendi ("koymicaksan
        // ne anlamı var") — eskisi isimleri kartın %72'sine itiyordu, telefon
        // ekranının ilk görünümünün çok altında kalıyordu.
        'id' => 'ciftfoto', 'label' => 'Çift fotoğrafı', 'type' => 'photo', 'spot' => 'card',
        'src' => '', 'box' => ['x' => 8, 'y' => 3, 'w' => 84, 'h' => 40, 'opacity' => 100],
        'permissions' => ['edit' => true, 'photo' => true],
    ],
    [
        'id' => 'kemer', 'label' => 'Kemer çerçeve', 'type' => 'image', 'spot' => 'card',
        'src' => '/assets/designs/roseraie-arch.png',
        'box' => ['x' => 4, 'y' => 0, 'w' => 92, 'h' => 42, 'opacity' => 100],
        'permissions' => [],
    ],
    [
        'id' => 'celenksol', 'label' => 'Çelenk sol', 'type' => 'image', 'spot' => 'card',
        'src' => '/assets/designs/roseraie-garland-left.png',
        'box' => ['x' => -4, 'y' => 44, 'w' => 42, 'h' => 0, 'opacity' => 92],
        'motion' => ['move' => 'fade', 'delay' => 300, 'duration' => 1400],
        'permissions' => [],
    ],
    [
        'id' => 'obertitel', 'label' => 'Überschrift', 'type' => 'text', 'spot' => 'card',
        'text' => ['de' => 'WIR HEIRATEN', 'en' => 'WE ARE GETTING MARRIED'],
        'box' => ['x' => 14, 'y' => 46, 'w' => 72],
        'style' => ['font' => 'label', 'color' => 'soft', 'size' => 16, 'align' => 'center'],
        'motion' => ['move' => 'fade', 'delay' => 500, 'duration' => 1200],
    ],
    [
        'id' => 'namen', 'label' => 'Namen', 'type' => 'text', 'spot' => 'card',
        'bind' => 'couple_names',
        'box' => ['x' => 10, 'y' => 50, 'w' => 80],
        'style' => ['font' => 'script', 'color' => 'fg', 'size' => 92, 'align' => 'center'],
        'motion' => ['move' => 'fade', 'delay' => 900, 'duration' => 1600],
    ],
    [
        'id' => 'celenkorta', 'label' => 'Çelenk orta', 'type' => 'image', 'spot' => 'card',
        'src' => '/assets/designs/roseraie-garland-mid.png',
        'box' => ['x' => 30, 'y' => 63, 'w' => 40, 'h' => 0, 'opacity' => 95],
        'motion' => ['move' => 'fade', 'delay' => 700, 'duration' => 1400],
        'permissions' => [],
    ],
    [
        'id' => 'tarih', 'label' => 'Datum', 'type' => 'text', 'spot' => 'card',
        'bind' => 'wedding_date',
        'box' => ['x' => 14, 'y' => 69, 'w' => 72],
        'style' => ['font' => 'display', 'color' => 'accent', 'size' => 24, 'align' => 'center'],
        'motion' => ['move' => 'fade', 'delay' => 1300, 'duration' => 1200],
    ],
    [
        // spot:page değil spot:card — "page" kart değil TÜM sayfaya göre
        // ölçekleniyor (bölümler dahil), o yüzden ilk denemede kart
        // genişliğinin katları kadar büyük ve RSVP'nin üstüne binmiş
        // çıkmıştı. Kart alanı -50..150 taştığı için kartın hemen altına
        // sarkması burada da mümkün, sadece kart genişliğine göre.
        'id' => 'kume', 'label' => 'Küme sağ üst', 'type' => 'image', 'spot' => 'card',
        'src' => '/assets/designs/roseraie-cluster.png',
        'box' => ['x' => 68, 'y' => -3, 'w' => 20, 'h' => 0, 'opacity' => 90],
        'permissions' => [],
    ],
    [
        'id' => 'celenkalt', 'label' => 'Çelenk alt', 'type' => 'image', 'spot' => 'card',
        'src' => '/assets/designs/roseraie-garland-bottom.png',
        'box' => ['x' => 24, 'y' => 76, 'w' => 52, 'h' => 0, 'opacity' => 90],
        'motion' => ['move' => 'fade', 'delay' => 1500, 'duration' => 1400],
        'permissions' => [],
    ],
];

$doc = [
    'id' => 'roseraie',
    'slug' => 'roseraie',
    'name' => ['de' => 'Roseraie', 'en' => 'Roseraie'],
    'category' => 'floral',
    'tags' => ['weiss', 'blush', 'siegel'],
    'status' => 'active',
    'sort' => 3,
    'canvas' => ['ratio' => '768:1376', 'safe' => 6],
    'palette' => $palette,
    'fonts' => $fonts,
    'sections' => $sections,
    'layers' => $layers,
    /*
     * Belirtilmezse DesignSections::flaeche() ilk spot:card görselini
     * (burada "kemer" — çerçeve, deliği fotoğraf için) bölümlerin arka
     * planı sanıp kullanıyor; o bir kağıt dokusu değil, sonuçta bölümler
     * boyunca devasa/tuhaf duruyordu. Aynı kemer görselinin düz, çiçeksiz
     * bir köşesinden kırpılmış gerçek kağıt dokusu — kartın kağıdı
     * altında öylece devam etsin diye.
     */
    'sectionsBg' => '/assets/designs/roseraie-paper.png',
];

if (!$dry && Design::findById($doc['id']) !== null && !$neu) {
    echo "roseraie: gibt es schon, übersprungen (--neu überschreibt)\n";
    exit(0);
}

if ($dry) {
    echo "roseraie: würde angelegt (", count($layers), " Ebenen)\n";
    exit(0);
}

Design::save(Design::complete($doc));
echo "roseraie: geschrieben\n";
