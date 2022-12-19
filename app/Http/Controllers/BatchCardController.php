<?php

namespace App\Http\Controllers;

use Validator;
use App\BatchCard;
use App\Recipe;
use App\Machine;
use App\HydroMachine;
use App\DryerMachine;
use App\Configuration;
use App\Process;
use App\User;
use App\RecipeToProcess;
use App\RecipeToProduct;
use App\BatchRecipe;
use App\BatchRecipeToProcess;
use App\BatchRecipeToProduct;
use App\BatchWashTypeToProcess;
use App\WashTypeToProcess;
use App\Style;
use App\Shift;
use App\Factory;
use App\WashType;
use App\Season;
use App\Color;
use Auth;
use Common;
use Session;
use Redirect;
use Response;
use DB;
use Helper;
use Illuminate\Http\Request;

class BatchCardController extends Controller {

    private $statusArr = [0 => ['status' => 'Draft', 'label' => 'warning'], 1 => ['status' => 'Waiting for Approval', 'label' => 'danger']
        , 2 => ['status' => 'Approved', 'label' => 'success']
        , 3 => ['status' => 'Denied', 'label' => 'primary']
    ];
    private $formulaArr = [1 => ['formula' => 'G/L', 'label' => 'success']
        , 2 => ['formula' => '%', 'label' => 'warning']
        , 3 => ['formula' => 'Direct Amount', 'label' => 'primary']
    ];

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();
        $shiftArr = ['0' => __('label.SELECT_SHIFT_OPT')] + Shift::orderBy('id', 'asc')->pluck('name', 'id')->toArray();
        $factoryArr = ['0' => __('label.SELECT_FACTORY_OPT')] + Factory::orderBy('id', 'asc')->pluck('name', 'id')->toArray();
        $washTypeArr = ['0' => __('label.SELECT_WASH_TYPE_OPT')] + WashType::orderBy('id', 'asc')->pluck('name', 'id')->toArray();

        $targetArr = BatchCard::join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                ->join('season', 'season.id', '=', 'batch_recipe.season_id')
                ->join('factory', 'factory.id', '=', 'batch_recipe.factory_id')
                ->join('color', 'color.id', '=', 'batch_recipe.color_id')
                ->select('batch_card.*', 'batch_recipe.reference_no as recipe_reference_no', 'batch_recipe.batch_card_id'
                        , 'batch_recipe.id as batch_recipe_id'
                        , 'style.name as style', 'factory.name as factory', 'color.name as color','season.name as season')
                ->orderBy('batch_recipe.id', 'desc');

        //begin filtering
        $searchText = $request->search;
        $operatorName = $request->operator_name;

        $recipeArr = ['0' => __('label.SELECT_RECIPE_OPT')] + Recipe::where('approval_status', '2')->where('status', '1')->pluck('reference_no', 'id')->toArray();
        $styleArr = ['0' => __('label.SELECT_STYLE_OPT')] + Style::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $machineArr = ['0' => __('label.SELECT_MACHINE_OPT')] + Machine::pluck('machine_no', 'id')->toArray();
        $seasonArr = ['0' => __('label.SELECT_SEASON_OPT')] + Season::where('status','1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $colorArr = ['0' => __('label.SELECT_COLOR_OPT')] + Color::where('status','1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        
        $opreratorArr = BatchCard::select('operator_name')->get();

        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('batch_card.reference_no', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->date)) {
            $targetArr = $targetArr->where('batch_card.date', '=', $request->date);
        }
        if (!empty($request->recipe)) {
            $targetArr = $targetArr->where('batch_card.recipe_id', '=', $request->recipe);
        }

        if (!empty($request->style_id)) {
            $targetArr = $targetArr->where('batch_recipe.style_id', '=', $request->style_id);
        }

        if (!empty($request->wash_type_id)) {
            $targetArr = $targetArr->where('batch_card.wash_type_id', '=', $request->wash_type_id);
        }

        if (!empty($operatorName)) {
            $targetArr->where(function ($query) use ($operatorName) {
                $query->where('operator_name', 'LIKE', '%' . $operatorName . '%');
            });
        }

        if (!empty($request->machine)) {
            $targetArr = $targetArr->where('batch_card.machine_id', '=', $request->machine);
        }

        if (!empty($request->shift)) {
            $targetArr = $targetArr->where('batch_card.shift_id', '=', $request->shift);
        }
        if (!empty($request->season_id)) {
            $targetArr = $targetArr->where('batch_recipe.season_id', '=', $request->season_id);
        }
        
