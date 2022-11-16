<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $messages = [
            'name.required' => 'Name tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'role.required' => "Role tidak boleh kosong"
        ];
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|string'
        ], $messages);
        $validator = $validate->validate();
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors(),
                'data' => null
            ], 400);
        }

        $validator['password'] = Hash::make($validator['password']);
        $user = User::create($validator);
        if ($user) {
            $token = $user->createToken('tokenku')->plainTextToken;
            return response([
                'message' => "success",
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 200);
        } else {
            return response([
                'message' => "gagal",
                'data' => null
            ], 400);
        }
    }
    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'Email tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ];
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ], $messages);
        $validator = $validate->validate();
        $user = User::where('email', $validator['email'])->first();
        if (!$user || !Hash::check($validator['password'], $user->password)) {
            return response()->json([
                'message' => 'unauthorized'
            ], 401);
        }
        $token = $user->createToken('tokenku')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json($response, 201);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}
