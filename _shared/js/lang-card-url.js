/**
 * Builds Google Translate URLs for language cards on the site root index.
 * Expects window.AcSiteConfig from each domain's /site-config.js:
 *   host               — canonical hostname (e.g. "aon.tokyo")
 *   translateSubdomain — subdomain for *.translate.goog (e.g. "aon-tokyo")
 *   jaCardMode         — "native" | "enToJaProxy" (default "native")
 */
(function () {
  "use strict";

  function cfg() {
    return window.AcSiteConfig || {};
  }

  function makeLangCardUrl(gc) {
    var c = cfg();
    var host = c.host || "";
    var sub = c.translateSubdomain || "";
    var mode = c.jaCardMode || "native";
    if (!host || !sub) {
      return "https://" + (host || "localhost") + "/top/";
    }
    if (gc === "ja") {
      if (mode === "enToJaProxy") {
        return (
          "https://" +
          sub +
          ".translate.goog/top/?_x_tr_sl=en&_x_tr_tl=ja&_x_tr_hl=ja&_x_tr_pto=wapp"
        );
      }
      return "https://" + host + "/top/";
    }
    return (
      "https://" +
      sub +
      ".translate.goog/top/?_x_tr_sl=ja&_x_tr_tl=" +
      encodeURIComponent(gc) +
      "&_x_tr_hl=ja&_x_tr_pto=wapp"
    );
  }

  window.AcSite = window.AcSite || {};
  window.AcSite.makeLangCardUrl = makeLangCardUrl;
})();
