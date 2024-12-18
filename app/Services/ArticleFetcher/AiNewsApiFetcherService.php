<?php
namespace App\Services\ArticleFetcher;

use Illuminate\Support\Facades\Http;
use App\Services\Abstract\ArticleFetcherService;
use Illuminate\Support\Facades\Log;
class AiNewsApiFetcherService extends ArticleFetcherService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.ainewsapi.api_key');
        $this->baseUrl = 'https://eventregistry.org/api/v1/article/getArticles';
    }
    public function fetchArticles(): array
    {
        try {
            // Fetch articles from the API
            $response = Http::get($this->baseUrl, [
                'apiKey' => $this->apiKey,
                'resultType' => 'articles',
                'lang' => 'eng',
                'articlesSortBy' => 'date',
                'articlesCount' => 10,
            ]);

            // Check if the response is successful and contains expected data
            if (!$response->successful() || !isset($response['articles']['results'])) {
                throw new \Exception('Invalid API response structure.');
            }

            // Extract articles
            $articles = $response->json()['articles']['results'];

            // Format the articles
            $formattedArticles = array_map(function ($article) {
                return [
                    'title' => $article['title'] ?? 'Untitled',
                    'content' => $article['body'] ?? '',
                    'url' => $article['url'] ?? '#',
                    'publishedAt' => $article['dateTimePub'] ?? now()->toDateTimeString(),
                ];
            }, $articles);

            // Store articles in the database
            $this->storeArticles($formattedArticles, 'ainewsapi');

            return $formattedArticles;
        } catch (\Throwable $e) {
            // Log the error for debugging purposes
            Log::error('Failed to fetch articles: ' . $e->getMessage());

            return [];
        }
    }
}
