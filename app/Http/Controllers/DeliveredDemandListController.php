<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductConsumptionMaster;
use App\ProductConsumptionDetails;
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

class DeliveredDemandListController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();
        $refNoArr = ProductConsumptionMaster::where('pro_consumption_master.status', 1)
                        ->where('pro_consumption_master.delivered', '1')
                        ->where('pro_consumption_master.source', '3')
                        ->select('voucher_no')->orderBy('voucher_no', 'asc')->get();
        $userArr = User::where('status', 1)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                        ->pluck('name', 'id')->toArray();

        $targetArr = ProductConsumptionMaster::where('pro_consumption_master.status', 1)
                ->where('pro_consumption_master.source', '3')
                ->where('pro_consumption_master.delivered', '1');

        //begin filtering
        if (!empty($request->voucher_no)) {
            $targetArr = $targetArr->where('pro_consumption_master.voucher_no', 'LIKE', '%' . $request->voucher_no . '%');
        }
        if (!empty($request->adjustment_date)) {
            $targetArr = $targetArr->where('pro_consumption_master.adjustment_date', '=', $request->adjustment_date);
        }

        if (($request->type) != '') {
            $targetArr = $targetArr->where('pro_consumption_master.delivered', '=', $request->type);
        }
        //end filtering
        $targetArr = $targetArr->select('pro_consumption_master.*')
                        ->orderBy('pro_consumption_master.id', 'desc')->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('deliveredDemandList?page=' . $page);
        }

        return view('substoreDemand.substoreList')->with(compact('targetArr', 'productArr', 'refNoArr', 'userArr'));
    }

    public function filter(Request $request) {
        $url = 'voucher_no=' . $request->voucher_no . '&adjustment_date=' . $request->adjustment_date;
        return Redirect::to('deliveredDemandList?' . $url);
    }

    public function getProductDetails(Request $request, $demandIdForPrint = null) {
        $loadView = 'deliveredProductDetails';
        return Common::getProductDetails($request, $demandIdForPrint, $loadView, 53);
    }

    
}//EOF -Class
