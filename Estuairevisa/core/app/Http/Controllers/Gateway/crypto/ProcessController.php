<?php 
namespace App\Http\Controllers\Gateway\crypto;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Checkout;
use App\Models\CheckoutLog;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ProcessController extends Controller
{
    public static function process($request, $gateway, $amount , $deposit)
    {

        $validation = [];
        if ($gateway->user_proof_param != null) {
            foreach ($gateway->user_proof_param as $params) {
                if ($params['type'] == 'text' || $params['type'] == 'textarea') {

                    $key = strtolower(str_replace(' ', '_', $params['field_name']));

                    $validationRules = $params['validation'] == 'required' ? 'required' : 'sometimes';

                    $validation[$key] = $validationRules;
                } else {

                    $key = strtolower(str_replace(' ', '_', $params['field_name']));

                    $validationRules = ($params['validation'] == 'required' ? 'required' : 'sometimes') . "|image|mimes:jpg,png,jpeg|max:2048";

                    $validation[$key] = $validationRules;
                }
            }
        }

        $data = $request->validate($validation);

        foreach ($data as $key => $upload) {

            if ($request->hasFile($key)) {

                $filename = uploadImage($upload, filePath('manual_payment'));

                $data[$key] = ['file' => $filename, 'type' => 'file'];
            }
        }
        
        if (session('type') == 'payment') {

            DB::beginTransaction();

            $checkout_data = session('checkout_data');

            $checkout = new Checkout();
            $checkout->user_id = auth()->user()->id;
            $checkout->trx = strtoupper(Str::random());
            $checkout->total_visa = count($checkout_data['items']);
            $checkout->payment_status = 0;
            $checkout->save();


            foreach ($checkout_data['items'] as $order => $item) {

                $log = new CheckoutLog();
                $log->order_number = $order;
                $log->plan_id = $item['plan']->id;
                $log->checkout_id = $checkout->id;
                $log->price = $item['plan']->price;
                $log->files = $item['session_info']['files'];
                $log->personal_info = $item['session_info']['personal_info'];
                $log->status = 'draft';
                $log->save();
            }

            $deposit->checkout_id = $checkout->id;
            DB::commit();

            session()->forget('checkout_data');
        }

        $deposit->payment_proof = $data;
        $deposit->payment_type = 0;
        $deposit->payment_status = 2;
        $deposit->save();


        $admin = Admin::first();

        if ($admin) {

            if (session('type') == 'deposit') {
                $admin->notify(new AdminNotification([
                    'type' => 'payment',
                    'url' => route('admin.deposit.log'),
                    'message' => "New deposit request from " . $deposit->user->username
                ]));
            } else {
                $admin->notify(new AdminNotification([
                    'type' => 'payment',
                    'url' => route('admin.manual.status', 'pending'),
                    'message' => "New payment request from " . $deposit->user->username
                ]));
            }
        }

        session()->forget('type');
        
        


        $deposit->payment_proof = $data;

        $deposit->payment_type = 0;

        $deposit->payment_status = 2;

        $deposit->save();
    }
}