<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;


JsonApi::register('v1')->routes(function ($api){
    $api->resource('articles');
    $api->resource('authors')->only('index','read');
});
