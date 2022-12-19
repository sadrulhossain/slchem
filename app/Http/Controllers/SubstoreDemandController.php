<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductConsumptionMaster;
use App\ProductConsumptionDetails;
use App\Configuration;
use App\User;
use DB;
use Auth;
use Validator;
use Response;
use Session;
use Helper;
use Redirect;
use Common;
use Illuminate\Http\Request;

class SubstoreDemandController extends Controller {

    public function create() {
        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::orderBy('name', 'asc')->where('status', '1')->where('approval_status', 1)->where('show_in_report', '1')->pluck('name', 'id')->toArray();
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $substoreTime = date('H:i:s');

        if (strtotime($substoreTime) <= strtotime($setCutOffTime->check_in_time)) {
            $substoreDate = (date('Y-m-d', strtotime("-1 days")));
        } else {
            $substoreDate = date('Y-m-d');
        }

        $consumeArr = ProductConsumptionMaster::select(DB::raw('count(id) as total'))
                        ->where('source', '3')
                        ->where('adjustment_date', $substoreDate)->first();
        $voucherId = $consumeArr->total + 1;
        $refNo = 'SUB-' . date('ymd', strtotime($substoreDate)) . str_pad($voucherId, 4, '0', STR_PAD_LEFT);
        return view('substoreDemand.generateDemand')->with(compact('productArr', 'refNo', 'substoreDate', 'substoreTime'));
    }
    
    public function purchaseNew(Request $request) {
        return Common::purchaseNew($request);
    }
    
    public function productHints(Request $request) {
        return Common::productHints($request);
    }

    public function generateDemand(Request $request) {
        $rules = $messages = [];
        $rules['product_id'] = 'required|not_in:0';

        if (!empty($request->attachment)) {
            $rules['attachment'] = 'mimes:jpeg,jpg,bmp,png,gif,pdf,doc,docs,xls,xlsx,csv|max:1024';
            $messages = [
                'mimes' => 'Invalid Attachment Type. Attachment Types are: jpeg,jpg,bmp,png,gif,pdf,doc,docs,xls,xlsx,csv',
                'max' => 'Attachment Maximum Size is 1MB',
            ];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $target = new ProductConsumptionMaster;
        $target->voucher_no = $request->voucher_no;
        $target->adjustment_date = $request->adjustment_date;
        $target->remarks = $request->remarks;
        $target->status = '1';
        $target->source = '3';
        $target->created_by = Auth::user()->id;
        $target->created_at = date('Y-m-d H:i:s');

        //file upload
        $file = $request->file('attachment');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/substoreDemand', $fileName);
            $target->attachment = $fileName;
        }

        if (!empty($request->add_btn)) {
            if ($target->save()) {
                $data = [];
                $i = 0;
                foreach ($request->product_id as $key => $productId) {
                    $data[$i]['master_id'] = $target->id;
                    $data[$i]['product_id'] = $productId;
                    $data[$i]['quantity'] = $request->quantity[$key];
                    $i++;
                }

                //Insert data to the Product Details Table
                $detailInsertStatus = ProductConsumptionDetails::insert($data);
                if (!$detailInsertStatus) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                    ProductConsumptionMaster::where('id', $target->id)->delete();
                    return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                }

                return Response::json(['success' => true], 200);
            } else {
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
            }
        } else {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_PRODUCT_FOR_SAVE')], 401);
        }
    }
    

}//EOF -Class
