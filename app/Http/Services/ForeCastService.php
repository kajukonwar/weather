<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class ForeCastService
{
    private $host = '';
    private $params = [];

    public function __construct()
    {
        $this->host = config('weather.forecast.provider.host');
        $this->params['appid'] = config('weather.forecast.provider.key');
    }

    public function getForeCastForCity($lat = '', $lon = '')
    {
        $url = $this->host.config('weather.forecast.provider.urls.5day');
        $this->params['lat'] = $lat;
        $this->params['lon'] = $lon;
        
        $response = Http::get($url, $this->params);
        $response->throw();
        return $response;
    }
}
