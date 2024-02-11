<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    // public function test_registration_screen_can_be_rendered(): void
    // {
    //     if (! Features::enabled(Features::registration())) {
    //         $this->markTestSkipped('Registration support is not enabled.');
    //     }

    //     $response = $this->get('/register');

    //     $response->assertStatus(200);
    // }

    // public function test_registration_screen_cannot_be_rendered_if_support_is_disabled(): void
    // {
    //     if (Features::enabled(Features::registration())) {
    //         $this->markTestSkipped('Registration support is enabled.');
    //     }

    //     $response = $this->get('/register');

    //     $response->assertStatus(404);
    // }


    public function test_new_users_can_register(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'patient',

        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);
        $this->assertAuthenticated();
        //$response->assertRedirect(RouteServiceProvider::HOME);
    }
}
