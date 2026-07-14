<?php

namespace App\Http\Controllers;

use App\Contracts\Services\AuthServiceInterface;
use App\Http\Requests\LoginRequest;
use App\Support\RoleRedirect;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if ($this->authService->login($credentials, $remember)) {
            // Session regeneration is handled by Auth::attempt()
            // No need to manually regenerate here

            $redirect = RoleRedirect::toDashboard(Auth::user());
            if ($redirect) {
                return $redirect;
            }

            // User has no role, log them out
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Your account has no assigned role. Please contact the administrator.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->authService->register($request->validated());
        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
