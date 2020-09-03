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
        $query = Article::query();
        foreach (request('filter', []) as $filter => $value) {
            if($filter === 'year'){
                $query->whereYear('created_at',$value);
            }elseif($filter === 'month'){
                $query->whereMonth('created_at',$value);
            }else{
                $query->where($filter, 'LIKE', "%{$value}%");
            }
        }
        $articles = $query->applySorts()->jsonPaginate();

        return ArticleCollection::make($articles);
    }

    public function show(Article $article)
    {
        return ArticleResource::make($article);
    }
}
