<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\GarmentsType;
use App\Supplier;
use App\Factory;
use App\MachineModel;
use App\Buyer;
use App\Style;
use App\Process;
use App\ProductToProcess;
use App\Recipe;
use App\RecipeToProcess;
use App\RecipeToProduct;
use App\User;
use App\Wash;
use App\WashType;
use App\DryerType;
use App\Shade;
use App\DryerMachine;
use App\WashTypeToProcess;
use App\Season;
use App\Color;
use Common;
use Auth;
use Response;
use DB;
use PDF;
use Redirect;
use Input;
use Session;
use Helper;
use Illuminate\Http\Request;
use App\Providers\AppServiceProvider;

class RecipeController extends Controller {

    private $controller = 'Recipe';
    private $statusArr = [1 => ['status' => 'Active', 'label' => 'success']
        , 2 => ['status' => 'Inactive', 'label' => 'danger']
    ];
    private $formulaArr = [1 => ['formula' => 'G/L', 'label' => 'success']
        , 2 => ['formula' => '%', 'label' => 'warning']
        , 3 => ['formula' => 'Direct Amount', 'label' => 'primary']
    ];
    private $approvalStatusArr = [1 => ['status' => 'Draft', 'label' => 'warning']
        , 2 => ['status' => 'Finalized', 'label' => 'primary']
    ];

