<?php

namespace App\Http\Controllers;

use Validator;
use App\BatchCard;
use App\Recipe;
use App\Machine;
use App\Factory;
use App\Buyer;
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
use App\WashTypeToProcess;
use App\Season;
use App\Color;
use App\Style;
use App\Shift;
use App\WashType;
use Auth;
use Session;
use Redirect;
use Common;
use Response;
use DB;
use Helper;
use Illuminate\Http\Request;

class BatchCardReportController extends Controller {

    private $controller = 'BatchCardReport';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();
        $shiftArr = ['0' => __('label.SELECT_SHIFT_OPT')] + Shift::orderBy('id', 'asc')->pluck('name', 'id')->toArray();
        $washTypeArr = ['0' => __('label.SELECT_WASH_TYPE_OPT')] + WashType::orderBy('id', 'asc')->pluck('name', 'id')->toArray();

        $recipeArr = ['0' => __('label.SELECT_RECIPE_OPT')] + Recipe::where('approval_status', '2')->where('status', '1')->pluck('reference_no', 'id')->toArray();
        $styleArr = ['0' => __('label.SELECT_STYLE_OPT')] + Style::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $machineArr = ['0' => __('label.SELECT_MACHINE_OPT')] + Machine::pluck('machine_no', 'id')->toArray();
        $factoryArr = ['0' => __('label.SELECT_FACTORY_OPT')] + Factory::pluck('code', 'id')->toArray();
        $buyerArr = ['0' => __('label.SELECT_BUYER_OPT')] + Buyer::pluck('name', 'id')->toArray();
        $opreratorArr = BatchCard::select('operator_name')->get();
        $seasonArr = ['0' => __('label.SELECT_SEASON_OPT')] + Season::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $colorArr = ['0' => __('label.SELECT_COLOR_OPT')] + Color::where('status', '1')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        if (!empty($qpArr)) {
            $targetArr = BatchCard::join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id')
                    ->join('style', 'style.id', '=', 'batch_recipe.style_id')
                    ->join('season', 'season.id', '=', 'batch_recipe.season_id')
                    ->join('color', 'color.id', '=', 'batch_recipe.color_id');


            //begin filtering
            $searchText = $request->search;
            $operatorName = $request->operator_name;


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

            if (!empty($request->buyer_id)) {
                $targetArr = $targetArr->where('batch_recipe.buyer_id', '=', $request->buyer_id);
            }

            if (!empty($request->factory_id)) {
                $targetArr = $targetArr->where('batch_recipe.factory_id', '=', $request->factory_id);
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

            if (!empty($request->color_id)) {
                $targetArr = $targetArr->where('batch_recipe.color_id', '=', $request->color_id);
            }

            if (!empty($request->season_id)) {
                $targetArr = $targetArr->where('batch_recipe.season_id', '=', $request->season_id);
            }
            //end filtering

            $targetArr = $targetArr->select('batch_card.*', 'batch_recipe.reference_no as recipe_reference_no'
                                    , 'batch_recipe.batch_card_id', 'batch_recipe.id as batch_recipe_id'
                                    , 'batch_recipe.wash_lot_quantity_piece', 'style.name as style'
                                    , 'batch_recipe.factory_id', 'batch_recipe.buyer_id'
                                    , 'color.name as color', 'season.name as season')
                            ->orderBy('batch_recipe.id', 'desc')->orderBy('id', 'asc')->paginate(Session::get('paginatorCount'));

            $totalQty = $this->getTotalQty($request->all(), $searchText, $operatorName);

            //change page number after delete if no data has current page
            if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
                $page = ($qpArr['page'] - 1);
                return redirect('batchCardReport?page=' . $page);
            }
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[54][6])) {
                return redirect('dashboard');
            }
            return view('batchCardReport.print.index')->with(compact('request', 'targetArr', 'qpArr', 'recipeArr', 'machineArr', 'opreratorArr', 'userFirstNameArr'
                                    , 'userLastNameArr', 'styleArr', 'shiftArr', 'washTypeArr', 'buyerArr', 'factoryArr', 'totalQty', 'seasonArr', 'colorArr'));
        } else {
            return view('batchCardReport.index')->with(compact('request', 'targetArr', 'qpArr', 'recipeArr', 'machineArr', 'opreratorArr', 'userFirstNameArr'
                                    , 'userLastNameArr', 'styleArr', 'shiftArr', 'washTypeArr', 'buyerArr', 'factoryArr', 'totalQty', 'seasonArr', 'colorArr'));
        }
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&date=' . $request->date . '&recipe=' . $request->recipe
                . '&style_id=' . $request->style . '&season_id=' . $request->season_id . '&color_id=' . $request->color_id
                . '&machine=' . $request->machine . '&shift=' . $request->shift . '&operator_name=' . $request->operator_name
                . '&wash_type_id=' . $request->wash_type_id . '&factory_id=' . $request->factory_id . '&buyer_id=' . $request->buyer_id;
        return Redirect::to('batchCardReport?' . $url);
    }

    private static function getTotalQty($parameters = '', $searchText = '', $operatorName = '') {
        $itemArr = BatchCard::join('batch_recipe', 'batch_recipe.batch_card_id', '=', 'batch_card.id');

        //begin filtering
        if (!empty($searchText)) {
            $itemArr->where(function ($query) use ($searchText) {
                $query->where('batch_card.reference_no', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($parameters['date'])) {
            $itemArr = $itemArr->where('batch_card.date', '=', $parameters['date']);
        }
        if (!empty($parameters['recipe'])) {
            $itemArr = $itemArr->where('batch_card.recipe_id', '=', $parameters['recipe']);
        }

        if (!empty($parameters['style_id'])) {
            $itemArr = $itemArr->where('batch_recipe.style_id', '=', $parameters['style_id']);
        }

        if (!empty($parameters['buyer_id'])) {
            $itemArr = $itemArr->where('batch_recipe.buyer_id', '=', $parameters['buyer_id']);
        }

        if (!empty($parameters['factory_id'])) {
            $itemArr = $itemArr->where('batch_recipe.factory_id', '=', $parameters['factory_id']);
        }

        if (!empty($parameters['wash_type_id'])) {
            $itemArr = $itemArr->where('batch_card.wash_type_id', '=', $parameters['wash_type_id']);
        }

        if (!empty($operatorName)) {
            $itemArr->where(function ($query) use ($operatorName) {
                $query->where('operator_name', 'LIKE', '%' . $operatorName . '%');
            });
        }

        if (!empty($parameters['machine'])) {
            $itemArr = $itemArr->where('batch_card.machine_id', '=', $parameters['machine']);
        }

        if (!empty($parameters['shift'])) {
            $itemArr = $itemArr->where('batch_card.shift_id', '=', $parameters['shift']);
        }

        if (!empty($parameters['color_id'])) {
            $itemArr = $itemArr->where('batch_recipe.color_id', '=', $parameters['color_id']);
        }

        if (!empty($parameters['season_id'])) {
            $itemArr = $itemArr->where('batch_recipe.season_id', '=', $parameters['season_id']);
        }

        //end filtering
        $itemArr = $itemArr->sum('batch_recipe.wash_lot_quantity_piece');
        $sumOfQty = !empty($itemArr) ? Helper::numberformat($itemArr, 0) : 0;

        return $sumOfQty;
    }

    public function loadBatchToken(Request $request) {
        $load = 'batchCard.showTokenNo';
        return Common::loadBatchToken($request, $load);
    }

}
