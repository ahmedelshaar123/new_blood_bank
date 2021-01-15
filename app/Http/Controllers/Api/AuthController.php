<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\NewPasswordRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Mail\ResetPassword;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $request->merge(['password' => bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client->api_token = Str::random(60);
        $client->save();
        $client->governorates()->attach($client->city->governorate_id);
        $client->bloodTypes()->attach($client->blood_type_id);
        return response()->json($client->load('city', 'bloodType'), 200);
    }

    public function login(LoginRequest $request) {
        if(Auth('client')->attempt(['phone'=>$request->phone,'password'=>$request->password])) {
            if(Auth('client')->user()->is_active == 0) {
                return response()->json('تم حظر الحساب', 400);
            }
            return response()->json('تم تسجيل الدخول بنجاح', 200);
        }else{
            return response()->json('بيانات الدخول غير صحيحة', 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request) {
        if(Auth('client')->attempt(['phone'=>$request->phone, 'password' => $request->password])) {
            Auth('client')->user()->pin_code = Str::random(4);
            Auth('client')->user()->save();
            Mail::to(Auth('client')->user()->email)
                    ->send(new ResetPassword(Auth('client')->user()->pin_code));
            return response()->json('برجاء مراجعة البريد الخاص بك', 200);
        }else{
            return response()->json('الهاتف غير مسجل لدينا', 400);
        }
    }

    public function newPassword(NewPasswordRequest $request) {
        if(Auth('client')->attempt(['pin_code'=>$request->pin_code, 'password' => $request->password])) {
            Auth('client')->user()->password = bcrypt($request->password);
            Auth('client')->user()->pin_code = null;
            Auth('client')->user()->save();
            return response()->json('تم تغيير كلمة السر بنجاح', 200);
        }else{
            return response()->json('البيانات غير صحيحة', 400);
        }
    }
}
