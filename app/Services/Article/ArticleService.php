<?php

namespace App\Services\Article;

use App\Repositories\Article\ArticleRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getPaginatedArticles(int $limit): LengthAwarePaginator
    {
        return $this->articleRepository->paginateArticles($limit);
    }

    public function getArticleById($id): Collection
    {
        return $this->articleRepository->findArticleById($id);
    }

    public function searchArticles(array $filters, int $limit): LengthAwarePaginator
    {
        return $this->articleRepository->searchArticles($filters, $limit);
    }
}
