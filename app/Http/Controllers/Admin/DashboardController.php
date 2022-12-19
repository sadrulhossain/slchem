<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\User;
use App\Configuration;
use App\LotWiseConsumptionDetails;
use App\ProductConsumptionMaster;
use App\ProductConsumptionDetails;
use App\Product;
use App\ProductCheckInDetails;
use App\ProductCheckInMaster;
use App\BatchCard;
use App\Demand;
use App\Certificate;
use App\ProductToCertificate;
use App\Buyer;
use App\ProductToGl;
use App\Recipe;
use App\BatchRecipe;
use App\Factory;
use App\MachineModel;
use App\GarmentsType;
use App\Shade;
use App\Color;
use App\WashType;
use App\Shift;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Debugbar;
use Helper;
use DB;
use DateTime;

class DashboardController extends Controller {

    public function __construct() {
        //$this->middleware('auth');
    }

    public function index() {
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $deliveryTime = date('H:i:s');
        if (strtotime($deliveryTime) <= strtotime($setCutOffTime->check_in_time)) {
            $toDate = (date('d F Y', strtotime("-1 days")));
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("-1 days"));
            $currentDate = date('Y-m-d ' . $setCutOffTime->check_in_time);
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
        } else {
            $toDate = date('d F Y');
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
            $currentDate = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("+1 days"));
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("+1 days"));
        }

        $sevenDaysAgo = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime('-6 days'));
        $fifteenDaysAgo = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime('-14 days'));

        // Todays Recipie Count
        $activesRecipieCount = Recipe::select('id', 'status', 'approval_status', 'created_at')
                ->where('status', '1')
//                ->where('approval_status', '2')
//                ->whereBetween('created_at', [$currentDay, $nextDay])
                ->count();

        // Todays Batch Card Count
        $todaysBatchCardCount = BatchCard::select('id', 'status', 'created_at')
                ->where('status', '1')
                ->whereBetween('created_at', [$currentDay, $nextDay])
                ->count();

        // Todays Total Batch Card with Demand Letter Count
        $todaysTotalDemandLetterInfo = Demand::select(DB::raw("DISTINCT batch_card_id"))
//                ->where('status', '1')
                ->whereBetween('created_at', [$currentDay, $nextDay])
                ->get();
        $todaysTotalBatchCardWithDemandLetterCount = !empty($todaysTotalDemandLetterInfo) ? sizeof($todaysTotalDemandLetterInfo) : 0;
//        echo '<pre>';        print_r($todaysTotalBatchCardWithDemandLetterCount);exit;
        // Todays Deliverd Store Demand Letter Count
        $todaysDeliverdStoreDemandLetterCount = ProductConsumptionMaster::select('id', 'status', 'source', 'delivered_at')
                ->where('status', '1')
                ->where('source', '3')
                ->whereBetween('delivered_at', [$currentDay, $nextDay])
                ->count();

        // Total Active Products Count
        $totalActiveProductsCount = Product::select('id', 'status', 'available_quantity', 'reorder_level')
                ->where('status', '1')
//                ->whereColumn('available_quantity', '<', 'reorder_level')
                ->count();

        // Low Quantity Products Count
        $lowQuantityProductsCount = Product::select('id', 'status', 'available_quantity', 'reorder_level')
                ->where('status', '1')
                ->whereColumn('available_quantity', '<', 'reorder_level')
                ->count();

        // Todays Total Batch Card Quantity
        $todaysToalBatchCardQuantity = BatchRecipe::join('batch_card', 'batch_card.id', '=', 'batch_recipe.batch_card_id')
                ->where('batch_card.status', '1')
                ->whereBetween('batch_card.created_at', [$currentDay, $nextDay])
                ->select(DB::raw('SUM(batch_recipe.wash_lot_quantity_piece) as piecesum'))
                //->groupBy('batch_recipe.batch_card_id')
                ->get();


        //  Total Batch Card Quantity(pcs)
        $todaysToalBatchCardQuantity = BatchRecipe::join('batch_card', 'batch_card.id', '=', 'batch_recipe.batch_card_id')
                ->where('batch_card.status', '1')
                ->whereBetween('batch_card.created_at', [$currentDay, $nextDay])
                ->select(DB::raw('SUM(batch_recipe.wash_lot_quantity_piece) as piecesum'))
                //->groupBy('batch_recipe.batch_card_id')
                ->first();
