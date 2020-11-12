<?php

namespace Tests\Feature\Categories;


use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ListCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_single_category()
    {
        $category = Category::factory()->create();

        $response = $this->jsonApi()->get(route('api.v1.categories.read', $category));

        $response->assertJson([
            'data' => [
                'type' => 'categories',
                'id' => (string)$category->getRouteKey(),
                'attributes' => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'links' => [
                    'self' => route('api.v1.categories.read', $category)
                ]
            ]
        ]);
    }
    /** @test */
    public function can_fetch_all_category()
    {
        $category = Category::factory()->times(3)->create();

        $response = $this->jsonApi()->get(route('api.v1.categories.index'));

        $response->assertJsonFragment([
                'type' => 'categories',
                'id' => (string)$category[0]->getRouteKey(),
                'attributes' => [
                    'name' => $category[0]->name,
                    'slug' => $category[0]->slug,
                ],
                'links' => [
                    'self' => route('api.v1.categories.read', $category[0])
                ]
        ]);
    }
}
