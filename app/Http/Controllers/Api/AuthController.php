<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

    
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'If an account exists for this email, a password reset link will be sent.',
            ], 200);
        }

        if ($status === Password::RESET_THROTTLED) {
            return response()->json([
                'message' => 'Please wait before requesting another reset link.',
            ], 429);
        }

        // Avoid revealing whether the email address has an account.
        return response()->json([
            'message' => 'If an account exists for this email, a password reset link will be sent.',
        ], 200);
    }

    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                // Revoke all existing Sanctum API tokens.
                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password has been reset successfully. Please log in again.',
            ], 200);
        }

        return response()->json([
            'message' => 'Unable to reset password. The token may be invalid, expired, or already used.',
        ], 422);
    }




}
