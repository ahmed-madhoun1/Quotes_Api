<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\HasApiTokens;

class RegisterController extends BaseController
{


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }
        $inputs = $request->all();
        $inputs['password'] = Hash::make($inputs['password']);
        $user = User::create($inputs);
        $success['token'] = $user->createToken('Ahmed')->accessToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User registered successfully');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $success['token'] = $user->createToken('Ahmed')->accessToken;
            $success['name'] = $user->name;
            return $this->sendResponse($success, 'User login successfully');
        } else {
            return $this->sendError('Please check your Auth', ['error' => 'Unauthorized']);
        }
    }
}
