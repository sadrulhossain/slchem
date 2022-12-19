<?php

namespace App\Http\Controllers;

use Validator;
use App\Season;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class SeasonController extends Controller {

    private $controller = 'Season';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Season::select('season.*')->orderBy('order', 'asc');

        //begin filtering
        
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        $nameArr = Season::select('name')->orderBy('order','asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('season.status' , '=' ,$request->status);
        }
        
        
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/season?page=' . $page);
        }

        return view('season.index')->with(compact('targetArr', 'qpArr', 'status','nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('season.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:season',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('season/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Season;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.SEASON_CREATED_SUCCESSFULLY'));
            return redirect('season');
        } else {
            Session::flash('error', __('label.SEASON_COULD_NOT_BE_CREATED'));
            return redirect('season/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Season::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('season');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('season.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) {
        $target = Season::find($id);
        $presentOrder = $target->order;
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:season,name,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('season/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->order = $request->order;
        $target->status = $request->status;
        
        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.SEASON_UPDATED_SUCCESSFULLY'));
            return redirect('season' . $pageNumber);
        } else {
            Session::flash('error', __('label.SEASON_COULD_NOT_BE_UPDATED'));
            return redirect('season/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Season::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //Dependency
        $dependencyArr = [
            'Recipe' => ['1' => 'season_id'],
			'BatchRecipe' => ['1' => 'season_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('season' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.SEASON_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.SEASON_COULD_NOT_BE_DELETED'));
        }
        return redirect('season' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('season?' . $url);
    }

}