//        echo '<pre>';print_r($todaysToalBatchCardQuantity);exit;
//        
//        
        //Total Reconciliation Mismatch
        $productArr = Product::orderBy('name', 'asc')->where('status', '1');
        $productIdArr = $productArr->pluck('id', 'id')->toArray();
        $productArr = $productArr->pluck('name', 'id')->toArray();

        $targetArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->leftJoin('product_function', 'product_function.id', '=', 'product.product_function_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.primary_unit_id')
                        ->where('product.status', '1')->where('product.approval_status', 1)
                        ->whereIn('product.id', $productIdArr)
                        ->select('product.name as product', 'product.id', 'product.available_quantity', 'product.product_code'
                                , 'measure_unit.name as unit_name', 'product_category.name as product_category')
                        ->orderBy('product', 'asc')->get();

        $availableQuantityArr = $balanceArr = $productStatusArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $item) {
                $availableQuantityArr[$item->id] = $item->available_quantity;
            }
        }

        $checkInArr = ProductCheckInDetails::select(DB::raw('SUM(quantity) as total_quantity'), 'product_id')
                        ->groupBy('product_id')->whereIn('product_id', $productIdArr)
                        ->pluck('total_quantity', 'product_id')->toArray();

        $consumeArr = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                        ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_quantity'), 'pro_consumption_details.product_id as product_id')
                        ->groupBy('pro_consumption_details.product_id')->whereIn('pro_consumption_details.product_id', $productIdArr)
                        ->pluck('total_quantity', 'product_id')->toArray();

        if (!empty($productIdArr)) {
            foreach ($productIdArr as $index => $productId) {
                $checkedIn = !empty($checkInArr[$productId]) ? $checkInArr[$productId] : 0;
                $consumed = !empty($consumeArr[$productId]) ? $consumeArr[$productId] : 0;
                $available = !empty($availableQuantityArr[$productId]) ? Helper::numberFormat($availableQuantityArr[$productId], 6) : 0;
                $balance = $checkedIn - $consumed;
                $balance = Helper::numberFormat($balance, 6);

                $productStatusArr['mismatch'] = !empty($productStatusArr['mismatch']) ? $productStatusArr['mismatch'] : 0;
                $productStatusArr['mismatch'] += ($balance == $available) ? 0 : 1;
            }
        }

        $productList = Product::where('status', '1')->where('approval_status', 1)->pluck('name', 'id')->toArray();
        $sourceList = ['1' => __('label.ADJUSTMENT'), '2' => __('label.PRODUCTION'), '3' => __('label.SUBSTORE')];

//        echo '<pre>';
//        print_r($productList);
//        exit;

        $prodConsumptionInfo = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                ->join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                ->select(DB::raw('SUM(pro_consumption_lot_wise_details.quantity) as total_qty')
                        , 'pro_consumption_master.source', 'pro_consumption_details.product_id')
                ->groupBy('pro_consumption_master.source')
                ->groupBy('pro_consumption_details.product_id')
                ->get();

        //START :: Make Product and Source Wise Total Qty 
        $sourceWiseTotalQty = [];
        $productCount = 0;
        if (!$prodConsumptionInfo->isEmpty()) {
            foreach ($prodConsumptionInfo as $consumedData) {
                $sourceWiseTotalQty[$consumedData->product_id][$consumedData->source] = !empty($consumedData->total_qty) ? $consumedData->total_qty : 0;
            }
        }
        //END :: Make Product and Source Wise Total Qty
        //START :: Make Product Wise Total Qty 
        $topConsumedProduct = [];
        if (!empty($sourceWiseTotalQty)) {
            foreach ($sourceWiseTotalQty as $productId => $value) {
                $topConsumedProduct[$productId] = !empty($value) ? $value : 0;
                $topConsumedProduct[$productId] = array_sum($value);
            }
        }
        arsort($topConsumedProduct);
        //START :: Make Product Wise Total Qty
        //START:: Get Top 10 Counsumed Product
        $top10ConsumedProductArr = [];
        $proCount = 0;
        if (!empty($topConsumedProduct)) {
            foreach ($topConsumedProduct as $productId => $qty) {
                if ($proCount < 10) {
                    $top10ConsumedProductArr[$productId] = !empty($qty) ? $qty : 0;
                }
                $proCount++;
            }
        }
        //END:: Get Top 10 Counsumed Product
        //***************** END :: Top 10 Most Consumed Product Data ***********************
        //******************** START:: Certificate Related Products ********//
        $certificateList = Certificate::pluck('name', 'id')->toArray();
        $productIdList = Product::where('status', '1')->where('approval_status', 1)->pluck('id', 'id')->toArray();

