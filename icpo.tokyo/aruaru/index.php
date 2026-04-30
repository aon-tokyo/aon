<?php
/**
 * 案件ボード — 純粋PHP版（Laravel不要）
 * ロリポップ！含むすべての共用レンタルサーバーで
 * このファイル1つをアップするだけで動作します。
 */

/* ═══════════════════════════════════════════════════════════
   定数・マスターデータ
═══════════════════════════════════════════════════════════ */

define('LANGS', [
    'JavaScript','TypeScript','Python','Go','PHP','Ruby','Java','Kotlin',
    'Swift','Rust','C#','C++','Scala','R','Dart','Elixir','Haskell','Lua','COBOL','VBA',
]);

define('FRAMEWORKS', [
    'フロントエンド' => [
        'React','Next.js','Vue.js','Nuxt.js','Angular','Svelte','SvelteKit',
        'Astro','Remix','Solid.js','HTMX',
    ],
    'バックエンド' => [
        'Express','Fastify','NestJS','Django','FastAPI','Flask',
        'Ruby on Rails','Laravel','Spring Boot','ASP.NET Core','Gin','Echo','Phoenix',
    ],
    'モバイル' => [
        'React Native','Flutter','SwiftUI','Jetpack Compose','Expo',
    ],
    'インフラ／ツール' => [
        'Docker','Kubernetes','Terraform','Ansible','AWS CDK',
        'GraphQL','REST API','dbt','Snowflake','LangChain',
    ],
]);

define('PREFS', [
    '','北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県',
    '茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県',
    '新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県',
    '静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県',
    '奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県',
    '徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県',
    '熊本県','大分県','宮崎県','鹿児島県','沖縄県',
]);

define('EXT_SITES', [
    ['name'=>'レバテックフリーランス','tag'=>'フリーランス','type'=>'fl',
     'desc'=>'案件数・単価ともに国内最大級のITフリーランス向けエージェント。高単価・長期案件が豊富。',
     'url'=>'https://freelance.levtech.jp/project/search/?keyword='],
    ['name'=>'ITプロパートナーズ','tag'=>'副業・フリーランス','type'=>'fl',
     'desc'=>'週2〜3日から参画できる副業・フリーランス案件に特化。スタートアップ系が豊富。',
     'url'=>'https://itpropartners.com/project/?keyword='],
    ['name'=>'Midworks','tag'=>'フリーランス','type'=>'fl',
     'desc'=>'フリーランスでも社会保険・各種保障が充実。正社員並みのサポートで安心して働ける。',
     'url'=>'https://midworks.com/projects/?keyword='],
    ['name'=>'クラウドテック','tag'=>'フリーランス','type'=>'fl',
     'desc'=>'クラウドワークスが運営するITフリーランス向けエージェント。多様な職種・単価帯。',
     'url'=>'https://crowdtech.jp/projects/search/?word='],
    ['name'=>'Findy Freelance','tag'=>'フリーランス','type'=>'fl',
     'desc'=>'GitHubスキルスコアで自動マッチング。エンジニア目線のフリーランス案件サービス。',
     'url'=>'https://findy-code.io/freelance/projects?keyword='],
    ['name'=>'Offers','tag'=>'副業・複業','type'=>'side',
     'desc'=>'副業・複業×開発案件のマッチング。スタートアップや成長企業の週1〜案件が充実。',
     'url'=>'https://offers.jp/jobs?keyword='],
    ['name'=>'Green','tag'=>'正社員転職','type'=>'sei',
     'desc'=>'IT・Web・ゲーム業界特化の転職サービス。正社員でキャリアアップしたい方向け。',
     'url'=>'https://www.green-japan.com/search?keyword='],
    ['name'=>'Wantedly','tag'=>'スタートアップ','type'=>'side',
     'desc'=>'「やりたいこと」でつながる採用サービス。スタートアップ・ベンチャーの求人が豊富。',
     'url'=>'https://www.wantedly.com/projects?query='],
    ['name'=>'Findy（転職）','tag'=>'エンジニア転職','type'=>'sei',
     'desc'=>'スキルスコアでスカウトが届くエンジニア特化の転職サービス。高年収求人多数。',
     'url'=>'https://findy-code.io/job-offers?search='],
    ['name'=>'Indeed Japan','tag'=>'総合求人','type'=>'gen',
     'desc'=>'国内最大級の求人検索エンジン。正社員・契約社員・フリーランスを幅広く検索可能。',
     'url'=>'https://jp.indeed.com/jobs?q='],
]);

