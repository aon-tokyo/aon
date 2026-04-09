import type { Metadata } from "next";
import { languages, regions, type Region } from "@/data/languages";
import { buildTranslateUrl } from "@/lib/translate-url";
import { WorldClient } from "./WorldClient";

export const metadata: Metadata = {
  title: "AON | World — Select Your Language",
  description:
    "Translate aon.tokyo into any of the world's languages via Google Translate.",
};

export interface CountryCard {
  code: string;
  name: string;
  nativeName: string;
  flag: string;
  region: Region;
  url: string;
}

function buildCards(): CountryCard[] {
  return languages.map((lang) => ({
    code: lang.code,
    name: lang.name,
    nativeName: lang.nativeName,
    flag: lang.flag,
    region: lang.region as Region,
    url: buildTranslateUrl(lang.googleCode),
  }));
}

export default function WorldPage() {
  const cards = buildCards();

  const grouped = regions.reduce(
    (acc, region) => {
      acc[region] = cards.filter((c) => c.region === region);
      return acc;
    },
    {} as Record<Region, CountryCard[]>,
  );

  return <WorldClient grouped={grouped} allCards={cards} regions={[...regions]} />;
}
