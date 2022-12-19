<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCheckInMaster;
use App\ProductCheckInDetails;
use App\ProductConsumptionMaster;
use App\ProductConsumptionDetails;
use App\LotWiseConsumptionDetails;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use PDF;
use Illuminate\Http\Request;

class DetailedLedgerReportController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();

        $productArr = array('0' => __('label.SELECT_PRODUCT_OPT')) + Product::where('status', '1')
                        ->where('approval_status', 1)
                        ->orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();

        $ledgerArr = $balanceArr = $lotWiseBalanceArr = [];
        $previousBalance = $totalCheckIn = $totalProdAdjust = $totalSubstore = $totalBalance = [];

        $fromDate = $toDate = '';



        if ($request->generate == 'true') {
            $productId = $request->product_id;
            $fromDate = !empty($request->from_date) ? $request->from_date . ' 00:00:00' : '';
            $toDate = !empty($request->to_date) ? $request->to_date . ' 23:59:59' : '';

            //check in info
            $checkInInfo = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', '=', 'product_checkin_details.master_id')
                    ->join('supplier', 'supplier.id', '=', 'product_checkin_details.supplier_id')
                    ->select('supplier.name as supplier', 'product_checkin_master.challan_no', 'product_checkin_master.ref_no', 'product_checkin_master.source'
                            , 'product_checkin_master.created_at', 'product_checkin_details.quantity', 'product_checkin_details.rate'
                            , 'product_checkin_details.amount', 'product_checkin_details.lot_number', 'product_checkin_details.id')
                    ->where('product_checkin_details.product_id', $productId)
                    ->orderBy('product_checkin_master.created_at', 'asc')
                    ->orderBy('product_checkin_details.id', 'asc');

            if (!empty($fromDate) && !empty($toDate)) {
                $checkInInfo = $checkInInfo->whereBetween('product_checkin_master.created_at', [$fromDate, $toDate]);
            }

            $checkInInfo = $checkInInfo->get();

            if (!$checkInInfo->isEmpty()) {
                foreach ($checkInInfo as $ck) {
                    $source = $ck->source == '1' ? __('label.INITIAL_BALANCE') : __('label.CHECK_IN');
                    $ledgerArr[$ck->created_at][$ck->lot_number][$ck->rate]['checkin'][$ck->id]['quantity'] = $ck->quantity;
                    $ledgerArr[$ck->created_at][$ck->lot_number][$ck->rate]['checkin'][$ck->id]['amount'] = $ck->amount;
                    $ledgerArr[$ck->created_at][$ck->lot_number][$ck->rate]['checkin'][$ck->id]['rate'] = $ck->rate;
                    $ledgerArr[$ck->created_at][$ck->lot_number][$ck->rate]['checkin'][$ck->id]['supplier'] = $ck->supplier;
                    $ledgerArr[$ck->created_at][$ck->lot_number][$ck->rate]['checkin'][$ck->id]['challan_no'] = $ck->challan_no;
                    $ledgerArr[$ck->created_at][$ck->lot_number][$ck->rate]['checkin'][$ck->id]['ref_no'] = $ck->ref_no;
                    $ledgerArr[$ck->created_at][$ck->lot_number][$ck->rate]['checkin'][$ck->id]['source'] = $source;
                }
            }

            //production and adjustment info
            $prodAdjustInfo = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                    ->join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                    ->select('pro_consumption_master.voucher_no', 'pro_consumption_master.source', 'pro_consumption_master.created_at'
                            , 'pro_consumption_master.delivered_at', 'pro_consumption_lot_wise_details.quantity', 'pro_consumption_lot_wise_details.rate'
                            , 'pro_consumption_lot_wise_details.amount', 'pro_consumption_lot_wise_details.lot_number', 'pro_consumption_lot_wise_details.id')
                    ->where('pro_consumption_details.product_id', $productId)
                    ->whereIn('pro_consumption_master.source', ['1', '2'])
                    ->orderBy('pro_consumption_master.created_at', 'asc')
                    ->orderBy('pro_consumption_lot_wise_details.id', 'asc');
            if (!empty($fromDate) && !empty($toDate)) {
                $prodAdjustInfo = $prodAdjustInfo->whereBetween('pro_consumption_master.created_at', [$fromDate, $toDate]);
            }

            $prodAdjustInfo = $prodAdjustInfo->get();
            if (!$prodAdjustInfo->isEmpty()) {
                foreach ($prodAdjustInfo as $prad) {
                    $source = !empty($prad->source) ? ($prad->source == '1' ? __('label.ADJUSTMENT') : ($prad->source == '2' ? __('label.PRODUCTION') : '')) : '';
                    $ledgerArr[$prad->created_at][$prad->lot_number][$prad->rate]['consume'][$prad->id]['quantity'] = $prad->quantity;
                    $ledgerArr[$prad->created_at][$prad->lot_number][$prad->rate]['consume'][$prad->id]['amount'] = $prad->amount;
                    $ledgerArr[$prad->created_at][$prad->lot_number][$prad->rate]['consume'][$prad->id]['rate'] = $prad->rate;
                    $ledgerArr[$prad->created_at][$prad->lot_number][$prad->rate]['consume'][$prad->id]['ref_no'] = $prad->voucher_no;
                    $ledgerArr[$prad->created_at][$prad->lot_number][$prad->rate]['consume'][$prad->id]['source'] = $source;
                }
            }

            //substore info
            $substoreInfo = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                    ->join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                    ->select('pro_consumption_master.voucher_no', 'pro_consumption_master.source', 'pro_consumption_master.created_at'
                            , 'pro_consumption_master.delivered_at', 'pro_consumption_lot_wise_details.quantity', 'pro_consumption_lot_wise_details.rate'
                            , 'pro_consumption_lot_wise_details.amount', 'pro_consumption_lot_wise_details.lot_number', 'pro_consumption_lot_wise_details.id')
                    ->where('pro_consumption_details.product_id', $productId)
                    ->whereIn('pro_consumption_master.source', ['3'])
                    ->orderBy('pro_consumption_master.created_at', 'asc')
                    ->orderBy('pro_consumption_lot_wise_details.id', 'asc');

            if (!empty($fromDate) && !empty($toDate)) {
                $substoreInfo = $substoreInfo->whereBetween('pro_consumption_master.delivered_at', [$fromDate, $toDate]);
            }

            $substoreInfo = $substoreInfo->get();

            if (!$substoreInfo->isEmpty()) {
                foreach ($substoreInfo as $sub) {
                    $source = !empty($sub->source) ? ($sub->source == '3' ? __('label.SUBSTORE') : '') : '';
                    $ledgerArr[$sub->delivered_at][$sub->lot_number][$sub->rate]['substore'][$sub->id]['quantity'] = $sub->quantity;
                    $ledgerArr[$sub->delivered_at][$sub->lot_number][$sub->rate]['substore'][$sub->id]['amount'] = $sub->amount;
                    $ledgerArr[$sub->delivered_at][$sub->lot_number][$sub->rate]['substore'][$sub->id]['rate'] = $sub->rate;
                    $ledgerArr[$sub->delivered_at][$sub->lot_number][$sub->rate]['substore'][$sub->id]['ref_no'] = $sub->voucher_no;
                    $ledgerArr[$sub->delivered_at][$sub->lot_number][$sub->rate]['substore'][$sub->id]['source'] = $source;
                }
            }

            ksort($ledgerArr);

            //previous balance set
            if (!empty($fromDate)) {
                $previousCheckInInfo = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', '=', 'product_checkin_details.master_id')
                        ->select(DB::raw('SUM(product_checkin_details.quantity) as total_quantity')
                                , DB::raw('SUM(product_checkin_details.amount) as total_amount'))
                        ->where('product_checkin_details.product_id', $productId)
                        ->where('product_checkin_master.created_at', '<', $fromDate)
                        ->first();

                $previousProdAdjustInfo = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                        ->join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                        ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_quantity')
                                , DB::raw('SUM(pro_consumption_lot_wise_details.amount) as total_amount'))
                        ->where('pro_consumption_details.product_id', $productId)
                        ->whereIn('pro_consumption_master.source', ['1', '2'])
                        ->where('pro_consumption_master.created_at', '<', $fromDate)
                        ->first();

                $previousSubstoreInfo = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                        ->join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                        ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_quantity')
                                , DB::raw('SUM(pro_consumption_lot_wise_details.amount) as total_amount'))
                        ->where('pro_consumption_details.product_id', $productId)
                        ->whereIn('pro_consumption_master.source', ['3'])
                        ->where('pro_consumption_master.delivered_at', '<', $fromDate)
                        ->first();

                $previousCheckIn['quantity'] = !empty($previousCheckInInfo->total_quantity) ? $previousCheckInInfo->total_quantity : 0;
                $previousCheckIn['amount'] = !empty($previousCheckInInfo->total_amount) ? $previousCheckInInfo->total_amount : 0;

                $previousProdAdjust['quantity'] = !empty($previousProdAdjustInfo->total_quantity) ? $previousProdAdjustInfo->total_quantity : 0;
                $previousProdAdjust['amount'] = !empty($previousProdAdjustInfo->total_amount) ? $previousProdAdjustInfo->total_amount : 0;

                $previousSubstore['quantity'] = !empty($previousSubstoreInfo->total_quantity) ? $previousSubstoreInfo->total_quantity : 0;
                $previousSubstore['amount'] = !empty($previousSubstoreInfo->total_amount) ? $previousSubstoreInfo->total_amount : 0;

                $previousBalance['quantity'] = $previousCheckIn['quantity'] - ($previousProdAdjust['quantity'] + $previousSubstore['quantity']);
                $previousBalance['amount'] = $previousCheckIn['amount'] - ($previousProdAdjust['amount'] + $previousSubstore['amount']);
            }

            //end :: previous balance set
            //balance sheet
            if (!empty($ledgerArr)) {
                $previousBalance['quantity'] = !empty($previousBalance['quantity']) ? $previousBalance['quantity'] : 0;
                $previousBalance['amount'] = !empty($previousBalance['amount']) ? $previousBalance['amount'] : 0;
                $balance['quantity'] = $previousBalance['quantity'];
                $balance['amount'] = $previousBalance['amount'];
                foreach ($ledgerArr as $dateTime => $lotInfo) {
                    foreach ($lotInfo as $lotNumber => $rateInfo) {
                        foreach ($rateInfo as $rate => $typeInfo) {
                            foreach ($typeInfo as $type => $details) {
                                foreach ($details as $id => $info) {
                                    $checkIn['quantity'] = $checkIn['amount'] = 0;
                                    $prodAdjust['quantity'] = $prodAdjust['amount'] = 0;
                                    $substore['quantity'] = $substore['amount'] = 0;

                                    if ($type == 'checkin') {
                                        $checkIn['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                        $checkIn['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                                    } elseif ($type == 'consume') {
                                        $prodAdjust['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                        $prodAdjust['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                                    } elseif ($type == 'substore') {
                                        $substore['quantity'] = !empty($info['quantity']) ? $info['quantity'] : 0;
                                        $substore['amount'] = !empty($info['amount']) ? $info['amount'] : 0;
                                    }

                                    $balance['quantity'] = $balance['quantity'] + $checkIn['quantity'] - ($prodAdjust['quantity'] + $substore['quantity']);
                                    $balance['amount'] = $balance['amount'] + $checkIn['amount'] - ($prodAdjust['amount'] + $substore['amount']);

                                    $balanceArr[$dateTime][$lotNumber][$rate][$type][$id] = $balance;

                                    $totalCheckIn['quantity'] = !empty($totalCheckIn['quantity']) ? $totalCheckIn['quantity'] : 0;
                                    $totalCheckIn['quantity'] += $checkIn['quantity'];
                                    $totalCheckIn['amount'] = !empty($totalCheckIn['amount']) ? $totalCheckIn['amount'] : 0;
                                    $totalCheckIn['amount'] += $checkIn['amount'];

                                    $totalProdAdjust['quantity'] = !empty($totalProdAdjust['quantity']) ? $totalProdAdjust['quantity'] : 0;
                                    $totalProdAdjust['quantity'] += $prodAdjust['quantity'];
                                    $totalProdAdjust['amount'] = !empty($totalProdAdjust['amount']) ? $totalProdAdjust['amount'] : 0;
                                    $totalProdAdjust['amount'] += $prodAdjust['amount'];

                                    $totalSubstore['quantity'] = !empty($totalSubstore['quantity']) ? $totalSubstore['quantity'] : 0;
                                    $totalSubstore['quantity'] += $substore['quantity'];
                                    $totalSubstore['amount'] = !empty($totalSubstore['amount']) ? $totalSubstore['amount'] : 0;
                                    $totalSubstore['amount'] += $substore['amount'];

                                    $totalBalance['quantity'] = !empty($totalBalance['quantity']) ? $totalBalance['quantity'] : 0;
                                    $totalBalance['quantity'] = $previousBalance['quantity'] + $totalCheckIn['quantity'] - ($totalProdAdjust['quantity'] + $totalSubstore['quantity']);
                                    $totalBalance['amount'] = !empty($totalBalance['amount']) ? $totalBalance['amount'] : 0;
                                    $totalBalance['amount'] = $previousBalance['amount'] + $totalCheckIn['amount'] - ($totalProdAdjust['amount'] + $totalSubstore['amount']);
                                }
                            }
                        }
                    }
                }
            }

            //end :: balance sheet

            $lotWiseCheckinInfo = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', '=', 'product_checkin_details.master_id')
                    ->select(DB::raw('SUM(product_checkin_details.quantity) as total_quantity')
                            , DB::raw('SUM(product_checkin_details.amount) as total_amount')
                            , 'product_checkin_details.lot_number')
                    ->groupBy('product_checkin_details.lot_number')
                    ->where('product_checkin_details.product_id', $productId);

            $lotWiseProdAdjustInfo = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                    ->join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                    ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_quantity')
                            , DB::raw('SUM(pro_consumption_lot_wise_details.amount) as total_amount')
                            , 'pro_consumption_lot_wise_details.lot_number')
                    ->groupBy('pro_consumption_lot_wise_details.lot_number')
                    ->where('pro_consumption_details.product_id', $productId)
                    ->whereIn('pro_consumption_master.source', ['1', '2']);

            $lotWiseSubstoreInfo = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                    ->join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                    ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_quantity')
                            , DB::raw('SUM(pro_consumption_lot_wise_details.amount) as total_amount')
                            , 'pro_consumption_lot_wise_details.lot_number')
                    ->groupBy('pro_consumption_lot_wise_details.lot_number')
                    ->where('pro_consumption_details.product_id', $productId)
                    ->whereIn('pro_consumption_master.source', ['3']);

            if (!empty($toDate)) {
                $lotWiseCheckinInfo = $lotWiseCheckinInfo->where('product_checkin_master.created_at', '<', $toDate);
                $lotWiseProdAdjustInfo = $lotWiseProdAdjustInfo->where('pro_consumption_master.created_at', '<', $toDate);
                $lotWiseSubstoreInfo = $lotWiseSubstoreInfo->where('pro_consumption_master.delivered_at', '<', $toDate);
            }

            $lotWiseCheckinInfo = $lotWiseCheckinInfo->get();
            $lotWiseProdAdjustInfo = $lotWiseProdAdjustInfo->get();
            $lotWiseSubstoreInfo = $lotWiseSubstoreInfo->get();

            if (!$lotWiseCheckinInfo->isEmpty()) {
                foreach ($lotWiseCheckinInfo as $lwck) {
                    $lotWiseLedgerArr[$lwck->lot_number]['checkin']['quantity'] = $lwck->total_quantity;
                    $lotWiseLedgerArr[$lwck->lot_number]['checkin']['amount'] = $lwck->total_amount;
                }
            }
            if (!$lotWiseProdAdjustInfo->isEmpty()) {
                foreach ($lotWiseProdAdjustInfo as $lwproad) {
                    $lotWiseLedgerArr[$lwproad->lot_number]['consume']['quantity'] = $lwproad->total_quantity;
                    $lotWiseLedgerArr[$lwproad->lot_number]['consume']['amount'] = $lwproad->total_amount;
                }
            }
            if (!$lotWiseSubstoreInfo->isEmpty()) {
                foreach ($lotWiseSubstoreInfo as $lwsub) {
                    $lotWiseLedgerArr[$lwsub->lot_number]['substore']['quantity'] = $lwsub->total_quantity;
                    $lotWiseLedgerArr[$lwsub->lot_number]['substore']['amount'] = $lwsub->total_amount;
                }
            }

            if (!empty($lotWiseLedgerArr)) {
                foreach ($lotWiseLedgerArr as $lotNumber => $typeInfo) {
                    foreach ($typeInfo as $type => $info) {
                        $lotWiseCheckin[$lotNumber]['quantity'] = !empty($lotWiseCheckin[$lotNumber]['quantity']) ? $lotWiseCheckin[$lotNumber]['quantity'] : 0;
                        $lotWiseCheckin[$lotNumber]['amount'] = !empty($lotWiseCheckin[$lotNumber]['amount']) ? $lotWiseCheckin[$lotNumber]['amount'] : 0;
                        $lotWiseProdAdjust[$lotNumber]['quantity'] = !empty($lotWiseProdAdjust[$lotNumber]['quantity']) ? $lotWiseProdAdjust[$lotNumber]['quantity'] : 0;
                        $lotWiseProdAdjust[$lotNumber]['amount'] = !empty($lotWiseProdAdjust[$lotNumber]['amount']) ? $lotWiseProdAdjust[$lotNumber]['amount'] : 0;
                        $lotWiseSubstore[$lotNumber]['quantity'] = !empty($lotWiseSubstore[$lotNumber]['quantity']) ? $lotWiseSubstore[$lotNumber]['quantity'] : 0;
                        $lotWiseSubstore[$lotNumber]['amount'] = !empty($lotWiseSubstore[$lotNumber]['amount']) ? $lotWiseSubstore[$lotNumber]['amount'] : 0;
                                        
                        if ($type == 'checkin') {
                            $lotWiseCheckin[$lotNumber]['quantity'] += !empty($info['quantity']) ? $info['quantity'] : 0;
                            $lotWiseCheckin[$lotNumber]['amount'] += !empty($info['amount']) ? $info['amount'] : 0;
                        } elseif ($type == 'consume') {
                            $lotWiseProdAdjust[$lotNumber]['quantity'] += !empty($info['quantity']) ? $info['quantity'] : 0;
                            $lotWiseProdAdjust[$lotNumber]['amount'] += !empty($info['amount']) ? $info['amount'] : 0;
                        } elseif ($type == 'substore') {
                            $lotWiseSubstore[$lotNumber]['quantity'] += !empty($info['quantity']) ? $info['quantity'] : 0;
                            $lotWiseSubstore[$lotNumber]['amount'] += !empty($info['amount']) ? $info['amount'] : 0;
                        }
                        
                        $lotWiseBalance['quantity'] = $lotWiseCheckin[$lotNumber]['quantity'] - ($lotWiseProdAdjust[$lotNumber]['quantity'] + $lotWiseSubstore[$lotNumber]['quantity']);
                        $lotWiseBalance['amount'] = $lotWiseCheckin[$lotNumber]['amount'] - ($lotWiseProdAdjust[$lotNumber]['amount'] + $lotWiseSubstore[$lotNumber]['amount']);
                        
                        $lotWiseBalanceArr[$lotNumber]['quantity'] = !empty($lotWiseBalanceArr[$lotNumber]['quantity']) ? $lotWiseBalanceArr[$lotNumber]['quantity'] : 0;
                        $lotWiseBalanceArr[$lotNumber]['quantity'] = !empty($lotWiseBalance['quantity']) ? $lotWiseBalance['quantity'] : 0;
                        $lotWiseBalanceArr[$lotNumber]['amount'] = !empty($lotWiseBalanceArr[$lotNumber]['amount']) ? $lotWiseBalanceArr[$lotNumber]['amount'] : 0;
                        $lotWiseBalanceArr[$lotNumber]['amount'] = !empty($lotWiseBalance['amount']) ? $lotWiseBalance['amount'] : 0;
                    }
                }
            }

