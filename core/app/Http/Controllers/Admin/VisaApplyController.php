<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CheckoutLog;
use App\Models\VisaStatusLog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
class VisaApplyController extends Controller
{

    public function list($type = "all")
    {

        $data['pageTitle'] = "Manage Apply";
        $data['navManageApplyActiveClass'] = 'active';

        $data['allVisaSubMenuActive'] = '';
        $data['pendingVisaSubMenuActive'] = '';
        $data['proccessingVisaSubMenuActive'] = '';
        $data['completeVisaSubMenuActive'] = '';
        $data['rejectedVisaSubMenuActive'] = '';
        $data['issuesVisaSubMenuActive'] = '';
        $data['reviewVisaSubMenuActive'] = '';
        $data['shippedVisaSubMenuActive'] = '';

        switch ($type) {
            case 'pending':
                $data['pendingVisaSubMenuActive'] = 'active';
                $status = 'pending';
                break;
            case 'processing':
                $data['proccessingVisaSubMenuActive'] = 'active';
                $status = 'proccessing';
                break;
            case 'complete':
                $data['completeVisaSubMenuActive'] = 'active';
                $status = 'complete';
                break;
            case 'rejected':    
                $data['rejectedVisaSubMenuActive'] = 'active';
                $status = 'reject';
                break;
            case 'issues':    
                $data['issuesVisaSubMenuActive'] = 'active';
                $status = 'issues';
                break;
            case 'review':    
                $data['reviewVisaSubMenuActive'] = 'active';
                $status = 'under_review';
                break;
            case 'shipped':    
                $data['shippedVisaSubMenuActive'] = 'active';
                $status = 'shipped';
                break;
            case 'all':
            default:
                $data['allVisaSubMenuActive'] = 'active';
                $status = null;
                break;
        }

        $query = CheckoutLog::where('status','!=','draft')->latest();
        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $data['items'] = $query->paginate(15);
        $data['currentType'] = $type;

        return view('backend.visa.list')->with($data);
    }

    public function details($order)
    {
        $data['pageTitle'] = "Apply Details";
        $data['navManageApplyActiveClass'] = 'active';
        $data['visa'] = CheckoutLog::where('order_number', $order)->firstOrFail();

        return view('backend.visa.details')->with($data);
    }
    
     
    public function download($file)
    {

        $path = "asset/images/visa_document/$file";
        
        if(file_exists($path)){
            
            ob_end_clean();
            
            $headers = array(
                'Content-Type: image/png',
            );


           return response()->download($path);
        }
    
        return back()->with('error', 'No file found');
    }


    
    

    public function changeStatus(Request $request, $order)
    {
        $request->validate([
            'note' => 'required|string',
            'status' => 'required'
        ]);

        $checkout = CheckoutLog::where('order_number', $order)->firstOrFail();
        $checkout->status = $request->status;
        $checkout->note = $request->note;
        $checkout->save();

        sendMail('VISA_CHANGED_STATUS', [
            'order_number' => $checkout->order_number,
            'plan' => $checkout->plan->title,
            'note' => $checkout->note,
            'link' => route('visa.track', ['order_number' => $checkout->order_number])
        ], $checkout->checkout->user);

        $log = new VisaStatusLog();
        $log->apply_id = $checkout->id;
        $log->status = $checkout->status;
        $log->note = $checkout->note;
        $log->save();


        return back()->with('success', 'Status changed successfully completed');
    }
}
