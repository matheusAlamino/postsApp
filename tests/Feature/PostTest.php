<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;

class PostTest extends TestCase
{
    protected $post;
    protected $post2;

    /** @test */
    public function index_post()
    {
        $this->popula();

        $response = $this->getJson('/api/post', $this->authHeader);

        $response->assertJsonStructure([
            'current_page',
            'data' => [
                [
                    'id',
                    'text',
                    'id_usuario',
                    'created_at',
                    'updated_at',
                    'user' => [
                        'id',
                        'name'
                    ]
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
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function index_post_unauthorized()
    {
        $this->popula();

        $response = $this->getJson('/api/post');

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function show_post()
    {
        $this->popula();

        $response = $this->getJson('api/post/' . $this->post->id, $this->authHeader);

        $response->assertJsonStructure([
            'id',
            'text',
            'id_usuario',
            'created_at',
            'updated_at',
            'comments'
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function show_post_not_exists()
    {
        $this->popula();

        $response = $this->getJson('api/post/-1', $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(400);
    }

    /** @test */
    public function show_post_unauthorized()
    {
        $this->popula();

        $response = $this->getJson('api/post/' . $this->post->id);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function store_post()
    {
        $data = [
            'text' => 'teste de insercao de novo post',
            'id_usuario' => $this->user->id
        ];

        $response = $this->postJson('api/post/', $data, $this->authHeader);

        $response->assertJsonStructure([
            'text',
            'id_usuario',
            'updated_at',
            'created_at',
            'id'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', $data);
    }

    /** @test */
    public function store_post_unauthorized()
    {
        $data = [
            'text' => 'teste de insercao de novo post',
            'id_usuario' => $this->user->id
        ];

        $response = $this->postJson('api/post/', $data);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('posts', $data);
    }

    /** @test */
    public function update_post()
    {
        $this->popula();

        $data = [
            'id' => $this->post->id,
            'text' => 'teste de update de novo post'
        ];

        $response = $this->putJson('api/post/' . $this->post->id, $data, $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', $data);
    }

    /** @test */
    public function update_post_wrong_user()
    {
        $this->popula();

        $data = [
            'id' => $this->post2->id,
            'text' => 'teste de update de novo post'
        ];

        $response = $this->putJson('api/post/' . $this->post2->id, $data, $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('posts', $data);
    }

    /** @test */
    public function update_post_unauthorized()
    {
        $this->popula();

        $data = [
            'id' => $this->post->id,
            'text' => 'teste de update de novo post'
        ];

        $response = $this->putJson('api/post/' . $this->post->id, $data);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('posts', $data);
    }

    /** @test */
    public function delete_post()
    {
        $this->popula();

        $data = [
            'text' => $this->post->text
        ];

        $response = $this->deleteJson('api/post/' . $this->post->id, [''], $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('posts', $data);
    }

    /** @test */
    public function delete_post_wrong_user()
    {
        $this->popula();

        $response = $this->deleteJson('api/post/' . $this->post2->id, [''], $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('posts', [
            'text' => $this->post2->text
        ]);
    }

    /** @test */
    public function delete_post_unauthorized()
    {
        $this->popula();

        $response = $this->deleteJson('api/post/' . $this->post->id);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseHas('posts', [
            'text' => $this->post->text
        ]);
    }

    /** @test */
    public function store_post_comment()
    {
        $this->popula();

        $data =  [
            'description' => 'teste de insercao de novo comentario em post',
            'id_post' => $this->post->id
        ];

        $response = $this->postJson(
            'api/post/' . $this->post->id . '/Comment',
            $data,
            $this->authHeader
        );

        $response->assertJsonStructure([
            'description', 'id_post', 'id_usuario', 'updated_at',
            'created_at', 'id'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', $data);
    }

    /** @test */
    public function store_comment_unauthorized()
    {
        $this->popula();

        $data =  [
            'description' => 'teste de insercao de novo comentario em post',
            'id_post' => $this->post->id
        ];

        $response = $this->postJson(
            'api/post/' . $this->post->id . '/Comment',
            $data
        );

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('comments', $data);
    }

    public function popula()
    {
        $this->post = $this->create('Post', 1, [
            'id_usuario' => $this->user->id
        ]);

        $this->post2 = $this->create('Post');
    }
}
