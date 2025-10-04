<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Checkout;
use App\Models\CheckoutLog;
use App\Models\VisaFileField;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisaController extends Controller
{
    public function all()
    {
        $data['pageTitle'] = 'Visa List';
        $data['items'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where(function ($query) {
            if (request()->order_number) {
                $query->where('order_number', request()->order_number);
            }
        })
            ->latest()->paginate();

        return view('frontend.user.visa.all')->with($data);
    }


    public function details($order)
    {
        $data['pageTitle'] = 'Visa Details';

        $data['visa'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('order_number', $order)->firstOrFail();

        return view('frontend.user.visa.details')->with($data);
    }

    public function visaPayment()
    {
        $checkout_data = session('checkout_data');
        $checkout_data = collect($checkout_data);
        $total_amount = collect($checkout_data['items'] ?? [])->sum(fn($item) => $item['plan']->price);

        if ($total_amount > auth()->user()->balance) {
            return back()->with('error', 'Not enough balance');
        }

        $checkout = new Checkout();
        $checkout->user_id = auth()->user()->id;
        $checkout->trx = strtoupper(Str::random());
        $checkout->total_visa = count($checkout_data['items']);
        $checkout->payment_status = 1;
        $checkout->save();


        foreach ($checkout_data['items'] as $order => $item) {

            $log = new CheckoutLog();
            $log->order_number = $order;
            $log->plan_id = $item['plan']->id;
            $log->checkout_id = $checkout->id;
            $log->price = $item['plan']->price;
            $log->files = $item['session_info']['files'];
            $log->personal_info = $item['session_info']['personal_info'];
            $log->status = 'pending';
            $log->save();


            $admin = Admin::first();

            if ($admin) {
                $admin->notify(new AdminNotification([
                    'type' => "others",
                    'url' => route('admin.visa.list'),
                    'message' => "New visa applay from " . auth()->user()->username
                ]));
            }

            sendMail(
                'VISA_APPLAY',
                [
                    'plan' => $log->plan->title,
                    'price' => $log->price,
                    'order_number' => $log->order_number,
                    'status' => $log->status(),
                    'link' => route('visa.track', ['order_number' => $log->order_number])
                ],
                auth()->user()
            );
        }




        session()->forget('checkout_data');
        session()->forget('apply_infos');

        return redirect()->route('user.visa.all')->with('success', 'Visa processing started successfully');
    }


    public function reSubmit($order)
    {
        $data['pageTitle'] = 'Visa Resubmit';
        $data['fields'] = VisaFileField::get();

        $data['visa'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('order_number', $order)->firstOrFail();


        if ($data['visa']->status != 'issues') {
            return back()->with('error', 'Invalid action');
        }

        return view('frontend.user.visa.re_submit')->with($data);
    }


    public function reSubmitStore(Request $request, $orderId)
    {

        $data = $request->validate([
            "phone_number" => 'required',
            'email_address' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'passport_number' => 'required',
            'profession' => 'required',
            'travel_date' => 'required|date|after_or_equal:today',
            'travel_purpose' => 'required',

        ]);

        $checkout = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('order_number', $orderId)->firstOrFail();



        $data['from_country'] = $request->from;
        $data['live_country'] = $request->live;

        $files  = $request->documents ?? [];
        $uploadedFiles = json_decode(json_encode($checkout->files), true);

        foreach ($files as $key => $file) {
            $uploadedFiles[$key] = uploadImage($file, filePath('visa_document'));
        }

        $checkout->personal_info = $data;
        $checkout->files = $uploadedFiles;
        $checkout->status = 'pending';
        $checkout->save();


        $admin = Admin::first();

        if ($admin) {
            $admin->notify(new AdminNotification([
                'type' => 'others',
                'url' => route('admin.visa.list', 'pending'),
                'message' => "Resubmit visa data from " . auth()->user()->username,
            ]));
        }


        return redirect()->route('user.dashboard')->with('success', 'Resubmited data successfully completed');
    }
}
