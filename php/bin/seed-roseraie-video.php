<?php
declare(strict_types=1);

/**
 * "Roseraie (Video)" — Roseraie'nin baştan sona videolu kardeşi. Aynı
 * palet/font/bölümler, ama üstte fotoğraf+çerçeve yerine dönen bir arka
 * plan filmi, zarf da statik mühür yerine video ile açılıyor.
 *
 * Mevcut iki video (bin/seed-designs.php'nin "referenz" varlıkları):
 *   - /assets/vorlagen/film.mp4   zarfın açılışı (kısa, tek seferlik)
 *   - /assets/intro/lumina-swans.mp4   sakin, döngülü atmosfer filmi
 *
 *   php bin/seed-roseraie-video.php          yoksa oluşturur
 *   php bin/seed-roseraie-video.php --neu    varsa da üzerine yazar
 *   php bin/seed-roseraie-video.php --dry    sadece gösterir
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
 * Roseraie ile aynı y-yerleşimi (isimler ilk ekranda görünsün diye
 * sıkıştırılmış hâliyle) — sadece üstteki foto+çerçeve, tek bir dönen
 * arka plan filmiyle değişti.
 */
$layers = [
    [
        'id' => 'kagit', 'label' => 'Kağıt', 'type' => 'shape', 'spot' => 'card',
        'box' => ['x' => 0, 'y' => 0, 'w' => 100, 'h' => 100, 'opacity' => 100],
        'style' => ['color' => 'paper'],
        'permissions' => [],
    ],
    [
        // Çiftin kendi filmiyle değiştirilebilsin diye photo hakkı açık —
        // aynen video kitaplığının izin mekanizması (spec: "video katmanı
        // da photo hakkına bağlanır").
        'id' => 'arkaplanfilm', 'label' => 'Arka plan filmi', 'type' => 'video', 'spot' => 'card',
        'src' => '/assets/intro/lumina-swans.mp4', 'poster' => '',
        'box' => ['x' => 0, 'y' => 0, 'w' => 100, 'h' => 42, 'opacity' => 100],
        'permissions' => ['edit' => true, 'photo' => true],
    ],
    [
        // Filmin metinle kesiştiği alt kenarda okunabilirlik için ince bir
        // örtü — "video" referans şablonundaki "Schleier" ile aynı fikir.
        'id' => 'ortu', 'label' => 'Örtü', 'type' => 'shape', 'spot' => 'card',
        'box' => ['x' => 0, 'y' => 30, 'w' => 100, 'h' => 12, 'opacity' => 55],
        'style' => ['color' => 'paper', 'blur' => 20],
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
    'id' => 'roseraie-video',
    'slug' => 'roseraie-video',
    'name' => ['de' => 'Roseraie (Video)', 'en' => 'Roseraie (Video)'],
    'family' => 'roseraie',
    'category' => 'floral',
    'tags' => ['weiss', 'blush', 'video'],
    'status' => 'active',
    'sort' => 4,
    'canvas' => ['ratio' => '768:1376', 'safe' => 6],
    'palette' => $palette,
    'fonts' => $fonts,
    'sections' => $sections,
    'layers' => $layers,
    // Zarf, mührün açılması yerine kısa bir filmle açılıyor.
    'intro' => ['video' => '/assets/vorlagen/film.mp4', 'poster' => ''],
    // Roseraie'deki gibi: bölümlerin arkasına düz kağıt, çerçeve/film
    // sızmasın diye.
    'sectionsBg' => '/assets/designs/roseraie-paper.png',
];

if (!$dry && Design::findById($doc['id']) !== null && !$neu) {
    echo "roseraie-video: gibt es schon, übersprungen (--neu überschreibt)\n";
    exit(0);
}

if ($dry) {
    echo "roseraie-video: würde angelegt (", count($layers), " Ebenen)\n";
    exit(0);
}

Design::save(Design::complete($doc));
echo "roseraie-video: geschrieben\n";
