<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryDeletingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp():void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }


    /**
     * Test that a category can be deleted successfully and removed from the database.
     */
    public function test_category_deleted_successfully(): void
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy' , $category));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Category deleted successfully');

        $this->assertDatabaseMissing('categories' , $category->toArray());
    }
}
