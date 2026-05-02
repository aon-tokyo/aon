<?php

namespace App\Http\Controllers;

use App\Services\GoogleCustomSearchService;
use Illuminate\Http\Request;

class AnkenController extends Controller
{
    public function __construct(
        private GoogleCustomSearchService $googleCse
    ) {}

    /* ─────────────────────────────────────────────────────────────
       プログラミング言語マスター（2026年4月）
    ───────────────────────────────────────────────────────────── */
    public const LANGS = [
        'JavaScript', 'TypeScript', 'Python', 'Go', 'PHP', 'Ruby', 'Java', 'Kotlin',
        'Swift', 'Rust', 'C#', 'C++', 'Scala', 'R', 'Dart', 'Elixir', 'Haskell', 'Lua', 'COBOL', 'VBA',
    ];

    /* ─────────────────────────────────────────────────────────────
       フレームワーク・ツールマスター（カテゴリ別）
    ───────────────────────────────────────────────────────────── */
    public const FRAMEWORKS = [
        'フロントエンド' => [
            'React', 'Next.js', 'Vue.js', 'Nuxt.js', 'Angular', 'Svelte', 'SvelteKit',
            'Astro', 'Remix', 'Solid.js', 'HTMX', 'Qwik', 'Lit', 'Preact',
            'TanStack Query', 'TanStack Router',
        ],
        'バックエンド' => [
            'Express', 'Fastify', 'NestJS', 'Hono', 'tRPC',
            'Django', 'FastAPI', 'Flask', 'Streamlit', 'Celery',
            'Laravel', 'Symfony', 'CakePHP', 'CodeIgniter', 'Lumen',
            'Ruby on Rails',
            'Spring Boot', 'Micronaut', 'Quarkus', 'Play Framework', 'Akka',
            'ASP.NET Core', 'Blazor',
            'Gin', 'Echo', 'Fiber', 'Chi',
            'Axum', 'Actix Web', 'Rocket',
            'Phoenix',
        ],
        'モバイル' => [
            'React Native', 'Flutter', 'SwiftUI', 'Jetpack Compose', 'Expo',
            'Capacitor', 'Ionic',
        ],
        'インフラ／ツール' => [
            'Docker', 'Kubernetes', 'Terraform', 'Ansible', 'AWS CDK', 'Crossplane',
            'Podman', 'Nix',
            'GraphQL', 'REST API', 'OpenAPI', 'gRPC',
            'OpenTelemetry', 'Prometheus', 'Grafana',
            'dbt', 'Snowflake', 'LangChain', 'Apache Kafka', 'Redis',
        ],
    ];

