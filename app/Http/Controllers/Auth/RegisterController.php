<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $customMessages = [
            'required' => 'Trường này là bắt buộc.',
            'regex' => 'Số điện thoại không hợp lệ.',
            'unique' => 'Số điện thoại của bạn đã được đăng ký.',
        ];
        $request->validate([
            'username' => 'required|string|regex:/^0[0-9]{9,10}$/|unique:customers',
            'password' => ['required', 'confirmed', 'min:8'],
        ], $customMessages);

        $customer = Customer::create([
            'username' => $request->username,
            'name' => $request->username,
            'address' => 'Địa chỉ tín dụng',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($customer));

        Auth::login($customer);

        return redirect(RouteServiceProvider::HOME);
    }
}