define('ANKEN', [
    /* フロントエンド */
    ['id'=>1,'title'=>'大規模ECサイト フロントエンド開発（React / Next.js 15）',
     'role'=>'フロントエンド','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'6ヶ月〜','rate'=>85,'posted'=>0,'hot'=>true,
     'langs'=>['TypeScript','JavaScript'],'fws'=>['React','Next.js','GraphQL'],
     'site_name'=>'レバテックフリーランス',
     'apply_url'=>'https://freelance.levtech.jp/project/search/?keyword=React+Next.js+TypeScript'],

    ['id'=>2,'title'=>'動画配信PF フロントエンド刷新（Vue 3 / Nuxt 3）',
     'role'=>'フロントエンド','style'=>'hybrid','prefecture'=>'東京都','city'=>'渋谷区','station'=>'渋谷',
     'location'=>'東京・週2出社','duration'=>'6ヶ月','rate'=>78,'posted'=>2,'hot'=>false,
     'langs'=>['TypeScript','JavaScript'],'fws'=>['Vue.js','Nuxt.js'],
     'site_name'=>'ITプロパートナーズ',
     'apply_url'=>'https://itpropartners.com/project/?keyword=Vue+Nuxt'],

    ['id'=>3,'title'=>'SaaS 管理画面リニューアル（Angular 18）',
     'role'=>'フロントエンド','style'=>'onsite','prefecture'=>'神奈川県','city'=>'横浜市','station'=>'横浜',
     'location'=>'横浜・常駐','duration'=>'4ヶ月','rate'=>72,'posted'=>5,'hot'=>false,
     'langs'=>['TypeScript'],'fws'=>['Angular'],
     'site_name'=>'クラウドテック',
     'apply_url'=>'https://crowdtech.jp/projects/search/?word=Angular+TypeScript'],

    ['id'=>4,'title'=>'金融ダッシュボード 新規開発（Svelte / SvelteKit）',
     'role'=>'フロントエンド','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'3ヶ月〜','rate'=>80,'posted'=>1,'hot'=>false,
     'langs'=>['TypeScript','JavaScript'],'fws'=>['Svelte','SvelteKit'],
     'site_name'=>'Findy Freelance',
     'apply_url'=>'https://findy-code.io/freelance/projects?keyword=Svelte'],

    ['id'=>5,'title'=>'コンテンツサイト新規構築（Astro / React）',
     'role'=>'フロントエンド','style'=>'remote','prefecture'=>'大阪府','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'2ヶ月','rate'=>65,'posted'=>7,'hot'=>false,
     'langs'=>['TypeScript','JavaScript'],'fws'=>['Astro','React'],
     'site_name'=>'Midworks',
     'apply_url'=>'https://midworks.com/projects/?keyword=Astro+React'],

    ['id'=>6,'title'=>'Rust + WebAssembly 高性能ブラウザアプリ開発',
     'role'=>'フロントエンド','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'4ヶ月','rate'=>102,'posted'=>8,'hot'=>true,
     'langs'=>['Rust','JavaScript'],'fws'=>['React'],
     'site_name'=>'Findy Freelance',
     'apply_url'=>'https://findy-code.io/freelance/projects?keyword=Rust+WebAssembly'],

    /* バックエンド */
    ['id'=>7,'title'=>'決済API 設計・開発（Go / Gin）',
     'role'=>'バックエンド','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'長期','rate'=>95,'posted'=>1,'hot'=>true,
     'langs'=>['Go'],'fws'=>['Gin','Docker'],
     'site_name'=>'レバテックフリーランス',
     'apply_url'=>'https://freelance.levtech.jp/project/search/?keyword=Go+Gin+API'],

    ['id'=>8,'title'=>'Go / Echo マイクロサービス API 開発',
     'role'=>'バックエンド','style'=>'remote','prefecture'=>'大阪府','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'長期','rate'=>93,'posted'=>0,'hot'=>false,
     'langs'=>['Go'],'fws'=>['Echo','Docker','Kubernetes'],
     'site_name'=>'ITプロパートナーズ',
     'apply_url'=>'https://itpropartners.com/project/?keyword=Go+Echo+マイクロサービス'],

    ['id'=>9,'title'=>'ヘルスケアSaaS API 構築（Python / FastAPI）',
     'role'=>'バックエンド','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'6ヶ月〜','rate'=>88,'posted'=>3,'hot'=>true,
     'langs'=>['Python'],'fws'=>['FastAPI','Docker'],
     'site_name'=>'Midworks',
     'apply_url'=>'https://midworks.com/projects/?keyword=Python+FastAPI'],

    ['id'=>10,'title'=>'Django REST API + PostgreSQL 設計・開発',
     'role'=>'バックエンド','style'=>'remote','prefecture'=>'福岡県','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'3ヶ月','rate'=>77,'posted'=>10,'hot'=>false,
     'langs'=>['Python'],'fws'=>['Django','REST API'],
     'site_name'=>'ITプロパートナーズ',
     'apply_url'=>'https://itpropartners.com/project/?keyword=Django+Python+API'],

    ['id'=>11,'title'=>'ECバックエンド 機能追加（PHP / Laravel 11）',
     'role'=>'バックエンド','style'=>'hybrid','prefecture'=>'愛知県','city'=>'名古屋市','station'=>'名古屋',
     'location'=>'名古屋・週3出社','duration'=>'6ヶ月','rate'=>68,'posted'=>4,'hot'=>false,
     'langs'=>['PHP'],'fws'=>['Laravel','REST API'],
     'site_name'=>'クラウドテック',
     'apply_url'=>'https://crowdtech.jp/projects/search/?word=PHP+Laravel'],

    ['id'=>12,'title'=>'スタートアップ自社PF 開発（Ruby on Rails）',
     'role'=>'バックエンド','style'=>'hybrid','prefecture'=>'東京都','city'=>'渋谷区','station'=>'渋谷',
     'location'=>'渋谷・週2出社','duration'=>'長期','rate'=>75,'posted'=>8,'hot'=>false,
     'langs'=>['Ruby'],'fws'=>['Ruby on Rails','GraphQL'],
     'site_name'=>'Wantedly',
     'apply_url'=>'https://www.wantedly.com/projects?query=Ruby+Rails'],

    ['id'=>13,'title'=>'ERPシステム API 開発（Java / Spring Boot）',
     'role'=>'バックエンド','style'=>'onsite','prefecture'=>'東京都','city'=>'千代田区','station'=>'大手町',
     'location'=>'大手町・常駐','duration'=>'長期','rate'=>90,'posted'=>6,'hot'=>false,
     'langs'=>['Java'],'fws'=>['Spring Boot','Docker'],
     'site_name'=>'Indeed Japan',
     'apply_url'=>'https://jp.indeed.com/jobs?q=Java+Spring+Boot+フリーランス'],

    ['id'=>14,'title'=>'チャットSaaS バックエンド（Node.js / NestJS）',
     'role'=>'バックエンド','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'4ヶ月〜','rate'=>82,'posted'=>2,'hot'=>false,
     'langs'=>['TypeScript'],'fws'=>['NestJS','Fastify','Docker'],
     'site_name'=>'Findy Freelance',
     'apply_url'=>'https://findy-code.io/freelance/projects?keyword=NestJS+Node.js'],

    ['id'=>15,'title'=>'Elixir / Phoenix リアルタイム通信基盤',
     'role'=>'バックエンド','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'3ヶ月','rate'=>88,'posted'=>15,'hot'=>false,
     'langs'=>['Elixir'],'fws'=>['Phoenix'],
     'site_name'=>'Wantedly',
     'apply_url'=>'https://www.wantedly.com/projects?query=Elixir+Phoenix'],

    /* フルスタック */
    ['id'=>16,'title'=>'スタートアップ CTO候補（Next.js + FastAPI）',
     'role'=>'フルスタック','style'=>'hybrid','prefecture'=>'東京都','city'=>'港区','station'=>'六本木',
     'location'=>'東京・週3出社','duration'=>'長期','rate'=>130,'posted'=>0,'hot'=>true,
     'langs'=>['TypeScript','Python'],'fws'=>['Next.js','FastAPI','Docker'],
     'site_name'=>'Green',
     'apply_url'=>'https://www.green-japan.com/search?keyword=CTO+フルスタック+Next.js'],

    ['id'=>17,'title'=>'BtoB SaaS フルスタック開発（React + Rails）',
     'role'=>'フルスタック','style'=>'hybrid','prefecture'=>'大阪府','city'=>'大阪市','station'=>'梅田',
     'location'=>'大阪・週2出社','duration'=>'6ヶ月','rate'=>85,'posted'=>3,'hot'=>false,
     'langs'=>['TypeScript','Ruby'],'fws'=>['React','Ruby on Rails'],
     'site_name'=>'Offers',
     'apply_url'=>'https://offers.jp/jobs?keyword=フルスタック+React+Rails'],

    ['id'=>18,'title'=>'社内ツール内製開発（Vue 3 + Laravel）',
     'role'=>'フルスタック','style'=>'onsite','prefecture'=>'埼玉県','city'=>'さいたま市','station'=>'大宮',
     'location'=>'大宮・常駐','duration'=>'3ヶ月','rate'=>70,'posted'=>14,'hot'=>false,
     'langs'=>['JavaScript','PHP'],'fws'=>['Vue.js','Laravel'],
     'site_name'=>'クラウドテック',
     'apply_url'=>'https://crowdtech.jp/projects/search/?word=Vue+Laravel+フルスタック'],

    /* モバイル */
    ['id'=>19,'title'=>'iOS ショッピングアプリ（Swift / SwiftUI）',
     'role'=>'モバイル','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'4ヶ月','rate'=>88,'posted'=>2,'hot'=>false,
     'langs'=>['Swift'],'fws'=>['SwiftUI'],
     'site_name'=>'ITプロパートナーズ',
     'apply_url'=>'https://itpropartners.com/project/?keyword=Swift+SwiftUI+iOS'],

    ['id'=>20,'title'=>'Android アプリ刷新（Kotlin / Jetpack Compose）',
     'role'=>'モバイル','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'5ヶ月','rate'=>82,'posted'=>5,'hot'=>true,
     'langs'=>['Kotlin'],'fws'=>['Jetpack Compose'],
     'site_name'=>'クラウドテック',
     'apply_url'=>'https://crowdtech.jp/projects/search/?word=Kotlin+Jetpack+Compose+Android'],

    ['id'=>21,'title'=>'クロスプラットフォームアプリ（Flutter / Dart）',
     'role'=>'モバイル','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'6ヶ月','rate'=>78,'posted'=>1,'hot'=>false,
     'langs'=>['Dart'],'fws'=>['Flutter','Expo'],
     'site_name'=>'Midworks',
     'apply_url'=>'https://midworks.com/projects/?keyword=Flutter+Dart'],

    ['id'=>22,'title'=>'医療アプリ React Native 開発（iOS/Android 両対応）',
     'role'=>'モバイル','style'=>'hybrid','prefecture'=>'東京都','city'=>'新宿区','station'=>'新宿',
     'location'=>'新宿・週2出社','duration'=>'長期','rate'=>83,'posted'=>9,'hot'=>false,
     'langs'=>['TypeScript'],'fws'=>['React Native','Expo'],
     'site_name'=>'Findy Freelance',
     'apply_url'=>'https://findy-code.io/freelance/projects?keyword=React+Native+モバイル'],

    /* インフラ / SRE */
    ['id'=>23,'title'=>'AWS クラウド移行 SRE（Terraform / Kubernetes）',
     'role'=>'インフラ／SRE','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'6ヶ月〜','rate'=>105,'posted'=>1,'hot'=>true,
     'langs'=>['Go','Python'],'fws'=>['Kubernetes','Terraform','AWS CDK'],
     'site_name'=>'レバテックフリーランス',
     'apply_url'=>'https://freelance.levtech.jp/project/search/?keyword=AWS+Terraform+Kubernetes+SRE'],

    ['id'=>24,'title'=>'MLOps 基盤構築（Kubernetes / Airflow / Python）',
     'role'=>'インフラ／SRE','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'4ヶ月','rate'=>98,'posted'=>4,'hot'=>false,
     'langs'=>['Python','Go'],'fws'=>['Kubernetes','Docker','Ansible'],
     'site_name'=>'Midworks',
     'apply_url'=>'https://midworks.com/projects/?keyword=MLOps+Kubernetes+Python'],

    ['id'=>25,'title'=>'大手通信 インフラ設計・DevOps 推進',
     'role'=>'インフラ／SRE','style'=>'onsite','prefecture'=>'東京都','city'=>'港区','station'=>'品川',
     'location'=>'品川・常駐','duration'=>'長期','rate'=>95,'posted'=>7,'hot'=>false,
     'langs'=>['Python'],'fws'=>['Ansible','Terraform','Docker'],
     'site_name'=>'ITプロパートナーズ',
     'apply_url'=>'https://itpropartners.com/project/?keyword=DevOps+インフラ+Terraform'],

    /* データ / AI・ML */
    ['id'=>26,'title'=>'生成AI チャットボット開発（LangChain / FastAPI）',
     'role'=>'データ／AI・ML','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'3ヶ月','rate'=>110,'posted'=>0,'hot'=>true,
     'langs'=>['Python'],'fws'=>['FastAPI','LangChain','Docker'],
     'site_name'=>'Offers',
     'apply_url'=>'https://offers.jp/jobs?keyword=LangChain+生成AI+Python+FastAPI'],

    ['id'=>27,'title'=>'データ基盤構築（dbt / Snowflake / Python）',
     'role'=>'データ／AI・ML','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'6ヶ月','rate'=>92,'posted'=>3,'hot'=>false,
     'langs'=>['Python','R'],'fws'=>['dbt','Snowflake'],
     'site_name'=>'Findy Freelance',
     'apply_url'=>'https://findy-code.io/freelance/projects?keyword=dbt+Snowflake+データエンジニア'],

    ['id'=>28,'title'=>'推薦システム ML エンジニア（Python / PyTorch）',
     'role'=>'データ／AI・ML','style'=>'remote','prefecture'=>'東京都','city'=>'','station'=>'',
     'location'=>'フルリモート','duration'=>'長期','rate'=>115,'posted'=>11,'hot'=>true,
     'langs'=>['Python'],'fws'=>['FastAPI','Docker'],
     'site_name'=>'Green',
     'apply_url'=>'https://www.green-japan.com/search?keyword=機械学習+MLエンジニア+Python'],

    ['id'=>29,'title'=>'Scala / Spark ビッグデータ処理最適化',
     'role'=>'データ／AI・ML','style'=>'hybrid','prefecture'=>'東京都','city'=>'品川区','station'=>'品川',
     'location'=>'東京・週1出社','duration'=>'3ヶ月','rate'=>100,'posted'=>6,'hot'=>false,
     'langs'=>['Scala','Python'],'fws'=>['Docker'],
     'site_name'=>'Indeed Japan',
     'apply_url'=>'https://jp.indeed.com/jobs?q=Scala+Spark+データエンジニア'],

    /* PM / PMO */
    ['id'=>30,'title'=>'大手金融 ITプロジェクト PM（アジャイル推進）',
     'role'=>'PM／PMO','style'=>'onsite','prefecture'=>'東京都','city'=>'千代田区','station'=>'大手町',
     'location'=>'大手町・常駐','duration'=>'長期','rate'=>95,'posted'=>5,'hot'=>false,
     'langs'=>[],'fws'=>[],
     'site_name'=>'Green',
     'apply_url'=>'https://www.green-japan.com/search?keyword=PM+PMO+ITプロジェクト'],

    ['id'=>31,'title'=>'スタートアップ プロダクトマネージャー（週3〜）',
     'role'=>'PM／PMO','style'=>'hybrid','prefecture'=>'東京都','city'=>'渋谷区','station'=>'渋谷',
     'location'=>'渋谷・週3出社','duration'=>'長期','rate'=>85,'posted'=>2,'hot'=>false,
     'langs'=>[],'fws'=>[],
     'site_name'=>'Wantedly',
     'apply_url'=>'https://www.wantedly.com/projects?query=プロダクトマネージャー+PM'],
]);

