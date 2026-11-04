import type { L } from "./i18n";

export type Venue = {
  slug: string;
  name: string;
  type: L;
  city: string;
  citySlug: string;
  address: string;
  capacity: L;
  lead: L;
  body: L<string[]>;
  light: L;
  timing: { time: string; what: L }[];
  spots: L<string[]>;
  rules: L<string[]>;
  faq: { q: L; a: L }[];
};

export const venues: Venue[] = [];

export const venueBySlug = (slug: string) => venues.find((v) => v.slug === slug);
export const venuesByCity = (citySlug: string) => venues.filter((v) => v.citySlug === citySlug);
