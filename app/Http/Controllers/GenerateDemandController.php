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
use App\Process;
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
use Common;
use Helper;
use Illuminate\Http\Request;

class GenerateDemandController extends Controller {

    private $statusArr = ['0' => 'Demand Generated', '1' => 'Delivered From Stock'];
    private $formulaArr = [1 => ['formula' => 'G/L', 'label' => 'success']
        , 2 => ['formula' => '%', 'label' => 'warning']
        , 3 => ['formula' => 'Direct Amount', 'label' => 'primary']
    ];

    public function generateDemand() {
        return view('demand.create');
    }

    public function saveDemand(Request $request) {
        $rules = [
            'batch_card_id' => 'required|not_in:0'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }


        $prevDemandArr = Demand::where('batch_card_id', $request->batch_card_id)->pluck('rtp_id', 'id')->toArray();

        //get batch card information
        $batchCardInfo = BatchCard::find($request->batch_card_id);
        //get batch recipe information
        $batchRecipeInfo = BatchRecipe::select('batch_recipe.id as recipe_id')
                        ->where('batch_card_id', $request->batch_card_id)->first();

        $recipeToProcessArr = BatchRecipeToProcess::join('process', 'process.id', '=', 'batch_recipe_to_process.process_id')
                        ->select('batch_recipe_to_process.id as rtp_id', 'process.id as process_id', 'process.name as process_name')
                        ->where('process.water', '!=', '1')
                        ->where('process.process_type_id', '=', '1')
                        ->where('batch_recipe_to_process.batch_recipe_id', $batchRecipeInfo->recipe_id)->get();
        //echo '<pre>';print_r($recipeToProcessArr->toArray());exit;
        // echo '<pre>';print_r($request->rtp_id);exit;

       

        $existingDemandArr = Demand::join('batch_recipe_to_process', 'batch_recipe_to_process.id', '=', 'demand.rtp_id')
                        ->join('process', 'process.id', '=', 'batch_recipe_to_process.process_id')
                        ->select('demand.token_no', 'demand.rtp_id', 'demand.id', 'batch_recipe_to_process.process_id', 'process.name as process_name')
                        ->where('demand.batch_card_id', $request->batch_card_id)
                        ->whereIn('demand.rtp_id', $request->rtp_id)->get();
        //echo '<pre>';print_r($existingDemandArr);exit;

        $recipeToProcessIdsArr = $targetArr = $errorMessage = [];
        $i = 0;
        if (!$existingDemandArr->isEmpty()) {
            foreach ($existingDemandArr as $rec) {
                $recipeToProcessIdsArr[$rec->rtp_id] = $rec->process_name;
            }
        }

        $currentProcess = (count($prevDemandArr) + 1);
        $rtpsArr = $request->rtp_id;

        if (!empty($rtpsArr)) {
            foreach ($rtpsArr as $key => $processId) {
                if (!array_key_exists($processId, $recipeToProcessIdsArr)) {
                    $targetArr[$i]['batch_card_id'] = $request->batch_card_id;
                    $targetArr[$i]['date'] = $request->date;
                    $targetArr[$i]['status'] = '0';
                    $targetArr[$i]['rtp_id'] = $processId;
                    $targetArr[$i]['token_no'] = $batchCardInfo->reference_no . '-' . $currentProcess . '/' . count($recipeToProcessArr);
                    $targetArr[$i]['created_by'] = Auth::user()->id;
                    $targetArr[$i]['created_at'] = date('Y-m-d H:i:s');
                    if ($currentProcess == $recipeToProcessArr->count()) {
                        BatchCard::where('id', $request->batch_card_id)->update(['demand_finish' => '1']);
                    }
                    $currentProcess++;
                    $i++;
                } else {
                    $errorMessage[] = "Demand is already generated for: " . $recipeToProcessIdsArr[$processId];
                }
            }
            if (!empty($targetArr)) {
                Demand::insert($targetArr);
                return Response::json(['success' => true], 200);
            }

            if (!empty($errorMessage)) {
                return Response::json(array('success' => false, 'heading' => 'Error', 'message' => $errorMessage), 401);
            }
        }
    }