/* ═══════════════════════════════════════════════════════════
   入力値の取得・サニタイズ
═══════════════════════════════════════════════════════════ */
$q        = trim($_GET['q']         ?? '');
$role     = trim($_GET['role']      ?? '');
$style    = trim($_GET['style']     ?? '');
$langs_in = array_filter(array_map('trim', (array)($_GET['langs'] ?? [])));
$fws_in   = array_filter(array_map('trim', (array)($_GET['fws']   ?? [])));
$min_rate = max(0, (int)($_GET['min_rate'] ?? 0));
$pref     = trim($_GET['pref']      ?? '');
$city     = trim($_GET['city']      ?? '');
$station  = trim($_GET['station']   ?? '');
$remote_ok = !empty($_GET['remote_ok']);
$sort_by  = in_array($_GET['sort'] ?? '', ['rate-desc','rate-asc','score','new'])
            ? $_GET['sort'] : 'new';

/* ═══════════════════════════════════════════════════════════
   フィルタリング
═══════════════════════════════════════════════════════════ */
function match_score(array $a, array $langs_in, array $fws_in): int {
    $sc = 0;
    foreach ($langs_in as $l) { if (in_array($l, $a['langs'], true)) $sc += 3; }
    foreach ($fws_in   as $f) { if (in_array($f, $a['fws'],   true)) $sc += 2; }
    if ($a['hot'])        $sc += 1;
    if ($a['posted'] <= 1) $sc += 1;
    return $sc;
}

