<?php

use Illuminate\Support\Facades\Route;

Route::get('articles/{article}','ArticleController@show')->name('api.v1.articles.show');
Route::get('articles','ArticleController@index')->name('api.v1.articles.index');
