<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;
use App\Service\ForeCastService;

class CityTest extends TestCase
{
    private $url = '';

    public function setUp() : void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->url = config('app.url').'/api/cities';
    }

    public function test_city_creation_empty_payload(): void
    {
        $payload = ['name' => '', 'country' => ''];
        $response = $this->postJson($this->url, $payload);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    "name" => [
                        "The name field is required."
                    ],
                    "country"=> [
                        "The country field is required."
                    ]
                ],
            ]);
    }

    public function test_city_creation_invalid_countrycode(): void
    {
        $wrong_code = 'wrongcountry';
        $payload = ['name' => 'Test', 'country' => $wrong_code];
        $response = $this->postJson($this->url, $payload);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    "country"=> [
                        "The ".$wrong_code." is not valid country code."
                    ]
                ],
            ]);
    }

    public function test_city_creation_success(): void
    {
        $payload = ['name' => 'London', 'country' => 'GB'];
        $response = $this->postJson($this->url, $payload);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'city',
                'latitude',
                'longitude',
                'country'
            ])
            ->assertJson([
                'city' => 'London',
                'country' => 'GB'
            ]);
    }
}