    /* ─────────────────────────────────────────────────────────────
       外部求人・案件サービス
    ───────────────────────────────────────────────────────────── */
    public const EXT_SITES = [
        ['name' => 'レバテックフリーランス', 'tag' => 'フリーランス', 'type' => 'fl',
            'desc' => '案件数・単価ともに国内最大級のITフリーランス向けエージェント。高単価・長期案件が豊富。',
            'url' => 'https://freelance.levtech.jp/project/search/?keyword='],
        ['name' => 'ITプロパートナーズ', 'tag' => '副業・フリーランス', 'type' => 'fl',
            'desc' => '週2〜3日から参画できる副業・フリーランス案件に特化。スタートアップ系が豊富。',
            'url' => 'https://itpropartners.com/job?free_word='],
        ['name' => 'Midworks', 'tag' => 'フリーランス', 'type' => 'fl',
            'desc' => 'フリーランスでも社会保険・各種保障が充実。正社員並みのサポートで安心して働ける。',
            'url' => 'https://midworks.com/projects/?keyword='],
        ['name' => 'クラウドテック', 'tag' => 'フリーランス', 'type' => 'fl',
            'desc' => 'クラウドワークスが運営するITフリーランス向けエージェント。多様な職種・単価帯。',
            'url' => 'https://crowdtech.jp/projects/search/?word='],
        ['name' => 'Findy Freelance', 'tag' => 'フリーランス', 'type' => 'fl',
            'desc' => 'GitHubスキルスコアで自動マッチング。エンジニア目線のフリーランス案件サービス。',
            'url' => 'https://findy-code.io/freelance/projects?keyword='],
        ['name' => 'Offers', 'tag' => '副業・複業', 'type' => 'side',
            'desc' => '副業・複業×開発案件のマッチング。スタートアップや成長企業の週1〜案件が充実。',
            'url' => 'https://offers.jp/jobs?keyword='],
        ['name' => 'Green', 'tag' => '正社員転職', 'type' => 'sei',
            'desc' => 'IT・Web・ゲーム業界特化の転職サービス。正社員でキャリアアップしたい方向け。',
            'url' => 'https://www.green-japan.com/search?keyword='],
        ['name' => 'Wantedly', 'tag' => 'スタートアップ', 'type' => 'side',
            'desc' => '「やりたいこと」でつながる採用サービス。スタートアップ・ベンチャーの求人が豊富。',
            'url' => 'https://www.wantedly.com/projects?query='],
        ['name' => 'Findy（転職）', 'tag' => 'エンジニア転職', 'type' => 'sei',
            'desc' => 'スキルスコアでスカウトが届くエンジニア特化の転職サービス。高年収求人多数。',
            'url' => 'https://findy-code.io/job-offers?search='],
        ['name' => 'Indeed Japan', 'tag' => '総合求人', 'type' => 'gen',
            'desc' => '国内最大級の求人検索エンジン。正社員・契約社員・フリーランスを幅広く検索可能。',
            'url' => 'https://jp.indeed.com/jobs?q='],
    ];

