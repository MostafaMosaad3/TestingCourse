<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiCategoryTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

//        $this->actingAs(
//            User::factory()->create()
//        );

        if($this->name() != 'test_prevent_unauthenticated_users_to_list_Category')
        {
            Sanctum::actingAs(
                User::factory()->create(),
            );
        }
    }

//    /**
//     * authenticate user via sanctum:actingAs
//     */
//    protected function authenticateUser()
//    {
//        Sanctum::actingAs(
//            User::factory()->create(),
//        );
//    }

    /**
     * test prevent unauthenticated users
     */
    public function test_prevent_unauthenticated_users_to_list_Category()
    {
        $response = $this->getJson('/api/categories');

        $response
            ->assertStatus(401);
    }


    /**
     * test listing categories
     */
    public function test_listing_categories():void
    {
//        $this->authenticateUser();
        Category::factory()->count(5)->create();

        $response = $this->getJson('/api/categories');

        $response
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');

    }


    /**
     * test adding category
     */
    public function test_adding_new_category():void
    {
        $category = Category::factory()->make();

        $response = $this->postJson('/api/categories', $category->toArray());

        $response
            ->assertStatus(201)
            ->assertJsonFragment(['name' => $category->name]);
    }


    /**
     * test showing category
     */
    public function test_showing_category():void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['name' => $category->name]);
    }


    /**
     * test updating category
     */
    public function test_updating_category():void
    {
        $category = Category::factory()->create();
        $updatedCategory = [
            'name' => 'updated name',
            'description' => 'updated description',
        ];

        $response = $this->putJson('/api/categories/'.$category->id, $updatedCategory) ;

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['name' => $updatedCategory['name']]);

    }


    /**
     * test deleting category
     */
    public function test_deleting_category():void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson('/api/categories/'.$category->id);

        $response
            ->assertStatus(204);

    }
}