//        echo '<pre>';
//        print_r($productList);
//        exit;

        $certificateRelatedProductCountArr = Certificate::leftJoin('product_to_certificate', 'product_to_certificate.certificate_id', 'certificate.id')
                ->select('product_to_certificate.product_id', 'certificate.id as certificate_id')
                ->whereIn('product_to_certificate.product_id', $productIdList)
                ->get();


        $certificateWiseProductList = $certificateWiseProductArr = [];
        if (!$certificateRelatedProductCountArr->isEmpty()) {
            foreach ($certificateRelatedProductCountArr as $info) {
                $certificateWiseProductList[$info->certificate_id][$info->product_id] = $info->product_id;
            }
        }


        if (!empty($certificateList)) {
            foreach ($certificateList as $certificateId => $certificateName) {
                $certificateWiseProductArr[$certificateId] = !empty($certificateWiseProductList[$certificateId]) ? count($certificateWiseProductList[$certificateId]) : 0;
            }
        }

//        echo '<pre>';
//        print_r($certificateWiseProductArr);
//        exit;
        //******************** END:: Certificate Related Products ********//
        //************************** Start :: last 7 days checkin summary **************************//
        $beginMonthDay = new DateTime($sevenDaysAgo);
        $endMonthDay = new DateTime($currentDate);

        $lastSevenDaysCheckedinInfo = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_master.id', 'product_checkin_details.master_id')
                ->select(DB::raw('SUM(product_checkin_details.quantity) as total_quantity'), 'product_checkin_details.product_id'
                        , 'product_checkin_master.created_at')
                ->groupBy('product_checkin_details.product_id', 'product_checkin_master.created_at')
                ->whereBetween('product_checkin_master.created_at', [$beginMonthDay, $endMonthDay])
                ->get();
        $lastSevenDaysCheckedinproductList = ProductCheckInDetails::join('product', 'product.id', 'product_checkin_details.product_id')
                        ->join('product_checkin_master', 'product_checkin_master.id', 'product_checkin_details.master_id')
                        ->whereBetween('product_checkin_master.created_at', [$beginMonthDay, $endMonthDay])
                        ->pluck('product.name', 'product_checkin_details.product_id')->toArray();

        $lastSevenDaysCheckedinArr = [];
        if (!$lastSevenDaysCheckedinInfo->isEmpty()) {
            foreach ($lastSevenDaysCheckedinInfo as $ck) {
                $createdAt = date('Y-m-d', strtotime($ck->created_at));
                $lastSevenDaysCheckedinArr[$createdAt][$ck->product_id] = !empty($lastSevenDaysCheckedinArr[$createdAt][$ck->product_id]) ? $lastSevenDaysCheckedinArr[$createdAt][$ck->product_id] : 0;
                $lastSevenDaysCheckedinArr[$createdAt][$ck->product_id] += $ck->total_quantity;
            }
        }

        for ($j = $beginMonthDay; $j <= $endMonthDay; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $dayArr[$day] = $j->format("j M Y");
        }

        //************************** End :: last 7 days checkin summary ****************************//
        //************************** Start :: last 5 generated batch card summary **************************//
        $batchCardStatusList = [
            'generated' => __('label.GENERATED_DEMAND_LETTER'),
            'deliverable' => __('label.DELIVERABLE_DEMAND_LETTER'),
            'delivered' => __('label.DELIVERED_DEMAND_LETTER'),
        ];

        $lastTenBatchCardList = BatchCard::orderBy('created_at', 'desc')->limit(10);
        $lastTenBatchCardIdList = $lastTenBatchCardList->pluck('id', 'id')->toArray();
        $lastTenBatchCardList = $lastTenBatchCardList->pluck('reference_no', 'id')->toArray();

        $lastTenBatchCardInfo = Demand::select('batch_card_id', 'status')
                ->whereIn('batch_card_id', $lastTenBatchCardIdList)
                ->get();

        $lastTenBatchCardArr = [];
        if (!$lastTenBatchCardInfo->isEmpty()) {
            foreach ($lastTenBatchCardInfo as $lFInfo) {
                $deliverable = ($lFInfo->status == '0') ? 1 : 0;
                $delivered = ($lFInfo->status == '1') ? 1 : 0;
                $batchCardId = $lFInfo->batch_card_id;
                $lastTenBatchCardArr[$batchCardId]['deliverable'] = !empty($lastTenBatchCardArr[$batchCardId]['deliverable']) ? $lastTenBatchCardArr[$batchCardId]['deliverable'] : 0;
                $lastTenBatchCardArr[$batchCardId]['deliverable'] += $deliverable;
                $lastTenBatchCardArr[$batchCardId]['delivered'] = !empty($lastTenBatchCardArr[$batchCardId]['delivered']) ? $lastTenBatchCardArr[$batchCardId]['delivered'] : 0;
                $lastTenBatchCardArr[$batchCardId]['delivered'] += $delivered;
                $lastTenBatchCardArr[$batchCardId]['generated'] = !empty($lastTenBatchCardArr[$batchCardId]['generated']) ? $lastTenBatchCardArr[$batchCardId]['generated'] : 0;
                $lastTenBatchCardArr[$batchCardId]['generated'] += 1;
            }
        }


        //************************** End :: last 5 generated batch card summary ****************************//
        //*********************************** START:: Assigned Buyer to Product Count ******************************//
        $buyerList = Buyer::where('status', 1)->pluck('name', 'id')->toArray();
        $buyerRelatedProductCountArr = Buyer::join('product_to_gl', 'product_to_gl.buyer_id', 'buyer.id')
                        ->select(DB::raw("COUNT(DISTINCT product_to_gl.product_id) as total_product"), 'buyer.id as buyer_id')
                        ->whereIn('product_to_gl.product_id', $productIdList)
                        ->groupBy('buyer.id')
                        ->pluck('total_product', 'buyer_id')->toArray();
        //*********************************** END:: Assigned Buyer to Product Count ******************************//
        //************************** Start :: last 15 days substore demand summary **************************//
        $beginMonthDay = new DateTime($fifteenDaysAgo);
        $endMonthDay = new DateTime($currentDate);

        $substoreDemandStatusList = [
            'generated' => __('label.GENERATED_DEMAND_LETTER'),
            'delivered' => __('label.DELIVERED_DEMAND_LETTER'),
        ];
        $generatedSubstoreDemandInfo = ProductConsumptionMaster::select('created_at', 'id')
                ->where('source', '3')->where('delivered', '0')
                ->whereBetween('created_at', [$beginMonthDay, $endMonthDay])
                ->orderBy('created_at', 'asc')
                ->get();
        $deliveredSubstoreDemandInfo = ProductConsumptionMaster::select('delivered_at', 'id')
                ->where('source', '3')->where('delivered', '1')
                ->whereBetween('delivered_at', [$beginMonthDay, $endMonthDay])
                ->orderBy('delivered_at', 'asc')
                ->get();
        $lastFifteenDaysSubstoreDemandArr = [];
        if (!$generatedSubstoreDemandInfo->isEmpty()) {
            foreach ($generatedSubstoreDemandInfo as $gdl) {
                $createdAt = date('Y-m-d', strtotime($gdl->created_at));
                $lastFifteenDaysSubstoreDemandArr[$createdAt]['generated'] = !empty($lastFifteenDaysSubstoreDemandArr[$createdAt]['generated']) ? $lastFifteenDaysSubstoreDemandArr[$createdAt]['generated'] : 0;
                $lastFifteenDaysSubstoreDemandArr[$createdAt]['generated'] += 1;
            }
        }
        if (!$deliveredSubstoreDemandInfo->isEmpty()) {
            foreach ($deliveredSubstoreDemandInfo as $ddl) {
                $deliveredAt = date('Y-m-d', strtotime($ddl->delivered_at));
                $lastFifteenDaysSubstoreDemandArr[$deliveredAt]['delivered'] = !empty($lastFifteenDaysSubstoreDemandArr[$deliveredAt]['delivered']) ? $lastFifteenDaysSubstoreDemandArr[$deliveredAt]['delivered'] : 0;
                $lastFifteenDaysSubstoreDemandArr[$deliveredAt]['delivered'] += 1;
            }
        }
        ksort($lastFifteenDaysSubstoreDemandArr);
        for ($j = $beginMonthDay; $j <= $endMonthDay; $j->modify('+1 day')) {
            $day = $j->format("Y-m-d");
            $subDayArr[$day] = $j->format("j M Y");
        }
        //************************** End :: last 15 days substore demand summary ****************************//
