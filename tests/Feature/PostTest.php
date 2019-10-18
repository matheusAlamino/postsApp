<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    protected $post;

    /** @test */
    public function verifica_se_sistema_esta_no_ar()
    {
        $response = $this->getJson('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function index_working()
    {
        $response = $this->getJson('/api/post', $this->authHeader);

        $response->assertStatus(200);
    }

    /** @test */
    public function show_working()
    {
        $this->popula();

        $response = $this->getJson('api/post/' . $this->post->id, $this->authHeader);

        $response->assertStatus(200);
    }

    public function popula()
    {
        $this->post = $this->create('Post');
    }
}
