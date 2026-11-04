import type { L } from "./i18n";

export type City = {
  slug: string;
  name: string;
  kreis: L;
  drive: L;
  lead: L;
  body: L<string[]>;
  spots: { name: string; note: L }[];
  venues: string[];
  neighbours: string[];
  faq: { q: L; a: L }[];
};

export const cities: City[] = [
  {
    slug: "krumbach",
    name: "Krumbach",
    kreis: { de: "Landkreis Günzburg (Bayern)", tr: "Günzburg ilçesi (Bayern)" },
    drive: { de: "Atelier vor Ort", tr: "Atölye şehir içinde" },
    lead: {
      de: "Hochzeitsfotograf und Videograf in Krumbach – dokumentarisch gearbeitet, mit ruhigem Blick für die Momente zwischen den Programmpunkten und ortskundig zwischen Marktplatz, Hubertussee und Schloss Hürben.",
      tr: "Krumbach'ta düğün fotoğrafçısı ve videografı – belgesel çalışma, program arasındaki anları yakalayan sakin bir bakış ve Marktplatz, Hubertussee ve Hürben Sarayı arasında bölgeyi tanıyan yerel deneyim.",
    },
    body: {
      de: [
        "Krumbach ist unsere Heimat. Standesamt im Rathaus am Marktplatz, kirchliche Trauung in der Stadtpfarrkirche oder in St. Michael in Hürben – wir kennen die Wege und den Zeitpuffer, den Brautpaare zwischen zwei Terminen in der Stadt wirklich brauchen.",
        "Für Portraits stehen wenige Autominuten entfernt gleich mehrere Kulissen bereit: der Schlosspark in Hürben mit Teich und altem Baumbestand, der stille Hubertussee am Stadtrand und das Kammeltal für weite Wiesenaufnahmen im Abendlicht.",
      ],
      tr: [
        "Krumbach bizim evimiz. Marktplatz'taki belediyede resmi nikah, Stadtpfarrkirche'de veya Hürben'deki St. Michael'de kilise töreni – iki yer arasındaki yolları ve çiftin gerçekten ihtiyaç duyduğu zaman payını biliyoruz.",
        "Portre için birkaç dakika uzakta birden fazla dekor var: gölet ve yaşlı ağaçlarıyla Hürben Schlosspark, şehir kenarındaki sakin Hubertussee ve akşam ışığında geniş çayır çekimleri için Kammeltal.",
      ],
    },
    spots: [
      {
        name: "Marktplatz & Rathaus",
        note: {
          de: "Direkt vor dem Standesamt, Fachwerk im Hintergrund.",
          tr: "Nikah dairesinin önü, arka planda ahşap cepheler.",
        },
      },
      {
        name: "Schlosspark Hürben",
        note: {
          de: "Teich mit Spiegelung, Allee und ruhige Rasenflächen.",
          tr: "Yansımalı gölet, ağaçlı yol ve sakin çimenlik alanlar.",
        },
      },
      {
        name: "Hubertussee",
        note: {
          de: "Wasser und Bäume ganz in der Nähe – ideal für die goldene Stunde.",
          tr: "Hemen yakında su ve ağaçlar – altın saat için ideal.",
        },
      },
      {
        name: "Kammeltal",
        note: {
          de: "Weite Felder und sanfte Hügel, knapp außerhalb von Krumbach.",
          tr: "Geniş tarlalar ve yumuşak tepeler, Krumbach'ın hemen dışında.",
        },
      },
    ],
    venues: [],
    neighbours: ["guenzburg", "ulm", "memmingen", "augsburg"],
    faq: [
      {
        q: {
          de: "Wo liegt euer Atelier?",
          tr: "Atölyeniz nerede?",
        },
        a: {
          de: "Direkt in Krumbach. Für ein unverbindliches Vorgespräch treffen wir uns nach Absprache im Atelier oder digital per Videogespräch.",
          tr: "Krumbach'ın içinde. Taahhütsüz ön görüşme için randevuyla atölyede ya da video görüşmeyle buluşuyoruz.",
        },
      },
      {
        q: {
          de: "Was passiert, wenn es am Hochzeitstag in Krumbach regnet?",
          tr: "Krumbach'ta düğün günü yağmur olursa ne oluyor?",
        },
        a: {
          de: "Für Portraits weichen wir in den überdachten Arkadenbereich am Marktplatz, in das Treppenhaus im Rathaus oder in die Allee im Schlosspark aus – dort bleibt es auch bei Regen trocken und das Licht funktioniert.",
          tr: "Portreleri Marktplatz'taki revak altına, belediyenin iç merdivenine ya da Schlosspark'taki ağaçlı yola taşıyoruz – oralar yağmurda da kuru kalır ve ışık işe yarar.",
        },
      },
      {
        q: {
          de: "Fotografiert ihr auch Henna-Abende und türkische Hochzeiten in Krumbach?",
          tr: "Krumbach'ta kına gecesi ve Türk düğünleri de çekiyor musunuz?",
        },
        a: {
          de: "Ja. Wir begleiten regelmäßig zweisprachige Feiern und sprechen Deutsch und Türkisch – das macht die Absprache mit beiden Familien einfacher.",
          tr: "Evet. İki dilli düğünleri düzenli çekiyoruz, Almanca ve Türkçe konuşuyoruz – iki aileyle anlaşmak bu sayede kolaylaşıyor.",
        },
      },
    ],
  },
  {
    slug: "guenzburg",
    name: "Günzburg",
    kreis: { de: "Landkreis Günzburg (Bayern)", tr: "Günzburg ilçesi (Bayern)" },
    drive: { de: "Rund 20 Minuten", tr: "Yaklaşık 20 dakika" },
    lead: {
      de: "Hochzeitsfotograf in Günzburg – barocke Altstadt, der Markt, die Frauenkirche von Dominikus Zimmermann und ruhige Donauauen innerhalb weniger Minuten.",
      tr: "Günzburg'da düğün fotoğrafçısı – barok eski şehir, Marktplatz, Dominikus Zimmermann'ın Frauenkirche'si ve dakikalar içinde ulaşılan sakin Donau kıyıları.",
    },
    body: {
      de: [
        "Günzburg liegt im selben Landkreis wie unser Atelier. Wir kennen die Trauzeiten im Rathaus, die ruhigen Stellen am Marktplatz an einem Samstagvormittag und die Lichtsituation in der Frauenkirche – einer der schönsten Rokokokirchen Süddeutschlands.",
        "Für Portraits bieten sich der Schlosspark und die Donauauen an. Wer zwischen Trauung und Feier eine halbe Stunde Zeit hat, kann sie hier so nutzen, dass trotzdem der ganze Tag entspannt bleibt.",
      ],
      tr: [
        "Günzburg atölyemizle aynı ilçede. Belediyedeki nikah saatlerini, cumartesi sabahı Marktplatz'ın sakin köşelerini ve Frauenkirche'nin — Güney Almanya'nın en güzel rokoko kiliselerinden birinin — ışık durumunu biliyoruz.",
        "Portre için Schlosspark ve Donau kıyıları ideal. Nikah ile kutlama arasında yarım saati olan çiftler, burayı kullanıp günü yine de rahat tutabiliyor.",
      ],
    },
    spots: [
      {
        name: "Marktplatz Günzburg",
        note: {
          de: "Pastellfarbene Fassaden, klassische Postkartenkulisse.",
          tr: "Pastel cepheler, klasik kartpostal manzarası.",
        },
      },
      {
        name: "Frauenkirche",
        note: {
          de: "Innenaufnahmen nach Absprache mit der Kirchengemeinde.",
          tr: "İç mekân çekimleri kilise idaresiyle anlaşarak.",
        },
      },
      {
        name: "Schlosspark",
        note: {
          de: "Ruhige Wege, schöner Baumbestand.",
          tr: "Sakin yollar, güzel ağaçlar.",
        },
      },
      {
        name: "Donauauen",
        note: {
          de: "Wasser, Schilf, viel Platz für Weitwinkel.",
          tr: "Su, sazlık, geniş açı için bolca alan.",
        },
      },
    ],
    venues: [],
    neighbours: ["krumbach", "ulm", "neu-ulm", "augsburg"],
    faq: [
      {
        q: {
          de: "Darf in der Frauenkirche Günzburg fotografiert werden?",
          tr: "Günzburg Frauenkirche'de fotoğraf çekilebiliyor mu?",
        },
        a: {
          de: "Ja, in Absprache mit der Kirchengemeinde und mit Rücksicht auf die Zeremonie. Wir arbeiten mit lichtstarken Objektiven ohne Blitz, damit die Trauung ungestört bleibt.",
          tr: "Evet, kilise idaresiyle anlaşarak ve törene saygı göstererek. Flaşsız, geniş diyaframlı lenslerle çalışıyoruz; tören bozulmasın diye.",
        },
      },
      {
        q: {
          de: "Berechnet ihr Anfahrt nach Günzburg?",
          tr: "Günzburg'a yol ücreti alıyor musunuz?",
        },
        a: {
          de: "Nein. Günzburg liegt in unserem direkten Einzugsgebiet; die Anfahrt ist bei allen Paketen enthalten.",
          tr: "Hayır. Günzburg birincil hizmet bölgemizde; yol ücreti tüm paketlere dahildir.",
        },
      },
      {
        q: {
          de: "Was tun bei schlechtem Wetter rund um Günzburg?",
          tr: "Günzburg çevresinde hava kötü olursa ne yapıyoruz?",
        },
        a: {
          de: "Wir planen immer einen Regenplan mit ein: überdachte Portraits am Rathausplatz, in der Vorhalle der Frauenkirche oder im historischen Dossenbergerhaus, sobald die Verwaltung grünes Licht gibt.",
          tr: "Her zaman bir yağmur planı hazırlıyoruz: Rathausplatz revakları altında, Frauenkirche girişinde ya da izin alındığında tarihi Dossenbergerhaus içinde portreler.",
        },
      },
    ],
  },
  {
    slug: "ulm",
    name: "Ulm",
    kreis: { de: "Stadtkreis Ulm (Baden-Württemberg)", tr: "Ulm şehri (Baden-Württemberg)" },
    drive: { de: "Rund 45 Minuten vom Atelier", tr: "Atölyeden yaklaşık 45 dakika" },
    lead: {
      de: "Hochzeitsfotograf in Ulm – vom Münsterplatz über das Fischerviertel bis an die Donau entstehen auf engem Raum sehr unterschiedliche Bilder.",
      tr: "Ulm'da düğün fotoğrafçısı – Münsterplatz'tan Fischerviertel'e ve Donau kıyısına kadar küçük bir alanda birbirinden çok farklı kareler çıkıyor.",
    },
    body: {
      de: [
        "Ulm vereint drei Welten auf wenigen hundert Metern: die Monumentalität des Ulmer Münsters mit dem höchsten Kirchturm der Welt, die mittelalterliche Enge und Schiefstellung des Fischerviertels und die weite Donaupromenade mit Blick nach Neu-Ulm.",
        "Standesamtliche Trauungen finden im Schwörhaus statt, die kirchliche Trauung häufig im Münster oder in der Pauluskirche. Für Portraits weichen wir am liebsten früh am Morgen oder in der letzten Stunde vor Sonnenuntergang aus, wenn der Münsterplatz und das Fischerviertel ruhiger sind.",
      ],
      tr: [
        "Ulm birkaç yüz metre içinde üç dünyayı birleştiriyor: dünyanın en yüksek kilise kulesine sahip Ulm Katedrali'nin anıtsallığı, Fischerviertel'in ortaçağ darlığı ve eğri evleri ve Neu-Ulm'a bakan geniş Donau kıyısı.",
        "Resmi nikahlar Schwörhaus'ta, kilise törenleri genellikle Münster'de ya da Pauluskirche'de yapılıyor. Portreler için en çok sabahın erken saatlerini ya da gün batımından önceki son saati seçiyoruz; Münsterplatz ve Fischerviertel o saatte daha sakin.",
      ],
    },
    spots: [
      {
        name: "Münsterplatz",
        note: {
          de: "Maßstab und Weite – frühmorgens ohne Besucher am schönsten.",
          tr: "Ölçek ve genişlik – sabah erkenden, ziyaretçisiz en güzel.",
        },
      },
      {
        name: "Fischerviertel",
        note: {
          de: "Fachwerk, schiefes Haus, Brücken über die Blau.",
          tr: "Ahşap cepheler, eğik ev, Blau üzerindeki köprüler.",
        },
      },
      {
        name: "Donaupromenade & Stadtmauer",
        note: {
          de: "Blick nach Neu-Ulm, am Abend warmes Gegenlicht.",
          tr: "Neu-Ulm manzarası, akşamları sıcak kontra ışık.",
        },
      },
      {
        name: "Friedrichsau",
        note: {
          de: "Altbaumbestand, ruhige Wiesen, wenige Minuten vom Zentrum.",
          tr: "Yaşlı ağaçlar, sakin çayırlar, merkezden birkaç dakika.",
        },
      },
    ],
    venues: [],
    neighbours: ["neu-ulm", "guenzburg", "memmingen", "augsburg"],
    faq: [
      {
        q: {
          de: "Wann ist die beste Uhrzeit für Fotos am Ulmer Münster?",
          tr: "Ulm Katedrali'nde en iyi çekim saati hangisi?",
        },
        a: {
          de: "Entweder zwischen 7:00 und 8:30 Uhr, bevor Touristen und Lieferverkehr unterwegs sind, oder in der letzten Stunde vor Sonnenuntergang, wenn die Westfassade warm ausgeleuchtet ist.",
          tr: "Ya 7:00–8:30 arası, turistler ve servis araçları henüz gelmeden; ya da gün batımından önceki son saat, batı cephesi sıcak ışıkla aydınlandığında.",
        },
      },
      {
        q: {
          de: "Was tun, wenn es in Ulm regnet?",
          tr: "Ulm'da yağmur olursa ne yapıyoruz?",
        },
        a: {
          de: "Fürs Fischerviertel lassen sich Portraits unter überhängenden Fachwerkbalken machen, am Münster gibt es das Hauptportal und die Kreuzgangarkaden, und für größere Shootings weichen wir in den überdachten Hof des Schwörhauses aus.",
          tr: "Fischerviertel'de çıkıntılı ahşap balkonların altında portreler alabiliyoruz; katedralde ana kapı ve revakları var; büyük çekimler için Schwörhaus'un iç avlusuna geçiyoruz.",
        },
      },
      {
        q: {
          de: "Fahrt ihr auch für ein Standesamt-Paket nach Ulm?",
          tr: "Ulm'a sadece nikah paketi için de geliyor musunuz?",
        },
        a: {
          de: "Ja. Für Paare, die klein im Schwörhaus heiraten, gibt es ein kompaktes Standesamt-Paket mit zwei bis drei Stunden Begleitung inklusive Portraits am Münster.",
          tr: "Evet. Schwörhaus'ta küçük bir törenle evlenen çiftler için 2–3 saatlik, Münster portrelerini de kapsayan sıkı bir nikah paketimiz var.",
        },
      },
    ],
  },
  {
    slug: "neu-ulm",
    name: "Neu-Ulm",
    kreis: { de: "Landkreis Neu-Ulm (Bayern)", tr: "Neu-Ulm ilçesi (Bayern)" },
    drive: { de: "Rund 45 Minuten vom Atelier", tr: "Atölyeden yaklaşık 45 dakika" },
    lead: {
      de: "Hochzeitsfotograf in Neu-Ulm – auf der bayerischen Seite der Donau, mit Glacis-Park, Edwin-Scharff-Haus und dem Blick zurück auf das Ulmer Münster.",
      tr: "Neu-Ulm'da düğün fotoğrafçısı – Donau'nun Bayern tarafında, Glacis Park, Edwin-Scharff-Haus ve karşıda Ulm Katedrali manzarasıyla.",
    },
    body: {
      de: [
        "Neu-Ulm ist für uns eng mit Ulm verbunden, hat aber ein eigenes Gesicht: moderner, geradliniger, weniger touristisch. Trauungen im Rathaus, Portraits im Glacis-Park und am Donauufer – und immer der Blick auf die andere Seite, zum Münster.",
        "Das Edwin-Scharff-Haus und die Caponniere im Glacis sind zwei Orte, die viele Paare übersehen; wir binden sie bewusst in den Zeitplan ein, weil sie Bilder liefern, die man nicht auf jeder Hochzeitsseite sieht.",
      ],
      tr: [
        "Neu-Ulm bizim için Ulm'a sıkı bağlı ama ayrı bir yüzü var: daha modern, daha düz çizgili, daha az turistik. Belediyede nikahlar, Glacis Park ve Donau kıyısında portreler – ve her zaman karşı yakadaki Münster manzarası.",
        "Edwin-Scharff-Haus ve Glacis'teki Caponniere, birçok çiftin gözünden kaçırdığı iki mekân; herkesin düğün sayfasında görülmeyen kareler verdikleri için programa özellikle yerleştiriyoruz.",
      ],
    },
    spots: [
      {
        name: "Glacis-Stadtpark",
        note: {
          de: "Alter Baumbestand, Wiesen, viel Platz.",
          tr: "Yaşlı ağaçlar, çayırlar, bolca alan.",
        },
      },
      {
        name: "Caponniere",
        note: {
          de: "Historisches Verteidigungsbauwerk, starke Backsteinkulisse.",
          tr: "Tarihi savunma yapısı, güçlü tuğla dekor.",
        },
      },
      {
        name: "Donauufer mit Blick auf Ulm",
        note: {
          de: "Münster-Silhouette im Hintergrund, am besten zur blauen Stunde.",
          tr: "Arka planda katedral silueti; en güzeli mavi saatte.",
        },
      },
      {
        name: "Petrusplatz",
        note: {
          de: "Moderne Architektur um die Petruskirche, klare Linien.",
          tr: "Petruskirche etrafında modern mimari, net çizgiler.",
        },
      },
    ],
    venues: [],
    neighbours: ["ulm", "guenzburg", "memmingen", "augsburg"],
    faq: [
      {
        q: {
          de: "Kombiniert ihr Trauungen in Neu-Ulm mit Portraits in Ulm?",
          tr: "Neu-Ulm'daki nikahla Ulm'daki portreleri birleştiriyor musunuz?",
        },
        a: {
          de: "Sehr gern. Die Herdbrücke verbindet beide Städte in wenigen Gehminuten – wir planen Portraits auf beiden Seiten der Donau ein, ohne dass der Tag hektisch wird.",
          tr: "Memnuniyetle. Herdbrücke iki şehri birkaç dakikalık yürüyüşle bağlıyor – Donau'nun iki yakasında da portreler koyuyoruz, gün yoğunlaşmıyor.",
        },
      },
      {
        q: {
          de: "Was tun, wenn das Wetter im Glacis-Park nicht mitspielt?",
          tr: "Glacis Park'ta hava kötü giderse ne yapıyoruz?",
        },
        a: {
          de: "Wir weichen in die Caponniere, unter die Alleen im Park oder in den Eingangsbereich des Edwin-Scharff-Hauses aus – alle drei funktionieren bei Regen und bringen trotzdem Charakter mit.",
          tr: "Caponniere'ye, parkın ağaç tünellerinin altına ya da Edwin-Scharff-Haus girişine geçiyoruz – üçü de yağmurda çalışıyor ve yine de karakter veriyor.",
        },
      },
      {
        q: {
          de: "Dürfen wir eine freie Trauung im Glacis-Park feiern?",
          tr: "Glacis Park'ta serbest tören yapabilir miyiz?",
        },
        a: {
          de: "Für freie Zeremonien im öffentlichen Raum braucht es eine Sondernutzungserlaubnis der Stadt. Wir weisen im Vorgespräch auf die üblichen Vorlaufzeiten hin, damit nichts an der Genehmigung scheitert.",
          tr: "Kamusal alanda serbest tören için şehirden özel kullanım izni gerekli. Ön görüşmede olağan başvuru sürelerini hatırlatıyoruz; izinden ötürü bir sorun yaşanmasın diye.",
        },
      },
    ],
  },
  {
    slug: "memmingen",
    name: "Memmingen",
    kreis: { de: "Stadtkreis Memmingen (Bayern)", tr: "Memmingen şehri (Bayern)" },
    drive: { de: "Rund 50 Minuten vom Atelier", tr: "Atölyeden yaklaşık 50 dakika" },
    lead: {
      de: "Hochzeitsfotograf in Memmingen – Renaissance-Rathaus, Siebendächerhaus, Marktplatz: Eine der fotogensten Altstädte im Allgäu, und selten überlaufen.",
      tr: "Memmingen'de düğün fotoğrafçısı – Rönesans belediyesi, Siebendächerhaus, Marktplatz: Allgäu'nun en fotojenik eski şehirlerinden biri ve çoğunlukla sakin.",
    },
    body: {
      de: [
        "Memmingen ist für uns einer der schönsten Orte der Region. Der Marktplatz mit dem Rathaus und der Steuerhaus-Fassade ist eine Kulisse, die auch bei schlechtem Wetter noch gute Bilder liefert. Standesamtliche Trauungen finden im Grimmelhaus statt, kirchliche Trauungen meistens in St. Martin oder St. Josef.",
        "Für Portraits kombinieren wir gern Marktplatz, Westertor und den Stadtpark Neue Welt. Alles liegt zu Fuß erreichbar – das spart Fahrzeit und hält den Zeitplan auch an einem langen Tag entspannt.",
      ],
      tr: [
        "Memmingen bize göre bölgenin en güzel yerlerinden biri. Rathaus ve Steuerhaus cephesiyle Marktplatz, kötü havada bile iyi kareler veren bir dekor. Resmi nikahlar Grimmelhaus'ta, kilise törenleri genelde St. Martin veya St. Josef'te.",
        "Portreler için Marktplatz, Westertor ve Stadtpark Neue Welt'i birlikte kullanmayı seviyoruz. Hepsi yürüme mesafesinde – yol zamanı kazandırıyor, uzun günlerde bile programı rahat tutuyor.",
      ],
    },
    spots: [
      {
        name: "Marktplatz & Rathaus",
        note: {
          de: "Renaissance-Fassade, klassische Trauungskulisse.",
          tr: "Rönesans cephesi, klasik nikah dekoru.",
        },
      },
      {
        name: "Siebendächerhaus",
        note: {
          de: "Bekanntestes Motiv der Altstadt, stark zur blauen Stunde.",
          tr: "Eski şehrin en bilinen manzarası, mavi saatte etkileyici.",
        },
      },
      {
        name: "Westertor & Stadtmauer",
        note: {
          de: "Historische Tore, kurze Wege vom Marktplatz.",
          tr: "Tarihi kapılar, Marktplatz'tan kısa yürüyüş.",
        },
      },
      {
        name: "Stadtpark Neue Welt",
        note: {
          de: "Grün, ruhig, ideal für Portraits ohne Publikum.",
          tr: "Yeşil, sakin; izleyicisiz portreler için ideal.",
        },
      },
    ],
    venues: [],
    neighbours: ["krumbach", "guenzburg", "ulm", "augsburg"],
    faq: [
      {
        q: {
          de: "Wann ist es auf dem Marktplatz Memmingen am ruhigsten?",
          tr: "Memmingen Marktplatz'ı ne zaman en sakin?",
        },
        a: {
          de: "Freitagvormittag und Samstag zur Mittagszeit ist am meisten los. Für Portraits planen wir, wenn möglich, den frühen Nachmittag ein oder die Stunde vor Sonnenuntergang.",
          tr: "Cuma öğleden önce ve cumartesi öğle saatleri en kalabalık zamanlar. Portreleri mümkünse öğleden sonranın ilk saatlerine ya da gün batımından önceki saate koyuyoruz.",
        },
      },
      {
        q: {
          de: "Was ist der Regenplan für eine Memminger Hochzeit?",
          tr: "Memmingen düğünü için yağmur planı nedir?",
        },
        a: {
          de: "Wir setzen auf drei überdachte Alternativen: die Arkaden am Rathaus, das Grimmelhaus selbst und das Westertor. Zusammen mit dem Treppenhaus im Rathaus reicht das für die komplette Reportage, ohne dass ein einziger Programmpunkt verschoben werden muss.",
          tr: "Üç kapalı alternatifimiz var: Rathaus revakları, Grimmelhaus içi ve Westertor. Rathaus iç merdiveniyle birlikte, tek bir program noktasını bile ertelemeden tüm çekimi karşılıyor.",
        },
      },
      {
        q: {
          de: "Kennt ihr die Hausregeln in St. Martin für Fotos?",
          tr: "St. Martin'in fotoğraf kurallarını biliyor musunuz?",
        },
        a: {
          de: "Ja. Wir stimmen uns vor jeder Trauung mit dem Mesner ab, arbeiten ohne Blitz und bleiben so platziert, dass die Zeremonie im Vordergrund steht.",
          tr: "Evet. Her törenden önce zangoçla konuşuyoruz, flaşsız çalışıyoruz ve törenin önde kalacağı şekilde konumlanıyoruz.",
        },
      },
    ],
  },
  {
    slug: "augsburg",
    name: "Augsburg",
    kreis: { de: "Stadtkreis Augsburg (Bayern)", tr: "Augsburg şehri (Bayern)" },
    drive: { de: "Rund 65 Minuten vom Atelier", tr: "Atölyeden yaklaşık 65 dakika" },
    lead: {
      de: "Hochzeitsfotograf in Augsburg – vom Goldenen Saal im Rathaus über die Fuggerei bis zur Maximilianstraße: eine Stadt, die große Feiern und kleine, feine Trauungen gleichermaßen trägt.",
      tr: "Augsburg'da düğün fotoğrafçısı – Rathaus'taki Altın Salon'dan Fuggerei'ye ve Maximilianstraße'ye: hem büyük düğünleri hem de küçük ama özenli nikahları taşıyabilen bir şehir.",
    },
    body: {
      de: [
        "Augsburg ist die größte Stadt in unserem Einzugsgebiet – entsprechend viele Hochzeiten finden hier statt. Standesamtliche Trauungen im historischen Rathaus (oft im Oberen Fletz), kirchliche Trauungen im Augsburger Dom, in St. Ulrich und Afra oder in St. Anna.",
        "Für Portraits sind der Rathausplatz, die Maximilianstraße und die Fuggerei eine der stärksten Kombinationen in Süddeutschland. Innerhalb von 15 Gehminuten entstehen Bilder, die sonst drei verschiedene Städte bräuchten.",
      ],
      tr: [
        "Augsburg hizmet bölgemizdeki en büyük şehir – dolayısıyla burada çok sayıda düğün oluyor. Resmi nikahlar tarihi belediyede (çoğunlukla Oberer Fletz'de), kilise törenleri Augsburg Katedrali'nde, St. Ulrich ve Afra'da ya da St. Anna'da.",
        "Portreler için Rathausplatz, Maximilianstraße ve Fuggerei, Güney Almanya'nın en güçlü kombinasyonlarından. 15 dakikalık yürüyüşle normalde üç ayrı şehir gereken kareler çıkıyor.",
      ],
    },
    spots: [
      {
        name: "Rathausplatz & Perlachturm",
        note: {
          de: "Direkt vor dem Standesamt, Klassiker.",
          tr: "Nikah dairesinin önü, klasik.",
        },
      },
      {
        name: "Maximilianstraße",
        note: {
          de: "Prachtstraße, Brunnen, abends ruhiger als am Tag.",
          tr: "Ana cadde, çeşmeler; akşamları gündüzden daha sakin.",
        },
      },
      {
        name: "Fuggerei",
        note: {
          de: "Eigene Atmosphäre – Eintrittsregelung beachten.",
          tr: "Kendine has bir atmosfer – giriş kurallarına dikkat.",
        },
      },
      {
        name: "Hofgarten & Wittelsbacher Park",
        note: {
          de: "Große Bäume, ruhige Wege, im Sommer Schatten.",
          tr: "Büyük ağaçlar, sakin yollar, yazın gölge.",
        },
      },
    ],
    venues: [],
    neighbours: ["krumbach", "guenzburg", "memmingen", "ulm"],
    faq: [
      {
        q: {
          de: "Darf im Goldenen Saal des Rathauses fotografiert werden?",
          tr: "Belediyenin Altın Salon'unda fotoğraf çekilebiliyor mu?",
        },
        a: {
          de: "Ja, wenn ihr dort heiratet. Für zusätzliche Portraits im Saal braucht es eine separate Anfrage – wir übernehmen die Abstimmung gern im Vorfeld.",
          tr: "Evet, orada evleniyorsanız. Ek portreler için ayrı başvuru gerekiyor – ön koordinasyonu memnuniyetle üstleniyoruz.",
        },
      },
      {
        q: {
          de: "Was tun, wenn es in Augsburg regnet?",
          tr: "Augsburg'da yağmur olursa ne yapıyoruz?",
        },
        a: {
          de: "Die Arkaden an der Maximilianstraße, der Innenhof der Stadtmetzg und die Vorhallen am Dom bilden einen funktionierenden Regenplan – alle drei liegen innerhalb von 10 Gehminuten vom Rathaus.",
          tr: "Maximilianstraße revakları, Stadtmetzg iç avlusu ve Katedral ön hollerini içeren çalışan bir yağmur planımız var – üçü de Rathaus'tan 10 dakika yürüyüş mesafesinde.",
        },
      },
      {
        q: {
          de: "Begleitet ihr auch sehr große Hochzeiten in Augsburg?",
          tr: "Augsburg'da çok büyük düğünleri de çekiyor musunuz?",
        },
        a: {
          de: "Ab etwa 300 Gästen empfehlen wir das Team-Paket mit zwei Fotografen – so bleiben Empfang, Saal und Bühne parallel abgedeckt, ohne dass Bilder verloren gehen.",
          tr: "Yaklaşık 300 misafirden sonra iki fotoğrafçılı ekip paketini öneriyoruz – böylece karşılama, salon ve sahne aynı anda kayıt altında kalıyor, hiçbir an kaçmıyor.",
        },
      },
    ],
  },
];

export const cityBySlug = (slug: string) => cities.find((c) => c.slug === slug);
