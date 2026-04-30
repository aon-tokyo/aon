<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>案件ボード | ITエンジニア案件・求人マッチング</title>
<meta name="description" content="希望言語・フレームワーク・月額・勤務地からIT案件・求人をマッチング。どのドメインでも /aruaru/index.php に置くだけで動く、ロリポップ！対応の案件サイトです。">
<meta name="theme-color" content="#0b1220">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='22' fill='%2306b6d4'/%3E%3Ctext x='50' y='68' font-size='60' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='900'%3E案%3C/text%3E%3C/svg%3E">
<!-- Bootstrap 5.3 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<!-- Google Fonts: Noto Sans JP -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
<style>
/* ── Design Tokens ─────────────────────────────────── */
:root {
  --bg:            #0b1220;
  --bg2:           #0f172a;
  --surface:       #111c33;
  --surface2:      #16223d;
  --border:        rgba(255,255,255,.08);
  --border-strong: rgba(255,255,255,.16);
  --text:          #e5edf7;
  --text-dim:      #9aa8c0;
  --text-muted:    #6b7a93;
  --primary:       #06b6d4;
  --primary-lt:    #22d3ee;
  --primary-glow:  rgba(6,182,212,.18);
  --accent:        #a78bfa;
  --success:       #10b981;
  --warning:       #f59e0b;
  --danger:        #ef4444;
  --r:             14px;
}

/* ── Base ──────────────────────────────────────────── */
html { scroll-behavior: smooth; }
body {
  font-family: 'Noto Sans JP','Hiragino Kaku Gothic ProN','Hiragino Sans',Meiryo,system-ui,sans-serif;
  background:
    radial-gradient(ellipse 1300px 600px at 90% -5%, rgba(6,182,212,.13), transparent 62%),
    radial-gradient(ellipse 900px 500px at -5% 28%,  rgba(167,139,250,.10), transparent 56%),
    var(--bg);
  color: var(--text);
  min-height: 100vh;
  -webkit-font-smoothing: antialiased;
}
a { color: inherit; text-decoration: none; }
::-webkit-scrollbar { width:6px; height:6px; }
::-webkit-scrollbar-track { background: var(--bg2); }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,.14); border-radius:3px; }

