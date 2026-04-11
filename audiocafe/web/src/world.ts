import type { LangEntry } from "./types";
import langs from "./data/languages.json";

const L = langs as LangEntry[];

const REGIONS = ["Asia", "Europe", "Americas", "Middle East", "Africa", "Pacific"] as const;
type Region = (typeof REGIONS)[number];

const REGION_LABELS: Record<Region, string> = {
  Asia: "Asia",
  Europe: "Europe",
  Americas: "Americas",
  "Middle East": "Middle East & Central Asia",
  Africa: "Africa",
  Pacific: "Pacific",
};

const ABC_KEY = "ABC";
const ABC_LABEL = "ABC (A-Z)";

function makeUrl(gc: string): string {
  if (gc === "ja")
    return "https://audiocafe-tokyo.translate.goog/top/?_x_tr_sl=en&_x_tr_tl=ja&_x_tr_hl=ja&_x_tr_pto=wapp";
  return `https://audiocafe-tokyo.translate.goog/top/?_x_tr_sl=ja&_x_tr_tl=${encodeURIComponent(gc)}&_x_tr_hl=ja&_x_tr_pto=wapp`;
}

const searchEl0 = document.getElementById("search");
const pillsEl0 = document.getElementById("pills");
const mainEl0 = document.getElementById("main");
if (!(searchEl0 instanceof HTMLInputElement) || !pillsEl0 || !mainEl0) {
  throw new Error("audiocafe: missing #search, #pills, or #main (search must be <input>)");
}
const searchEl = searchEl0;
const pillsEl = pillsEl0;
const mainEl = mainEl0;

let activeRegion: "all" | typeof ABC_KEY | Region = "all";

function buildPills(): void {
  let h = '<button class="pill active" data-r="all">All</button>';
  h += `<button class="pill" data-r="${ABC_KEY}">${ABC_LABEL}</button>`;
  for (let i = 0; i < REGIONS.length; i++) {
    const r = REGIONS[i];
    h += `<button class="pill" data-r="${r}">${REGION_LABELS[r]}</button>`;
  }
  pillsEl.innerHTML = h;
  const btns = pillsEl.querySelectorAll(".pill");
  for (let j = 0; j < btns.length; j++) {
    const b = btns[j];
    b.addEventListener("click", () => {
      const dr = b.getAttribute("data-r");
      activeRegion = (dr === "all" || dr === ABC_KEY || REGIONS.includes(dr as Region) ? dr : "all") as
        | "all"
        | typeof ABC_KEY
        | Region;
      const all = pillsEl.querySelectorAll(".pill");
      for (let k = 0; k < all.length; k++) all[k].className = "pill";
      b.className = "pill active";
      render();
    });
  }
}

function render(): void {
  const q = (searchEl.value || "").toLowerCase().trim();
  let base: LangEntry[] = L;
  if (activeRegion !== "all" && activeRegion !== ABC_KEY) {
    base = [];
    for (let i = 0; i < L.length; i++) {
      if (L[i].r === activeRegion) base.push(L[i]);
    }
  }
  const items: LangEntry[] = [];
  for (let i = 0; i < base.length; i++) {
    const c = base[i];
    if (
      !q ||
      c.n.toLowerCase().indexOf(q) !== -1 ||
      c.t.toLowerCase().indexOf(q) !== -1 ||
      c.g.toLowerCase().indexOf(q) !== -1 ||
      c.c.toLowerCase().indexOf(q) !== -1 ||
      c.a.toLowerCase().indexOf(q) !== -1
    ) {
      items.push(c);
    }
  }
  if (!items.length) {
    mainEl.innerHTML = '<p class="empty">No languages found.</p>';
    return;
  }

  let html = "";

  if (activeRegion === ABC_KEY) {
    const sorted = items.slice().sort((a, b) => {
      const x = a.n.toLowerCase();
      const y = b.n.toLowerCase();
      return x < y ? -1 : x > y ? 1 : 0;
    });
    const groups: Record<string, LangEntry[]> = {};
    const letters: string[] = [];
    for (let i = 0; i < sorted.length; i++) {
      const letter = sorted[i].n.charAt(0).toUpperCase();
      if (!groups[letter]) {
        groups[letter] = [];
        letters.push(letter);
      }
      groups[letter].push(sorted[i]);
    }
    letters.sort();
    for (let i = 0; i < letters.length; i++) {
      const arr = groups[letters[i]];
      html += `<section class="region-section"><h2 class="region-title">${esc(letters[i])}<span class="region-count">(${arr.length})</span></h2><div class="grid">`;
      for (let j = 0; j < arr.length; j++) html += cardHtml(arr[j]);
      html += "</div></section>";
    }
  } else {
    const order: readonly string[] = activeRegion === "all" ? REGIONS : [activeRegion];
    const grouped: Record<string, LangEntry[]> = {};
    for (let i = 0; i < order.length; i++) grouped[order[i]] = [];
    for (let i = 0; i < items.length; i++) {
      const r = items[i].r;
      if (!grouped[r]) grouped[r] = [];
      grouped[r].push(items[i]);
    }
    for (let i = 0; i < order.length; i++) {
      const key = order[i] as Region;
      const arr = grouped[key];
      if (!arr || !arr.length) continue;
      html += `<section class="region-section"><h2 class="region-title">${REGION_LABELS[key]}<span class="region-count">(${arr.length})</span></h2><div class="grid">`;
      for (let j = 0; j < arr.length; j++) html += cardHtml(arr[j]);
      html += "</div></section>";
    }
  }
  mainEl.innerHTML = html;
}

