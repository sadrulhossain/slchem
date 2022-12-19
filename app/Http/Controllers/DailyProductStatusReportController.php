<?php

namespace App\Http\Controllers;

use App\DailyProduct;
use App\ProductCheckInDetails;
use App\ProductConsumptionDetails;
use App\LotWiseConsumptionDetails;
use App\DailyProductDetails;
use App\Product;
use App\Configuration;
use DB;
use Helper;
use Session;
use Common;
use Response;
use Auth;
use Illuminate\Http\Request;

class DailyProductStatusReportController extends Controller {

    public function generateDateWiseProduct() {
        Helper::generateDateWiseProduct();
    }
    
    public function index(Request $request) {
        $productArr = Product::orderBy('name', 'asc')->where('status', '1')
                ->where('approval_status', 1);
        $productIdList = $productArr->pluck('id', 'id')->toArray();
        $productArr = $productArr->pluck('name', 'id')->toArray();
        
        $targetArr = [];
            
        if ($request->generate == 'true') {

            if (empty($request->date)) {
                Session::flash('error', __('label.DATE_MUST_BE_SELECTED'));
                return redirect('dailyProductStatusReport');
            }
            
            //Get Previous Date for Count Before Date
            $prevDate = date('Y-m-d', strtotime('-1 days', strtotime($request->date)));
            
            
         
            //Get Temporary Product CheckIn Details for Given Date
            $productCheckInDetailTempArr = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_details.master_id', '=', 'product_checkin_master.id')
                            ->where('product_checkin_master.checkin_date',$request->date)
                            ->select(DB::raw('SUM(product_checkin_details.quantity) as total_qty'), DB::raw('SUM(product_checkin_details.amount) as total_amount')
                                    , 'product_checkin_details.product_id')
                            ->groupBy('product_id')->get()->toArray();
            
            
            //Final Array based on Temporary Array for CheckIn Details Data
            $productCheckInDetailArr = [];
            if (!empty($productCheckInDetailTempArr)) {
                foreach ($productCheckInDetailTempArr as $key => $item) {
                    $productCheckInDetailArr[$item['product_id']]['total_qty'] = $item['total_qty'];
                    $productCheckInDetailArr[$item['product_id']]['total_amount'] = $item['total_amount'];
                }
            }
           
            
            //Get Data for Previous Date
            $productCheckInDetailPrevDateTempArr = ProductCheckInDetails::join('product_checkin_master'
                    , 'product_checkin_details.master_id', '=', 'product_checkin_master.id')
                            ->where('product_checkin_master.checkin_date','<=',$prevDate)
                            ->select(DB::raw('SUM(product_checkin_details.quantity) as total_qty')
                              , DB::raw('SUM(product_checkin_details.amount) as total_amount'), 'product_checkin_details.product_id')
                            ->groupBy('product_id')->get()->toArray();

            //Final Data for Previous Date
            $productCheckInDetailPrevDateArr = [];
            if (!empty($productCheckInDetailPrevDateTempArr)) {
                foreach ($productCheckInDetailPrevDateTempArr as $key => $item) {
                    $productCheckInDetailPrevDateArr[$item['product_id']]['total_qty'] = $item['total_qty'];
                    $productCheckInDetailPrevDateArr[$item['product_id']]['total_amount'] = $item['total_amount'];
                }
            }
            
            
            //data taken from product consumption detail Table based on given date
            $productConsumptionDetailTempArr = ProductConsumptionDetails::join('pro_consumption_master', 'pro_consumption_details.master_id', '=', 'pro_consumption_master.id')
                            ->where('pro_consumption_master.adjustment_date', $request->date)
                            ->where('pro_consumption_master.status', 1)
                            ->select(DB::raw('SUM(pro_consumption_details.quantity) as total_qty'), 'pro_consumption_details.product_id')
                            ->groupBy('pro_consumption_details.product_id')->get()->toArray();

            $productConsumptionDetailArr = [];
            if (!empty($productConsumptionDetailTempArr)) {
                foreach ($productConsumptionDetailTempArr as $key => $item) {
                    $productConsumptionDetailArr[$item['product_id']]['total_qty'] = $item['total_qty'];
                }
            }
            
               
            //data taken from lot wise product consumption detail Table
            $lotWiseConsumptionDetaiTempArr = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                            ->join('pro_consumption_master', 'pro_consumption_master.id','=', 'pro_consumption_details.master_id')
                            ->where('pro_consumption_master.adjustment_date', $request->date)
                            ->where('pro_consumption_master.status',1)
                            //->where('pro_consumption_master.adjustment_date', '<=', $request->month . '-31')
                            ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_qty')
                                    , DB::raw('SUM(pro_consumption_lot_wise_details.amount) as total_amount')
                                    , 'pro_consumption_details.product_id')
                            ->groupBy('pro_consumption_details.product_id')->get()->toArray();

            $lotWiseConsumptionDetaiArr = [];
            if (!empty($lotWiseConsumptionDetaiTempArr)) {
                foreach ($lotWiseConsumptionDetaiTempArr as $key => $item) {
                    $lotWiseConsumptionDetaiArr[$item['product_id']]['total_qty'] = $item['total_qty'];
                    $lotWiseConsumptionDetaiArr[$item['product_id']]['total_amount'] = $item['total_amount'];
                    $lotWiseConsumptionDetaiArr[$item['product_id']]['rate'] = ($item['total_qty'] != 0) ? Helper::numberformat($item['total_amount'] / $item['total_qty']) : $item['total_amount'];
                }
            }
            
            $lotWiseConsumptionDetailPrevDateTempArr = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                            ->join('pro_consumption_master', 'pro_consumption_master.id', 'pro_consumption_details.master_id')
                            ->where('pro_consumption_master.adjustment_date', '<=', $prevDate)
                            ->where('pro_consumption_master.status',1)
                            ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_qty')
                                    , DB::raw('SUM(pro_consumption_lot_wise_details.amount) as total_amount')
                                    , 'pro_consumption_details.product_id')
                            ->groupBy('pro_consumption_details.product_id')
                            ->get()->toArray();

