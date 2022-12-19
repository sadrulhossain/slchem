<?php

namespace App\Http\Controllers;

use Validator;
use App\BatchCard;
use App\Demand;
use App\Machine;
use App\Shift;
use App\Factory;
use App\HydroMachine;
use App\DryerMachine;
use App\Product;
use App\ProductConsumptionMaster;
use App\ProductConsumptionDetails;
use App\ProductToProcess;
use App\Configuration;
use App\GarmentsType;
use App\User;
use App\Style;
use App\LotWiseConsumptionDetails;
use App\BatchRecipe;
use App\BatchRecipeToProcess;
use App\BatchRecipeToProduct;
use Auth;
use Session;
use Redirect;
use Response;
use Common;
use DB;
use Helper;
use Illuminate\Http\Request;

class DeliveredChemicalsListController extends Controller {

    private $statusArr = ['0' => 'Demand Generated', '1' => 'Delivered From Stock'];
    private $formulaArr = [1 => ['formula' => 'G/L', 'label' => 'success']
        , 2 => ['formula' => '%', 'label' => 'warning']
        , 3 => ['formula' => 'Direct Amount', 'label' => 'primary']
    ];

    public function index(Request $request) {
        $request->session()->forget('multiple_demand_id');
        //passing param for custom function
        $qpArr = $request->all();
        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();
        $targetArr = Demand::join('batch_card', 'batch_card.id', '=', 'demand.batch_card_id')
                ->join('machine', 'machine.id', '=', 'batch_card.machine_id')
                ->join('shift', 'shift.id', '=', 'batch_card.shift_id')
                ->join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                ->join('factory', 'factory.id', '=', 'batch_recipe.factory_id')
                ->join('garments_type', 'garments_type.id', '=', 'batch_recipe.garments_type_id')
                ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')
                ->orderBy('demand.id', 'desc')
                ->select(['demand.*', 'batch_card.date', 'shift.name as shift', 'factory.name as factory', 'style.name as style', 'buyer.name as buyer', 'buyer.logo as buyer_logo', 'demand.token_no'
                    , 'machine.machine_no', 'garments_type.name as garments_type', 'demand.status', 'batch_card.reference_no as batch_card'])
                ->where('demand.status', '1');

        //begin filtering

        $searchText = $request->search;

        $batchCardArr = ['0' => __('label.SELECT_BATCH_CARD_OPT')] + BatchCard::where('status', '1')->orderBy('id', 'desc')->pluck('reference_no', 'id')->toArray();
        $machineArr = ['0' => __('label.SELECT_MACHINE_OPT')] + Machine::pluck('machine_no', 'id')->toArray();
        $garmentsArr = ['0' => __('label.SELECT_GARMENTS_TYPE_OPT')] + GarmentsType::pluck('name', 'id')->toArray();
        $shiftArr = ['0' => __('label.SELECT_SHIFT_TYPE_OPT')] + Shift::pluck('name', 'id')->toArray();
        $factoryArr = ['0' => __('label.SELECT_FACTORY_TYPE_OPT')] + Factory::pluck('name', 'id')->toArray();
        $styleArr = ['0' => __('label.SELECT_STYLE_OPT')] + Style::pluck('name', 'id')->toArray();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('demand.token_no', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->batch_card_id)) {
            $targetArr = $targetArr->where('demand.batch_card_id', '=', $request->batch_card_id);
        }
        if (!empty($request->machine)) {
            $targetArr = $targetArr->where('batch_card.machine_id', '=', $request->machine);
        }
        if (!empty($request->date)) {
            $targetArr = $targetArr->where('demand.date', '=', $request->date);
        }
        if (!empty($request->garments)) {
            $targetArr = $targetArr->where('batch_recipe.garments_type_id', '=', $request->garments);
        }
        if (!empty($request->shift)) {
            $targetArr = $targetArr->where('batch_card.shift_id', '=', $request->shift);
        }
        if (!empty($request->factory)) {
            $targetArr = $targetArr->where('batch_recipe.factory_id', '=', $request->factory);
        }
        if (!empty($request->style)) {
            $targetArr = $targetArr->where('batch_recipe.style_id', '=', $request->style);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('deliveredChemicalsList?page=' . $page);
        }

