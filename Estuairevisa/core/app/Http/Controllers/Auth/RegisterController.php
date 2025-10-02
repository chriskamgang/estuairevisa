<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;


class RegisterController extends Controller
{
    public function index()
    {
        $pageTitle = 'Register User';

        return view('frontend.user.auth.register', compact('pageTitle'));
    }

    public function register(Request $request)
    {

       $general = GeneralSetting::first();

       $signupBonus = $general->signup_bonus;
       
       $request->validate([
            'reffered_by' => 'sometimes',
            'fname' => 'required',
            'lname' => 'required',
            'username' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'g-recaptcha-response'=>Rule::requiredIf($general->allow_recaptcha== 1)
            
        ],[
            'fname.required'=> 'First name is required',
            'lname.required' => 'Last name is required',
            'g-recaptcha-response.required' => 'You Have To fill recaptcha'
        ]);


        if($request->reffered_by){  

            $user = User::where('username',$request->reffered_by)->first();       

            if(!$user){
                $notify[] = ['error','No User Found Assocciated with this reffer Name'];

                return redirect()->route('user.register')->withNotify($notify);
            }
        }

        
        
        event(new Registered($user = $this->create($request, $signupBonus)));

        

        Auth::login($user);


        $notify[] = ['success','Successfully Registered'];

        if($request->place_order)
        {
            return redirect()->route('visa.cart');
        }else{
            return redirect()->route('user.dashboard')->withNotify($notify);
        }

        
       

    }

    public function dashboard()
    {
        if (auth()->check()) {
            return view('frontend.user.dashboard');
        }

        return redirect()->route('user.login')->withSuccess('You are not allowed to access');
    }

    public function create($request, $signupBonus)
    {
       
        return User::create([
            'fname' => $request->fname,
            'balance'=> $signupBonus,
            'lname' => $request->lname,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 1,
            'password' => bcrypt($request->password),
            'reffered_by' => $request->reffered_by ?? ''
        ]);
    }

    public function signOut()
    {
        Auth::logout();

        session()->forget('google2fa');

        return Redirect()->route('user.login');
    }
}
