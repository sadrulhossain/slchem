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
use App\LotWiseConsumptionDetails;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class ReconciliationReportController extends Controller {

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
                        ->select('product.name as product', 'product.id', 'product.available_quantity', 'product.product_code'
                                , 'measure_unit.name as unit_name', 'product_category.name as product_category')
                        ->orderBy('product', 'asc')->get();
        
        $availableQuantityArr = $balanceArr = $productStatusArr = [];
        if(!$targetArr->isEmpty()){
            foreach($targetArr as $item){
                $availableQuantityArr[$item->id] = $item->available_quantity;
            }
        }

        $checkInArr = ProductCheckInDetails::select(DB::raw('SUM(quantity) as total_quantity'), 'product_id')
                        ->groupBy('product_id')->whereIn('product_id', $productIdList)
                        ->pluck('total_quantity', 'product_id')->toArray();

        $consumeArr = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                        ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_quantity'), 'pro_consumption_details.product_id as product_id')
                        ->groupBy('pro_consumption_details.product_id')->whereIn('pro_consumption_details.product_id', $productIdList)
                        ->pluck('total_quantity', 'product_id')->toArray();

        if (!empty($productIdList)) {
            foreach ($productIdList as $index => $productId) {
                $checkedIn = !empty($checkInArr[$productId]) ? $checkInArr[$productId] : 0;
                $consumed = !empty($consumeArr[$productId]) ? $consumeArr[$productId] : 0;
                $available = !empty($availableQuantityArr[$productId]) ? Helper::numberFormat($availableQuantityArr[$productId],6) : 0;
                $balance = $checkedIn - $consumed;
                $balanceArr[$productId]['quantity'] = $balance;
                $balance = Helper::numberFormat($balance,6);
                $productStatusArr['total'] = !empty($productStatusArr['total']) ? $productStatusArr['total'] : 0;
                $productStatusArr['total'] += 1;
                
                $productStatusArr['match'] = !empty($productStatusArr['match']) ? $productStatusArr['match'] : 0;
                $productStatusArr['match'] += ($balance == $available) ? 1 : 0;
                
                $productStatusArr['mismatch'] = !empty($productStatusArr['mismatch']) ? $productStatusArr['mismatch'] : 0;
                $productStatusArr['mismatch'] += ($balance == $available) ? 0 : 1;
                $balanceArr[$productId]['match'] = ($balance == $available) ? 1 : 0;
                
            }
        }


//            echo '<pre>';
//            print_r($balanceArr);
//            print_r($productStatusArr);
//            exit;
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[70][6])) {
                return redirect('dashboard');
            }
            return view('reconciliationReport.print.index')->with(compact('request', 'targetArr', 'productArr', 'balanceArr', 'productStatusArr'));
        } else {
            return view('reconciliationReport.index')->with(compact('request', 'targetArr', 'productArr', 'balanceArr', 'productStatusArr'));
        }
    }

    public function filter(Request $request) {
        $product = !empty($request->product) ? implode(",", $request->product) : '';
        return redirect('reconciliationReport?generate=true&product='
                . $product);
    }

    public function getSupplierManufacturer(Request $request) {
        //product wise supplier
        $loadView = 'reconciliationReport.showSupplierManufacturer';
        return Common::getSupplierManufacturer($request, $loadView);
    }

}
