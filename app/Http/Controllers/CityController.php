<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    /**
     * Accept city from user and store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:cities|max:255'
        ]);
        $city = new City;
        $city->name = $validated['name'];
        $city->save();
        return response()->json(['name' => $city->name]);
    }
}
