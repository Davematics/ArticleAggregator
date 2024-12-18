<?php

namespace App\Services\Abstract;
use App\Models\Article;
abstract class ArticleFetcherService
{
    abstract public function fetchArticles(): array;

    protected function storeArticles(array $articles, string $source)
    {
        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title' => $article['title'],
                    'content' => $article['content'],
                    'source' => $source,
                    'published_at' => $article['publishedAt'] ?? now(),
                ]
            );
        }
    }
}
