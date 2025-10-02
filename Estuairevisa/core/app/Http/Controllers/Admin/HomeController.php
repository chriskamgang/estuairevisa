<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckoutLog;
use App\Models\Checkout;
use App\Models\Gateway;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Subscriber;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        $data['pageTitle'] = 'Dashboard';
        $data['navDashboardActiveClass'] = "active";
        
        $allCheckoutPayments = Checkout::where('payment_status',1)->with('logs')
            ->get()
            ->flatMap(function ($checkout) {
                return $checkout->logs;
            })
            ->sum('price');
            
            

        $data['totalPayments'] = $allCheckoutPayments;

        $pendingCheckoutLogs = Checkout::where('payment_status', 0)
            ->with('logs')
            ->get()
            ->flatMap(function ($checkout) {
                return $checkout->logs;
            })
            ->sum('price');

        $data['totalPendingPayments'] = $pendingCheckoutLogs;

        $data['totalVisaRequest'] = CheckoutLog::where('status', '!=', 'draft')->count();
        $data['totalVisaProcessing'] = CheckoutLog::processing()->count();
        $data['totalVisaCompleted'] = CheckoutLog::completed()->count();
        $data['totalVisaRejected'] = CheckoutLog::rejected()->count();
        $data['totalVisaShipped'] = CheckoutLog::shipped()->count();

        $data['totalUser'] = User::count();

        $data['activeUser'] = User::where('status', 1)->count();

        $data['deActiveUser'] = User::where('status', 0)->count();
        $data['totalTicket'] = Ticket::count();

        $months = collect([]);
        $totalAmount = collect([]);
        $data['users'] = User::latest()->paginate(5);
        
        Payment::where('payment_status', 1)
            ->select(DB::raw('SUM(final_amount) as total'), DB::raw('MONTHNAME(created_at) month'))
            ->groupby('month')
            ->get()
            ->map(function ($q) use ($months, $totalAmount) {
                $months->push($q->month);
                $totalAmount->push($q->total);
            });

        $data['months'] = $months;
        $data['totalAmount'] = $totalAmount;

        $data['totalGateways'] = Gateway::where('gateway_name', '!=', 'bank')->count();
        $data['activeGateway'] = Gateway::where('status', 1)->count();

        return view('backend.dashboard')->with($data);
    }

    public function transaction(Request $request)
    {
        $data['pageTitle'] = 'Transaction Log';
        $data['navReportActiveClass'] = 'active';
        $data['subNavTransactionActiveClass'] = 'active';

        $dates = array_map(function ($date) {
            return Carbon::parse($date);
        }, explode('-', $request->dates));

        $data['transactions'] = Transaction::when($request->dates, function ($q) use ($dates) {
            $q->whereBetween('created_at', $dates);
        })->where('payment_status', 1)->latest()->paginate();

        $data['gateways'] = Gateway::where('status', 1)->get();

        return view('backend.transaction')->with($data);
    }

    public function markNotification(Request $request, $type = "others")
    {
        
        if ($type == "payment") {
            auth()->guard('admin')->user()
                ->unreadNotifications
                ->filter(function ($notification) {
                    return $notification->data['type'] === 'deposit' || $notification->data['type'] == 'payment';
                })->markAsRead();
        } else {
            auth()->guard('admin')->user()
                ->unreadNotifications
                ->filter(function ($notification) use ($type) {
                    return $notification->data['type'] === $type;
                })->markAsRead();
        }


        return redirect()->back()->with('success', 'All Notifications are Marked');
    }
    public function subscribers()
    {

        $pageTitle = "Newsletter Subscriber";
        $subscribers = Subscriber::latest()->paginate();
        return view('backend.subscriber', compact('subscribers', 'pageTitle'));
    }
}
