<?php

namespace App\Http\Controllers;

use Validator;
use App\BatchCard;
use App\Demand;
use App\Recipe;
use App\Machine;
use App\HydroMachine;
use App\DryerMachine;
use App\Product;
use App\RecipeToProcess;
use App\RecipeToProduct;
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
use Common;
use Session;
use Redirect;
use Response;
use DB;
use Helper;
use Illuminate\Http\Request;

class DemandController extends Controller {

    private $statusArr = ['0' => 'Demand Generated', '1' => 'Delivered From Stock'];
    private $formulaArr = [1 => ['formula' => 'G/L', 'label' => 'success']
        , 2 => ['formula' => '%', 'label' => 'warning']
        , 3 => ['formula' => 'Direct Amount', 'label' => 'primary']
    ];

    public function index(Request $request) {

        //passing param for custom function
		$request->session()->forget('demand_id');
        $qpArr = $request->all();
        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();
        $filterStatusArr = ['' => __('label.SELECT_STATUS_OPT'), '0' => 'Demand Generated', '1' => 'Delivered From Stock'];
        $styleArr = ['0' => __('label.SELECT_STYLE_OPT')] + Style::pluck('name', 'id')->toArray();
        $batchCardArr = ['0' => __('label.SELECT_STYLE_OPT')];
        //begin filtering
        $searchText = $request->search;
        $targetArr = [];
        if (!empty($qpArr)) {
            $targetArr = Demand::join('batch_card', 'batch_card.id', '=', 'demand.batch_card_id')
                    ->join('machine', 'machine.id', '=', 'batch_card.machine_id')
                    ->join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                    ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                    ->join('garments_type', 'garments_type.id', '=', 'batch_recipe.garments_type_id')
                    ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')->orderBy('demand.id', 'desc')
                    ->select(['demand.*', 'machine.machine_no', 'batch_card.date', 'style.name as style'
                        , 'buyer.name as buyer', 'buyer.logo as buyer_logo'
                        , 'demand.token_no'
                        , 'garments_type.name as garments_type', 'demand.status'
                        , 'batch_card.reference_no as batch_card']);

            if (!empty($searchText)) {
                $targetArr->where(function ($query) use ($searchText) {
                    $query->where('token_no', 'LIKE', '%' . $searchText . '%');
                });
            }

            if (!empty($request->batch_card_id)) {
                $targetArr = $targetArr->where('demand.batch_card_id', '=', $request->batch_card_id);
            }
            if (!empty($request->style_id)) {
                $targetArr = $targetArr->where('batch_recipe.style_id', '=', $request->style_id);
            }

            if (!empty($request->date)) {
                $targetArr = $targetArr->where('demand.date', '=', $request->date);
            }
            if ($request->status != '') {
                $targetArr = $targetArr->where('demand.status', '=', $request->status);
            }
            //end filtering

            $targetArr = $targetArr->orderBy('demand.id','desc')->paginate(Session::get('paginatorCount'));


            //change page number after delete if no data has current page
            if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
                $page = ($qpArr['page'] - 1);
                return redirect('demand?page=' . $page);
            }
        } //if parameter is set and submitted for filter

        return view('demand.index')->with(compact('targetArr', 'qpArr', 'statusArr'
                                , 'request', 'tokenNoArr', 'filterStatusArr'
                                , 'userFirstNameArr', 'userLastNameArr', 'styleArr', 'batchCardArr'));
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&batch_card_id=' . $request->batch_card_id . '&batch_card_ref=' . $request->batch_card . '&date=' . $request->date . '&status=' . $request->status . '&style_id=' . $request->style_id;
        return Redirect::to('demand?' . $url);
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
            if(empty($userAccessArr[48][6])){
                return redirect('dashboard');
            }
            return view('demand.print')->with(compact('target', 'id', 'statusArr', 'productArr', 'productWithLotArr'));
        }

        $view = view('demand.details', compact('target', 'id', 'statusArr', 'productArr', 'productWithLotArr'))->render();
        return response()->json(['html' => $view]);
    }

    
    public function setDemandId(Request $request) {
        $prevData = $request->session()->get('demand_id');

        if (!empty($prevData)) {
            $firstDemandId = current($prevData);

            $targetArr = Demand::find($firstDemandId);
            $batchCardArr = Demand::find($request->demand_id);

            if ($targetArr->batch_card_id != $batchCardArr->batch_card_id) {
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.PLEASE_SELECT_DEMAND_OF_SAME_BATCHCARD')], 401);
            }
        }
        //$demandVal = 0;
        if (!empty($request->demand_val)) {
            $request->session()->put('demand_id.' . $request->demand_id, $request->demand_id);
        } else {
            $request->session()->forget('demand_id.' . $request->demand_id);
        }
        return Response::json(['success' => true]);
    }

    public function printDemandList(Request $request) {
//        $demandIdArr = [];
//        if (!empty($allItem)) {//For Printing Purpose
//            $allItemArr = explode(",", $allItem);
//            $demandIdArr = array_filter($allItemArr);
//        }

        $targetArr = Demand::join('batch_card', 'batch_card.id', '=', 'demand.batch_card_id')
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
                    , 'batch_recipe.reference_no as recipe_no', 'batch_recipe.wash_lot_quantity_weight', 'batch_recipe.wash_lot_quantity_piece'])
                ->whereIn('demand.id', $request->demand_id)
                ->get();

        $statusArr = $this->statusArr;

        foreach ($targetArr as $item) {
            $productArr[$item->id] = BatchRecipeToProduct::join('product', 'product.id', '=', 'batch_recipe_to_product.product_id')
                            ->where('batch_recipe_to_product.batch_rtp_id', $item->rtp_id)
                            ->select('product.id', 'product.name', 'total_qty', 'product.show_in_report')->get();
        }

        if ($request->view == 'print') {
            return view('demand.printDemandList')->with(compact('targetArr', 'id', 'statusArr', 'productArr'));
        }
    }

    public function loadTokenNo(Request $request) {
        $query = "%" . $request->search_keyword . "%";
        $tokenNumberArr = Demand::where('token_no', 'LIKE', $query)->latest()->take(20)->get(['token_no', 'id']);
        $view = view('demand.showTokenNo', compact('tokenNumberArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function loadBatchToken(Request $request) {
        $load = 'demand.showBatchNo';
        return Common::loadBatchToken($request, $load);
    }

}
