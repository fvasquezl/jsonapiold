<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_articles_by_title()
    {
        Article::factory()->create([
            'title' => 'Learn Laravel from scratch'
        ]);

        Article::factory()->create([
            'title' => 'Other Article'
        ]);

        $url = route('api.v1.articles.index', ['filter[title]' => 'Laravel']);

        $this->jsonApi()->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Learn Laravel from scratch')
            ->assertDontSee('Other Artifle');
    }

    /** @test */
    public function can_filter_articles_by_content()
    {
        Article::factory()->create([
            'content' => '<div>Learn Laravel from scratch</div>'
        ]);

        Article::factory()->create([
            'content' => '<div>Other Article</div>'
        ]);

        $url = route('api.v1.articles.index', ['filter[content]' => 'Laravel']);

        $this->jsonApi()->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Learn Laravel from scratch')
            ->assertDontSee('Other Artifle');
    }

    /** @test */
    public function can_filter_articles_by_year()
    {
        Article::factory()->create([
            'title' => 'Article from 2020',
            'created_at' => now()->year(2020)
        ]);

        Article::factory()->create([
            'title' => 'Article from 2021',
            'created_at' => now()->year(2021)
        ]);

        $url = route('api.v1.articles.index', ['filter[year]' => 2020]);

        $this->jsonApi()->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Article from 2020')
            ->assertDontSee('Article from 2021');
    }

    /** @test */
    public function can_filter_articles_by_month()
    {
        Article::factory()->create([
            'title' => 'Article from February',
            'created_at' => now()->month(2)
        ]);
        Article::factory()->create([
            'title' => 'Another Article from February',
            'created_at' => now()->month(2)
        ]);

        Article::factory()->create([
            'title' => 'Article from January',
            'created_at' => now()->month(1)
        ]);

        $url = route('api.v1.articles.index', ['filter[month]' => 2]);

        $this->jsonApi()->get($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Article from February')
            ->assertSee('Another Article from February')
            ->assertDontSee('Article from January');
    }

    /** @test */
    public function cannot_filter_articles_by_unknown_filters()
    {
        Article::factory()->create();

        $url = route('api.v1.articles.index', ['filter[unknown]' => 2]);

        $this->jsonApi()->get($url)->assertStatus(400);
    }

    /** @test */
    public function can_search_articles_by_title_and_content()
    {
        Article::factory()->create([
            'title' => 'Article from Aprendible',
            'content' => 'Content'
        ]);
        Article::factory()->create([
            'title' => 'Another Article',
            'content' => 'Content Aprendible...'
        ]);

        Article::factory()->create([
            'title' => 'Title2',
            'content' => 'Content 2'
        ]);

        $url = route('api.v1.articles.index', ['filter[search]' => 'Aprendible']);

        $this->jsonApi()->get($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Article from Aprendible')
            ->assertSee('Another Article')
            ->assertDontSee('Title2');
    }

    /** @test */
    public function can_search_articles_by_title_and_content_with_multiple_terms()
    {
        Article::factory()->create([
            'title' => 'Article from Aprendible',
            'content' => 'Content'
        ]);

        Article::factory()->create([
            'title' => 'Another Article',
            'content' => 'Content Aprendible...'
        ]);

        Article::factory()->create([
            'title' => 'Another Laravel Article',
            'content' => 'Content...'
        ]);

        Article::factory()->create([
            'title' => 'Title2',
            'content' => 'Content 2'
        ]);


        $url = route('api.v1.articles.index', ['filter[search]' => 'Aprendible Laravel']);

        $this->jsonApi()->get($url)
            ->assertJsonCount(3, 'data')
            ->assertSee('Article from Aprendible')
            ->assertSee('Another Article')
            ->assertSee('Another Laravel Article')
            ->assertDontSee('Title2');
    }

    /** @test */
    public function can_filter_articles_by_categories()
    {
        Article::factory()
            ->times(2)
            ->create();

        $category = Category::factory()
            ->hasArticles(2)
            ->create();

        $this->jsonApi()
            ->filter(['categories'=> $category->getRouteKey()])
            ->get(route('api.v1.articles.index'))
            ->assertJsonCount(2, 'data')
        ;
    }

    /** @test */
    public function can_filter_articles_by_multiple_categories()
    {
        Article::factory()->times(2)->create();

        $category = Category::factory()->hasArticles(2)->create();
        $category2 = Category::factory()->hasArticles(3)->create();

        $this->jsonApi()
            ->filter(['categories'=> $category->getRouteKey().','.$category2->getRouteKey()])
            ->get(route('api.v1.articles.index'))
            ->assertJsonCount(5, 'data')
        ;
    }
}
