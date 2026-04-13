/**
 * Early body script for /top/: sets ac-tg-blog-mode / ac-ja-native-blog-mode.
 * Reads window.AcTopConfig.nativeJaHosts from each domain's /top/site-config.js
 */
(function () {
  function fromHash() {
    try {
      var h = location.hash || "";
      return h.indexOf("tg=1") !== -1;
    } catch (e) {
      return false;
    }
  }
  function fromStorage() {
    try {
      return sessionStorage.getItem("tgBlogView") === "1";
    } catch (e) {
      return false;
    }
  }
  function activate() {
    document.body.classList.add("ac-tg-blog-mode");
  }
  var h = location.hostname || "";
  var list = (window.AcTopConfig && window.AcTopConfig.nativeJaHosts) || [];
  var i,
    match = false;
  for (i = 0; i < list.length; i++) {
    if (h === list[i]) {
      match = true;
      break;
    }
  }
  if (h.indexOf(".translate.goog") !== -1) {
    activate();
    try {
      sessionStorage.setItem("tgBlogView", "1");
    } catch (e) {}
    return;
  }
  if (match) {
    try {
      sessionStorage.removeItem("tgBlogView");
    } catch (e) {}
    document.body.classList.add("ac-ja-native-blog-mode");
    return;
  }
  if (fromHash()) activate();
  else if (fromStorage()) activate();
})();
