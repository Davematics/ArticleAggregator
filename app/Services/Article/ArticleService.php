<?php

namespace App\Services\Article;

use App\Repositories\Article\ArticleRepository;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getPaginatedArticles(int $limit)
    {
        return $this->articleRepository->paginateArticles($limit);
    }

    public function getArticleById($id)
    {
        return $this->articleRepository->findArticleById($id);
    }

    public function searchArticles(array $filters, int $limit)
    {
        return $this->articleRepository->searchArticles($filters, $limit);
    }
}
