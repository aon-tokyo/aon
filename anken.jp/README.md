# 案件ボード（anken.jp）

シンプルなフリーランス向けIT案件マッチングサイトのデモ。単一の `index.html` で動作する静的サイトです。

## 特徴

- 完全な静的HTML（外部ライブラリ不要、依存ゼロ）
- 日本語対応、レスポンシブデザイン
- キーワード／職種／働き方で絞り込み
- 人気スキルチップ（複数選択対応）
- 単価・新着での並び替え
- 応募モーダル + トースト通知

## 使い方

### ローカル確認

```bash
# ブラウザで直接開く（最も簡単）
open anken.jp/index.html

# もしくは簡易サーバーで起動（Python が入っている場合）
python3 -m http.server 8000 --directory anken.jp
# → http://localhost:8000/
```

> 上記の `python3 -m http.server` は **ローカル開発用**です。
> 本番サーバーで Python は **不要**です。

### 本番（共用レンタルサーバー）へのデプロイ

このサイトは **完全な静的HTML / CSS / JavaScript** で動作するため、
サーバー側の言語（PHP / Python / Perl など）は一切不要です。
そのため以下のような共用レンタルサーバーでも、**`index.html` を FTP でアップするだけ**で動きます。

- ✅ ロリポップ！（全プラン、Python 非対応プランでも動作）
- ✅ さくらのレンタルサーバ
- ✅ エックスサーバー
- ✅ ConoHa WING
- ✅ GitHub Pages / Cloudflare Pages / Netlify / Vercel など

#### ロリポップ！の場合の手順（例）

1. ロリポップ！ユーザー専用ページで FTP 情報を確認
2. FFFTP / FileZilla / 「ロリポップ！FTP（ブラウザ版）」で接続
3. 公開フォルダ（例：独自ドメイン用 `/anken.jp/`）に `index.html` をアップロード
4. `https://あなたのドメイン/` にアクセスして表示確認


## ファイル構成

```
anken.jp/
└── index.html   # 本体（HTML + CSS + JS + サンプルデータ）
```

## カスタマイズ

`index.html` 内の `ANKEN` 配列を編集することで、案件データを差し替えられます。

```js
const ANKEN = [
  { id, title, role, style, skills, rate, location, duration, posted, hot },
  ...
];
```

- `style` は `"remote"` / `"hybrid"` / `"onsite"`
- `rate` は月額（万円）
- `posted` は経過日数（0=本日）
- `hot:true` で「注目」バッジ表示
