# Rust / Rust+Poem で作る「HTTPS運用GUI」提案

## 背景

「かんたんKUSANAGI」のように、GUIからHTTPSを簡単に有効化しつつ、ドメイン・サブドメイン管理を効率化したい要件です。  
ただしPHP中心ではなく、RustまたはRust+Poemで構築したいという前提です。

## 推奨アーキテクチャ

- **Web GUI/API**: Rust + Poem
- **HTTPS終端/証明書自動更新**: Caddy（推奨）またはNginx + Certbot
- **認証**: `login_id + email + OTP`
- **永続化**: PostgreSQL（将来）
- **運用基盤**: systemd + journalctl + UFW

## Rust単体 vs Rust+Poem

### Rust単体（自前HTTP実装寄り）

**メリット**
- 依存を最小化できる
- 学習目的・組み込み用途では柔軟

**デメリット**
- 認証/JSON/API設計の実装コストが高い
- 保守時に独自実装の負債が増えやすい
- 機能追加速度が落ちやすい

### Rust + Poem（推奨）

**メリット**
- APIとミドルウェアの実装が早い
- 型安全に運用機能を追加しやすい
- テストしやすく、将来のRBACや監査ログ拡張に向く

**デメリット**
- フレームワーク知識が必要
- 依存ライブラリ更新の追従が必要

## このPoCの到達点

`rust-https-gui` で以下を実装済み:

1. OTP発行API（ID+email）
2. OTP検証API（セッショントークン発行）
3. ドメイン管理API
4. サブドメイン追加/更新API
5. HTTPS有効化API（PoCでは状態管理）
6. 運用サマリAPI

## 現時点の制約（PoC）

- OTPはログ出力（メール送信未接続）
- インメモリ保存（再起動で消える）
- 実際のDNS操作・証明書操作は未接続

## 本番化の優先順

1. OTPメール配送の実装（SES/SendGrid）
2. PostgreSQL + sqlx で永続化
3. 監査ログ・操作履歴
4. Caddy API連携（証明書状態確認・再発行）
5. GUIフロント（操作画面）
6. RBAC（owner / operator / viewer）

## 相談しながら進めるための決定ポイント

次の3つを決めると実装を固められます。

1. **メール配送方式**: SES / SendGrid / SMTP のどれを使うか  
2. **DNS管理先**: ConoHa DNS APIを使うか、外部DNSを使うか  
3. **GUI技術**: Rustフロント（Leptos/Yew）か、JSフロント（Svelte/React）か

これが決まれば、PoCを本番向け構成へ段階的に移行できます。
