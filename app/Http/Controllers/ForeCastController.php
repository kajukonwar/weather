<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ForeCastService;
use App\Models\City;

class ForeCastController extends Controller
{

    public function index(ForeCastService $forecast, Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required',
        ]);

        $city = City::findOrFail($validated['city_id']);
        $lat = $city->latitude;
        $lon = $city->longitude;
        return $forecast->getForeCastForCity($lat, $lon);
    }
}
