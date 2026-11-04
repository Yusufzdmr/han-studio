import type { L } from "./i18n";

/**
 * SEO-Metadaten und Marketing-Kennungen – alles im Admin pflegbar.
 *
 * Warum hier und nicht in den Seiten: Titel und Beschreibung sind das, was in
 * Google steht. Wer die anpassen will, soll keinen Entwickler brauchen. Die
 * Werte unten sind nur die Startbelegung; ab dem ersten Speichern gilt der
 * Stand aus der Datenbank (siehe lib/cms.ts).
 */

/** Ein Eintrag pro Seite. Leerer Titel/Text = die Seite entscheidet selbst. */
export type SeoEntry = {
  title: L;
  description: L;
  /** Aus dem Google-Index nehmen (und aus der Sitemap). */
  noindex: boolean;
  /** Eigenes Vorschaubild fuer WhatsApp, Facebook, X. Leer = Standardbild. */
  image: string;
};

/**
 * Titelvorlagen fuer die Seiten, die es viele Male gibt. Platzhalter in
 * geschweiften Klammern werden ersetzt; leere Vorlage = eingebauter Titel.
 */
export type SeoTemplates = {
  /** {name} = Stadtname */
  city: L;
  /** {name} = Name der Location */
  venue: L;
  /** {title} = Beitragstitel */
  post: L;
  /** {couple} = Brautpaar, {venue} = Location */
  story: L;
};

/**
 * Kennungen fuer Analytics, Ads und Pixel liegen bewusst nicht hier, sondern
 * unter „Integrationen“ (lib/integrations.ts) – zusammen mit den Zugangsdaten
 * der uebrigen Dienste. Hier geht es nur um das, was in Google zu lesen ist.
 */
export type MarketingContent = {
  pages: Record<string, SeoEntry>;
  templates: SeoTemplates;
  /** Vorschaubild, wenn eine Seite keins hat */
  defaultImage: string;
};

type PageDef = {
  key: string;
  /** Pfad ohne Sprachpraefix */
  path: string;
  label: L;
  /** Startbelegung; leer = die Seite setzt den Wert selbst (z. B. aus dem Beitrag) */
  title?: L;
  description?: L;
  /** Der Text kommt aus einem anderen Admin-Bereich, wenn hier nichts steht */
  auto?: L;
  noindex?: boolean;
};

/**
 * Reihenfolge = Reihenfolge im Admin. Wer eine neue Seite anlegt, traegt sie
 * hier ein – Admin-Formular, Sitemap und Metadaten lesen alle diese Liste.
 */