$result = array_filter(ANKEN, function($a) use ($q, $role, $style, $langs_in, $fws_in, $min_rate, $pref, $city, $station, $remote_ok) {
    if ($q !== '') {
        $hay = implode(' ', array_merge([$a['title'],$a['role'],$a['location'],$a['prefecture'],$a['city'],$a['station'],$a['site_name']], $a['langs'], $a['fws']));
        if (mb_stripos($hay, $q) === false) return false;
    }
    if ($role !== '' && $a['role'] !== $role) return false;
    if ($remote_ok && $a['style'] === 'onsite') return false;
    if (!$remote_ok && $style !== '' && $a['style'] !== $style) return false;
    if (!empty($langs_in) && !array_intersect($langs_in, $a['langs'])) return false;
    if (!empty($fws_in)   && !array_intersect($fws_in,   $a['fws']))   return false;
    if ($min_rate > 0 && $a['rate'] < $min_rate) return false;
    if ($pref !== '' && $a['style'] !== 'remote' && $a['prefecture'] !== $pref) return false;
    if ($city !== '' && $a['city'] !== '' && mb_stripos($a['city'], $city) === false) return false;
    if ($station !== '' && $a['station'] !== '' && mb_stripos($a['station'], $station) === false) return false;
    return true;
});
$result = array_values($result);

usort($result, function($a, $b) use ($sort_by, $langs_in, $fws_in) {
    return match ($sort_by) {
        'rate-desc' => $b['rate'] <=> $a['rate'],
        'rate-asc'  => $a['rate'] <=> $b['rate'],
        'score'     => match_score($b, $langs_in, $fws_in) <=> match_score($a, $langs_in, $fws_in),
        default     => $a['posted'] <=> $b['posted'],
    };
});

/* ═══════════════════════════════════════════════════════════
   外部サイト向けキーワード生成
═══════════════════════════════════════════════════════════ */
$kw_parts = array_filter(array_merge(
    $q !== '' ? [$q] : [],
    $langs_in,
    $fws_in,
    $role !== '' ? [$role] : [],
));
$kw = urlencode(mb_substr(implode(' ', $kw_parts), 0, 100));

/* ═══════════════════════════════════════════════════════════
   ヘルパー関数
═══════════════════════════════════════════════════════════ */
function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function sel(string $val, string $current): string { return $val === $current ? ' selected' : ''; }
function chk(bool $cond): string { return $cond ? ' checked' : ''; }
function in_arr(string $v, array $arr): bool { return in_array($v, $arr, true); }

