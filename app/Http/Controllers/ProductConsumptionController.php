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
        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::orderBy('name', 'asc')
                        ->where('status', '1')
                        ->where('approval_status', 1)
                        ->where('show_in_report', '0')
                        ->pluck('name', 'id')->toArray();
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $adjustmentTime = date('H:i:s');

        if (strtotime($adjustmentTime) <= strtotime($setCutOffTime->check_in_time)) {
            $adjustmentDate = (date('Y-m-d', strtotime("-1 days")));
        } else {
            $adjustmentDate = date('Y-m-d');
        }
        $consumeArr = ProductConsumptionMaster::select(DB::raw('count(id) as total'))
                        ->where('source', '1')->where('adjustment_date', $adjustmentDate)->first();

        $voucherId = $consumeArr->total + 1;
        $voucherNo = 'SAD-' . date('ymd', strtotime($adjustmentDate)) . str_pad($voucherId, 4, '0', STR_PAD_LEFT);
        return view('productConsumption.consume')->with(compact('productArr', 'voucherNo', 'adjustmentDate'
                                , 'adjustmentTime'));
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
        $target->status = 1;
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
            DB::beginTransaction();
            try {
                if ($target->save()) {
                    //echo $target->id." Inserted";
                    //sleep(10);
                    $data = [];
                    $i = 0;
                    foreach ($request->product_id as $key => $productId) {
                        $data[$i]['master_id'] = $target->id;
                        $data[$i]['product_id'] = $productId;
                        $data[$i]['quantity'] = $request->quantity[$key];
                        $i++;
                    }
                    //Insert data to the Product Details Table
                    //try {
                    $detailInsertStatus = ProductConsumptionDetails::insert($data);

                    if (!$detailInsertStatus) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                        //ProductConsumptionMaster::where('id', $target->id)->delete();
                        DB::rollback();
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                    } else {
                        ///////////////////////////////////////////////
                        $requiredQuantityArr = ProductConsumptionDetails::where('master_id', $target->id)->orderBy('id', 'asc')
                                        ->select('id', 'product_id', 'quantity')->lockForUpdate()->get();
//                        echo '<pre>';
//                        print_r($requiredQuantityArr->toArray());
//                        exit;

                        $productArr = array_column($requiredQuantityArr->toArray(), 'product_id');


                        $availableQtyArr = Product::whereIn('id', $productArr)
                                        ->select('name', 'available_quantity', 'id')
                                        ->lockForUpdate()->get()->toArray();

                        $availableArr = [];
                        foreach ($availableQtyArr as $item) {
                            $availableArr[$item['id']]['name'] = $item['name'];
                            $availableArr[$item['id']]['available_quantity'] = $item['available_quantity'];
                        }


                        $adjProdQtyArr = [];
                        $message = $error = '';
                        $insertionFlag = 1;
                        if (!empty($requiredQuantityArr)) {
                            foreach ($requiredQuantityArr as $data) {
                                //Form Product Cumulative Quantity Array
                                $adjProdQtyArr[$data['product_id']]['total_qty'] = (isset($adjProdQtyArr[$data['product_id']]['total_qty']) ? $adjProdQtyArr[$data['product_id']]['total_qty'] : 0) + $data['quantity'];
                                $adjProdQtyArr[$data['product_id']]['available_quantity'] = $availableArr[$data['product_id']]['available_quantity'];
                                $adjProdQtyArr[$data['product_id']]['product_name'] = $availableArr[$data['product_id']]['name'];

                                //Check for Individual Item Quantity with Stock Available Quantity
                                if ($data['quantity'] > $availableArr[$data['product_id']]['available_quantity']) {
                                    $insertionFlag = 0;
                                    $message .= 'Required Quantity exceeds Available Quantity for  ' . $availableArr[$data['product_id']]['name'] . '<br />';
                                }
                            }//EOF - Foreach

                            foreach ($adjProdQtyArr as $adjProdId => $adjProdQty) {
                                //Check for Individual Item Quantity with Stock Available Quantity
                                if ($adjProdQty['total_qty'] > $adjProdQty['available_quantity']) {
                                    $insertionFlag = 0;
                                    $message .= "Required Total Quantity exceeds Available Quantity for Product: " . $adjProdQty['product_name'];
                                }
                            }//EOF - Foreach
                        }//EOF - if

                        if (!empty($message)) {
                            DB::rollback();
                            return Response::json(['success' => false, 'heading' => 'Error', 'message' => $message], 401);
                        }
                        //exit;
                        if (($insertionFlag == 1) && (!empty($requiredQuantityArr))) {
                            foreach ($requiredQuantityArr as $data) {
                                //adjustment lot wise information insertion in lot wise consumption details table  
                                $consumeStatus = Helper::consumeQuantity($target->id, $data['id'], $data['product_id'], $data['quantity']);
                                if ($consumeStatus) {//check consumption status
                                    Product::where('id', $data['product_id'])->decrement('available_quantity', $data['quantity']);
                                } else {
                                    $insertionFlag = 0;
                                    DB::rollback();
                                    return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.QUANTITY_IS_ALREADY_CONSUMED_FOR') . $availableArr[$data['product_id']]['name']], 401);
                                    //$error .= __('label.QUANTITY_IS_ALREADY_CONSUMED_FOR') . $availableArr[$data['product_id']]['name']. '<br />';
                                }
                            } // EOF --foreach
//                            if (!empty($error)) {
//                                DB::rollback();
//                                return Response::json(['success' => false, 'heading' => 'Error', 'message' => $error], 401);
//                            }
                            
                            DB::commit();
                            return Response::json(['success' => true], 200);
                        } //EOF -- insertion
                        else {
                            DB::rollback();
                            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.SOMETHING_WENT_WRONG')], 401);
                        }
                    }
                    ////////////////////////////////
                    //DB::commit();
                } //EOF-IF Target->SAVE()
            } catch (\Throwable $e) {
                DB::rollback();
                //print_r($e->getMessage());
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
            }
        } else {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_PRODUCT_FOR_SAVE')], 401);
        }
    }

}

//EOF -Class
