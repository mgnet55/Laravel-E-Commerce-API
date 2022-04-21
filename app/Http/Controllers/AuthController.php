<?php

namespace App\Http\Controllers;

use App\Classes\ImageManager;
use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiResponse
{
    public function login(LoginRequest $request, $type)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], !!$request['remember_token'])) {

            if (Auth::user()->hasRole($type)) {
                $data = [
                    'token' => Auth::user()->createToken('api_token')->plainTextToken,
                    'name' => Auth::user()->name,
                    'role' => Auth::user()->getRoleNames(),
                ];
                return $this->handleResponse($data, 'User logged-in!');
            }
            return $this->handleError('Unauthorized.', ['Unauthorized' => "You don't have the right role"], 403);
        } else {
            return $this->handleError('invalid credentials.', ['error' => 'invalid credentials'], 401);
        }

    }

    public function register(RegisterRequest $request,$role)
    {

        $input = $request->all();
        $input['avatar'] = ImageManager::generateName($request, 'avatar', 'profile');
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $data = [
            'token' => $user->createToken('LaravelSanctumAuth')->plainTextToken,
            'name' => $user->name,
            'avatar' => $input['avatar']
        ];
        ImageManager::upload($request, 'avatar', 'profiles', $input['avatar']);
        $user->assignRole($role);
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

    public function roles()
    {
        return $this->handleResponse(['role' => Auth::user()->getRoleNames()], '');

    }

}