export const seoPages: PageDef[] = [
  {
    key: "home",
    path: "/",
    label: { de: "Startseite", tr: "Ana sayfa" },
    title: {
      de: "Hochzeitsfotograf Krumbach · Ulm · Memmingen · Augsburg | Han Studios",
      tr: "Krumbach · Ulm · Memmingen · Augsburg Düğün Fotoğrafçısı | Han Studios",
    },
    description: {
      de: "Dokumentarische Hochzeitsfotografie und Hochzeitsfilm in Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg und Günzburg. Private Kundengalerie und digitale Hochzeitseinladung inklusive.",
      tr: "Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg ve Günzburg'da belgesel tarzda düğün fotoğrafçılığı ve düğün filmi. Özel müşteri galerisi ve dijital düğün davetiyesi dahil.",
    },
  },
  {
    key: "leistungen",
    path: "/leistungen",
    label: { de: "Leistungen", tr: "Hizmetler" },
    title: {
      de: "Leistungen – Hochzeitsfotografie & Hochzeitsfilm im Allgäu und in Schwaben",
      tr: "Hizmetler – Allgäu ve Schwaben'de düğün fotoğrafçılığı ve düğün filmi",
    },
    description: {
      de: "Hochzeitsreportage, Hochzeitsfilm in 4K, Standesamt, Henna-Abend und After-Wedding-Shooting in Krumbach, Ulm, Memmingen und Augsburg.",
      tr: "Krumbach, Ulm, Memmingen ve Augsburg'da düğün çekimi, 4K düğün filmi, nikah, kına gecesi ve after-wedding çekimi.",
    },
  },
  {
    key: "preise",
    path: "/preise",
    label: { de: "Preise", tr: "Fiyatlar" },
    title: {
      de: "Preise Hochzeitsfotograf – Pakete für Krumbach, Ulm & Augsburg",
      tr: "Düğün fotoğrafçısı fiyatları – Krumbach, Ulm ve Augsburg paketleri",
    },
    description: {
      de: "Transparente Pakete für Standesamt, Ganztagesreportage und kombinierte Foto- und Filmbegleitung. Alles inklusive, keine versteckten Kosten.",
      tr: "Nikah, tam gün çekim ve foto+film birlikte paketleri; şeffaf fiyatlar, gizli maliyet yok.",
    },
  },
  {
    key: "portfolio",
    path: "/portfolio",
    label: { de: "Portfolio", tr: "Portfolyo" },
    title: {
      de: "Portfolio – echte Hochzeiten aus Krumbach, Ulm und Augsburg",
      tr: "Portfolyo – Krumbach, Ulm ve Augsburg'dan gerçek düğünler",
    },
    description: {
      de: "Hochzeitsreportagen aus Krumbach, Ulm, Memmingen und Augsburg – dokumentarisch, ruhig und ehrlich.",
      tr: "Krumbach, Ulm, Memmingen ve Augsburg'dan düğün çekimleri – belgesel, sakin ve dürüst.",
    },
  },
  {
    key: "hochzeitslocations",
    path: "/hochzeitslocations",
    label: { de: "Locations (Übersicht)", tr: "Mekânlar (liste)" },
    title: {
      de: "Hochzeitslocations in Schwaben und im Allgäu – Fotograf mit Ortskenntnis",
      tr: "Schwaben ve Allgäu'da düğün mekânları – mekânı tanıyan fotoğrafçı",
    },
    description: {
      de: "Schloss, Festsaal, Eventlocation: Für die beliebtesten Hochzeitslocations in Krumbach, Ulm, Memmingen, Augsburg und Günzburg kennen wir Licht, Zeitplan und Hausregeln.",
      tr: "Saray, düğün salonu, etkinlik mekânı: Krumbach, Ulm, Memmingen, Augsburg ve Günzburg'daki en çok tercih edilen mekânların ışığını, zaman planını ve kurallarını biliyoruz.",
    },
  },
  {
    key: "regionen",
    path: "/regionen",
    label: { de: "Regionen", tr: "Bölgeler" },
    title: {
      de: "Hochzeitsfotograf zwischen Ulm, Memmingen und Augsburg – alle Regionen",
      tr: "Ulm, Memmingen ve Augsburg arası düğün fotoğrafçısı – tüm bölgeler",
    },
    description: {
      de: "Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg und Günzburg: Hochzeitsfotograf und Videograf mit Ortskenntnis in Schwaben und im Allgäu.",
      tr: "Krumbach, Ulm, Neu-Ulm, Memmingen, Augsburg ve Günzburg: Schwaben ve Allgäu'da bölgeyi tanıyan düğün fotoğrafçısı ve videografı.",
    },
  },
  {
    key: "ratgeber",
    path: "/ratgeber",
    label: { de: "Ratgeber (Übersicht)", tr: "Rehber (liste)" },
    title: {
      de: "Ratgeber – Hochzeitsfotografie, Zeitplan & Locations",
      tr: "Rehber – düğün fotoğrafçılığı, akış planı ve mekânlar",
    },
    auto: { de: "Text aus dem Ratgeber-Vorspann", tr: "Metin rehber girişinden" },
  },
  {
    key: "ueber-mich",
    path: "/ueber-mich",
    label: { de: "Über mich", tr: "Hakkımda" },
    auto: {
      de: 'Titel und Text aus "Über mich & Stimmen"',
      tr: '"Hakkımda & yorumlar" bölümünden',
    },
  },
  {
    key: "kontakt",
    path: "/kontakt",
    label: { de: "Kontakt", tr: "İletişim" },
    title: {
      de: "Kontakt – Hochzeitsfotograf in Krumbach anfragen",
      tr: "İletişim – Krumbach'ta düğün fotoğrafçısı",
    },
    description: {
      de: "Fragt euer Hochzeitsdatum an. Antwort in der Regel innerhalb von 24 Stunden. Atelier in Krumbach, Einzugsgebiet Ulm, Memmingen und Augsburg.",
      tr: "Düğün tarihinizi sorun. Genelde 24 saat içinde yanıt. Atölye Krumbach'ta; Ulm, Memmingen ve Augsburg hizmet alanı.",
    },
  },
  {
    key: "einladung",
    path: "/einladung",
    label: { de: "Digitale Einladung", tr: "Dijital davetiye" },
    title: {
      de: "Digitale Hochzeitseinladung erstellen – mit RSVP & Countdown",
      tr: "Dijital düğün davetiyesi oluştur – RSVP ve geri sayımlı",
    },
    description: {
      de: "Eigene Einladungsseite in drei Minuten: Countdown, Google-Maps-Route, WhatsApp-Versand und Zusagen. Für Hochzeitspaare von Han Studios kostenlos.",
      tr: "Üç dakikada kendi davetiye sayfanız: geri sayım, Google Maps yol tarifi, WhatsApp paylaşımı ve katılım bildirimi. Han Studios çiftlerine ücretsiz.",
    },
  },
  {
    key: "galerie",
    path: "/galerie",
    label: { de: "Kundengalerie (Login)", tr: "Müşteri galerisi (giriş)" },
    title: { de: "Kundengalerie – Login", tr: "Müşteri galerisi – giriş" },
    auto: { de: "Text aus dem Galerie-Vorspann", tr: "Metin galeri girişinden" },
    noindex: true,
  },
  {
    key: "impressum",
    path: "/impressum",
    label: { de: "Impressum", tr: "Impressum" },
    auto: { de: 'Titel aus "Rechtstexte"', tr: '"Yasal metinler" bölümünden' },
  },
  {
    key: "datenschutz",
    path: "/datenschutz",
    label: { de: "Datenschutz", tr: "Gizlilik" },
    auto: { de: 'Titel aus "Rechtstexte"', tr: '"Yasal metinler" bölümünden' },
  },
  {
    key: "agb",
    path: "/agb",
    label: { de: "AGB", tr: "AGB" },
    auto: { de: 'Titel aus "Rechtstexte"', tr: '"Yasal metinler" bölümünden' },
  },
];

