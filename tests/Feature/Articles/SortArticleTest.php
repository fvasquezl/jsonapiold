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
        $article1 = Article::factory()->create(['title'=>'C Title']);
        $article2 = Article::factory()->create(['title'=>'B Title']);
        $article3 = Article::factory()->create(['title'=>'A Title']);
        $url = route('api.v1.articles.index',['sort'=> 'title']);

        $this->jsonApi()->get($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title',
        ]);
    }


    /** @test */
    public function it_can_sort_articles_by_title_desc()
    {
        $article1 = Article::factory()->create(['title'=>'C Title']);
        $article2 = Article::factory()->create(['title'=>'B Title']);
        $article3 = Article::factory()->create(['title'=>'A Title']);
        $url = route('api.v1.articles.index',['sort'=>'-title']);

        $this->jsonApi()->get($url)->assertSeeInOrder([
            'C Title',
            'B Title',
            'A Title',
        ]);
    }


    /** @test */
    public function it_can_sort_articles_by_title_and_content()
    {
        $article1 = Article::factory()->create([
            'title'=>'C Title',
            'content'=> 'B content'
            ]);
        $article2 = Article::factory()->create([
            'title'=>'B Title',
            'content'=> 'C content'
            ]);
        $article3 = Article::factory()->create([
            'title'=>'A Title',
            'content'=> 'D content'
            ]);

        \DB::listen(function ($db){
            dump($db->sql);
        });

        $url = route('api.v1.articles.index').'?sort=title,-content';


        $this->jsonApi()->get($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title',
        ]);


        $url = route('api.v1.articles.index').'?sort=-content,title';

        $this->jsonApi()->get($url)->assertSeeInOrder([
            'D content',
            'C content',
            'B content',
        ]);
    }


    /** @test */
    public function it_cannot_sort_articles_by_unknown_fields()
    {
        $article1 = Article::factory()->times(3)->create();

        $url = route('api.v1.articles.index').'?sort=unknown';

        $this->jsonApi()->get($url)->assertStatus(400);

    }
}
