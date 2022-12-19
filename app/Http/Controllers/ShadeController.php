<?php

namespace App\Http\Controllers;

use Validator;
use App\Shade;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class ShadeController extends Controller {

    private $controller = 'Shade';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        
        $nameArr = Shade::select('name')->orderBy('order', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');


        $targetArr = Shade::orderBy('order', 'asc');
        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('shade.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/shade?page=' . $page);
        }

        return view('shade.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }
    

    public function create(Request $request) { 
        //passing param for custom function
        $qpArr = $request->all();
        
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        
        return view('shade.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:shade',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('shade/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Shade;
        $target->name = $request->name;
        $target->description = $request->description;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.SHADE_CREATED_SUCCESSFULLY'));
            return redirect('shade');
        } else {
            Session::flash('error', __('label.SHADE_COULD_NOT_BE_CREATED'));
            return redirect('shade/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Shade::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('shade');
        }

        //passing param for custom function
        $qpArr = $request->all();

        return view('shade.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    
    public function update(Request $request, $id) {

        $target = Shade::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        //end back same page after update
        
        $rules = [];
        $rules = [
            'name' => 'required|unique:shade,name,' . $id,
            'order' => 'required|not_in:0'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('shade/' . $id . '/edit' . $pageNumber)
                            ->withInput()->withErrors($validator);
        }

        $target->name = $request->name;
        $target->description = $request->description;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.SHADE_UPDATED_SUCCESSFULLY'));
            return redirect('shade' . $pageNumber);
        } else {
            Session::flash('error', __('label.SHADE_COULD_NOT_BE_UPDATED'));
            return redirect('shade/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        
        $target = Shade::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency
        $dependencyArr = [
            'Recipe' => ['1' => 'shade_id']
        ];
        
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return redirect('shade' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.SHADE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.SHADE_COULD_NOT_BE_DELETED'));
        }
        return redirect('shade' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('shade?' . $url);
    }

}
