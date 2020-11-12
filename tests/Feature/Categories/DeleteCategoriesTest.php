<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_delete_categories()
    {
        $article = Category::factory()->create();

        $this->jsonApi()->delete(route('api.v1.categories.delete',$article))
            ->assertStatus(401); //Unauthenticated

    }

    /** @test */
    public function authenticated_users_can_delete_their_categories()
    {
        $article = Category::factory()->create();

        Sanctum::actingAs($article->user);

        $this->jsonApi()->delete(route('api.v1.categories.delete',$article))
            ->assertStatus(204); //Not content

    }

    /** @test */
    public function authenticated_users_cannot_delete_others_categories()
    {
        $article = Category::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()->delete(route('api.v1.categories.delete',$article))
            ->assertStatus(403); //Unauthorized

    }
}
