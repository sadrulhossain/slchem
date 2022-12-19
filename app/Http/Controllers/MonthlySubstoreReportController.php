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
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class MonthlySubstoreReportController extends Controller {

    // Monthly Demand Report 
    public function index(Request $request) {
        if ($request->generate == 'true') {

            if (empty($request->substore_month)) {
                Session::flash('error', __('label.SUBSTORE_DEMAND_MONTH_MUST_BE_SELECTED'));
                return redirect('monthlySubstoreReport');
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

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if(empty($userAccessArr[65][6])){
                return redirect('dashboard');
            }
            return view('substoreReport.print.monthlyDemand')->with(compact('request', 'targetArr', 'rowspan'));
        } else {
            return view('substoreReport.monthlyDemand')->with(compact('request', 'targetArr', 'rowspan'));
        }
    }

    public function filter(Request $request) {
        return redirect('monthlySubstoreReport?generate=true&substore_month='
                . $request->substore_month);
    }

}
