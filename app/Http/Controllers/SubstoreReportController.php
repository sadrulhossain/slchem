<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Product;
use App\Supplier;
use App\Manufacturer;
use App\ProductToSupplier;
use App\ProductToManufacturer;
use App\ProductConsumptionDetails;
use App\User;
use Helper;
use DB;
use Auth;
use PDF;
use Input;
use Illuminate\Http\Request;

class SubstoreReportController extends Controller {

    public function dailyDemand(Request $request) {
        if ($request->generate == 'true') {
            if (empty($request->from_date) || empty($request->to_date)) {
                Session::flash('error', __('label.DATE_MUST_BE_SELECTED'));
                return redirect('substoreReport/daily?from_date=' . $request->from_date . '&to_date=' . $request->to_date);
            }

            if ($request->from_date > $request->to_date) {
                Session::flash('error', __('label.FROM_DATE_IS_GREATER_THAN_TO_DATE'));
                return redirect('substoreReport/daily?from_date=' . $request->from_date . '&to_date=' . $request->to_date);
            }
            
            $userInfo = User::select('id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as name"))
                        ->pluck('name', 'id')->toArray();

            $dataArr = ProductConsumptionDetails::join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                    ->join('product', 'product.id', '=', 'pro_consumption_details.product_id')
                    ->where('pro_consumption_master.source', '3')
                    ->where('pro_consumption_master.delivered', '1')
                    ->where('pro_consumption_master.adjustment_date', '>=', $request->from_date)
                    ->where('pro_consumption_master.adjustment_date', '<=', $request->to_date)
                    ->select('product.name'
                            , 'pro_consumption_master.adjustment_date'
                            , 'pro_consumption_master.approved_at', 'pro_consumption_master.approved_by'
                            , 'pro_consumption_master.delivered_at', 'pro_consumption_master.delivered_by'
                            , DB::raw('SUM(pro_consumption_details.quantity) as total'), 'pro_consumption_details.product_id'
                            , 'pro_consumption_master.voucher_no'
                    )
                    ->groupBy('pro_consumption_details.product_id'
                            , 'name', 'pro_consumption_master.adjustment_date', 'pro_consumption_master.voucher_no'
                            , 'pro_consumption_master.approved_at'
                            , 'pro_consumption_master.approved_by', 'pro_consumption_master.delivered_at', 'pro_consumption_master.delivered_by')
                    ->orderBy('pro_consumption_master.adjustment_date', 'desc')
                    ->orderBy('pro_consumption_master.voucher_no', 'asc')
                    ->get();

            $targetArr = [];
            if (!empty($dataArr)) {
                foreach ($dataArr as $item) {
                    $targetArr[$item->adjustment_date][$item->voucher_no]['data'][] = $item->toArray();
                    $targetArr[$item->adjustment_date][$item->voucher_no]['authority'] = array( 'delivered_at'=>$item->delivered_at
                            , 'delivered_by'=>$userInfo[$item->delivered_by]
                            );
                }
            }//if
        } //if debug true

        if ($request->view == 'print') {
            return view('substoreReport.print.dailyDemand')->with(compact('request', 'targetArr', 'rowspan'));
        } else {
            return view('substoreReport.dailyDemand')->with(compact('request', 'targetArr', 'rowspan'));
        }
    }

    public function dailyDemandFilter(Request $request) {
        return redirect('substoreReport/daily?generate=true&from_date='
                . $request->from_date . '&to_date=' . $request->to_date);
    }

    // Monthly Demand Report 
    public function monthlyDemand(Request $request) {
        if ($request->generate == 'true') {

            if (empty($request->substore_month)) {
                Session::flash('error', __('label.SUBSTORE_DEMAND_MONTH_MUST_BE_SELECTED'));
                return redirect('substoreReport/monthly');
            }

            $dataArr = ProductConsumptionDetails::join('pro_consumption_master', 'pro_consumption_details.master_id', '=', 'pro_consumption_master.id')
                            ->join('product', 'pro_consumption_details.product_id', '=', 'product.id')
                            ->where('pro_consumption_master.source', '3')
                            ->where('pro_consumption_master.delivered', '1')
                            ->where('pro_consumption_master.adjustment_date', '>=', $request->substore_month . '-01')
                            ->where('pro_consumption_master.adjustment_date', '<=', $request->substore_month . '-31')
                            ->select('pro_consumption_details.*'
                                    , 'product.name', 'pro_consumption_master.*')
                            ->orderBy('pro_consumption_master.adjustment_date', 'desc')->get();
            
            $targetArr = [];
            if (!empty($dataArr)) {
                foreach ($dataArr as $item) {
                    $targetArr[$item->adjustment_date][$item->voucher_no]['data'][] = $item->toArray();
                }
            }//if
            
           // echo '<pre>';print_r($targetArr);exit;
            
        } //if debug true

        if ($request->view == 'print') {
            return view('substoreReport.print.monthlyDemand')->with(compact('request', 'targetArr', 'rowspan'));
        } else {
            return view('substoreReport.monthlyDemand')->with(compact('request', 'targetArr', 'rowspan'));
        }
    }

    public function monthlyDemandFilter(Request $request) {
        return redirect('substoreReport/monthly?generate=true&substore_month='
                . $request->substore_month);
    }

}