function posted_label(int $d): string {
    if ($d === 0) return '本日';
    if ($d === 1) return '昨日';
    return $d . '日前';
}
function style_badge(string $s): string {
    return match($s) {
        'remote' => '<span class="bdg bdg-remote">リモート</span>',
        'hybrid' => '<span class="bdg bdg-hybrid">ハイブリッド</span>',
        'onsite' => '<span class="bdg bdg-onsite">常駐</span>',
        default  => '',
    };
}

/* ═══════════════════════════════════════════════════════════
   HTML 出力
═══════════════════════════════════════════════════════════ */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>案件ボード | ITエンジニア案件・求人マッチング</title>
<meta name="description" content="希望言語・フレームワーク・月額・勤務地からIT案件・求人をマッチング。外部サイトへ直接応募。ロリポップ！含む全レンタルサーバー対応の純粋PHP版。">
<meta name="theme-color" content="#0b1220">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='22' fill='%2306b6d4'/%3E%3Ctext x='50' y='68' font-size='60' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='900'%3E案%3C/text%3E%3C/svg%3E">
<!-- Bootstrap 5.3 CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<!-- Noto Sans JP -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#0b1220;--bg2:#0f172a;--surface:#111c33;--surface2:#16223d;
  --border:rgba(255,255,255,.08);--border-s:rgba(255,255,255,.16);
  --text:#e5edf7;--dim:#9aa8c0;--muted:#6b7a93;
  --primary:#06b6d4;--primary-lt:#22d3ee;--primary-glow:rgba(6,182,212,.18);
  --accent:#a78bfa;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;
  --r:14px;
}
html{scroll-behavior:smooth}
body{
  font-family:'Noto Sans JP','Hiragino Kaku Gothic ProN',Meiryo,system-ui,sans-serif;
  background:
    radial-gradient(ellipse 1300px 600px at 90% -5%,rgba(6,182,212,.13),transparent 62%),
    radial-gradient(ellipse 900px 500px at -5% 28%, rgba(167,139,250,.10),transparent 56%),
    var(--bg);
  color:var(--text);min-height:100vh;-webkit-font-smoothing:antialiased;
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
.btn-cta{padding:.48rem 1rem;border-radius:9px;border:0;font-weight:700;font-size:.85rem;background:linear-gradient(135deg,var(--primary),var(--accent));color:#0b1220;box-shadow:0 6px 18px rgba(6,182,212,.3);transition:filter .15s;cursor:pointer}
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
.form-check-input{background:var(--surface2);border-color:var(--border-s)}
.form-check-input:checked{background-color:var(--primary);border-color:var(--primary)}
.form-check-label{color:var(--dim);font-size:.88rem}

/* Chips */
.chip-wrap{display:flex;flex-wrap:wrap;gap:.28rem;max-height:78px;overflow:hidden;transition:max-height .25s ease}
.chip-wrap.open{max-height:none}
.chip{display:inline-block;padding:.22rem .58rem;border-radius:9999px;font-size:.74rem;font-weight:700;background:rgba(255,255,255,.05);color:var(--dim);border:1px solid var(--border);cursor:pointer;transition:all .12s;user-select:none;line-height:1.5}
.chip:hover{color:#fff;background:rgba(255,255,255,.1)}
.chip.on{background:var(--primary-glow);color:var(--primary-lt);border-color:rgba(34,211,238,.45)}
.chip-cat{background:rgba(167,139,250,.08);color:var(--accent);border-color:rgba(167,139,250,.25);cursor:default;font-size:.68rem}
.chip-more{font-size:.72rem;color:var(--primary-lt);cursor:pointer;margin-top:.25rem;display:inline-block}

/* Buttons */
.btn-search{width:100%;padding:.75rem 1.4rem;border-radius:10px;border:0;font-weight:800;font-size:.95rem;background:linear-gradient(135deg,var(--primary),var(--accent));color:#0b1220;box-shadow:0 8px 24px rgba(6,182,212,.3);transition:filter .15s,transform .1s;cursor:pointer}
.btn-search:hover{filter:brightness(1.08);transform:translateY(-1px)}
.btn-reset{width:100%;display:block;text-align:center;padding:.75rem 1.1rem;border-radius:10px;border:1px solid var(--border-s);background:transparent;color:var(--dim);font-weight:700;font-size:.88rem;transition:all .15s}
.btn-reset:hover{color:#fff;background:rgba(255,255,255,.06)}
.sort-sel{background:var(--surface2);border:1px solid var(--border-s);color:#fff;padding:.4rem .75rem;border-radius:8px;font-size:.84rem;font-family:inherit;cursor:pointer}

/* Cards */
.card-anken{background:linear-gradient(165deg,var(--surface) 0%,var(--surface2) 100%);border:1px solid var(--border);border-radius:var(--r);padding:1.1rem;display:flex;flex-direction:column;height:100%;position:relative;overflow:hidden;transition:transform .18s,border-color .18s,box-shadow .18s}
.card-anken::before{content:"";position:absolute;inset:0 0 auto 0;height:3px;background:linear-gradient(90deg,var(--primary),var(--accent));opacity:0;transition:opacity .2s}
.card-anken:hover{transform:translateY(-3px);border-color:var(--border-s);box-shadow:0 14px 38px rgba(0,0,0,.48)}
.card-anken:hover::before{opacity:1}
.bdg{display:inline-block;font-size:.63rem;font-weight:800;padding:.16rem .48rem;border-radius:5px;letter-spacing:.03em;margin-right:.18rem}
.bdg-new{background:rgba(16,185,129,.16);color:#34d399}
.bdg-hot{background:rgba(239,68,68,.16);color:#fb7185}
.bdg-remote{background:rgba(34,211,238,.16);color:var(--primary-lt)}
.bdg-onsite{background:rgba(245,158,11,.16);color:#fbbf24}
.bdg-hybrid{background:rgba(167,139,250,.16);color:#c4b5fd}
.card-title{font-size:.93rem;font-weight:800;color:#fff;line-height:1.45}
.card-meta{font-size:.77rem;color:var(--dim);display:flex;flex-wrap:wrap;gap:.25rem .75rem}
.stag{display:inline-block;font-size:.67rem;font-weight:700;padding:.14rem .48rem;border-radius:5px}
.stag-lang{background:rgba(6,182,212,.12);color:#67e8f9;border:1px solid rgba(6,182,212,.28)}
.stag-fw{background:rgba(167,139,250,.12);color:#c4b5fd;border:1px solid rgba(167,139,250,.28)}
.card-rate{font-size:1.22rem;font-weight:900;color:#fff}
.card-rate small{font-size:.74rem;color:var(--muted);font-weight:500}
.card-annual{font-size:.82rem;font-weight:700;color:var(--accent);margin-left:.4rem}
.site-badge{font-size:.62rem;font-weight:700;color:var(--muted);border:1px solid var(--border);padding:.1rem .42rem;border-radius:5px}
.btn-apply{display:inline-flex;align-items:center;gap:.28rem;padding:.52rem .88rem;border-radius:8px;font-size:.78rem;font-weight:800;background:var(--primary-glow);color:var(--primary-lt);border:1px solid rgba(34,211,238,.35);transition:all .15s}
.btn-apply:hover{background:var(--primary);color:#0b1220;border-color:transparent}
.empty-state{text-align:center;padding:3.5rem 1rem;background:var(--surface);border:1px dashed var(--border-s);border-radius:var(--r);color:var(--dim)}
.empty-state strong{display:block;color:#fff;font-size:1.05rem;margin-bottom:.4rem}

/* External Sites */
.ext-section{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:1.5rem}
.ext-title{font-size:1.05rem;font-weight:800;color:#fff}
.ext-sub{font-size:.82rem;color:var(--dim);line-height:1.65}
.ext-card{display:block;height:100%;background:var(--surface2);border:1px solid var(--border);border-radius:11px;padding:.9rem 1rem;transition:border-color .15s,transform .15s,box-shadow .15s;color:inherit}
.ext-card:hover{border-color:var(--border-s);transform:translateY(-2px);box-shadow:0 8px 22px rgba(0,0,0,.3)}
.ext-card-name{font-size:.92rem;font-weight:800;color:#fff}
.ext-tag{font-size:.62rem;font-weight:800;padding:.1rem .42rem;border-radius:5px}
.t-fl{background:rgba(6,182,212,.15);color:var(--primary-lt)}
.t-sei{background:rgba(16,185,129,.15);color:#34d399}
.t-side{background:rgba(245,158,11,.15);color:#fbbf24}
.t-gen{background:rgba(167,139,250,.15);color:#c4b5fd}
.ext-desc{font-size:.78rem;color:var(--dim);margin:.28rem 0;line-height:1.55}
.ext-cta{font-size:.76rem;font-weight:700;color:var(--primary-lt)}

/* Footer */
.site-footer{border-top:1px solid var(--border);padding:1.6rem 1rem;text-align:center;color:var(--muted);font-size:.76rem;line-height:1.9}
.site-footer a{color:var(--dim)}
.site-footer a:hover{color:#fff}
</style>
</head>
<body>

<!-- HEADER -->
<header class="site-header">
  <div class="container-xl">
    <div class="d-flex align-items-center justify-content-between py-2 gap-2">
      <a href="?" class="brand">
        <span class="brand-icon" aria-hidden="true">案</span>
        <span>
          <span class="d-block" style="font-size:.98rem;line-height:1.1">案件ボード</span>
          <span class="d-block" style="font-size:.58rem;letter-spacing:.12em;color:var(--muted);font-weight:600">ANKEN BOARD</span>
        </span>
      </a>
      <nav class="d-flex align-items-center gap-1">
        <a href="#search" class="nav-a d-none d-md-inline">案件を探す</a>
        <a href="#ext"    class="nav-a d-none d-md-inline">外部求人サイト</a>
        <button class="btn-cta" type="button"
          onclick="document.getElementById('q').focus();document.getElementById('search').scrollIntoView({behavior:'smooth'})">案件を探す</button>
      </nav>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="hero">
  <div class="container-xl px-3">
    <p style="font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--primary-lt);font-weight:700;margin-bottom:.6rem">IT案件・求人マッチング</p>
    <h1 class="hero-h1">スキルと希望条件から<br>あなたにぴったりの案件が見つかる。</h1>
    <p class="hero-sub">言語・フレームワーク・月額・勤務地で絞り込み。マッチした案件の外部サイトへ直接応募 ＋ 似た求人が見つかる外部サービスもご紹介。</p>
    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4" style="row-gap:.75rem">
      <div class="text-center"><span class="hero-stat-val"><?= count(ANKEN) ?></span><span class="hero-stat-lbl d-block">掲載案件</span></div>
      <div class="text-center"><span class="hero-stat-val"><?= count(EXT_SITES) ?></span><span class="hero-stat-lbl d-block">外部求人サイト</span></div>
      <div class="text-center"><span class="hero-stat-val">80%</span><span class="hero-stat-lbl d-block">リモート対応</span></div>
      <div class="text-center"><span class="hero-stat-val">¥60〜130万</span><span class="hero-stat-lbl d-block">月額レンジ</span></div>
    </div>
  </div>
</section>

<!-- SEARCH PANEL -->
<section class="pb-3" id="search">
  <div class="container-xl px-3">
    <form method="GET" action="" class="search-panel" id="search-form">

      <!-- Row 1: keyword / role / style -->
      <div class="row g-2 mb-2">
        <div class="col-12 col-md-5">
          <label class="f-label" for="q">キーワード</label>
          <input class="form-control" id="q" name="q" type="search"
                 placeholder="例：React、フルスタック、AI、Go"
                 value="<?= h($q) ?>">
        </div>
        <div class="col-6 col-md-4">
          <label class="f-label" for="role">職種カテゴリ</label>
          <select class="form-select" id="role" name="role">
            <option value="">すべての職種</option>
            <?php foreach (['フロントエンド','バックエンド','フルスタック','モバイル','インフラ／SRE','データ／AI・ML','PM／PMO'] as $r): ?>
              <option value="<?= h($r) ?>"<?= sel($r, $role) ?>><?= h($r) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-6 col-md-3">
          <label class="f-label" for="style">働き方</label>
          <select class="form-select" id="style" name="style">
            <option value="">すべて</option>
            <option value="remote"<?= sel('remote',$style) ?>>フルリモート</option>
            <option value="hybrid"<?= sel('hybrid',$style) ?>>ハイブリッド</option>
            <option value="onsite"<?= sel('onsite',$style) ?>>常駐</option>
          </select>
        </div>
      </div>

      <!-- Row 2: Language chips -->
      <div class="mb-2">
        <label class="f-label">希望プログラミング言語 <span style="color:var(--muted);font-weight:500;text-transform:none;letter-spacing:0">（複数選択可・OR検索）</span></label>
        <div class="chip-wrap" id="lang-chips">
          <?php foreach (LANGS as $lang): ?>
            <span class="chip<?= in_arr($lang,$langs_in)?' on':'' ?>"
                  data-val="<?= h($lang) ?>" data-group="langs"
                  onclick="toggleChip(this)"><?= h($lang) ?></span>
          <?php endforeach; ?>
        </div>
        <span class="chip-more" onclick="toggleWrap('lang-chips',this)">▼ すべて表示</span>
      </div>

      <!-- Row 3: Framework chips -->
      <div class="mb-3">
        <label class="f-label">希望フレームワーク・ツール <span style="color:var(--muted);font-weight:500;text-transform:none;letter-spacing:0">（複数選択可・OR検索）</span></label>
        <div class="chip-wrap" id="fw-chips">
          <?php foreach (FRAMEWORKS as $cat => $fwList): ?>
            <span class="chip chip-cat"><?= h($cat) ?></span>
            <?php foreach ($fwList as $fw): ?>
              <span class="chip<?= in_arr($fw,$fws_in)?' on':'' ?>"
                    data-val="<?= h($fw) ?>" data-group="fws"
                    onclick="toggleChip(this)"><?= h($fw) ?></span>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
        <span class="chip-more" onclick="toggleWrap('fw-chips',this)">▼ すべて表示</span>
      </div>

      <!-- Hidden inputs for selected langs/fws -->
      <div id="hidden-chip-inputs">
        <?php foreach ($langs_in as $l): ?>
          <input type="hidden" name="langs[]" value="<?= h($l) ?>" data-chip-val="<?= h($l) ?>">
        <?php endforeach; ?>
        <?php foreach ($fws_in as $f): ?>
          <input type="hidden" name="fws[]" value="<?= h($f) ?>" data-chip-val="<?= h($f) ?>">
        <?php endforeach; ?>
      </div>

      <!-- Row 4: Rate / Annual -->
      <div class="row g-2 mb-2">
        <div class="col-12 col-md-7">
          <label class="f-label" for="rate-slider">希望月額（下限）</label>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <input type="range" class="form-range flex-grow-1" id="rate-slider"
                   min="0" max="200" step="5" value="<?= $min_rate ?>"
                   style="min-width:120px" oninput="syncRate(this.value)">
            <input type="number" class="form-control text-center" id="rate-num" name="min_rate"
                   min="0" max="200" step="5" value="<?= $min_rate ?>"
                   style="width:70px;flex-shrink:0" oninput="syncRateFromNum(this.value)">
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
                   min="0" max="2400" step="60" value="<?= $min_rate * 12 ?>"
                   placeholder="0" oninput="syncFromAnnual(this.value)">
            <span style="color:var(--dim);font-size:.82rem;white-space:nowrap">万円/年〜</span>
          </div>
          <div style="font-size:.68rem;color:var(--muted);margin-top:.3rem">月額 × 12 で自動換算（入力も可）</div>
        </div>
      </div>

      <!-- Row 5: Location -->
      <div class="row g-2 mb-3 align-items-end">
        <div class="col-12 col-sm-4 col-md-3">
          <label class="f-label" for="pref">都道府県</label>
          <select class="form-select" id="pref" name="pref">
            <?php foreach (PREFS as $p): ?>
              <option value="<?= h($p) ?>"<?= sel($p,$pref) ?>><?= $p === '' ? '都道府県を選択' : h($p) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12 col-sm-4 col-md-3">
          <label class="f-label" for="city">市区町村</label>
          <input class="form-control" id="city" name="city" type="text"
                 placeholder="例：渋谷区" value="<?= h($city) ?>">
        </div>
        <div class="col-12 col-sm-4 col-md-3">
          <label class="f-label" for="station">最寄り駅</label>
          <input class="form-control" id="station" name="station" type="text"
                 placeholder="例：渋谷、梅田" value="<?= h($station) ?>">
        </div>
        <div class="col-12 col-md-3 d-flex align-items-center" style="padding-top:1.6rem">
          <div class="form-check m-0">
            <input class="form-check-input" type="checkbox" id="remote-ok"
                   name="remote_ok" value="1"<?= chk($remote_ok) ?>>
            <label class="form-check-label" for="remote-ok">リモート・ハイブリッドのみ</label>
          </div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="row g-2">
        <div class="col-8 col-sm-9 col-md-10">
          <button type="submit" class="btn-search">マッチする案件を探す</button>
        </div>
        <div class="col-4 col-sm-3 col-md-2">
          <a href="?" class="btn-reset">リセット</a>
        </div>
      </div>

    </form>
  </div>
</section>

<!-- RESULTS -->
<main class="py-3" id="results">
  <div class="container-xl px-3">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
      <h2 style="font-size:1.08rem;font-weight:800;color:#fff;margin:0">
        マッチング結果
        <span style="font-size:.88rem;color:var(--dim);font-weight:600">（<?= count($result) ?>件）</span>
      </h2>
      <!-- Sort: hidden inputs + auto-submit -->
      <form method="GET" action="" id="sort-form">
        <?php
        $pass = ['q'=>$q,'role'=>$role,'style'=>$style,'min_rate'=>$min_rate,'pref'=>$pref,'city'=>$city,'station'=>$station];
        foreach ($pass as $k=>$v) { if ($v !== '' && $v !== 0): ?>
          <input type="hidden" name="<?= h($k) ?>" value="<?= h((string)$v) ?>">
        <?php endif; } ?>
        <?php foreach ($langs_in as $l): ?>
          <input type="hidden" name="langs[]" value="<?= h($l) ?>">
        <?php endforeach; ?>
        <?php foreach ($fws_in as $f): ?>
          <input type="hidden" name="fws[]" value="<?= h($f) ?>">
        <?php endforeach; ?>
        <?php if ($remote_ok): ?><input type="hidden" name="remote_ok" value="1"><?php endif; ?>
        <select class="sort-sel" name="sort" onchange="this.form.submit()">
          <option value="new"       <?= sel('new',      $sort_by) ?>>新着順</option>
          <option value="rate-desc" <?= sel('rate-desc',$sort_by) ?>>月額が高い順</option>
          <option value="rate-asc"  <?= sel('rate-asc', $sort_by) ?>>月額が低い順</option>
          <option value="score"     <?= sel('score',    $sort_by) ?>>マッチ度順</option>
        </select>
      </form>
    </div>

    <!-- Job Cards -->
    <?php if (empty($result)): ?>
      <div class="empty-state">
        <strong>該当する案件が見つかりませんでした</strong>
        条件を変えてもう一度お試しください。
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($result as $a): ?>
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card-anken">
              <div class="d-flex justify-content-between align-items-start mb-1" style="gap:.35rem">
                <div>
                  <?= $a['posted'] <= 1 ? '<span class="bdg bdg-new">NEW</span>' : '' ?>
                  <?= $a['hot'] ? '<span class="bdg bdg-hot">注目</span>' : '' ?>
                  <?= style_badge($a['style']) ?>
                </div>
                <span style="font-size:.7rem;color:var(--muted);flex-shrink:0"><?= posted_label($a['posted']) ?></span>
              </div>

              <div class="card-title mb-2"><?= h($a['title']) ?></div>

              <div class="card-meta mb-2">
                <span>📍 <?= h($a['location']) ?></span>
                <span>⏱ <?= h($a['duration']) ?></span>
                <span style="color:var(--muted)"><?= h($a['role']) ?></span>
              </div>

              <div class="d-flex flex-wrap gap-1 mb-2">
                <?php foreach ($a['langs'] as $l): ?>
                  <span class="stag stag-lang"><?= h($l) ?></span>
                <?php endforeach; ?>
                <?php foreach ($a['fws'] as $f): ?>
                  <span class="stag stag-fw"><?= h($f) ?></span>
                <?php endforeach; ?>
              </div>

              <div class="mt-auto">
                <div class="mb-2">
                  <span class="card-rate">¥<?= $a['rate'] ?><small> 万円/月</small></span>
                  <span class="card-annual">年収 約¥<?= $a['rate'] * 12 ?>万</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="site-badge"><?= h($a['site_name']) ?></span>
                  <a href="<?= h($a['apply_url']) ?>"
                     target="_blank" rel="noopener noreferrer"
                     class="btn-apply">
                    このサイトで応募
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- EXTERNAL SITES -->
    <div class="mt-4" id="ext">
      <div class="ext-section">
        <div class="ext-title mb-1">🔍 この条件でもっと探す — 外部求人・案件サイト</div>
        <p class="ext-sub mb-3">
          <?php
          $kw_label_parts = array_filter(array_merge($q !== '' ? [$q] : [], $langs_in, $fws_in));
          echo $kw_label_parts
              ? '「' . h(implode(' / ', array_slice($kw_label_parts, 0, 4))) . '」に関連する案件を外部サービスでも探せます。'
              : '現在の検索条件に関連する案件を外部サービスでも探せます。';
          ?>
          各サイトの検索結果ページへ直接リンクします（別タブ）。
        </p>
        <div class="row g-2">
          <?php foreach (EXT_SITES as $site): ?>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
              <a href="<?= h($site['url'] . $kw) ?>"
                 target="_blank" rel="noopener noreferrer"
                 class="ext-card">
                <div class="d-flex align-items-center gap-1 mb-1 flex-wrap">
                  <span class="ext-card-name"><?= h($site['name']) ?></span>
                  <span class="ext-tag t-<?= h($site['type']) ?>"><?= h($site['tag']) ?></span>
                </div>
                <div class="ext-desc"><?= h($site['desc']) ?></div>
                <div class="ext-cta">このサイトで探す →</div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

  </div>
</main>

<footer class="site-footer">
  <div>© 2026 案件ボード — ITエンジニア案件・求人マッチング（純粋PHP版）</div>
  <div class="mt-1">
    <a href="#search">案件を探す</a> ·
    <a href="#ext">外部求人サイト</a>
  </div>
  <!--
  ════════════════════════════════════════════════════════════
  ロリポップ！（全プラン）へのデプロイ手順
  ════════════════════════════════════════════════════════════
  【必要ファイル】 この index.php 1ファイルのみ
  【サーバー要件】 PHP 7.4 以上（ロリポップ！全プランで対応）
                  Composer / SSH / DB 一切不要
  【手順】
    1. ロリポップ！管理画面 → FTP 情報を確認
    2. FFFTP / FileZilla / ロリポップ！FTP（ブラウザ）で接続
    3. 公開ディレクトリに aruaru/ フォルダを作成し index.php を配置
    4. https://あなたのドメイン/aruaru/index.php にアクセス
  ════════════════════════════════════════════════════════════
  -->
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* 月額 ↔ 年収 連動 */
function syncRate(v)         { document.getElementById('rate-num').value   = v;            document.getElementById('annual-num').value = v * 12; }
function syncRateFromNum(v)  { const n=parseInt(v)||0; document.getElementById('rate-slider').value = n; document.getElementById('annual-num').value = n * 12; }
function syncFromAnnual(v)   { const m=Math.round((parseInt(v)||0)/12);    document.getElementById('rate-slider').value = m; document.getElementById('rate-num').value = m; }

/* チップ選択 → hidden input の追加/削除 */
function toggleChip(el) {
  el.classList.toggle('on');
  const val   = el.dataset.val;
  const group = el.dataset.group;   // "langs" or "fws"
  const wrap  = document.getElementById('hidden-chip-inputs');
  const id    = 'hci-' + group + '-' + val.replace(/[^a-zA-Z0-9]/g,'_');

  if (el.classList.contains('on')) {
    if (!document.getElementById(id)) {
      const inp = document.createElement('input');
      inp.type  = 'hidden';
      inp.id    = id;
      inp.name  = group + '[]';
      inp.value = val;
      wrap.appendChild(inp);
    }
  } else {
    const existing = document.getElementById(id);
    if (existing) existing.remove();
  }
}

/* チップ欄 展開/折り畳み */
function toggleWrap(wrapId, btn) {
  const wrap = document.getElementById(wrapId);
  const open = wrap.classList.toggle('open');
  btn.textContent = open ? '▲ 閉じる' : '▼ すべて表示';
}
</script>
</body>
</html>
