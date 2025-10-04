<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gateway;
use Illuminate\Http\Request;


class DynamicGatewayController extends Controller
{

    public function index()
    {
        $pageTitle = 'Gateways';
        $subNavManualGateway = 'active';
        $navPaymentGatewayActiveClass = 'active';
        $gateways = Gateway::where('is_created', true)->paginate();

        return view('backend.gateways.index', compact('pageTitle', 'gateways', 'subNavManualGateway', 'navPaymentGatewayActiveClass'));
    }

    public function create()
    {
        $pageTitle = 'Create Gateway';
        $subNavManualGateway = 'active';
        $navPaymentGatewayActiveClass = 'active';
        return view('backend.gateways.create', compact('pageTitle', 'subNavManualGateway', 'navPaymentGatewayActiveClass'));
    }


    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|unique:gateways,gateway_name",
            "instruction" => "required",
            "gateway_currency" => "required",
            "rate" => "required|numeric",
            "charge" => "required|numeric",
            "status" => "required",
            'image' => 'required|mimes:jpg,jpeg,png'
        ]);


        $gatewayParameters = [
            'gateway_currency' => strtolower($request->gateway_currency),
            'instruction' => $request->instruction
        ];


        if ($request->hasFile('image')) {
            $filename = uploadImage($request->image, gatewayImagePath());
        }

        Gateway::create([
            'gateway_name' => str_replace(' ', '_', $request->name),
            'gateway_image' => $filename,
            'gateway_parameters' => $gatewayParameters,
            'user_proof_param' => $request->user_proof_param,
            'gateway_type' => 0,
            'status' => $request->status,
            'rate' => $request->rate,
            'charge' => $request->charge,
            'is_created' => 1
        ]);


        $notify[] = ['success', "Gateway Created Successfully"];

        return redirect()->back()->withNotify($notify);
    }


    public function edit($id)
    {
        $pageTitle = 'Edit Gateway';

        $gateway = Gateway::findOrFail($id);


        return view('backend.gateways.edit', compact('pageTitle', 'gateway'));
    }


    public function update(Request $request, $id)
    {
        $gateway = Gateway::findOrFail($id);

        $request->validate([
            "name" => "required|unique:gateways,gateway_name," . $gateway->id,
            "instruction" => "required",
            "gateway_currency" => "required",
            "rate" => "required|numeric",
            "charge" => "required|numeric",
            "status" => "required",
            'image' => 'mimes:jpg,jpeg,png'
        ]);


        $gatewayParameters = [
            'gateway_currency' => strtolower($request->gateway_currency),
            'instruction' => $request->instruction
        ];

        $gateway->update([
            'gateway_name' => str_replace(' ', '_', $request->name),
            'gateway_image' => $request->hasFile('image') ? uploadImage($request->image, gatewayImagePath(), '', $gateway->gateway_image) : $gateway->gateway_image,
            'gateway_parameters' => $gatewayParameters,
            'user_proof_param' => $request->user_proof_param,
            'status' => $request->status,
            'rate' => $request->rate,
            'charge' => $request->charge,
        ]);


        $notify[] = ['success', "Gateway Updated Successfully"];

        return redirect()->back()->withNotify($notify);
    }
}