//        echo '<pre>';
//        print_r($generatedSubstoreDemandInfo);
//        exit;

        return view('admin.dashboard')->with(compact('dayArr', 'lastSevenDaysCheckedinArr', 'lastSevenDaysCheckedinproductList'
                                , 'lastTenBatchCardArr', 'lastTenBatchCardList', 'batchCardStatusList', 'sourceWiseTotalQty'
                                , 'top10ConsumedProductArr', 'sourceList', 'productList', 'certificateList', 'certificateWiseProductArr'
                                , 'buyerRelatedProductCountArr', 'buyerList', 'activesRecipieCount', 'todaysBatchCardCount'
                                , 'todaysTotalBatchCardWithDemandLetterCount', 'todaysDeliverdStoreDemandLetterCount', 'totalActiveProductsCount', 'lowQuantityProductsCount'
                                , 'todaysToalBatchCardQuantity', 'productStatusArr', 'substoreDemandStatusList', 'lastFifteenDaysSubstoreDemandArr'
                                , 'subDayArr', 'toDate'));
    }

    public function getCertificateRelatedProducts(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
        $certificateInfo = Certificate::find($request->certificate_id);
        //product list
        $productIdArr = ProductToCertificate::join('product', 'product.id', 'product_to_certificate.product_id')
                ->join('product_category', 'product_category.id', 'product.product_category_id')
                ->join('product_function', 'product_function.id', 'product.product_function_id')
                ->where('product.status', '1')
                ->where('product.approval_status', 1)
                ->where('certificate_id', $request->certificate_id)
                ->select('product_to_certificate.product_id', 'product.name as product_name'
                        , 'product_category.name as product_category', 'product_function.name as product_function', 'product.available_quantity')
                ->distinct('product_to_certificate.product_id')
                ->get();


        $view = view('admin.certficateRelatedProducts.showCertificateRelatedProducts', compact('request', 'certificateInfo', 'productIdArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function getBuyerRelatedProducts(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
        $buyerInfo = Buyer::find($request->buyer_id);
        //echo '<pre>';print_r($buyerInfo);exit;
        //product list
        $productIdArr = ProductToGl::join('product', 'product.id', 'product_to_gl.product_id')
                ->join('product_category', 'product_category.id', 'product.product_category_id')
                ->join('product_function', 'product_function.id', 'product.product_function_id')
                ->where('product.status', '1')
                ->where('product.approval_status', 1)
                ->where('buyer_id', $request->buyer_id)
                ->select('product_to_gl.product_id', 'product.name as product_name'
                        , 'product_category.name as product_category', 'product_function.name as product_function'
                        , 'product_to_gl.rsl', 'product_to_gl.mrsl'
                )
                ->distinct('product_to_gl.product_id')
                ->orderBy('product_name', 'asc')
                ->get();

        //echo '<pre>';print_r($productIdArr->toArray());exit;
        $productInfoArr = [];
        if (!$productIdArr->isEmpty()) {
            foreach ($productIdArr as $item) {
                $rsl[$item->product_id] = !empty($rsl[$item->product_id]) ? $rsl[$item->product_id] : 0;
                $rsl[$item->product_id] += !empty($item->rsl) ? 1 : 0;

                $mrsl[$item->product_id] = !empty($mrsl[$item->product_id]) ? $mrsl[$item->product_id] : 0;
                $mrsl[$item->product_id] += !empty($item->mrsl) ? 1 : 0;

                $productInfoArr[$item->product_id]['product_category'] = $item->product_category;
                $productInfoArr[$item->product_id]['product_function'] = $item->product_function;
                $productInfoArr[$item->product_id]['product_name'] = $item->product_name;
                $productInfoArr[$item->product_id]['rsl'] = ($rsl[$item->product_id] != 0) ? 1 : 0;
                $productInfoArr[$item->product_id]['mrsl'] = ($mrsl[$item->product_id] != 0) ? 1 : 0;
            }
        }

        //echo '<pre>';print_r($productInfoArr);exit;


        $view = view('admin.buyerRelatedProducts.showBuyerRelatedProducts', compact('request', 'buyerInfo', 'productInfoArr'))->render();
        return response()->json(['html' => $view]);
    }

    // Start Todays Recipie Show in Modal
    public function totalActiveRecipieView(Request $request) {
//        $setCutOffTime = Configuration::select('check_in_time')->first();
//        $deliveryTime = date('H:i:s');
//        if (strtotime($deliveryTime) <= strtotime($setCutOffTime->check_in_time)) {
//            $toDate = (date('d F Y', strtotime("-1 days")));
//            $currentDay = date('Y-m-d '.$setCutOffTime->check_in_time, strtotime("-1 days"));
//            $nextDay = date('Y-m-d '.$setCutOffTime->check_in_time);
//        } else {
//            $toDate = date('d F Y');
//            $currentDay = date('Y-m-d '.$setCutOffTime->check_in_time);
//            $nextDay = date('Y-m-d '.$setCutOffTime->check_in_time, strtotime("+1 days"));
//        }

        $targetArr = Recipe::leftJoin('style', 'style.id', '=', 'recipe.style_id')
                ->leftJoin('factory', 'factory.id', '=', 'recipe.factory_id')
                ->leftJoin('buyer', 'buyer.id', '=', 'recipe.buyer_id')
                ->leftJoin('machine_model', 'machine_model.id', '=', 'recipe.machine_model_id')
                ->leftJoin('garments_type', 'garments_type.id', '=', 'recipe.garments_type_id')
                ->leftJoin('shade', 'shade.id', '=', 'recipe.shade_id')
                ->leftJoin('season', 'season.id', '=', 'recipe.season_id')
                ->leftJoin('color', 'color.id', '=', 'recipe.color_id')
                ->select('recipe.*', 'factory.name as factory', 'buyer.name as buyer', 'machine_model.name as machine_model'
                        , 'garments_type.name as garments_type', 'style.name as style'
                        , 'shade.name as shade', 'season.name as season'
                        , 'color.name as color')
//                ->where('recipe.approval_status', '2')
                ->where('recipe.status', '1')
//                ->where('approval_status', '2')
//                ->whereBetween('recipe.created_at', [$currentDay, $nextDay])
                ->orderBy('recipe.id', 'desc')
                ->get();
        $totalActiveRecipieCount = count($targetArr);
        $view = view('admin.dashboardModals.showTotalActiveRecipe', compact('targetArr', 'totalActiveRecipieCount'))->render();
        return response()->json(['html' => $view]);
    }

    // End Todays Recipie Show in Modal
    // Start Todays Batch Card Show in Modal
    public function todaysBatchCardView(Request $request) {
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $deliveryTime = date('H:i:s');
        if (strtotime($deliveryTime) <= strtotime($setCutOffTime->check_in_time)) {
            $toDate = (date('d F Y', strtotime("-1 days")));
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("-1 days"));
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
        } else {
            $toDate = date('d F Y');
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("+1 days"));
        }

        $targetArr = BatchCard::join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                ->join('season', 'season.id', '=', 'batch_recipe.season_id')
                ->join('color', 'color.id', '=', 'batch_recipe.color_id')
                ->select('batch_card.*', 'batch_recipe.reference_no as recipe_reference_no', 'batch_recipe.batch_card_id'
                        , 'batch_recipe.id as batch_recipe_id'
                        , 'style.name as style', 'color.name as color', 'season.name as season')
                ->where('batch_card.status', '1')
                ->whereBetween('batch_card.created_at', [$currentDay, $nextDay])
                ->orderBy('batch_recipe.id', 'desc')
                ->get();

        $todaysBatchCardCount = count($targetArr);

        $washTypeArr = ['0' => __('label.SELECT_WASH_TYPE_OPT')] + WashType::orderBy('id', 'asc')->pluck('name', 'id')->toArray();
        $shiftArr = ['0' => __('label.SELECT_SHIFT_OPT')] + Shift::orderBy('id', 'asc')->pluck('name', 'id')->toArray();
        $view = view('admin.dashboardModals.showTodaysBatchCard', compact('targetArr', 'washTypeArr', 'shiftArr', 'todaysBatchCardCount'))->render();
        return response()->json(['html' => $view]);
    }

    // End Todays Batch Card Show in Modal
    // Start Todays Batch Card With Demand Letter Show in Modal
    public function todaysBatchCardWithDemandLetterView(Request $request) {
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $deliveryTime = date('H:i:s');
        if (strtotime($deliveryTime) <= strtotime($setCutOffTime->check_in_time)) {
            $toDate = (date('d F Y', strtotime("-1 days")));
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("-1 days"));
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
        } else {
            $toDate = date('d F Y');
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("+1 days"));
        }

        $targetArr = Demand::join('batch_card', 'batch_card.id', '=', 'demand.batch_card_id')
                ->join('machine', 'machine.id', '=', 'batch_card.machine_id')
                ->join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                ->join('garments_type', 'garments_type.id', '=', 'batch_recipe.garments_type_id')
                ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')->orderBy('demand.id', 'desc')
                ->select(['demand.*', 'machine.machine_no', 'batch_card.date', 'style.name as style'
                    , 'buyer.name as buyer', 'buyer.logo as buyer_logo'
                    , 'demand.token_no'
                    , 'garments_type.name as garments_type', 'demand.status as demand_status'
                    , 'batch_card.reference_no as batch_card'])
