<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthCustomController extends Controller
{

    public function indexPharmacy()
    {
        
        return view('pharmacy-admin/login');
    }  
      

    public function loginCustom(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],
        [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ]
    );
        $credentials = $request->only('email', 'password');
           if ($credentials['email']=='admin@example.com' && $credentials['password']=='123456'){
        return redirect()->intended('pharmacy-admin/index_pharmacy_admin')
                        ->withSuccess('Signed in');
        }
        if (Auth::attempt($credentials)) {
            return redirect()->intended('pharmacy-admin/index_pharmacy_admin')
                        ->withSuccess('Signed in');
        }
        return redirect("pharmacy-admin/login")->withErrors('These credentials do not match our records.');
    }

    public function registrationPharmacy()
    {
        return view('pharmacy-admin/register');
    }
      

    public function registrationCustom(Request $request)
    {  
        $request->validate([
            'name' => 'required|min:5|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
           
        ],
        [
            'name.required' => 'Username is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'password_confirmation.required' => 'Confirm Password is required',
        ]
    );
           
        $data = $request->all();
        $check = $this->store($data);
         
        return redirect("pharmacy-admin/login")->withSuccess('You have signed-in');
    }


    public function store(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }    
    

    public function dashboardPage()
    {
        if(Auth::check()){
            return view('pharmacy-admin/index_pharmacy_admin');
        }
  
        return redirect("pharmacy-admin/login")->withSuccess('You are not allowed to access');
    }
    

    public function signOut() {
        Session::flush();
        Auth::logout();
  
        return Redirect('pharmacy-admin/login');
    }
}
