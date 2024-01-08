<?php

namespace App\Http\Controllers\NonAsn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class NonasnLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('nonasn.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'niptt' => ['required', 'string'],
            'password' => ['required', 'string']
        ], [
            'niptt.required' => 'nomor induk harus diisi',
            'password.required' => 'password harus diisi'
        ]);

        $credentials = [
            'niptt' => $request->niptt,
            'password' => $request->password,
            'blokir' => 'N'
        ];

        if (!Auth::guard('nonasn')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'niptt' => 'username/password tidak sesuai atau akun diblokir'
            ]);
        }

        $request->session()->regenerate();
        return redirect()->route('nonasn.dashboard');
    }

    public function logout()
    {
        Auth::guard('nonasn')->logout();
        Session::flush();
        return redirect()->route('nonasn.login');
    }
}
