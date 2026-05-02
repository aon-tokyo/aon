<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>案件ボード | ITエンジニア案件・求人マッチング（Laravel版）</title>
<meta name="description" content="希望言語・フレームワーク・月額・勤務地からIT案件・求人をマッチング。外部サイトへの直接応募リンク付き。ロリポップ！ハイスピード以上で動作するLaravel版。">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#0b1220">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='22' fill='%2306b6d4'/%3E%3Ctext x='50' y='68' font-size='60' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='900'%3E案%3C/text%3E%3C/svg%3E">
<!-- Bootstrap 5.3 CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<!-- Google Fonts: Noto Sans JP -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
<style>
:root {
  --bg:#0b1220; --bg2:#0f172a; --surface:#111c33; --surface2:#16223d;
  --border:rgba(255,255,255,.08); --border-s:rgba(255,255,255,.16);
  --text:#e5edf7; --dim:#9aa8c0; --muted:#6b7a93;
  --primary:#06b6d4; --primary-lt:#22d3ee; --primary-glow:rgba(6,182,212,.18);
  --accent:#a78bfa; --success:#10b981; --warning:#f59e0b; --danger:#ef4444;
  --r:14px;
}
html{scroll-behavior:smooth}
body{
  font-family:'Noto Sans JP','Hiragino Kaku Gothic ProN',Meiryo,system-ui,sans-serif;
  background:
    radial-gradient(ellipse 1300px 600px at 90% -5%,rgba(6,182,212,.13),transparent 62%),
    radial-gradient(ellipse 900px 500px at -5% 28%, rgba(167,139,250,.10),transparent 56%),
    var(--bg);
  color:var(--text); min-height:100vh; -webkit-font-smoothing:antialiased;
}
a{color:inherit;text-decoration:none}
::-webkit-scrollbar{width:6px;height:6px}
::-webkit-scrollbar-track{background:var(--bg2)}
::-webkit-scrollbar-thumb{background:rgba(255,255,255,.14);border-radius:3px}

