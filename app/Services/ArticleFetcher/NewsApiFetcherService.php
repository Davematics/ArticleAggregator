<?php
namespace App\Services\ArticleFetcher;

use Illuminate\Support\Facades\Http;
use App\Services\Abstract\ArticleFetcherService;
use Illuminate\Support\Facades\Log;
class NewsApiFetcherService extends ArticleFetcherService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.guardian.api_key');
        $this->baseUrl = 'https://newsapi.org/v2/top-headlines';
    }
    public function fetchArticles(): array
{
    try {
        // Fetch articles from the API
        $response = Http::get($this->baseUrl, [
            'apiKey' => $this->apiKey,
            'language' => 'en',
        ]);

        // Check if the response is successful and contains expected data
        if (!$response->successful() || !isset($response['articles'])) {
            throw new \Exception('Invalid API response structure.');
        }

        // Extract articles
        $articles = $response->json()['articles'];

        // Store articles in the database
        $this->storeArticles($articles, 'newsapi');

        return $articles;

    } catch (\Throwable $e) {
        // Log the error for debugging purposes
        Log::error('Failed to fetch articles: ' . $e->getMessage());

       
        return [];
    }
}

}
