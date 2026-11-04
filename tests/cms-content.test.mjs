import test from "node:test";
import assert from "node:assert/strict";

import { completeSiteContent } from "../lib/complete-content.ts";

test("fills blank stats and missing price packages from defaults", () => {
  const defaults = {
    stats: { weddings: "180+", years: "9", delivery: "3", rating: "4,9 ★" },
    packages: [{ slug: "klassik", name: { de: "Der ganze Tag" }, price: "1.890 €" }],
    addons: [{ name: { de: "Fotoalbum" }, price: "+ 590 €" }],
  };

  const result = completeSiteContent(
    {
      stats: { weddings: "", years: "", delivery: "", rating: "" },
      packages: [],
      addons: [],
    },
    defaults,
  );

  assert.deepEqual(result.stats, defaults.stats);
  assert.deepEqual(result.packages, defaults.packages);
  assert.deepEqual(result.addons, defaults.addons);
});

test("keeps intentional CMS values while filling only blank fields", () => {
  const result = completeSiteContent(
    {
      stats: { weddings: "240+", years: "", delivery: "2", rating: "4,8 ★" },
      packages: [{ slug: "custom", name: { de: "Individuell" }, price: "999 €" }],
      addons: [{ name: { de: "Album" }, price: "" }],
    },
    {
      stats: { weddings: "180+", years: "9", delivery: "3", rating: "4,9 ★" },
      packages: [{ slug: "klassik", name: { de: "Der ganze Tag" }, price: "1.890 €" }],
      addons: [{ name: { de: "Fotoalbum" }, price: "+ 590 €" }],
    },
  );

  assert.equal(result.stats.weddings, "240+");
  assert.equal(result.stats.years, "9");
  assert.equal(result.stats.delivery, "2");
  assert.equal(result.packages[0].name.de, "Individuell");
  assert.equal(result.addons[0].price, "+ 590 €");
});
