<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_update_articles()
    {
        $article = Article::factory()->create();
        $this->jsonApi()
            ->patch(route('api.v1.articles.update', $article))
            ->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_update_their_articles()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->user);

        $this->jsonApi()->withData([
                    'type' => 'articles',
                    'id' => $article->getRouteKey(),
                    'attributes' => [
                        'title' => 'Title changed',
                        'slug' => 'title-changed',
                        'content' => 'Content changed'
                    ]
            ])
            ->patch(route('api.v1.articles.update', $article))
            ->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'title' => 'Title changed',
            'slug' => 'title-changed',
            'content' => 'Content changed'
        ]);
    }

    /** @test */
    public function authenticated_users_can_update_others_articles()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($user = User::factory()->create());

        $this->jsonApi()->withData([
                    'type' => 'articles',
                    'id' => $article->getRouteKey(),
                    'attributes' => [
                        'title' => 'Title changed',
                        'slug' => 'title-changed',
                        'content' => 'Content changed'
                    ]
            ])
            ->patch(route('api.v1.articles.update', $article))
            ->assertStatus(403);

        $this->assertDatabaseMissing('articles', [
            'title' => 'Title changed',
            'slug' => 'title-changed',
            'content' => 'Content changed'
        ]);
    }

    /** @test */
    public function can_update_the_title_only()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->user);

        $this->jsonApi()->withData([
                    'type' => 'articles',
                    'id' => $article->getRouteKey(),
                    'attributes' => [
                        'title' => 'Title changed',
                    ]
            ])
            ->patch(route('api.v1.articles.update', $article))
            ->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'title' => 'Title changed',
        ]);
    }

    /** @test */
    public function can_update_the_slug_only()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->user);

        $this->jsonApi()->withData([
                    'type' => 'articles',
                    'id' => $article->getRouteKey(),
                    'attributes' => [
                        'slug' => 'slug-changed',
                    ]
            ])
            ->patch(route('api.v1.articles.update', $article))
            ->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'slug' => 'slug-changed',
        ]);
    }
}