//            echo '<pre>';
//            print_r($lotWiseLedgerArr);
//            print_r($lotWiseBalanceArr);
//            exit;
        }


        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[71][6])) {
                return redirect('dashboard');
            }
            return view('detailedLedgerReport.print.index')->with(compact('productArr', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'request', 'lotWiseBalanceArr'));
        } else {
            return view('detailedLedgerReport.index')->with(compact('productArr', 'qpArr'
                                    , 'ledgerArr', 'previousBalance', 'balanceArr', 'totalBalance'
                                    , 'request', 'lotWiseBalanceArr'));
        }
    }

    public
            function filter(Request $request) {
//        $messages = [];
        $rules = [
            'product_id' => 'required|not_in:0',
        ];

//        if (!empty($request->from_date)) {
//            $rules['to_date'] = 'required';
//            $messages['to_date.required'] = __('label.THE_TO_DATE_FIELD_IS_REQUIRED');
//        }
//        if (!empty($request->to_date)) {
//            $rules['from_date'] = 'required';
//            $messages['from_date.required'] = __('label.THE_FROM_DATE_FIELD_IS_REQUIRED');
//        }

        $url = 'product_id=' . $request->product_id . '&from_date=' . $request->from_date
                . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('detailedLedgerReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }

        return Redirect::to('detailedLedgerReport?generate=true&' . $url);
    }

}
