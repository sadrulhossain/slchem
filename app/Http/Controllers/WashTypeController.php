<?php

namespace App\Http\Controllers;

use Validator;
use App\WashType;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class WashTypeController extends Controller {

    private $controller = 'WashType';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = WashType::select('wash_type.*')->orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = WashType::select('name')->orderBy('name','asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('wash_type.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/washType?page=' . $page);
        }

        return view('washType.index')->with(compact('targetArr', 'qpArr','nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('washType.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:wash_type',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('washType/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new WashType;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.WASH_TYPE_CREATED_SUCCESSFULLY'));
            return redirect('washType');
        } else {
            Session::flash('error', __('label.WASH_TYPE_COULD_NOT_BE_CREATED'));
            return redirect('washType/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = WashType::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('washType');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('washType.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = WashType::find($id);
        $presentOrder = $target->order;
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:wash_type,name,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('washType/' . $id . '/edit' . $pageNumber)
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
            Session::flash('success', __('label.WASH_TYPE_UPDATED_SUCCESSFULLY'));
            return redirect('washType' . $pageNumber);
        } else {
            Session::flash('error', __('label.WASH_TYPE_COULD_NOT_BE_UPDATED'));
            return redirect('washType/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = WashType::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
		
		
		//dependency
        $dependencyArr = [
            'WashTypeToProcess' => ['1' => 'wash_type_id'],
            'BatchWashTypeToProcess' => ['1' => 'wash_type_id'],
            'BatchCard' => ['1' => 'wash_type_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return redirect('washType' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.WASH_TYPE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.WASH_TYPE_COULD_NOT_BE_DELETED'));
        }
        return redirect('washType' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('washType?' . $url);
    }

}