    /* ─────────────────────────────────────────────────────────────
       案件サンプルデータ（実在する外部サイトへの直接応募URLつき）
    ───────────────────────────────────────────────────────────── */
    public const ANKEN = [
        /* フロントエンド */
        ['id' => 1, 'title' => '大規模ECサイト フロントエンド開発（React / Next.js 15）',
            'role' => 'フロントエンド', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '6ヶ月〜', 'rate' => 85, 'posted' => 0, 'hot' => true,
            'langs' => ['TypeScript', 'JavaScript'], 'fws' => ['React', 'Next.js', 'GraphQL'],
            'site_name' => 'レバテックフリーランス',
            'apply_url' => 'https://freelance.levtech.jp/project/search/?keyword=React+Next.js+TypeScript'],

        ['id' => 2, 'title' => '動画配信PF フロントエンド刷新（Vue 3 / Nuxt 3）',
            'role' => 'フロントエンド', 'style' => 'hybrid', 'prefecture' => '東京都', 'city' => '渋谷区', 'station' => '渋谷',
            'location' => '東京・週2出社', 'duration' => '6ヶ月', 'rate' => 78, 'posted' => 2, 'hot' => false,
            'langs' => ['TypeScript', 'JavaScript'], 'fws' => ['Vue.js', 'Nuxt.js'],
            'site_name' => 'ITプロパートナーズ',
            'apply_url' => 'https://itpropartners.com/job?free_word=Vue+Nuxt'],

        ['id' => 3, 'title' => 'SaaS 管理画面リニューアル（Angular 18）',
            'role' => 'フロントエンド', 'style' => 'onsite', 'prefecture' => '神奈川県', 'city' => '横浜市', 'station' => '横浜',
            'location' => '横浜・常駐', 'duration' => '4ヶ月', 'rate' => 72, 'posted' => 5, 'hot' => false,
            'langs' => ['TypeScript'], 'fws' => ['Angular'],
            'site_name' => 'クラウドテック',
            'apply_url' => 'https://crowdtech.jp/projects/search/?word=Angular+TypeScript'],

        ['id' => 4, 'title' => '金融ダッシュボード 新規開発（Svelte / SvelteKit）',
            'role' => 'フロントエンド', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '3ヶ月〜', 'rate' => 80, 'posted' => 1, 'hot' => false,
            'langs' => ['TypeScript', 'JavaScript'], 'fws' => ['Svelte', 'SvelteKit'],
            'site_name' => 'Findy Freelance',
            'apply_url' => 'https://findy-code.io/freelance/projects?keyword=Svelte'],

        ['id' => 5, 'title' => 'コンテンツサイト新規構築（Astro / React）',
            'role' => 'フロントエンド', 'style' => 'remote', 'prefecture' => '大阪府', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '2ヶ月', 'rate' => 65, 'posted' => 7, 'hot' => false,
            'langs' => ['TypeScript', 'JavaScript'], 'fws' => ['Astro', 'React'],
            'site_name' => 'Midworks',
            'apply_url' => 'https://midworks.com/projects/?keyword=Astro+React'],

        ['id' => 6, 'title' => 'Rust + WebAssembly 高性能ブラウザアプリ開発',
            'role' => 'フロントエンド', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '4ヶ月', 'rate' => 102, 'posted' => 8, 'hot' => true,
            'langs' => ['Rust', 'JavaScript'], 'fws' => ['React'],
            'site_name' => 'Findy Freelance',
            'apply_url' => 'https://findy-code.io/freelance/projects?keyword=Rust+WebAssembly'],

        /* バックエンド */
        ['id' => 7, 'title' => '決済API 設計・開発（Go / Gin）',
            'role' => 'バックエンド', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '長期', 'rate' => 95, 'posted' => 1, 'hot' => true,
            'langs' => ['Go'], 'fws' => ['Gin', 'Docker'],
            'site_name' => 'レバテックフリーランス',
            'apply_url' => 'https://freelance.levtech.jp/project/search/?keyword=Go+Gin+API'],

        ['id' => 8, 'title' => 'Go / Echo マイクロサービス API 開発',
            'role' => 'バックエンド', 'style' => 'remote', 'prefecture' => '大阪府', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '長期', 'rate' => 93, 'posted' => 0, 'hot' => false,
            'langs' => ['Go'], 'fws' => ['Echo', 'Docker', 'Kubernetes'],
            'site_name' => 'ITプロパートナーズ',
            'apply_url' => 'https://itpropartners.com/job?free_word=Go+Echo+マイクロサービス'],

        ['id' => 9, 'title' => 'ヘルスケアSaaS API 構築（Python / FastAPI）',
            'role' => 'バックエンド', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '6ヶ月〜', 'rate' => 88, 'posted' => 3, 'hot' => true,
            'langs' => ['Python'], 'fws' => ['FastAPI', 'Docker'],
            'site_name' => 'Midworks',
            'apply_url' => 'https://midworks.com/projects/?keyword=Python+FastAPI'],

        ['id' => 10, 'title' => 'Django REST API + PostgreSQL 設計・開発',
            'role' => 'バックエンド', 'style' => 'remote', 'prefecture' => '福岡県', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '3ヶ月', 'rate' => 77, 'posted' => 10, 'hot' => false,
            'langs' => ['Python'], 'fws' => ['Django', 'REST API'],
            'site_name' => 'ITプロパートナーズ',
            'apply_url' => 'https://itpropartners.com/job?free_word=Django+Python+API'],

        ['id' => 11, 'title' => 'ECバックエンド 機能追加（PHP / Laravel 11）',
            'role' => 'バックエンド', 'style' => 'hybrid', 'prefecture' => '愛知県', 'city' => '名古屋市', 'station' => '名古屋',
            'location' => '名古屋・週3出社', 'duration' => '6ヶ月', 'rate' => 68, 'posted' => 4, 'hot' => false,
            'langs' => ['PHP'], 'fws' => ['Laravel', 'REST API'],
            'site_name' => 'クラウドテック',
            'apply_url' => 'https://crowdtech.jp/projects/search/?word=PHP+Laravel'],

        ['id' => 12, 'title' => 'スタートアップ自社PF 開発（Ruby on Rails）',
            'role' => 'バックエンド', 'style' => 'hybrid', 'prefecture' => '東京都', 'city' => '渋谷区', 'station' => '渋谷',
            'location' => '渋谷・週2出社', 'duration' => '長期', 'rate' => 75, 'posted' => 8, 'hot' => false,
            'langs' => ['Ruby'], 'fws' => ['Ruby on Rails', 'GraphQL'],
            'site_name' => 'Wantedly',
            'apply_url' => 'https://www.wantedly.com/projects?query=Ruby+Rails'],

        ['id' => 13, 'title' => 'ERPシステム API 開発（Java / Spring Boot）',
            'role' => 'バックエンド', 'style' => 'onsite', 'prefecture' => '東京都', 'city' => '千代田区', 'station' => '大手町',
            'location' => '大手町・常駐', 'duration' => '長期', 'rate' => 90, 'posted' => 6, 'hot' => false,
            'langs' => ['Java'], 'fws' => ['Spring Boot', 'Docker'],
            'site_name' => 'Indeed Japan',
            'apply_url' => 'https://jp.indeed.com/jobs?q=Java+Spring+Boot+フリーランス'],

        ['id' => 14, 'title' => 'チャットSaaS バックエンド（Node.js / NestJS）',
            'role' => 'バックエンド', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '4ヶ月〜', 'rate' => 82, 'posted' => 2, 'hot' => false,
            'langs' => ['TypeScript'], 'fws' => ['NestJS', 'Fastify', 'Docker'],
            'site_name' => 'Findy Freelance',
            'apply_url' => 'https://findy-code.io/freelance/projects?keyword=NestJS+Node.js'],

        ['id' => 15, 'title' => 'Elixir / Phoenix リアルタイム通信基盤',
            'role' => 'バックエンド', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '3ヶ月', 'rate' => 88, 'posted' => 15, 'hot' => false,
            'langs' => ['Elixir'], 'fws' => ['Phoenix'],
            'site_name' => 'Wantedly',
            'apply_url' => 'https://www.wantedly.com/projects?query=Elixir+Phoenix'],

        /* フルスタック */
        ['id' => 16, 'title' => 'スタートアップ CTO候補（Next.js + FastAPI）',
            'role' => 'フルスタック', 'style' => 'hybrid', 'prefecture' => '東京都', 'city' => '港区', 'station' => '六本木',
            'location' => '東京・週3出社', 'duration' => '長期', 'rate' => 130, 'posted' => 0, 'hot' => true,
            'langs' => ['TypeScript', 'Python'], 'fws' => ['Next.js', 'FastAPI', 'Docker'],
            'site_name' => 'Green',
            'apply_url' => 'https://www.green-japan.com/search?keyword=CTO+フルスタック+Next.js'],

        ['id' => 17, 'title' => 'BtoB SaaS フルスタック開発（React + Rails）',
            'role' => 'フルスタック', 'style' => 'hybrid', 'prefecture' => '大阪府', 'city' => '大阪市', 'station' => '梅田',
            'location' => '大阪・週2出社', 'duration' => '6ヶ月', 'rate' => 85, 'posted' => 3, 'hot' => false,
            'langs' => ['TypeScript', 'Ruby'], 'fws' => ['React', 'Ruby on Rails'],
            'site_name' => 'Offers',
            'apply_url' => 'https://offers.jp/jobs?keyword=フルスタック+React+Rails'],

        ['id' => 18, 'title' => '社内ツール内製開発（Vue 3 + Laravel）',
            'role' => 'フルスタック', 'style' => 'onsite', 'prefecture' => '埼玉県', 'city' => 'さいたま市', 'station' => '大宮',
            'location' => '大宮・常駐', 'duration' => '3ヶ月', 'rate' => 70, 'posted' => 14, 'hot' => false,
            'langs' => ['JavaScript', 'PHP'], 'fws' => ['Vue.js', 'Laravel'],
            'site_name' => 'クラウドテック',
            'apply_url' => 'https://crowdtech.jp/projects/search/?word=Vue+Laravel+フルスタック'],

        /* モバイル */
        ['id' => 19, 'title' => 'iOS ショッピングアプリ（Swift / SwiftUI）',
            'role' => 'モバイル', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '4ヶ月', 'rate' => 88, 'posted' => 2, 'hot' => false,
            'langs' => ['Swift'], 'fws' => ['SwiftUI'],
            'site_name' => 'ITプロパートナーズ',
            'apply_url' => 'https://itpropartners.com/job?free_word=Swift+SwiftUI+iOS'],

        ['id' => 20, 'title' => 'Android アプリ刷新（Kotlin / Jetpack Compose）',
            'role' => 'モバイル', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '5ヶ月', 'rate' => 82, 'posted' => 5, 'hot' => true,
            'langs' => ['Kotlin'], 'fws' => ['Jetpack Compose'],
            'site_name' => 'クラウドテック',
            'apply_url' => 'https://crowdtech.jp/projects/search/?word=Kotlin+Jetpack+Compose+Android'],

        ['id' => 21, 'title' => 'クロスプラットフォームアプリ（Flutter / Dart）',
            'role' => 'モバイル', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '6ヶ月', 'rate' => 78, 'posted' => 1, 'hot' => false,
            'langs' => ['Dart'], 'fws' => ['Flutter', 'Expo'],
            'site_name' => 'Midworks',
            'apply_url' => 'https://midworks.com/projects/?keyword=Flutter+Dart'],

        ['id' => 22, 'title' => '医療アプリ React Native 開発（iOS/Android 両対応）',
            'role' => 'モバイル', 'style' => 'hybrid', 'prefecture' => '東京都', 'city' => '新宿区', 'station' => '新宿',
            'location' => '新宿・週2出社', 'duration' => '長期', 'rate' => 83, 'posted' => 9, 'hot' => false,
            'langs' => ['TypeScript'], 'fws' => ['React Native', 'Expo'],
            'site_name' => 'Findy Freelance',
            'apply_url' => 'https://findy-code.io/freelance/projects?keyword=React+Native+モバイル'],

        /* インフラ / SRE */
        ['id' => 23, 'title' => 'AWS クラウド移行 SRE（Terraform / Kubernetes）',
            'role' => 'インフラ／SRE', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '6ヶ月〜', 'rate' => 105, 'posted' => 1, 'hot' => true,
            'langs' => ['Go', 'Python'], 'fws' => ['Kubernetes', 'Terraform', 'AWS CDK'],
            'site_name' => 'レバテックフリーランス',
            'apply_url' => 'https://freelance.levtech.jp/project/search/?keyword=AWS+Terraform+Kubernetes+SRE'],

        ['id' => 24, 'title' => 'MLOps 基盤構築（Kubernetes / Airflow / Python）',
            'role' => 'インフラ／SRE', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '4ヶ月', 'rate' => 98, 'posted' => 4, 'hot' => false,
            'langs' => ['Python', 'Go'], 'fws' => ['Kubernetes', 'Docker', 'Ansible'],
            'site_name' => 'Midworks',
            'apply_url' => 'https://midworks.com/projects/?keyword=MLOps+Kubernetes+Python'],

        ['id' => 25, 'title' => '大手通信 インフラ設計・DevOps 推進',
            'role' => 'インフラ／SRE', 'style' => 'onsite', 'prefecture' => '東京都', 'city' => '港区', 'station' => '品川',
            'location' => '品川・常駐', 'duration' => '長期', 'rate' => 95, 'posted' => 7, 'hot' => false,
            'langs' => ['Python'], 'fws' => ['Ansible', 'Terraform', 'Docker'],
            'site_name' => 'ITプロパートナーズ',
            'apply_url' => 'https://itpropartners.com/job?free_word=DevOps+インフラ+Terraform'],

        /* データ / AI・ML */
        ['id' => 26, 'title' => '生成AI チャットボット開発（LangChain / FastAPI）',
            'role' => 'データ／AI・ML', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '3ヶ月', 'rate' => 110, 'posted' => 0, 'hot' => true,
            'langs' => ['Python'], 'fws' => ['FastAPI', 'LangChain', 'Docker'],
            'site_name' => 'Offers',
            'apply_url' => 'https://offers.jp/jobs?keyword=LangChain+生成AI+Python+FastAPI'],

        ['id' => 27, 'title' => 'データ基盤構築（dbt / Snowflake / Python）',
            'role' => 'データ／AI・ML', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '6ヶ月', 'rate' => 92, 'posted' => 3, 'hot' => false,
            'langs' => ['Python', 'R'], 'fws' => ['dbt', 'Snowflake'],
            'site_name' => 'Findy Freelance',
            'apply_url' => 'https://findy-code.io/freelance/projects?keyword=dbt+Snowflake+データエンジニア'],

        ['id' => 28, 'title' => '推薦システム ML エンジニア（Python / PyTorch）',
            'role' => 'データ／AI・ML', 'style' => 'remote', 'prefecture' => '東京都', 'city' => '', 'station' => '',
            'location' => 'フルリモート', 'duration' => '長期', 'rate' => 115, 'posted' => 11, 'hot' => true,
            'langs' => ['Python'], 'fws' => ['FastAPI', 'Docker'],
            'site_name' => 'Green',
            'apply_url' => 'https://www.green-japan.com/search?keyword=機械学習+MLエンジニア+Python'],

        ['id' => 29, 'title' => 'Scala / Spark ビッグデータ処理最適化',
            'role' => 'データ／AI・ML', 'style' => 'hybrid', 'prefecture' => '東京都', 'city' => '品川区', 'station' => '品川',
            'location' => '東京・週1出社', 'duration' => '3ヶ月', 'rate' => 100, 'posted' => 6, 'hot' => false,
            'langs' => ['Scala', 'Python'], 'fws' => ['Docker'],
            'site_name' => 'Indeed Japan',
            'apply_url' => 'https://jp.indeed.com/jobs?q=Scala+Spark+データエンジニア'],

        /* PM / PMO */
        ['id' => 30, 'title' => '大手金融 ITプロジェクト PM（アジャイル推進）',
            'role' => 'PM／PMO', 'style' => 'onsite', 'prefecture' => '東京都', 'city' => '千代田区', 'station' => '大手町',
            'location' => '大手町・常駐', 'duration' => '長期', 'rate' => 95, 'posted' => 5, 'hot' => false,
            'langs' => [], 'fws' => [],
            'site_name' => 'Green',
            'apply_url' => 'https://www.green-japan.com/search?keyword=PM+PMO+ITプロジェクト'],

        ['id' => 31, 'title' => 'スタートアップ プロダクトマネージャー（週3〜）',
            'role' => 'PM／PMO', 'style' => 'hybrid', 'prefecture' => '東京都', 'city' => '渋谷区', 'station' => '渋谷',
            'location' => '渋谷・週3出社', 'duration' => '長期', 'rate' => 85, 'posted' => 2, 'hot' => false,
            'langs' => [], 'fws' => [],
            'site_name' => 'Wantedly',
            'apply_url' => 'https://www.wantedly.com/projects?query=プロダクトマネージャー+PM'],
    ];

