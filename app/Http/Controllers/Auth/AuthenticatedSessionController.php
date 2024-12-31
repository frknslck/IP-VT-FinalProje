<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Models\LoginLog;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Log the login event
        $user = auth()->user();
        $ipAddress = request()->ip();

        LoginLog::create([
            'user_id' => $user->id ?? null,
            'event' => 'login',
            'status' => 'success',
            'ip_address' => $ipAddress,
        ]);

        return redirect()->intended(route('homepage', absolute: false))->with('success', 'Login was successfull');;
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Log the logout event
        $user = auth()->user();
        $ipAddress = request()->ip();

        LoginLog::create([
            'user_id' => $user->id ?? null,
            'event' => 'logout',
            'status' => 'success',
            'ip_address' => $ipAddress,
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout was successfull');;
    }
}
