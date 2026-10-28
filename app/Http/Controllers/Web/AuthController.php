<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Str;
use App\Http\Requests\ForgotPasswordRequest;



class AuthController extends Controller
{
     public function showRegister()
    {
        return view('auth.register');
    }

   public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'customer',
        ]);

        $guestSessionId = $request->session()->getId();

        app(\App\Services\CartService::class)->mergeGuestCart($guestSessionId, $user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($this->dashboardRoute())
            ->with('success', 'Registration successful!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $guestSessionId = $request->session()->getId();



        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        app(\App\Services\CartService::class)->mergeGuestCart($guestSessionId, $request->user());

        return redirect()->intended(route($this->dashboardRoute())
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have logged out successfully.');
    }

    private function dashboardRoute(): string
    {
        return auth()->user()->role === 'admin'
        ?'admin.dashboard':'customer.dashboard';
    }


    
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->validated()
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(
                'success',
                'If an account exists for that email, a password reset link will be sent.'
            );
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors([
                'email' => 'Please wait before requesting another reset link.',
            ]);
        }

        // Avoid revealing whether the email belongs to an account.
        return back()->with(
            'success',
            'If an account exists for that email, a password reset link will be sent.'
        );
    }

    public function showResetPassword(string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request()->query('email'),
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->validated(),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                // Revoke all Sanctum API tokens for this user.
                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with(
                'success',
                'Your password has been reset successfully. Please log in again.'
            );
        }

        return back()
            ->withErrors([
                'email' => 'The password reset link is invalid, expired, or already used.',
            ])
            ->withInput($request->only('email'));
    }

}
