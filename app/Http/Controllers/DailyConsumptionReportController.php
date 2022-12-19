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
use Common;
use Input;
use Illuminate\Http\Request;

class DailyConsumptionReportController extends Controller {

    public function index(Request $request) {
        if ($request->generate == 'true') {

            if (empty($request->from_date) || empty($request->to_date)) {
                Session::flash('error', __('label.DATE_MUST_BE_SELECTED'));
                return redirect('dailyConsumptionReport?from_date=' . $request->from_date . '&to_date=' . $request->to_date);
            }

            if ($request->from_date > $request->to_date) {
                Session::flash('error', __('label.FROM_DATE_IS_GREATER_THAN_TO_DATE'));
                return redirect('dailyConsumptionReport?from_date=' . $request->from_date . '&to_date=' . $request->to_date);
            }

            $userInfo = User::select('id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as name"))
                        ->pluck('name', 'id')->toArray();
            
            $dataArr = ProductConsumptionDetails::join('pro_consumption_master', 'pro_consumption_details.master_id', '=', 'pro_consumption_master.id')
                            ->join('product', 'pro_consumption_details.product_id', '=', 'product.id')
                            ->where('pro_consumption_master.status', '1')
                            ->where('pro_consumption_master.source', '1')
                            ->where('product.status', '1')
                            ->where('product.approval_status', 1)
                            ->where('pro_consumption_master.adjustment_date', '>=', $request->from_date)
                            ->where('pro_consumption_master.adjustment_date', '<=', $request->to_date)
                            ->select('product.name'
                                    , 'pro_consumption_master.adjustment_date','pro_consumption_master.created_by'
                                    ,'pro_consumption_master.approved_at','pro_consumption_master.approved_by'
                                    , DB::raw('SUM(pro_consumption_details.quantity) as total')
                                    , 'pro_consumption_details.product_id'
                                    , 'pro_consumption_master.voucher_no'
                            )
                            ->groupBy('pro_consumption_details.product_id', 'name', 'pro_consumption_master.adjustment_date'
                                    ,'pro_consumption_master.created_by'
                                    , 'pro_consumption_master.voucher_no' ,'pro_consumption_master.approved_at'
                                    ,'pro_consumption_master.approved_by')
                            ->orderBy('pro_consumption_master.adjustment_date', 'desc')
                            ->orderBy('pro_consumption_master.voucher_no', 'asc')->get();
            $targetArr = [];
            if (!empty($dataArr)) {
                foreach ($dataArr as $item) {
                    $targetArr[$item->adjustment_date][$item->voucher_no]['data'][] = $item->toArray();
                    $targetArr[$item->adjustment_date][$item->voucher_no]['authority'] = array('adjustment_by' => $userInfo[$item->created_by],
                        'approved_by'=>isset($userInfo[$item->approved_by]) ?$userInfo[$item->approved_by] : '');
                }
            }//if
        } //if debug true
        

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if(empty($userAccessArr[58][6])){
                return redirect('dashboard');
            }
            return view('consumptionReport.print.dailyConsumption')->with(compact('request', 'targetArr', 'productInfoArr','userInfo'));
        } else {
            return view('consumptionReport.dailyConsumption')->with(compact('request', 'targetArr', 'productInfoArr','userInfo'));
        }
    }

    public function filter(Request $request) {
        return redirect('dailyConsumptionReport?generate=true&from_date='
                . $request->from_date . '&to_date=' . $request->to_date);
    }
}
