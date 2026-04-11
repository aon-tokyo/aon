var SEARCH_LIST = '\u6b4c\u5fc3\u308a\u3048\u0020\u97d3\u56fd\u0054\u0056\u0020\u65e5\u97d3\u6b4c\u738b\u6226\u0020\u6700\u521d\u304b\u3089\u4eca\u307e\u3067\u0020\u9053\u5316\u5e2b\u306e\u30bd\u30cd\u30c3\u30c8\u0020\u5ddd\u306e\u6d41\u308c\u306e\u3088\u3046\u306b\u0020\u30c7\u30e5\u30a8\u30c3\u30c8\u0020\u7460\u7483\u8272\u306e\u5730\u7403';
var FALLBACK_SEARCH = '\u6b4c\u5fc3\u308a\u3048';
var DEFAULT_BG_VIDEO_ID = 'O2IMOiSCLi8';
var SAFE_BG_VIDEO_IDS = ['O2IMOiSCLi8', 'NDWleweBx_I', 'qsYPw-VNekQ'];
var safeBgRotate = 0;
var ytBgPlayer = null;
var adCheckTimer = null;
var ytBgFallbackUsed = false;
var currentSearchQuery = SEARCH_LIST;
var ytControlsBound = false;
var ytSearchFetchToken = 0;
var YT_SAMPLE_VIDEO_IDS = { "M7lc1UVf-VE": 1, "jNQXAC9IVRw": 1 };
var ytBgLoop = true;
var ytCtxMenuBound = false;
var ytBgLastToggleMs = 0;
var ytCardNavBound = false;
var ytHandoffStartPaused = false;

function getCurrentYtBgVideoId() {
  try {
    if (ytBgPlayer && ytBgPlayer.getVideoData) {
      var vd = ytBgPlayer.getVideoData();
      if (vd && vd.video_id && /^[a-zA-Z0-9_-]{11}$/.test(vd.video_id)) return vd.video_id;
    }
  } catch (e) {}
  return null;
}

function buildYtHandoffHashString(vid, vol, loop, q, paused) {
  if (!vid || !/^[a-zA-Z0-9_-]{11}$/.test(vid)) return "";
  var v = vol | 0;
  if (v < 0) v = 0;
  if (v > 100) v = 100;
  var qs = q != null ? String(q) : "";
  return "ytbg=1&v=" + encodeURIComponent(vid) + "&vol=" + v + "&lp=" + (loop ? "1" : "0") + "&ps=" + (paused ? "1" : "0") + "&q=" + encodeURIComponent(qs);
}

function parseYtHandoffFromLocation() {
  var raw = window.location.hash;
  if (!raw || raw.length < 8) return null;
  var seg = raw.charAt(0) === "#" ? raw.slice(1) : raw;
  if (seg.indexOf("ytbg=1") === -1) return null;
  var params = {};
  seg.split("&").forEach(function(part) {
    var i = part.indexOf("=");
    if (i === -1) return;
    var k = decodeURIComponent(part.slice(0, i));
    var v = decodeURIComponent(part.slice(i + 1).replace(/\+/g, " "));
    params[k] = v;
  });
  if (params.ytbg !== "1" || !params.v) return null;
  if (!/^[a-zA-Z0-9_-]{11}$/.test(params.v)) return null;
  return params;
}

function consumeYtHandoffHash() {
  var params = parseYtHandoffFromLocation();
  if (!params) return null;
  try {
    if (window.history && window.history.replaceState) {
      var clean = window.location.pathname + window.location.search;
      window.history.replaceState(null, "", clean);
    }
  } catch (e) {}
  return params;
}

function applyYtHandoffParams(ho) {
  if (!ho || !ho.v || !/^[a-zA-Z0-9_-]{11}$/.test(ho.v)) return false;
  if (ho.q != null && ho.q !== "") {
    currentSearchQuery = ho.q;
    var si = document.getElementById("ytBgSearch");
    if (si) si.value = ho.q;
  }
  ytBgLoop = ho.lp !== "0";
  ytHandoffStartPaused = ho.ps === "1";
  var vel = document.getElementById("ytBgVolume");
  if (vel && ho.vol != null && ho.vol !== "") {
    var vv = parseInt(ho.vol, 10);
    if (!isNaN(vv) && vv >= 0 && vv <= 100) vel.value = String(vv);
  }
  updateYtTransportUi();
  return true;
}

