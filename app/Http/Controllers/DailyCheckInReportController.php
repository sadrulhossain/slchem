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

class DailyCheckInReportController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();
        
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::pluck('name', 'id')->toArray();
        $manufacturerArr = ['0' => __('label.SELECT_MANUFACTURER_OPT')] + Manufacturer::pluck('name', 'id')->toArray();


        if (!empty($qpArr)) {
            $targetArr = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_details.master_id', '=', 'product_checkin_master.id')
                    ->leftJoin('supplier', 'product_checkin_details.supplier_id', '=', 'supplier.id')
                    ->leftJoin('manufacturer', 'product_checkin_details.manufacturer_id', '=', 'manufacturer.id')
                    ->join('product', 'product_checkin_details.product_id', '=', 'product.id')
                    ->join('users', 'users.id', '=', 'product_checkin_master.created_by')
                    ->leftJoin('measure_unit', 'measure_unit.id', '=', 'product.primary_unit_id')
                    ->select('product_checkin_details.*', 'supplier.name as supplier_name', 'manufacturer.name as manufacturer_name'
                            , 'measure_unit.name as primary_unit_name'
                            , 'product.name', 'product_checkin_master.*','users.first_name', 'users.last_name')
                    ->orderBy('product_checkin_master.checkin_date', 'asc')
                    ->orderBy('product.name', 'asc')
                    ->where('product_checkin_master.checkin_date', '>=', $request->from_date)
                    ->where('product_checkin_master.checkin_date', '<=', $request->to_date);

            if (!empty($request->manufacturer_id)) {
                $targetArr = $targetArr->where('product_checkin_details.manufacturer_id', '=', $request->manufacturer_id);
            }
            if (!empty($request->supplier_id)) {
                $targetArr = $targetArr->where('product_checkin_details.supplier_id', '=', $request->supplier_id);
            }

            $targetArr = $targetArr->get();
        } //if debug true

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if(empty($userAccessArr[55][6])){
                return redirect('dashboard');
            }
            return view('checkInReport.print.dailyCheckIn')->with(compact('qpArr', 'targetArr', 'productInfoArr', 'supplierArr', 'manufacturerArr'));
        } else {
            return view('checkInReport.dailyCheckIn')->with(compact('qpArr', 'targetArr', 'productInfoArr', 'supplierArr', 'manufacturerArr'));
        }
    }

    public function filter(Request $request) {
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('dailyCheckInReport')
                            ->withInput()
                            ->withErrors($validator);
        }

        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date . '&supplier_id=' . $request->supplier_id . '&manufacturer_id=' . $request->manufacturer_id;
        return Redirect::to('dailyCheckInReport?' . $url);
    }
    

}
