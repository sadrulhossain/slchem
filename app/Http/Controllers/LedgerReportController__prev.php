<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Product;
use App\ProductCheckInMaster;
use App\ProductCheckInDetails;
use App\ProductConsumptionMaster;
use App\ProductConsumptionDetails;
use App\LotWiseConsumptionDetails;
use Helper;
use DB;
use Auth;
use Common;
use PDF;
use Input;
use Illuminate\Http\Request;

class LedgerReportController extends Controller {

    public function index(Request $request) {
        $productArr = array('0' => __('label.SELECT_PRODUCT_OPT')) + Product::where('status', '1')
                        ->where('approval_status', 1)
                        ->orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        if ($request->generate == 'true') {

            if ((empty($request->product_id)) || (empty($request->from_date)) || (empty($request->to_date))) {
                Session::flash('error', __('label.PRODUCT_DATE_MUST_SELECT'));
                return redirect('ledgerReport');
            }

            //Get Previous Date for Count Before Date
            $prevDate = date('Y-m-d', strtotime('-1 days', strtotime($request->from_date)));

            //query for displaying before checkin data
            $bfCheckinArr = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', '=', 'product_checkin_details.master_id')
                            ->select(DB::raw('SUM(product_checkin_details.quantity) as quantity')
                                    , DB::raw('SUM(product_checkin_details.amount) as amount'))
                            ->where('product_checkin_master.checkin_date', '<=', $prevDate)
                            ->where('product_checkin_details.product_id', $request->product_id)
                            ->first()->toArray();

            //query for displaying before consumption data
            $bfConsumpArr = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                            ->join('pro_consumption_master', 'pro_consumption_master.id', 'pro_consumption_details.master_id')
                            ->where('pro_consumption_details.product_id', $request->product_id)
                            ->where('pro_consumption_master.adjustment_date', '<=', $prevDate)
                            ->where('pro_consumption_master.status', 1)
                            //->whereBetween('pro_consumption_master.source', ['1', '2','3'])
                            ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_qty')
                                    , DB::raw('SUM(pro_consumption_lot_wise_details.amount) as total_amount')
                            )
                            ->first()->toArray();

            //prepare array for displaying before checkin and before consumption data
            $bfTargetArr = [];
            $bfTargetArr['checkin_qty'] = $checkInQty = !empty($bfCheckinArr['quantity']) ? $bfCheckinArr['quantity'] : 0;
            $bfTargetArr['checkin_amount'] = $checkInAmnt = !empty($bfCheckinArr['amount']) ? $bfCheckinArr['amount'] : 0;
            $bfTargetArr['checkin_rate'] = ($checkInQty != 0) ? ($checkInAmnt / $checkInQty) : $checkInAmnt;
            $bfTargetArr['consump_qty'] = $issuedQty = !empty($bfConsumpArr['total_qty']) ? $bfConsumpArr['total_qty'] : 0;
            $bfTargetArr['consump_amount'] = $issuedAmnt = !empty($bfConsumpArr['total_amount']) ? $bfConsumpArr['total_amount'] : 0;
            $bfTargetArr['consump_rate'] = ($issuedQty != 0) ? ($issuedAmnt / $issuedQty) : $issuedAmnt;
            $bfTargetArr['consump_qty_balance'] = $balanceQty = $checkInQty - $issuedQty;
            $bfTargetArr['consump_amount_balance'] = $balanceAmnt = $checkInAmnt - $issuedAmnt;
            $bfTargetArr['consump_rate_balance'] = ($balanceQty != 0) ? ($balanceAmnt / $balanceQty) : $balanceAmnt;


            //making a date array from from_date and to_date
            $datesArr = array();
            $format = 'Y-m-d';
            $step = '+1 day';

            $current = strtotime($request->from_date);
            $last = strtotime($request->to_date);

            while ($current <= $last) {
                $datesArr[date($format, $current)] = 1;
                $current = strtotime($step, $current);
            }

            //query for displaying current checkin data
            $crCheckinArr = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', '=', 'product_checkin_details.master_id')
                            ->join('supplier', 'supplier.id', '=', 'product_checkin_details.supplier_id')
                            ->select('product_checkin_master.checkin_date', 'supplier.name as supplier_name'
                                    , 'product_checkin_master.challan_no', 'product_checkin_details.quantity'
                                    , 'product_checkin_details.amount', 'product_checkin_details.rate', 'product_checkin_details.lot_number')
                            ->where('product_checkin_details.product_id', $request->product_id)
                            ->whereBetween('product_checkin_master.checkin_date', [$request->from_date, $request->to_date])
                            ->get()->toArray();



            //query for displaying current consumption data
            $crConsumpArr = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                            ->join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                            ->select('pro_consumption_master.adjustment_date'
                                    , 'pro_consumption_lot_wise_details.rate'
                                    , DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as quantity')
                                    , DB::raw('SUM(pro_consumption_lot_wise_details.amount) as amount')
                                    , 'pro_consumption_lot_wise_details.lot_number')
                            ->where('pro_consumption_details.product_id', $request->product_id)
                            ->whereBetween('pro_consumption_master.adjustment_date', [$request->from_date, $request->to_date])
                            ->where('pro_consumption_master.status', 1)
                            //->whereBetween('pro_consumption_master.source', ['1', '2','3'])
                            ->groupBy('pro_consumption_master.adjustment_date'
                                    , 'pro_consumption_lot_wise_details.lot_number'
                                    , 'pro_consumption_lot_wise_details.rate')
                            ->orderBy('pro_consumption_master.adjustment_date', 'asc')
                            ->orderBy('pro_consumption_lot_wise_details.id', 'asc')
                            ->get()->toArray();


            //echo '<pre>';print_r($crConsumpArr);exit;
            //prepare data for displaying checkin side
            $crCheckinInfo = $daywiseCheckIn = [];

            $i = 0;
            $testCheckinDate = "";
            if (!empty($crCheckinArr)) {
                foreach ($crCheckinArr as $value) {
                    if ($testCheckinDate != $value['checkin_date']) {
                        $testCheckinDate = $value['checkin_date'];
                        $i = 0;
                    }
                    $crCheckinInfo[$value['checkin_date']][$i]['supplier'] = $value['supplier_name'];
                    $crCheckinInfo[$value['checkin_date']][$i]['challan_no'] = $value['challan_no'];
                    $crCheckinInfo[$value['checkin_date']][$i]['lot_number'] = $value['lot_number'];
                    $crCheckinInfo[$value['checkin_date']][$i]['quantity'] = $value['quantity'];
                    $crCheckinInfo[$value['checkin_date']][$i]['rate'] = $value['rate'];
                    $crCheckinInfo[$value['checkin_date']][$i]['amount'] = $value['amount'];
                    $i++;
                }
            }



            $j = 0;
            $testadjustmentDate = "";
            $crConsumpInfo = $daywiseConsump = [];
            if (!empty($crConsumpArr)) {
                foreach ($crConsumpArr as $item) {
                    if ($testadjustmentDate != $item['adjustment_date']) {
                        $testadjustmentDate = $item['adjustment_date'];
                        $j = 0;
                    }
                    $crConsumpInfo[$item['adjustment_date']][$j]['lot_number'] = $item['lot_number'];
                    $crConsumpInfo[$item['adjustment_date']][$j]['quantity'] = $item['quantity'];
                    $crConsumpInfo[$item['adjustment_date']][$j]['rate'] = $item['rate'];
                    $crConsumpInfo[$item['adjustment_date']][$j]['amount'] = $item['amount'];
                    $j++;
                }
            }

            

            //Form a complete tree with Date wise CheckIn and Consumption data
            $infoTree = [];
            foreach ($datesArr as $date => $val) {
                if (isset($crCheckinInfo[$date])) {
                    $infoTree[$date]['checkIn'] = $crCheckinInfo[$date];
                } else {
                    $infoTree[$date]['checkIn'] = [];
                }

                if (isset($crConsumpInfo[$date])) {
                    $infoTree[$date]['consumption'] = $crConsumpInfo[$date];
                } else {
                    $infoTree[$date]['consumption'] = [];
                }

                //Re-arrange the Date Array based on Left (checkIn)/Right (consumption) child count
                $datesArr[$date] = ((sizeof($infoTree[$date]['checkIn']) == 0) && (sizeof($infoTree[$date]['consumption']) == 0)) ? 1 : ((sizeof($infoTree[$date]['checkIn']) < sizeof($infoTree[$date]['consumption'])) ? sizeof($infoTree[$date]['consumption']) : sizeof($infoTree[$date]['checkIn']));
            }


            $balanceArr = [];

            $b = ($bfTargetArr['checkin_qty'] - $bfTargetArr['consump_qty']);

            $amount = $bfTargetArr['checkin_amount'] - $bfTargetArr['consump_amount'];
            $balanceInQty = Helper::commaSperateFormat($b, 6);
            $balanceInAmount = Helper::commaSperateFormat($amount, 6);

            foreach ($datesArr as $date => $rowCount) {
                for ($i = 0; $i < $rowCount; $i++) {
                    $balanceInQty = ($balanceInQty +
                            (empty($crCheckinInfo[$date][$i]['quantity']) ? 0 : $crCheckinInfo[$date][$i]['quantity'])) - (empty($crConsumpInfo[$date][$i]['quantity']) ? 0 : $crConsumpInfo[$date][$i]['quantity']);

                    $balanceInAmount = ($balanceInAmount +
                            ((empty($crCheckinInfo[$date][$i]['amount']) ? 0 : $crCheckinInfo[$date][$i]['amount']))) - (empty($crConsumpInfo[$date][$i]['amount']) ? 0 : $crConsumpInfo[$date][$i]['amount']);
                    $balanceArr[$date][$i]['quantity'] = Helper::commaSperateFormat($balanceInQty, 6);
                    $balanceArr[$date][$i]['amount'] = Helper::commaSperateFormat($balanceInAmount, 6);
                    $balanceArr[$date][$i]['rate'] = ($balanceInQty != 0) ? ($balanceInAmount / $balanceInQty) : 0;
                }
            }
        } //if debug true


        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[63][6])) {
                return redirect('dashboard');
            }
            return view('ledgerReport.print.ledgerReport')->with(compact('request', 'productArr', 'bfTargetArr'
                                    , 'datesArr', 'crCheckinInfo', 'crConsumpInfo', 'balanceQuantity', 'balanceAmount'
                                    , 'balanceArr', 'productName', 'daywiseConsump', 'infoTree'));
        } else {
            return view('ledgerReport.dailyStatus')->with(compact('request', 'productArr', 'bfTargetArr'
                                    , 'datesArr', 'crCheckinInfo', 'crConsumpInfo', 'balanceQuantity', 'balanceAmount'
                                    , 'balanceArr', 'daywiseConsump', 'infoTree'));
        }
    }

    public function filter(Request $request) {
        return redirect('ledgerReport?generate=true&product_id='
                . $request->product_id . '&from_date=' . $request->from_date . '&to_date=' . $request->to_date);
    }

}
