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

class MonthlyConsumptionReportController extends Controller {

    // Monthly Consumption Report 
    public function index(Request $request) {
        if ($request->generate == 'true') {

            if (empty($request->checkout_month)) {
                Session::flash('error', __('label.CONSUME_MONTH_MUST_BE_SELECTED'));
                return redirect('monthlyConsumptionReport');
            }

            $targetArr = ProductConsumptionDetails::join('pro_consumption_master', 'pro_consumption_details.master_id', '=', 'pro_consumption_master.id')
                            ->join('product', 'pro_consumption_details.product_id', '=', 'product.id')
                            ->where('pro_consumption_master.status', '1')
                            ->where('pro_consumption_master.source', '1')
                            ->where('product.status', '1')
                            ->where('product.approval_status', 1)
                            ->where('pro_consumption_master.adjustment_date', '>=', $request->checkout_month . '-01')
                            ->where('pro_consumption_master.adjustment_date', '<=', $request->checkout_month . '-31')
                            ->select('pro_consumption_details.*', 'product.name', 'pro_consumption_master.*')
                            ->orderBy('pro_consumption_master.adjustment_date', 'asc')->get();
        } //if debug true

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if(empty($userAccessArr[59][6])){
                return redirect('dashboard');
            }
            return view('consumptionReport.print.monthlyConsumption')->with(compact('request', 'targetArr', 'productInfoArr'));
        } else {
            return view('consumptionReport.monthlyConsumption')->with(compact('request', 'targetArr', 'productInfoArr'));
        }
    }

    public function filter(Request $request) {
        return redirect('monthlyConsumptionReport?generate=true&checkout_month='
                . $request->checkout_month);
    }

}
