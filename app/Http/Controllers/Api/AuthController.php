<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $request->merge(['password', bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client->api_token = Str::random(60);
        $client->save();
        return response()->json($client->load('city', 'bloodType'), 200);
    }

    public function login(LoginRequest $request) {
        if(Auth::attempt(['phone'=>$request->phone,'password'=>$request->password])) {
            return response()->json('تم تسجيل الدخول بنجاح', 200);
        }else{
            return response()->json('بيانات الدخول غير صحيحة', 400);
        }
    }
}
