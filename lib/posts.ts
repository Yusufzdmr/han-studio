import type { L } from "./i18n";

/* ------------------------------------------------------------------ */
/*  Ratgeber – Standardbeiträge                                        */
/*                                                                     */
/*  Der Ratgeber ist der Teil des Local SEO, der laufend weiterwächst. */
/*  Jeder Beitrag verlinkt auf eine Stadt- und/oder Locationseite –    */
/*  daher stammt der eigentliche Nutzen, nicht aus dem Text allein.    */
/*  Im Admin unter "Ratgeber" vollständig bearbeitbar.                 */
/* ------------------------------------------------------------------ */

export type PostFaq = { q: L; a: L };

export type Post = {
  slug: string;
  title: L;
  /** Anrisstext für Übersicht, Meta-Description und Teaser */
  excerpt: L;
  /** Absätze; eine Zeile, die mit "## " beginnt, wird zur Zwischenüberschrift */
  body: L<string[]>;
  /** ISO-Datum, steuert die Sortierung */
  date: string;
  /** Bildkennung aus lib/photos.json */
  seed: string;
  /** Im Admin hochgeladene Bilder (ersetzen das Platzhalterbild) */
  uploads?: string[];
  /** Interne Verlinkung – der SEO-Hebel */
  citySlug?: string;
  venueSlug?: string;
  /** Erscheint als FAQ-Schema unter dem Beitrag */
  faq?: PostFaq[];
};