/* ── Header ────────────────────────────────────────── */
.site-header {
  position: sticky; top:0; z-index:100;
  background: rgba(11,18,32,.82);
  border-bottom: 1px solid var(--border);
  backdrop-filter: blur(14px) saturate(1.4);
  -webkit-backdrop-filter: blur(14px) saturate(1.4);
}
.brand { display:flex; align-items:center; gap:.6rem; font-weight:900; font-size:1.05rem; }
.brand-icon {
  width:36px; height:36px; border-radius:10px;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  display:grid; place-items:center;
  font-size:1rem; font-weight:900; color:#0b1220;
  box-shadow: 0 6px 18px rgba(6,182,212,.35);
  flex-shrink:0;
}
.nav-a {
  color: var(--text-dim); font-weight:600; font-size:.88rem;
  padding:.42rem .7rem; border-radius:8px; transition:all .15s;
}
.nav-a:hover { color:#fff; background:rgba(255,255,255,.06); }
.btn-cta {
  padding:.48rem 1rem; border-radius:9px; border:0; font-weight:700; font-size:.85rem;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  color:#0b1220; box-shadow:0 6px 18px rgba(6,182,212,.3); transition:filter .15s;
}
.btn-cta:hover { filter:brightness(1.1); }

/* ── Hero ──────────────────────────────────────────── */
.hero { padding:3.5rem 0 2rem; text-align:center; }
.hero-h1 {
  font-size: clamp(1.7rem,4.5vw,2.9rem); font-weight:900; line-height:1.25; letter-spacing:-.01em;
  background: linear-gradient(135deg,#fff 0%,#cfe9ff 55%,#a5b4fc 100%);
  -webkit-background-clip:text; background-clip:text; color:transparent;
}
.hero-sub { color:var(--text-dim); font-size:clamp(.88rem,1.5vw,.98rem); max-width:48rem; margin:.75rem auto 0; line-height:1.7; }
.hero-stat-val { font-size:1.35rem; font-weight:900; color:#fff; display:block; }
.hero-stat-lbl { font-size:.75rem; color:var(--text-muted); }

/* ── Search Panel ──────────────────────────────────── */
.search-panel {
  background: var(--surface); border:1px solid var(--border-strong);
  border-radius:var(--r); padding:1.4rem;
  box-shadow: 0 12px 36px rgba(0,0,0,.38);
}
.f-label {
  font-size:.72rem; font-weight:700; color:var(--text-dim);
  letter-spacing:.06em; text-transform:uppercase; margin-bottom:.32rem; display:block;
}
.form-control, .form-select {
  background: var(--surface2) !important;
  border: 1px solid var(--border-strong) !important;
  color: #fff !important;
  border-radius:9px !important;
  font-size:.88rem;
}
.form-control::placeholder { color:var(--text-muted) !important; }
.form-control:focus, .form-select:focus {
  box-shadow:0 0 0 3px var(--primary-glow) !important;
  border-color:var(--primary-lt) !important;
  outline:none;
}
.form-select option { background:var(--surface2); color:#fff; }
.form-range { accent-color:var(--primary); cursor:pointer; }
.form-check-input { background:var(--surface2); border-color:var(--border-strong); accent-color:var(--primary); }
.form-check-label { color:var(--text-dim); font-size:.88rem; }

/* Rate display */
.rate-val { font-size:1.2rem; font-weight:900; color:var(--primary-lt); }
.rate-val .unit { font-size:.78rem; color:var(--text-muted); font-weight:500; }
.annual-val { font-size:.95rem; font-weight:700; color:var(--accent); }
.annual-val .unit { font-size:.72rem; color:var(--text-muted); font-weight:500; }

/* Chip Selector */
.chip-wrap {
  display:flex; flex-wrap:wrap; gap:.28rem;
  max-height:78px; overflow:hidden; transition:max-height .25s ease;
}
.chip-wrap.open { max-height:none; }
.chip {
  padding:.22rem .58rem; border-radius:9999px; font-size:.74rem; font-weight:700;
  background:rgba(255,255,255,.05); color:var(--text-dim);
  border:1px solid var(--border); cursor:pointer; transition:all .12s; user-select:none;
}
.chip:hover { color:#fff; background:rgba(255,255,255,.1); }
.chip.on { background:var(--primary-glow); color:var(--primary-lt); border-color:rgba(34,211,238,.45); }
.chip-more { font-size:.72rem; color:var(--primary-lt); cursor:pointer; margin-top:.25rem; display:inline-block; }

/* Action Buttons */
.btn-search {
  width:100%; padding:.75rem 1.4rem; border-radius:10px; border:0;
  font-weight:800; font-size:.95rem;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  color:#0b1220; box-shadow:0 8px 24px rgba(6,182,212,.3);
  transition:filter .15s,transform .1s;
}
.btn-search:hover { filter:brightness(1.08); transform:translateY(-1px); }
.btn-reset {
  width:100%; padding:.75rem 1.1rem; border-radius:10px;
  border:1px solid var(--border-strong); background:transparent;
  color:var(--text-dim); font-weight:700; font-size:.88rem; transition:all .15s;
}
.btn-reset:hover { color:#fff; background:rgba(255,255,255,.06); }

/* ── Job Cards ─────────────────────────────────────── */
.card-anken {
  background: linear-gradient(165deg, var(--surface) 0%, var(--surface2) 100%);
  border:1px solid var(--border); border-radius:var(--r); padding:1.1rem;
  display:flex; flex-direction:column; height:100%;
  position:relative; overflow:hidden;
  transition:transform .18s,border-color .18s,box-shadow .18s;
}
.card-anken::before {
  content:""; position:absolute; inset:0 0 auto 0; height:3px;
  background:linear-gradient(90deg, var(--primary), var(--accent));
  opacity:0; transition:opacity .2s;
}
.card-anken:hover { transform:translateY(-3px); border-color:var(--border-strong); box-shadow:0 14px 38px rgba(0,0,0,.48); }
.card-anken:hover::before { opacity:1; }

.bdg { display:inline-block; font-size:.63rem; font-weight:800; padding:.16rem .48rem; border-radius:5px; letter-spacing:.03em; }
.bdg-new    { background:rgba(16,185,129,.16); color:#34d399; }
.bdg-hot    { background:rgba(239,68,68,.16); color:#fb7185; }
.bdg-remote { background:rgba(34,211,238,.16); color:var(--primary-lt); }
.bdg-onsite { background:rgba(245,158,11,.16); color:#fbbf24; }
.bdg-hybrid { background:rgba(167,139,250,.16); color:#c4b5fd; }

.card-title { font-size:.93rem; font-weight:800; color:#fff; line-height:1.45; }
.card-meta  { font-size:.77rem; color:var(--text-dim); display:flex; flex-wrap:wrap; gap:.25rem .75rem; }
.card-meta-i { display:inline-flex; align-items:center; gap:.2rem; }

.stag { font-size:.67rem; font-weight:700; padding:.14rem .48rem; border-radius:5px; }
.stag-lang { background:rgba(6,182,212,.12); color:#67e8f9; border:1px solid rgba(6,182,212,.28); }
.stag-fw   { background:rgba(167,139,250,.12); color:#c4b5fd; border:1px solid rgba(167,139,250,.28); }

.card-rate { font-size:1.22rem; font-weight:900; color:#fff; }
.card-rate small { font-size:.74rem; color:var(--text-muted); font-weight:500; }
.card-annual { font-size:.82rem; font-weight:700; color:var(--accent); margin-left:.4rem; }

.site-badge { font-size:.62rem; font-weight:700; color:var(--text-muted); border:1px solid var(--border); padding:.1rem .42rem; border-radius:5px; }

.btn-apply {
  display:inline-flex; align-items:center; gap:.28rem;
  padding:.52rem .88rem; border-radius:8px; font-size:.78rem; font-weight:800;
  background:var(--primary-glow); color:var(--primary-lt);
  border:1px solid rgba(34,211,238,.35); transition:all .15s;
}
.btn-apply:hover { background:var(--primary); color:#0b1220; border-color:transparent; }

/* Sort */
.sort-sel {
  background:var(--surface2); border:1px solid var(--border-strong); color:#fff;
  padding:.4rem .75rem; border-radius:8px; font-size:.84rem; font-family:inherit;
}

/* Empty */
.empty-state {
  text-align:center; padding:3.5rem 1rem;
  background:var(--surface); border:1px dashed var(--border-strong); border-radius:var(--r);
  color:var(--text-dim);
}
.empty-state strong { display:block; color:#fff; font-size:1.05rem; margin-bottom:.4rem; }

/* ── External Sites ────────────────────────────────── */
.ext-section {
  background:var(--surface); border:1px solid var(--border);
  border-radius:var(--r); padding:1.5rem;
}
.ext-title { font-size:1.05rem; font-weight:800; color:#fff; }
.ext-sub   { font-size:.82rem; color:var(--text-dim); line-height:1.65; }

.ext-card {
  display:block; height:100%; text-decoration:none; color:inherit;
  background:var(--surface2); border:1px solid var(--border);
  border-radius:11px; padding:.9rem 1rem;
  transition:border-color .15s,transform .15s,box-shadow .15s;
}
.ext-card:hover { border-color:var(--border-strong); transform:translateY(-2px); box-shadow:0 8px 22px rgba(0,0,0,.3); color:inherit; }
.ext-card-name { font-size:.92rem; font-weight:800; color:#fff; }
.ext-tag { font-size:.62rem; font-weight:800; padding:.1rem .42rem; border-radius:5px; }
.t-fl   { background:rgba(6,182,212,.15); color:var(--primary-lt); }
.t-sei  { background:rgba(16,185,129,.15); color:#34d399; }
.t-side { background:rgba(245,158,11,.15); color:#fbbf24; }
.t-gen  { background:rgba(167,139,250,.15); color:#c4b5fd; }
.ext-desc { font-size:.78rem; color:var(--text-dim); margin:.28rem 0; line-height:1.55; }
.ext-cta  { font-size:.76rem; font-weight:700; color:var(--primary-lt); display:inline-flex; align-items:center; gap:.22rem; }

/* ── Footer ────────────────────────────────────────── */
.site-footer {
  border-top:1px solid var(--border); padding:1.6rem 1rem;
  text-align:center; color:var(--text-muted); font-size:.76rem; line-height:1.9;
}
.site-footer a { color:var(--text-dim); }
.site-footer a:hover { color:#fff; }
</style>
</head>
<body>

<!-- ═══════════════════════════════════════════════════
     HEADER
════════════════════════════════════════════════════ -->
<header class="site-header">
  <div class="container-xl">
    <div class="d-flex align-items-center justify-content-between py-2 gap-2">
      <a href="#" class="brand">
        <span class="brand-icon" aria-hidden="true">案</span>
        <span>
          <span class="d-block" style="font-size:.98rem;line-height:1.1">案件ボード</span>
          <span class="d-block" style="font-size:.58rem;letter-spacing:.12em;color:var(--text-muted);font-weight:600">ANKEN BOARD</span>
        </span>
      </a>
      <nav class="d-flex align-items-center gap-1" aria-label="メインナビ">
        <a href="#search" class="nav-a d-none d-md-inline">案件を探す</a>
        <a href="#ext" class="nav-a d-none d-md-inline">求人サイト一覧</a>
        <button class="btn-cta" onclick="document.getElementById('q').focus();document.getElementById('search').scrollIntoView({behavior:'smooth'})">案件を探す</button>
      </nav>
    </div>
  </div>
</header>

<!-- ═══════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════ -->
<section class="hero">
  <div class="container-xl px-3">
    <p style="font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--primary-lt);font-weight:700;margin-bottom:.6rem">IT案件・求人マッチング</p>
    <h1 class="hero-h1">スキルと希望条件から<br>あなたにぴったりの案件が見つかる。</h1>
    <p class="hero-sub">言語・フレームワーク・月額・勤務地で絞り込み。マッチした案件の外部サイトへ直接応募 ＋ 似た求人が見つかる外部サービスもまとめてご紹介。</p>
    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4" style="row-gap:.75rem">
      <div class="text-center"><span class="hero-stat-val" id="stat-total">35</span><span class="hero-stat-lbl d-block">掲載案件</span></div>
      <div class="text-center"><span class="hero-stat-val">10</span><span class="hero-stat-lbl d-block">外部求人サイト</span></div>
      <div class="text-center"><span class="hero-stat-val">80%</span><span class="hero-stat-lbl d-block">リモート対応</span></div>
      <div class="text-center"><span class="hero-stat-val">¥60〜130万</span><span class="hero-stat-lbl d-block">月額レンジ</span></div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     SEARCH PANEL
════════════════════════════════════════════════════ -->
<section class="pb-3" id="search">
  <div class="container-xl px-3">
    <div class="search-panel">

      <!-- Row 1: Keyword / Role / Style -->
      <div class="row g-2 mb-2">
        <div class="col-12 col-md-5">
          <label class="f-label" for="q">キーワード</label>
          <input class="form-control" id="q" type="search" placeholder="例：React、フルスタック、AI、Go">
        </div>
        <div class="col-6 col-md-4">
          <label class="f-label" for="role">職種カテゴリ</label>
          <select class="form-select" id="role">
            <option value="">すべての職種</option>
            <option>フロントエンド</option>
            <option>バックエンド</option>
            <option>フルスタック</option>
            <option>モバイル</option>
            <option>インフラ／SRE</option>
            <option>データ／AI・ML</option>
            <option>PM／PMO</option>
          </select>
        </div>
        <div class="col-6 col-md-3">
          <label class="f-label" for="style">働き方</label>
          <select class="form-select" id="style">
            <option value="">すべて</option>
            <option value="remote">フルリモート</option>
            <option value="hybrid">ハイブリッド</option>
            <option value="onsite">常駐</option>
          </select>
        </div>
      </div>

      <!-- Row 2: Language chips -->
      <div class="mb-2">
        <label class="f-label">希望プログラミング言語 <span style="color:var(--text-muted);font-weight:500;text-transform:none;letter-spacing:0">（複数選択可・OR 検索）</span></label>
        <div class="chip-wrap" id="lang-chips"></div>
        <span class="chip-more" id="lang-more" onclick="toggleChips('lang')">▼ すべて表示</span>
      </div>

      <!-- Row 3: Framework chips -->
      <div class="mb-3">
        <label class="f-label">希望フレームワーク・ツール <span style="color:var(--text-muted);font-weight:500;text-transform:none;letter-spacing:0">（複数選択可・OR 検索）</span></label>
        <div class="chip-wrap" id="fw-chips"></div>
        <span class="chip-more" id="fw-more" onclick="toggleChips('fw')">▼ すべて表示</span>
      </div>

      <!-- Row 4: Rate / Annual salary -->
      <div class="row g-2 mb-2">
        <div class="col-12 col-md-7">
          <label class="f-label" for="rate-slider">希望月額（下限）</label>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <input type="range" class="form-range flex-grow-1" id="rate-slider" min="0" max="200" step="5" value="0" style="min-width:120px">
            <input type="number" class="form-control text-center" id="rate-num" min="0" max="200" step="5" value="0" style="width:70px;flex-shrink:0">
            <span style="color:var(--text-dim);font-size:.82rem;white-space:nowrap">万円/月〜</span>
          </div>
          <div class="d-flex justify-content-between mt-1" style="font-size:.68rem;color:var(--text-muted)">
            <span>0（下限なし）</span><span>100万</span><span>200万</span>
          </div>
        </div>
        <div class="col-12 col-md-5">
          <label class="f-label" for="annual-num">希望年収（下限・月額と連動）</label>
          <div class="d-flex align-items-center gap-2">
            <input type="number" class="form-control" id="annual-num" min="0" max="2400" step="60" value="0" placeholder="0">
            <span style="color:var(--text-dim);font-size:.82rem;white-space:nowrap">万円/年〜</span>
          </div>
          <div style="font-size:.68rem;color:var(--text-muted);margin-top:.3rem">月額 × 12 で自動換算（入力も可）</div>
        </div>
      </div>

      <!-- Row 5: Location -->
      <div class="row g-2 mb-3 align-items-end">
        <div class="col-12 col-sm-4 col-md-3">
          <label class="f-label" for="pref">都道府県</label>
          <select class="form-select" id="pref"></select>
        </div>
        <div class="col-12 col-sm-4 col-md-3">
          <label class="f-label" for="city">市区町村</label>
          <input class="form-control" id="city" type="text" placeholder="例：渋谷区">
        </div>
        <div class="col-12 col-sm-4 col-md-3">
          <label class="f-label" for="stn">最寄り駅</label>
          <input class="form-control" id="stn" type="text" placeholder="例：渋谷、梅田">
        </div>
        <div class="col-12 col-md-3 d-flex align-items-center pt-2 pt-md-0" style="padding-top:1.6rem !important">
          <div class="form-check m-0">
            <input class="form-check-input" type="checkbox" id="remote-ok">
            <label class="form-check-label" for="remote-ok">リモート・ハイブリッドのみ</label>
          </div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="row g-2">
        <div class="col-8 col-sm-9 col-md-10"><button class="btn-search" onclick="render()">マッチする案件を探す</button></div>
        <div class="col-4 col-sm-3 col-md-2"><button class="btn-reset" onclick="resetAll()">リセット</button></div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     RESULTS
════════════════════════════════════════════════════ -->
<main class="py-3" id="results">
  <div class="container-xl px-3">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <h2 style="font-size:1.08rem;font-weight:800;color:#fff;margin:0">
        マッチング結果 <span id="result-count" style="font-size:.88rem;color:var(--text-dim);font-weight:600"></span>
      </h2>
      <select class="sort-sel" id="sort" onchange="render()">
        <option value="new">新着順</option>
        <option value="rate-desc">月額が高い順</option>
        <option value="rate-asc">月額が低い順</option>
        <option value="score">マッチ度順</option>
      </select>
    </div>

    <div class="row g-3" id="grid"></div>

    <!-- External Sites -->
    <div class="mt-4" id="ext">
      <div class="ext-section">
        <div class="ext-title mb-1">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-.15em;margin-right:.35rem"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
          この条件でもっと探す — 外部求人・案件サイト
        </div>
        <p class="ext-sub mb-3" id="ext-sub"></p>
        <div class="row g-2" id="ext-grid"></div>
      </div>
    </div>

  </div>
</main>

<!-- ═══════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════ -->
<footer class="site-footer">
  <div>© 2026 案件ボード — ITエンジニア案件・求人マッチング（サンプルサイト）</div>
  <div class="mt-1">
    <a href="#search">案件を探す</a> ·
    <a href="#ext">外部求人サイト</a> ·
    <a href="mailto:contact@example.com">お問い合わせ</a>
  </div>
  <!--
    ════════════════════════════════════════════════════
    ロリポップ！等 共用レンタルサーバーへのアップ手順
    ════════════════════════════════════════════════════
    【必要ファイル】 各ドメイン直下の /aruaru/index.php 1つのみ。
    【サーバー要件】 PHP / Python / Node.js 一切不要。
                   Bootstrap / Google Fonts は CDN 経由。
    【アップ手順】
      1. ロリポップ！ユーザー専用ページ → FTP 情報を確認
      2. FFFTP / FileZilla / ロリポップ！FTP（ブラウザ）で接続
      3. 独自ドメイン用の公開フォルダに aruaru/index.php をアップロード
      4. https://あなたのドメイン/aruaru/index.php にアクセスして確認
    【その他対応サーバー】
      さくらのレンタルサーバ / エックスサーバー / ConoHa WING /
      GitHub Pages / Cloudflare Pages / Netlify / Vercel 等すべて対応。
    ════════════════════════════════════════════════════
  -->
</footer>

<!-- Bootstrap JS (accordion/modal等が必要な場合に備えて) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ================================================================
   DATA
   ================================================================ */

/** @type {string[]} 主要プログラミング言語（2026年4月） */
const LANGS = [
  "JavaScript","TypeScript","Python","Go","PHP","Ruby","Java","Kotlin",
  "Swift","Rust","C#","C++","Scala","R","Dart","Elixir","Haskell","Lua","COBOL","VBA"
];

/** @type {Object.<string,string[]>} フレームワーク・ツール */
const FWS = {
  "フロントエンド": [
    "React","Next.js","Vue.js","Nuxt.js","Angular","Svelte","SvelteKit",
    "Astro","Remix","Solid.js","HTMX"
  ],
  "バックエンド": [
    "Express","Fastify","NestJS","Django","FastAPI","Flask",
    "Ruby on Rails","Laravel","Spring Boot","ASP.NET Core","Gin","Echo","Phoenix"
  ],
  "モバイル": [
    "React Native","Flutter","SwiftUI","Jetpack Compose","Expo"
  ],
  "インフラ / ツール": [
    "Docker","Kubernetes","Terraform","Ansible","AWS CDK",
    "GraphQL","REST API","dbt","Snowflake","LangChain"
  ]
};

/** @type {string[]} 都道府県リスト */
const PREFS = [
  "","北海道","青森県","岩手県","宮城県","秋田県","山形県","福島県",
  "茨城県","栃木県","群馬県","埼玉県","千葉県","東京都","神奈川県",
  "新潟県","富山県","石川県","福井県","山梨県","長野県","岐阜県",
  "静岡県","愛知県","三重県","滋賀県","京都府","大阪府","兵庫県",
  "奈良県","和歌山県","鳥取県","島根県","岡山県","広島県","山口県",
  "徳島県","香川県","愛媛県","高知県","福岡県","佐賀県","長崎県",
  "熊本県","大分県","宮崎県","鹿児島県","沖縄県"
];

/**
 * @typedef {Object} Anken
 * @prop {number}   id
 * @prop {string}   title
 * @prop {string}   role
 * @prop {string}   style     "remote"|"hybrid"|"onsite"
 * @prop {string[]} langs
 * @prop {string[]} fws
 * @prop {number}   rate      万円/月
 * @prop {string}   prefecture
 * @prop {string}   city
 * @prop {string}   station
 * @prop {string}   location
 * @prop {string}   duration
 * @prop {number}   posted    days ago
 * @prop {boolean}  [hot]
 * @prop {string}   siteName
 * @prop {string}   applyUrl
 */

/** @type {Anken[]} */
const ANKEN = [
  /* ── フロントエンド ─────────────────────────────── */
  {id:1,title:"大規模ECサイト フロントエンド開発（React / Next.js 15）",
   role:"フロントエンド",style:"remote",langs:["TypeScript","JavaScript"],fws:["React","Next.js","GraphQL"],
   rate:85,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"6ヶ月〜",posted:0,hot:true,
   siteName:"レバテックフリーランス",
   applyUrl:"https://freelance.levtech.jp/project/search/?keyword=React+Next.js+TypeScript"},

  {id:2,title:"動画配信PF フロントエンド刷新（Vue 3 / Nuxt 3）",
   role:"フロントエンド",style:"hybrid",langs:["TypeScript","JavaScript"],fws:["Vue.js","Nuxt.js"],
   rate:78,prefecture:"東京都",city:"渋谷区",station:"渋谷",location:"東京・週2出社",duration:"6ヶ月",posted:2,
   siteName:"ITプロパートナーズ",
   applyUrl:"https://itpropartners.com/project/?keyword=Vue+Nuxt"},

  {id:3,title:"SaaS 管理画面リニューアル（Angular 18）",
   role:"フロントエンド",style:"onsite",langs:["TypeScript"],fws:["Angular"],
   rate:72,prefecture:"神奈川県",city:"横浜市",station:"横浜",location:"横浜・常駐",duration:"4ヶ月",posted:5,
   siteName:"クラウドテック",
   applyUrl:"https://crowdtech.jp/projects/search/?word=Angular+TypeScript"},

  {id:4,title:"金融ダッシュボード 新規開発（Svelte / SvelteKit）",
   role:"フロントエンド",style:"remote",langs:["TypeScript","JavaScript"],fws:["Svelte","SvelteKit"],
   rate:80,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"3ヶ月〜",posted:1,
   siteName:"Findy Freelance",
   applyUrl:"https://findy-code.io/freelance/projects?keyword=Svelte"},

  {id:5,title:"コンテンツサイト新規構築（Astro / React）",
   role:"フロントエンド",style:"remote",langs:["TypeScript","JavaScript"],fws:["Astro","React"],
   rate:65,prefecture:"大阪府",city:"",station:"",location:"フルリモート",duration:"2ヶ月",posted:7,
   siteName:"Midworks",
   applyUrl:"https://midworks.com/projects/?keyword=Astro+React"},

  {id:6,title:"Rust + WebAssembly 高性能ブラウザアプリ開発",
   role:"フロントエンド",style:"remote",langs:["Rust","JavaScript"],fws:["React"],
   rate:102,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"4ヶ月",posted:8,hot:true,
   siteName:"Findy Freelance",
   applyUrl:"https://findy-code.io/freelance/projects?keyword=Rust+WebAssembly"},

  {id:7,title:"Next.js + Remix フロント統合マイグレーション",
   role:"フロントエンド",style:"remote",langs:["TypeScript"],fws:["Next.js","Remix","React"],
   rate:87,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"3ヶ月",posted:3,
   siteName:"Findy（転職）",
   applyUrl:"https://findy-code.io/job-offers?search=Next.js+Remix"},

  {id:8,title:"WordPress → Headless CMS 移行（Next.js）",
   role:"フロントエンド",style:"hybrid",langs:["TypeScript","JavaScript"],fws:["Next.js","React"],
   rate:62,prefecture:"大阪府",city:"大阪市",station:"難波",location:"大阪・週2出社",duration:"2ヶ月",posted:9,
   siteName:"クラウドテック",
   applyUrl:"https://crowdtech.jp/projects/search/?word=Next.js+WordPress+ヘッドレス"},

  /* ── バックエンド ────────────────────────────────── */
  {id:9,title:"決済API 設計・開発（Go / Gin）",
   role:"バックエンド",style:"remote",langs:["Go"],fws:["Gin","Docker"],
   rate:95,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"長期",posted:1,hot:true,
   siteName:"レバテックフリーランス",
   applyUrl:"https://freelance.levtech.jp/project/search/?keyword=Go+Gin+API"},

  {id:10,title:"Go / Echo マイクロサービス API 開発",
   role:"バックエンド",style:"remote",langs:["Go"],fws:["Echo","Docker","Kubernetes"],
   rate:93,prefecture:"大阪府",city:"",station:"",location:"フルリモート",duration:"長期",posted:0,
   siteName:"ITプロパートナーズ",
   applyUrl:"https://itpropartners.com/project/?keyword=Go+Echo+マイクロサービス"},

  {id:11,title:"ヘルスケアSaaS API 構築（Python / FastAPI）",
   role:"バックエンド",style:"remote",langs:["Python"],fws:["FastAPI","Docker"],
   rate:88,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"6ヶ月〜",posted:3,hot:true,
   siteName:"Midworks",
   applyUrl:"https://midworks.com/projects/?keyword=Python+FastAPI"},

  {id:12,title:"Django REST API + PostgreSQL 設計・開発",
   role:"バックエンド",style:"remote",langs:["Python"],fws:["Django","REST API"],
   rate:77,prefecture:"福岡県",city:"",station:"",location:"フルリモート",duration:"3ヶ月",posted:10,
   siteName:"ITプロパートナーズ",
   applyUrl:"https://itpropartners.com/project/?keyword=Django+Python+API"},

  {id:13,title:"ECバックエンド 機能追加（PHP / Laravel 11）",
   role:"バックエンド",style:"hybrid",langs:["PHP"],fws:["Laravel","REST API"],
   rate:68,prefecture:"愛知県",city:"名古屋市",station:"名古屋",location:"名古屋・週3出社",duration:"6ヶ月",posted:4,
   siteName:"クラウドテック",
   applyUrl:"https://crowdtech.jp/projects/search/?word=PHP+Laravel"},

  {id:14,title:"PHP 8 / Laravel 11 サービス API リプレイス",
   role:"バックエンド",style:"hybrid",langs:["PHP"],fws:["Laravel","REST API","Docker"],
   rate:73,prefecture:"福岡県",city:"福岡市",station:"博多",location:"福岡・週2出社",duration:"4ヶ月",posted:17,
   siteName:"Midworks",
   applyUrl:"https://midworks.com/projects/?keyword=PHP+Laravel+API"},

  {id:15,title:"ERPシステム API 開発（Java / Spring Boot）",
   role:"バックエンド",style:"onsite",langs:["Java"],fws:["Spring Boot","Docker"],
   rate:90,prefecture:"東京都",city:"千代田区",station:"大手町",location:"大手町・常駐",duration:"長期",posted:6,
   siteName:"Indeed Japan",
   applyUrl:"https://jp.indeed.com/jobs?q=Java+Spring+Boot+フリーランス"},

  {id:16,title:"C# / ASP.NET Core 業務システム改修",
   role:"バックエンド",style:"onsite",langs:["C#"],fws:["ASP.NET Core","Docker"],
   rate:78,prefecture:"神奈川県",city:"川崎市",station:"川崎",location:"川崎・常駐",duration:"6ヶ月",posted:12,
   siteName:"Indeed Japan",
   applyUrl:"https://jp.indeed.com/jobs?q=C%23+ASP.NET+フリーランス"},

  {id:17,title:"スタートアップ自社PF 開発（Ruby on Rails）",
   role:"バックエンド",style:"hybrid",langs:["Ruby"],fws:["Ruby on Rails","GraphQL"],
   rate:75,prefecture:"東京都",city:"渋谷区",station:"渋谷",location:"渋谷・週2出社",duration:"長期",posted:8,
   siteName:"Wantedly",
   applyUrl:"https://www.wantedly.com/projects?query=Ruby+Rails"},

  {id:18,title:"Elixir / Phoenix リアルタイム通信基盤",
   role:"バックエンド",style:"remote",langs:["Elixir"],fws:["Phoenix"],
   rate:88,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"3ヶ月",posted:15,
   siteName:"Wantedly",
   applyUrl:"https://www.wantedly.com/projects?query=Elixir+Phoenix"},

  {id:19,title:"チャットSaaS バックエンド（Node.js / NestJS）",
   role:"バックエンド",style:"remote",langs:["TypeScript"],fws:["NestJS","Fastify","Docker"],
   rate:82,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"4ヶ月〜",posted:2,
   siteName:"Findy Freelance",
   applyUrl:"https://findy-code.io/freelance/projects?keyword=NestJS+Node.js"},

  /* ── フルスタック ────────────────────────────────── */
  {id:20,title:"スタートアップ CTO候補（Next.js + FastAPI）",
   role:"フルスタック",style:"hybrid",langs:["TypeScript","Python"],fws:["Next.js","FastAPI","Docker"],
   rate:130,prefecture:"東京都",city:"港区",station:"六本木",location:"東京・週3出社",duration:"長期",posted:0,hot:true,
   siteName:"Green",
   applyUrl:"https://www.green-japan.com/search?keyword=CTO+フルスタック+Next.js"},

  {id:21,title:"BtoB SaaS フルスタック開発（React + Rails）",
   role:"フルスタック",style:"hybrid",langs:["TypeScript","Ruby"],fws:["React","Ruby on Rails"],
   rate:85,prefecture:"大阪府",city:"大阪市",station:"梅田",location:"大阪・週2出社",duration:"6ヶ月",posted:3,
   siteName:"Offers",
   applyUrl:"https://offers.jp/jobs?keyword=フルスタック+React+Rails"},

  {id:22,title:"社内ツール内製開発（Vue 3 + Laravel）",
   role:"フルスタック",style:"onsite",langs:["JavaScript","PHP"],fws:["Vue.js","Laravel"],
   rate:70,prefecture:"埼玉県",city:"さいたま市",station:"大宮",location:"大宮・常駐",duration:"3ヶ月",posted:14,
   siteName:"クラウドテック",
   applyUrl:"https://crowdtech.jp/projects/search/?word=Vue+Laravel+フルスタック"},

  /* ── モバイル ────────────────────────────────────── */
  {id:23,title:"iOS ショッピングアプリ（Swift / SwiftUI）",
   role:"モバイル",style:"remote",langs:["Swift"],fws:["SwiftUI"],
   rate:88,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"4ヶ月",posted:2,
   siteName:"ITプロパートナーズ",
   applyUrl:"https://itpropartners.com/project/?keyword=Swift+SwiftUI+iOS"},

  {id:24,title:"Android アプリ刷新（Kotlin / Jetpack Compose）",
   role:"モバイル",style:"remote",langs:["Kotlin"],fws:["Jetpack Compose"],
   rate:82,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"5ヶ月",posted:5,hot:true,
   siteName:"クラウドテック",
   applyUrl:"https://crowdtech.jp/projects/search/?word=Kotlin+Jetpack+Compose+Android"},

  {id:25,title:"クロスプラットフォームアプリ（Flutter / Dart）",
   role:"モバイル",style:"remote",langs:["Dart"],fws:["Flutter","Expo"],
   rate:78,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"6ヶ月",posted:1,
   siteName:"Midworks",
   applyUrl:"https://midworks.com/projects/?keyword=Flutter+Dart"},

  {id:26,title:"医療アプリ React Native 開発（iOS/Android 両対応）",
   role:"モバイル",style:"hybrid",langs:["TypeScript"],fws:["React Native","Expo"],
   rate:83,prefecture:"東京都",city:"新宿区",station:"新宿",location:"新宿・週2出社",duration:"長期",posted:9,
   siteName:"Findy Freelance",
   applyUrl:"https://findy-code.io/freelance/projects?keyword=React+Native+モバイル"},

  /* ── インフラ / SRE ──────────────────────────────── */
  {id:27,title:"AWS クラウド移行 SRE（Terraform / Kubernetes）",
   role:"インフラ／SRE",style:"remote",langs:["Go","Python"],fws:["Kubernetes","Terraform","AWS CDK"],
   rate:105,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"6ヶ月〜",posted:1,hot:true,
   siteName:"レバテックフリーランス",
   applyUrl:"https://freelance.levtech.jp/project/search/?keyword=AWS+Terraform+Kubernetes+SRE"},

  {id:28,title:"MLOps 基盤構築（Kubernetes / Airflow / Python）",
   role:"インフラ／SRE",style:"remote",langs:["Python","Go"],fws:["Kubernetes","Docker","Ansible"],
   rate:98,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"4ヶ月",posted:4,
   siteName:"Midworks",
   applyUrl:"https://midworks.com/projects/?keyword=MLOps+Kubernetes+Python"},

  {id:29,title:"大手通信 インフラ設計・DevOps 推進",
   role:"インフラ／SRE",style:"onsite",langs:["Python"],fws:["Ansible","Terraform","Docker"],
   rate:95,prefecture:"東京都",city:"港区",station:"品川",location:"品川・常駐",duration:"長期",posted:7,
   siteName:"ITプロパートナーズ",
   applyUrl:"https://itpropartners.com/project/?keyword=DevOps+インフラ+Terraform"},

  /* ── データ / AI・ML ─────────────────────────────── */
  {id:30,title:"生成AI チャットボット開発（LangChain / FastAPI）",
   role:"データ／AI・ML",style:"remote",langs:["Python"],fws:["FastAPI","LangChain","Docker"],
   rate:110,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"3ヶ月",posted:0,hot:true,
   siteName:"Offers",
   applyUrl:"https://offers.jp/jobs?keyword=LangChain+生成AI+Python+FastAPI"},

  {id:31,title:"データ基盤構築（dbt / Snowflake / Python）",
   role:"データ／AI・ML",style:"remote",langs:["Python","R"],fws:["dbt","Snowflake"],
   rate:92,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"6ヶ月",posted:3,
   siteName:"Findy Freelance",
   applyUrl:"https://findy-code.io/freelance/projects?keyword=dbt+Snowflake+データエンジニア"},

  {id:32,title:"Scala / Spark ビッグデータ処理最適化",
   role:"データ／AI・ML",style:"hybrid",langs:["Scala","Python"],fws:["Docker"],
   rate:100,prefecture:"東京都",city:"品川区",station:"品川",location:"東京・週1出社",duration:"3ヶ月",posted:6,
   siteName:"Indeed Japan",
   applyUrl:"https://jp.indeed.com/jobs?q=Scala+Spark+データエンジニア"},

  {id:33,title:"推薦システム ML エンジニア（Python / PyTorch）",
   role:"データ／AI・ML",style:"remote",langs:["Python"],fws:["FastAPI","Docker"],
   rate:115,prefecture:"東京都",city:"",station:"",location:"フルリモート",duration:"長期",posted:11,hot:true,
   siteName:"Green",
   applyUrl:"https://www.green-japan.com/search?keyword=機械学習+MLエンジニア+Python"},

  /* ── PM / PMO ────────────────────────────────────── */
  {id:34,title:"大手金融 ITプロジェクト PM（アジャイル推進）",
   role:"PM／PMO",style:"onsite",langs:[],fws:[],
   rate:95,prefecture:"東京都",city:"千代田区",station:"大手町",location:"大手町・常駐",duration:"長期",posted:5,
   siteName:"Green",
   applyUrl:"https://www.green-japan.com/search?keyword=PM+PMO+ITプロジェクト"},

  {id:35,title:"スタートアップ プロダクトマネージャー（週3〜）",
   role:"PM／PMO",style:"hybrid",langs:[],fws:[],
   rate:85,prefecture:"東京都",city:"渋谷区",station:"渋谷",location:"渋谷・週3出社",duration:"長期",posted:2,
   siteName:"Wantedly",
   applyUrl:"https://www.wantedly.com/projects?query=プロダクトマネージャー+PM"},
];

/** @type {{name:string,tag:string,tagClass:string,desc:string,baseUrl:string}[]} */
const EXT_SITES = [
  {name:"レバテックフリーランス", tag:"フリーランス", tagClass:"t-fl",
   desc:"案件数・単価ともに国内最大級のITフリーランス向けエージェント。高単価・長期案件が豊富。",
   baseUrl:"https://freelance.levtech.jp/project/search/?keyword="},
  {name:"ITプロパートナーズ", tag:"副業・フリーランス", tagClass:"t-fl",
   desc:"週2〜3日から参画できる副業・フリーランス案件に特化。スタートアップ系が豊富。",
   baseUrl:"https://itpropartners.com/project/?keyword="},
  {name:"Midworks", tag:"フリーランス", tagClass:"t-fl",
   desc:"フリーランスでも社会保険・各種保障が充実。正社員並みのサポートで安心して働ける。",
   baseUrl:"https://midworks.com/projects/?keyword="},
  {name:"クラウドテック", tag:"フリーランス", tagClass:"t-fl",
   desc:"クラウドワークスが運営するITフリーランス向けエージェント。多様な職種・単価帯。",
   baseUrl:"https://crowdtech.jp/projects/search/?word="},
  {name:"Findy Freelance", tag:"フリーランス", tagClass:"t-fl",
   desc:"GitHubスキルスコアで自動マッチング。エンジニア目線のフリーランス案件サービス。",
   baseUrl:"https://findy-code.io/freelance/projects?keyword="},
  {name:"Offers", tag:"副業・複業", tagClass:"t-side",
   desc:"副業・複業×開発案件のマッチング。スタートアップや成長企業の週1〜案件が充実。",
   baseUrl:"https://offers.jp/jobs?keyword="},
  {name:"Green", tag:"正社員転職", tagClass:"t-sei",
   desc:"IT・Web・ゲーム業界特化の転職サービス。正社員でキャリアアップしたい方向け。",
   baseUrl:"https://www.green-japan.com/search?keyword="},
  {name:"Wantedly", tag:"スタートアップ", tagClass:"t-side",
   desc:"「やりたいこと」でつながる採用サービス。スタートアップ・ベンチャーの求人が豊富。",
   baseUrl:"https://www.wantedly.com/projects?query="},
  {name:"Findy（転職）", tag:"エンジニア転職", tagClass:"t-sei",
   desc:"スキルスコアでスカウトが届くエンジニア特化の転職サービス。高年収求人多数。",
   baseUrl:"https://findy-code.io/job-offers?search="},
  {name:"Indeed Japan", tag:"総合求人", tagClass:"t-gen",
   desc:"国内最大級の求人検索エンジン。正社員・契約社員・フリーランスを幅広く検索可能。",
   baseUrl:"https://jp.indeed.com/jobs?q="},
];

/* ================================================================
   STATE
   ================================================================ */
const selLangs = new Set();
const selFws   = new Set();

/* ================================================================
   INIT
   ================================================================ */
(function init() {
  /* Prefecture select */
  const prefSel = document.getElementById('pref');
  PREFS.forEach(p => {
    const o = document.createElement('option');
    o.value = p; o.textContent = p || '都道府県を選択';
    prefSel.appendChild(o);
  });

  /* Language chips */
  const langWrap = document.getElementById('lang-chips');
  LANGS.forEach(l => {
    const b = document.createElement('button');
    b.type = 'button'; b.className = 'chip'; b.textContent = l;
    b.addEventListener('click', () => { toggle(selLangs, l, b); render(); });
    langWrap.appendChild(b);
  });

  /* Framework chips (all categories, grouped by label) */
  const fwWrap = document.getElementById('fw-chips');
  Object.entries(FWS).forEach(([cat, fws]) => {
    /* Category label chip */
    const lbl = document.createElement('span');
    lbl.className = 'chip'; lbl.style.cssText = 'background:rgba(167,139,250,.08);color:var(--accent);border-color:rgba(167,139,250,.25);cursor:default;font-size:.68rem';
    lbl.textContent = cat;
    fwWrap.appendChild(lbl);

    fws.forEach(fw => {
      const b = document.createElement('button');
      b.type = 'button'; b.className = 'chip'; b.textContent = fw;
      b.addEventListener('click', () => { toggle(selFws, fw, b); render(); });
      fwWrap.appendChild(b);
    });
  });

  /* Rate / Salary sync */
  const slider    = document.getElementById('rate-slider');
  const rateNum   = document.getElementById('rate-num');
  const annualNum = document.getElementById('annual-num');

  slider.addEventListener('input', () => {
    rateNum.value   = slider.value;
    annualNum.value = Number(slider.value) * 12 || 0;
    render();
  });
  rateNum.addEventListener('input', () => {
    slider.value    = rateNum.value;
    annualNum.value = Number(rateNum.value) * 12 || 0;
    render();
  });
  annualNum.addEventListener('input', () => {
    const m = Math.round((Number(annualNum.value) || 0) / 12);
    rateNum.value  = m;
    slider.value   = m;
    render();
  });

  /* Auto-render on filter changes */
  ['q','role','style','pref','city','stn','sort'].forEach(id => {
    const el = document.getElementById(id);
    el.addEventListener('input',  render);
    el.addEventListener('change', render);
  });
  document.getElementById('remote-ok').addEventListener('change', render);

  render();
})();

/* ================================================================
   HELPERS
   ================================================================ */
function toggle(set, val, btn) {
  if (set.has(val)) { set.delete(val); btn.classList.remove('on'); }
  else              { set.add(val);    btn.classList.add('on');    }
}

function toggleChips(type) {
  const wrap = document.getElementById(type + '-chips');
  const more = document.getElementById(type + '-more');
  const open = wrap.classList.toggle('open');
  more.textContent = open ? '▲ 閉じる' : '▼ すべて表示';
}

/** Build keyword string for external site URLs */
function buildKw() {
  const parts = [];
  const q = document.getElementById('q').value.trim();
  if (q) parts.push(q);
  selLangs.forEach(l => parts.push(l));
  selFws.forEach(f => parts.push(f));
  const role = document.getElementById('role').value;
  if (role) parts.push(role);
  return encodeURIComponent(parts.join(' ').trim().slice(0, 100));
}

/** Human-readable keyword label */
function kwLabel() {
  const parts = [];
  const q = document.getElementById('q').value.trim();
  if (q) parts.push(q);
  selLangs.forEach(l => parts.push(l));
  selFws.forEach(f => parts.push(f));
  return parts.length ? `「${parts.slice(0, 4).join(' / ')}」` : '現在の検索条件';
}

function styleBadge(s) {
  if (s === 'remote')  return '<span class="bdg bdg-remote me-1">リモート</span>';
  if (s === 'hybrid')  return '<span class="bdg bdg-hybrid me-1">ハイブリッド</span>';
  if (s === 'onsite')  return '<span class="bdg bdg-onsite me-1">常駐</span>';
  return '';
}
function postedLabel(d) {
  if (d === 0) return '本日';
  if (d === 1) return '昨日';
  return d + '日前';
}

/* icon inline SVGs */
const ICO_PIN   = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>`;
const ICO_CLOCK = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>`;
const ICO_EXT   = `<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>`;

/* ================================================================
   FILTER + SCORE
   ================================================================ */
/** @param {Anken} a @returns {number} */
function score(a) {
  let sc = 0;
  selLangs.forEach(l => { if (a.langs.includes(l)) sc += 3; });
  selFws.forEach(f =>   { if (a.fws.includes(f))   sc += 2; });
  if (a.hot)    sc += 1;
  if (a.posted <= 1) sc += 1;
  return sc;
}

function filterAnken() {
  const q        = document.getElementById('q').value.trim().toLowerCase();
  const role     = document.getElementById('role').value;
  const style    = document.getElementById('style').value;
  const minRate  = parseInt(document.getElementById('rate-num').value) || 0;
  const pref     = document.getElementById('pref').value;
  const city     = document.getElementById('city').value.trim().toLowerCase();
  const stn      = document.getElementById('stn').value.trim().toLowerCase();
  const remoteOk = document.getElementById('remote-ok').checked;

  return ANKEN.filter(a => {
    if (q) {
      const hay = [a.title, ...a.langs, ...a.fws, a.role, a.location, a.prefecture, a.city, a.station, a.siteName].join(' ').toLowerCase();
      if (!hay.includes(q)) return false;
    }
    if (role  && a.role  !== role)  return false;
    if (style && a.style !== style) return false;
    if (remoteOk && a.style === 'onsite') return false;
    if (selLangs.size && !a.langs.some(l => selLangs.has(l))) return false;
    if (selFws.size   && !a.fws.some(f   => selFws.has(f)))   return false;
    if (minRate > 0 && a.rate < minRate) return false;
    if (pref && a.style !== 'remote' && a.prefecture !== pref) return false;
    if (city && a.city    && !a.city.toLowerCase().includes(city))    return false;
    if (stn  && a.station && !a.station.toLowerCase().includes(stn))  return false;
    return true;
  });
}

/* ================================================================
   RENDER CARDS
   ================================================================ */
function render() {
  let list = filterAnken();
  const sortVal = document.getElementById('sort').value;
  if      (sortVal === 'rate-desc') list.sort((a,b) => b.rate - a.rate);
  else if (sortVal === 'rate-asc')  list.sort((a,b) => a.rate - b.rate);
  else if (sortVal === 'score')     list.sort((a,b) => score(b) - score(a));
  else                              list.sort((a,b) => a.posted - b.posted);

  const grid = document.getElementById('grid');
  document.getElementById('result-count').textContent = `（${list.length}件）`;

  grid.innerHTML = '';

  if (!list.length) {
    grid.innerHTML = `<div class="col-12"><div class="empty-state"><strong>該当する案件が見つかりませんでした</strong>条件を変えてもう一度お試しください。</div></div>`;
  } else {
    list.forEach(a => {
      const col = document.createElement('div');
      col.className = 'col-12 col-md-6 col-lg-4';
      col.innerHTML = cardHtml(a);
      grid.appendChild(col);
    });
  }

  renderExtSites(list);
}

function cardHtml(a) {
  const badges =
    (a.posted <= 1 ? '<span class="bdg bdg-new me-1">NEW</span>' : '') +
    (a.hot         ? '<span class="bdg bdg-hot me-1">注目</span>' : '') +
    styleBadge(a.style);

  const langTags = a.langs.map(l => `<span class="stag stag-lang">${l}</span>`).join('');
  const fwTags   = a.fws.map(f   => `<span class="stag stag-fw">${f}</span>`).join('');

  return `<div class="card-anken">
  <div class="d-flex justify-content-between align-items-start mb-1" style="gap:.35rem">
    <div>${badges}</div>
    <span style="font-size:.7rem;color:var(--text-muted);flex-shrink:0">${postedLabel(a.posted)}</span>
  </div>
  <div class="card-title mb-2">${a.title}</div>
  <div class="card-meta mb-2">
    <span class="card-meta-i">${ICO_PIN}${a.location}</span>
    <span class="card-meta-i">${ICO_CLOCK}${a.duration}</span>
    <span style="color:var(--text-muted)">${a.role}</span>
  </div>
  <div class="d-flex flex-wrap gap-1 mb-2">${langTags}${fwTags}</div>
  <div class="mt-auto">
    <div class="mb-2">
      <span class="card-rate">¥${a.rate}<small> 万円/月</small></span>
      <span class="card-annual">年収 約¥${a.rate * 12}万</span>
    </div>
    <div class="d-flex justify-content-between align-items-center">
      <span class="site-badge">${a.siteName}</span>
      <a href="${a.applyUrl}" target="_blank" rel="noopener noreferrer" class="btn-apply">
        このサイトで応募 ${ICO_EXT}
      </a>
    </div>
  </div>
</div>`;
}

/* ================================================================
   RENDER EXTERNAL SITES
   ================================================================ */
function renderExtSites(filteredList) {
  const kw = buildKw();
  document.getElementById('ext-sub').textContent =
    `${kwLabel()} に関連する案件・求人を外部サービスでも探せます。` +
    `各サイトの検索結果ページへ直接リンクします（別タブで開きます）。`;

  document.getElementById('ext-grid').innerHTML = EXT_SITES.map(s => `
    <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
      <a href="${s.baseUrl}${kw}" target="_blank" rel="noopener noreferrer" class="ext-card">
        <div class="d-flex align-items-center gap-1 mb-1 flex-wrap">
          <span class="ext-card-name">${s.name}</span>
          <span class="ext-tag ${s.tagClass}">${s.tag}</span>
        </div>
        <div class="ext-desc">${s.desc}</div>
        <div class="ext-cta">このサイトで探す ${ICO_EXT}</div>
      </a>
    </div>`).join('');
}

/* ================================================================
   RESET
   ================================================================ */
function resetAll() {
  document.getElementById('q').value         = '';
  document.getElementById('role').value      = '';
  document.getElementById('style').value     = '';
  document.getElementById('rate-slider').value = 0;
  document.getElementById('rate-num').value  = 0;
  document.getElementById('annual-num').value = 0;
  document.getElementById('pref').value      = '';
  document.getElementById('city').value      = '';
  document.getElementById('stn').value       = '';
  document.getElementById('remote-ok').checked = false;
  selLangs.clear();
  selFws.clear();
  document.querySelectorAll('.chip.on').forEach(c => c.classList.remove('on'));
  render();
}
</script>
</body>
</html>
