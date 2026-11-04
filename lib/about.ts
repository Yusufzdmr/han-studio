import type { L } from "./i18n";

/* ------------------------------------------------------------------ */
/*  "Über mich" – Standardinhalte                                      */
/*  Im Admin unter "Über mich & Stimmen" bearbeitbar.                  */
/* ------------------------------------------------------------------ */

export type AboutValue = { t: L; d: L };

export type AboutContent = {
  /** Name in der Überschrift – in beiden Sprachen identisch */
  name: string;
  lead: L;
  body: L<string[]>;
  valuesTitle: L;
  values: AboutValue[];
  gearTitle: L;
  gear: L<string[]>;
};

export const about: AboutContent = {
  name: "Ayhan Akyel",
  lead: {
    de: "Hochzeitsfotograf und Videograf aus Krumbach – mit Blick für die ruhigen Momente zwischen den Programmpunkten.",
    tr: "Krumbach'tan düğün fotoğrafçısı ve videografı – program noktaları arasındaki sakin anlara bakışla.",
  },
  body: {
    de: [
      "Eine Hochzeit ist kein Fotoshooting mit Feier drumherum. Sie ist ein Tag, an dem hunderte kleine Dinge passieren, die niemand geplant hat: der Bruder, der beim Einzug heimlich Tränen wegwischt. Die Großmutter, die zum ersten Mal seit Jahren tanzt. Die Braut, die zwei Minuten allein im Nebenzimmer steht.",
      "Deshalb arbeite ich leise. Ich stelle keine Szenen nach, ich unterbreche keine Reden für ein besseres Foto und ich rufe niemanden zurück, weil das Licht gerade schöner ist. Was ich mache: den Tag vorher so planen, dass die schönen Momente überhaupt Platz haben.",
      "Deutsch und Türkisch spreche ich fließend – in Familien, in denen beide Sprachen zuhause sind, nimmt das viel Druck aus dem Hochzeitstag.",
    ],
    tr: [
      "Düğün, etrafında kutlama olan bir fotoğraf çekimi değildir. Kimsenin planlamadığı yüzlerce küçük şeyin yaşandığı bir gündür: girişte gizlice gözyaşını silen abi. Yıllar sonra ilk kez dans eden anneanne. Yan odada iki dakika tek başına duran gelin.",
      "Bu yüzden sessiz çalışıyorum. Sahne yeniden kurdurmuyorum, daha iyi bir kare için konuşmaları bölmüyorum ve ışık güzelleşti diye kimseyi geri çağırmıyorum. Yaptığım şey: günü önceden öyle planlamak ki o güzel anlara yer kalsın.",
      "Almanca ve Türkçeyi akıcı konuşuyorum – iki dilin birden evde olduğu ailelerde, bu durum düğün gününün üzerinden ciddi bir baskı alıyor.",
    ],
  },
  valuesTitle: { de: "Wie ich arbeite", tr: "Nasıl çalışıyorum" },
  values: [
    {
      t: { de: "Leise statt laut", tr: "Sessiz, gürültüsüz" },
      d: {
        de: "Keine Kommandos, keine Unterbrechungen. Ihr merkt uns kaum – das sieht man später auf den Bildern.",
        tr: "Komut yok, kesinti yok. Bizi neredeyse fark etmiyorsunuz – bu sonradan karelerde görülüyor.",
      },
    },
    {
      t: { de: "Ehrlich statt geschönt", tr: "Dürüst, süslemesiz" },
      d: {
        de: "Ich sage vorher, was zeitlich geht und was nicht. Lieber ein realistischer Plan als ein enttäuschter Abend.",
        tr: "Zaman olarak neyin mümkün olduğunu önceden söylüyorum. Hayal kırıklığı yerine gerçekçi bir plan.",
      },
    },
    {
      t: { de: "Zweisprachig", tr: "İki dilli" },
      d: {
        de: "Deutsch und Türkisch – bei Familien, in denen beide Sprachen gesprochen werden, macht das den Tag deutlich entspannter.",
        tr: "Almanca ve Türkçe – iki dilin konuşulduğu ailelerde bu, günü ciddi şekilde rahatlatıyor.",
      },
    },
    {
      t: { de: "Ortskundig in Schwaben und im Allgäu", tr: "Schwaben ve Allgäu'da yerel" },
      d: {
        de: "Krumbach, Ulm, Memmingen, Augsburg – die Standesämter, Trauorte und Fotospots kennen wir aus eigener Arbeit.",
        tr: "Krumbach, Ulm, Memmingen, Augsburg – nikah daireleri, tören yerleri ve çekim noktaları kendi çalışmalarımızdan biliyoruz.",
      },
    },
  ],
  gearTitle: { de: "Technik", tr: "Ekipman" },
  gear: {
    de: [],
    tr: [],
  },
};
