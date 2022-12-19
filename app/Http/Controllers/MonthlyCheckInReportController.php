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
use App\ProductCheckInDetails;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class MonthlyCheckInReportController extends Controller {
    
    // Monthly Check In Report 
    public function index(Request $request) {
        $qpArr = $request->all();
        if (!empty($qpArr)) {
            //echo $request->checkin_month;exit;

            if (empty($request->checkin_month)) {
                Session::flash('error', __('label.CHECK_IN_MONTH__MUST_BE_SELECTED'));
                return redirect('monthlyCheckInReport');
            }

            $targetArr = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_details.master_id', '=', 'product_checkin_master.id')
                            ->join('supplier', 'product_checkin_details.supplier_id', '=', 'supplier.id')
                            ->join('manufacturer', 'product_checkin_details.manufacturer_id', '=', 'manufacturer.id')
                            ->join('product', 'product_checkin_details.product_id', '=', 'product.id')
                    ->join('users', 'users.id', '=', 'product_checkin_master.created_by')
                            ->leftJoin('measure_unit', 'measure_unit.id', '=', 'product.primary_unit_id')
                            ->where('product_checkin_master.checkin_date', '>=', $request->checkin_month . '-01')
                            ->where('product_checkin_master.checkin_date', '<=', $request->checkin_month . '-31')
                            ->select('product_checkin_details.*', 'supplier.name as supplier_name', 'manufacturer.name as manufacturer_name'
                                    , 'measure_unit.name as primary_unit_name'
                                    , 'product.name', 'product_checkin_master.*','users.first_name', 'users.last_name')
                            ->orderBy('product_checkin_master.checkin_date', 'asc')
                            ->orderBy('product.name', 'asc')->get();
        } //if debug true

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if(empty($userAccessArr[56][6])){
                return redirect('dashboard');
            }
            return view('checkInReport.print.monthlyCheckIn')->with(compact('qpArr', 'targetArr', 'productInfoArr'));
        } else {
            return view('checkInReport.monthlyCheckIn')->with(compact('qpArr', 'targetArr', 'productInfoArr'));
        }
    }

    public function filter(Request $request) {
        $rules = [
            'checkin_month' => 'required',
        ];
        
        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('monthlyCheckInReport')
                            ->withInput()
                            ->withErrors($validator);
        }

        $url = 'checkin_month=' . $request->checkin_month;
        return Redirect::to('monthlyCheckInReport?' . $url);
    }

}
