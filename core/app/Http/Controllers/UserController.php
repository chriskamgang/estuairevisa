<?php

namespace App\Http\Controllers;


use App\Models\CheckoutLog;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $data['pageTitle'] = "Dashboard";
        $data['user'] = Auth::user();
        $applaies = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });

        $data['total_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();
        $data['pending_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('status', 'pending')->count();
        $data['processing_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('status', 'proccessing')->count();
        $data['complete_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('status', 'complete')->count();
        $data['shipped_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('status', 'shipped')->count();

        $tickets = Ticket::where('user_id', auth()->user()->id);
        $data['total_ticket'] = $tickets->count();
        $data['pending_ticket'] = $tickets->where('status', 1)->count();
        $data['closed_ticket'] = $tickets->where('status', 2)->count();
        $pending_payment = Checkout::where('user_id', auth()->id())
            ->where('payment_status', 0)
            ->with('logs')
            ->get()
            ->flatMap(function ($checkout) {
                return $checkout->logs;
            })
            ->sum('price');

        $data['pending_payment'] = $pending_payment;
        $data['balance'] = auth()->user()->balance;
        $data['applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->latest()->take(10)->get();
        $data['total_deposit'] = Deposit::where('user_id', auth()->user()->id)->where('payment_status', 1)->sum('amount');
        return view('frontend.user.dashboard')->with($data);
    }

    public function profile()
    {
        $pageTitle = 'Profile Edit';

        $user = auth()->user();

        return view('frontend.user.profile', compact('pageTitle', 'user'));
    }

    public function profileUpdate(Request $request)
    {


        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'username' => 'required|unique:users,username,' . Auth::id(),
            'image' => 'sometimes|image|mimes:jpg,png,jpeg',
            'email' => 'required|unique:users,email,' . Auth::id(),
            'phone' => 'unique:users,id,' . Auth::id(),

        ], [
            'fname.required' => 'First Name is required',
            'lname.required' => 'Last Name is required',

        ]);

        $user = auth()->user();


        if ($request->hasFile('image')) {
          
            $filename = uploadImage($request->image, filePath('user'), old:$user->image);
            $user->image = $filename;
        }


        $data = [
            'country' => $request->country,
            'city' => $request->city,
            'zip' => $request->zip,
            'state' => $request->state,
        ];

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $data;
        $user->save();



        $notify[] = ['success', 'Successfully Updated Profile'];

        return back()->withNotify($notify);
    }


    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view('frontend.user.auth.changepassword', compact('pageTitle'));
    }


    public function updatePassword(Request $request)
    {

        $request->validate([
            'oldpassword' => 'required|min:6',
            'password' => 'min:6|confirmed',

        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->oldpassword, $user->password)) {
            return redirect()->back()->with('error', 'Old password do not match');
        } else {
            $user->password = bcrypt($request->password);

            $user->save();

            return redirect()->back()->with('success', 'Password Updated');
        }
    }


    public function transaction()
    {
        $pageTitle = "Transactions";

        $transactions = Transaction::where('user_id', auth()->id())->latest()->with('user')->paginate();

        return view('frontend.user.transaction', compact('pageTitle', 'transactions'));
    }

    public function transactionLog()
    {
        $pageTitle = 'Transaction Log';

        $transactions = Transaction::where('user_id', auth()->id())->where('payment_status', 1)->latest()->paginate();


        return view('frontend.user.transaction', compact('pageTitle', 'transactions'));
    }

    public function referral()
    {

        $parent = User::where('username', auth()->user()->username)->first();
        $user = getUserWithChildren($parent->id);
        return view('frontend.user.referral',compact('user'));
    }

    /**
     * Save FCM token for push notifications
     */
    public function saveFCMToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        try {
            $user = auth()->user();
            $user->fcm_token = $request->token;
            $user->push_notification_enabled = 1;
            $user->save();

            \Log::info('FCM token saved successfully', [
                'user_id' => $user->id,
                'token' => substr($request->token, 0, 20) . '...'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notifications activées avec succès!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving FCM token', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'activation des notifications'
            ], 500);
        }
    }
}
