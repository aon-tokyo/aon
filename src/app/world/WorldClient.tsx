"use client";

import { useState, useMemo } from "react";
import type { CountryCard } from "./page";
import type { Region } from "@/data/languages";

interface Props {
  grouped: Record<Region, CountryCard[]>;
  allCards: CountryCard[];
  regions: Region[];
}

const regionLabels: Record<Region, string> = {
  Asia: "🌏 Asia",
  Europe: "🌍 Europe",
  Americas: "🌎 Americas",
  "Middle East": "🕌 Middle East & Central Asia",
  Africa: "🌍 Africa",
  Pacific: "🌊 Pacific",
};

export function WorldClient({ grouped, allCards, regions }: Props) {
  const [search, setSearch] = useState("");
  const [activeRegion, setActiveRegion] = useState<Region | "all">("all");

  const filtered = useMemo(() => {
    const q = search.toLowerCase().trim();
    const base =
      activeRegion === "all" ? allCards : grouped[activeRegion] ?? [];
    if (!q) return base;
    return base.filter(
      (c) =>
        c.name.toLowerCase().includes(q) ||
        c.nativeName.toLowerCase().includes(q) ||
        c.code.toLowerCase().includes(q),
    );
  }, [search, activeRegion, allCards, grouped]);

  const groupedFiltered = useMemo(() => {
    if (activeRegion !== "all") {
      return { [activeRegion]: filtered } as Partial<Record<Region, CountryCard[]>>;
    }
    const g: Partial<Record<Region, CountryCard[]>> = {};
    for (const region of regions) {
      const items = filtered.filter((c) => c.region === region);
      if (items.length > 0) g[region] = items;
    }
    return g;
  }, [filtered, activeRegion, regions]);

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white">
      {/* Header */}
      <header className="relative overflow-hidden">
        <div className="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(120,119,198,0.3),transparent)]" />
        <div className="relative mx-auto max-w-7xl px-4 py-16 text-center sm:px-6 lg:px-8">
          <a href="https://aon.tokyo/top/" className="inline-block mb-6">
            <span className="text-4xl font-black tracking-tight bg-gradient-to-r from-cyan-400 via-blue-400 to-violet-400 bg-clip-text text-transparent sm:text-5xl lg:text-6xl">
              AON
            </span>
          </a>
          <h1 className="mt-2 text-xl font-medium text-slate-300 sm:text-2xl">
            Please select your native language.
          </h1>
          <p className="mt-2 text-sm text-slate-400 max-w-2xl mx-auto">
            あなたの母国語を選択してください。2回目の選択時はブラウザを閉じて再度開いてください。
            <br />
            動画を視聴するには日本語を選択してください。
          </p>

          {/* Search */}
          <div className="relative mx-auto mt-8 max-w-md">
            <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
              <svg
                className="h-5 w-5 text-slate-400"
                fill="none"
                viewBox="0 0 24 24"
                strokeWidth={2}
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                />
              </svg>
            </div>
            <input
              type="text"
              placeholder="Search languages…"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full rounded-full border border-white/10 bg-white/5 py-3 pl-12 pr-4 text-sm text-white placeholder-slate-400 backdrop-blur-md transition-all focus:border-cyan-400/50 focus:outline-none focus:ring-2 focus:ring-cyan-400/20"
            />
          </div>

          {/* Region filters */}
          <div className="mt-6 flex flex-wrap items-center justify-center gap-2">
            <RegionPill
              label="🌐 All"
              active={activeRegion === "all"}
              onClick={() => setActiveRegion("all")}
            />
            {regions.map((r) => (
              <RegionPill
                key={r}
                label={regionLabels[r]}
                active={activeRegion === r}
                onClick={() => setActiveRegion(r)}
              />
            ))}
          </div>
        </div>
      </header>

      {/* Content */}
      <main className="mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
        {Object.keys(groupedFiltered).length === 0 && (
          <p className="py-20 text-center text-lg text-slate-400">
            No languages found. Try a different search term.
          </p>
        )}

        {(Object.entries(groupedFiltered) as [Region, CountryCard[]][]).map(
          ([region, cards]) => (
            <section key={region} className="mb-12">
              <h2 className="mb-6 text-lg font-semibold tracking-wide text-slate-300">
                {regionLabels[region]}
                <span className="ml-2 text-sm font-normal text-slate-500">
                  ({cards.length})
                </span>
              </h2>
              <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                {cards.map((card) => (
                  <LanguageCard key={card.code} card={card} />
                ))}
              </div>
            </section>
          ),
        )}
      </main>

      {/* Footer */}
      <footer className="border-t border-white/5 py-8">
        <div className="mx-auto max-w-7xl px-4 text-center text-xs text-slate-500 sm:px-6 lg:px-8">
          <p>
            Copyright &copy; {new Date().getFullYear()}{" "}
            <a
              href="https://aon.tokyo/top/"
              className="text-slate-400 hover:text-white transition-colors"
            >
              AON
            </a>
            , Akiru Akiruno-City Tokyo Japan. All Rights Reserved.
          </p>
          <p className="mt-2">
            Powered by{" "}
            <span className="text-slate-400">Google Translate</span> &bull;
            Next.js &bull; TypeScript &bull; Tailwind CSS
          </p>
        </div>
      </footer>
    </div>
  );
}

function RegionPill({
  label,
  active,
  onClick,
}: {
  label: string;
  active: boolean;
  onClick: () => void;
}) {
  return (
    <button
      onClick={onClick}
      className={`rounded-full px-4 py-1.5 text-xs font-medium transition-all ${
        active
          ? "bg-cyan-500/20 text-cyan-300 ring-1 ring-cyan-400/40"
          : "bg-white/5 text-slate-400 hover:bg-white/10 hover:text-slate-200"
      }`}
    >
      {label}
    </button>
  );
}

function LanguageCard({ card }: { card: CountryCard }) {
  return (
    <a
      href={card.url}
      target="_blank"
      rel="noopener noreferrer"
      className="group relative flex flex-col items-center gap-2 rounded-xl border border-white/[0.06] bg-white/[0.03] p-4 text-center backdrop-blur-sm transition-all duration-200 hover:scale-[1.03] hover:border-cyan-400/30 hover:bg-white/[0.07] hover:shadow-lg hover:shadow-cyan-500/5"
    >
      <span className="text-3xl leading-none transition-transform duration-200 group-hover:scale-110">
        {card.flag}
      </span>
      <span className="text-sm font-semibold text-white/90 group-hover:text-white">
        {card.nativeName}
      </span>
      <span className="text-[11px] text-slate-500 group-hover:text-slate-400">
        {card.name}
      </span>
    </a>
  );
}
