<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Google Custom Search API — PHP版 index.php と同等のページング・フォールバック・キャッシュ。
 */
class GoogleCustomSearchService
{
    public const MAX_RESULTS = 30;

    public function search(string $primaryQuery, array $fallbackQueries = [], int $targetTotal = self::MAX_RESULTS): array
    {
        $key = config('services.google.cse_key');
        $cx = config('services.google.cse_cx');
        if (! is_string($key) || $key === '' || ! is_string($cx) || $cx === '') {
            return [];
        }

        $fb = array_values(array_unique(array_filter($fallbackQueries, fn ($x) => is_string($x) && $x !== '')));
        $cacheKey = 'gse_v2_'.md5($primaryQuery."\0".implode("\0", $fb));

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey, []);
        }

        $targetTotal = max(1, min(30, $targetTotal));
        $seen = [];
        $merged = [];

        $this->collectQuery($primaryQuery, $targetTotal, $seen, $merged, $key, $cx);

        if ($merged === []) {
            foreach ($fb as $q) {
                $this->collectQuery($q, $targetTotal, $seen, $merged, $key, $cx);
                if ($merged !== []) {
                    break;
                }
            }
        }

        if ($merged !== []) {
            Cache::put($cacheKey, $merged, now()->addDays(7));
        }

        return $merged;
    }

    /**
     * @param  array<string, true>  $seen
     * @param  array<int, array<string, string>>  $merged
     */
    private function collectQuery(string $query, int $targetTotal, array &$seen, array &$merged, string $key, string $cx): void
    {
        $start = 1;
        while (count($merged) < $targetTotal && $start <= 91) {
            $need = min(10, $targetTotal - count($merged));
            $page = $this->fetchPage($query, $start, $need, $key, $cx);
            if ($page === []) {
                break;
            }
            foreach ($page as $it) {
                $u = $it['url'] ?? '';
                if ($u === '' || isset($seen[$u])) {
                    continue;
                }
                $seen[$u] = true;
                $merged[] = $it;
                if (count($merged) >= $targetTotal) {
                    return;
                }
            }
            if (count($page) < $need) {
                break;
            }
            $start += 10;
        }
    }

    /**
     * @return array<int, array{title: string, snippet: string, url: string, domain: string}>
     */
    private function fetchPage(string $query, int $start1Based, int $num, string $key, string $cx): array
    {
        $num = max(1, min(10, $num));
        $start1Based = max(1, min(91, $start1Based));

        try {
            $response = Http::timeout(8)->get('https://www.googleapis.com/customsearch/v1', [
                'key' => $key,
                'cx' => $cx,
                'q' => $query,
                'num' => $num,
                'start' => $start1Based,
                'gl' => 'jp',
            ]);
        } catch (\Throwable) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $data = $response->json();
        if (! empty($data['error']) || empty($data['items']) || ! is_array($data['items'])) {
            return [];
        }

        return array_map(function ($it) {
            $link = $it['link'] ?? '';

            return [
                'title' => $it['title'] ?? '',
                'snippet' => $it['snippet'] ?? '',
                'url' => $link,
                'domain' => parse_url($link, PHP_URL_HOST) ?: '',
            ];
        }, $data['items']);
    }
}
