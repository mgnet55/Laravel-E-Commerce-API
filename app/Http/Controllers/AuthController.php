<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('api_token')->plainTextToken;
            $success['name'] = $auth->name;
            return response($success, 200);
        } else {
            return response(['error' => 'Unauthorised'],401);
        }
    }
}
