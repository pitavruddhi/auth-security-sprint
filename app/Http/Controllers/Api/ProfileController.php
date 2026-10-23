<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // Display the user details

    public function show(Request $request):JsonResponse
    {

        $user = $request->user();

        return response()->json([
            'message' => 'Profile Data retrieved Successfully.',
            'user'    =>  $user->only([
                'id',
                'name',
                'email',
                'role',
                'created_at',
            ]),
        ]);


    }

    // update the profile

    public function update(Request $request): JsonResponse
    {

        $user = $request->user();

        $validated = $request->validate([
             'name'  =>['sometimes','required','string','max:255'],
             'email' =>[
                    'sometimes',
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users','email')->ignore($user->id),
             ],
        ]);

        $user->fill($validated);
        $user->save();

        return response()->json([
            'message' => 'Profile updated Successfully.',
            'user'    => $user->only([
                'id',
                'name',
                'email',
                'role',
                'created_at'
            ]),
        ]);

    }
}
