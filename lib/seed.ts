import type { Gallery, Invitation, Rsvp } from "./store";

/**
 * Demo-Datenbestand.
 *
 * Wird nur beim allerersten Start in eine leere Datenbank geschrieben
 * (siehe `lib/store.ts` -> `seedIfEmpty`). Danach gilt ausschliesslich, was
 * im Admin gepflegt wurde – ein Neustart setzt nichts mehr zurueck.
 */

// Keine Demo-Galerien beim Erststart – echte Kundengalerien werden im Admin
// unter „Kundengalerien" angelegt.
export const seedGalleries = (): Gallery[] => [];

// Keine Demo-Einladungen beim Erststart – echte Einladungen entstehen über
// den Einladungsassistenten.
export const seedInvitations = (): Invitation[] => [];

export const seedRsvps = (): Rsvp[] => [];
