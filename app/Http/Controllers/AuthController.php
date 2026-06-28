<?php

namespace App\Http\Controllers;

use App\Contracts\Services\AuthServiceInterface;
use App\Enums\UserRole;
use App\Http\Requests\LoginRequest;
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
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->hasRole(UserRole::ADMIN->value)) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole(UserRole::STUDENT->value)) {
                return redirect()->route('student.dashboard');
            } elseif ($user->hasRole(UserRole::SCHOOL->value)) {
                return redirect()->route('school.dashboard');
            } elseif ($user->hasRole(UserRole::DONOR->value)) {
                return redirect()->route('donor.dashboard');
            }
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
