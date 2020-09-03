<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::applyFilters()->applySorts()->jsonPaginate();
        return ArticleCollection::make($articles);
    }

    public function show(Article $article)
    {
        return ArticleResource::make($article);
    }
}