function cardHtml(c: LangEntry): string {
  return (
    `<a class="card" href="${makeUrl(c.g)}" target="_blank" rel="noopener noreferrer">` +
    `<img class="card-flag" src="https://flagcdn.com/w160/${c.fc}.png" alt="${esc(c.c)}" loading="lazy">` +
    `<span class="card-code">${esc(c.a)}</span>` +
    `<span class="card-native">${esc(c.t)}</span>` +
    `<span class="card-en">${esc(c.n)}</span>` +
    `<span class="card-country">${esc(c.c)}</span>` +
    `</a>`
  );
}

function esc(s: string): string {
  const d = document.createElement("div");
  d.textContent = s;
  return d.innerHTML;
}

searchEl.addEventListener("input", () => {
  render();
});
buildPills();
render();

/* Google Translate URL repair (images + links)
   - Fixes translate.goog proxy URLs and translate.google.com/website?u= wrapper URLs
   - Ensures flag images and external media still load even after translation */
(function translateFixIife() {
  function restoreUrl(url: string): string {
    try {
      const u = new URL(url, window.location.href);
      const host = u.hostname;

      if (host === "translate.google.com" && (u.pathname === "/website" || u.pathname === "/translate")) {
        const wrapped = u.searchParams.get("u");
        if (!wrapped) return url;
        let prev = wrapped;
        for (let di = 0; di < 3; di++) {
          try {
            const dec = decodeURIComponent(prev);
            if (dec === prev) break;
            prev = dec;
          } catch {
            break;
          }
        }
        return prev;
      }

      if (host.indexOf(".translate.goog") !== -1) {
        const originalDomain = host.replace(".translate.goog", "").replace(/-/g, ".");
        const params = u.searchParams;
        params.delete("_x_tr_sl");
        params.delete("_x_tr_tl");
        params.delete("_x_tr_hl");
        params.delete("_x_tr_pto");
        params.delete("_x_tr_hist");
        const search = params.toString();
        return `https://${originalDomain}${u.pathname}${search ? `?${search}` : ""}${u.hash}`;
      }
      return url;
    } catch {
      return url;
    }
  }

  function fixAll(): void {
    const imgs = document.querySelectorAll("img[src]");
    for (let i = 0; i < imgs.length; i++) {
      const img = imgs[i];
      const src = img.getAttribute("src");
      if (!src) continue;
      if (src.indexOf("flagcdn.com") === -1) continue;
      const fixed = restoreUrl(src);
      if (fixed !== src) img.setAttribute("src", fixed);
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", fixAll);
  } else {
    fixAll();
  }
  window.addEventListener("load", () => {
    fixAll();
    setTimeout(fixAll, 1000);
    setTimeout(fixAll, 3000);
  });
  try {
    const mo = new MutationObserver(() => {
      fixAll();
    });
    mo.observe(document.body || document.documentElement, {
      childList: true,
      subtree: true,
      attributes: true,
      attributeFilter: ["href", "src"],
    });
  } catch {
    /* ignore */
  }
})();
