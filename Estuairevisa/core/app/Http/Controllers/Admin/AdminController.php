<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    
    public function profile()
    {
        $pageTitle = 'Profile';

        return view('backend.profile',compact('pageTitle'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'username' => 'required',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png'
        ]);

        $admin = auth()->guard('admin')->user();

        if($request->has('image')){

            $path = filePath('admin');

            $size = '200x200';

            $filename = uploadImage($request->image, $path, $size, $admin->image);

            $admin->image = $filename;
        }


        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->save();

        $notify[] = ['success', 'Admin Profile Update Success'];

        return redirect()->back()->withNotify($notify);

    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $admin = auth()->guard('admin')->user();

        if(!Hash::check($request->old_password, $admin->password)){
            $notify[] = ['error','Password Does not match'];

            return back()->withNotify($notify);
        }

        $admin->password = bcrypt($request->password);
        $admin->save();


        $notify[] = ['success','Password changed Successfully'];

        return back()->withNotify($notify);

    }

    
}
