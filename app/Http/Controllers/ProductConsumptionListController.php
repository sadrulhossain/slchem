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
use Session;
use Common;
use Helper;
use Redirect;
use Illuminate\Http\Request;

class ProductConsumptionListController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Approved'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];

    public function index(Request $request) {
        $qpArr = $request->all();
        $productArr = Product::pluck('name', 'id')->toArray();
        $statusList = array('' => __('label.SELECT_STATUS_OPT'), '0' => 'Pending for Approval', '1' => 'Approved');
        $refNoArr = ProductConsumptionMaster::where('pro_consumption_master.source', '1')->select('voucher_no')->orderBy('voucher_no', 'asc')->get();

        $targetArr = ProductConsumptionMaster::join('users', 'users.id', '=', 'pro_consumption_master.created_by');

        //begin filtering
        if (!empty($request->ref_no)) {
            $targetArr = $targetArr->where('pro_consumption_master.voucher_no', 'LIKE', '%' . $request->ref_no . '%');
        }
        if (!empty($request->adjustment_date)) {
            $targetArr = $targetArr->where('pro_consumption_master.adjustment_date', '=', $request->adjustment_date);
        }
        //end filtering
        $targetArr = $targetArr->select('pro_consumption_master.*', 'pro_consumption_master.status'
                                , 'pro_consumption_master.created_at', 'pro_consumption_master.adjustment_date', 'users.first_name', 'users.last_name')
                        ->where('pro_consumption_master.source', '1')
                        ->orderBy('pro_consumption_master.id', 'desc')->paginate(Session::get('paginatorCount'));

        $statusArr = $this->statusArr;
//change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/productConsumptionList?page=' . $page);
        }

        return view('productConsumption.consumptionList')->with(compact('targetArr', 'statusArr', 'productArr', 'refNoArr', 'statusList'));
    }

    public function filter(Request $request) {
        $url = 'ref_no=' . $request->ref_no . '&adjustment_date=' . $request->adjustment_date;
        return Redirect::to('productConsumptionList?' . $url);
    }
    
    public function getProductConsumptionDetails(Request $request) {
        return Common::getProductConsumptionDetails($request);
    }
}//EOF -Class
