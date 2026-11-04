export const site = {
  name: "Han Studios",
  legalName: "Han Studios – Ayhan Akyel",
  tagline: "Hochzeitsfotografie & Film",
  claim: "Hochzeitsfotograf & Videograf in Krumbach, Ulm, Memmingen und Augsburg",
  url:
    process.env.NEXT_PUBLIC_SITE_URL ||
    (process.env.VERCEL_PROJECT_PRODUCTION_URL
      ? `https://${process.env.VERCEL_PROJECT_PRODUCTION_URL}`
      : "http://localhost:3000"),
  locale: "de_DE",
  founded: "",
  owner: "Ayhan Akyel",
  phone: "+491743283263",
  phoneHuman: "0174 3283263",
  whatsapp: "491743283263",
  email: "info@hanstudio.de",
  street: "Dr.-Sprandelstraße 1",
  zip: "86381",
  city: "Krumbach",
  region: "Bayern",
  country: "DE",
  geo: { lat: 48.2411, lng: 10.3668 },
  priceRange: "€€€",
  openingHours: "Nach Vereinbarung",
  instagram: "",
  googleProfile: "",
  vimeo: "",
} as const;

export const nav = [
  { href: "/leistungen", label: "Leistungen" },
  { href: "/portfolio", label: "Portfolio" },
  { href: "/hochzeitslocations", label: "Locations" },
  { href: "/preise", label: "Preise" },
  { href: "/ratgeber", label: "Ratgeber" },
  { href: "/ueber-mich", label: "Über mich" },
  { href: "/kontakt", label: "Kontakt" },
] as const;

export const toolsNav = [
  { href: "/galerie", label: "Kundengalerie" },
  { href: "/einladung", label: "Digitale Einladung" },
] as const;

export const legalNav = [
  { href: "/impressum", label: "Impressum" },
  { href: "/datenschutz", label: "Datenschutz" },
  { href: "/agb", label: "AGB" },
] as const;
