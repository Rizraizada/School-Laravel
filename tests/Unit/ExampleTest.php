<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_user_factory_uses_project_schema(): void
    {
        $user = User::factory()->create();

        $this->assertNotEmpty($user->username);
        $this->assertNotEmpty($user->full_name);
        $this->assertContains($user->role, ['teacher', 'admin']);
    }
}
