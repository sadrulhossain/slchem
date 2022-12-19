<?php

namespace App\Http\Controllers;

use Validator;
use App\Wash;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class WashController extends Controller {

    private $controller = 'Wash';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Wash::select('wash.*')->orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Wash::select('name')->orderBy('name','asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('wash.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/wash?page=' . $page);
        }

        return view('wash.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('wash.create')->with(compact('qpArr', 'orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:wash',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('wash/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Wash;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.WASH_CREATED_SUCCESSFULLY'));
            return redirect('wash');
        } else {
            Session::flash('error', __('label.WASH_COULD_NOT_BE_CREATED'));
            return redirect('wash/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Wash::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('wash');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('wash.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) { //echo '<pre>';print_r($request->all());exit;
        $target = Wash::find($id);
        $presentOrder = $target->order;
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:wash,name,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('wash/' . $id . '/edit' . $pageNumber)
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
            Session::flash('success', __('label.WASH_UPDATED_SUCCESSFULLY'));
            return redirect('wash' . $pageNumber);
        } else {
            Session::flash('error', __('label.WASH_COULD_NOT_BE_UPDATED'));
            return redirect('wash/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Wash::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.WASH_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.WASH_COULD_NOT_BE_DELETED'));
        }
        return redirect('wash' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('wash?' . $url);
    }

}