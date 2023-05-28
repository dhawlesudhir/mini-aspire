<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $response = $this->json('POST', '/api/login', ['email' => 'ag@application.com', 'password' => 'dskjdhkdjs']);
        $response->assertStatus(201);
    }


    public function test_false_login()
    {
        $response = $this->json('POST', '/api/login');

        $response->assertStatus(422);
    }

    public function test_false_logout()
    {
        $response = $this->json('GET', '/api/logout');
        $response->assertStatus(401);
    }

    public function test_register_without_valid_details()
    {

        $form = [];

        $response = $this->json('POST', '/api/register', $form);
        $response->assertStatus(422);
    }


    public function test_register_with_valid_details()
    {
        $number = rand(1, 100);
        $form = [
            'first_name' => 'sudhir',
            'last_name' => 'dhawle',
            'email' => 'sudhirdhawle' . $number . '@mail.com',
            'password' => 'demo@123',
            'password_confirmation' => 'demo@123'
        ];

        $response = $this->json('POST', '/api/register', $form);
        $response->assertStatus(201);
    }
}