    /* ─────────────────────────────────────────────────────────────
       都道府県リスト
    ───────────────────────────────────────────────────────────── */
    public const PREFS = [
        '', '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
        '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
        '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
        '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
        '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
        '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
        '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県',
    ];

    /* ─────────────────────────────────────────────────────────────
       アクション: トップ表示
    ───────────────────────────────────────────────────────────── */
    public function index(Request $request)
    {
        return $this->search($request);
    }

    /* ─────────────────────────────────────────────────────────────
       アクション: 検索・フィルタ
    ───────────────────────────────────────────────────────────── */
    public function search(Request $request)
    {
        $q = $request->input('q', '');
        $role = $request->input('role', '');
        $style = $request->input('style', '');
        $langs = array_filter((array) $request->input('langs', []));
        $fws = array_filter((array) $request->input('fws', []));
        $minRate = (int) $request->input('min_rate', 0);
        $pref = $request->input('pref', '');
        $city = $request->input('city', '');
        $station = $request->input('station', '');
        $remoteOk = $request->boolean('remote_ok');
        $sortBy = $request->input('sort', 'new');

        $list = collect(self::ANKEN)->filter(function ($a) use ($q, $role, $style, $langs, $fws, $minRate, $pref, $city, $station, $remoteOk) {

            // キーワード
            if ($q !== '') {
                $hay = implode(' ', array_merge([$a['title'], $a['role'], $a['location'], $a['prefecture'], $a['city'], $a['station'], $a['site_name']], $a['langs'], $a['fws']));
                if (mb_stripos($hay, $q) === false) {
                    return false;
                }
            }

            // 職種
            if ($role !== '' && $a['role'] !== $role) {
                return false;
            }

            // 働き方
            if ($remoteOk && $a['style'] === 'onsite') {
                return false;
            }
            if (! $remoteOk && $style !== '' && $a['style'] !== $style) {
                return false;
            }

            // 言語 (OR)
            if (! empty($langs) && ! array_intersect($langs, $a['langs'])) {
                return false;
            }

            // フレームワーク (OR)
            if (! empty($fws) && ! array_intersect($fws, $a['fws'])) {
                return false;
            }

            // 月額下限
            if ($minRate > 0 && $a['rate'] < $minRate) {
                return false;
            }

            // 都道府県（リモート案件は都道府県フィルタ不要）
            if ($pref !== '' && $a['style'] !== 'remote' && $a['prefecture'] !== $pref) {
                return false;
            }

            // 市区町村
            if ($city !== '' && $a['city'] !== '' && mb_stripos($a['city'], $city) === false) {
                return false;
            }

            // 最寄り駅
            if ($station !== '' && $a['station'] !== '' && mb_stripos($a['station'], $station) === false) {
                return false;
            }

            return true;
        });

        // ソート
        $list = match ($sortBy) {
            'rate-desc' => $list->sortByDesc('rate'),
            'rate-asc' => $list->sortBy('rate'),
            'score' => $list->sortByDesc(fn ($a) => $this->score($a, $langs, $fws)),
            default => $list->sortBy('posted'),
        };

        // 外部サイトURL用キーワード生成
        $kwParts = array_filter(array_merge(
            $q !== '' ? [$q] : [],
            $langs,
            $fws,
            $role !== '' ? [$role] : [],
        ));
        $kw = urlencode(mb_substr(implode(' ', $kwParts), 0, 100));

        $tailKw = 'フリーランス 案件 求人 エンジニア';
        $headParts = array_values(array_filter(array_merge(
            $langs,
            $fws,
            $q !== '' ? [$q] : [],
            $pref !== '' ? [$pref] : [],
        )));
        if ($headParts === []) {
            $searchQuery = 'ITエンジニア フリーランス 案件 求人 2026';
        } else {
            $fullJoin = implode(' ', array_merge($headParts, [$tailKw]));
            if (mb_strlen($fullJoin) <= 100) {
                $searchQuery = $fullJoin;
            } else {
                $tail = ' '.$tailKw;
                $budget = max(24, 100 - mb_strlen($tail));
                $headJoin = implode(' ', $headParts);
                if (mb_strlen($headJoin) <= $budget) {
                    $searchQuery = $headJoin.$tail;
                } else {
                    $searchQuery = rtrim(mb_substr($headJoin, 0, $budget)).$tail;
                }
                if (mb_strlen($searchQuery) > 100) {
                    $searchQuery = mb_substr($searchQuery, 0, 100);
                }
            }
        }

        $googleFallback = [];
        $core = array_values(array_filter(array_merge($langs, $fws)));
        if ($core !== []) {
            $googleFallback[] = mb_substr(implode(' ', array_merge($core, ['求人', 'エンジニア', '案件'])), 0, 100);
            $googleFallback[] = mb_substr(implode(' ', array_merge($core, ['フリーランス'])), 0, 100);
            $googleFallback[] = mb_substr(implode(' ', $core).' 採用 エンジニア', 0, 100);
            $googleFallback[] = mb_substr(implode(' ', $core).' 求人', 0, 100);
            if ($minRate > 0) {
                $googleFallback[] = mb_substr(implode(' ', $core)." 単価{$minRate}万円 求人", 0, 100);
            }
        }
        if ($pref !== '') {
            $googleFallback[] = mb_substr($pref.' IT 求人 エンジニア フリーランス', 0, 100);
        }
        $googleFallback[] = 'IT エンジニア 求人 案件 2026';
        $googleFallback = array_values(array_unique(array_filter(
            $googleFallback,
            fn ($x) => $x !== '' && $x !== $searchQuery
        )));

        $googleConfigured = is_string(config('services.google.cse_key'))
            && config('services.google.cse_key') !== ''
            && is_string(config('services.google.cse_cx'))
            && config('services.google.cse_cx') !== '';

        $googleResults = $googleConfigured
            ? $this->googleCse->search($searchQuery, $googleFallback, GoogleCustomSearchService::MAX_RESULTS)
            : [];

        if ($googleResults === [] && $googleConfigured) {
            $googleResults = $this->buildExtSiteFallbackCards($langs, $fws, $q, $role);
        }

        return view('anken.index', [
            'anken' => $list->values(),
            'langs' => self::LANGS,
            'frameworks' => self::FRAMEWORKS,
            'prefs' => self::PREFS,
            'ext_sites' => self::EXT_SITES,
            'kw' => $kw,
            'roles' => ['フロントエンド', 'バックエンド', 'フルスタック', 'モバイル', 'インフラ／SRE', 'データ／AI・ML', 'PM／PMO'],
            'google_results' => $googleResults,
            'search_query' => $searchQuery,
            'google_cse_configured' => $googleConfigured,
            // 検索値を view に渡して入力保持
            'input' => [
                'q' => $q,
                'role' => $role,
                'style' => $style,
                'langs' => $langs,
                'fws' => $fws,
                'min_rate' => $minRate,
                'annual' => $minRate * 12,
                'pref' => $pref,
                'city' => $city,
                'station' => $station,
                'remote_ok' => $remoteOk,
                'sort' => $sortBy,
            ],
        ]);
    }

