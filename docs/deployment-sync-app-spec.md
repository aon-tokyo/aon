# デプロイ同期アプリ仕様書（ドラフト）

ローカル（または Cursor のワークスペース）のファイルを、**GitHub への push** と **レンタルサーバーへのアップロード**を、用途に応じて組み合わせられる CLI／デスクトップアプリ。

---

## 1. 目的

- 静的サイトやビルド成果物を、**手動 FTP クライアントに頼らず**反映する。
- **SFTP と FTP** を接続先の能力に合わせて切り替える。
- **GitHub のみ** / **レンタルサーバーのみ** / **両方同時**を、プロファイルまたは実行時オプションで切り替える。

---

## 2. 転送プロトコル（切り替え可能）

| モード | 説明 | 備考 |
|--------|------|------|
| **SFTP** | SSH 上の SFTP（推奨） | 鍵認証またはパスワード（設定で選択） |
| **FTP** | 従来 FTP | 平文のため FTPS（明示的 TLS）をオプションでサポート推奨 |

- プロファイルごとに `protocol: "sftp" | "ftp"` を指定。
- レンタルサーバー側が SFTP のみ／FTP のみの場合は、該当プロファイルで一方のみ使用。

---

## 3. デプロイ先モード（切り替え可能）

| モード | 動作 |
|--------|------|
| `github-only` | リモートへ `git push` のみ（ブランチ・リモート名は設定） |
| `server-only` | レンタルサーバーへ FTP/SFTP のみ（本仕様の「レンタルだけにアップ」） |
| `dual` | **同一操作**で GitHub に push **かつ** レンタルサーバーへアップロード（順序: 先に Git 成否を確認してからサーバー、または設定で逆順も可） |

- CLI 例: `--target github` | `--target server` | `--target both`
- 設定ファイル例: `deploy.target: "github-only" | "server-only" | "dual"`

---

## 4. 同期の範囲

- **パス**: ローカルルート（例: リポジトリルート）とリモートルート（例: `public_html/example.com/`）のマッピング。
- **含める**: 通常はビルド出力ディレクトリ or 静的ファイル一式（`index.html` 等）。
- **除外**: `.git`、`node_modules`、`.env`、`*~`、任意 glob（`.deployignore` 互換でも可）。

---

## 5. トリガー（実装フェーズで選択）

1. **手動**: コマンド一回実行（`deploy` / `sync`）。
2. **ファイル監視**（オプション）: 保存時に差分のみアップロード（サーバー向け）。Git はコミット単位で別操作とするか、自動 commit はオプションで無効デフォルト推奨。
3. **Git フック**（オプション）: `pre-push` でサーバー同期を追加（`dual` 時）。

※ 「自動アップデート」は **監視 + サーバー同期** と **push 後の CI** のどちらでも満たせるため、本アプリはまず **手動 CLI + 明確な target 切替**を必須、監視は任意機能とする。

---

## 6. GitHub 連携

- ローカルが **git リポジトリ**であること。
- `github-only` / `dual` で:
  - `git add` / `commit` / `push` を行うかは **設定**（「push のみ」「コミットメッセージ指定してから push」など）。
- **認証**: HTTPS は PAT、SSH は既存の `ssh-agent` 利用を推奨（トークンを設定ファイルに平文保存しない）。

---

## 7. レンタルサーバー連携

- **FTP**: ホスト、ユーザー、パスワード、パッシブモード、ルートパス。
- **SFTP**: ホスト、ポート（既定 22）、ユーザー、鍵パスまたはパスワード、リモートルート。
- **FTPS**（任意）: 明示的 TLS、証明書検証の緩和は開発用のみに限定。

---

## 8. 設定ファイル

- 単一ファイル例: `deploy.config.yaml` または `deploy.config.json`（リポジトリに **秘密をコミットしない**。`.gitignore` に `deploy.local.yaml` 等）。
- 複数環境: `profiles.default`, `profiles.production`, `profiles.staging`。

```yaml
# 例（秘密は deploy.local.yaml で上書き）
target: dual   # github-only | server-only | dual
git:
  remote: origin
  branch: main
  pushOnly: true
server:
  protocol: sftp   # ftp | sftp
  host: example.com
  port: 22
  user: "${DEPLOY_SFTP_USER}"
  remoteRoot: /home/user/public_html
paths:
  localRoot: .
  include:
    - "**/*.html"
    - "_shared/**"
  exclude:
    - ".git/**"
    - "node_modules/**"
```

---

## 9. セキュリティ

- パスワード・トークンは **環境変数**または OS 秘密ストレージ（keychain）参照。
- FTP は平文のため UI/ログに「本番は SFTP/FTPS 推奨」と明示。
- アップロード前に **dry-run**（一覧表示のみ）オプション。

---

## 10. 非機能要件

- クロスプラットフォーム（Windows / macOS / Linux）を CLI で優先。
- 失敗時: Git が失敗したらサーバーに行かない（`dual` のデフォルト）、または `--continue-on-git-failure` で上書き可能。

---

## 11. 将来拡張（任意）

- GUI（トレイ常駐、プロファイル切替）。
- GitHub Actions 用の **同じ設定スキーマ**を共有し、CI からも同じルールでデプロイ。

---

## 12. 用語

- **同時アップ**: 本仕様では `dual` モード（GitHub push + サーバーアップロード）。
- **レンタルだけ**: `server-only` モード。

---

*文書バージョン: 0.1 — 実装時にプロファイル名・CLI 名は製品に合わせて確定する。*