    public function index(Request $request) {
        $qpArr = $request->all();
        $filterApprovalStatusArr = array('' => __('label.SELECT_APPROVAL_STATUS_OPT')) + array('1' => 'Draft', '2' => 'Finalized');
        $filterStatusArr = array('' => __('label.SELECT_STATUS_OPT')) + array('1' => 'Active', '2' => 'Inactive');
        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();
        $targetArr = Recipe::join('style', 'style.id', '=', 'recipe.style_id')
                ->join('factory', 'factory.id', '=', 'recipe.factory_id')
                ->join('buyer', 'buyer.id', '=', 'recipe.buyer_id')
                ->join('machine_model', 'machine_model.id', '=', 'recipe.machine_model_id')
                ->join('garments_type', 'garments_type.id', '=', 'recipe.garments_type_id')
                ->join('shade', 'shade.id', '=', 'recipe.shade_id')
                ->join('season', 'season.id', '=', 'recipe.season_id')
                ->join('color', 'color.id', '=', 'recipe.color_id')
                ->select('recipe.*', 'factory.name as factory', 'buyer.name as buyer', 'machine_model.name as machine_model'
                        , 'garments_type.name as garments_type', 'style.name as style'
                        , 'shade.name as shade', 'season.name as season', 'color.name as color')
                ->orderBy('recipe.id', 'desc');
        //begin filtering
        $searchText = $request->search;
        $recipeArr = Recipe::select('reference_no')->get();
        $factoryArr = ['0' => __('label.SELECT_FACTORY_OPT')] + Factory::pluck('name', 'id')->toArray();
        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $styleArr = ['0' => __('label.SELECT_STYLE_OPT')] + Style::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $shadeArr = ['0' => __('label.SELECT_SHADE_OPT')] + Shade::orderBy('name', 'asc')->where('status', 1)->pluck('name', 'id')->toArray();
        $seasonArr = ['0' => __('label.SELECT_SEASON_OPT')] + Season::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $colorArr = ['0' => __('label.SELECT_COLOR_OPT')] + Color::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $machineModelArr = ['0' => __('label.SELECT_WASHING_MACHINE_TYPE_OPT')] + MachineModel::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('recipe.reference_no', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->date)) {
            $targetArr = $targetArr->where('recipe.date', '=', $request->date);
        }
        if (!empty($request->factory)) {
            $targetArr = $targetArr->where('recipe.factory_id', '=', $request->factory);
        }
        if (!empty($request->buyer)) {
            $targetArr = $targetArr->where('recipe.buyer_id', '=', $request->buyer);
        }
        if (!empty($request->style_id)) {
            $targetArr = $targetArr->where('recipe.style_id', '=', $request->style_id);
        }
        if (!empty($request->shade_id)) {
            $targetArr = $targetArr->where('recipe.shade_id', '=', $request->shade_id);
        }
        if (!empty($request->season_id)) {
            $targetArr = $targetArr->where('recipe.season_id', '=', $request->season_id);
        }
        if (!empty($request->color_id)) {
            $targetArr = $targetArr->where('recipe.color_id', '=', $request->color_id);
        }
        if (!empty($request->washing_machine_type)) {
            $targetArr = $targetArr->where('recipe.machine_model_id', '=', $request->washing_machine_type);
        }
        if ($request->fil_status != '') {
            $targetArr = $targetArr->where('recipe.approval_status', '=', $request->fil_status);
        }
        if ($request->fil_active_status != '') {
            $targetArr = $targetArr->where('recipe.status', '=', $request->fil_active_status);
        }
        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('recipe?page=' . $page);
        }
        $statusArr = $this->statusArr;
        $approvalStatusArr = $this->approvalStatusArr;
        return view('recipe.index')->with(compact('qpArr', 'targetArr', 'statusArr', 'filterApprovalStatusArr'
                                , 'filterStatusArr', 'recipeArr', 'factoryArr', 'buyerArr', 'seasonArr', 'approvalStatusArr'
                                , 'colorArr', 'machineModelArr', 'userFirstNameArr', 'userLastNameArr', 'styleArr', 'shadeArr'));
    }

    public function create(Request $request) {
        $qpArr = $request->all();
        $factoryArr = ['0' => __('label.SELECT_FACTORY_OPT')] + Factory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $washArr = ['0' => __('label.SELECT_WASH_OPT')] + Wash::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $machineModelArr = ['0' => __('label.SELECT_WASHING_MACHINE_TYPE_OPT')] + MachineModel::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $styleArr = ['0' => __('label.SELECT_STYLE_OPT')] + Style::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $garmentsTypeArr = ['0' => __('label.SELECT_GARMENTS_TYPE_OPT')] + GarmentsType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $relatedProcessArr = ProductToProcess::select(DB::raw('DISTINCT(process_id) as process_id'))->pluck('process_id')->toArray();
        $dryerTypeArr = [0 => __('label.SELECT_DRYER_TYPE_OPT')] + DryerType::pluck('name', 'id')->toArray();
        $dryerMachineArr = [0 => __('label.SELECT_DRYER_MACHINE_OPT')];
        $washTypeArr = ['0' => __('label.SELECT_WASH_TYPE_OPT')] + WashType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $seasonArr = ['0' => __('label.SELECT_SEASON_OPT')] + Season::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $colorArr = ['0' => __('label.SELECT_COLOR_OPT')] + Color::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();


        $processArrPre = Process::join('process_type', 'process_type.id', '=', 'process.process_type_id')
                        ->orderBy('process_type.id', 'asc')
                        ->orderBy('process.name', 'asc')
                        ->select('process.name', 'process.id', 'process.water', 'process_type.name as process_type_name')
                        ->get()->toArray();
        $processArr[0] = __('label.SELECT_PROCESS_OPT');
        foreach ($processArrPre as $item) {
            if ($item['water'] == '1' || $item['water'] == '0') {
                $processArr[$item['process_type_name']][$item['id']] = $item['name'];
            } else if (in_array($item['id'], $relatedProcessArr)) {
                $processArr[$item['process_type_name']][$item['id']] = $item['name'];
            }
        }
        $orderList = array('0' => __('label.SELECT_ORDER_OPT'));
        $processList = [];
        $shadeList = [0 => __('label.SELECT_SHADE_OPT')] + Shade::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        return view('recipe.create')->with(compact('factoryArr', 'buyerArr', 'machineModelArr'
                                , 'styleArr', 'garmentsTypeArr', 'supplierArr', 'processArr'
                                , 'qpArr', 'washArr', 'dryerTypeArr', 'shadeList', 'dryerMachineArr'
                                , 'orderList', 'washTypeArr', 'processList', 'seasonArr', 'colorArr'));
    }

    public function getDryerMachine(Request $request) {
        return Common::getDryerMachine($request);
    }

    public function getProcessWiseProduct(Request $request) {
        return Common::getProcessWiseProduct($request);
    }

    public function getFactoryCode(Request $request) {
        return Common::getFactoryCode($request);
    }

    public function getDryerMachineEdited(Request $request) {
        return Common::getDryerMachine($request);
    }

    public function getProcessWiseProductEdited(Request $request) {
        return Common::getProcessWiseProduct($request);
    }

    public function getFactoryCodeEdited(Request $request) {
        return Common::getFactoryCode($request);
    }

    public static function getFactoryFinalizeCode($factoryId) {
        $factoryInfo = Factory::find($factoryId);
        //Fetch Last Reference No with Selected Factory
        $lastRecipeRefNo = Recipe::select('reference_no')
                        ->where('recipe.factory_id', $factoryId)
                        ->where('recipe.approval_status', '2')
                        ->orderBy('recipe.id', 'desc')->first();
        //Get Last/Max Reference No and Add One (+1) to Get New Reference No
        if (!empty($lastRecipeRefNo)) {
            $newSerialNo = substr($lastRecipeRefNo->reference_no, 0, strpos($lastRecipeRefNo->reference_no, "-")) . "-" . ((substr($lastRecipeRefNo->reference_no, (strpos($lastRecipeRefNo->reference_no, "-") + 1), strlen($lastRecipeRefNo->reference_no))) + 1);
        } else {
            $newSerialNo = $factoryInfo->code . "-1";
        }
        return $newSerialNo;
    }

    public function addProcess(Request $request) {
        return Common::addProcess($request);
    }

    public function updateProcess(Request $request) {
        return Common::updateProcess($request);
    }

    public function addProcessEdited(Request $request) {
        return Common::addProcess($request);
    }

    public function updateProcessEdited(Request $request) {
        return Common::updateProcess($request);
    }

    public function saveRecipe(Request $request) {
        $formulaArr = $request->formula;
        $qtyArr = $request->qty;
        $totalQtyArr = $request->total_qty;
        $totalQtyDetailArr = $request->total_qty_detail;
        $waterArr = $request->water;
        $dryChemicalArr = $request->dry_chemical;
        $phArr = $request->ph;
        $temperatureArr = $request->temperature;
        $timeArr = $request->time;
        $waterRatioArr = $request->water_ratio;
        $remarksArr = $request->remarks;
        $productList = Product::pluck('name', 'id')->toArray();
        $processList = Process::pluck('name', 'id')->toArray();
        $processInfo = Process::find($request->process_id);
        $rules = [
            'reference_no' => 'required|unique:recipe,reference_no',
            'date' => 'required|date',
            'factory_id' => 'required|not_in:0',
            'buyer_id' => 'required|not_in:0',
            'wash_type_id' => 'required|not_in:0',
            'shade_id' => 'required|not_in:0',
            'season_id' => 'required|not_in:0',
            'color_id' => 'required|not_in:0',
            'machine_model_id' => 'required|not_in:0',
            'wash_lot_quantity_weight' => 'required|not_in:0',
            'wash_lot_quantity_piece' => 'required|not_in:0',
            'weight_one_piece' => 'required|not_in:0',
            'style_id' => 'required|not_in:0',
            'order_no' => 'required',
            'garments_type_id' => 'required|not_in:0',
            'supplier_id' => 'required',
            'dryer_type' => 'required',
            'drying_time' => 'required|numeric',
            'drying_temperature' => 'required|numeric',
            'process_id' => 'required|not_in:0',
            'dryer_type_id' => 'required|not_in:0'
        ];
        $message = [];

        if (!empty($qtyArr)) {
            foreach ($qtyArr as $identifier => $qtyItem) {
                foreach ($qtyItem as $processId => $productArr) {
                    if (is_array($productArr)) {
//                        if ($productArr != 'water') {
                        foreach ($productArr as $productId => $productDoge) {
                            $productInfo = Product::find($productId);

                            $rules['formula.' . $identifier . '.' . $processId . '.' . $productId] = 'required';
                            $rules['total_qty.' . $identifier . '.' . $processId . '.' . $productId] = 'required|numeric';

                            $message['formula.' . $identifier . '.' . $processId . '.' . $productId . '.required'] = 'Formula is required for ' . $processList[$processId] . '-' . $productList[$productId];
                            $message['total_qty.' . $identifier . '.' . $processId . '.' . $productId . '.required'] = 'Total Qty is required for ' . $processList[$processId] . '-' . $productList[$productId];
                            $message['total_qty.' . $identifier . '.' . $processId . '.' . $productId . '.numeric'] = 'Total Qty must be numeric for ' . $processList[$processId] . '-' . $productList[$productId];

                            if (!empty($productInfo->type_of_dosage_ratio) && ($productInfo->type_of_dosage_ratio != '3')) {
                                $rules['qty.' . $identifier . '.' . $processId . '.' . $productId] = 'nullable|numeric|between:' . Helper::numberformat($productInfo->from_dosage, 2) . ',' . Helper::numberformat($productInfo->to_dosage, 2);
                            } else if (!empty($productInfo->type_of_dosage_ratio) && ($productInfo->type_of_dosage_ratio == '3')) {
                                $rules['total_qty.' . $identifier . '.' . $processId . '.' . $productId] = 'required|numeric';
                            }

                            $message['qty.' . $identifier . '.' . $processId . '.' . $productId . '.between'] = 'Qty must be between ' . Helper::numberformat($productInfo->from_dosage, 2) . ' and ' . Helper::numberformat($productInfo->to_dosage, 2) . ' for ' . $processList[$processId] . ' - ' . $productList[$productId];
                        }
//                        }
                    }
                }
                //for dry process temperature
                $rules['temperature.' . $identifier . '.' . $processId] = 'required|numeric';
                $message['temperature.' . $identifier . '.' . $processId . '.required'] = 'Temperature is required for ' . $processList[$processId];
                $message['temperature.' . $identifier . '.' . $processId . '.numeric'] = 'Temperature must be numeric for ' . $processList[$processId];
                //for dry process time
                $rules['time.' . $identifier . '.' . $processId] = 'required';
                $message['time.' . $identifier . '.' . $processId . '.required'] = 'Time is required for ' . $processList[$processId];
            }
        }


        if (!empty($waterArr)) {
            foreach ($waterArr as $identifier => $processWaterArr) {
                $proccessKeyArr = array_keys($processWaterArr);
                $processId = $proccessKeyArr[0];
                //for water
                $rules['water.' . $identifier . '.' . $processId] = 'required|numeric';
                $message['water.' . $identifier . '.' . $processId . '.required'] = 'Water is required for ' . $processList[$processId];
                $message['water.' . $identifier . '.' . $processId . '.numeric'] = 'Water must be numeric for ' . $processList[$processId];
                //for temperature
                $rules['temperature.' . $identifier . '.' . $processId] = 'required|numeric';
                $message['temperature.' . $identifier . '.' . $processId . '.required'] = 'Temperature is required for ' . $processList[$processId];
                $message['temperature.' . $identifier . '.' . $processId . '.numeric'] = 'Temperature must be numeric for ' . $processList[$processId];
                //for time
                $rules['time.' . $identifier . '.' . $processId] = 'required';
                $message['time.' . $identifier . '.' . $processId . '.required'] = 'Time is required for ' . $processList[$processId];
            }
        }
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        $recipe = new Recipe;
        $recipe->shade_id = $request->shade_id;
        $recipe->date = $request->date;
        $recipe->factory_id = $request->factory_id;
        $recipe->buyer_id = $request->buyer_id;
        $recipe->wash_id = 0; //for different purpose wash id is now 0.
        $recipe->season_id = $request->season_id;
        $recipe->color_id = $request->color_id;
        $recipe->machine_model_id = $request->machine_model_id;
        $recipe->dryer_machine_id = $request->dryer_machine_id;
        $recipe->wash_lot_quantity_weight = $request->wash_lot_quantity_weight;
        $recipe->wash_lot_quantity_piece = $request->wash_lot_quantity_piece;
        $recipe->weight_one_piece = $request->weight_one_piece;
        $recipe->style_id = $request->style_id;
        $recipe->dry_process_info = $request->dry_process_info;
        $recipe->order_no = $request->order_no;
        $recipe->garments_type_id = $request->garments_type_id;
        $recipe->supplier_id = $request->supplier_id;
        $recipe->fabric_ref = $request->fabric_ref;
        $recipe->dryer_type = $request->dryer_type;
        $recipe->dryer_load_qty = $request->dryer_load_qty;
        $recipe->drying_temperature = $request->drying_temperature;
        $recipe->drying_time = $request->drying_time;
        $recipe->dryer_type_id = $request->dryer_type_id;

        if (!empty($request->add_btn)) {
            if (!empty($request->save_draft)) { //For saving recipe as Draft
                $recipe->reference_no = $request->reference_no;
                $returnMessage = 'Recipe has been saved as Draft';
                $recipe->status = '2';
                $recipe->approval_status = '1';
            }
            $recipe->save();
            //update query for origin_id update in recipe table
            //Recipe::where('id', $recipe->id)->update(['origin_id' => $recipe->id]);
            $recipeToProductArr = [];
            $i = 0;
            foreach ($qtyArr as $identifier => $processInfo) {
                $recipeToProcess = new RecipeToProcess;
                $recipeToProcess->recipe_id = $recipe->id;
                $recipeToProcess->identifier = $identifier;
                $recipeToProcess->process_id = key($processInfo);
                $recipeToProcess->dry_chemical = isset($dryChemicalArr[$identifier][key($processInfo)]) ? $dryChemicalArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->water = isset($waterArr[$identifier][key($processInfo)]) ? $waterArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->water_ratio = isset($waterRatioArr[$identifier][key($processInfo)]) ? $waterRatioArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->ph = isset($phArr[$identifier][key($processInfo)]) ? $phArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->temperature = $temperatureArr[$identifier][key($processInfo)];
                $recipeToProcess->time = $timeArr[$identifier][key($processInfo)];
                $recipeToProcess->remarks = isset($remarksArr[$identifier][key($processInfo)]) ? $remarksArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->save();

                $productArr = $processInfo[key($processInfo)];
                if (is_array($productArr)) {
                    foreach ($productArr as $productId => $doge) {
                        $recipeToProductArr[$i]['rtp_id'] = $recipeToProcess->id;
                        $recipeToProductArr[$i]['product_id'] = $productId;
                        $recipeToProductArr[$i]['formula'] = $formulaArr[$identifier][key($processInfo)][$productId];
                        $recipeToProductArr[$i]['qty'] = $qtyArr[$identifier][key($processInfo)][$productId];
                        $recipeToProductArr[$i]['total_qty'] = $totalQtyArr[$identifier][key($processInfo)][$productId];
                        $recipeToProductArr[$i]['total_qty_detail'] = $totalQtyDetailArr[$identifier][key($processInfo)][$productId];
                        $i++;
                    }
                }
            }
            RecipeToProduct::insert($recipeToProductArr);
            $processNoArr = $request->process_no;
            /* Add data WashType Wise Process */
            if (!empty($request->wash_type_id)) {
                foreach ($request->wash_type_id as $key => $washTypeId) {
                    if (!empty($processNoArr[$washTypeId])) {
                        $commaSeparatedArray = explode(',', $processNoArr[$washTypeId]);
                    }
                    $newNodes = [];
                    foreach ($commaSeparatedArray as $key => $processId) {
                        $preProcessIdArr = explode('-', $processId);
                        $newNodes[$preProcessIdArr[0]] = $preProcessIdArr[1];
                    }
                    $washTypeToProcess = new WashTypeToProcess;
                    $washTypeToProcess->recipe_id = $recipe->id;
                    $washTypeToProcess->wash_type_id = $washTypeId;
                    $washTypeToProcess->process_id = json_encode($newNodes, JSON_FORCE_OBJECT);
                    $washTypeToProcess->save();
                }
            }

            /** Start:: Store Wash Type to Water in Recipe Table as Json Encode * */
            if (!empty($request->wash_water)) {
                $washTypeToWater = [];
                foreach ($request->wash_water as $washTypeId => $waterVal) {
                    $washTypeToWater[$washTypeId] = $waterVal;
                    Recipe::where('id', $recipe->id)->update(array('wash_type_to_water' => json_encode($washTypeToWater, JSON_FORCE_OBJECT)));
                }
            }
            /** End:: Store Wash Type to Water in Recipe Table as Json Encode * */
            return Response::json(['success' => true, 'heading' => 'Success', 'message' => $returnMessage], 200);
        } else {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_PROCESS_FOR_SAVE')], 401);
        }
    }

    public function edit(Request $request, $id) {
        $target = Recipe::find($id);
        $productList = Product::pluck('name', 'id')->toArray();
        $qpArr = $request->all();
        $factoryArr = ['0' => __('label.SELECT_FACTORY_OPT')] + Factory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $machineModelArr = ['0' => __('label.SELECT_WASHING_MACHINE_TYPE_OPT')] + MachineModel::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $styleArr = ['0' => __('label.SELECT_STYLE_OPT')] + Style::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $garmentsTypeArr = ['0' => __('label.SELECT_GARMENTS_TYPE_OPT')] + GarmentsType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')] + Supplier::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $washArr = ['0' => __('label.SELECT_WASH_OPT')] + Wash::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $washTypeArr = ['0' => __('label.SELECT_WASH_TYPE_OPT')] + WashType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $relatedProcessArr = ProductToProcess::select(DB::raw('DISTINCT(process_id) as process_id'))->pluck('process_id')->toArray();
        $dryerTypeArr = [0 => __('label.SELECT_DRYER_TYPE_OPT')] + DryerType::pluck('name', 'id')->toArray();
        $dryerMachineArr = [0 => __('label.SELECT_DRYER_MACHINE_OPT')] + DryerMachine::pluck('machine_no', 'id')->toArray();
        $processArr = Process::pluck('name', 'id')->toArray();
        $seasonArr = ['0' => __('label.SELECT_SEASON_OPT')] + Season::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $colorArr = ['0' => __('label.SELECT_COLOR_OPT')] + Color::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();



        $processArrPre = Process::join('process_type', 'process_type.id', '=', 'process.process_type_id')
                        ->orderBy('process_type.id', 'asc')
                        ->orderBy('process.name', 'asc')
                        ->select('process.name', 'process.id', 'process.water', 'process_type.name as process_type_name')
                        ->get()->toArray();
        $processedArr[0] = __('label.SELECT_PROCESS_OPT');
        foreach ($processArrPre as $item) {
            if ($item['water'] == '1' || $item['water'] == '0') {
                $processedArr[$item['process_type_name']][$item['id']] = $item['name'];
            } else if (in_array($item['id'], $relatedProcessArr)) {
                $processedArr[$item['process_type_name']][$item['id']] = $item['name'];
            }
        }
        $processList = RecipeToProcess::join('process', 'process.id', '=', 'recipe_to_process.process_id')
                        ->where('recipe_to_process.recipe_id', $id)
                        ->orderBy('recipe_to_process.id', 'asc')
                        ->select('recipe_to_process.*', 'process.id as process_id'
                                , 'process.name', 'process.water as water_type', 'process.process_type_id')->get();

        $productArrPre = RecipeToProduct::join('recipe_to_process', 'recipe_to_process.id', '=', 'recipe_to_product.rtp_id')
                        ->join('product', 'product.id', '=', 'recipe_to_product.product_id')
                        ->where('recipe_to_process.recipe_id', $id)
                        ->select('product.name', 'qty', 'total_qty', 'total_qty_detail', 'rtp_id'
                                , 'product.id as product_id', 'product.type_of_dosage_ratio', 'product.from_dosage', 'product.id as product_id', 'product.type_of_dosage_ratio'
                                , 'product.to_dosage'
                                , 'formula')->get();
        $productArr = [];
        foreach ($productArrPre as $item) {
            $productArr[$item->rtp_id][] = $item->toArray();
        }
        $targetArr = [];
        $i = $totalWater = 0;
        foreach ($processList as $process) {
            $targetArr[$i]['process_id'] = $process['id'];
            $targetArr[$i]['dry_chemical'] = $process['dry_chemical'];
            $targetArr[$i]['process_type_id'] = $process['process_type_id'];
            $targetArr[$i]['identifier'] = $process['identifier'];
            $targetArr[$i]['org_process_id'] = $process['process_id'];
            $targetArr[$i]['process'] = $process['name'];
            $targetArr[$i]['water'] = $process['water'];
            $targetArr[$i]['water_ratio'] = $process['water_ratio'];
            $targetArr[$i]['ph'] = $process['ph'];
            $targetArr[$i]['temperature'] = $process['temperature'];
            $targetArr[$i]['time'] = $process['time'];
            $targetArr[$i]['remarks'] = $process['remarks'];
            $targetArr[$i]['water_type'] = $process['water_type'];
            $totalWater += $process['water'];
            $i++;
        }//EOF-Foreach

        $orderList = array('0' => __('label.SELECT_ORDER_OPT'));
        $shadeList = [0 => __('label.SELECT_SHADE_OPT')] + Shade::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        //Prepare ProcessArr for WashType and Added Process for Recipe
        $processInfoArr = $newProcessInfoArr = [];
        if (!empty($targetArr)) {
            $j = 1;
            foreach ($targetArr as $processInfo) {
                $processInfoArr[$processInfo['identifier'] . '-' . $processInfo['org_process_id']] = $j . '.' . $processInfo['process'];
                $j++;
            }
        }

        $washTypeInfoArr = WashTypeToProcess::where('recipe_id', $id)->pluck('process_id', 'wash_type_id')->toArray();
        $processedWashTypeArr = [];
        if (!empty($washTypeInfoArr)) {
            foreach ($washTypeInfoArr as $washTypeId => $process) {
                $processedWashTypeArr[$washTypeId] = (array) json_decode($process);
            }
        }

        $processNameList = Process::pluck('name', 'id')->toArray();
        $washTypeToWaterArr = (array) json_decode($target->wash_type_to_water);
        return view('recipe.edit')->with(compact('target', 'factoryArr', 'buyerArr', 'machineModelArr', 'productArr'
                                , 'processedArr', 'totalWater', 'styleArr', 'garmentsTypeArr', 'supplierArr', 'washArr'
                                , 'processArr', 'qpArr', 'targetArr', 'dryerTypeArr', 'shadeList', 'productList'
                                , 'dryerMachineArr', 'orderList', 'washTypeInfo', 'washTypeArr'
                                , 'processArr', 'washTypeInfoArr', 'washTypeArr', 'processInfoArr'
                                , 'processedWashTypeArr', 'processNameList', 'newProcessInfoArr'
                                , 'washTypeToWaterArr', 'seasonArr', 'colorArr'));
    }

    public function updateRecipe(Request $request) {
        
        $recipe = Recipe::find($request->id);
        $pageNumber = $request->filter;
        $formulaArr = $request->formula;
        $qtyArr = $request->qty;
        $totalQtyArr = $request->total_qty;
        $totalQtyDetailArr = $request->total_qty_detail;
        $waterArr = $request->water;
        $dryChemicalArr = $request->dry_chemical;
        $phArr = $request->ph;
        $temperatureArr = $request->temperature;
        $timeArr = $request->time;
        $waterRatioArr = $request->water_ratio;
        $remarksArr = $request->remarks;
        $productList = Product::pluck('name', 'id')->toArray();
        $processList = Process::pluck('name', 'id')->toArray();
        $rules = [
            'reference_no' => 'required',
            'date' => 'required|date',
            'factory_id' => 'required|not_in:0',
            'buyer_id' => 'required|not_in:0',
            //'wash_id' => 'required|not_in:0',
            'shade_id' => 'required|not_in:0',
            'season_id' => 'required|not_in:0',
            'color_id' => 'required|not_in:0',
            'machine_model_id' => 'required|not_in:0',
            'wash_lot_quantity_weight' => 'required|not_in:0',
            'wash_lot_quantity_piece' => 'required|not_in:0',
            'weight_one_piece' => 'required|not_in:0',
            'style_id' => 'required|not_in:0',
            'order_no' => 'required',
            'garments_type_id' => 'required|not_in:0',
            'supplier_id' => 'required',
            'dryer_type' => 'required',
            'drying_time' => 'required|numeric',
            'dryer_type_id' => 'required|not_in:0',
            'drying_temperature' => 'required|numeric',
            'wash_type_id' => 'required',
            'process_no' => 'required',
        ];
//        if ($processInfo->process_type_id == '1' && $processInfo->water != '1') {
//            $rules['product_id'] = 'required|not_in:0';
//        }
        if (!empty($qtyArr)) {
            foreach ($qtyArr as $identifier => $qtyItem) {
                foreach ($qtyItem as $processId => $productArr) {
                    if (is_array($productArr)) {
//                    if ($productArr != 'water') {
                        foreach ($productArr as $productId => $productDoge) {
                            $productInfo = Product::find($productId);

                            $rules['formula.' . $identifier . '.' . $processId . '.' . $productId] = 'required';
                            $rules['total_qty.' . $identifier . '.' . $processId . '.' . $productId] = 'required|numeric';

                            $message['formula.' . $identifier . '.' . $processId . '.' . $productId . '.required'] = 'Formula is required for ' . $processList[$processId] . '-' . $productList[$productId];
                            $message['total_qty.' . $identifier . '.' . $processId . '.' . $productId . '.required'] = 'Total Qty is required for ' . $processList[$processId] . '-' . $productList[$productId];
                            $message['total_qty.' . $identifier . '.' . $processId . '.' . $productId . '.numeric'] = 'Total Qty must be numeric for ' . $processList[$processId] . '-' . $productList[$productId];

                            if (!empty($productInfo->type_of_dosage_ratio) && ($productInfo->type_of_dosage_ratio != '3')) {
                                $rules['qty.' . $identifier . '.' . $processId . '.' . $productId] = 'nullable|numeric|between:' . $productInfo->from_dosage . ',' . $productInfo->to_dosage;
                            } else if (!empty($productInfo->type_of_dosage_ratio) && ($productInfo->type_of_dosage_ratio == '3')) {
                                $rules['total_qty.' . $identifier . '.' . $processId . '.' . $productId] = 'required|numeric';
                            }

                            $message['qty.' . $identifier . '.' . $processId . '.' . $productId . '.between'] = 'Qty must be between ' . Helper::numberformat($productInfo->from_dosage, 2) . ' and ' . Helper::numberformat($productInfo->to_dosage, 2) . ' for ' . $processList[$processId] . ' - ' . $productList[$productId];
                        }
                    }
                }
                //for dry process temperature
                $rules['temperature.' . $identifier . '.' . $processId] = 'required|numeric';
                $message['temperature.' . $identifier . '.' . $processId . '.required'] = 'Temperature is required for ' . $processList[$processId];
                $message['temperature.' . $identifier . '.' . $processId . '.numeric'] = 'Temperature must be numeric for ' . $processList[$processId];
                //for dry process time
                $rules['time.' . $identifier . '.' . $processId] = 'required';
                $message['time.' . $identifier . '.' . $processId . '.required'] = 'Time is required for ' . $processList[$processId];
            }
        }
        if (!empty($waterArr)) {
            foreach ($waterArr as $identifier => $processWaterArr) {
                $proccessKeyArr = array_keys($processWaterArr);
                $processId = $proccessKeyArr[0];
                //for water
                $rules['water.' . $identifier . '.' . $processId] = 'required|numeric';
                $message['water.' . $identifier . '.' . $processId . '.required'] = 'Water is required for ' . $processList[$processId];
                $message['water.' . $identifier . '.' . $processId . '.numeric'] = 'Water must be numeric for ' . $processList[$processId];
                //for temperature
                $rules['temperature.' . $identifier . '.' . $processId] = 'required|numeric';
                $message['temperature.' . $identifier . '.' . $processId . '.required'] = 'Temperature is required for ' . $processList[$processId];
                $message['temperature.' . $identifier . '.' . $processId . '.numeric'] = 'Temperature must be numeric for ' . $processList[$processId];
                //for time
                $rules['time.' . $identifier . '.' . $processId] = 'required';
                $message['time.' . $identifier . '.' . $processId . '.required'] = 'Time is required for ' . $processList[$processId];
            }
        }
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        $recipe->shade_id = $request->shade_id;
        //$recipe->origin_id = $request->origin_id;
        $recipe->date = $request->date;
        $recipe->factory_id = $request->factory_id;
        $recipe->buyer_id = $request->buyer_id;
        $recipe->wash_id = 0; //for different purpose wash id is now 0.
        $recipe->season_id = $request->season_id;
        $recipe->color_id = $request->color_id;
        $recipe->machine_model_id = $request->machine_model_id;
        $recipe->wash_lot_quantity_weight = $request->wash_lot_quantity_weight;
        $recipe->wash_lot_quantity_piece = $request->wash_lot_quantity_piece;
        $recipe->weight_one_piece = $request->weight_one_piece;
        $recipe->style_id = $request->style_id;
        $recipe->dry_process_info = $request->dry_process_info;
        $recipe->order_no = $request->order_no;
        $recipe->garments_type_id = $request->garments_type_id;
        $recipe->supplier_id = $request->supplier_id;
        $recipe->fabric_ref = $request->fabric_ref;
        $recipe->dryer_type = $request->dryer_type;
        $recipe->dryer_load_qty = $request->dryer_load_qty;
        $recipe->drying_temperature = $request->drying_temperature;
        $recipe->drying_time = $request->drying_time;
        $recipe->dryer_type_id = $request->dryer_type_id;
        $recipe->dryer_machine_id = $request->dryer_machine_id;

        if (isset($request->save_draft) && !empty($request->save_draft)) { //For saving recipe as Draft
            $recipe->reference_no = $request->reference_no;
            $returnMessage = 'Recipe has been saved as Draft';
            $recipe->status = '2';
            $recipe->approval_status = '1';
        }
        $recipe->save();
        $recipeToProductArr = [];
        $i = 0;
        //Fetch all the rtpID & delete the previous data
        $rtpIdArr = RecipeToProcess::where('recipe_id', $request->id)->pluck('id')->toArray();
        RecipeToProcess::where('recipe_id', $request->id)->delete();
        RecipeToProduct::whereIn('rtp_id', $rtpIdArr)->delete();
        if (!empty($qtyArr)) {
            foreach ($qtyArr as $identifier => $processInfo) {
                $recipeToProcess = new RecipeToProcess;
                $recipeToProcess->recipe_id = $recipe->id;
                $recipeToProcess->identifier = $identifier;
                $recipeToProcess->process_id = key($processInfo);
                $recipeToProcess->dry_chemical = isset($dryChemicalArr[$identifier][key($processInfo)]) ? $dryChemicalArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->water = isset($waterArr[$identifier][key($processInfo)]) ? $waterArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->water_ratio = isset($waterRatioArr[$identifier][key($processInfo)]) ? $waterRatioArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->ph = isset($phArr[$identifier][key($processInfo)]) ? $phArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->temperature = $temperatureArr[$identifier][key($processInfo)];
                $recipeToProcess->time = $timeArr[$identifier][key($processInfo)];
                $recipeToProcess->remarks = isset($remarksArr[$identifier][key($processInfo)]) ? $remarksArr[$identifier][key($processInfo)] : null;
                $recipeToProcess->save();
                $productArr = $processInfo[key($processInfo)];
                if (is_array($productArr)) {
                    foreach ($productArr as $productId => $doge) {
                        $recipeToProductArr[$i]['rtp_id'] = $recipeToProcess->id;
                        $recipeToProductArr[$i]['product_id'] = $productId;
                        $recipeToProductArr[$i]['formula'] = $formulaArr[$identifier][key($processInfo)][$productId];
                        $recipeToProductArr[$i]['qty'] = $qtyArr[$identifier][key($processInfo)][$productId];
                        $recipeToProductArr[$i]['total_qty'] = $totalQtyArr[$identifier][key($processInfo)][$productId];
                        $recipeToProductArr[$i]['total_qty_detail'] = $totalQtyDetailArr[$identifier][key($processInfo)][$productId];
                        $i++;
                    }
                }
            }
        }
        RecipeToProduct::insert($recipeToProductArr);
        //Delete Previous data of WashType Process on this recipe
        $processNoArr = $request->process_no;
        /* Add data WashType Wise Process */
        if (!empty($request->wash_type_id)) {
            WashTypeToProcess::where('recipe_id', $request->id)->delete();
            foreach ($request->wash_type_id as $key => $washTypeId) {
                if (!empty($processNoArr[$washTypeId])) {
                    $commaSeparatedArray = explode(',', $processNoArr[$washTypeId]);
                }
                $newNodes = [];
                foreach ($commaSeparatedArray as $key => $processId) {
                    $preProcessIdArr = explode('-', $processId);
                    $newNodes[$preProcessIdArr[0]] = $preProcessIdArr[1];
                }

                $washTypeToProcess = new WashTypeToProcess;
                $washTypeToProcess->recipe_id = $recipe->id;
                $washTypeToProcess->wash_type_id = $washTypeId;
                $washTypeToProcess->process_id = json_encode($newNodes, JSON_FORCE_OBJECT);
                $washTypeToProcess->save();
            }
        }

        /** Start:: Store Wash Type to Water in Recipe Table as Json Encode * */
        if (!empty($request->wash_water)) {
            $washTypeToWater = [];
            foreach ($request->wash_water as $washTypeId => $waterVal) {
                $washTypeToWater[$washTypeId] = $waterVal;
                Recipe::where('id', $request->id)->update(array('wash_type_to_water' => json_encode($washTypeToWater, JSON_FORCE_OBJECT)));
            }
        }
        /** End:: Store Wash Type to Water in Recipe Table as Json Encode * */
        //exit;
        return Response::json(['success' => true, 'heading' => 'Success', 'message' => $returnMessage, 'filter' => $pageNumber], 200);
    }

    public function getRecipeDetails(Request $request, $id = null) {
        $detailsView = 'details';
        return Common::getRecipeDetails($request, $id, 44, $detailsView);
    }

    public function destroy(Request $request, $id) {
        $target = Recipe::find($id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency
        $dependencyArr = [
            'RecipeToProcess' => ['1' => 'recipe_id'],
            'WashTypeToProcess' => ['1' => 'recipe_id'],
            'BatchWashTypeToProcess' => ['1' => 'recipe_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return redirect('product' . $pageNumber);
                }
            }
        }


        if ($target->delete()) {
            $dataArr = RecipeToProcess::select('*')->where('recipe_id', $id)->get();
            if (!empty($dataArr)) {
                foreach ($dataArr as $item) {
                    RecipeToProduct::where('rtp_id', $item->id)->delete();
                }
            }
            RecipeToProcess::where('recipe_id', $id)->delete();
            Session::flash('error', __('label.RECIPE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.RECIPE_COULD_NOT_BE_DELETED'));
        }
        return redirect('recipe' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&fil_status=' . $request->fil_status . '&fil_active_status=' . $request->fil_active_status . '&factory=' . $request->factory . '&buyer=' . $request->buyer . '&season=' . $request->season . '&color=' . $request->color
                . '&washing_machine_type=' . $request->washing_machine_type . '&date=' . $request->date
                . '&style_id=' . $request->style_id . '&shade_id=' . $request->shade_id . '&season_id=' . $request->season_id . '&color_id=' . $request->color_id;
        $redirectTo = 'recipe';
        return Redirect::to($redirectTo . '?' . $url);
    }

    public function generateVersionNo($versionNo = null, $clonedParentId = null) {
        $recipeCount = Recipe::select(DB::raw('COUNT(*) as total'))->where('parent_id', $clonedParentId)->first();
        $clonedVersion = $versionNo . '.' . (($recipeCount->total) + 1);
        return $clonedVersion;
    }

    public function doActive(Request $request, $id) {
        $target = Recipe::find($id);
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('recipe');
        }
        $target->status = '1';
        $target->save();
        Session::flash('success', $target->reference_no . ' ' . __('label.HAS_BEEN_ACTIVATED_SUCCESSFULLY'));
        if ($request->type == 'finalized') {
            return redirect('recipe/finalized');
        } else {
            return redirect('recipe');
        }
    }

    public function makeDeactive(Request $request, $id) {
        $target = Recipe::find($id);
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('recipe');
        }
        $target->status = '2';
        $target->save();
        Session::flash('success', $target->reference_no . ' ' . __('label.HAS_BEEN_DEACTIVATED_SUCCESSFULLY'));
        if ($request->type == 'finalized') {
            return redirect('recipe/finalized');
        } else {
            return redirect('recipe');
        }
    }

    public function showDeactivate(Request $request) {
        $target = Recipe::select('id')->where('id', $request->recipe_id)->first();
        $view = view('recipe.showDeactivate', compact('target'))->render();
        return response()->json(['html' => $view]);
    }

    public function showActivate(Request $request) {
        $target = Recipe::select('id')->where('id', $request->recipe_id)->first();
        $view = view('recipe.showActivate', compact('target'))->render();
        return response()->json(['html' => $view]);
    }

    public function activate(Request $request) {
        $statusInfo = '1';
        $activationMessage = __('label.RECIPE_HAS_BEEN_SUCCESSFULLY_ACTIVATED');
        return Common::activateOrDeactive($request, $statusInfo, $activationMessage);
    }

    public function deactivate(Request $request) {
        $statusInfo = '2';
        $activationMessage = __('label.RECIPE_HAS_BEEN_SUCCESSFULLY_DEACTIVATED');
        return Common::activateOrDeactive($request, $statusInfo, $activationMessage);
    }
    
    public function showHistory(Request $request) {
        return Common::showHistory($request);
    }

    public function recipeFinalize(Request $request) {
        //get previous recipe related data include this query
        $prevRecipeInfo = Recipe::find($request->recipe_id);
        $prevRecipetoProcessArr = RecipeToProcess::where('recipe_id', $request->recipe_id)->get();
        $prevRecipetoProductArr = RecipeToProduct::join('recipe_to_process', 'recipe_to_process.id', '=', 'recipe_to_product.rtp_id')
                ->select('recipe_to_process.recipe_id', 'recipe_to_process.process_id', 'recipe_to_product.rtp_id'
                        , 'recipe_to_product.product_id', 'recipe_to_product.formula', 'recipe_to_product.qty', 'recipe_to_product.total_qty')
                ->where('recipe_to_process.recipe_id', $request->recipe_id)
                ->orderBy('recipe_to_process.recipe_id', 'asc')
                ->orderBy('recipe_to_process.process_id', 'asc')
                ->orderBy('recipe_to_product.rtp_id', 'asc')
                ->orderBy('recipe_to_product.product_id', 'asc')
                ->get();


        $prevRecipetoWashProecessArr = WashTypeToProcess::where('recipe_id', $request->recipe_id)->pluck('process_id', 'wash_type_id')->toArray();
        //echo '<pre>';print_r($prevRecipetoWashProecessArr);exit;
        $prevItemSorted = [];
        foreach ($prevRecipetoProductArr as $prevRPItem) {
            $prevItemSorted[$prevRPItem->rtp_id][] = $prevRPItem;
        }
        //insert data to recipe master table after press finalize button
        $finalizedRecipe = new Recipe;
        $finalizedRecipe->shade_id = $prevRecipeInfo->shade_id;
        $finalizedRecipe->reference_no = $this->getFactoryFinalizeCode($prevRecipeInfo->factory_id);
        $finalizedRecipe->date = date('Y-m-d');
        $finalizedRecipe->factory_id = $prevRecipeInfo->factory_id;
        $finalizedRecipe->buyer_id = $prevRecipeInfo->buyer_id;
        $finalizedRecipe->wash_id = $prevRecipeInfo->wash_id;
        $finalizedRecipe->season_id = $prevRecipeInfo->season_id;
        $finalizedRecipe->color_id = $prevRecipeInfo->color_id;
        $finalizedRecipe->machine_model_id = $prevRecipeInfo->machine_model_id;
        $finalizedRecipe->dryer_machine_id = $prevRecipeInfo->dryer_machine_id;
        $finalizedRecipe->wash_lot_quantity_weight = $prevRecipeInfo->wash_lot_quantity_weight;
        $finalizedRecipe->wash_lot_quantity_piece = $prevRecipeInfo->wash_lot_quantity_piece;
        $finalizedRecipe->weight_one_piece = $prevRecipeInfo->weight_one_piece;
        $finalizedRecipe->style_id = $prevRecipeInfo->style_id;
        $finalizedRecipe->dry_process_info = $prevRecipeInfo->dry_process_info;
        $finalizedRecipe->order_no = $prevRecipeInfo->order_no;
        $finalizedRecipe->garments_type_id = $prevRecipeInfo->garments_type_id;
        $finalizedRecipe->supplier_id = $prevRecipeInfo->supplier_id;
        $finalizedRecipe->fabric_ref = $prevRecipeInfo->fabric_ref;
        $finalizedRecipe->dryer_type = $prevRecipeInfo->dryer_type;
        $finalizedRecipe->dryer_load_qty = $prevRecipeInfo->dryer_load_qty;
        $finalizedRecipe->drying_temperature = $prevRecipeInfo->drying_temperature;
        $finalizedRecipe->drying_time = $prevRecipeInfo->drying_time;
        $finalizedRecipe->dryer_type_id = $prevRecipeInfo->dryer_type_id;
        $finalizedRecipe->status = '1';
        $finalizedRecipe->approval_status = '2';
        $finalizedRecipe->act_deact_cause = $prevRecipeInfo->act_deact_cause;
        $finalizedRecipe->wash_type_to_water = $prevRecipeInfo->wash_type_to_water;
        $finalizedRecipe->created_at = date('Y-m-d h:i:s');
        $finalizedRecipe->created_by = Auth::user()->id;
        $finalizedRecipe->save();
        //update query for origin_id update in recipe table
        //Recipe::where('id', $finalizedRecipe->id)->update(['origin_id' => $finalizedRecipe->id]);
        $finalizedRecipeToProductArr = $washTypeToProcess = [];
        $i = $j = 0;
        //insert data to recipe to process, recipe to product table after press finalize button
        foreach ($prevRecipetoProcessArr as $item) {
            $finalizedRecipeToProcess = new RecipeToProcess;
            $finalizedRecipeToProcess->recipe_id = $finalizedRecipe->id;
            $finalizedRecipeToProcess->identifier = $item->identifier;
            $finalizedRecipeToProcess->process_id = $item->process_id;
            $finalizedRecipeToProcess->dry_chemical = isset($item->dry_chemical) ? $item->dry_chemical : null;
            $finalizedRecipeToProcess->water = isset($item->water) ? $item->water : null;
            $finalizedRecipeToProcess->water_ratio = isset($item->water_ratio) ? $item->water_ratio : null;
            $finalizedRecipeToProcess->ph = isset($item->ph) ? $item->ph : null;
            $finalizedRecipeToProcess->temperature = $item->temperature;
            $finalizedRecipeToProcess->time = $item->time;
            $finalizedRecipeToProcess->remarks = isset($item->remarks) ? $item->remarks : null;
            $finalizedRecipeToProcess->save();
            $processInfo = Process::where('id', $finalizedRecipeToProcess->process_id)->first();
            if ($processInfo->process_type_id == '1' && $processInfo->water != '1') {
                foreach ($prevItemSorted[$item->id] as $productItem) {
                    $finalizedRecipeToProductArr[$i]['rtp_id'] = $finalizedRecipeToProcess->id;
                    $finalizedRecipeToProductArr[$i]['product_id'] = $productItem->product_id;
                    $finalizedRecipeToProductArr[$i]['formula'] = $productItem->formula;
                    $finalizedRecipeToProductArr[$i]['qty'] = $productItem->qty;
                    $finalizedRecipeToProductArr[$i]['total_qty'] = $productItem->total_qty;
                    $i++;
                }
            }
        }

        //insert data recipe to product for finalize recipe
        RecipeToProduct::insert($finalizedRecipeToProductArr);
        if (!empty($prevRecipetoWashProecessArr)) {
            foreach ($prevRecipetoWashProecessArr as $washTypeId => $process) {
                $finalizedWashToProcessArr = new WashTypeToProcess;
                $finalizedWashToProcessArr->recipe_id = $finalizedRecipe->id;
                $finalizedWashToProcessArr->wash_type_id = $washTypeId;
                $finalizedWashToProcessArr->process_id = $process;
                $finalizedWashToProcessArr->save();
            }
        }
        return Response::json(['success' => true, 'heading' => 'Success', 'message' => $prevRecipeInfo->reference_no . ' ' . __('label.HAS_FINALIZED_SUCCESSFULLY')], 200);
    }

}
