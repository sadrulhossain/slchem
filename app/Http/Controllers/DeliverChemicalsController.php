<?php

namespace App\Http\Controllers;

use Validator;
use App\BatchCard;
use App\Demand;
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
use Session;
use Redirect;
use Response;
use DB;
use Helper;
use Common;
use Illuminate\Http\Request;

class DeliverChemicalsController extends Controller {

    private $statusArr = ['0' => 'Demand Generated', '1' => 'Delivered From Stock'];
    private $formulaArr = [1 => ['formula' => 'G/L', 'label' => 'success']
        , 2 => ['formula' => '%', 'label' => 'warning']
        , 3 => ['formula' => 'Direct Amount', 'label' => 'primary']
    ];

    public function index(Request $request) {
        $request->session()->forget('demand_id');

        //passing param for custom function
        $qpArr = $request->all();
        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();


        //begin filtering
        $searchText = $request->search;
        $batchCardArr = ['0' => __('label.SELECT_BATCH_CARD_OPT')] + BatchCard::where('status', '1')->orderBy('id', 'desc')->pluck('reference_no', 'id')->toArray();
        $machineArr = ['0' => __('label.SELECT_MACHINE_OPT')] + Machine::pluck('machine_no', 'id')->toArray();
        $garmentsArr = ['0' => __('label.SELECT_GARMENTS_TYPE_OPT')] + GarmentsType::pluck('name', 'id')->toArray();
        $styleArr = ['0' => __('label.SELECT_STYLE_OPT')] + Style::pluck('name', 'id')->toArray();
        $statusArr = $this->statusArr;

        if (!empty($qpArr)) {
            $targetArr = Demand::join('batch_card', 'batch_card.id', '=', 'demand.batch_card_id')
                    ->join('machine', 'machine.id', '=', 'batch_card.machine_id')
                    ->join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                    ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                    ->join('garments_type', 'garments_type.id', '=', 'batch_recipe.garments_type_id')
                    ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')
                    ->orderBy('demand.id', 'desc')
                    ->select(['demand.*', 'machine.machine_no', 'batch_card.date', 'style.name as style', 'buyer.name as buyer', 'buyer.logo as buyer_logo', 'demand.token_no'
                        , 'garments_type.name as garments_type', 'demand.status', 'batch_card.reference_no as batch_card'])
                    ->where('demand.status', '0');


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
            if (!empty($request->style)) {
                $targetArr = $targetArr->where('batch_recipe.style_id', '=', $request->style);
            }
            //end filtering

            $targetArr = $targetArr->paginate(Session::get('paginatorCount'));


            //change page number after delete if no data has current page
            if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
                $page = ($qpArr['page'] - 1);
                return redirect('deliverChemicals?page=' . $page);
            }
        }//if parameter is set and submitted to filter