        $statusArr = $this->statusArr;
        return view('demand.deliveredList')->with(compact('targetArr', 'qpArr', 'statusArr'
                                , 'request', 'tokenNoArr', 'batchCardArr', 'shiftArr', 'factoryArr', 'machineArr', 'garmentsArr', 'userFirstNameArr'
                                , 'userLastNameArr', 'styleArr', 'userFirstNameArr', 'userLastNameArr'));
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&batch_card_id=' . $request->batch_card_id 
                . '&batch_card_ref=' . $request->batch_card . '&date=' . $request->date 
                . '&machine=' . $request->machine . '&garments=' . $request->garments 
                . '&shift=' . $request->shift . '&factory=' . $request->factory 
                . '&style=' . $request->style;
        return Redirect::to('deliveredChemicalsList?' . $url);
    }

    public function details(Request $request, $demandIdForPrint = null) {
        $target = Demand::join('batch_card', 'batch_card.id', '=', 'demand.batch_card_id')
                ->join('machine', 'machine.id', '=', 'batch_card.machine_id')
                ->join('shift', 'shift.id', '=', 'batch_card.shift_id')
                ->join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                ->join('garments_type', 'garments_type.id', '=', 'batch_recipe.garments_type_id')
                ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')
                ->join('factory', 'factory.id', '=', 'batch_recipe.factory_id')
                ->orderBy('demand.id', 'asc')
                ->select(['demand.id', 'machine.machine_no', 'batch_card.date', 'style.name as style', 'shift.name as shift', 'factory.name as factory'
            , 'buyer.name as buyer', 'demand.token_no', 'garments_type.name as garments_type', 'demand.status'
            , 'batch_card.reference_no as batch_card', 'demand.rtp_id', 'batch_recipe.id as recipe_id'
            , 'batch_recipe.reference_no as recipe_no', 'batch_recipe.wash_lot_quantity_weight', 'batch_recipe.wash_lot_quantity_piece']);

        if (!empty($demandIdForPrint)) {//For Printing Purpose
            $target = $target->find($demandIdForPrint);
        } else {//For Detail Pop-Up
            $target = $target->find($request->demand_id);
        }

        $statusArr = $this->statusArr;

        $productArr = BatchRecipeToProduct::join('product', 'product.id', '=', 'batch_recipe_to_product.product_id')
                        ->where('batch_recipe_to_product.batch_rtp_id', $target->rtp_id)
                        ->select('product.id', 'product.name', 'total_qty', 'product.show_in_report')->get();


        $productWithLotArr = [];
        //Fetch Lot Information and form Node: Start
        if (!empty($productArr)) {
            foreach ($productArr as $product) {
                $lotInfoArr = ProductConsumptionDetails::join('pro_consumption_lot_wise_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id');
                if (!empty($demandIdForPrint)) {//For Printing Purpose
                    $lotInfoArr = $lotInfoArr->where('pro_consumption_details.demand_id', $demandIdForPrint);
                } else {//For Detail Pop-Up
                    $lotInfoArr = $lotInfoArr->where('pro_consumption_details.demand_id', $request->demand_id);
                }
                $lotInfoArr = $lotInfoArr->where('pro_consumption_details.product_id', $product->id)
                                ->select('pro_consumption_lot_wise_details.lot_number', 'pro_consumption_lot_wise_details.quantity'
                                        , 'pro_consumption_lot_wise_details.rate', 'pro_consumption_lot_wise_details.amount')->get();

                $productWithLotArr[$product->id] = $lotInfoArr->toArray();
            }//foreach           
        }
        //Fetch Lot Information and form Node: End
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if(empty($userAccessArr[50][6])){
                return redirect('dashboard');
            }
            return view('demand.print')->with(compact('target', 'id', 'statusArr', 'productArr', 'productWithLotArr'));
        }

        $view = view('demand.details', compact('target', 'id', 'statusArr', 'productArr', 'productWithLotArr'))->render();
        return response()->json(['html' => $view]);
    }

    
    public function makeMultiDemandId(Request $request) {
        $prevData = $request->session()->get('multiple_demand_id');
        if (!empty($prevData)) {
            $firstDemandId = current($prevData);

            $targetArr = Demand::find($firstDemandId);
            $batchCardArr = Demand::find($request->demand_id);

            if ($targetArr->batch_card_id != $batchCardArr->batch_card_id) {
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.PLEASE_SELECT_DEMAND_OF_SAME_BATCHCARD')], 401);
            }
        }
        //$demandVal = 0;
        if (!empty($request->demand_data)) {
            $request->session()->put('multiple_demand_id.' . $request->demand_id, $request->demand_id);
        } else {
            $request->session()->forget('multiple_demand_id.' . $request->demand_id);
        }
        return Response::json(['success' => true]);
    }

    //Added for Multiple Demand Details
    public function multipleDemandDetails(Request $request, $allItem = null) {
        $qpArr = $request->all();
        $demandIdArr = [];
        if (!empty($allItem)) {//For Printing Purpose
            $allItemArr = explode(",", $allItem);
            $demandIdArr = array_filter($allItemArr);
        } else {//For Detail Pop-Up
            if (isset($request->all_item)) {//if multiple demand selected
                $allItemArr = explode(",", $request->all_item);
                $demandIdArr = array_filter($allItemArr);
            }
        }
        
        $targetArr = Demand::join('batch_card', 'batch_card.id', '=', 'demand.batch_card_id')
                        ->join('machine', 'machine.id', '=', 'batch_card.machine_id')
                        ->join('shift', 'shift.id', '=', 'batch_card.shift_id')
                        ->join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                        ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                        ->join('garments_type', 'garments_type.id', '=', 'batch_recipe.garments_type_id')
                        ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')
                        ->join('factory', 'factory.id', '=', 'batch_recipe.factory_id')
                        ->orderBy('demand.id', 'asc')
                        ->select('demand.id', 'machine.machine_no', 'batch_card.date', 'style.name as style', 'shift.name as shift', 'factory.name as factory'
                                , 'buyer.name as buyer', 'demand.token_no', 'garments_type.name as garments_type', 'demand.status'
                                , 'batch_card.reference_no as batch_card', 'demand.rtp_id', 'batch_recipe.id as recipe_id'
                                , 'batch_recipe.reference_no as recipe_no', 'batch_recipe.wash_lot_quantity_weight', 'batch_recipe.wash_lot_quantity_piece')
                        ->whereIn('demand.id', $demandIdArr)->get();


        $statusArr = $this->statusArr;
        $productArr = [];
        foreach ($targetArr as $item) {
            $productArr[$item->id] = BatchRecipeToProduct::join('product', 'product.id', '=', 'batch_recipe_to_product.product_id')
                            ->where('batch_recipe_to_product.batch_rtp_id', $item->rtp_id)
                            ->select('product.id', 'product.name', 'total_qty', 'product.show_in_report')->get();
        }

        $productWithLotArr = [];
        //Fetch Lot Information and form Node: Start
        foreach ($targetArr as $item) {
            if (!empty($productArr[$item->id])) {
                foreach ($productArr[$item->id] as $product) {
                    $lotInfoArr = ProductConsumptionDetails::join('pro_consumption_lot_wise_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                                    ->where('pro_consumption_details.demand_id', $item->id)
                                    ->where('pro_consumption_details.product_id', $product->id)
                                    ->select('pro_consumption_lot_wise_details.lot_number', 'pro_consumption_lot_wise_details.quantity'
                                            , 'pro_consumption_lot_wise_details.rate', 'pro_consumption_lot_wise_details.amount')->get();

                    $productWithLotArr[$item->id][$product->id] = $lotInfoArr->toArray();
                }//foreach           
            }
        }
        //Fetch Lot Information and form Node: End
        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if(empty($userAccessArr[50][6])){
                return redirect('dashboard');
            }
            return view('demand.printMultiDemandDetails')->with(compact('targetArr', 'id', 'statusArr', 'productArr', 'productWithLotArr'));
        }

        $view = view('demand.multipleDemandDetails', compact('request', 'targetArr', 'id', 'statusArr', 'productArr', 'productWithLotArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function loadTokenforDelivered(Request $request) {
        $query = "%" . $request->search_keyword . "%";
        $tokenNumberArr = Demand::where('token_no', 'LIKE', $query)->where('status', '1')->latest()->take(20)->get(['token_no', 'id']);

        $view = view('demand.showTokenNo', compact('tokenNumberArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function loadBatchTokenForDeliveredDemand(Request $request) {
        $query = "%" . $request->search_keyword . "%";
        $tokenNumberArr = BatchCard::leftJoin('demand', 'batch_card.id', '=', 'demand.batch_card_id')
                        ->where('demand.status', '1')->where('batch_card.reference_no', 'LIKE', $query)
                        ->orderBy('batch_card.created_at', 'desc')->take(20)->distinct()->get(['batch_card.reference_no', 'batch_card.id']);

        $view = view('demand.showBatchNo', compact('tokenNumberArr'))->render();
        return response()->json(['html' => $view]);
    }

}
