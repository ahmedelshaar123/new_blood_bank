<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\NewPasswordRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\RegisterTokenRequest;
use App\Http\Requests\Api\RemoveTokenRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Mail\ResetPassword;
use App\Models\Client;
use App\Models\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $request->merge(['password' => bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client->api_token = Str::random(60);
        $client->save();
        $client->governorates()->attach($request->governorate_id);
        $client->bloodTypes()->attach($client->blood_type_id);
        return response()->json($client->load('city', 'city.governorate', 'bloodType', 'governorates', 'bloodTypes'), 200);
    }

    public function login(LoginRequest $request) {
        $client = Client::where('phone', $request->phone)->first();
        if($client){
            if(Hash::check($request->password, $client->password)) {
                if ($client->is_active == 0) {
                    return response()->json("تم حظر حسابك", 400);
                } else {
                    return response()->json($client->load('city', 'city.governorate', 'bloodType', 'governorates', 'bloodTypes'), 200);
                }
            } else {
                return response()->json("بيانات الدخول غير صحيحة", 400);
            }
        }else{
            return response()->json("بيانات الدخول غير صحيحة", 400);
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $request->user()->update($request->except('password', 'governorate_id', 'blood_type_id'));

        if ($request->has('password'))
        {
            $request->user()->password = bcrypt($request->password);
            $request->user()->save();
        }

        if ($request->has('governorate_id'))
        {

            $request->user()->governorates()->detach($request->user()->city->governorate_id);
            $request->user()->governorates()->attach($request->governorate_id);
        }
        if ($request->has('blood_type_id'))
        {

            $request->user()->bloodTypes()->detach($request->user()->blood_type_id);
            $request->user()->bloodTypes()->attach($request->blood_type_id);
        }

        $data = [
            'client' => $request->user()->fresh()->load('city', 'city.governorate','bloodType', 'governorates', 'bloodTypes')
        ];
        return response()->json($data, 200);
    }

    public function resetPassword(ResetPasswordRequest $request) {
        $client = Client::where('phone', $request->phone)->first();
        if($client){
            $code = Str::random(4);
            $client->update(['pin_code' => $code]);
            Mail::to($client->email)
                ->send(new ResetPassword($client->pin_code));
            return response()->json("برجاء فحص بريدك", 200);
        }else{
            return response()->json("البيانات غير صحيحة", 400);
        }
    }

    public function newPassword(NewPasswordRequest $request) {
        $client = Client::where('pin_code', $request->pin_code)->where('pin_code', '!=', null)->first();
        if($client) {
            $client->password = bcrypt($request->password);
            $client->pin_code = null;
            $client->save();
            return response()->json('تم تغيير كلمة السر بنجاح', 200);
        }else{
            return response()->json('البيانات غير صحيحة', 400);
        }
    }

    public function registerToken(RegisterTokenRequest $request) {
        $token = $request->user()->tokens()->create($request->all());
        return response()->json($token->load('client'), 200);
    }

    public function removeToken(RemoveTokenRequest $request) {
        Token::where('token', $request->token)->delete();
        return response()->json('تم الحذف بنجاح', 200);
    }
}
