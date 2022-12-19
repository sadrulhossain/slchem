<?php

namespace App\Http\Controllers;

use Validator;
use App\DryerMachine;
use App\DryerType;
use Session;
use Redirect;
use Illuminate\Http\Request;

class DryerMachineController extends Controller {

    private $controller = 'DryerMachine';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = DryerMachine::with('DryerType')->orderBy('machine_no', 'asc');

        //begin filtering
        $searchText = $request->search;
        $machineNoArr = DryerMachine::select('machine_no')->get();
        $dryerTypeArr = [0 => __('label.SELECT_DRYER_TYPE_OPT')] + DryerType::pluck('name', 'id')->toArray();
        $status = array('' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '0' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('machine_no', 'LIKE', '%' . $searchText . '%');
            });
        }

        if(!empty($request->dryer_type_id)){
            $targetArr = $targetArr->where('dryer_machine.dryer_type_id' , '=' ,$request->dryer_type_id);
        }
        if($request->status != ''){
            $targetArr = $targetArr->where('dryer_machine.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
         if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('dryerMachine?page=' . $page);
        }

        return view('dryerMachine.index')->with(compact('targetArr', 'qpArr', 'machineNoArr', 'status','dryerTypeArr'));
        
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $dryerTypeArr = [0 => __('label.SELECT_DRYER_TYPE_OPT')] + DryerType::pluck('name', 'id')->toArray();
        return view('dryerMachine.create')->with(compact('qpArr','dryerTypeArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'dryer_type_id' => 'required|not_in:0',
                    'machine_no' => 'required|unique:dryer_machine',
        ]);

        if ($validator->fails()) {
            return redirect('dryerMachine/create' . $pageNumber)
                            ->withInput()->withErrors($validator);
        }

        $target = new DryerMachine;
        $target->dryer_type_id = $request->dryer_type_id;
        $target->machine_no = $request->machine_no;
        $target->description = $request->description;
        $target->status = ($request->status == '1') ? '1' : '0';

        if ($target->save()) {
            Session::flash('success', __('label.DRYER_MACHINE_CREATED_SUCCESSFULLY'));
            return redirect('dryerMachine');
        } else {
            Session::flash('error', __('label.DRYER_MACHINE_COULD_NOT_BE_CREATED'));
            return redirect('dryerMachine/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = DryerMachine::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('dryerMachine');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $dryerTypeArr = [0 => __('label.SELECT_DRYER_TYPE_OPT')] + DryerType::pluck('name', 'id')->toArray();

        return view('dryerMachine.edit')->with(compact('target', 'qpArr','dryerTypeArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = DryerMachine::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'dryer_type_id' => 'required|not_in:0',
                    'machine_no' => 'required|unique:dryer_machine,machine_no,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('dryerMachine/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->dryer_type_id = $request->dryer_type_id;
        $target->machine_no = $request->machine_no;
        $target->description = $request->description;
        $target->status = ($request->status == '1') ? '1' : '0';
        
        if ($target->save()) {
            Session::flash('success', __('label.DRYER_MACHINE_UPDATED_SUCCESSFULLY'));
            return redirect('dryerMachine' . $pageNumber);
        } else {
            Session::flash('error', __('label.DRYER_MACHINE_COULD_NOT_BE_UPDATED'));
            return redirect('dryerMachine/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = DryerMachine::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //dependency
        $dependencyArr = [
            'BatchCard' => ['1' => 'dryer_machine_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('dryerMachine' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.DRYER_MACHINE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.DRYER_MACHINE_COULD_NOT_BE_DELETED'));
        }
        return redirect('dryerMachine' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search. '&dryer_type_id=' . $request->dryer_type_id. '&status=' . $request->status;
        return Redirect::to('dryerMachine?' . $url);
    }

}