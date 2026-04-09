/**
 * Generate a Google Translate URL for translating aon.tokyo/top/ into the
 * specified target language.
 *
 * Google Translate's website translation rewrites the domain:
 *   example.com → example-com.translate.goog
 * Dots in the original domain become hyphens, then `.translate.goog` is appended.
 *
 * Parameters:
 *   _x_tr_sl  – source language (ja for Japanese)
 *   _x_tr_tl  – target language (the googleCode from our data)
 *   _x_tr_hl  – host/interface language (ja — keep Google UI in Japanese)
 *   _x_tr_pto – page translation options (wapp for web-app mode)
 */
export function buildTranslateUrl(googleLangCode: string): string {
  const sourceLang = "ja";
  const interfaceLang = "ja";
  const translatedDomain = "aon-tokyo.translate.goog";
  const path = "/top/";

  const params = new URLSearchParams({
    _x_tr_sl: sourceLang,
    _x_tr_tl: googleLangCode,
    _x_tr_hl: interfaceLang,
    _x_tr_pto: "wapp",
  });

  return `https://${translatedDomain}${path}?${params.toString()}`;
}
