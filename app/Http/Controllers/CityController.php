<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;
use Jekk0\laravel\Iso3166\Validation\Rules\Iso3166Alpha2;
use App\Http\Services\ForeCastService;

class CityController extends Controller
{
    /**
     * Accept city from user and store
     */
    public function store(ForeCastService $forecast, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'country' => ['required', new Iso3166Alpha2()]
        ]);

        $country = Country::firstOrCreate(
            ['iso3166_code' => $validated['country']]
        );

        try {
            $coordinates = $forecast->getGeoCoordinates($validated['name'], $validated['country']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.', 'error' => $e->getMessage()], 500);
        }

        $city = new City(
            [
                'name' => $validated['name'],
                'latitude' => !empty($coordinates) ? $coordinates[0]['lat'] : '',
                'longitude' => !empty($coordinates) ? $coordinates[0]['lon'] : '',
            ]
        );
        $country->cities()->save($city);
        return response()->json([
            'id' => $city->id,
            'city' => $city->name,
            'latitude' => $city->latitude,
            'longitude' => $city->longitude,
            'country' => $validated['country']
        ]);
    }
}
