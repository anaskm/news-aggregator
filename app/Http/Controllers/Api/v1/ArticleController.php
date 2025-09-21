<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchArticlesRequest;
use App\Services\News\Contracts\ArticleRepository;

class ArticleController extends Controller
{
    // GET /articles
    public function index(SearchArticlesRequest $request, ArticleRepository $articleRepository)
    {
        $validated = $request->validated();

        $query = $validated['query'] ?? '';
        $provider = $validated['source'] ?? null;
        $category = $validated['category'] ?? null;
        $page = $validated['page'] ?? 1;
        $perPage = $validated['limit'] ?? 10;

        $articles = $articleRepository->search(
            $query,
            $provider,
            $category,
            $page,
            $perPage
        );

        return response()->json($articles->toArray());
    }

    // GET /articles/{id}
    public function show($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find((int) $id);
        
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }
}