function bindYtCardHandoffOnce() {
  if (ytCardNavBound) return;
  ytCardNavBound = true;
  document.addEventListener("click", function(ev) {
    var t = ev.target;
    if (!t || !t.closest) return;
    var a = t.closest("a.card");
    if (!a || !a.getAttribute("href")) return;
    var vid = getCurrentYtBgVideoId();
    if (!vid) return;
    var vel = document.getElementById("ytBgVolume");
    var vol = vel ? (parseInt(vel.value, 10) || 0) : 0;
    syncQueryFromInput();
    var paused = false;
    try {
      if (ytBgPlayer && ytBgPlayer.getPlayerState && window.YT) {
        var st = ytBgPlayer.getPlayerState();
        paused = st === YT.PlayerState.PAUSED || st === YT.PlayerState.CUED;
      }
    } catch (e) {}
    var frag = buildYtHandoffHashString(vid, vol, ytBgLoop, currentSearchQuery, paused);
    if (!frag) return;
    try {
      var u = new URL(a.getAttribute("href"), window.location.href);
      u.hash = frag;
      a.setAttribute("href", u.toString());
    } catch (e) {
      try {
        a.href = a.href.split("#")[0] + "#" + frag;
      } catch (e2) {}
    }
  }, true);
}

function syncQueryFromInput() {
  var inp = document.getElementById("ytBgSearch");
  var q = inp ? String(inp.value || "").trim() : "";
  if (!q) q = SEARCH_LIST;
  currentSearchQuery = q;
}

function syncVolumeFromSlider() {
  var el = document.getElementById("ytBgVolume");
  var p = ytBgPlayer;
  if (!el || !p) return;
  var v = parseInt(el.value, 10) || 0;
  try {
    if (v > 0) {
      p.unMute();
      p.setVolume(v);
    } else {
      p.setVolume(0);
      p.mute();
    }
  } catch (e) {}
}

function updateYtTransportUi() {
  var pauseBtn = document.getElementById("ytBgPauseToggle");
  var loopOn = document.getElementById("ytBgLoopOn");
  var loopOff = document.getElementById("ytBgLoopOff");
  var p = ytBgPlayer;
  var YT = window.YT;
  if (pauseBtn && YT && p && p.getPlayerState) {
    try {
      var st = p.getPlayerState();
      var playing = st === YT.PlayerState.PLAYING || st === YT.PlayerState.BUFFERING;
      pauseBtn.textContent = playing ? "\u4e00\u6642\u505c\u6b62" : "\u518d\u751f";
      pauseBtn.setAttribute("aria-pressed", playing ? "true" : "false");
    } catch (e) {}
  }
  if (loopOn && loopOff) {
    loopOn.setAttribute("aria-pressed", ytBgLoop ? "true" : "false");
    loopOff.setAttribute("aria-pressed", !ytBgLoop ? "true" : "false");
  }
}

function destroyYtBgPlayer() {
  ytSearchFetchToken++;
  try {
    if (ytBgPlayer && ytBgPlayer.destroy) ytBgPlayer.destroy();
  } catch (e) {}
  ytBgPlayer = null;
}