    /* ─────────────────────────────────────────────────────────────
       マッチングスコア算出（高いほど関連度が高い）
    ───────────────────────────────────────────────────────────── */
    /**
     * Google CSE が0件のとき、外部求人サイトの検索URLをカード表示（純粋PHP版と同等）
     *
     * @return array<int, array{title: string, snippet: string, url: string, domain: string, is_fallback: bool}>
     */
    private function buildExtSiteFallbackCards(array $langs, array $fws, string $q, string $role): array
    {
        $kwParts = array_filter(array_merge(
            $q !== '' ? [$q] : [],
            $langs,
            $fws,
            $role !== '' ? [$role] : [],
        ));
        $kwSfx = urlencode(mb_substr(implode(' ', $kwParts), 0, 100));
        $label = implode(' ', array_merge($langs, $fws));
        if ($label === '') {
            $label = '条件';
        }
        $out = [];
        foreach (self::EXT_SITES as $site) {
            $out[] = [
                'title' => $site['name'].' で「'.$label.'」を検索',
                'snippet' => $site['desc'],
                'url' => $site['url'].$kwSfx,
                'domain' => parse_url($site['url'], PHP_URL_HOST) ?: '',
                'is_fallback' => true,
            ];
        }

        return array_slice($out, 0, min(GoogleCustomSearchService::MAX_RESULTS, count($out)));
    }

    private function score(array $a, array $langs, array $fws): int
    {
        $sc = 0;
        foreach ($langs as $l) {
            if (in_array($l, $a['langs'], true)) {
                $sc += 3;
            }
        }
        foreach ($fws as $f) {
            if (in_array($f, $a['fws'], true)) {
                $sc += 2;
            }
        }
        if ($a['hot']) {
            $sc += 1;
        }
        if ($a['posted'] <= 1) {
            $sc += 1;
        }

        return $sc;
    }
}
