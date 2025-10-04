<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageUserController extends Controller
{
   public function index()
   {

        $data['pageTitle'] = 'All Users';
        $data['navManageUserActiveClass'] = 'active';
        $data['subNavManageUserActiveClass'] = 'active';

        $data['users'] = User::latest()->paginate();

        return view('backend.users.index')->with($data);
   }

   public function userDetails(Request $request)
    {
        $user = User::where('id', $request->user)->firstOrFail();
        $pageTitle = "User Details";

        return view('backend.users.details', compact('pageTitle', 'user'));
    }
    
    public function contactList()
    {
        $data['pageTitle'] = "All Contacts";
        $data['contacts'] = Contact::latest()->paginate();
        
        return view('backend.contact_list')->with($data);
    }

    public function userUpdate (Request $request, User $user)
    {

        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'unique:users,phone,' . $user->id,
            'status' => 'required|in:0,1'
        ]);

        $data = [
            'country' => $request->country,
            'city' => $request->city,
            'zip' => $request->zip,
            'state' => $request->state,
        ];


        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->phone = $request->phone;
        $user->address = $data;
        $user->status = $request->status;

        $user->save();



        $notify[] = ['success', 'User Updated Successfully'];

        return back()->withNotify($notify);
    }

    public function sendUserMail(Request $request, User $user)
    {
        $data = $request->validate([
            'subject' => 'required',
            "message" => 'required',
        ]);

        $data['name'] = $user->fullname;
        $data['email'] = $user->email;

        sendGeneralMail($data);

        $notify[] = ['success', 'Send Email To user Successfully'];

        return back()->withNotify($notify);
    }

    public function disabled(Request $request)
    {
        $pageTitle = 'Disabled Users';

        $search = $request->search;

        $users = User::when($search, function($q) use($search){
            $q->where('name','LIKE','%'.$search.'%')
              ->orWhere('company_name','LIKE','%'.$search.'%')
              ->orWhere('email','LIKE','%'.$search.'%')
              ->orWhere('mobile','LIKE','%'.$search.'%');

        })->where('status',0)->latest()->paginate();

        return view('backend.users.index', compact('pageTitle', 'users'));
    }

    public function userStatusWiseFilter(Request $request)
    {
       $data['pageTitle'] = ucwords($request->status).' Users';
        $data['navManageUserActiveClass'] = 'active';
       if ($request->status == 'active'){
           $data['subNavActiveUserActiveClass'] = 'active';
       }else{
           $data['subNavDeactiveUserActiveClass'] = 'active';
       }

       $users = User::query();

        if($request->status == 'active'){
            $users->where('status',1);
        }elseif($request->status == 'deactive'){
            $users->where('status',0);
        }


        $data['users'] = $users->paginate();


        return view('backend.users.index')->with($data);

    }

    public function userBalanceUpdate(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        if($request->type == 'add'){
            $user->balance =  $user->balance + $request->balance;

        }else{
            if($user->balance < $request->balance){
                $notify[] = ['error', 'Insufficient balance'];

                return back()->withNotify($notify);
            }
            $user->balance =  $user->balance - $request->balance;
        }

        $user->save();

        $notify[] = ['success', 'Successfully '.$request->type.' balance'];

        return back()->withNotify($notify);
    }

    public function loginAsUser($id)
    {
        $user = User::findOrFail($id);

        Auth::loginUsingId($user->id);

        return redirect()->route('user.dashboard');

    }
}