function extractFirstWatchIdFromHtml(html) {
  if (!html || typeof html !== "string") return null;
  var re = /\/watch\?v=([a-zA-Z0-9_-]{11})/g;
  var m;
  while ((m = re.exec(html)) !== null) {
    var id = m[1];
    if (!YT_SAMPLE_VIDEO_IDS[id]) return id;
  }
  re = /youtube\.com\/watch\?[^"'\\s<>]*v=([a-zA-Z0-9_-]{11})/g;
  while ((m = re.exec(html)) !== null) {
    if (m[1] !== "videoseries" && !YT_SAMPLE_VIDEO_IDS[m[1]]) return m[1];
  }
  return null;
}

function resolveYoutubeSearchVideoId(query, cb) {
  var ytResults = "https://www.youtube.com/results?search_query=" + encodeURIComponent(query) + "&sp=EgIQAQ%253D%253D";
  var url = "https://r.jina.ai/" + ytResults;
  fetch(url, { credentials: "omit", mode: "cors" })
    .then(function(r) {
      if (!r.ok) throw new Error("fetch");
      return r.text();
    })
    .then(function(text) {
      var id = extractFirstWatchIdFromHtml(text);
      cb(id);
    })
    .catch(function() {
      cb(null);
    });
}

function reloadYtBackground() {
  destroyYtBgPlayer();
  var host = document.getElementById("yt-bg-player-host");
  if (host) host.innerHTML = "";
  createYtBgPlayer();
  setTimeout(function() { syncVolumeFromSlider(); }, 0);
  setTimeout(function() { syncVolumeFromSlider(); }, 400);
  setTimeout(function() { syncVolumeFromSlider(); }, 1200);
}

function applyYtSearch() {
  syncQueryFromInput();
  ytBgFallbackUsed = false;
  safeBgRotate = 0;
  reloadYtBackground();
}

function bindYtControlsOnce() {
  if (ytControlsBound) return;
  ytControlsBound = true;
  var searchIn = document.getElementById("ytBgSearch");
  var applyBtn = document.getElementById("ytBgSearchApply");
  var onlyBtn = document.getElementById("ytBgOnlyToggle");
  var volWrap = document.getElementById("ytBgVolumeWrap");
  if (searchIn) searchIn.value = SEARCH_LIST;
  if (applyBtn) applyBtn.addEventListener("click", applyYtSearch, false);
  if (searchIn) searchIn.addEventListener("keydown", function(ev) {
    if (ev.key === "Enter") { ev.preventDefault(); applyYtSearch(); }
  }, false);
  if (onlyBtn) onlyBtn.addEventListener("click", function() {
    var on = document.body.classList.toggle("yt-only-mode");
    onlyBtn.setAttribute("aria-pressed", on ? "true" : "false");
    onlyBtn.textContent = on ? "\u30b5\u30a4\u30c8\u306b\u623b\u308b" : "Youtube\u306b\u79fb\u52d5";
    setTimeout(function() { syncVolumeFromSlider(); }, 0);
  }, false);
  if (volWrap) volWrap.addEventListener("input", function(ev) {
    if (!ev.target || ev.target.id !== "ytBgVolume") return;
    syncVolumeFromSlider();
  }, false);
  var pauseTog = document.getElementById("ytBgPauseToggle");
  var loopOnBtn = document.getElementById("ytBgLoopOn");
  var loopOffBtn = document.getElementById("ytBgLoopOff");
  if (pauseTog) pauseTog.addEventListener("click", function() {
    var p = ytBgPlayer;
    var YT = window.YT;
    if (!p || !YT || !p.getPlayerState) return;
    try {
      var st = p.getPlayerState();
      if (st === YT.PlayerState.PLAYING || st === YT.PlayerState.BUFFERING) {
        if (p.pauseVideo) p.pauseVideo();
      } else {
        if (p.playVideo) p.playVideo();
        syncVolumeFromSlider();
      }
      updateYtTransportUi();
    } catch (e) {}
  }, false);
  if (loopOnBtn) loopOnBtn.addEventListener("click", function() {
    ytBgLoop = true;
    updateYtTransportUi();
  }, false);
  if (loopOffBtn) loopOffBtn.addEventListener("click", function() {
    ytBgLoop = false;
    updateYtTransportUi();
  }, false);
  bindYtBgInteractionOnce();
  bindYtCardHandoffOnce();
}

function hideYtCtxMenu() {
  var menu = document.getElementById("ytBgCtxMenu");
  if (!menu) return;
  menu.style.display = "none";
  menu.hidden = true;
}

function showYtCtxMenu(clientX, clientY) {
  var menu = document.getElementById("ytBgCtxMenu");
  if (!menu) return;
  menu.hidden = false;
  menu.style.display = "block";
  var w = menu.offsetWidth || 160;
  var h = menu.offsetHeight || 200;
  var x = Math.min(clientX, window.innerWidth - w - 6);
  var y = Math.min(clientY, window.innerHeight - h - 6);
  menu.style.left = Math.max(4, x) + "px";
  menu.style.top = Math.max(4, y) + "px";
}

function bindYtBgInteractionOnce() {
  if (ytCtxMenuBound) return;
  ytCtxMenuBound = true;
  var menu = document.getElementById("ytBgCtxMenu");
  function toggleBgPlayPause() {
    var now = Date.now();
    if (now - ytBgLastToggleMs < 320) return;
    ytBgLastToggleMs = now;
    var p = ytBgPlayer;
    if (!p || !p.getPlayerState) return;
    try {
      var YT = window.YT;
      if (!YT) return;
      var st = p.getPlayerState();
      if (st === YT.PlayerState.PLAYING) {
        if (p.pauseVideo) p.pauseVideo();
      } else {
        if (p.playVideo) p.playVideo();
        syncVolumeFromSlider();
      }
      updateYtTransportUi();
    } catch (e) {}
  }
  document.addEventListener("click", function(ev) {
    if (menu && !menu.hidden && menu.contains(ev.target)) return;
    if (menu && !menu.hidden) hideYtCtxMenu();
    if (ev.button !== 0) return;
    var el = ev.target;
    if (el && el.closest && el.closest("a,button,input,textarea,select,label,.yt-bg-vol-wrap,.yt-bg-ctx-menu")) return;
    toggleBgPlayPause();
  }, true);
  document.addEventListener("contextmenu", function(ev) {
    var el = ev.target;
    if (el && el.closest && el.closest("#ytBgVolumeWrap,.yt-bg-ctx-menu,a,button,input,textarea,select,label")) return;
    ev.preventDefault();
    showYtCtxMenu(ev.clientX, ev.clientY);
  }, true);
  if (menu) {
    var btns = menu.querySelectorAll("[data-yt-ctx]");
    for (var i = 0; i < btns.length; i++) {
      btns[i].addEventListener("click", function(ev) {
        ev.preventDefault();
        ev.stopPropagation();
        var act = ev.currentTarget.getAttribute("data-yt-ctx");
        var p = ytBgPlayer;
        hideYtCtxMenu();
        if (!p) return;
        try {
          if (act === "play") {
            if (p.playVideo) p.playVideo();
            syncVolumeFromSlider();
            return;
          }
          if (act === "pause") {
            if (p.pauseVideo) p.pauseVideo();
            return;
          }
          if (act === "loop") {
            ytBgLoop = !ytBgLoop;
            updateYtTransportUi();
            return;
          }
          if (act === "mute") {
            var mu = p.isMuted && p.isMuted();
            if (mu) {
              if (p.unMute) p.unMute();
              syncVolumeFromSlider();
            } else {
              if (p.mute) p.mute();
            }
            return;
          }
          if (act === "seekBack" && p.getCurrentTime && p.seekTo) {
            p.seekTo(Math.max(0, p.getCurrentTime() - 10), true);
            return;
          }
          if (act === "seekFwd" && p.getCurrentTime && p.seekTo) {
            p.seekTo(p.getCurrentTime() + 10, true);
            return;
          }
          if (act === "volDown" || act === "volUp") {
            var vel = document.getElementById("ytBgVolume");
            var vv = vel ? (parseInt(vel.value, 10) || 0) : 0;
            vv += act === "volUp" ? 10 : -10;
            if (vv < 0) vv = 0;
            if (vv > 100) vv = 100;
            if (vel) vel.value = String(vv);
            syncVolumeFromSlider();
            return;
          }
          if (act === "openYt") {
            var vid = null;
            try {
              if (p.getVideoData) vid = p.getVideoData().video_id;
            } catch (e1) {}
            if (vid) window.open("https://www.youtube.com/watch?v=" + encodeURIComponent(vid), "_blank", "noopener,noreferrer");
          }
        } catch (e) {}
      });
    }
  }
}

function markBgReady() {
  try { document.body.classList.add("yt-bg-ready"); } catch (e) {}
}

function isLikelyAdTitle(t) {
  if (!t || typeof t !== "string") return false;
  if (/[広告]|スポンサー|Advertisement|Sponsored|^\s*Ad\s| 広告/i.test(t)) return true;
  return false;
}

function skipIfAd(player) {
  try {
    if (!player || !player.getVideoData) return;
    var vd = player.getVideoData();
    var title = (vd && vd.title) ? vd.title : "";
    if (isLikelyAdTitle(title)) {
      if (player.nextVideo) player.nextVideo();
    }
  } catch (e) {}
}

function clearAdTimer() {
  if (adCheckTimer) {
    clearInterval(adCheckTimer);
    adCheckTimer = null;
  }
}

function loadSafeBgVideo(player, idx) {
  var id = SAFE_BG_VIDEO_IDS[idx % SAFE_BG_VIDEO_IDS.length];
  try {
    if (player && player.loadVideoById) player.loadVideoById(id);
  } catch (e) {}
}

function onPlayerError(ev) {
  var p = ev.target;
  try {
    if (!ytBgFallbackUsed) {
      ytBgFallbackUsed = true;
      currentSearchQuery = FALLBACK_SEARCH;
      var fs = document.getElementById("ytBgSearch");
      if (fs) fs.value = FALLBACK_SEARCH;
      reloadYtBackground();
      return;
    }
    if (safeBgRotate < SAFE_BG_VIDEO_IDS.length) {
      loadSafeBgVideo(p, safeBgRotate);
      safeBgRotate++;
      try { if (p.playVideo) p.playVideo(); } catch (e1) {}
      return;
    }
    try {
      if (p && p.loadVideoById) p.loadVideoById(DEFAULT_BG_VIDEO_ID);
      if (p && p.playVideo) p.playVideo();
    } catch (e2) {}
  } catch (e) {
    try {
      if (p && p.loadVideoById) p.loadVideoById(DEFAULT_BG_VIDEO_ID);
      if (p && p.playVideo) p.playVideo();
    } catch (e3) {}
  }
}

function onPlayerStateChange(ev) {
  var p = ev.target;
  var YT = window.YT;
  if (!YT) return;
  clearAdTimer();
  if (ev.data === YT.PlayerState.PLAYING || ev.data === YT.PlayerState.BUFFERING) {
    markBgReady();
  }
  updateYtTransportUi();
  if (ev.data === YT.PlayerState.PLAYING) {
    setTimeout(function() { skipIfAd(p); }, 500);
    setTimeout(function() { skipIfAd(p); }, 2000);
    adCheckTimer = setInterval(function() { skipIfAd(p); }, 3500);
    return;
  }
  if (ev.data === YT.PlayerState.ENDED) {
    try {
      if (ytBgLoop) {
        if (p.seekTo) p.seekTo(0, true);
        if (p.playVideo) p.playVideo();
      } else {
        if (p.seekTo) p.seekTo(0, true);
      }
    } catch (e) {}
  }
}

function onPlayerReady(ev) {
  var p = ev.target;
  bindYtControlsOnce();
  safeBgRotate = 0;
  try {
    syncVolumeFromSlider();
    if (ytHandoffStartPaused) {
      ytHandoffStartPaused = false;
      if (p.pauseVideo) p.pauseVideo();
    } else {
      if (p.playVideo) p.playVideo();
    }
    updateYtTransportUi();
  } catch (e) {}
}

function instantiateYtBgPlayer(videoId) {
  if (!window.YT || !YT.Player) return;
  if (ytBgPlayer) return;
  var host = document.getElementById("yt-bg-player-host");
  if (!host) return;
  var vid = videoId || DEFAULT_BG_VIDEO_ID;
  ytBgPlayer = new YT.Player("yt-bg-player-host", {
    videoId: vid,
    width: "100%",
    height: "100%",
    playerVars: {
      autoplay: 1,
      controls: 0,
      rel: 0,
      modestbranding: 1,
      playsinline: 1,
      enablejsapi: 1,
      iv_load_policy: 3
    },
    events: {
      onReady: onPlayerReady,
      onStateChange: onPlayerStateChange,
      onError: onPlayerError
    }
  });
}

function createYtBgPlayer() {
  if (!window.YT || !YT.Player) return;
  if (ytBgPlayer) return;
  var host = document.getElementById("yt-bg-player-host");
  if (!host) return;
  var ho = consumeYtHandoffHash();
  if (ho && applyYtHandoffParams(ho)) {
    instantiateYtBgPlayer(ho.v);
    return;
  }
  syncQueryFromInput();
  var q = currentSearchQuery;
  var myToken = ytSearchFetchToken;
  resolveYoutubeSearchVideoId(q, function(firstId) {
    if (myToken !== ytSearchFetchToken) return;
    if (ytBgPlayer) return;
    if (firstId) {
      instantiateYtBgPlayer(firstId);
      return;
    }
    resolveYoutubeSearchVideoId(FALLBACK_SEARCH, function(fbId) {
      if (myToken !== ytSearchFetchToken) return;
      if (ytBgPlayer) return;
      instantiateYtBgPlayer(fbId || DEFAULT_BG_VIDEO_ID);
    });
  });
}

function initYtBgPlayer() {
  if (!window.YT || !window.YT.Player) return;
  createYtBgPlayer();
}

window.onYouTubeIframeAPIReady = initYtBgPlayer;

(function scheduleYtBgInit() {
  function tryInit() {
    if (window.YT && window.YT.Player) {
      initYtBgPlayer();
      return true;
    }
    return false;
  }
  if (tryInit()) return;
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function() { tryInit(); }, false);
  }
  var n = 0;
  var tick = setInterval(function() {
    n++;
    if (tryInit() || n > 300) clearInterval(tick);
  }, 50);
})();
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", function() { bindYtCardHandoffOnce(); }, false);
} else {
  bindYtCardHandoffOnce();
}
