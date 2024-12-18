<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Services\Article\ArticleService;
use App\Http\Requests\ArticleSearchRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request)
    {
        $articles = $this->articleService->getPaginatedArticles($request->get('limit', 10));
        return ArticleResource::collection($articles);
    }

    public function show($id)
    {
        $article = $this->articleService->getArticleById($id);
        return new ArticleResource($article);
    }

    public function search(ArticleSearchRequest $request)
    {
        $filters = $request->only(['keyword', 'date', 'url', 'source']);
        $articles = $this->articleService->searchArticles($filters, $request->get('limit', 10));
        return ArticleResource::collection($articles);
    }
}
