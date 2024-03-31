<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'regex:/^0[0-9]{9,10}$/'],
            'password' => ['required'],
        ]);
        if (Auth::attempt(['username'=>$credentials['username'],'password' =>$credentials['password']])) {
            $request->session()->regenerate();
            $request->session()->put('username', Auth::user()->username);
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'username' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/dang-nhap');
    }
}