            $lotWiseConsumptionDetailPrevDateArr = [];
            if (!empty($lotWiseConsumptionDetailPrevDateTempArr)) {
                foreach ($lotWiseConsumptionDetailPrevDateTempArr as $key => $item) {
                    $lotWiseConsumptionDetailPrevDateArr[$item['product_id']]['total_qty'] = $item['total_qty'];
                    $lotWiseConsumptionDetailPrevDateArr[$item['product_id']]['total_amount'] = $item['total_amount'];
                    $lotWiseConsumptionDetailPrevDateArr[$item['product_id']]['rate'] = ($item['total_qty'] != 0) ? Helper::numberformat($item['total_amount'] / $item['total_qty']) : $item['total_amount'];
                }
            }
            
            //get data from product table 
            if (!empty($request->product)) {
                $productIdList = explode(",", $request->product);
            }
            $productInfoArr = Product::whereIn('id', $productIdList)->where('status', '1')->where('approval_status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

            //get Locations
            $productLocationArr = Product::whereIn('id', $productIdList)->where('show_in_report', '!=', '1')->pluck('storage_location', 'id')->toArray();

            if (!empty($productInfoArr)) {
                foreach ($productInfoArr as $productId => $productName) {
                    $targetArr[$productId]['name'] = $productName;
                    $targetArr[$productId]['location'] = isset($productLocationArr[$productId]) ? $productLocationArr[$productId] : null;
                    $targetArr[$productId]['prev_date_checkin_qty'] = $prev_checkin_qty = isset($productCheckInDetailPrevDateArr[$productId]['total_qty']) ? $productCheckInDetailPrevDateArr[$productId]['total_qty'] : 0;
                    $targetArr[$productId]['prev_date_checkin_amount'] = $prev_checkin_amount = isset($productCheckInDetailPrevDateArr[$productId]['total_amount']) ? $productCheckInDetailPrevDateArr[$productId]['total_amount'] : 0;      
                    $targetArr[$productId]['prev_date_issue_qty'] = $prev_issue_qty = isset($lotWiseConsumptionDetailPrevDateArr[$productId]['total_qty']) ? $lotWiseConsumptionDetailPrevDateArr[$productId]['total_qty'] : 0;
                    $targetArr[$productId]['prev_date_issue_amount'] = $prev_issue_amount = isset($lotWiseConsumptionDetailPrevDateArr[$productId]['total_amount']) ? $lotWiseConsumptionDetailPrevDateArr[$productId]['total_amount'] : 0;
                    $targetArr[$productId]['prev_date_balance_qty'] = $prev_balance_qty = ($prev_checkin_qty - $prev_issue_qty);
                    $targetArr[$productId]['prev_date_balance_amount'] = $prev_balance_amount = ($prev_checkin_amount-$prev_issue_amount);
                    $targetArr[$productId]['this_date_qty'] = $this_date_qty = isset($productCheckInDetailArr[$productId]['total_qty']) ? $productCheckInDetailArr[$productId]['total_qty'] : 0;
                    $targetArr[$productId]['this_date_amount'] = $this_date_amount = isset($productCheckInDetailArr[$productId]['total_amount']) ? $productCheckInDetailArr[$productId]['total_amount'] : 0;
                    $targetArr[$productId]['total_qty'] = $total_qty = $this_date_qty + $prev_balance_qty;
                    $targetArr[$productId]['total_amount'] = $total_amount = $this_date_amount + $prev_balance_amount; 
                    $targetArr[$productId]['issue_qty'] = $issue_qty = isset($lotWiseConsumptionDetaiArr[$productId]['total_qty']) ? $lotWiseConsumptionDetaiArr[$productId]['total_qty'] : 0;
                    $targetArr[$productId]['issue_amount'] = $issue_amount = isset($lotWiseConsumptionDetaiArr[$productId]['total_amount']) ? $lotWiseConsumptionDetaiArr[$productId]['total_amount'] : 0;
                    $targetArr[$productId]['balance_qty'] = $total_qty - $issue_qty;
                    $targetArr[$productId]['balance_amount'] = $total_amount - $issue_amount;
                    $targetArr[$productId]['balance_rate'] = !empty($targetArr[$productId]['balance_qty']) ? ($targetArr[$productId]['balance_amount']/$targetArr[$productId]['balance_qty']) : $targetArr[$productId]['balance_amount'];

                }//EOF-foreach
            }//EOF-if !empty
        }//if debug true'
		

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if(empty($userAccessArr[61][6])){
                return redirect('dashboard');
            }
            return view('productStatusReport.print.dailyProductStatus')->with(compact('request', 'targetArr', 'productArr'));
        } else {
            return view('productStatusReport.dailyProductStatus')->with(compact('request', 'targetArr', 'productArr'));
        }
    }

    public function filter(Request $request) {
        $product = !empty($request->product) ? implode(",", $request->product) : '';
        $url = 'date=' . $request->date . '&product=' . $product;
        return redirect('dailyProductStatusReport?generate=true&' . $url);
    }

}
