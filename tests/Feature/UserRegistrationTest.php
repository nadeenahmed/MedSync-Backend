<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase; 
    public function user_can_register_as_patient() :void
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
        //$this->assertDatabaseHas('patients', ['address' => '123 Main St']);
        //$this->assertDatabaseHas('emergency_data', ['systolic' => 120, 'diastolic' => 80]);
    }
}
