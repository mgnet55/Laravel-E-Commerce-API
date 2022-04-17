<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Classes\ImageManager;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiResponse
{


    public function login(LoginRequest $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], !!$request['remember_token'])) {
//            $auth = Auth::user();
            $data = [
                'token'=> Auth::user()->createToken('api_token')->plainTextToken,
                'name' => Auth::user()->name,
                'role' => Auth::user()->getRoleNames(),
            ];
            return $this->handleResponse($data, 'User logged-in!');
        } else {
            return $this->handleError('invalid credentials.', ['error' => 'invalid credentials'], 401);
        }
    }

    public function register(RegisterRequest $request)
    {

//        $input = $request->except('avatar');
        $input['avatar'] = ImageManager::upload($request, 'avatar', 'profiles');
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $data = [
            'token' => $user->createToken('LaravelSanctumAuth')->plainTextToken,
            'name' => $user->name,
            'avatar' => $input['avatar']
        ];
        return $this->handleResponse($data, 'User successfully registered!');
    }

    public function logout()
    {
        Auth::logout();
        return $this->handleResponse('', 'successfully logged out');
    }

    public function logoutAllDevices()
    {

        auth()->user()->tokens()->delete();
        return $this->handleResponse('', 'successfully logged out from all devices');
    }

}
