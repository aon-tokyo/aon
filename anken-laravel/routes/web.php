<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnkenController;

/*
|-------------------------------------------------------------------
| 案件マッチングサイト ルート定義
|-------------------------------------------------------------------
| ロリポップ！ハイスピード / スタンダード以上 (SSH + PHP 8.3 + MySQL)
| 公開ドキュメントルート = プロジェクト内の public/ ディレクトリ
| デプロイ後 URL 例: https://audiocafe.tokyo/aruaru/public/
|
| ※ シンボリックリンク設定で /aruaru/ 直下を public/ に向ければ
|   https://例えばドメイン/aruaru/ でも表示可能。
*/

Route::get('/',         [AnkenController::class, 'index'])->name('anken.index');
Route::get('/search',   [AnkenController::class, 'search'])->name('anken.search');
