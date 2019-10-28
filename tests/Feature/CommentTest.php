<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    protected $post;
    protected $comment;
    protected $comment2;

    /** @test */
    public function show_comment()
    {
        $this->popula();

        $response = $this->getJson('api/comment/' . $this->comment->id, $this->authHeader);

        $response->assertJsonStructure([
            'id',
            'description',
            'id_post',
            'id_usuario',
            'updated_at',
            'created_at'
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function show_comment_not_exists()
    {
        $this->popula();

        $response = $this->getJson('api/comment/-1', $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(400);
    }

    /** @test */
    public function show_comment_unauthorized()
    {
        $this->popula();

        $response = $this->getJson('api/comment/' . $this->comment->id);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function update_comment()
    {
        $this->popula();

        $data = [
            'description' => 'teste de update de comentario'
        ];

        $response = $this->putJson('api/comment/' . $this->comment->id, $data, $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', $data);
    }

    /** @test */
    public function update_comment_wrong_user()
    {
        $this->popula();

        $data = [
            'id' => $this->comment2->id,
            'description' => 'teste de update de comentario'
        ];

        $response = $this->putJson('api/comment/' . $this->comment2->id, $data, $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('comments', $data);
    }

    /** @test */
    public function update_comment_unauthorized()
    {
        $this->popula();

        $data = [
            'description' => 'teste de update de comentario'
        ];

        $response = $this->putJson('api/comment/' . $this->comment->id, $data);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('comments', $data);
    }

    /** @test */
    public function delete_comment()
    {
        $this->popula();

        $data = [
            'description' => $this->comment->description
        ];

        $response = $this->deleteJson('api/comment/' . $this->comment->id, [''], $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('comments', $data);
    }

    /** @test */
    public function delete_comment_wrong_comment()
    {
        $this->popula();

        $response = $this->deleteJson('api/comment/-1', [''], $this->authHeader);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('comments', [
            'description' => $this->comment->description
        ]);
    }

    /** @test */
    public function delete_comment_unauthorized()
    {
        $this->popula();

        $data = [
            'description' => $this->comment->description
        ];

        $response = $this->deleteJson('api/comment/' . $this->comment->id);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseHas('comments', $data);
    }

    public function popula()
    {
        $this->post = $this->create('Post', 1, [
            'id_usuario' => $this->user->id
        ]);

        $this->comment = $this->create('Comment', 1, [
            'id_post' => $this->post->id,
            'id_usuario' => $this->user->id
        ]);

        $this->comment2 = $this->create('Comment', 1, [
            'id_post' => $this->post->id
        ]);
    }
}