/* Header */
.site-header{position:sticky;top:0;z-index:100;background:rgba(11,18,32,.82);border-bottom:1px solid var(--border);backdrop-filter:blur(14px) saturate(1.4);-webkit-backdrop-filter:blur(14px) saturate(1.4)}
.brand{display:flex;align-items:center;gap:.6rem;font-weight:900;font-size:1.05rem}
.brand-icon{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent));display:grid;place-items:center;font-size:1rem;font-weight:900;color:#0b1220;box-shadow:0 6px 18px rgba(6,182,212,.35);flex-shrink:0}
.nav-a{color:var(--dim);font-weight:600;font-size:.88rem;padding:.42rem .7rem;border-radius:8px;transition:all .15s}
.nav-a:hover{color:#fff;background:rgba(255,255,255,.06)}
.btn-cta{padding:.48rem 1rem;border-radius:9px;border:0;font-weight:700;font-size:.85rem;background:linear-gradient(135deg,var(--primary),var(--accent));color:#0b1220;box-shadow:0 6px 18px rgba(6,182,212,.3);transition:filter .15s}
.btn-cta:hover{filter:brightness(1.1)}

/* Hero */
.hero{padding:3.5rem 0 2rem;text-align:center}
.hero-h1{font-size:clamp(1.7rem,4.5vw,2.9rem);font-weight:900;line-height:1.25;letter-spacing:-.01em;background:linear-gradient(135deg,#fff 0%,#cfe9ff 55%,#a5b4fc 100%);-webkit-background-clip:text;background-clip:text;color:transparent}
.hero-sub{color:var(--dim);font-size:clamp(.88rem,1.5vw,.98rem);max-width:48rem;margin:.75rem auto 0;line-height:1.7}
.hero-stat-val{font-size:1.35rem;font-weight:900;color:#fff;display:block}
.hero-stat-lbl{font-size:.75rem;color:var(--muted)}

/* Search Panel */
.search-panel{background:var(--surface);border:1px solid var(--border-s);border-radius:var(--r);padding:1.4rem;box-shadow:0 12px 36px rgba(0,0,0,.38)}
.f-label{font-size:.72rem;font-weight:700;color:var(--dim);letter-spacing:.06em;text-transform:uppercase;margin-bottom:.32rem;display:block}
.form-control,.form-select{background:var(--surface2)!important;border:1px solid var(--border-s)!important;color:#fff!important;border-radius:9px!important;font-size:.88rem}
.form-control::placeholder{color:var(--muted)!important}
.form-control:focus,.form-select:focus{box-shadow:0 0 0 3px var(--primary-glow)!important;border-color:var(--primary-lt)!important;outline:none}
.form-select option{background:var(--surface2);color:#fff}
.form-range{accent-color:var(--primary);cursor:pointer}
.form-check-input{background:var(--surface2);border-color:var(--border-s);accent-color:var(--primary)}
.form-check-label{color:var(--dim);font-size:.88rem}

/* Chips */
.chip-wrap{display:flex;flex-wrap:wrap;gap:.28rem;max-height:78px;overflow:hidden;transition:max-height .25s ease}
.chip-wrap.open{max-height:none}
.chip{padding:.22rem .58rem;border-radius:9999px;font-size:.74rem;font-weight:700;background:rgba(255,255,255,.05);color:var(--dim);border:1px solid var(--border);cursor:pointer;transition:all .12s;user-select:none}
.chip:hover{color:#fff;background:rgba(255,255,255,.1)}
.chip.on{background:var(--primary-glow);color:var(--primary-lt);border-color:rgba(34,211,238,.45)}
.chip-more{font-size:.72rem;color:var(--primary-lt);cursor:pointer;margin-top:.25rem;display:inline-block}

/* Buttons */
.btn-search{width:100%;padding:.75rem 1.4rem;border-radius:10px;border:0;font-weight:800;font-size:.95rem;background:linear-gradient(135deg,var(--primary),var(--accent));color:#0b1220;box-shadow:0 8px 24px rgba(6,182,212,.3);transition:filter .15s,transform .1s}
.btn-search:hover{filter:brightness(1.08);transform:translateY(-1px)}
.btn-reset{width:100%;padding:.75rem 1.1rem;border-radius:10px;border:1px solid var(--border-s);background:transparent;color:var(--dim);font-weight:700;font-size:.88rem;transition:all .15s}
.btn-reset:hover{color:#fff;background:rgba(255,255,255,.06)}
.sort-sel{background:var(--surface2);border:1px solid var(--border-s);color:#fff;padding:.4rem .75rem;border-radius:8px;font-size:.84rem;font-family:inherit}

/* Cards */
.card-anken{background:linear-gradient(165deg,var(--surface) 0%,var(--surface2) 100%);border:1px solid var(--border);border-radius:var(--r);padding:1.1rem;display:flex;flex-direction:column;height:100%;position:relative;overflow:hidden;transition:transform .18s,border-color .18s,box-shadow .18s}
.card-anken::before{content:"";position:absolute;inset:0 0 auto 0;height:3px;background:linear-gradient(90deg,var(--primary),var(--accent));opacity:0;transition:opacity .2s}
.card-anken:hover{transform:translateY(-3px);border-color:var(--border-s);box-shadow:0 14px 38px rgba(0,0,0,.48)}
.card-anken:hover::before{opacity:1}
.bdg{display:inline-block;font-size:.63rem;font-weight:800;padding:.16rem .48rem;border-radius:5px;letter-spacing:.03em}
.bdg-new{background:rgba(16,185,129,.16);color:#34d399}
.bdg-hot{background:rgba(239,68,68,.16);color:#fb7185}
.bdg-remote{background:rgba(34,211,238,.16);color:var(--primary-lt)}
.bdg-onsite{background:rgba(245,158,11,.16);color:#fbbf24}
.bdg-hybrid{background:rgba(167,139,250,.16);color:#c4b5fd}
.card-title{font-size:.93rem;font-weight:800;color:#fff;line-height:1.45}
.card-meta{font-size:.77rem;color:var(--dim);display:flex;flex-wrap:wrap;gap:.25rem .75rem}
.stag{font-size:.67rem;font-weight:700;padding:.14rem .48rem;border-radius:5px}
.stag-lang{background:rgba(6,182,212,.12);color:#67e8f9;border:1px solid rgba(6,182,212,.28)}
.stag-fw{background:rgba(167,139,250,.12);color:#c4b5fd;border:1px solid rgba(167,139,250,.28)}
.card-rate{font-size:1.22rem;font-weight:900;color:#fff}
.card-rate small{font-size:.74rem;color:var(--muted);font-weight:500}
.card-annual{font-size:.82rem;font-weight:700;color:var(--accent);margin-left:.4rem}
.site-badge{font-size:.62rem;font-weight:700;color:var(--muted);border:1px solid var(--border);padding:.1rem .42rem;border-radius:5px}
.btn-apply{display:inline-flex;align-items:center;gap:.28rem;padding:.52rem .88rem;border-radius:8px;font-size:.78rem;font-weight:800;background:var(--primary-glow);color:var(--primary-lt);border:1px solid rgba(34,211,238,.35);transition:all .15s}
.btn-apply:hover{background:var(--primary);color:#0b1220;border-color:transparent}

/* Empty */
.empty-state{text-align:center;padding:3.5rem 1rem;background:var(--surface);border:1px dashed var(--border-s);border-radius:var(--r);color:var(--dim)}
.empty-state strong{display:block;color:#fff;font-size:1.05rem;margin-bottom:.4rem}

/* External Sites */
.ext-section{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:1.5rem}
.ext-title{font-size:1.05rem;font-weight:800;color:#fff}
.ext-sub{font-size:.82rem;color:var(--dim);line-height:1.65}
.ext-card{display:block;height:100%;background:var(--surface2);border:1px solid var(--border);border-radius:11px;padding:.9rem 1rem;transition:border-color .15s,transform .15s,box-shadow .15s}
.ext-card:hover{border-color:var(--border-s);transform:translateY(-2px);box-shadow:0 8px 22px rgba(0,0,0,.3);color:inherit}
.ext-card-name{font-size:.92rem;font-weight:800;color:#fff}
.ext-tag{font-size:.62rem;font-weight:800;padding:.1rem .42rem;border-radius:5px}
.t-fl{background:rgba(6,182,212,.15);color:var(--primary-lt)}
.t-sei{background:rgba(16,185,129,.15);color:#34d399}
.t-side{background:rgba(245,158,11,.15);color:#fbbf24}
.t-gen{background:rgba(167,139,250,.15);color:#c4b5fd}
.ext-desc{font-size:.78rem;color:var(--dim);margin:.28rem 0;line-height:1.55}
.ext-cta{font-size:.76rem;font-weight:700;color:var(--primary-lt);display:inline-flex;align-items:center;gap:.22rem}

/* Footer */
.site-footer{border-top:1px solid var(--border);padding:1.6rem 1rem;text-align:center;color:var(--muted);font-size:.76rem;line-height:1.9}
.site-footer a{color:var(--dim)}
.site-footer a:hover{color:#fff}
</style>
</head>
<body>

{{-- ═══ HEADER ═══════════════════════════════════════════════════ --}}
<header class="site-header">
  <div class="container-xl">
    <div class="d-flex align-items-center justify-content-between py-2 gap-2">
      <a href="{{ route('anken.index') }}" class="brand">
        <span class="brand-icon" aria-hidden="true">案</span>
        <span>
          <span class="d-block" style="font-size:.98rem;line-height:1.1">案件ボード</span>
          <span class="d-block" style="font-size:.58rem;letter-spacing:.12em;color:var(--muted);font-weight:600">ANKEN BOARD — Laravel {{ app()->version() }}</span>
        </span>
      </a>
      <nav class="d-flex align-items-center gap-1">
        <a href="#search" class="nav-a d-none d-md-inline">案件を探す</a>
        <a href="#ext"    class="nav-a d-none d-md-inline">外部求人サイト</a>
        <button class="btn-cta" onclick="document.getElementById('q').focus();document.getElementById('search').scrollIntoView({behavior:'smooth'})">案件を探す</button>
      </nav>
    </div>
  </div>
</header>

{{-- ═══ HERO ═══════════════════════════════════════════════════════ --}}
<section class="hero">
  <div class="container-xl px-3">
    <p style="font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--primary-lt);font-weight:700;margin-bottom:.6rem">IT案件・求人マッチング</p>
    <h1 class="hero-h1">スキルと希望条件から<br>あなたにぴったりの案件が見つかる。</h1>
    <p class="hero-sub">言語・フレームワーク・月額・勤務地で絞り込み。マッチした案件の外部サイトへ直接応募 ＋ 似た求人が見つかる外部サービスもまとめてご紹介。</p>
    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4" style="row-gap:.75rem">
      <div class="text-center"><span class="hero-stat-val">{{ count(\App\Http\Controllers\AnkenController::ANKEN) }}</span><span class="hero-stat-lbl d-block">掲載案件</span></div>
      <div class="text-center"><span class="hero-stat-val">{{ count(\App\Http\Controllers\AnkenController::EXT_SITES) }}</span><span class="hero-stat-lbl d-block">外部求人サイト</span></div>
      <div class="text-center"><span class="hero-stat-val">80%</span><span class="hero-stat-lbl d-block">リモート対応</span></div>
      <div class="text-center"><span class="hero-stat-val">¥60〜130万</span><span class="hero-stat-lbl d-block">月額レンジ</span></div>
    </div>
  </div>
</section>

{{-- ═══ SEARCH PANEL ════════════════════════════════════════════════ --}}
<section class="pb-3" id="search">
  <div class="container-xl px-3">
    <form method="GET" action="{{ route('anken.search') }}" class="search-panel" id="search-form">
      @csrf

      {{-- Row 1: Keyword / Role / Style --}}
      <div class="row g-2 mb-2">
        <div class="col-12 col-md-5">
          <label class="f-label" for="q">キーワード</label>
          <input class="form-control" id="q" name="q" type="search"
                 placeholder="例：React、フルスタック、AI、Go"
                 value="{{ $input['q'] }}">
        </div>
        <div class="col-6 col-md-4">
          <label class="f-label" for="role">職種カテゴリ</label>
          <select class="form-select" id="role" name="role">
            <option value="">すべての職種</option>
            @foreach($roles as $r)
              <option value="{{ $r }}" @selected($input['role'] === $r)>{{ $r }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-3">
          <label class="f-label" for="style">働き方</label>
          <select class="form-select" id="style" name="style">
            <option value="">すべて</option>
            <option value="remote"  @selected($input['style']==='remote')>フルリモート</option>
            <option value="hybrid"  @selected($input['style']==='hybrid')>ハイブリッド</option>
            <option value="onsite"  @selected($input['style']==='onsite')>常駐</option>
          </select>
        </div>
      </div>

      {{-- Row 2: Language chips --}}
      <div class="mb-2">
        <label class="f-label">希望プログラミング言語 <span style="color:var(--muted);font-weight:500;text-transform:none;letter-spacing:0">（複数選択可・OR 検索）</span></label>
        <div class="chip-wrap" id="lang-chips">
          @foreach($langs as $lang)
            <button type="button"
              class="chip {{ in_array($lang, $input['langs']) ? 'on' : '' }}"
              data-name="{{ $lang }}"
              data-input-name="langs[]"
              onclick="toggleChip(this)">{{ $lang }}</button>
          @endforeach
        </div>
        <span class="chip-more" onclick="toggleChips('lang')">▼ すべて表示</span>
      </div>

      {{-- Row 3: Framework chips --}}
      <div class="mb-3">
        <label class="f-label">希望フレームワーク・ツール <span style="color:var(--muted);font-weight:500;text-transform:none;letter-spacing:0">（複数選択可・OR 検索）</span></label>
        <div class="chip-wrap" id="fw-chips">
          @foreach($frameworks as $cat => $fwList)
            <span class="chip" style="background:rgba(167,139,250,.08);color:var(--accent);border-color:rgba(167,139,250,.25);cursor:default;font-size:.68rem">{{ $cat }}</span>
            @foreach($fwList as $fw)
              <button type="button"
                class="chip {{ in_array($fw, $input['fws']) ? 'on' : '' }}"
                data-name="{{ $fw }}"
                data-input-name="fws[]"
                onclick="toggleChip(this)">{{ $fw }}</button>
            @endforeach
          @endforeach
        </div>
        <span class="chip-more" onclick="toggleChips('fw')">▼ すべて表示</span>
      </div>

      {{-- Hidden inputs for selected langs/fws --}}
      <div id="hidden-inputs">
        @foreach($input['langs'] as $l)
          <input type="hidden" name="langs[]" value="{{ $l }}">
        @endforeach
        @foreach($input['fws'] as $f)
          <input type="hidden" name="fws[]" value="{{ $f }}">
        @endforeach
      </div>

      {{-- Row 4: Rate / Annual salary --}}
      <div class="row g-2 mb-2">
        <div class="col-12 col-md-7">
          <label class="f-label" for="rate-slider">希望月額（下限）</label>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <input type="range" class="form-range flex-grow-1" id="rate-slider"
                   min="0" max="200" step="5" value="{{ $input['min_rate'] }}"
                   style="min-width:120px"
                   oninput="syncRate(this.value)">
            <input type="number" class="form-control text-center" id="rate-num" name="min_rate"
                   min="0" max="200" step="5" value="{{ $input['min_rate'] }}"
                   style="width:70px;flex-shrink:0"
                   oninput="syncRateFromNum(this.value)">
            <span style="color:var(--dim);font-size:.82rem;white-space:nowrap">万円/月〜</span>
          </div>
          <div class="d-flex justify-content-between mt-1" style="font-size:.68rem;color:var(--muted)">
            <span>0（下限なし）</span><span>100万</span><span>200万</span>
          </div>
        </div>
        <div class="col-12 col-md-5">
          <label class="f-label" for="annual-num">希望年収（下限・月額と連動）</label>
          <div class="d-flex align-items-center gap-2">
            <input type="number" class="form-control" id="annual-num"
                   min="0" max="2400" step="60"
                   value="{{ $input['annual'] }}"
                   placeholder="0"
                   oninput="syncFromAnnual(this.value)">
            <span style="color:var(--dim);font-size:.82rem;white-space:nowrap">万円/年〜</span>
          </div>
          <div style="font-size:.68rem;color:var(--muted);margin-top:.3rem">月額 × 12 で自動換算（入力も可）</div>
        </div>
      </div>

      {{-- Row 5: Location --}}
      <div class="row g-2 mb-3 align-items-end">
        <div class="col-12 col-sm-4 col-md-3">
          <label class="f-label" for="pref">都道府県</label>
          <select class="form-select" id="pref" name="pref">
            @foreach($prefs as $p)
              <option value="{{ $p }}" @selected($input['pref']===$p)>
                {{ $p === '' ? '都道府県を選択' : $p }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-sm-4 col-md-3">
          <label class="f-label" for="city">市区町村</label>
          <input class="form-control" id="city" name="city" type="text"
                 placeholder="例：渋谷区" value="{{ $input['city'] }}">
        </div>
        <div class="col-12 col-sm-4 col-md-3">
          <label class="f-label" for="station">最寄り駅</label>
          <input class="form-control" id="station" name="station" type="text"
                 placeholder="例：渋谷、梅田" value="{{ $input['station'] }}">
        </div>
        <div class="col-12 col-md-3 d-flex align-items-center" style="padding-top:1.6rem">
          <div class="form-check m-0">
            <input class="form-check-input" type="checkbox" id="remote-ok" name="remote_ok"
                   value="1" @checked($input['remote_ok'])>
            <label class="form-check-label" for="remote-ok">リモート・ハイブリッドのみ</label>
          </div>
        </div>
      </div>

      {{-- Buttons --}}
      <div class="row g-2">
        <div class="col-8 col-sm-9 col-md-10">
          <button type="submit" class="btn-search">マッチする案件を探す</button>
        </div>
        <div class="col-4 col-sm-3 col-md-2">
          <a href="{{ route('anken.index') }}" class="btn-reset d-block text-center">リセット</a>
        </div>
      </div>

    </form>
  </div>
</section>

{{-- ═══ RESULTS ══════════════════════════════════════════════════════ --}}
<main class="py-3" id="results">
  <div class="container-xl px-3">

    {{-- Google Custom Search（純粋PHP版と同じマッチング結果） --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <h2 style="font-size:1.08rem;font-weight:800;color:#fff;margin:0">
        🔍 マッチング結果
        @if(count($google_results) > 0)
          <span style="font-size:.82rem;color:#fff;font-weight:600">（{{ count($google_results) }}件）</span>
        @elseif($google_cse_configured)
          <span style="font-size:.82rem;color:#fff;font-weight:600">（0件）</span>
        @endif
      </h2>
      @if(count($google_results) > 0)
        <span style="font-size:.72rem;color:#dde6f5">検索: 「{{ \Illuminate\Support\Str::limit($search_query, 40) }}」</span>
      @endif
    </div>

    @if(count($google_results) > 0)
      <div class="row g-3 mb-4">
        @foreach($google_results as $i => $gr)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card-anken">
              <div class="d-flex align-items-center justify-content-between mb-2" style="gap:.4rem">
                <div style="display:flex;align-items:center;gap:.35rem;overflow:hidden">
                  <span style="font-size:.58rem;font-weight:800;color:var(--primary-lt);letter-spacing:.06em;background:var(--primary-glow);padding:.1rem .42rem;border-radius:4px;flex-shrink:0">Google</span>
                  <span style="font-size:.65rem;color:#dde6f5;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $gr['domain'] }}</span>
                </div>
                <span style="font-size:.62rem;color:#dde6f5;flex-shrink:0">#{{ $i + 1 }}</span>
              </div>
              <a href="{{ $gr['url'] }}" target="_blank" rel="noopener noreferrer" style="text-decoration:none">
                <div class="card-title mb-2" style="color:#fff">{{ \Illuminate\Support\Str::limit($gr['title'], 60) }}</div>
              </a>
              <div style="font-size:.78rem;color:#dde6f5;line-height:1.65;margin-bottom:.9rem">
                {{ \Illuminate\Support\Str::limit($gr['snippet'], 130) }}
              </div>
              <div class="mt-auto">
                <a href="{{ $gr['url'] }}" target="_blank" rel="noopener noreferrer" class="btn-apply" style="width:100%;justify-content:center">
                  詳細・応募はこちら
                  <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div style="font-size:.66rem;color:#dde6f5;margin-top:.7rem;text-align:right;margin-bottom:1.5rem">
        Powered by Google Custom Search API · 結果は最大7日間キャッシュ · 各リンク先で詳細・応募をご確認ください
      </div>
    @elseif(!$google_cse_configured)
      <div class="empty-state" style="border-style:dashed;text-align:left;padding:1.2rem 1.4rem;margin-bottom:1.5rem">
        <strong style="font-size:1rem;display:block;margin-bottom:.5rem">🔍 Google検索マッチングを有効にしてください</strong>
        <p style="font-size:.82rem;line-height:1.8;margin:0">
          <code style="color:var(--primary-lt);font-size:.78rem">GOOGLE_CSE_KEY</code> と
          <code style="color:var(--primary-lt);font-size:.78rem">GOOGLE_CSE_CX</code> を .env に設定すると、実際の案件・求人ページを一覧表示します（純粋PHP版と同じAPI）。
        </p>
      </div>
    @else
      <div class="empty-state" style="margin-bottom:1.5rem"><strong>検索結果が見つかりませんでした</strong>キーワードや条件を変えてもう一度お試しください。</div>
    @endif

    {{-- 参考案件（サンプルデータ） --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <h3 style="font-size:.92rem;font-weight:800;color:#dde6f5;margin:0">
        📋 参考案件（サンプルデータ）
        <span style="font-size:.78rem;font-weight:600;color:#dde6f5">（{{ $anken->count() }}件）</span>
      </h3>
      <form method="GET" action="{{ route('anken.search') }}" id="sort-form">
        {{-- 現在の検索条件を hidden で保持 --}}
        @if($input['q'])<input type="hidden" name="q" value="{{ $input['q'] }}">@endif
        @if($input['role'])<input type="hidden" name="role" value="{{ $input['role'] }}">@endif
        @if($input['style'])<input type="hidden" name="style" value="{{ $input['style'] }}">@endif
        @foreach($input['langs'] as $l)<input type="hidden" name="langs[]" value="{{ $l }}">@endforeach
        @foreach($input['fws']   as $f)<input type="hidden" name="fws[]"   value="{{ $f }}">@endforeach
        @if($input['min_rate'])<input type="hidden" name="min_rate" value="{{ $input['min_rate'] }}">@endif
        @if($input['pref'])<input type="hidden" name="pref" value="{{ $input['pref'] }}">@endif
        @if($input['city'])<input type="hidden" name="city" value="{{ $input['city'] }}">@endif
        @if($input['station'])<input type="hidden" name="station" value="{{ $input['station'] }}">@endif
        @if($input['remote_ok'])<input type="hidden" name="remote_ok" value="1">@endif
        <select class="sort-sel" name="sort" onchange="this.form.submit()">
          <option value="new"       @selected($input['sort']==='new')>新着順</option>
          <option value="rate-desc" @selected($input['sort']==='rate-desc')>月額が高い順</option>
          <option value="rate-asc"  @selected($input['sort']==='rate-asc')>月額が低い順</option>
          <option value="score"     @selected($input['sort']==='score')>マッチ度順</option>
        </select>
      </form>
    </div>

    @if($anken->isEmpty())
      <div class="empty-state">
        <strong>該当する案件が見つかりませんでした</strong>
        条件を変えてもう一度お試しください。
      </div>
    @else
      <div class="row g-3">
        @foreach($anken as $a)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card-anken">
              {{-- Badges + posted date --}}
              <div class="d-flex justify-content-between align-items-start mb-1" style="gap:.35rem">
                <div>
                  @if($a['posted'] <= 1)<span class="bdg bdg-new me-1">NEW</span>@endif
                  @if($a['hot'])       <span class="bdg bdg-hot me-1">注目</span>@endif
                  @if($a['style']==='remote') <span class="bdg bdg-remote me-1">リモート</span>
                  @elseif($a['style']==='hybrid') <span class="bdg bdg-hybrid me-1">ハイブリッド</span>
                  @elseif($a['style']==='onsite') <span class="bdg bdg-onsite me-1">常駐</span>
                  @endif
                </div>
                <span style="font-size:.7rem;color:var(--muted);flex-shrink:0">
                  @if($a['posted']===0)本日@elseif($a['posted']===1)昨日@else{{ $a['posted'] }}日前@endif
                </span>
              </div>

              {{-- Title --}}
              <div class="card-title mb-2">{{ $a['title'] }}</div>

              {{-- Meta --}}
              <div class="card-meta mb-2">
                <span>📍 {{ $a['location'] }}</span>
                <span>⏱ {{ $a['duration'] }}</span>
                <span style="color:var(--muted)">{{ $a['role'] }}</span>
              </div>

              {{-- Language / FW tags --}}
              <div class="d-flex flex-wrap gap-1 mb-2">
                @foreach($a['langs'] as $lang)<span class="stag stag-lang">{{ $lang }}</span>@endforeach
                @foreach($a['fws']   as $fw)  <span class="stag stag-fw">{{ $fw }}</span>@endforeach
              </div>

              {{-- Rate --}}
              <div class="mt-auto">
                <div class="mb-2">
                  <span class="card-rate">¥{{ $a['rate'] }}<small> 万円/月</small></span>
                  <span class="card-annual">年収 約¥{{ $a['rate'] * 12 }}万</span>
                </div>

                {{-- Apply button --}}
                <div class="d-flex justify-content-between align-items-center">
                  <span class="site-badge">{{ $a['site_name'] }}</span>
                  <a href="{{ $a['apply_url'] }}"
                     target="_blank" rel="noopener noreferrer"
                     class="btn-apply">
                    このサイトで応募
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    {{-- ═══ EXTERNAL SITES ══════════════════════════════════════ --}}
    <div class="mt-4" id="ext">
      <div class="ext-section">
        <div class="ext-title mb-1">
          🔍 この条件でもっと探す — 外部求人・案件サイト
        </div>
        <p class="ext-sub mb-3">
          @if($input['q'] || $input['langs'] || $input['fws'])
            「{{ implode(' / ', array_filter(array_merge([$input['q']], $input['langs'], $input['fws']))) }}」に関連する案件を外部サービスでも探せます。
          @else
            現在の検索条件に関連する案件を外部サービスでも探せます。
          @endif
          各サイトの検索結果ページへ直接リンクします（別タブで開きます）。
        </p>
        <div class="row g-2">
          @foreach($ext_sites as $site)
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
              <a href="{{ $site['url'] }}{{ $kw }}"
                 target="_blank" rel="noopener noreferrer"
                 class="ext-card">
                <div class="d-flex align-items-center gap-1 mb-1 flex-wrap">
                  <span class="ext-card-name">{{ $site['name'] }}</span>
                  <span class="ext-tag t-{{ $site['type'] }}">{{ $site['tag'] }}</span>
                </div>
                <div class="ext-desc">{{ $site['desc'] }}</div>
                <div class="ext-cta">
                  このサイトで探す
                  <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>
</main>

<footer class="site-footer">
  <div>© 2026 案件ボード — ITエンジニア案件・求人マッチング（Laravel {{ app()->version() }}）</div>
  <div class="mt-1">
    <a href="#search">案件を探す</a> ·
    <a href="#ext">外部求人サイト</a>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* 月額 ↔ 年収 双方向連動 */
function syncRate(v) {
  document.getElementById('rate-num').value    = v;
  document.getElementById('annual-num').value  = v * 12;
}
function syncRateFromNum(v) {
  const n = parseInt(v)||0;
  document.getElementById('rate-slider').value = n;
  document.getElementById('annual-num').value  = n * 12;
}
function syncFromAnnual(v) {
  const m = Math.round((parseInt(v)||0) / 12);
  document.getElementById('rate-slider').value = m;
  document.getElementById('rate-num').value    = m;
}

/* チップ選択 ↔ hidden input 同期 */
function toggleChip(btn) {
  btn.classList.toggle('on');
  const name  = btn.dataset.name;
  const iname = btn.dataset.inputName;
  const wrap  = document.getElementById('hidden-inputs');

  if (btn.classList.contains('on')) {
    const inp = document.createElement('input');
    inp.type  = 'hidden';
    inp.name  = iname;
    inp.value = name;
    inp.dataset.chip = name;
    wrap.appendChild(inp);
  } else {
    const existing = wrap.querySelector(`input[data-chip="${CSS.escape(name)}"]`);
    if (existing) existing.remove();
  }
}

/* チップ欄の展開/折り畳み */
function toggleChips(type) {
  const wrap = document.getElementById(type + '-chips');
  const more = wrap.nextElementSibling;
  const open = wrap.classList.toggle('open');
  more.textContent = open ? '▲ 閉じる' : '▼ すべて表示';
}
</script>
</body>
</html>
