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

class CheckInReportController extends Controller {

    //Stock Summary Report
    public function index(Request $request) {
        $productArr = Product::orderBy('name', 'asc')->where('status', '1')
                ->where('approval_status', 1);
        $productIdList = $productArr->pluck('id', 'id')->toArray();
        $productArr = $productArr->pluck('name', 'id')->toArray();
//        $supplierArr = Supplier::orderBy('name','asc')->pluck('name','id')->toArray();
//        $manufacturerArr = Manufacturer::orderBy('name','asc')->pluck('name','id')->toArray();
        if ($request->generate == 'true') {
            if (!empty($request->product)) {
                $productIdList = explode(",", $request->product);
            }
        }
        $targetArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->leftJoin('product_function', 'product_function.id', '=', 'product.product_function_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.primary_unit_id')
                        ->where('product.status', '1')->where('product.approval_status', 1)
                        ->whereIn('product.id', $productIdList)
                        ->select('product.name as product', 'product.available_quantity', 'product.product_code'
                                , 'measure_unit.name as unit_name', 'product_category.name as product_category')
                        ->orderBy('product', 'asc')->get();
        //echo '<pre>';print_r($targetArr);exit;
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[57][6])) {
                return redirect('dashboard');
            }
            return view('checkInReport.print.stockSummary')->with(compact('request', 'targetArr', 'productArr'));
        } else {
            return view('checkInReport.stockSummary')->with(compact('request', 'targetArr', 'productArr'));
        }
    }

    public function filter(Request $request) {
        $product = !empty($request->product) ? implode(",", $request->product) : '';
        return redirect('checkInReport?generate=true&product='
                . $product);
    }

    public function getSupplierManufacturer(Request $request) {
        //product wise supplier
        $loadView = 'checkInReport.showSupplierManufacturer';
        return Common::getSupplierManufacturer($request, $loadView);
    }

}
