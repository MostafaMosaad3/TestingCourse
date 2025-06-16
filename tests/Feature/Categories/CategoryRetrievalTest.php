<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryRetrievalTest extends TestCase
{
    use  RefreshDatabase;
    /**
     * Test that categories page opened successfully.
     */
    public function test_if_categories_page_open_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/categories');


        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
        $response->assertSeeText('Add New Category');
    }

    /**
     * Test that all categories can be retrieved successfully
     */

    public function test_if_categories_page_retrieve_date_successfully(): void
    {
        Category::factory()->count(5)->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/categories');
        $response->assertStatus(200);
        $response->assertViewHas('categories' , function($categories){
            return $categories->count() === 5 ;
        });
    }

    /**
     * Test pagination works as expected
     */

    public function test_if_categories_page_pagination_work(): void
    {
        Category::factory()->count(15)->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/categories');

        $response->assertViewHas('categories' , function($categories){
            return $categories->count() === 10 ;
        });

        $response = $this->actingAs($user)->get('/categories?page=2');
        $response->assertViewHas('categories' , function($categories){
            return $categories->count() === 5 ;
        });

    }
}
