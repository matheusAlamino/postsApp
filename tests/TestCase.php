<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;
use JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, DatabaseTransactions;

    protected $faker;
    protected $user;
    protected $authHeader;

    //configura o teste
    public function setUp(): void
    {
        parent::setUp();
        //$this->artisan('migrate');
        $this->faker = Faker::create();

        $this->user = $this->create('User');

        $token = JWTAuth::fromUser($this->user);

        $this->authHeader = ['Content-type' => 'application/json',  'Authorization' => 'Bearer ' . $token];
    }

    public function tearDown(): void
    {
        $this->artisan('migrate:refresh');
        parent::tearDown();
    }

    public function create(string $model, int $qtd = 1, array $atributos = [])
    {
        $factory = factory("App\\$model", $qtd)->create($atributos);

        if ($qtd == 1) {
            return $factory[0];
        } else {
            return $factory;
        }
    }
}
