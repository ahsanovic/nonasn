<?php

namespace App\Http\Controllers\Fasilitator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Fasilitator\UserFasilitator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('fasilitator.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ], [
            'username.required' => 'username harus diisi',
            'password.required' => 'password harus diisi'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'blokir' => 'N',
        ];

        if (!Auth::guard('fasilitator')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'username' => 'username/password tidak sesuai atau akun diblokir'
            ]);
        }

        $request->session()->regenerate();
        return redirect()->route('fasilitator.dashboard');
    }

    public function logout()
    {
        Auth::guard('fasilitator')->logout();
        Session::flush();
        return redirect()->route('fasilitator.login');
    }
}
