<?php
namespace App\Services\ArticleFetcher;

use Illuminate\Support\Facades\Http;
use App\Services\Abstract\ArticleFetcherService;
use Illuminate\Support\Facades\Log;

class GuardianFetcherService extends ArticleFetcherService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.guardian.api_key');
        $this->baseUrl = 'https://content.guardianapis.com/search';
    }
    public function fetchArticles(): array
    {
        // Fetch articles from the API
        try {
            $response = Http::get($this->baseUrl, [
                'api-key' => config($this->apiKey),
                'show-fields' => 'all',
            ]);

            // Check if the response is successful and contains expected data
            if (!$response->successful() || !isset($response['response']['results'])) {
                throw new \Exception('Invalid API response structure.');
            }

            // Extract articles
            $articles = $response->json()['response']['results'];

            $formattedArticles = array_map(function ($article) {
                return [
                    'title' => $article['webTitle'] ?? 'Untitled',
                    'content' => $article['fields']['body'] ?? '',
                    'url' => $article['webUrl'],
                    'publishedAt' => $article['webPublicationDate'],
                ];
            }, $articles);

            // Store articles in the database
            $this->storeArticles($formattedArticles, 'guardian');

            return $formattedArticles;
        } catch (\Throwable $e) {
            // Log the error for debugging purposes
            Log::error('Failed to fetch articles: ' . $e->getMessage());

            return [];
        }
    }
}