export const posts: Post[] = [
  {
    slug: "was-kostet-hochzeitsfotograf-schwaben",
    date: "2026-11-04",
    seed: "lum-blog-preis",
    citySlug: "krumbach",
    title: {
      de: "Was kostet ein Hochzeitsfotograf in Schwaben?",
      tr: "Schwaben'de düğün fotoğrafçısı ne kadar?",
    },
    excerpt: {
      de: "Preise variieren stark – nicht, weil der Markt chaotisch wäre, sondern weil sehr unterschiedliche Leistungen darunter laufen. So setzt sich ein ehrliches Hochzeitsfoto-Paket zusammen und darauf achtet ihr, bevor ihr unterschreibt.",
      tr: "Fiyatlar çok değişkendir – pazar kaotik olduğu için değil, altında çok farklı hizmetler olduğu için. Dürüst bir düğün fotoğrafı paketi nasıl oluşuyor ve imzalamadan önce nelere dikkat etmeli.",
    },
    body: {
      de: [
        "Die Frage „Was kostet ein Hochzeitsfotograf?“ hat in Schwaben keine einfache Antwort. Wer heute im Raum Krumbach, Ulm, Memmingen oder Augsburg sucht, findet Angebote zwischen etwa 800 € und 5.000 € – für dieselbe Formulierung „Ganztagesreportage“. Dahinter stecken sehr unterschiedliche Leistungen. Ein Preisschild ohne Beschreibung sagt deshalb wenig.",
        "## Was in einem seriösen Paket enthalten sein sollte",
        "Ein ganztägiges Angebot im mittleren Preissegment umfasst in der Regel eine Begleitung von acht bis zehn Stunden, die ausgewählte und bearbeitete Online-Galerie, einen Download der Bilder in voller Auflösung und ein schriftliches Nutzungsrecht für private Zwecke. Bei höheren Preisen kommen meistens Zweitkamera, ein zweites Objektivset oder Filmbegleitung dazu – nicht zwingend \"mehr Bilder\".",
        "## Woher die Unterschiede kommen",
        "Preisunterschiede haben in Schwaben vor allem vier Gründe: Erfahrungsstand, Nachbearbeitungszeit, Teamgröße und Aufbewahrung. Ein Paket mit 24-monatiger Archivierung, doppelter Sicherung am Hochzeitsabend und verbindlichen Lieferfristen hat mehr Arbeit im Hintergrund als ein Angebot, bei dem die Daten nach einem Jahr gelöscht werden.",
        "## Was oft fehlt",
        "Günstige Angebote lassen häufig genau die Punkte offen, die später streitanfällig sind: Anzahl der Bilder, Lieferzeit, Nutzungsrechte, Ersatzregelung bei Krankheit. Vor der Unterschrift lohnt ein Blick in den Vertrag genau an diesen Stellen – nicht in die Bildgalerie.",
        "## Anfahrt ist ein eigener Posten",
        "In Schwaben ist die Entfernung zwischen Krumbach, Ulm und Augsburg zwar überschaubar, aber nicht null. Seriöse Anbieter sagen klar, bis zu welchem Radius die Anfahrt enthalten ist und was darüber hinaus pro Kilometer berechnet wird. Ein Pauschalpreis \"inklusive Anfahrt\" ohne Radiusangabe ist ein Hinweis, nachzufragen.",
        "## Standesamt vs. Ganztag",
        "Für eine standesamtliche Trauung im Rathaus Krumbach, im Schwörhaus Ulm oder im Augsburger Rathaus genügt oft ein kompaktes Paket über zwei bis drei Stunden. Es gibt keinen Grund, dafür den Preis einer Ganztagsreportage zu zahlen – und auch keinen Grund, auf saubere Bearbeitung und ordentliche Lieferzeit zu verzichten.",
        "## Fazit",
        "Der Preis ist nur so aussagekräftig wie die Beschreibung dahinter. Vergleicht nicht die Zahl, sondern die Leistungen auf einer Zeile: Stunden, Bilder, Lieferzeit, Nutzungsrechte, Ersatzregelung. Danach wisst ihr, was ihr vergleicht.",
      ],
      tr: [
        "\"Düğün fotoğrafçısı ne kadar?\" sorusunun Schwaben'de kolay bir cevabı yok. Bugün Krumbach, Ulm, Memmingen ya da Augsburg çevresinde arayan biri, aynı \"tam gün çekim\" ifadesi için 800 € ile 5.000 € arası teklifler buluyor. Arkada çok farklı hizmetler var. Dolayısıyla açıklamasız bir fiyat tek başına bir şey söylemiyor.",
        "## Ciddi bir pakette ne olmalı",
        "Orta segment bir tam gün paketi genelde şunları içerir: 8–10 saatlik çekim, seçilmiş ve işlenmiş online galeri, karelerin tam çözünürlükte indirilebilmesi ve özel kullanım için yazılı kullanım hakkı. Daha yüksek fiyatlarda ikinci kamera, ikinci lens seti ya da film genelde eklenir – bu \"daha fazla kare\" demek değildir.",
        "## Farklar nereden geliyor",
        "Schwaben'de fiyat farklarının başlıca dört nedeni var: deneyim, post-prodüksiyon süresi, ekip büyüklüğü ve arşivleme. 24 ay arşivli, düğün akşamı çift yedekli ve sözleşmede garanti edilmiş teslim süreleri olan bir pakette, bir yıl sonra silen bir pakete göre çok daha fazla arka plan işi vardır.",
        "## Sıklıkla eksik olanlar",
        "Ucuz tekliflerde çoğu kez tam da sonradan tartışma çıkaran noktalar boş kalır: kare sayısı, teslim süresi, kullanım hakları, hastalık durumunda yedek fotoğrafçı. İmza öncesi bakılacak yer, galeri değil, sözleşmenin bu kısımlarıdır.",
        "## Ulaşım ayrı bir kalem",
        "Schwaben'de Krumbach, Ulm ve Augsburg arası mesafeler yönetilebilir ama sıfır değil. Ciddi sağlayıcılar ulaşımın hangi yarıçapa kadar dahil olduğunu ve ötesinde kilometre başına ne alındığını net söyler. Yarıçap belirtmeden \"ulaşım dahil\" denen paketlerde sormak gerekir.",
        "## Nikah ve tam gün",
        "Krumbach belediyesinde, Ulm'daki Schwörhaus'ta veya Augsburg Rathaus'ta bir resmî nikah için genelde 2–3 saatlik, sıkı bir paket yeter. Bunun için tam gün fiyatını ödemenin anlamı yok – aynı şekilde özenli post-prodüksiyondan veya düzgün teslim süresinden vazgeçmenin de.",
        "## Sonuç",
        "Fiyat, arkasındaki açıklama kadar anlamlıdır. Rakamı değil, tek bir satırdaki hizmetleri karşılaştırın: saat, kare, teslim süresi, kullanım hakları, yedek düzenlemesi. Sonrasında neyi karşılaştırdığınızı biliyor olursunuz.",
      ],
    },
    faq: [
      {
        q: {
          de: "Gibt es feste Preislisten für Hochzeitsfotografen in Schwaben?",
          tr: "Schwaben'de düğün fotoğrafçısı için sabit fiyat listeleri var mı?",
        },
        a: {
          de: "Nein. Jedes Studio kalkuliert eigenständig; Berufsverbände geben höchstens Richtgrößen vor. Deshalb lohnt der Vergleich nach Leistung, nicht nach Durchschnittspreis.",
          tr: "Hayır. Her stüdyo kendi hesabını yapıyor; meslek dernekleri olsa olsa yönlendirici rakamlar verir. Bu yüzden karşılaştırma ortalama fiyata göre değil, hizmete göre yapılmalı.",
        },
      },
      {
        q: {
          de: "Warum ist ein Paket mit Zweitfotograf teurer, obwohl die Zeit dieselbe ist?",
          tr: "İki fotoğrafçılı paket neden daha pahalı, oysa süre aynı?",
        },
        a: {
          de: "Weil zwei Personen am Tag arbeiten, zwei Personen nach der Hochzeit nachbearbeiten und doppelt so viele Daten zu sichern und zu verwalten sind. Der Mehraufwand liegt nicht am Hochzeitstag, sondern in den Wochen danach.",
          tr: "Çünkü günün içinde iki kişi çalışır, düğün sonrası iki kişi işler ve iki kat veri yedeklenip yönetilir. Asıl yük düğün günü değil, sonraki haftalar.",
        },
      },
    ],
  },

  {
    slug: "hochzeitslocations-ulm-augsburg-memmingen",
    date: "2026-11-04",
    seed: "lum-blog-locations",
    citySlug: "ulm",
    title: {
      de: "Hochzeitslocations in Ulm, Augsburg und Memmingen: Licht, Zeitplan, Fotospots",
      tr: "Ulm, Augsburg ve Memmingen'de düğün mekânları: Işık, zaman planı, çekim noktaları",
    },
    excerpt: {
      de: "Drei Städte, drei sehr unterschiedliche Gesichter: die Monumentalität des Ulmer Münsters, die barocke Prachtstraße in Augsburg, die Renaissance-Enge in Memmingen. Wie sich das in der Hochzeitsplanung niederschlägt.",
      tr: "Üç şehir, birbirinden çok farklı üç yüz: Ulm Katedrali'nin anıtsallığı, Augsburg'un barok ana caddesi, Memmingen'in Rönesans dokusu. Düğün planlamasında bu farklar ne anlama geliyor.",
    },
    body: {
      de: [
        "Zwischen Ulm, Augsburg und Memmingen liegen weniger als 90 Autominuten – und drei völlig unterschiedliche Bildwelten. Wer für die eigene Hochzeit entscheidet, zwischen diesen Städten, trifft eine ästhetische Entscheidung, nicht nur eine logistische. Ein Überblick nach Standesamt, Trauort und Portraitspot.",
        "## Ulm: Maßstab und Weite",
        "Standesamtliche Trauungen finden im Schwörhaus statt – ein historisches Gebäude am Weinhof mit eigenem Trauzimmer und überdachtem Innenhof, der bei Regen funktioniert. Kirchliche Trauungen meistens im Ulmer Münster oder in der Pauluskirche; das Münster ist einer der hellsten gotischen Räume Süddeutschlands, liefert aber durch die Größe auch schnell einsame Bilder, wenn die Gesellschaft klein ist.",
        "Für Portraits ist der Weg kurz: Münsterplatz, Fischerviertel, Donauufer – alles in zehn Gehminuten. Der Münsterplatz ist frühmorgens und in der Stunde vor Sonnenuntergang am schönsten. Das Fischerviertel funktioniert zu jeder Tageszeit, braucht bei hartem Mittagslicht aber die schattigen Gassen zwischen den Fachwerkhäusern.",
        "## Augsburg: Pracht und Programm",
        "Das Augsburger Rathaus mit dem Goldenen Saal ist eine der aufwendigsten Trauungskulissen in Bayern. Standesämtlich getraut wird meist im Oberen Fletz; wer den Goldenen Saal selbst nutzen möchte, kann das in Absprache mit der Verwaltung – es braucht Vorlauf. Kirchliche Trauungen im Dom, in St. Ulrich und Afra oder St. Anna.",
        "Für Portraits ist die Maximilianstraße zwischen Herkulesbrunnen und Ulrichsplatz eine der besten Strecken. Die Fuggerei lohnt sich, wenn ihr vorher ein Zeitfenster abstimmt und den regulären Eintritt kalkuliert. Für Grün und Bäume reichen zehn Gehminuten zum Hofgarten.",
        "## Memmingen: Nähe und Charakter",
        "Memmingen trauen lässt sich im Grimmelhaus – zentrale Lage, sehr ruhige Trauzimmer. Von dort sind Marktplatz, Westertor und Stadtpark Neue Welt zu Fuß erreichbar, die drei wichtigsten Fotospots der Altstadt. Kirchliche Trauungen meistens in St. Martin oder St. Josef; bei beiden lohnt die Rücksprache mit dem Mesner wegen Position und Technik.",
        "Memminger Hochzeiten brauchen weniger Fahrtzeit als anderswo – die kurzen Wege machen den Tag entspannt, selbst wenn das Programm eng wird.",
        "## Was das für die Zeitplanung heißt",
        "Ulm braucht frühe oder späte Portraits, weil der Münsterplatz tagsüber nicht leer wird. Augsburg braucht mehr Puffer, weil die Maximilianstraße an Samstagen voll ist. Memmingen braucht am wenigsten Puffer, weil alles in Gehdistanz liegt. Drei Städte, drei verschiedene Zeitpläne – wer das vorher weiß, spart am Hochzeitstag die Minuten, die am Ende fehlen.",
      ],
      tr: [
        "Ulm, Augsburg ve Memmingen arası 90 dakika yoldan az – ama birbirinden tamamen farklı üç görsel dünya. Kendi düğünü için bu şehirler arasında seçim yapan çift, sadece lojistik değil estetik bir karar da veriyor. Nikah dairesi, tören yeri ve portre noktası üzerinden kısa bir bakış.",
        "## Ulm: Ölçek ve genişlik",
        "Resmî nikahlar Schwörhaus'ta yapılıyor – Weinhof'ta, kendi nikah salonu ve yağmurda da çalışan kapalı iç avlusu olan tarihi bir bina. Kilise törenleri çoğunlukla Ulm Katedrali veya Pauluskirche'de; katedral Güney Almanya'nın en aydınlık gotik iç mekânlarından biri ama büyüklüğü nedeniyle küçük düğünlerde kareler ister istemez boş kalabiliyor.",
        "Portre için yol kısa: Münsterplatz, Fischerviertel, Donau kıyısı – hepsi on dakikalık yürüyüş içinde. Münsterplatz, sabah erken saatte ve gün batımından önceki bir saatte en güzel. Fischerviertel her saatte çalışır; ama sert öğle ışığında ahşap evler arasındaki gölgeli sokaklara geçmek gerekiyor.",
        "## Augsburg: ihtişam ve program",
        "Augsburg Rathaus'taki Altın Salon, Bayern'in en gösterişli nikah dekorlarından biri. Resmî tören genelde Oberer Fletz'de olur; Altın Salon'u kullanmak isteyen çift, idareyle önceden görüşerek izin alabiliyor – zaman payı gerekiyor. Kilise törenleri Katedral'de, St. Ulrich ve Afra'da ya da St. Anna'da.",
        "Portreler için Maximilianstraße, Herkules Çeşmesi ile Ulrichsplatz arasındaki güzergâhta en iyi sonucu veriyor. Fuggerei, önceden zaman dilimi ayarlanıp normal giriş ücreti hesaba katıldığında değer. Yeşil ve ağaç isteyen çiftler için on dakikalık yürüyüşle Hofgarten'e çıkılıyor.",
        "## Memmingen: yakınlık ve karakter",
        "Memmingen'de nikah Grimmelhaus'ta – merkezi konum, çok sakin nikah salonları. Oradan Marktplatz, Westertor ve Stadtpark Neue Welt yürüyerek ulaşılabilir; eski şehrin en önemli üç çekim noktası. Kilise törenleri genelde St. Martin veya St. Josef'te; iki kilisede de konum ve teknik için zangoçla önceden konuşmak gerekir.",
        "Memmingen düğünleri başka şehirlerden daha az yol zamanı istiyor – kısa mesafeler, program sıkışsa bile günü rahat tutuyor.",
        "## Bunun zaman planı için anlamı",
        "Ulm erken ya da geç portre istiyor, çünkü Münsterplatz gün içinde boşalmıyor. Augsburg daha fazla pay istiyor, çünkü Maximilianstraße cumartesileri kalabalık. Memmingen en az payı istiyor, çünkü her şey yürüme mesafesinde. Üç şehir, üç farklı zaman planı – bunu önceden bilen çift, düğün günü sonunda eksilen dakikaları kazanıyor.",
      ],
    },
    faq: [
      {
        q: {
          de: "Braucht man im Goldenen Saal in Augsburg eine extra Genehmigung?",
          tr: "Augsburg'da Altın Salon için ek izin gerekiyor mu?",
        },
        a: {
          de: "Für die Trauung selbst nein, die Buchung läuft über das Standesamt. Für zusätzliche Portraits im Saal braucht es eine separate Absprache mit der Verwaltung, idealerweise schon bei der Terminreservierung.",
          tr: "Törenin kendisi için hayır, rezervasyon nikah dairesi üzerinden. Salonda ek portre çekimi için idareyle ayrı bir anlaşma gerekiyor, en iyisi tarih rezervasyonu sırasında.",
        },
      },
      {
        q: {
          de: "Welche der drei Städte eignet sich für eine Hochzeit bei Regen am besten?",
          tr: "Üç şehrin yağmurlu bir düğüne en uygunu hangisi?",
        },
        a: {
          de: "Memmingen – allein durch die Nähe von Grimmelhaus, Rathaus-Arkaden und Westertor lassen sich Trauung und Portraits komplett im Trockenen abwickeln. In Ulm und Augsburg funktioniert das auch, braucht aber mehr Planung.",
          tr: "Memmingen – Grimmelhaus, Rathaus revakları ve Westertor arasındaki yakınlık sayesinde nikah ve portreler tamamen kapalı alanda yapılabiliyor. Ulm ve Augsburg'da da mümkün ama daha çok planlama istiyor.",
        },
      },
    ],
  },

  {
    slug: "regenplan-zeitplan-hochzeit-schwaben",
    date: "2026-11-04",
    seed: "lum-blog-regen",
    citySlug: "memmingen",
    title: {
      de: "Regenplan und Zeitplan für die Hochzeit in Schwaben",
      tr: "Schwaben'de düğün için yağmur planı ve zaman planı",
    },
    excerpt: {
      de: "In Schwaben regnet es im Juni an etwa 13 Tagen pro Monat – nicht selten genau am gebuchten Samstag. Ein belastbarer Regenplan ist deshalb kein Extra, sondern Teil des Zeitplans. So sieht einer aus, der am Hochzeitstag wirklich trägt.",
      tr: "Schwaben'de haziran ayında ortalama 13 gün yağış görüyor – sıklıkla tam da rezerve edilmiş cumartesi gününde. Dolayısıyla sağlam bir yağmur planı bir ekstra değil, zaman planının parçasıdır. Düğün günü gerçekten işe yarayan bir plan nasıl olur.",
    },
    body: {
      de: [
        "In Krumbach, Ulm, Memmingen und Augsburg sind Sommerhochzeiten beliebt – und Sommer heißt in Schwaben nicht Dauersonne. Juni und Juli zählen zu den regenreichsten Monaten des Jahres; einzelne Hitzewochen wechseln sich mit Gewitternachmittagen ab. Wer daraus einen Plan ableitet, hat am Hochzeitstag deutlich weniger Stress.",
        "## Regen ist nicht das Problem, Planlosigkeit ist es",
        "Fast alle Hochzeiten, die durch Regen „aus dem Ruder laufen“, hatten keinen echten Plan B – nur die Hoffnung, dass der Plan A hält. Ein Regenplan ist etwas, das vorher vor Ort angesehen wurde, nicht etwas, das im Trauzimmer improvisiert wird.",
        "## Drei Zonen pro Ort",
        "Für jeden Hochzeitsort braucht ihr drei trockene Orte: eine überdachte Außenfläche für die ersten Gruppenbilder, einen Raum mit Fensterlicht für Portraits und einen Rückfallort, falls das Shooting unterbrochen werden muss. Die drei Orte sollten innerhalb von zehn Gehminuten erreichbar sein.",
        "## Beispiele aus der Region",
        "In Krumbach: Rathaus-Arkaden, Schlosspark-Allee, Grimmelhaus-ähnliche Innenhöfe. In Ulm: Kreuzgangarkaden am Münster, überhängende Fachwerkbalken im Fischerviertel, Hof des Schwörhauses. In Memmingen: Rathaus-Arkaden, Grimmelhaus selbst, Westertor. In Augsburg: Arkaden Maximilianstraße, Stadtmetzg-Innenhof, Vorhallen am Dom.",
        "## Zeitpuffer – der eigentliche Hebel",
        "Der verlässlichste Regenplan ist ein Zeitplan, der 15 Minuten Reserve pro Programmpunkt hat. Trauung 15 Minuten früher, Sektempfang mit Pufferminuten, Paarshooting mit doppelter Option – so lässt sich auch ein 20-Minuten-Gewitter aushalten, ohne das Abendessen nach hinten zu verschieben.",
        "## Was ihr im Vorfeld klärt",
        "Fragt die Location nach überdachten Außenbereichen, nach Stromanschlüssen für zusätzliche Lichtsetups und nach einer Räumlichkeit, die bei Umbau zur Feier für zwanzig Minuten frei bleibt. Diese drei Punkte sind der Unterschied zwischen einem improvisierten und einem geplanten Regentag.",
        "## Winter und Übergangszeit",
        "Winterhochzeiten in Schwaben sind unterschätzt: Schnee auf dem Marktplatz Memmingen oder im Hofgarten Augsburg ergibt Bilder, die im Sommer nicht möglich sind. Entscheidend ist, die kürzeren Tage einzurechnen – im Dezember ist Sonnenuntergang schon gegen 16:30 Uhr. Portraits werden entsprechend früher gelegt, nicht ans Ende des Tages.",
        "## Zusammengefasst",
        "Ein guter Plan hat nicht einen Ablauf, sondern zwei: für gutes Wetter und für Regen. Der zweite Plan ist mit 30 Minuten Vorarbeit erledigt – und spart am Hochzeitstag Stunden an Nerven.",
      ],
      tr: [
        "Krumbach, Ulm, Memmingen ve Augsburg'da yaz düğünü çok tercih ediliyor – ama yaz Schwaben'de \"sürekli güneş\" demek değil. Haziran ve temmuz yılın en yağışlı aylarından; sıcak haftalar, gök gürültülü öğle sonralarıyla değişiyor. Bundan bir plan çıkarmayı bilen çift, düğün günü belirgin biçimde daha az stres yaşıyor.",
        "## Sorun yağmur değil, plansızlık",
        "Yağmur yüzünden \"rayından çıkan\" düğünlerin neredeyse hepsinde gerçek bir B planı yok, sadece A planının tutacağına dair bir umut vardı. Yağmur planı, önceden yerinde görülmüş bir şeydir; nikah salonunda doğaçlama kurulan bir şey değil.",
        "## Her mekân için üç bölge",
        "Her düğün yerinde üç kuru alanınız olmalı: ilk grup fotoğrafları için kapalı bir dış alan, pencere ışığıyla portre için bir iç mekân ve çekimin kesilmesi durumunda sığınılacak bir yedek nokta. Üçünün de on dakika yürüyüş içinde erişilebilir olması gerekir.",
        "## Bölgeden örnekler",
        "Krumbach'ta: Rathaus revakları, Schlosspark ağaçlı yolu, iç avlular. Ulm'da: Münster revak galerisi, Fischerviertel'in çıkıntılı balkonları, Schwörhaus iç avlusu. Memmingen'de: Rathaus revakları, Grimmelhaus içi, Westertor. Augsburg'da: Maximilianstraße revakları, Stadtmetzg iç avlusu, Katedral ön holleri.",
        "## Zaman payı – asıl manivela",
        "En sağlam yağmur planı aslında her program maddesi için 15 dakika yedek bırakan bir zaman planıdır. Nikahı 15 dakika erkene, karşılamayı yedek dakikalarla, çift çekimini çift seçenekli – böylece 20 dakikalık bir yağmur bile akşam yemeğini ertelemeden atlatılıyor.",
        "## Önceden netleştirilecekler",
        "Mekâna şunu sorun: kapalı dış alanları var mı, ek aydınlatma için prizler nerede ve yemeğe geçiş sırasında yaklaşık yirmi dakika boş kalabilecek bir oda var mı. Bu üç nokta, doğaçlanmış bir yağmur günü ile planlanmış bir yağmur günü arasındaki farktır.",
        "## Kış ve geçiş sezonu",
        "Schwaben'de kış düğünleri hak ettiği değeri görmüyor: Memmingen Marktplatz'ında ya da Augsburg Hofgarten'da kar, yazın asla mümkün olmayan kareler veriyor. Burada belirleyici olan, kısalan günleri hesaba katmak – aralıkta gün batımı artık 16:30 civarında. Portreleri buna göre günün sonuna değil, erken saate koyuyoruz.",
        "## Özet",
        "İyi bir planın tek değil, iki akışı vardır: iyi hava için ve yağmur için. İkinci plan 30 dakikalık ön çalışmayla hazırlanıyor – ve düğün günü saatlerce sinir kazandırıyor.",
      ],
    },
    faq: [
      {
        q: {
          de: "Wann ist in Schwaben die wetterstabilste Hochzeitszeit?",
          tr: "Schwaben'de hava en istikrarlı düğün dönemi hangisi?",
        },
        a: {
          de: "Statistisch Ende August bis Mitte September. Juni und Juli sind beliebt, aber eben auch regenreich; Mai ist warm genug, hat aber öfter Frühlingsgewitter am Nachmittag.",
          tr: "İstatistiksel olarak ağustos sonu ile eylül ortası arası. Haziran ve temmuz çok tercih ediliyor ama en yağışlı aylar; mayıs sıcak, öte yandan öğleden sonra ilkbahar yağışları oluyor.",
        },
      },
      {
        q: {
          de: "Lohnt sich eine Winterhochzeit in Krumbach oder Memmingen?",
          tr: "Krumbach ya da Memmingen'de kış düğünü mantıklı mı?",
        },
        a: {
          de: "Ja, wenn der Zeitplan an die kurzen Tage angepasst wird. Schnee auf historischen Marktplätzen ist fotografisch stark – aber Portraits gehören dann in die späte Mittagszeit, nicht ans Ende des Abends.",
          tr: "Evet, zaman planı kısa günlere uyarlanırsa. Tarihi meydanlarda kar, fotoğrafik olarak çok güçlü – ama portreleri o zaman günün sonuna değil, geç öğlene koymak gerekir.",
        },
      },
    ],
  },

  {
    slug: "hochzeitsfotograf-finden-sieben-fragen",
    date: "2026-05-18",
    seed: "lum-blog-fragen",
    citySlug: "krumbach",
    title: {
      de: "Hochzeitsfotograf finden: 7 Fragen, die ihr vor der Buchung stellen solltet",
      tr: "Düğün fotoğrafçısı seçerken sormanız gereken 7 soru",
    },
    excerpt: {
      de: "Der Preis allein sagt wenig. Diese sieben Fragen zeigen euch in zehn Minuten, ob jemand zu eurem Tag passt – und decken die Punkte auf, über die später am häufigsten gestritten wird.",
      tr: "Fiyat tek başına bir şey söylemez. Bu yedi soru, on dakikada size uygun olup olmadığını gösterir – ve sonradan en çok tartışma çıkan noktaları önceden ortaya koyar.",
    },
    body: {
      de: [
        "Die meisten Paare buchen ihren Hochzeitsfotografen nach zwei Kriterien: Bildsprache und Preis. Beides ist wichtig, aber beides sagt wenig darüber aus, wie der Tag tatsächlich ablaufen wird. Die folgenden Fragen kosten euch ein Gespräch und ersparen euch im Zweifel eine Enttäuschung.",
        "## 1. Wer fotografiert tatsächlich bei uns?",
        "Bei größeren Studios ist die Person im Erstgespräch nicht immer die Person am Hochzeitstag. Lasst euch den Namen geben und fragt nach einer vollständigen Hochzeit von genau dieser Person – nicht nach einer Best-of-Auswahl aus mehreren Aufträgen.",
        "## 2. Dürfen wir eine komplette Hochzeit sehen?",
        "Eine Galerie mit 30 Höhepunkten kann jeder zusammenstellen. Interessant ist, wie die zweihundert Bilder dazwischen aussehen: die Rede, die niemand geplant hat, der dunkle Saal um 22 Uhr, die Familienaufstellung mit 40 Personen.",
        "## 3. Was passiert bei Krankheit oder Ausfall?",
        "Ein seriöser Vertrag regelt das schriftlich: Ersatz aus einem Netzwerk innerhalb einer festen Frist oder vollständige Rückerstattung. Eine mündliche Zusage ist an dieser Stelle zu wenig.",
        "## 4. Wie lange dauert die Lieferung – und was steht im Vertrag?",
        "„Ein paar Wochen“ ist keine Frist. Fragt nach konkreten Zahlen für Vorschau, vollständige Galerie und Film, und danach, ob diese Fristen vertraglich zugesichert sind.",
        "## 5. Wie viele Bilder bekommen wir, und in welcher Form?",
        "Wichtig ist weniger die Anzahl als das Format: Bekommt ihr die Bilder in voller Auflösung zum Herunterladen? Gibt es eine Online-Galerie? Dürft ihr die Bilder privat frei nutzen?",
        "## 6. Wart ihr schon einmal in unserer Location?",
        "Wer die Location kennt, weiß, wo um 18 Uhr noch Licht steht und welche Wege wie lange dauern. Wer sie nicht kennt, sollte vorher hinfahren – fragt, ob das eingeplant ist.",
        "## 7. Was passiert mit unseren Bildern?",
        "Veröffentlichung auf Website und Social Media darf nur mit gesonderter, freiwilliger Einwilligung erfolgen. Diese Einwilligung muss unabhängig vom Fotoauftrag sein und jederzeit widerrufbar bleiben.",
        "Wenn eine Fotografin oder ein Fotograf auf alle sieben Fragen ruhig und konkret antwortet, habt ihr die wichtigste Information schon: Da arbeitet jemand, der seinen Ablauf kennt.",
      ],
      tr: [
        "Çiftlerin çoğu düğün fotoğrafçısını iki ölçüte göre seçiyor: görsel tarz ve fiyat. İkisi de önemli, ama ikisi de günün gerçekte nasıl geçeceği hakkında pek bir şey söylemiyor. Aşağıdaki sorular size bir görüşmeye mal olur, karşılığında olası bir hayal kırıklığını önler.",
        "## 1. Bizim düğünümüzde gerçekte kim çekecek?",
        "Büyük stüdyolarda ilk görüşmedeki kişi, düğün günü gelen kişi olmayabiliyor. İsmi net alın ve tam olarak o kişinin çektiği eksiksiz bir düğün isteyin – farklı işlerden derlenmiş bir seçki değil.",
        "## 2. Eksiksiz bir düğün görebilir miyiz?",
        "Otuz karelik bir seçkiyi herkes hazırlayabilir. Asıl mesele aradaki iki yüz karenin nasıl olduğu: kimsenin planlamadığı konuşma, gece 22'de karanlık salon, kırk kişilik aile fotoğrafı.",
        "## 3. Hastalık ya da aksilik durumunda ne oluyor?",
        "Ciddi bir sözleşme bunu yazılı düzenler: belirli bir süre içinde ağdan eşdeğer ekip ya da tam iade. Bu konuda sözlü güvence yeterli değil.",
        "## 4. Teslim ne kadar sürüyor – ve sözleşmede ne yazıyor?",
        "\"Birkaç hafta\" bir süre değildir. Ön izleme, tam galeri ve film için somut sayılar isteyin; bu sürelerin sözleşmeyle güvence altına alınıp alınmadığını sorun.",
        "## 5. Kaç kare alıyoruz ve hangi biçimde?",
        "Sayıdan çok biçim önemli: Kareleri tam çözünürlükte indirebiliyor musunuz? Online galeri var mı? Kareleri özel kullanımda serbestçe kullanabiliyor musunuz?",
        "## 6. Bizim mekânımızda daha önce çekim yaptınız mı?",
        "Mekânı bilen kişi, saat 18'de ışığın nerede durduğunu ve hangi geçişin ne kadar sürdüğünü bilir. Bilmeyen önceden gidip görmeli – bunun planlanıp planlanmadığını sorun.",
        "## 7. Karelerimize ne olacak?",
        "Web sitesinde ve sosyal medyada yayın, yalnızca ayrı ve gönüllü bir izinle yapılabilir. Bu izin fotoğraf işinden bağımsız olmalı ve her zaman geri alınabilmeli.",
        "Bir fotoğrafçı bu yedi sorunun hepsine sakin ve somut cevap veriyorsa, en önemli bilgiyi zaten almışsınız demektir: karşınızda akışını bilen biri var.",
      ],
    },
    faq: [
      {
        q: {
          de: "Wie früh sollten wir den Hochzeitsfotografen buchen?",
          tr: "Düğün fotoğrafçısını ne kadar önceden ayırtmalıyız?",
        },
        a: {
          de: "Für Samstage zwischen Mai und September sind zwölf bis achtzehn Monate üblich. Unter der Woche und in der Nebensaison reichen oft sechs Monate.",
          tr: "Mayıs–Eylül arası cumartesiler için on iki ila on sekiz ay normaldir. Hafta içi ve sezon dışında çoğu zaman altı ay yeterli oluyor.",
        },
      },
      {
        q: {
          de: "Ist ein zweiter Fotograf notwendig?",
          tr: "İkinci bir fotoğrafçı gerekli mi?",
        },
        a: {
          de: "Ab etwa 120 Gästen oder wenn sich Braut und Bräutigam getrennt vorbereiten, lohnt sich die zweite Kamera fast immer. Bei kleinen Feiern ist sie meist verzichtbar.",
          tr: "Yaklaşık 120 kişiden sonra ya da gelin ve damat ayrı hazırlanıyorsa ikinci kamera neredeyse her zaman işe yarıyor. Küçük kutlamalarda genelde gerekmiyor.",
        },
      },
    ],
  },

  {
    slug: "hochzeit-zeitplan-licht",
    date: "2026-06-24",
    seed: "lum-blog-zeitplan",
    citySlug: "ulm",
    title: {
      de: "Der Zeitplan, der wirklich funktioniert – und warum das Licht ihn bestimmt",
      tr: "Gerçekten işe yarayan düğün akışı – ve neden ışık belirler",
    },
    excerpt: {
      de: "Die schönsten Paarbilder entstehen in einem Zeitfenster von 40 Minuten. Wer den Tag darum herum plant, braucht keine Bilder nachzustellen. So sieht ein realistischer Ablauf aus.",
      tr: "En güzel çift kareleri 40 dakikalık bir aralıkta çıkıyor. Günü onun etrafına kuran çift, hiçbir kareyi yeniden kurgulamak zorunda kalmıyor. Gerçekçi bir akış şöyle görünür.",
    },
    body: {
      de: [
        "Fast jede Hochzeit, bei der am Ende Zeit fehlt, hat dasselbe Problem: Der Ablauf wurde nach den Wünschen der Location geplant, nicht nach dem Licht. Dabei ist die goldene Stunde der einzige Punkt im Tagesablauf, den niemand verschieben kann.",
        "## Wann ist die goldene Stunde?",
        "Sie beginnt etwa eine Stunde vor Sonnenuntergang und dauert je nach Jahreszeit 30 bis 50 Minuten. Im Juni in Bayern und Schwaben heißt das ungefähr 20:15 Uhr, Ende September bereits 18:30 Uhr. Ein Unterschied von fast zwei Stunden – geplant wird der Tag aber oft mit demselben Schema.",
        "## Ein Ablauf, der aufgeht",
        "Getting Ready ab drei Stunden vor der Trauung, mit Puffer für die Anfahrt. Trauung. Danach Sektempfang und Gruppenbilder, solange alle Gäste noch beisammen sind – das dauert mit 40 Personen etwa 25 Minuten, nicht zehn.",
        "Der entscheidende Block: 30 bis 45 Minuten Paarshooting, gelegt auf die goldene Stunde. Das bedeutet, dass das Abendessen entweder davor endet oder danach beginnt. Beides funktioniert – nur mitten hinein sollte es nicht fallen.",
        "## Die drei häufigsten Fehler",
        "Erstens: Gruppenbilder erst nach dem Essen. Dann sind einige Gäste schon draußen, andere nicht mehr ganz bei der Sache. Zweitens: kein Puffer zwischen Trauung und Empfang – zehn Minuten Verspätung verschieben den ganzen Abend. Drittens: das Paarshooting auf die Mittagszeit legen, weil dann gerade Zeit ist. Hartes Mittagslicht ist die schwierigste Situation des Tages.",
        "## Was tun, wenn es regnet?",
        "Ein guter Plan hat für jeden Außenpunkt eine Innenalternative, die vorher angesehen wurde. Regen ist kein Problem, wenn er eingeplant ist – überdachte Eingänge, Fensterlicht und Schirme ergeben oft die ungewöhnlicheren Bilder.",
      ],
      tr: [
        "Sonunda zaman yetmeyen düğünlerin neredeyse hepsinde aynı sorun var: akış, mekânın istekleri üzerine kurulmuş, ışığın üzerine değil. Oysa altın saat, günün kimsenin erteleyemeyeceği tek noktası.",
        "## Altın saat ne zaman?",
        "Gün batımından yaklaşık bir saat önce başlar ve mevsime göre 30–50 dakika sürer. Bayern ve Schwaben'de haziranda bu aşağı yukarı 20:15, eylül sonunda ise 18:30 demek. Neredeyse iki saatlik fark – ama gün çoğu zaman aynı şablonla planlanıyor.",
        "## İşleyen bir akış",
        "Hazırlık, nikahtan üç saat önce başlar, yol için pay bırakılır. Nikah. Ardından karşılama ve grup fotoğrafları – henüz herkes bir aradayken. Kırk kişiyle bu on değil, yaklaşık 25 dakika sürer.",
        "Belirleyici blok: altın saate denk getirilmiş 30–45 dakikalık çift çekimi. Bu da akşam yemeğinin ya ondan önce bitmesi ya da sonra başlaması demek. İkisi de olur – yeter ki tam ortasına denk gelmesin.",
        "## En sık yapılan üç hata",
        "Birincisi: grup fotoğraflarını yemekten sonraya bırakmak. O saatte bir kısım misafir dışarıda, bir kısmının aklı başka yerde olur. İkincisi: nikah ile karşılama arasına pay koymamak – on dakikalık gecikme bütün akşamı kaydırır. Üçüncüsü: çift çekimini öğlene almak, çünkü \"o saatte boşluk var\". Sert öğle ışığı günün en zor koşuludur.",
        "## Yağmur yağarsa?",
        "İyi bir planda her açık hava noktasının, önceden görülmüş bir kapalı alternatifi vardır. Yağmur, planlandığı sürece sorun değildir – kapalı girişler, pencere ışığı ve şemsiyeler çoğu zaman daha sıra dışı kareler verir.",
      ],
    },
    faq: [
      {
        q: { de: "Wie lange dauert ein Paarshooting?", tr: "Çift çekimi ne kadar sürer?" },
        a: {
          de: "30 bis 45 Minuten reichen für die Bilder, die später wirklich verwendet werden. Längere Shootings werden für die meisten Paare anstrengend, ohne dass die Ergebnisse besser werden.",
          tr: "Sonradan gerçekten kullanılan kareler için 30–45 dakika yeterli. Daha uzun çekimler çoğu çift için yorucu oluyor ve sonuç daha iyi olmuyor.",
        },
      },
      {
        q: { de: "Sollen die Gruppenbilder vor oder nach dem Essen sein?", tr: "Grup fotoğrafları yemekten önce mi sonra mı?" },
        a: {
          de: "Vorher, direkt im Anschluss an den Sektempfang. Dann sind alle Gäste anwesend und die Aufstellung dauert halb so lang.",
          tr: "Önce, karşılamanın hemen ardından. O anda tüm misafirler orada olur ve dizilim yarı sürede tamamlanır.",
        },
      },
    ],
  },

  {
    slug: "deutsch-tuerkische-hochzeit-fotografieren",
    date: "2026-07-30",
    seed: "lum-blog-kina",
    citySlug: "krumbach",
    title: {
      de: "Deutsch-türkische Hochzeit: Was beim Fotografieren wirklich zählt",
      tr: "Alman-Türk düğünü: Fotoğrafta gerçekten önemli olan ne?",
    },
    excerpt: {
      de: "Zwei Feiern, zwei Familien, oft zwei Sprachen. Wer den Ablauf einer Kına-Nacht und eines Nikah kennt, verpasst die Momente nicht, auf die es ankommt – hier die Punkte, die im Zeitplan stehen müssen.",
      tr: "İki kutlama, iki aile, çoğu zaman iki dil. Kına gecesinin ve nikahın akışını bilen, önemli anları kaçırmaz – akışta mutlaka yer alması gereken noktalar.",
    },
    body: {
      de: [
        "Eine deutsch-türkische Hochzeit ist selten ein Tag. Häufig sind es zwei oder drei: die Kına-Nacht, das Standesamt und die große Feier – manchmal über zwei Wochenenden verteilt, manchmal an aufeinanderfolgenden Abenden. Für die Fotografie heißt das vor allem: vorher klären, was wann stattfindet und wer welche Rolle hat.",
        "## Die Kına-Nacht ist kein Vorprogramm",
        "Der Henna-Abend ist emotional oft der intensivste Teil der ganzen Feier. Der Moment, in dem der Braut die Hand geschlossen wird, während die Schwiegermutter die Goldmünze hineinlegt, dauert vielleicht 40 Sekunden. Wer in dieser Zeit den Objektivwechsel macht, hat ihn verpasst.",
        "Praktisch bedeutet das: zwei Kameras, kein Wechsel während des Rituals, und vorher wissen, aus welcher Richtung die Braut hereingeführt wird. Das Licht ist meist warm und schwach – lichtstarke Festbrennweiten sind hier keine Spielerei, sondern Voraussetzung.",
        "## Standesamt und Nikah trennen",
        "Viele Paare haben eine standesamtliche Trauung und zusätzlich einen Nikah. Beide brauchen Zeit im Plan, auch wenn der eine Termin nur 20 Minuten dauert. Fragt vorher, ob im Trauzimmer fotografiert werden darf und wo genau ihr stehen dürft – die Regeln unterscheiden sich von Ort zu Ort.",
        "## Der Einzug und das Geldanstecken",
        "Bei der großen Feier sind zwei Programmpunkte gesetzt: der Einzug des Paares und das Anstecken von Gold und Geld. Letzteres kann bei großen Familien 45 Minuten dauern und ist für die Gäste einer der wichtigsten Momente – im Zeitplan wird er trotzdem regelmäßig vergessen.",
        "## Zweisprachigkeit ist Arbeitserleichterung",
        "Wenn eine Großmutter nur Türkisch spricht und der Trauzeuge nur Deutsch, entscheidet die Verständigung darüber, wie entspannt die Gruppenbilder werden. Ein Team, das beide Sprachen spricht, spart bei 40 Personen leicht zehn Minuten – und die Bilder sehen entsprechend gelöster aus.",
        "## Was in den Zeitplan gehört",
        "Kına-Ritual, Einzug, Nikah beziehungsweise Trauung, Goldanstecken, Eröffnungstanz, Halay. Für jeden dieser Punkte eine Uhrzeit und eine grobe Dauer – mehr braucht es nicht, damit der Tag ohne Kommandos abläuft.",
      ],
      tr: [
        "Alman-Türk düğünü nadiren tek bir gündür. Çoğu zaman iki ya da üç: kına gecesi, nikah dairesi ve büyük düğün – kimi zaman iki hafta sonuna yayılır, kimi zaman ardışık akşamlarda olur. Fotoğraf açısından bunun anlamı şu: neyin ne zaman olduğu ve kimin hangi rolde olduğu önceden netleşmeli.",
        "## Kına gecesi bir ön program değildir",
        "Kına, duygusal olarak çoğu zaman tüm kutlamanın en yoğun bölümüdür. Kaynananın altını avuca koyup gelinin elinin kapatıldığı an belki 40 saniye sürer. O sırada lens değiştiren, o anı kaçırmıştır.",
        "Pratikte bu şu demek: iki kamera, ritüel sırasında değişiklik yok ve gelinin hangi yönden getirileceğini önceden bilmek. Işık genelde sıcak ve zayıftır – geniş diyaframlı sabit lensler burada bir lüks değil, gereklilik.",
        "## Nikah dairesi ile dinî nikahı ayırın",
        "Birçok çiftin hem resmî nikahı hem de dinî nikahı oluyor. İkisi de planda yer kaplamalı, biri yalnızca 20 dakika sürse bile. Nikah salonunda fotoğraf çekilip çekilemeyeceğini ve tam olarak nerede durabileceğinizi önceden sorun – kurallar yere göre değişiyor.",
        "## Giriş ve takı takma",
        "Büyük düğünde iki program maddesi kesindir: çiftin girişi ve altın/para takma. İkincisi kalabalık ailelerde 45 dakikayı bulabilir ve misafirler için en önemli anlardan biridir – buna rağmen akış planında düzenli olarak unutulur.",
        "## İki dillilik işi kolaylaştırır",
        "Bir anneanne yalnızca Türkçe, sağdıç yalnızca Almanca konuşuyorsa, grup fotoğraflarının ne kadar rahat geçeceğini anlaşma belirler. İki dili de konuşan bir ekip, kırk kişilik dizilimde rahatlıkla on dakika kazandırır – kareler de o oranda rahat çıkar.",
        "## Akışta mutlaka olması gerekenler",
        "Kına ritüeli, giriş, nikah, takı takma, ilk dans, halay. Bu maddelerin her biri için bir saat ve kabaca bir süre – günün komut verilmeden akması için fazlası gerekmiyor.",
      ],
    },
    faq: [
      {
        q: {
          de: "Braucht man für Kına-Nacht und Hochzeit zwei getrennte Buchungen?",
          tr: "Kına gecesi ve düğün için iki ayrı anlaşma mı gerekiyor?",
        },
        a: {
          de: "Nein. Beide Termine lassen sich in einem Paket zusammenfassen; das ist in der Regel günstiger als zwei Einzelbuchungen und sorgt dafür, dass beide Abende dieselbe Bildsprache haben.",
          tr: "Hayır. İki tarih tek pakette birleştirilebilir; bu genelde iki ayrı anlaşmadan daha uygun oluyor ve iki akşamın da aynı görsel dilde olmasını sağlıyor.",
        },
      },
      {
        q: {
          de: "Wie lange dauert das Goldanstecken?",
          tr: "Takı takma ne kadar sürer?",
        },
        a: {
          de: "Bei 150 Gästen sind 30 bis 45 Minuten realistisch. Plant diesen Punkt fest ein, sonst verschiebt er den ganzen Abend.",
          tr: "150 misafirde 30–45 dakika gerçekçi. Bu maddeyi akışa sabit koyun, yoksa bütün akşamı kaydırıyor.",
        },
      },
    ],
  },
];

export const postBySlug = (slug: string) => posts.find((p) => p.slug === slug);
