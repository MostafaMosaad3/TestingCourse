<?php

namespace Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCreatingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp():void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    /**
     * Test category create page rendered successfully .
     */
    public function test_category_create_page_rendered_successfully()  :void
    {
        $response = $this->get('/categories/create');

        $response->assertStatus(200);
        $response->assertViewIs('categories.create');

    }


    /**
     * Test category can be created successfully
     */
    public function test_category_can_be_created_successfully():void
    {
        $category = Category::factory()->make();

        $response = $this->post('/categories', $category->toArray()) ;

        $response->assertStatus(302);
        $response->assertSessionHas('success' , 'Category Created Successfully');

        $this->assertDatabaseHas('categories' , $category->toArray());
    }


    /**
     * Test category name is required
     */

    public function test_category_name_is_required():void
    {
//        $category = Category::factory()->make(['name' => null]);
        $category = [
            'description' => 'testing'
        ];

        $response = $this->post('/categories', $category);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('categories' , $category);
    }


    /**
     * test category name must be at least 3 characters long
     */

    public function test_category_name_must_be_minimum_3_characters():void
    {
        $category = [
            'name' => str_repeat('a' , 2) ,
            'description' => 'testing'
        ];

        $response = $this->post('/categories' , $category);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('categories' , $category);
    }


    /**
     * test category name must be at most 255 characters long
     */

    public function test_category_name_must_be_mostly_255_characters():void
    {
        $category = [
            'name' => str_repeat('a' , 256) ,
            'description' => 'testing'
        ];

        $response = $this->post('/categories' , $category);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('categories' , $category);
    }







}
