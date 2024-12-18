<?php

namespace App\Repositories\Article;

use App\Models\Article;

class ArticleRepository
{
    public function paginateArticles(int $limit)
    {
        return Article::orderBy('published_at', 'desc')->paginate($limit);
    }

    public function findArticleById($id)
    {
        return Article::findOrFail($id);
    }

    public function searchArticles(array $filters, int $limit)
    {
        $query = Article::query();

        if (!empty($filters['keyword'])) {
            $query->where('title', 'like', "%{$filters['keyword']}%");
        }

        if (!empty($filters['date'])) {
            $query->whereDate('published_at', $filters['date']);
        }

        if (!empty($filters['url'])) {
            $query->where('url', $filters['url']);
        }

        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        return $query->orderBy('published_at', 'desc')->paginate($limit);
    }
}
