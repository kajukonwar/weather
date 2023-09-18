<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\City;

class ForeCastTest extends TestCase
{
    private $url = '';

    public function setUp() : void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->url = config('app.url').'/api/forecast';
    }
    
    public function test_forecast_empty_payload(): void
    {
        $this->url = $this->url.'?city_id_wrong=test';
        $response = $this->getJson($this->url);
        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => "The city id field is required.",
                'errors' => [
                    "city_id" => [
                        "The city id field is required."
                    ]
                ],
            ]);
    }

    public function test_forecast_invalid_city(): void
    {
        $wrong_code = -555512;
        $this->url = $this->url.'?city_id='.$wrong_code;
        $response = $this->getJson($this->url);
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Invalid city'
            ]);
    }

    public function test_forecast_wrong_coordinates(): void
    {
        $payload = ['name' => 'WrongCityInWorld', 'country' => 'GB'];
        $this->postJson(config('app.url').'/api/cities', $payload);
        $city_id = City::where('name', 'WrongCityInWorld')->first()->id;

        $this->url = $this->url.'?city_id='.$city_id;
        $response = $this->getJson($this->url);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => []
            ]);
    }

    public function test_forecast_success(): void
    {
        $city_id = City::where('name', 'London')->first()->id;
        $this->url = $this->url.'?city_id='.$city_id;
        $response = $this->getJson($this->url);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data',
            ])
            ->assertJson([
                'message' => 'success',
            ]);
    }
}
