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

```bash
# ブラウザで直接開く
open anken.jp/index.html

# もしくはローカルサーバーで起動
python3 -m http.server 8000 --directory anken.jp
# http://localhost:8000/
```

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
