<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryAuthorizationTest extends TestCase
{
    /**
     * test guest cannot access categories page
     */
    public function test_guest_cannot_access_categories_page():void
    {
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

}
