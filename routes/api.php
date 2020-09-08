<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;


JsonApi::register('v1')->routes(function ($api){
    $api->resource('articles')->relationships(function ($api){
        $api->hasOne('authors');
    });
    $api->resource('authors')->only('index','read');
});
