<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryRetrievingTest extends TestCase
{
    use  RefreshDatabase;

//    protected $user ;

    protected function setUp(): void
    {
        parent::setUp();
//        $this->user = User::factory()->create();
        $this->actingAs(User::factory()->create());
    }

    /**
     * Test that categories page opened successfully.
     */
    public function test_if_categories_page_open_successfully(): void
    {
//        $user = User::factory()->create();
//        $user = $this->user;

//        $response = $this->actingAs($user)->get('/categories');
        $response = $this->get('/categories');


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
//        $user = User::factory()->create();
//        $user = $this->user;

//        $response = $this->actingAs($user)->get('/categories');
        $response = $this->get('/categories');

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
        Category::factory()->count(10)->create();
//        $user = User::factory()->create();
//        $user = $this->user;

//        $response = $this->actingAs($user)->get('/categories');
        $response = $this->get('/categories');

        $response->assertViewHas('categories' , function($categories){
            return $categories->count() === 10 ;
        });


//        $response = $this->actingAs($user)->get('/categories?page=2');
        $response = $this->get('/categories?page=2');
        $response->assertViewHas('categories' , function($categories){
            return $categories->count() === 5 ;
        });

    }


    /**
     * test if categories show page contains the right content
     */

    public function test_if_categories_show_page_contains_the_right_content(): void
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.show' , $category));

        $response->assertStatus(200);
        $response->assertViewIs('categories.show');
        $response->assertViewHas('category' , $category) ;
        $response->assertSeeText($category->name);
    }
}
