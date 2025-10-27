<?php

namespace App\Http\Controllers;

use App\Models\CheckoutLog;
use App\Models\Country;
use App\Models\Gateway;
use App\Models\Plan;
use App\Models\VisaFileField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisaApplyController extends Controller
{
    public function startApplay($id)
    {

        $plan = Plan::find($id);


        if (!$plan) {
            return [
                'status' => false,
                'message' => "Plan not found"
            ];
        }


        $session = session('apply_infos');

        if (!$session) {

            $data = [
                'plan_id' => $plan->id,
                'trx' => strtoupper(Str::random())
            ];
        } else {
            $data['plan_id'] = $plan->id;
            $data['trx'] = strtoupper(Str::random());
        }

        // Définir des valeurs par défaut pour from_id et live_id
        // On utilise le premier pays disponible dans la liste
        $firstCountry = Country::active()->first();
        if ($firstCountry) {
            $data['from_id'] = $firstCountry->id;
            $data['live_id'] = $firstCountry->id;
        }

        session()->put('apply_infos', $data);

        // Passer directement au formulaire d'informations de fichier
        return [
            'status' => true,
            'html' => view('frontend.form_modals.file_info_modal')->render(),
            'modal_name' => 'fileInfoModal'
        ];
    }


    public function applayInfos(Request $request)
    {
        $from = (int)$request->from;
        $live = (int)$request->live;

        $session = session('apply_infos');

        if (!$session) {
            return [
                'status' => false,
                'message' => 'Submission error, try again'
            ];
        }

        $session['from_id'] = $from;
        $session['live_id'] = $live;

        session()->put('apply_infos', $session);

        return [
            'status' => true,
            'html' => view('frontend.form_modals.file_info_modal')->render(),
            'modal_name' => 'fileInfoModal'
        ];
    }

    public function applyDocuments()
    {
        $session = session('apply_infos');
        $plan = Plan::find($session['plan_id']);
        $fields = VisaFileField::active()->get();
        if (!$session) {
            return [
                'status' => false,
                'message' => 'Submission error, try again'
            ];
        }

        return [
            'status' => true,
            'html' => view('frontend.form_modals.wrapper.documents-wrapper', compact('plan', 'fields'))->render(),
            'slide_name' => 'document-slide'
        ];
    }

    public function submitDocument(Request $request)
    {
        $session = session('apply_infos');
        $plan = Plan::find($session['plan_id']);
        $from = Country::find($session['from_id']);
        $live = Country::find($session['live_id']);

        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Session expired or missing. Please restart the application process.',
            ]);
        }


        $fields = VisaFileField::active()->get();
        $uploadedFiles = [];

        foreach ($fields as $field) {
            $inputName = $field->name;
        
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
        
                $request->validate([
                    $inputName => 'file|mimes:jpg,jpeg,png,pdf',
                ]);
        
                // Get MIME type
                $mimeType = $file->getMimeType();
        
                // If it's an image, use uploadImage(); otherwise, just store the PDF
                if (str_starts_with($mimeType, 'image/')) {
                    $path = uploadImage($file, filePath('visa_document'));
                } else {
 
                    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                    $path_url = filePath('visa_document'); 
                    $file->move($path_url, $filename);
                    $path = $filename; 
                }
        
                $uploadedFiles[$inputName] = $path;
            }
        }

        $session['files'] = $uploadedFiles;



        session()->put('apply_infos', $session);

        return [
            'status' => true,
            'html' => view('frontend.form_modals.wrapper.details-wrapper', compact('plan', 'from', 'live'))->render(),
            'slide_name' => 'details-slide'
        ];
    }

    public function infoSubmit(Request $request)
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
            'destination_country' => 'required',

        ]);

        $data['from_country'] = $request->destination_country;
        $data['live_country'] = $request->destination_country;
    
        $session = session('apply_infos');


        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Session expired or missing. Please restart the application process.',
            ]);
        }


        $session['personal_info'] = $data;

        session()->put('apply_infos', $session);
        $data = session('apply_infos');

        $plan = Plan::find($data['plan_id']);
        $from = Country::find($data['from_id']);
        $live = Country::find($data['live_id']);
        $plans = Plan::active()->get();


        return [
            'status' => true,
            'html' => view('frontend.form_modals.wrapper.review-wrapper', compact('plan', 'from', 'live', 'data', 'plans'))->render(),
            'slide_name' => 'details-slide'
        ];
    }

    public function checkout()
    {
        $session = session('apply_infos');




        $plan = Plan::find($session['plan_id']);
        $checkout_data = session('checkout_data', []);
        $checkout_data['items'][$session['trx']] = ['session_info' => $session, 'plan' => $plan];
        session()->put('checkout_data', $checkout_data);

        $checkout_data = collect($checkout_data);
        $gateways = Gateway::where('status', 1)->get();


        return [
            'status' => true,
            'html' => view('frontend.form_modals.wrapper.checkout-wrapper', compact('checkout_data', 'gateways'))->render(),
            'slide_name' => 'checkout-slide'
        ];
    }

    public function cart()
    {
        $checkout_data = session('checkout_data', []);

        if (!$checkout_data || !$checkout_data['items']) {
            return redirect()->route('home')->with('warning', 'No data available');
        }

        $checkout_data = collect($checkout_data);
        $gateways = Gateway::where('status', 1)->get();

        return view('frontend.pages.cart', compact('checkout_data', 'gateways'));
    }

    public function removeCart($trx)
    {
        $checkoutData = session('checkout_data') ?? [];

        if (isset($checkoutData['items'][$trx])) {

            unset($checkoutData['items'][$trx]);

            if (count($checkoutData['items']) > 0) {
                session()->put('checkout_data', $checkoutData);
                return back()->with('Item deleted successfully');
            } else {
                session()->forget('checkout_data');
                return redirect()->route('home');
            }
        }

        return redirect()->route('visa.cart')->with('success', 'No item found.');
    }

    public function track(Request $request)
    {

        $request->validate([
            'order_number' => 'required'
        ]);

        $order = $request->order_number;

        $data['pageTitle'] = 'Track Visa';
        $data['visa'] = CheckoutLog::where('order_number', $order)->first();

        return view('frontend.pages.track')->with($data);
    }


    public function planSearchByCountry(Request $request)
    {
        $from = (int) $request->from_id;
        $to = (int) $request->to_id;


        $plans = Plan::where('country_ids', '!=', '')->active()->where(function ($query) use ($from, $to) {
            $query->whereJsonContains('country_ids', $from)
                ->orWhereJsonContains('country_ids', $to);
        })->get();
        
        

        return view('frontend.fetch_visa_plans', compact('plans'));
    }


    public function planChange($id)
    {

        $plan = Plan::find($id);
        if (!$plan) {
            return [
                'status' => false,
                'message' => 'No plan found'
            ];
        }
        $session = session('apply_infos');
        $session['plan_id'] = $id;
        session()->put('apply_infos', $session);

        return [
            'status' => true,
            'plan_name' => $plan->title,
            'plan_price' => number_format($plan->price, 2),
            'plan_id' => $plan->id,
            'message' => 'Plan changes successfully'
        ];
    }
}
