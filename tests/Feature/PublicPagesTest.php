<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_home_page_loads(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
    }
}
