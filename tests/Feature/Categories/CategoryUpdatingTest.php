<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryUpdatingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    /**
     * Test category update page rendered successfully .
     */
    public function test_category_update_page_rendered_successfully()  :void
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.edit', $category));

        $response
            ->assertStatus(200)
            ->assertViewIs('categories.edit')
            ->assertViewHas('category', $category)
            ->assertSee($category->name)
            ->assertSee($category->description);

    }


    /**
     * Test category can be updated successfully
     */
    public function test_category_can_be_updated_successfully():void
    {
        $category = Category::factory()->create();
        $updatedCategory = [
            'name' => 'updated name',
            'description' => 'updated description',
        ];

        $response = $this->patch(route('categories.update', $category), $updatedCategory);

        $response
            ->assertStatus(302)
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Category updated successfully');

        $this->assertDatabaseMissing('categories', $category->toArray())
            ->assertDatabaseHas('categories', $updatedCategory);

    }


    /**
     * Test category name is required
     */

    public function test_category_name_is_required():void
    {
        $category = Category::factory()->create();
        $updatedCategory = [
            'name' => '',
            'description' => 'updated description',
        ];

        $response = $this->patch(route('categories.update', $category), $updatedCategory);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }


    /**
     * test category name must be at least 3 characters long
     */

    public function test_category_name_must_be_minimum_3_characters():void
    {
        $category = Category::factory()->create();
        $updatedCategory = [
            'name' => str_repeat('a' , 2),
            'description' => 'updated description',
        ];

        $response = $this->patch(route('categories.update', $category), $updatedCategory);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('categories' , $updatedCategory);
    }


    /**
     * test category name must be at most 255 characters long
     */

    public function test_category_name_must_be_mostly_255_characters():void
    {
        $category = Category::factory()->create();
        $updatedCategory = [
            'name' => str_repeat('a' , 256),
            'description' => 'updated description',
        ];

        $response = $this->patch(route('categories.update', $category), $updatedCategory);


        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('categories' , $updatedCategory);
    }








}
