/**
 * Google Translate URL handling for /top/ blog pages.
 * Loaded after DOM; expects document.body.
 */
(function(){
"use strict";

function isTranslatedBlogView(){
  try{
    if(location.hostname.indexOf(".translate.goog")!==-1) return true;
    if(document.body&&document.body.classList&&document.body.classList.contains("ac-tg-blog-mode")) return true;
    if(sessionStorage.getItem("tgBlogView")==="1") return true;
  }catch(e){}
  return false;
}

function getTranslateRouteParams(){
  var sl="ja", tl="en", hl="ja";
  try{
    var u=new URL(location.href);
    if(u.searchParams.get("_x_tr_sl")) sl=u.searchParams.get("_x_tr_sl");
    if(u.searchParams.get("_x_tr_tl")) tl=u.searchParams.get("_x_tr_tl");
    if(u.searchParams.get("_x_tr_hl")) hl=u.searchParams.get("_x_tr_hl");
  }catch(e){}
  return { sl:sl, tl:tl, hl:hl };
}

function isVideoPlaybackHost(hostname){
  if(!hostname) return false;
  var h=String(hostname).toLowerCase();
  if(h==="youtu.be") return true;
  if(/(^|\.)youtube\.com$/.test(h)) return true;
  if(/(^|\.)youtube-nocookie\.com$/.test(h)) return true;
  if(/(^|\.)vimeo\.com$/.test(h)) return true;
  if(/(^|\.)dailymotion\.com$/.test(h)) return true;
  if(h==="dai.ly") return true;
  if(/(^|\.)nicovideo\.jp$/.test(h)) return true;
  if(/(^|\.)nico\.ms$/.test(h)) return true;
  if(/(^|\.)video\.fc2\.com$/.test(h)) return true;
  if(/(^|\.)facebook\.com$/.test(h)) return true;
  if(h==="fb.watch"||/(^|\.)fb\.watch$/.test(h)) return true;
  if(/(^|\.)instagram\.com$/.test(h)) return true;
  if(/(^|\.)tiktok\.com$/.test(h)) return true;
  if(/(^|\.)twitch\.tv$/.test(h)) return true;
  if(/(^|\.)bilibili\.com$/.test(h)) return true;
  return false;
}

function wrapUrlForTranslateProxy(url){
  if(!url||typeof url!=="string") return url;
  var t=url.trim();
  if(!t||t.indexOf("mailto:")===0||t.indexOf("tel:")===0||t.indexOf("javascript:")===0) return url;
  if(t.charAt(0)==="#"&&t.indexOf("http")===-1) return url;
  try{
    var u=new URL(t, location.href);
    if(u.protocol!=="http:"&&u.protocol!=="https:") return url;
    var host=u.hostname;
    if(host.indexOf(".translate.goog")!==-1){
      var rUn=restoreUrl(u.href);
      if(rUn&&rUn!==u.href){
        try{
          var uUn=new URL(rUn);
          if(isVideoPlaybackHost(uUn.hostname)) return uUn.href;
        }catch(eUn){}
      }
      return u.href;
    }
    if(host==="translate.google.com") return u.href;
    if(isVideoPlaybackHost(host)) return u.href;
    var proxyHost=host.replace(/\./g,"-")+".translate.goog";
    var p=getTranslateRouteParams();
    var qs=new URLSearchParams(u.search);
    qs.set("_x_tr_sl", p.sl);
    qs.set("_x_tr_tl", p.tl);
    qs.set("_x_tr_hl", p.hl);
    qs.set("_x_tr_pto", "wapp");
    var qstr=qs.toString();
    return "https://"+proxyHost+u.pathname+(qstr?"?"+qstr:"")+u.hash;
  }catch(e){
    return url;
  }
}

function restoreUrl(url){
  if(!url||typeof url!=="string") return url;

  try{
    var u=new URL(url);
    var host=u.hostname;
    if(host==="translate.google.com" && (u.pathname==="/website" || u.pathname==="/translate")){
      var wrapped=u.searchParams.get("u");
      if(!wrapped) return url;
      var prev=wrapped;
      for(var di=0; di<3; di++){
        try{
          var dec=decodeURIComponent(prev);
          if(dec===prev) break;
          prev=dec;
        }catch(e){
          break;
        }
      }
      return prev;
    }

    if(host.indexOf(".translate.goog")===-1) return url;

    var originalDomain=host.replace(".translate.goog","").replace(/-/g,".");

    var params=u.searchParams;
    params.delete("_x_tr_sl");
    params.delete("_x_tr_tl");
    params.delete("_x_tr_hl");
    params.delete("_x_tr_pto");
    params.delete("_x_tr_hist");

    var path=u.pathname;
    var search=params.toString();
    var hash=u.hash;
    var restored="https://"+originalDomain+path+(search?"?"+search:"")+hash;
    return restored;
  }catch(e){
    return url;
  }
}

function applyRestoredSrc(el, attr){
  if(!el||!el.getAttribute) return;
  var v=el.getAttribute(attr);
  if(!v) return;
  var fixed=restoreUrl(v);
  if(fixed&&fixed!==v) el.setAttribute(attr, fixed);
}

function fixMediaSources(){
  var sel="video[src],video[data-src],source[src],source[data-src],iframe[src],iframe[data-src],img[src],img[data-src],img[data-original],img[data-lazy-src]";
  var nodes=document.querySelectorAll(sel);
  for(var k=0;k<nodes.length;k++){
    var el=nodes[k];
    applyRestoredSrc(el, "src");
    applyRestoredSrc(el, "data-src");
    applyRestoredSrc(el, "data-original");
    applyRestoredSrc(el, "data-lazy-src");
    applyRestoredSrc(el, "poster");
    var ss=el.getAttribute&&el.getAttribute("srcset");
    if(ss){
      var parts=ss.split(",");
      var out=[];
      for(var si=0;si<parts.length;si++){
        var piece=parts[si].trim();
        if(!piece){ out.push(piece); continue; }
        var sp=piece.lastIndexOf(" ");
        var urlPart=sp>0?piece.slice(0,sp).trim():piece;
        var desc=sp>0?piece.slice(sp):"";
        var fu=restoreUrl(urlPart);
        out.push((fu||urlPart)+(desc||""));
      }
      var joined=out.join(", ");
      if(joined!==ss) el.setAttribute("srcset", joined);
    }
  }
}

function fixAllLinks(){
  var inTg=isTranslatedBlogView();
  var links=document.querySelectorAll("a[href]");
  for(var i=0;i<links.length;i++){
    var href=links[i].getAttribute("href");
    if(!href) continue;
    if(inTg){
      try{
        if(href.charAt(0)==="#"&&href.indexOf("http")===-1) continue;
        var abs=new URL(href, location.href).href;
        var wrapped=wrapUrlForTranslateProxy(abs);
        if(wrapped!==href) links[i].setAttribute("href", wrapped);
        if(links[i].dataset){
          var ds=links[i].dataset;
          var candidates=[ds.href, ds.url, ds.originalUrl, ds.originalHref, ds.src, ds.u];
          for(var ci=0;ci<candidates.length;ci++){
            var cand=candidates[ci];
            if(!cand) continue;
            try{
              var abs2=new URL(cand, location.href).href;
              var w2=wrapUrlForTranslateProxy(abs2);
              if(w2&&w2.indexOf("http")===0){
                links[i].setAttribute("href", w2);
                break;
              }
            }catch(e2){}
          }
        }
      }catch(e){}
    }else{
      var fixed=restoreUrl(href);
      if(fixed!==href) links[i].setAttribute("href", fixed);
      if((!fixed || fixed===href) && links[i].dataset){
        var ds2=links[i].dataset;
        var candidates2=[ds2.href, ds2.url, ds2.originalUrl, ds2.originalHref, ds2.src, ds2.u];
        for(var cj=0;cj<candidates2.length;cj++){
          var cand2=candidates2[cj];
          if(!cand2) continue;
          var fixedCand=restoreUrl(cand2);
          if(fixedCand && fixedCand.indexOf("http")===0){
            links[i].setAttribute("href", fixedCand);
            break;
          }
        }
      }
    }
  }

  fixMediaSources();
}

function hardenImages(){
  var imgs=document.querySelectorAll("img");
  for(var i=0;i<imgs.length;i++){
    (function(img){
      if(!img.getAttribute("data-orig-src")){
        var s=img.getAttribute("src");
        if(s) img.setAttribute("data-orig-src", s);
      }
      img.addEventListener("error", function(){
        try{
          var cur=img.getAttribute("src") || "";
          var orig=img.getAttribute("data-orig-src") || "";
          var fixed=restoreUrl(cur);
          if(fixed && fixed!==cur){
            img.setAttribute("src", fixed);
            return;
          }
          if(orig && orig!==cur){
            img.setAttribute("src", orig);
            return;
          }
        }catch(e){}
      }, { passive:true });
    })(imgs[i]);
  }
}

document.addEventListener("click", function(ev){
  try{
    var el=ev.target;
    while(el && el !== document.documentElement){
      if(el.tagName && String(el.tagName).toLowerCase()==="a") break;
      el=el.parentNode;
    }
    if(!el || !el.getAttribute) return;
    var href=el.getAttribute("href");
    if(!href) return;

    if(isTranslatedBlogView()){
      if(ev.button!==0) return;
      if(href.charAt(0)==="#"&&href.indexOf("http")===-1) return;
      try{
        if(href.indexOf(".translate.goog")!==-1||href.indexOf("translate.google.com/website")!==-1||href.indexOf("translate.google.com/translate")!==-1){
          var rVid=restoreUrl(href);
          if(rVid&&rVid!==href){
            try{
              var uVid=new URL(rVid);
              if(isVideoPlaybackHost(uVid.hostname)){
                ev.preventDefault();
                var tv=el.getAttribute("target")||"_blank";
                if(tv==="_blank") window.open(rVid, "_blank", "noopener,noreferrer");
                else location.href=rVid;
                return;
              }
            }catch(eVid){}
          }
        }
        var abs=new URL(href, location.href).href;
        var u=new URL(abs);
        if(u.protocol!=="http:"&&u.protocol!=="https:") return;
        if(isVideoPlaybackHost(u.hostname)){
          ev.preventDefault();
          var tvd=el.getAttribute("target")||"_blank";
          if(tvd==="_blank") window.open(abs, "_blank", "noopener,noreferrer");
          else location.href=abs;
          return;
        }
        var w=wrapUrlForTranslateProxy(abs);
        if(!w||w===href) return;
        ev.preventDefault();
        var target=el.getAttribute("target")||"_self";
        if(target==="_blank") window.open(w, "_blank", "noopener,noreferrer");
        else location.href=w;
      }catch(e){}
      return;
    }

    if(href.indexOf(".translate.goog")===-1 && href.indexOf("translate.google.com/website")===-1) return;
    var restored=restoreUrl(href);
    if(!restored || restored===href) return;
    ev.preventDefault();
    var target2=el.getAttribute("target") || "_blank";
    if(target2==="_self"){
      window.location.href=restored;
    }else{
      window.open(restored, target2);
    }
  }catch(e){}
}, true);

if(document.readyState==="loading"){
  document.addEventListener("DOMContentLoaded",function(){fixAllLinks(); hardenImages();});
}else{
  fixAllLinks(); hardenImages();
}

window.addEventListener("load",function(){
  fixAllLinks();
  hardenImages();
  setTimeout(fixAllLinks,1000);
  setTimeout(fixAllLinks,3000);
  setTimeout(fixAllLinks,6000);
});

var observer=new MutationObserver(function(){fixAllLinks();});
observer.observe(document.body||document.documentElement,{childList:true,subtree:true,attributes:true,attributeFilter:["href","src","data-src","data-original","data-lazy-src","poster","srcset"]});
})();

