<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $client = Client::create($request->all());
        $client->api_token = Str::random(60);
        $client->save();
        return response()->json($client->load('city', 'bloodType'), 200);
    }
}
