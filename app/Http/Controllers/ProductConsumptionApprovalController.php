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
use Common;
use Session;
use Helper;
use Redirect;
use Illuminate\Http\Request;

class ProductConsumptionApprovalController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Approved'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];

    
    public function index(Request $request) {
        $qpArr = $request->all();

        $refNoArr = ProductConsumptionMaster::select('voucher_no')->orderBy('voucher_no', 'asc')->get();
        $targetArr = ProductConsumptionMaster::join('users', 'users.id', '=', 'pro_consumption_master.created_by');

        //begin filtering
        if (!empty($request->ref_no)) {
            $targetArr = $targetArr->where('pro_consumption_master.voucher_no', 'LIKE', '%' . $request->ref_no . '%');
        }
        if (!empty($request->adjustment_date)) {
            $targetArr = $targetArr->where('pro_consumption_master.adjustment_date', '=', $request->adjustment_date);
        }
        //end filtering

        $targetArr = $targetArr->select('pro_consumption_master.*', 'users.first_name', 'users.last_name')
                        ->where('pro_consumption_master.status', 0)->where('pro_consumption_master.source', '1')
                        ->orderBy('pro_consumption_master.id', 'asc')->paginate(Session::get('paginatorCount'));
        $labelStatusArr = $this->statusArr;
        $statusArr = $this->viewStatusArr;
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/productConsumptionApproval?page=' . $page);
        }
        return view('productConsumption.approvalConfirmation')->with(compact('targetArr', 'qpArr', 'labelStatusArr', 'statusArr', 'refNoArr'));
    }

    public function pendingFilter(Request $request) {
        $url = 'ref_no=' . $request->ref_no . '&adjustment_date=' . $request->adjustment_date;
        return Redirect::to('productConsumptionApproval?' . $url);
    }

    public function getProductConsumptionDetails(Request $request) {
        return Common::getProductConsumptionDetails($request);
    }

    public function doApprove(Request $request) {

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        $requiredQuantityArr = ProductConsumptionDetails::where('master_id', $request->master_id)->orderBy('id', 'asc')->select('id', 'product_id', 'quantity')->get();
        $productArr = array_column($requiredQuantityArr->toArray(), 'product_id');

        $availableQtyArr = Product::whereIn('id', $productArr)->select('name', 'available_quantity', 'id')->get()->toArray();

        $availableArr = [];
        foreach ($availableQtyArr as $item) {
            $availableArr[$item['id']]['name'] = $item['name'];
            $availableArr[$item['id']]['available_quantity'] = $item['available_quantity'];
        }


        $adjProdQtyArr = [];
        $message = '';
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
            Session::flash('error', $message);
            return redirect('productConsumptionApproval' . $pageNumber);
        }

        if (($insertionFlag == 1) && (!empty($requiredQuantityArr))) {
            foreach ($requiredQuantityArr as $data) {
                //adjustment lot wise information insertion in lot wise consumption details table  
                $consumeStatus = Helper::consumeQuantity($request->master_id, $data['id'], $data['product_id'], $data['quantity']);
                if ($consumeStatus) {//check consumption status
                    Product::where('id', $data['product_id'])->decrement('available_quantity', $data['quantity']);
                }else {               
                    $insertionFlag = 0;
                    LotWiseConsumptionDetails::where('consump_master_id', $request->master_id)->delete();
                    ProductConsumptionDetails::where('master_id', $request->master_id)->delete();
                    ProductConsumptionMaster::where('id', $request->master_id)->delete();
                    Session::flash('error', __('label.SOME_OF_PRODUCTS_ARE_ALREADY_CONSUMED_DATA_NOT_ADDED'));
                    return redirect('productConsumptionApproval' . $pageNumber);
                }
            } // EOF --foreach

            $target = ProductConsumptionMaster::where('id', $request->master_id)->first();

            if (!empty($target)) {
                $target->status = 1; //approved
                $target->approved_by = Auth::user()->id;
                $target->approved_at = date('Y-m-d H:i:s');

                //Product
                if ($target->save()) {
                    Session::flash('success', __('label.CONSUMPTION_HAS_BEEN_APPROVED'));
                    return redirect('productConsumptionApproval' . $pageNumber);
                } else { //If failed to Insert in Consumption Master Table                
                    $insertionFlag = 0;
                    Session::flash('success', __('label.DATA_COULD_NOT_BE_SAVE'));
                    return redirect('productConsumptionApproval' . $pageNumber);
                }
            } else {
                $insertionFlag = 0;
                Session::flash('error', __('label.INVALID_DATA'));
                return redirect('productConsumptionApproval' . $pageNumber);
            }
        } //EOF -- insertion
        else {
            Session::flash('error', __('label.SOMETHING_WENT_WRONG'));
            return redirect('productConsumptionApproval' . $pageNumber);
        }
    }//EOF -Function
}//EOF -Class
