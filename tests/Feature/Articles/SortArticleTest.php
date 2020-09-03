<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SortArticleTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function it_can_sort_articles_by_title_asc()
    {
        $article1 = factory(Article::class)->create(['title'=>'C Title']);
        $article2 = factory(Article::class)->create(['title'=>'B Title']);
        $article3 = factory(Article::class)->create(['title'=>'A Title']);
        $url = route('api.v1.articles.index',['sort'=> 'title']);

        $this->getJson($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title',
        ]);
    }


    /** @test */
    public function it_can_sort_articles_by_title_desc()
    {
        $article1 = factory(Article::class)->create(['title'=>'C Title']);
        $article2 = factory(Article::class)->create(['title'=>'B Title']);
        $article3 = factory(Article::class)->create(['title'=>'A Title']);
        $url = route('api.v1.articles.index',['sort'=>'-title']);

        $this->getJson($url)->assertSeeInOrder([
            'C Title',
            'B Title',
            'A Title',
        ]);
    }


    /** @test */
    public function it_can_sort_articles_by_title_and_content()
    {
        $article1 = factory(Article::class)->create([
            'title'=>'C Title',
            'content'=> 'B content'
            ]);
        $article2 = factory(Article::class)->create([
            'title'=>'B Title',
            'content'=> 'C content'
            ]);
        $article3 = factory(Article::class)->create([
            'title'=>'A Title',
            'content'=> 'D content'
            ]);

        \DB::listen(function ($db){
            dump($db->sql);
        });

        $url = route('api.v1.articles.index').'?sort=title,-content';


        $this->getJson($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title',
        ]);


        $url = route('api.v1.articles.index').'?sort=-content,title';

        $this->getJson($url)->assertSeeInOrder([
            'D content',
            'C content',
            'B content',
        ]);
    }


    /** @test */
    public function it_cannot_sort_articles_by_unknown_fields()
    {
        $article1 = factory(Article::class)->times(3)->create();

        $url = route('api.v1.articles.index').'?sort=unknown';

        $this->getJson($url)->assertStatus(400);

    }
}
