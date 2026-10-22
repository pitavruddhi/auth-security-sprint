<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register Customer
    public function register(RegisterRequest $request): JsonResponse
    {

        $validated = $request->validated();

        // create the customer 

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => $validated['password'],
        ]);


        // assign role 

        $user->role = 'customer';
        $user->save();

        // Sanctum bearer token

        $token = $user->createToken('customer-api')->plainTextToken;

        return response()->json([
            'message' => 'Customer Registered Successfully.',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],

            'token'         => $token,
            'token_type'    => 'Bearer',
        ], 201);
    }

    // Login Customer

    public function login(LoginRequest $request): JsonResponse
    {

        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if(!$user || !Hash::check($validated['password'], $user->password) || $user->role!== 'customer')
        {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);

        }

        $token = $user->createToken('customer-api')->plainTextToken;

        return response()->json([
            'message' => 'Customer Login Successful.',
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],

            'token'         => $token,
            'token_type'    => 'Bearer'


        ]);

    }

    // Admin Login

    public function adminLogin(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (
            ! $user ||
            ! Hash::check($validated['password'], $user->password) ||
            $user->role !== 'admin'
        ) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = $user->createToken('admin-api')->plainTextToken;

        return response()->json([
            'message' => 'Admin login successful.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

}