        return view('demand.deliverChemicals')->with(compact('targetArr', 'qpArr', 'statusArr', 'request'
                                , 'tokenNoArr', 'batchCardArr', 'machineArr', 'garmentsArr', 'userFirstNameArr'
                                , 'userLastNameArr', 'styleArr'));
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&batch_card_id=' . $request->batch_card_id . '&batch_card_ref=' . $request->batch_card . '&date=' . $request->date . '&machine=' . $request->machine . '&garments=' . $request->garments . '&style=' . $request->style;
        return Redirect::to('deliverChemicals?' . $url);
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
            if (empty($userAccessArr[49][6])) {
                return redirect('dashboard');
            }
            return view('demand.print')->with(compact('target', 'id', 'statusArr', 'productArr', 'productWithLotArr'));
        }

        $view = view('demand.details', compact('target', 'id', 'statusArr', 'productArr', 'productWithLotArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function doDeliver(Request $request) {
        $demandIdArr = [];
        if (isset($request->all_item)) {//if multiple demand selected
            $allItemArr = explode(",", $request->all_item);
            $demandIdArr = array_filter($allItemArr);
        }

        if (empty($demandIdArr)) {
            return Response::json(array('success' => false, 'message' => __('label.NO_DEMAND_HAS_BEEN_SELECTED_TO_DELIVER')), 400);
        }

        //Relate Demand_id to Product_id: Start

        $demandToProductArr = [];
        if (!empty($demandIdArr)) {
            $demandToProductArr = Demand::join('batch_recipe_to_product', 'demand.rtp_id', '=', 'batch_recipe_to_product.batch_rtp_id')
                            ->join('product', 'product.id', '=', 'batch_recipe_to_product.product_id')
                            ->select('demand.id as demand_id', 'demand.token_no', 'batch_recipe_to_product.product_id'
                                    , 'product.name as product_name', 'product.available_quantity'
                                    , 'batch_recipe_to_product.total_qty'
                                    , 'product.show_in_report', 'batch_recipe_to_product.batch_rtp_id', 'demand.rtp_id')
                            ->where('product.show_in_report', '!=', '1')
                            ->whereIn('demand.id', $demandIdArr)->get()->toArray();
        }

        ksort($demandToProductArr);
        $message = [];
        $cummulativeProQtyArr = [];
        /* START:: check required quantity is greater than available quantity */
        $cummProdQtyArr = [];
        if (!empty($demandToProductArr)) {
            foreach ($demandToProductArr as $key => $item) {
                //Form Product Cumulative Quantity Array
                $cummProdQtyArr[$item['product_id']]['total_qty'] = (isset($cummProdQtyArr[$item['product_id']]['total_qty']) ? $cummProdQtyArr[$item['product_id']]['total_qty'] : 0) + $item['total_qty'];
                $cummProdQtyArr[$item['product_id']]['available_quantity'] = $item['available_quantity'];
                $cummProdQtyArr[$item['product_id']]['product_name'] = $item['product_name'];

                //Check for Individual Item Quantity with Stock Available Quantity
                if ($item['total_qty'] > $item['available_quantity']) {
                    $message[] = 'Required Quantity exceeds Available Quantity for Token: ' . $item['token_no'] . " 's Product: " . $item['product_name'];
                }
            }//EOF - Foreach

            foreach ($cummProdQtyArr as $cumProdId => $cumProdQty) {
                //Check for Individual Item Quantity with Stock Available Quantity
                if ($cumProdQty['total_qty'] > $cumProdQty['available_quantity']) {
                    $message[] = "Required Total Quantity exceeds Available Quantity for Product: " . $cumProdQty['product_name'];
                }
            }//EOF - Foreach
        }


        if (!empty($message)) {
            return Response::json(array('success' => false, 'heading' => 'Error', 'message' => $message), 401);
        }
        /* END:: check required quantity is greater than available quantity */

        // Generate Demand Reference Number
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $deliveryTime = date('H:i:s');
        if (strtotime($deliveryTime) <= strtotime($setCutOffTime->check_in_time)) {
            $deliveryDate = (date('Y-m-d', strtotime("-1 days")));
        } else {
            $deliveryDate = date('Y-m-d');
        }
        $deliverArr = ProductConsumptionMaster::select(DB::raw('count(id) as total'))->where('source', '2')
                        ->where('adjustment_date', $deliveryDate)->first();
        $deliver = $deliverArr->total + 1;
        $voucherNo = 'POD-' . date('ymd', strtotime($deliveryDate)) . str_pad($deliver, 4, '0', STR_PAD_LEFT);


        $target = new ProductConsumptionMaster;
        $target->voucher_no = $voucherNo;
        $target->status = '1'; //approved
        $target->source = '2'; //delivered
        $target->approved_by = Auth::user()->id;
        $target->approved_at = date('Y-m-d H:i:s');
        $target->adjustment_date = $deliveryDate;

        //Prepare Array to Deliver Chemical
        $dataDeliver = [];
        $insertionFlag = 1;
        
        DB::beginTransaction();
        try {
            if (!empty($demandToProductArr)) {//If Products are found         
                $j = 0;
                if ($target->save()) { //Save to the Consumption Master Table
                    foreach ($demandToProductArr as $key => $item) {
                        $dataDeliver[$j]['master_id'] = $target->id;
                        $dataDeliver[$j]['demand_id'] = $item['demand_id'];
                        $dataDeliver[$j]['product_id'] = $item['product_id'];
                        $dataDeliver[$j]['quantity'] = $item['total_qty'];

                        $j++;
                    }
                    //Insert Delivered data to the Product Consumption Details Table
                    $detailInsertStatus = ProductConsumptionDetails::insert($dataDeliver);

                    if (!$detailInsertStatus) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                        //ProductConsumptionMaster::where('id', $target->id)->delete();
                        DB::rollback();
                        $insertionFlag = 0;
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 400);
                    }
                } else { //If failed to Insert in Consumption Master Table                
                    $insertionFlag = 0;
                    return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 400);
                }
            } else {
                $insertionFlag = 0;
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_PRODUCT_TO_DELIVER')], 400);
            }

            //Update Product Table with Revised Quantity and Insert to Lot wise Details Table and Demand Table: Start
            $consumedData = ProductConsumptionDetails::where('master_id', $target->id)->orderBy('id', 'asc')
                            ->select('id', 'demand_id', 'product_id', 'quantity')->lockForUpdate()->get();

            if (!empty($consumedData) && ($insertionFlag == 1)) {
                foreach ($consumedData as $data) {
                    $consumeStatus = Helper::consumeQuantity($target->id, $data['id'], $data['product_id'], $data['quantity']);
                    if ($consumeStatus) {//check consumption status
                        Product::where('id', $data['product_id'])->decrement('available_quantity', $data['quantity']);
                    } else {
                        $insertionFlag = 0;
                        DB::rollback();
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.QUANTITY_IS_ALREADY_CONSUMED_FOR') . $cummProdQtyArr[$data['product_id']]['product_name']], 400);
                    } //EOF - if
                }//EOF - foraech                
                //Update Demand Table Status
                if (!empty($demandIdArr)) {
                    $demandUpdatedStatus = Demand::where('status', '0')->whereIn('id', $demandIdArr)->update(
                            array('status' => '1', 'delivered_by' => Auth::user()->id, 'delivered_at' => date("Y-m-d H:i:s"))
                    );
                    
                    if(!$demandUpdatedStatus){
                        DB::rollback();
                    }
                }
                DB::commit();
                return Response::json(['success' => true], 200);
            } else {//EOF - If not empty ConsumptionDetails Data 
                DB::rollback();
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.SOMETHING_WENT_WRONG')], 400);
            }
            //Update Product Table with Revised Quantity and Insert to Lot wise Details Table: End
        } catch (\Throwable $e) {
            DB::rollback();
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.SOMETHING_WENT_WRONG')], 400);
        }
    }

    //EOF -Function

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

    public function loadTokenToDeliver(Request $request) {
        $query = "%" . $request->search_keyword . "%";
        $tokenNumberArr = Demand::where('token_no', 'LIKE', $query)->where('status', '0')->latest()->take(20)->get(['token_no', 'id']);

        $view = view('demand.showTokenNo', compact('tokenNumberArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function loadBatchTokenForDeliver(Request $request) {
        $query = "%" . $request->search_keyword . "%";
        $tokenNumberArr = BatchCard::join('demand', 'batch_card.id', '=', 'demand.batch_card_id')
                        ->where('demand.status', '0')
                        ->where('batch_card.reference_no', 'LIKE', $query)->orderBy('batch_card.created_at', 'desc')->take(20)->distinct()->get(['batch_card.reference_no', 'batch_card.id']);

        $view = view('demand.showBatchNo', compact('tokenNumberArr'))->render();
        return response()->json(['html' => $view]);
    }

}
