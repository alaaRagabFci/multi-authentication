<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Owner;

class FrontController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct(Request $request)
    // {
    //     if($request->is('front/login'))
    //         $this->middleware('guest:owner')->except('logout');
    //     elseif($request->is('front/home'))
    //         $this->middleware('auth:owner');
    // }

//login
    public function showOwnerLoginForm()
    {
        return view('front.login');
    }

    public function ownerLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('owner')->attempt(['email' => $request->email, 'password' => $request->password], 
            $request->get('remember'))) {
            return redirect()->intended('/front/home');
        }
        return back()->withInput($request->only('email', 'remember'));
    }

//Register
    public function showOwnerRegisterForm()
    {
        return view('front.register');
    }

    protected function createOwner(Request $request)
    {
        $this->validator($request->all())->validate();
        $admin = Owner::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        if (Auth::guard('owner')->attempt(['email' => $request->email, 'password' => $request['password']])) {
            return redirect()->intended('front/home');
        }

        return redirect()->intended('front/login');
    }

    public function frontHome()
    {
        return view('owner');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function logoutUser(Request $request)
    {
        Auth::guard('owner')->logout();
        return redirect('/front/login');
    }
}