export const seoPageByKey = (key: string) => seoPages.find((p) => p.key === key);

const empty = (): L => ({ de: "", tr: "" });

export const defaultMarketing = (): MarketingContent => ({
  pages: Object.fromEntries(
    seoPages.map((p) => [
      p.key,
      {
        title: p.title ? { ...p.title } : empty(),
        description: p.description ? { ...p.description } : empty(),
        noindex: Boolean(p.noindex),
        image: "",
      },
    ])
  ),
  templates: {
    city: {
      de: "Hochzeitsfotograf {name} – Foto & Video | Han Studios",
      tr: "{name} Düğün Fotoğrafçısı – Foto & Video | Han Studios",
    },
    venue: {
      de: "{name} Hochzeitsfotograf – Erfahrung vor Ort",
      tr: "{name} düğün fotoğrafçısı – mekân tecrübesi",
    },
    post: { de: "{title}", tr: "{title}" },
    story: { de: "{couple} – {venue}", tr: "{couple} – {venue}" },
  },
  defaultImage: "",
});

/** {name} → Wert. Unbekannte Platzhalter bleiben stehen, damit der Fehler sichtbar ist. */
export function renderTemplate(template: string, vars: Record<string, string>) {
  return template.replace(/\{(\w+)\}/g, (all, key: string) => vars[key] ?? all);
}

/** Google zeigt etwa 60 Zeichen Titel und 160 Zeichen Beschreibung. */
export const seoLimits = {
  title: { min: 30, max: 60 },
  description: { min: 70, max: 160 },
} as const;
