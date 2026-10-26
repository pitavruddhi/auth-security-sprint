<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request
    ): RedirectResponse {
        $request->user()->fill(
            $request->validated()
        )->save();

        return redirect()
            ->route('customer.dashboard')
            ->with('success', 'Your profile has been updated successfully.');
    }

}