//                ->where('demand.status', '1')
                ->whereBetween('demand.created_at', [$currentDay, $nextDay])
                ->get();
        $batchCardDemandArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $target) {
                $batchCardDemandArr[$target->batch_card_id]['batch_card'] = $target->batch_card;
                $batchCardDemandArr[$target->batch_card_id]['date'] = $target->date;
                $batchCardDemandArr[$target->batch_card_id]['buyer'] = $target->buyer;
                $batchCardDemandArr[$target->batch_card_id]['machine_no'] = $target->machine_no;
                $batchCardDemandArr[$target->batch_card_id]['style'] = $target->style;
                $batchCardDemandArr[$target->batch_card_id]['garments_type'] = $target->garments_type;
                $batchCardDemandArr[$target->batch_card_id]['demand'][$target->id] = $target->toArray();
            }
        }

        $rowSpanArr = [];
        if (!empty($batchCardDemandArr)) {
            foreach ($batchCardDemandArr as $batchCardId => $batchCardInfo) {
                foreach ($batchCardInfo['demand'] as $demandId => $demandInfo) {
                    $rowSpanArr[$batchCardId] = !empty($rowSpanArr[$batchCardId]) ? $rowSpanArr[$batchCardId] : 0;
                    $rowSpanArr[$batchCardId] += 1;
                    $batchCardDemandArr[$batchCardId]['batch_status'] = !empty($batchCardDemandArr[$batchCardId]['batch_status']) ? $batchCardDemandArr[$batchCardId]['batch_status'] : 0;
                    $batchCardDemandArr[$batchCardId]['batch_status'] += (($demandInfo['demand_status'] == '1') ? 1 : 0);
                }
            }
        }
        
        
        $todaysTotalBatchCardWithDemandLetterCount = !empty($batchCardDemandArr) ? count($batchCardDemandArr) : 0;
        
        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();
        $view = view('admin.dashboardModals.showTodaysBatchCardWithDemandLetter', compact('batchCardDemandArr', 'todaysTotalBatchCardWithDemandLetterCount'
                        , 'rowSpanArr', 'userFirstNameArr', 'userLastNameArr'))->render();
        return response()->json(['html' => $view]);
    }

    // End Todays Deliverd Demand Letter Show in Modal
    // Start Todays Deliverd Store Demand Letter Show in Modal
    public function todaysDeliverdStoreDemandLetterView(Request $request) {
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $deliveryTime = date('H:i:s');
        if (strtotime($deliveryTime) <= strtotime($setCutOffTime->check_in_time)) {
            $toDate = (date('d F Y', strtotime("-1 days")));
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("-1 days"));
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
        } else {
            $toDate = date('d F Y');
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("+1 days"));
        }

        $targetArr = ProductConsumptionMaster::where('pro_consumption_master.status', 1)
                ->where('pro_consumption_master.source', '3')
                ->where('pro_consumption_master.delivered', '1')
                ->where('pro_consumption_master.status', '1')
                ->whereBetween('pro_consumption_master.delivered_at', [$currentDay, $nextDay])
                ->get();
        $userArr = User::where('status', 1)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                        ->pluck('name', 'id')->toArray();
        $todaysDeliverdStoreDemandLetterCount = count($targetArr);
        $view = view('admin.dashboardModals.showTodaysDeliverdStoreDemandLetter', compact('targetArr', 'userArr', 'todaysDeliverdStoreDemandLetterCount'))->render();
        return response()->json(['html' => $view]);
    }

    // End Todays Deliverd Store Demand Letter Show in Modal
    // Start Active Products Show in Modal
    public function TotalActiveProductsView(Request $request) {

        $targetArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->leftJoin('product_function', 'product_function.id', '=', 'product.product_function_id')
                ->select('product.*', 'product_category.name as product_category', 'product_function.name as product_function')
                ->where('product.status', '1')
//                ->whereColumn('product.available_quantity', '<', 'product.reorder_level')
                ->get();
        $totalActiveProductsCount = count($targetArr);
//        echo '<pre>';        print_r();exit;

        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();

        $view = view('admin.dashboardModals.showTotalActiveProducts', compact('targetArr', 'userFirstNameArr', 'userLastNameArr', 'totalActiveProductsCount'))->render();
        return response()->json(['html' => $view]);
    }

    // End Active Products Show in Modal
    // 
    // Start Low Quantity Products Show in Modal
    public function lowQuantityProductsView(Request $request) {

        $targetArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->leftJoin('product_function', 'product_function.id', '=', 'product.product_function_id')
                ->select('product.*', 'product_category.name as product_category', 'product_function.name as product_function')
                ->where('product.status', '1')
                ->whereColumn('product.available_quantity', '<', 'product.reorder_level')
                ->get();
        $lowQuantityProductsCount = count($targetArr);
//        echo '<pre>';        print_r();exit;

        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();

        $view = view('admin.dashboardModals.showLowQuantityProducts', compact('targetArr', 'userFirstNameArr', 'userLastNameArr', 'lowQuantityProductsCount'))->render();
        return response()->json(['html' => $view]);
    }

    // End Low Quantity Products Show in Modal
    // Start Todays Total Batch Card Quantity Show in Modal
    public function todaysTotalBatchCardQuantityView(Request $request) {
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $deliveryTime = date('H:i:s');
        if (strtotime($deliveryTime) <= strtotime($setCutOffTime->check_in_time)) {
            $toDate = (date('d F Y', strtotime("-1 days")));
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("-1 days"));
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
        } else {
            $toDate = date('d F Y');
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("+1 days"));
        }

        $targetArr = BatchCard::join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                ->join('season', 'season.id', '=', 'batch_recipe.season_id')
                ->join('color', 'color.id', '=', 'batch_recipe.color_id')
                ->whereBetween('batch_card.created_at', [$currentDay, $nextDay])
                ->select('batch_card.*', 'batch_recipe.reference_no as recipe_reference_no'
                        , 'batch_recipe.batch_card_id', 'batch_recipe.id as batch_recipe_id'
                        , 'batch_recipe.wash_lot_quantity_piece', 'style.name as style'
                        , 'batch_recipe.factory_id', 'batch_recipe.buyer_id'
                        , 'color.name as color', 'season.name as season')
                ->orderBy('batch_recipe.id', 'desc')
                ->orderBy('id', 'asc')
                ->get();
        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::pluck('name', 'id')->toArray();
        $washTypeArr = ['0' => __('label.SELECT_WASH_TYPE_OPT')] + WashType::orderBy('id', 'asc')->pluck('name', 'id')->toArray();
        $factoryArr = ['0' => __('label.SELECT_FACTORY_OPT')] + Factory::pluck('code', 'id')->toArray();
        $shiftArr = ['0' => __('label.SELECT_SHIFT_OPT')] + Shift::orderBy('id', 'asc')->pluck('name', 'id')->toArray();
        $totalQty = $this->getTotalQty($request->all());

        $view = view('admin.dashboardModals.showTodaysTotalBatchCardQuantity', compact('targetArr', 'shiftArr', 'buyerArr', 'factoryArr', 'washTypeArr', 'totalQty'))->render();
        return response()->json(['html' => $view]);
    }

    private static function getTotalQty() {
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $deliveryTime = date('H:i:s');
        if (strtotime($deliveryTime) <= strtotime($setCutOffTime->check_in_time)) {
            $toDate = (date('d F Y', strtotime("-1 days")));
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("-1 days"));
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
        } else {
            $toDate = date('d F Y');
            $currentDay = date('Y-m-d ' . $setCutOffTime->check_in_time);
            $nextDay = date('Y-m-d ' . $setCutOffTime->check_in_time, strtotime("+1 days"));
        }

        $itemArr = BatchCard::join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                ->whereBetween('batch_card.created_at', [$currentDay, $nextDay])
                ->sum('batch_recipe.wash_lot_quantity_piece');

        return $itemArr;
    }

    // End Todays Total Batch Card Quantity Show in Modal
    // Start Todays Reconciliation Mismatch Show in Modal
    public function todaysReconciliationMismatchView(Request $request) {

        $productArr = Product::orderBy('name', 'asc')->where('status', '1')
                ->where('approval_status', 1);
        $productIdList = $productArr->pluck('id', 'id')->toArray();
        $productArr = $productArr->pluck('name', 'id')->toArray();

        $targetArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->leftJoin('product_function', 'product_function.id', '=', 'product.product_function_id')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.primary_unit_id')
                        ->where('product.status', '1')->where('product.approval_status', 1)
                        ->whereIn('product.id', $productIdList)
                        ->select('product.name as product', 'product.id', 'product.available_quantity', 'product.product_code'
                                , 'measure_unit.name as unit_name', 'product_category.name as product_category')
                        ->orderBy('product', 'asc')->get();
//        echo '<pre>';        print_r($targetArr);exit;

        $availableQuantityArr = $balanceArr = $productStatusArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $item) {
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
                $available = !empty($availableQuantityArr[$productId]) ? Helper::numberFormat($availableQuantityArr[$productId], 6) : 0;
                $balance = $checkedIn - $consumed;
                $balanceArr[$productId]['quantity'] = $balance;
                $balance = Helper::numberFormat($balance, 6);
                $productStatusArr['total'] = !empty($productStatusArr['total']) ? $productStatusArr['total'] : 0;
                $productStatusArr['total'] += 1;



                $productStatusArr['match'] = !empty($productStatusArr['match']) ? $productStatusArr['match'] : 0;
                $productStatusArr['match'] += ($balance == $available) ? 1 : 0;

                $productStatusArr['mismatch'] = !empty($productStatusArr['mismatch']) ? $productStatusArr['mismatch'] : 0;
                $productStatusArr['mismatch'] += ($balance == $available) ? 0 : 1;
                $balanceArr[$productId]['match'] = ($balance == $available) ? 1 : 0;
            }
        }

        $view = view('admin.dashboardModals.showTodaysReconciliationMismatch', compact('request', 'targetArr', 'productArr', 'balanceArr', 'productStatusArr', 'checkInArr', 'consumeArr'))->render();
        return response()->json(['html' => $view]);
    }

    // End Todays Reconciliation Mismatch Show in Modal

    public function alluser() {
        $users = User::count();
        //dd($users);
        return view('admin.dashboard')->with(['users' => $users]);
    }

}
