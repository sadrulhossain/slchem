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

class FinalizedRecipeController extends Controller {

    private $controller = 'FinalizedRecipe';
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
                                , 'shade.name as shade', 'season.name as season'
                                , 'color.name as color')
                        ->where('recipe.approval_status', '2')->orderBy('recipe.id', 'desc');
        //begin filtering
        $searchText = $request->search;
        $recipeArr = Recipe::select('reference_no')->where('status', '2')->get();
        $factoryArr = ['0' => __('label.SELECT_FACTORY_OPT')] + Factory::pluck('name', 'id')->toArray();
        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $styleArr = ['0' => __('label.SELECT_STYLE_OPT')] + Style::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $shadeArr = ['0' => __('label.SELECT_SHADE_OPT')] + Shade::orderBy('order', 'asc')->where('status', 1)->pluck('name', 'id')->toArray();
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
        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        $statusArr = $this->statusArr;
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('finalizedRecipe?page=' . $page);
        }
        return view('recipe.finalized')->with(compact('qpArr', 'targetArr', 'factoryArr', 'buyerArr', 'seasonArr', 'colorArr'
                                , 'machineModelArr', 'recipeArr', 'statusArr', 'styleArr', 'shadeArr'));
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&factory=' . $request->factory . '&buyer=' . $request->buyer
                . '&style_id=' . $request->style . '&season=' . $request->season . '&color=' . $request->color
                . '&washing_machine_type=' . $request->washing_machine_type . '&date=' . $request->date
                . '&shade_id=' . $request->shade_id. '&season_id=' . $request->season_id . '&color_id=' . $request->color_id;
        $redirectTo = 'finalizedRecipe';
        return Redirect::to($redirectTo . '?' . $url);
    }

    public function generateVersionNo($versionNo = null, $clonedParentId = null) {
        $recipeCount = Recipe::select(DB::raw('COUNT(*) as total'))->where('parent_id', $clonedParentId)->first();
        $clonedVersion = $versionNo . '.' . (($recipeCount->total) + 1);
        return $clonedVersion;
    }

    public function showDeactivate(Request $request) {
        $target = Recipe::select('id')->where('id', $request->recipe_id)->first();
        $view = view('recipe.showFinalizedDeactivate', compact('target'))->render();
        return response()->json(['html' => $view]);
    }

    public function showActivate(Request $request) {
        $target = Recipe::select('id')->where('id', $request->recipe_id)->first();
        $view = view('recipe.showFinalizedActivate', compact('target'))->render();
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

   public function getRecipeDetails(Request $request, $id = null) {
        $detailsView = 'finalizedDetails';
        return Common::getRecipeDetails($request, $id, 45, $detailsView);
    }

    public function showHistory(Request $request) {
        return Common::showHistory($request);
    }

}