    public function getRecipeInfo(Request $request) {

        $prevDemandArr = Demand::where('batch_card_id', $request->batch_card_id)->pluck('rtp_id', 'id')->toArray();

        $batchCardInfo = BatchCard::join('machine', 'machine.id', '=', 'batch_card.machine_id')
                        ->join('machine_model', 'machine_model.id', '=', 'machine.washing_machine_type_id')
                        ->join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                        ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                        ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')
                        ->join('garments_type', 'garments_type.id', '=', 'batch_recipe.garments_type_id')
                        ->select('machine.machine_no', 'machine_model.name as model', 'batch_card.reference_no', 'batch_card.date'
                                , 'style.name as style', 'buyer.name as buyer', 'batch_card.recipe_id', 'batch_card.id'
                                , 'garments_type.name as garments_type', 'batch_recipe.reference_no as recipe_refference'
                                , 'batch_recipe.wash_lot_quantity_weight', 'batch_recipe.wash_lot_quantity_piece', 'batch_card.demand_finish')
                        ->where('batch_card.id', '=', $request->batch_card_id)->first();

        $recipe = BatchRecipe::join('factory', 'factory.id', '=', 'batch_recipe.factory_id')
                        ->join('buyer', 'buyer.id', '=', 'batch_recipe.buyer_id')
                        ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                        ->join('machine_model', 'machine_model.id', '=', 'batch_recipe.machine_model_id')
                        ->select('factory.name as factory', 'buyer.name as buyer', 'style.name as style', 'batch_recipe.wash_lot_quantity_weight'
                                , 'batch_recipe.wash_lot_quantity_piece', 'machine_model.name as machine_model', 'batch_recipe.id as recipe_id')
                        ->where('batch_recipe.batch_card_id', $batchCardInfo->id)->first();

        $processArr = BatchRecipeToProcess::join('process', 'process.id', '=', 'batch_recipe_to_process.process_id')
                ->where('batch_recipe_to_process.batch_recipe_id', $recipe->recipe_id)
                ->where('process.water', '!=', '1')
                ->where('process.process_type_id', '=', '1')
                ->orderBy('batch_recipe_to_process.id', 'asc')
                ->select('batch_recipe_to_process.*', 'process.name', 'process.water as water_type')
                ->get();
        $productArrPre = BatchRecipeToProduct::join('batch_recipe_to_process', 'batch_recipe_to_process.id', '=', 'batch_recipe_to_product.batch_rtp_id')
                        ->join('product', 'product.id', '=', 'batch_recipe_to_product.product_id')
                        ->where('batch_recipe_to_process.batch_recipe_id', $recipe->recipe_id)
                        ->select('product.name', 'batch_recipe_to_product.*')->get();

        $productArr = [];
        foreach ($productArrPre as $item) {
            $productArr[$item->batch_rtp_id][] = $item->toArray();
        }

        $targetArr = [];
        $i = $totalWater = 0;
        if (!$processArr->isEmpty()) {
            foreach ($processArr as $process) {
                $targetArr[$i]['id'] = $process['id'];
                $targetArr[$i]['process_id'] = $process['process_id'];
                $targetArr[$i]['process'] = $process['name'];
                $targetArr[$i]['water'] = $process['water'];
                $targetArr[$i]['water_ratio'] = $process['water_ratio'];
                $targetArr[$i]['dry_chemical'] = $process['dry_chemical'];
                $targetArr[$i]['ph'] = $process['ph'];
                $targetArr[$i]['temperature'] = $process['temperature'];
                $targetArr[$i]['time'] = $process['time'];
                $targetArr[$i]['remarks'] = $process['remarks'];
                $targetArr[$i]['water_type'] = $process['water_type'];
                $targetArr[$i]['process_type_id'] = $process['process_type_id'];
                $totalWater += $process['water'];
                $i++;
            }
        }

        $formulaArr = $this->formulaArr;

        $view = view('demand.recipeInfo')->with(compact('targetArr', 'recipe', 'processArr', 'productArr', 'formulaArr'
                                , 'totalWater', 'id', 'batchCardInfo', 'prevDemandArr', 'currentProcess'))->render();
        return response()->json(['html' => $view]);
    }

    public
            function loadBatchTokenToGenerateDemand(Request $request) {
        $load = 'demand.showBatchNo';
        return Common::loadBatchToken($request, $load);
    }

}
