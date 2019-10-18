<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function index_users()
    {
        $this->popula();

        $reponse = $this->getJson('/api/user', $this->authHeader);

        $reponse->assertStatus(200);
    }

    /** @test */
    public function show_user()
    {
        $response = $this->getJson('/api/user/' . $this->user->id, $this->authHeader);

        $response->assertStatus(200);
    }

    /** @test */
    public function show_badRequest()
    {
        $response = $this->getJson('/api/user/500', $this->authHeader);

        $response->assertStatus(400);
    }

    public function popula()
    {
        $this->user = $this->create('User');
    }
}
