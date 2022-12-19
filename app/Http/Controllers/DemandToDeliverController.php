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

class DemandToDeliverController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();
        $refNoArr = ProductConsumptionMaster::where('pro_consumption_master.status', 1)
                        ->where('pro_consumption_master.delivered', '0')
                        ->where('pro_consumption_master.source', '3')
                        ->select('voucher_no')->orderBy('voucher_no', 'asc')->get();

        $userArr = User::where('status', 1)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                        ->pluck('name', 'id')->toArray();
        $targetArr = ProductConsumptionMaster::join('users', 'users.id', '=', 'pro_consumption_master.created_by');

        //begin filtering
        if (!empty($request->voucher_no)) {
            $targetArr = $targetArr->where('pro_consumption_master.voucher_no', 'LIKE', '%' . $request->voucher_no . '%');
        }
        if (!empty($request->adjustment_date)) {
            $targetArr = $targetArr->where('pro_consumption_master.adjustment_date', '=', $request->adjustment_date);
        }
        //end filtering
        $targetArr = $targetArr->select('pro_consumption_master.*', 'users.first_name', 'users.last_name')
                        ->where('pro_consumption_master.status', 1)
                        ->where('pro_consumption_master.source', '3')
                        ->where('pro_consumption_master.delivered', '0')
                        ->orderBy('pro_consumption_master.id', 'desc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('demandToDeliver?page=' . $page);
        }
        return view('substoreDemand.deliverableDemand')->with(compact('targetArr', 'qpArr', 'refNoArr', 'userArr'));
    }

    public function filter(Request $request) {
        $url = 'voucher_no=' . $request->voucher_no . '&adjustment_date=' . $request->adjustment_date;
        return Redirect::to('demandToDeliver?' . $url);
    }

    public function getProductDetails(Request $request, $demandIdForPrint = null) {
        $loadView = 'productDetails';
        return Common::getProductDetails($request, $demandIdForPrint, $loadView, 52);
    }

    public function makeConsume(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        $requiredQuantityArr = ProductConsumptionDetails::where('master_id', $request->master_id)->orderBy('id', 'asc')
                ->select('id', 'product_id', 'quantity')->get();
        $productArr = array_column($requiredQuantityArr->toArray(), 'product_id');

        $availableQtyArr = Product::whereIn('id', $productArr)->select('name', 'available_quantity', 'id')->get()->toArray();

        $availableArr = [];
        foreach ($availableQtyArr as $item) {
            $availableArr[$item['id']]['name'] = $item['name'];
            $availableArr[$item['id']]['available_quantity'] = $item['available_quantity'];
        }

        $subProdQtyArr = [];
        $message = '';
        $insertionFlag = 1;


        if (!empty($requiredQuantityArr)) {
            foreach ($requiredQuantityArr as $data) {
                //Form Product Cumulative Quantity Array
                $subProdQtyArr[$data['product_id']]['total_qty'] = (isset($subProdQtyArr[$data['product_id']]['total_qty']) ? $subProdQtyArr[$data['product_id']]['total_qty'] : 0) + $data['quantity'];
                $subProdQtyArr[$data['product_id']]['available_quantity'] = $availableArr[$data['product_id']]['available_quantity'];
                $subProdQtyArr[$data['product_id']]['product_name'] = $availableArr[$data['product_id']]['name'];

                //Check for Individual Item Quantity with Stock Available Quantity
                if ($data['quantity'] > $availableArr[$data['product_id']]['available_quantity']) {
                    $insertionFlag = 0;
                    $message .= 'Required Quantity exceeds Available Quantity for  ' . $availableArr[$data['product_id']]['name'] . '<br />';
                }
            }//EOF - Foreach

            foreach ($subProdQtyArr as $subProdId => $subProdQty) {
                //Check for Individual Item Quantity with Stock Available Quantity
                if ($subProdQty['total_qty'] > $subProdQty['available_quantity']) {
                    $insertionFlag = 0;
                    $message .= "Required Total Quantity exceeds Available Quantity for Product: " . $subProdQty['product_name'];
                }
            }//EOF - Foreach
        }//EOF - if

        if (!empty($message)) {
            Session::flash('error', $message);
            return redirect('demandToDeliver' . $pageNumber);
        }

        //Check Product Balance before Delivery
        if (($insertionFlag == 1) && (!empty($requiredQuantityArr))) {
            DB::beginTransaction();
            try {
                foreach ($requiredQuantityArr as $data) {
                    //substore lot wise information insertion in lot wise consumption details table
                    $consumeStatus = Helper::consumeQuantity($request->master_id, $data['id'], $data['product_id'], $data['quantity']);
                    if ($consumeStatus) {//check consumption status
                        Product::where('id', $data['product_id'])->decrement('available_quantity', $data['quantity']);
                    } else {
                        $insertionFlag = 0;
                        DB::rollback();
                        Session::flash('error', __('label.QUANTITY_IS_ALREADY_CONSUMED_FOR') . $availableArr[$data['product_id']]['name']);
                        return redirect('demandToDeliver' . $pageNumber);
                    }//EOF -if
                }// EOF --foreach

                $target = ProductConsumptionMaster::where('id', $request->master_id)->lockForUpdate()->first();

                if (!empty($target)) {
                    $target->delivered = '1'; //Substore Delivered
                    $target->delivered_by = Auth::user()->id;
                    $target->delivered_at = date('Y-m-d H:i:s');

                    //Product
                    if ($target->save()) {
                        //If No Error Found, Then Commit
                        DB::commit();
                        Session::flash('success', __('label.SUBSTORE_DENMAND_HAS_BEEN_DELIVERED_SUCCESSFULLY'));
                        return redirect('demandToDeliver' . $pageNumber);
                    } else { //If failed to Insert in Consumption Master Table                
                        $insertionFlag = 0;
                        DB::rollback();
                        Session::flash('error', __('label.SUBSTORE_DENMAND_HAS_NOT_DELIVERED_SUCCESSFULLY'));
                        return redirect('demandToDeliver' . $pageNumber);
                    }
                } else {
                    $insertionFlag = 0;
                    DB::rollback();
                    Session::flash('error', __('label.INVALID_DATA'));
                    return redirect('demandToDeliver' . $pageNumber);
                }
                
            } catch (\Throwable $e) {
                DB::rollback();
                Session::flash('error', __('label.SOMETHING_WENT_WRONG'));
                return redirect('demandToDeliver' . $pageNumber);
            }
        } else {
            Session::flash('error', __('label.SOMETHING_WENT_WRONG'));
            return redirect('demandToDeliver' . $pageNumber);
        }//EOF -if
    }

//EOF- function
}

//EOF -Class
