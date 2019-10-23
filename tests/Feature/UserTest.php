<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    protected $notification;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function index_users_working()
    {
        $response = $this->getJson('/api/user', $this->authHeader);

        $response->assertJsonStructure([
            [
                'id', 'name', 'email', 'email_verified_at', 'created_at',
                'updated_at'
            ]
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function index_users_unauthorized()
    {
        $response = $this->getJson('/api/user');

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function show_user_working()
    {
        $response = $this->getJson('/api/user/' . $this->user->id, $this->authHeader);

        $response->assertJsonStructure([
            'id', 'name', 'email', 'email_verified_at', 'created_at',
            'updated_at'
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function show_user_not_exists()
    {
        $response = $this->getJson('/api/user/-1', $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(400);
    }

    /** @test */
    public function show_user_unauthorized()
    {
        $response = $this->getJson('/api/user/' . $this->user->id);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function store_user()
    {
        $data = [
            'name' => 'Matheus Henrique',
            'email' => 'henrique@gmail.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/user/', $data, $this->authHeader);

        $response->assertJsonStructure([
            'user_id'
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function update_user()
    {
        $data = [
            'name' => 'Jonatas Carlos de Souza',
            'email' => 'jonatas@gmail.com',
            'password' => 'password'
        ];

        $response = $this->putJson('api/user/' . $this->user->id, $data, $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function update_user_wrong()
    {
        $data = [
            'name' => 'Jonatas Carlos de Souza',
            'email' => 'jonatas@gmail.com',
            'password' => 'password'
        ];

        $response = $this->putJson('api/user/-1', $data, $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(400);

        $this->assertDatabaseMissing('users', $data);
    }

    /** @test */
    public function update_user_unauthorized()
    {
        $data = [
            'name' => 'teste de update de novo post',
            'email' => 'jonatas@gmail.com',
            'password' => 'password'
        ];

        $response = $this->putJson('api/user/' . $this->user->id, $data);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('users', $data);
    }

    /** @test */
    public function delete_user()
    {
        $data = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => $this->user->password
        ];

        $response = $this->deleteJson('api/user/' . $this->user->id, [''], $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', $data);
    }

    /** @test */
    public function delete_user_wrong()
    {
        $data = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => $this->user->password
        ];

        $response = $this->deleteJson('api/user/-1', [''], $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(400);

        $this->assertDatabaseHas('users', $data);
    }

    /** @test */
    public function delete_user_unauthorized()
    {
        $data = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => $this->user->password
        ];

        $response = $this->deleteJson('api/user/' . $this->user->id);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseHas('users', $data);
    }

    /** @test */
    public function update_notifications()
    {
        $this->popula();

        $response = $this->putJson(
            'api/user/' . $this->user->id . '/Notifications',
            [''],
            $this->authHeader
        );

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('notifications', [
            'id' => $this->notification->id,
            'text' => $this->notification->text,
            'id_usuario' => $this->notification->id_usuario,
            'seen' => true
        ]);
    }

    public function popula()
    {
        $this->notification = $this->create('Notification', 1, [
            'id_usuario' => $this->user->id,
            'seen' => false
        ]);
    }
}
