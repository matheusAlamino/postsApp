<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    protected $notification;
    protected $notification2;

    /** @test */
    public function index_notification()
    {
        $this->popula();

        $response = $this->getJson('/api/notification', $this->authHeader);

        $response->assertJsonStructure([
            'data' => [
                'current_page',
                'data' => [
                    [
                        'id',
                        'text',
                        'id_usuario',
                        'seen',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ],
            'countNotSeen'
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function index_notification_unauthorized()
    {
        $this->popula();

        $response = $this->getJson('/api/notification');

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function show_notification()
    {
        $this->popula();

        $response = $this->getJson('api/notification/' . $this->notification->id, $this->authHeader);

        $response->assertJsonStructure([
            'id',
            'text',
            'id_usuario',
            'seen',
            'created_at',
            'updated_at'
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function show_notification_not_exists()
    {
        $this->popula();

        $response = $this->getJson('api/notification/-1', $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function show_notification_unauthorized()
    {
        $this->popula();

        $response = $this->getJson('api/notification/' . $this->notification->id);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function delete_notification()
    {
        $this->popula();

        $data = [
            'text' => $this->notification->text
        ];

        $response = $this->deleteJson('api/notification/' . $this->notification->id, [''], $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('notifications', $data);
    }

    /** @test */
    public function delete_notification_wrong_user()
    {
        $this->popula();

        $data = [
            'text' => $this->notification->text
        ];

        $response = $this->deleteJson('api/notification/' . $this->notification2->id, [''], $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('notifications', $data);
    }

    /** @test */
    public function delete_notification_unauthorized()
    {
        $this->popula();

        $data = [
            'text' => $this->notification->text
        ];

        $response = $this->deleteJson('api/notification/' . $this->notification->id);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseHas('notifications', $data);
    }

    public function popula()
    {
        $this->post = $this->create('Post', 1, [
            'id_usuario' => $this->user->id
        ]);

        $this->notification = $this->create('Notification', 1, [
            'id_usuario' => $this->user->id
        ]);

        $this->notification2 = $this->create('Notification');
    }
}