        if (!empty($request->color_id)) {
            $targetArr = $targetArr->where('batch_recipe.color_id', '=', $request->color_id);
        }
        if (!empty($request->factory)) {
            $targetArr = $targetArr->where('batch_recipe.factory_id', '=', $request->factory);
        }
        //end filtering

        $targetArr = $targetArr->orderBy('id', 'asc')->paginate(Session::get('paginatorCount'));


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('batchCard?page=' . $page);
        }

        return view('batchCard.index')->with(compact('targetArr', 'qpArr'
                                , 'recipeArr', 'machineArr', 'factoryArr'
                                , 'opreratorArr', 'userFirstNameArr'
                                , 'userLastNameArr', 'styleArr', 'shiftArr', 'washTypeArr','seasonArr','colorArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $shiftArr = [0 => __('label.SELECT_SHIFT_OPT')] + Shift::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $recipeArr = [0 => __('label.SELECT_RECIPE_OPT')] + Recipe::join('style', 'style.id', '=', 'recipe.style_id')
                        ->join('shade', 'shade.id', '=', 'recipe.shade_id')
                        ->join('machine_model', 'machine_model.id', '=', 'recipe.machine_model_id')
                 ->join('season', 'season.id', '=', 'recipe.season_id')
                        ->join('color', 'color.id', '=', 'recipe.color_id')
                        ->where('recipe.status', '1')
                        ->where('approval_status', '2')
                        ->select('recipe.id'
                                , DB::raw("CONCAT(style.name,'- (',recipe.reference_no,') -',color.name,'-',season.name,'-',shade.name,'-',machine_model.name) as reference_no"))
                        ->orderBy('recipe.id', 'desc')
                        ->pluck('reference_no', 'recipe.id')->toArray();
        $washTypeArr = [0 => __('label.SELECT_WASH_TYPE_OPT')] + WashType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        return view('batchCard.create')->with(compact('qpArr', 'recipeArr', 'dryerMachineArr', 'shiftArr', 'washTypeArr'));
    }

    public function saveBatchCard(Request $request) {
        $rules = [
            'recipe_id' => 'required|not_in:0',
            'shift_id' => 'required|not_in:0'
        ];

        if (!empty($request->recipe_id)) {
            $rules = [
                'wash_type_id' => 'required|not_in:0',
                'reference_no' => 'required|unique:batch_card,reference_no',
                'machine_id' => 'required|not_in:0',
                'lot_weight' => 'required',
                'lot_qty' => 'required',
                'shift_id' => 'required|not_in:0'
            ];
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        //get previous recipe related data include this query 
        $motherRecipeInfo = Recipe::find($request->recipe_id);
        $prevRecipetoProcessArr = RecipeToProcess::where('recipe_id', $request->recipe_id)->get();

        $prevWashTypeInfoArr = WashTypeToProcess::where('recipe_id', $request->recipe_id)
                        ->where('wash_type_id', $request->wash_type_id)
                ->pluck('process_id', 'wash_type_id')->toArray();

        //Prepare Array for WashType wise Selected Process
        $processedWashTypeArr = [];
        if (!empty($prevWashTypeInfoArr)) {
            foreach ($prevWashTypeInfoArr as $washTypeId => $processInfo) {
                $processedWashTypeArr = json_decode($processInfo, true);
            }
        }

        $targetWashTypeArr = [];
        if (!empty($processedWashTypeArr)) {
            foreach ($processedWashTypeArr as $identifier => $processId) {
                $targetWashTypeArr[$identifier] = $processId;
            }
        }

        //Make array of wash type wise water
        $washTypeToWaterArr = json_decode($motherRecipeInfo->wash_type_to_water, true);

        $motherRecipetoProductArr = RecipeToProduct::join('recipe_to_process', 'recipe_to_process.id', '=', 'recipe_to_product.rtp_id')
                ->select('recipe_to_process.recipe_id', 'recipe_to_process.process_id', 'recipe_to_product.rtp_id'
                        , 'recipe_to_product.product_id', 'recipe_to_product.formula', 'recipe_to_product.qty', 'recipe_to_product.total_qty')
                ->where('recipe_to_process.recipe_id', $request->recipe_id)
                ->orderBy('recipe_to_process.recipe_id', 'asc')
                ->orderBy('recipe_to_process.process_id', 'asc')
                ->orderBy('recipe_to_product.rtp_id', 'asc')
                ->orderBy('recipe_to_product.product_id', 'asc')
                ->get();


        $mRPItemSorted = [];
        foreach ($motherRecipetoProductArr as $mRPItem) {
            $mRPItemSorted[$mRPItem->rtp_id][] = $mRPItem;
        }



        //insert data to batch card table
        $target = new BatchCard;
        $target->recipe_origin_id = $request->recipe_origin_id;
        $target->reference_no = $request->reference_no;
        $target->machine_id = $request->machine_id;
        $target->recipe_id = $request->recipe_id;
        $target->shift_id = $request->shift_id;
        $target->wash_type_id = $request->wash_type_id;
        $target->operator_name = $request->operator_name;
        $target->remarks = $request->remarks;
        $target->status = '1';

        //set batch card date based on configuration time  
        $setCheckInTime = Configuration::select('check_in_time')->first();
        $batchCardTime = date('H:i:s');

        if (strtotime($batchCardTime) <= strtotime($setCheckInTime->check_in_time)) {
            $target->date = (date('Y-m-d', strtotime("-1 days")));
        } else {
            $target->date = date('Y-m-d');
        }

        //After Save data to Batch Card Table:: insertion to batch recipe table
        if ($target->save()) {
            //if change wash lot quantity then work this 
            $batchRecipe = new BatchRecipe;
            //$batchRecipe->parent_id = $motherRecipeInfo->parent_id;
            $batchRecipe->batch_card_id = $target->id;
            $batchRecipe->shade_id = $motherRecipeInfo->shade_id;
            $batchRecipe->reference_no = $motherRecipeInfo->reference_no;
            $batchRecipe->date = $motherRecipeInfo->date;
            $batchRecipe->factory_id = $motherRecipeInfo->factory_id;
            $batchRecipe->buyer_id = $motherRecipeInfo->buyer_id;
            $batchRecipe->wash_id = 0;
            $batchRecipe->season_id = $motherRecipeInfo->season_id;
            $batchRecipe->color_id = $motherRecipeInfo->color_id;
            $batchRecipe->machine_model_id = $motherRecipeInfo->machine_model_id;
            $batchRecipe->dryer_machine_id = $motherRecipeInfo->dryer_machine_id;
            $batchRecipe->wash_lot_quantity_weight = $request->lot_weight;
            $batchRecipe->wash_lot_quantity_piece = $request->lot_qty;
            $batchRecipe->weight_one_piece = $motherRecipeInfo->weight_one_piece;
            $batchRecipe->style_id = $motherRecipeInfo->style_id;
            $batchRecipe->dry_process_info = $motherRecipeInfo->dry_process_info;
            $batchRecipe->order_no = $motherRecipeInfo->order_no;
            $batchRecipe->garments_type_id = $motherRecipeInfo->garments_type_id;
            $batchRecipe->supplier_id = $motherRecipeInfo->supplier_id;
            $batchRecipe->fabric_ref = $motherRecipeInfo->fabric_ref;
            $batchRecipe->dryer_type = $motherRecipeInfo->dryer_type;
            $batchRecipe->dryer_load_qty = $motherRecipeInfo->dryer_load_qty;
            $batchRecipe->drying_temperature = $motherRecipeInfo->drying_temperature;
            $batchRecipe->drying_time = $motherRecipeInfo->drying_time;
            $batchRecipe->dryer_type_id = $motherRecipeInfo->dryer_type_id;
            $batchRecipe->approval_status = $motherRecipeInfo->approval_status;
            $batchRecipe->approved_by = $motherRecipeInfo->approved_by;
            $batchRecipe->approved_at = $motherRecipeInfo->approved_at;
            $batchRecipe->created_at = date('Y-m-d h:i:s');
            $batchRecipe->created_by = Auth::user()->id;
            $batchRecipe->save();
            //update query for origin_id update in batch recipe table
            BatchRecipe::where('id', $batchRecipe->id)->update(['origin_id' => $batchRecipe->id]);
            $batchRecipeToProductArr = [];
            $i = 0;


            foreach ($prevRecipetoProcessArr as $item) {
                if (!empty($targetWashTypeArr)) { //BatchCard from New Recipe
                    if (array_key_exists($item->identifier, $targetWashTypeArr)) {

                        /**                         * Start :: Insertion Data to batch_recipe_to_process table ** */
                        $batchRecipeProcessArr = new BatchRecipeToProcess;
                        $batchRecipeProcessArr->batch_recipe_id = $batchRecipe->id;
                        $batchRecipeProcessArr->identifier = $item->identifier;
                        $batchRecipeProcessArr->process_id = $item->process_id;
                        $batchRecipeProcessArr->dry_chemical = $item->dry_chemical;
                        $batchRecipeProcessArr->water = ($request->lot_weight * $item->water_ratio);
                        $batchRecipeProcessArr->water_ratio = $item->water_ratio;
                        $batchRecipeProcessArr->ph = $item->ph;
                        $batchRecipeProcessArr->temperature = $item->temperature;
                        $batchRecipeProcessArr->time = $item->time;
                        $batchRecipeProcessArr->remarks = $item->remarks;
                        $batchRecipeProcessArr->save();
                        /*                         * * End :: Insertion Data to batch_recipe_to_process table ** */

                        $processInfo = Process::where('id', $batchRecipeProcessArr->process_id)->first();
                        /*                         * * Start :: Make Data for batch_recipe_to_product table ** */
                        if ($processInfo->process_type_id == '1' && $processInfo->water != '1') {
                            foreach ($mRPItemSorted[$item->id] as $productItem) {
                                $batchRecipeToProductArr[$i]['batch_rtp_id'] = $batchRecipeProcessArr->id;
                                $batchRecipeToProductArr[$i]['product_id'] = $productItem->product_id;
                                $batchRecipeToProductArr[$i]['formula'] = $productItem->formula;
                                $batchRecipeToProductArr[$i]['qty'] = $productItem->qty;
                                if ($productItem->formula == '1') {
                                    $batchRecipeToProductArr[$i]['total_qty'] = ($request->lot_weight * $batchRecipeProcessArr->water_ratio ) * ($productItem->qty / 1000); // Divide by 1000 to convert gm to KG
                                } elseif ($productItem->formula == '2') {
                                    $batchRecipeToProductArr[$i]['total_qty'] = ($request->lot_weight * $productItem->qty) / 100;
                                } elseif ($productItem->formula == '3') {
                                    $batchRecipeToProductArr[$i]['total_qty'] = ($motherRecipeInfo->wash_lot_quantity_weight > 0) ? (($productItem->total_qty * $request->lot_weight) / $motherRecipeInfo->wash_lot_quantity_weight) : 0;
                                }

                                $batchRecipeToProductArr[$i]['total_qty_detail'] = $this->unitConversion($batchRecipeToProductArr[$i]['total_qty']);

                                $i++;
                            }
                        }
                        /*                         * * End :: Make Data for batch_recipe_to_product table ** */
                    }
                } else {
                    $batchRecipeProcessArr = new BatchRecipeToProcess;
                    $batchRecipeProcessArr->batch_recipe_id = $batchRecipe->id;
                    $batchRecipeProcessArr->identifier = $item->identifier;
                    $batchRecipeProcessArr->process_id = $item->process_id;
                    $batchRecipeProcessArr->dry_chemical = $item->dry_chemical;
                    $batchRecipeProcessArr->water = ($request->lot_weight * $item->water_ratio);
                    $batchRecipeProcessArr->water_ratio = $item->water_ratio;
                    $batchRecipeProcessArr->ph = $item->ph;
                    $batchRecipeProcessArr->temperature = $item->temperature;
                    $batchRecipeProcessArr->time = $item->time;
                    $batchRecipeProcessArr->remarks = $item->remarks;
                    $batchRecipeProcessArr->save();

                    $processInfo = Process::where('id', $batchRecipeProcessArr->process_id)->first();

                    /*                     * * Start :: Make Data for batch_recipe_to_product table ** */
                    if ($processInfo->process_type_id == '1' && $processInfo->water != '1') {
                        foreach ($mRPItemSorted[$item->id] as $productItem) {
                            $batchRecipeToProductArr[$i]['batch_rtp_id'] = $batchRecipeProcessArr->id;
                            $batchRecipeToProductArr[$i]['product_id'] = $productItem->product_id;
                            $batchRecipeToProductArr[$i]['formula'] = $productItem->formula;
                            $batchRecipeToProductArr[$i]['qty'] = $productItem->qty;
                            if ($productItem->formula == '1') {
                                $batchRecipeToProductArr[$i]['total_qty'] = ($request->lot_weight * $batchRecipeProcessArr->water_ratio ) * ($productItem->qty / 1000); // Divide by 1000 to convert gm to KG
                            } elseif ($productItem->formula == '2') {
                                $batchRecipeToProductArr[$i]['total_qty'] = ($request->lot_weight * $productItem->qty) / 100;
                            } elseif ($productItem->formula == '3') {
                                $batchRecipeToProductArr[$i]['total_qty'] = ($motherRecipeInfo->wash_lot_quantity_weight > 0) ? (($productItem->total_qty * $request->lot_weight) / $motherRecipeInfo->wash_lot_quantity_weight) : 0;
                            }

                            $batchRecipeToProductArr[$i]['total_qty_detail'] = $this->unitConversion($batchRecipeToProductArr[$i]['total_qty']);


                            $i++;
                        }//EOF - foreach
                    }//EOF - if
                    /**                     * End :: Make Data for batch_recipe_to_product table ** */
                }
            }
            /*             * * Start:: Insertion data batch_recipe_to_product ** */
            BatchRecipeToProduct::insert($batchRecipeToProductArr);
            /*             * * End:: Insertion data batch_recipe_to_product ** */


            /* Add data WashType Wise Process */
            $batchWashTypeToProcess = [
                'batch_recipe_id' => $batchRecipe->id,
                'wash_type_id' => $target->wash_type_id,
                'process_id' => json_encode($targetWashTypeArr, JSON_FORCE_OBJECT),
            ];
            BatchWashTypeToProcess::insert($batchWashTypeToProcess);
//            if (!empty($targetWashTypeArr)) {
//                foreach ($targetWashTypeArr as $key => $processId) {
//                    $newNodes[$key] = $processId;
//                    $batchWashTypeToProcess = new BatchWashTypeToProcess;
//                    $batchWashTypeToProcess->batch_recipe_id = $batchRecipe->id;
//                    $batchWashTypeToProcess->wash_type_id = $target->wash_type_id;
//                    $batchWashTypeToProcess->process_id = json_encode($newNodes, JSON_FORCE_OBJECT);
//                    $batchWashTypeToProcess->save();
//                }
//            }

            /*             * * Start:: Update Wash Type to Water in batch_recipe ** */
            $waterInfo = [];
            if (!empty($washTypeToWaterArr)) {
                if (array_key_exists($request->wash_type_id, $washTypeToWaterArr)) {
                    $waterInfo[$request->wash_type_id] = $washTypeToWaterArr[$request->wash_type_id];
                    BatchRecipe::where('id', $batchRecipe->id)->update(array('wash_type_to_water' => json_encode($waterInfo, JSON_FORCE_OBJECT)));
                }
            }
            /*             * * End:: Update Wash Type to Water in batch_recipe ** */
            return Response::json(['success' => true], 200);
        }
    }

    public function unitConversion($totalQtyStr = "") {
        $pos = strpos($totalQtyStr, ".");
        if ($pos === false) {
            $kgAmnt = $totalQtyStr;
            $gmAmntArr = "";
        } else {
            $totalQtyArr = explode(".", $totalQtyStr);
            $kgAmnt = $totalQtyArr[0];
            $gmAmntArr = $totalQtyArr[1];
        }

        $kgFinalAmntStr = '';
        if ($kgAmnt > 0) {
            $kgFinalAmntStr = (int) $kgAmnt . " " . __('label.UNIT_KG');
        }


        if ($pos !== false) { //If decimal point exists
            $totalAmntStr = str_pad($gmAmntArr, 6, "0", STR_PAD_RIGHT);

            $gmStr = substr($totalAmntStr, 0, 3); //Subtract gram aamount
            $gmFinalAmntStr = "";
            if ($gmStr > 0) {
                $gmFinalAmntStr = (int) $gmStr . " " . __('label.GM');
            }
            $miliGmStr = substr($totalAmntStr, 3, 3); //Subtract miligram aamount
            $mgFinalAmntStr = "";
            if ($miliGmStr > 0) {
                $mgFinalAmntStr = (int) $miliGmStr . " " . __('label.MG');
            }

            $qtyTotalDetail = $kgFinalAmntStr . " " . $gmFinalAmntStr . " " . $mgFinalAmntStr;
        } else {
            $qtyTotalDetail = $kgFinalAmntStr;
        }

        return $qtyTotalDetail;
    }

    public function edit(Request $request, $id) {
        $target = BatchCard::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('batchCard');
        }

        $qpArr = $request->all();
        $recipeArr = [0 => __('label.SELECT_RECIPE_OPT')] + Recipe::pluck('reference_no', 'id')->toArray();
        $machineArr = [0 => __('label.SELECT_WASH_MACHINE_OPT')] + Machine::pluck('machine_no', 'id')->toArray();
        $hydroMachineArr = [0 => __('label.SELECT_HYDRO_MACHINE_OPT')] + HydroMachine::pluck('machine_no', 'id')->toArray();
        $dryerMachineArr = [0 => __('label.SELECT_DRYER_MACHINE_OPT')] + DryerMachine::pluck('machine_no', 'id')->toArray();
        return view('batchCard.edit')->with(compact('qpArr', 'recipeArr', 'machineArr', 'hydroMachineArr', 'dryerMachineArr'
                                , 'target'));
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&date=' . $request->date . '&recipe=' . $request->recipe
                . '&style_id=' . $request->style.'&season_id=' . $request->season_id
                . '&color_id=' . $request->color_id
                . '&machine=' . $request->machine . '&shift=' . $request->shift . '&factory=' . $request->factory . '&operator_name=' . $request->operator_name
                . '&wash_type_id=' . $request->wash_type_id;
        return Redirect::to('batchCard?' . $url);
    }

    public function getRecipeInfo(Request $request) {
        //get recipe information
        $target = Recipe::join('factory', 'factory.id', '=', 'recipe.factory_id')
                ->join('buyer', 'buyer.id', '=', 'recipe.buyer_id')
                ->join('style', 'style.id', '=', 'recipe.style_id')
                ->leftJoin('wash', 'wash.id', '=', 'recipe.wash_id')
                ->join('machine_model', 'machine_model.id', '=', 'recipe.machine_model_id')
                ->join('color', 'color.id', '=', 'recipe.color_id')
                ->join('season', 'season.id', '=', 'recipe.season_id')
                ->leftJoin('dryer_type', 'dryer_type.id', '=', 'recipe.dryer_type_id')
                ->select('factory.name as factory', 'buyer.name as buyer', 'style.name as style', 'wash_lot_quantity_weight'
                        , 'wash_lot_quantity_piece', 'machine_model.name as machine_model', 'dryer_type.name as dryer_type'
                        , 'recipe.reference_no', 'recipe.origin_id'
                        , 'recipe.drying_temperature', 'recipe.id','season.name as season'
                        ,'color.name as color')
                ->find($request->recipe_id);

//        $batchReferenceNoArr = BatchCard::select(DB::raw('count(batch_card.id) as total'))
//                ->where('recipe_origin_id', $target->origin_id);
//
//        $batchReferenceNoArr = $batchReferenceNoArr->first();
        //START:: Make Batch Refference Number using recipe id
        $batchReferenceNoArr = BatchCard::select(DB::raw('count(batch_card.id) as total'))
                ->where('recipe_id', $request->recipe_id);

        $batchReferenceNoArr = $batchReferenceNoArr->first();
        $batchReferenceFormat = $batchReferenceNoArr->total + 1;
        $batchReferenceNo = str_pad($batchReferenceFormat, 3, '0', STR_PAD_LEFT);
        //END:: Make Batch Refference Number using recipe id

        $washTypeInfoArr = WashTypeToProcess::join('wash_type', 'recipe_wash_type_to_process.wash_type_id', '=', 'wash_type.id')->where('recipe_id', $request->recipe_id)->select('wash_type.name', 'recipe_wash_type_to_process.wash_type_id')->get();

        $processedWashTypeArr = [];

        //If Old Recipe is in Execution show all Wash Types
        if ($washTypeInfoArr->isEmpty()) {
            $processedWashTypeArr = [0 => __('label.SELECT_WASH_TYPE_OPT')] + WashType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        } else {
            //For New Wash Type (Wash Type to Process Relations Set)
            if (!empty($washTypeInfoArr)) {
                foreach ($washTypeInfoArr as $item) {
                    $processedWashTypeArr[$item->wash_type_id] = $item->name;
                }
            }
            $processedWashTypeArr = [0 => __('label.SELECT_WASH_TYPE_OPT')] + $processedWashTypeArr;
        }

        $machineArr = [0 => __('label.SELECT_WASH_MACHINE_OPT')] + Machine::pluck('machine_no', 'id')->toArray();

        $view = view('batchCard.recipeInfo', compact('target', 'machineArr', 'processedWashTypeArr'))->render();
        $response = view('batchCard.manageReference', compact('batchReferenceNo', 'target'))->render();

        return response()->json(['html' => $view, 'reference' => $response]);
    }

    public function details(Request $request, $id = null) {
        $shiftArr = ['0' => __('label.SELECT_SHIFT_OPT')] + Shift::orderBy('id', 'asc')->pluck('name', 'id')->toArray();

        //get BatchCard Information using id
        $batchCardArr = BatchCard::join('machine', 'machine.id', '=', 'batch_card.machine_id')
                ->leftjoin('wash_type', 'wash_type.id', '=', 'batch_card.wash_type_id')
                ->leftjoin('hydro_machine', 'hydro_machine.id', '=', 'batch_card.hydro_machine_id')
                ->select('machine.machine_no as machine', 'hydro_machine.machine_no as hydro_machine', 'wash_type.name as wash_type_name'
                , 'batch_card.*');

        if (!empty($id)) {
            $batchCardArr = $batchCardArr->where('batch_card.id', $id);
        } else {
            $batchCardArr = $batchCardArr->where('batch_card.id', $request->batch_id);
        }

        $batchCardArr = $batchCardArr->first();

        //get batch recipe to process data using batch card id
        $recipeArr = BatchRecipe::join('factory', 'factory.id', '=', 'batch_recipe.factory_id')
                        ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')
                        ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                        ->join('machine_model', 'machine_model.id', '=', 'batch_recipe.machine_model_id')
                        ->leftJoin('dryer_machine', 'dryer_machine.id', '=', 'batch_recipe.dryer_machine_id')
                        ->leftJoin('dryer_type', 'dryer_type.id', '=', 'batch_recipe.dryer_type_id')
                        ->join('color', 'color.id', '=', 'batch_recipe.color_id')
                        ->join('season', 'season.id', '=', 'batch_recipe.season_id')
                        ->where('batch_recipe.batch_card_id', $batchCardArr->id)
                        ->select('factory.name as factory', 'buyer.name as buyer', 'style.name as style', 'batch_recipe.wash_lot_quantity_weight'
                                , 'batch_recipe.wash_lot_quantity_piece', 'machine_model.name as machine_model'
                                ,'color.name as color'
                                , 'dryer_type.name as dryer_type', 'batch_recipe.drying_temperature', 'dryer_machine.machine_no as dryer_machine')->first();

        $statusArr = $this->statusArr;

        if ($request->view == 'print') {
            return view('batchCard.print')->with(compact('batchCardArr', 'recipeArr', 'statusArr', 'shiftArr'));
        }

        $view = view('batchCard.details', compact('batchCardArr', 'recipeArr', 'statusArr', 'shiftArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function recipeDetails(Request $request, $id = null) {
        //get batch recipe information using batch card id
        $target = BatchRecipe::join('batch_card', 'batch_card.id', '=', 'batch_recipe.batch_card_id')
                ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                ->join('garments_type', 'garments_type.id', '=', 'batch_recipe.garments_type_id')
                ->join('factory', 'factory.id', '=', 'batch_recipe.factory_id')
                ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')
                ->join('machine_model', 'machine_model.id', '=', 'batch_recipe.machine_model_id')
                ->join('shade', 'shade.id', '=', 'batch_recipe.shade_id')
                ->join('color', 'color.id', '=', 'batch_recipe.color_id')
                ->join('season', 'season.id', '=', 'batch_recipe.season_id')
                ->leftJoin('dryer_type', 'dryer_type.id', '=', 'batch_recipe.dryer_type_id')
                ->leftJoin('wash', 'wash.id', '=', 'batch_recipe.wash_id')
                ->leftJoin('wash_type', 'wash_type.id', '=', 'batch_card.wash_type_id');

        if (!empty($id)) {
            $target = $target->where('batch_recipe.id', $id);
        } else {
            $target = $target->where('batch_recipe.id', $request->batch_recipe_id);
        }
        $target = $target->select('batch_recipe.*', 'style.name as style', 'garments_type.name as garments_type', 'factory.name as factory'
                        , 'buyer.name as buyer', 'buyer.logo as buyer_logo', 'machine_model.name as machine_model'
                        , 'machine_model.rpm', 'wash.name as wash', 'dryer_type.name as dryer_type_name', 'shade.name as shade_name'
                        , 'wash_type.name as wash_type', 'batch_card.wash_type_id'
                        , 'season.name as season', 'color.name as color')->first();

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('batchCard');
        }

        //get batch recipe to process data
        $processArr = BatchRecipeToProcess::join('process', 'process.id', '=', 'batch_recipe_to_process.process_id')
                ->orderBy('batch_recipe_to_process.id', 'asc');

        if (!empty($id)) {
            $processArr = $processArr->where('batch_recipe_to_process.batch_recipe_id', $id);
        } else {
            $processArr = $processArr->where('batch_recipe_to_process.batch_recipe_id', $request->batch_recipe_id);
        }

        $processArr = $processArr->select('batch_recipe_to_process.*', 'process.name', 'process.water as water_type'
                        , 'batch_recipe_to_process.water_ratio', 'process.process_type_id')
                ->get();

        $productArrPre = BatchRecipeToProduct::join('batch_recipe_to_process', 'batch_recipe_to_process.id', '=', 'batch_recipe_to_product.batch_rtp_id')
                ->join('product', 'product.id', '=', 'batch_recipe_to_product.product_id');

        if (!empty($id)) {
            $productArrPre = $productArrPre->where('batch_recipe_to_process.batch_recipe_id', $id);
        } else {
            $productArrPre = $productArrPre->where('batch_recipe_to_process.batch_recipe_id', $request->batch_recipe_id);
        }
        $productArrPre = $productArrPre->select('product.name', 'batch_recipe_to_product.qty'
                        , 'batch_recipe_to_product.batch_rtp_id'
                        , 'batch_recipe_to_product.total_qty', 'batch_recipe_to_product.total_qty_detail', 'batch_recipe_to_product.formula')
                ->get();

        //prepare product Array
        $productArr = [];
        foreach ($productArrPre as $item) {
            $productArr[$item->batch_rtp_id][] = $item->toArray();
        }

        $targetArr = [];
        $i = $totalWater = 0;
        foreach ($processArr as $process) {
            $targetArr[$i]['process_type_id'] = $process['process_type_id'];
            $targetArr[$i]['process_id'] = $process['id'];
            $targetArr[$i]['process'] = $process['name'];
            $targetArr[$i]['dry_chemical'] = $process['dry_chemical'];
            $targetArr[$i]['water'] = $process['water'];
            $targetArr[$i]['water_ratio'] = $process['water_ratio'];
            $targetArr[$i]['ph'] = $process['ph'];
            $targetArr[$i]['temperature'] = $process['temperature'];
            $targetArr[$i]['time'] = $process['time'];
            $targetArr[$i]['remarks'] = $process['remarks'];
            $targetArr[$i]['water_type'] = $process['water_type'];
            $totalWater += $process['water'];
            $i++;
        }

        $formulaArr = $this->formulaArr;
        $washTypeArr = WashType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $washTypeToWaterArr = (array) json_decode($target->wash_type_to_water);

        $washTypeInfoArr = BatchWashTypeToProcess::orderBy('id', 'asc');
        if (!empty($id)) {
            $washTypeInfoArr = $washTypeInfoArr->where('batch_recipe_id', $id);
        } else {
            $washTypeInfoArr = $washTypeInfoArr->where('batch_recipe_id', $request->batch_recipe_id);
        }
        $washTypeInfoArr = $washTypeInfoArr->pluck('process_id', 'wash_type_id')->toArray();

        $processedWashTypeArr = [];
        if (!empty($washTypeInfoArr)) {
            foreach ($washTypeInfoArr as $washTypeId => $process) {
                $processedWashTypeArr[$washTypeId] = (array) json_decode($process);
            }
        }
        $processNameList = Process::pluck('name', 'id')->toArray();


        if ($request->view == 'print') {
            return view('batchCard.batchRecipePrint')->with(compact('targetArr', 'target', 'productArr', 'totalWater', 'formulaArr'
                                    , 'washTypeArr', 'washTypeToWaterArr'));
        }

        $view = view('batchCard.batchRecipeDetails', compact('targetArr', 'target', 'productArr', 'totalWater', 'formulaArr'
                        , 'washTypeArr', 'washTypeToWaterArr', 'processedWashTypeArr', 'processNameList'))->render();
        return response()->json(['html' => $view]);
    }

    public function manageInfo(Request $request) {
        $prevBatchData = BatchCard::find($request->batch_id);
        $hydroMachineArr = [0 => __('label.SELECT_HYDRO_MACHINE_OPT')] + HydroMachine::pluck('machine_no', 'id')->toArray();
        $view = view('batchCard.manageInfo', compact('request', 'prevBatchData', 'hydroMachineArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function updateInformation(Request $request) {
        BatchCard::where('id', $request->batch_card_id)->update([
            'hydro_machine_id' => $request->hydro_machine_id,
            'in_time' => $request->in_time,
            'out_time' => $request->out_time,
            'machine_in_time' => $request->machine_in_time,
            'machine_out_time' => $request->machine_out_time,
            'hydro_in_time' => $request->hydro_in_time,
            'hydro_out_time' => $request->hydro_out_time,
            'ok_qty' => $request->ok_qty,
            'not_ok_qty' => $request->not_ok_qty,
        ]);
        return Response::json(['success' => true], 200);
    }

    public function loadBatchToken(Request $request) {
        $load = 'batchCard.showTokenNo';
        return Common::loadBatchToken($request, $load);
    }

}
