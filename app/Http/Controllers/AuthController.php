<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiResponse
{

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('api_token')->plainTextToken;
            $success['name'] = $auth->name;
            $success['role'] = $auth->getRoleNames();

            return $this->handleResponse($success, 'User logged-in!');
        } else {
            return $this->handleError('invalid credentials.', ['error' => 'invalid credentials'], 401);
        }
    }

    public function register(RegisterRequest $request)
    {

        $input = $request->except('avatar');
        $input['avatar'] = $this->imageUploader($request, 'avatar', 'profiles');
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        return $user;
        $data = [
            'token' => $user->createToken('LaravelSanctumAuth')->plainTextToken,
            'name' => $user->name,
            'avatar' => $input['avatar']
        ];
        return $this->handleResponse($data, 'User successfully registered!');
    }

    public function imageUploader(Request $request, string $fileInputName, string $driver = 'public', string $fileName = null): ?string
    {
        if ($request->hasFile($fileInputName)) {
            $fileName = $fileName ?? $fileInputName . '_' . time();
            $fileName .= '.' . $request->file($fileInputName)->extension();
            $request->file($fileInputName)->storeAs('', name: $fileName, options: $driver);
            return $fileName;
        }
        return null;
    }

}
