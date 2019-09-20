<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    /** @test */
    public function verifica_se_sistema_esta_no_ar()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function consegue_carregar_posts()
    {
        $reponse = $this->get('/api/post');

        $reponse->assertStatus(200);
    }
}
