<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductConsumptionMaster;
use App\ProductConsumptionDetails;
use App\LotWiseConsumptionDetails;
use App\Configuration;
use App\User;
use DB;
use Auth;
use Validator;
use Response;
use Session;
use Helper;
use Common;
use Redirect;
use Illuminate\Http\Request;

class ProductConsumptionController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Approved'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];

    public function create() {
        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::orderBy('name', 'asc')->where('status', '1')->where('approval_status', 1)->pluck('name', 'id')->toArray();
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $adjustmentTime = date('H:i:s');

        if (strtotime($adjustmentTime) <= strtotime($setCutOffTime->check_in_time)) {
            $adjustmentDate = (date('Y-m-d', strtotime("-1 days")));
        } else {
            $adjustmentDate = date('Y-m-d');
        }
        $consumeArr = ProductConsumptionMaster::select(DB::raw('count(id) as total'))->where('source', '1')->where('adjustment_date', $adjustmentDate)->first();
        //echo $consumeArr;exit;
        $voucherId = $consumeArr->total + 1;
        $voucherNo = 'SAD-' . date('ymd', strtotime($adjustmentDate)) . str_pad($voucherId, 4, '0', STR_PAD_LEFT);
        return view('productConsumption.consume')->with(compact('productArr', 'voucherNo', 'adjustmentDate', 'adjustmentTime'));
    }
    
    public function productHints(Request $request) {
        return Common::productHints($request);
    }
    
    public function purchaseNew(Request $request) {
        return Common::purchaseNew($request);
    }

    public function consumeProduct(Request $request) {
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
        $target->status = 0;
        $target->source = '1';
        $target->created_by = Auth::user()->id;
        $target->created_at = date('Y-m-d H:i:s');

        //file upload
        $file = $request->file('attachment');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/productconsume', $fileName);
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
