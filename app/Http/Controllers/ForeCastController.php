<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ForeCastService;
use App\Models\City;
use Illuminate\Support\Facades\Cache;

class ForeCastController extends Controller
{

    public function index(ForeCastService $forecast, Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required',
        ]);

        $city = City::find($validated['city_id']);
        if (empty($city)) {
            return response()->json(['message' => 'Invalid city'], 404);
        }

        $lat = $city->latitude;
        $lon = $city->longitude;

        //return empty forecast data but mark request as success
        if (empty($lat) || empty($lon)) {
            return response()->json(['message' => 'success', 'data' => []]);
        }
        $key = md5($lat.$lon);
        if (Cache::has($key)) {
            $forecast_data = Cache::get($key);
            $response = ['message' => 'success', 'data' => $forecast_data];
            return response()->json($response);
        }

        $response_code = 200;
        try {
            $forecast_data = $forecast->getForeCastForCity($lat, $lon);
            Cache::put($key, $forecast_data, now()->addMinutes(10));
            $response = ['message' => 'success', 'data' => $forecast_data];
        } catch (\Exception $e) {
            $response_code = $e->getCode();
            $response = ['message' => 'Something went wrong', 'errors' => $e->getMessage()];
        }
        return response()->json($response, $response_code);
    }
}
