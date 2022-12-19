<?php

namespace App\Http\Controllers;

use Validator;
use App\Machine;
use App\MachineModel;
use Session;
use Redirect;
use Illuminate\Http\Request;

class MachineController extends Controller {

    private $controller = 'Machine';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Machine::with('MachineModel')->orderBy('machine_no', 'asc');

        //begin filtering
        $searchText = $request->search;
        $machineNoArr = Machine::select('machine_no')->get();
        $machineModelArr = ['0' => __('label.SELECT_WASHING_MACHINE_TYPE_OPT')] + MachineModel::pluck('name','id')->toArray();
        $status = array('' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '0' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {

                $query->where('machine_no', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->machine_model)){
            $targetArr = $targetArr->where('machine.washing_machine_type_id' , '=' ,$request->machine_model);
        }
        if($request->status != ''){
            $targetArr = $targetArr->where('machine.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('machine?page=' . $page);
        }

        return view('machine.index')->with(compact('targetArr', 'qpArr', 'machineModelArr', 'machineNoArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $machineModelArr = [0 => __('label.SELECT_WASHING_MACHINE_TYPE_OPT')] + MachineModel::pluck('name', 'id')->toArray();
        return view('machine.create')->with(compact('qpArr', 'machineModelArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'washing_machine_type_id' => 'required|not_in:0',
                    'machine_no' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('machine/create' . $pageNumber)
                            ->withInput()->withErrors($validator);
        }

        $target = new Machine;
        $target->washing_machine_type_id = $request->washing_machine_type_id;
        $target->machine_no = $request->machine_no;
        $target->description = $request->description;
        $target->status = ($request->status == '1') ? '1' : '0';

        if ($target->save()) {
            Session::flash('success', __('label.WASHING_MACHINE_CREATED_SUCCESSFULLY'));
            return redirect('machine');
        } else {
            Session::flash('error', __('label.WASHING_MACHINE_COULD_NOT_BE_CREATED'));
            return redirect('machine/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Machine::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('machine');
        }
        
        $machineModelArr = MachineModel::pluck('name', 'id')->toArray();
        
        //passing param for custom function
        $qpArr = $request->all();
        return view('machine.edit')->with(compact('target', 'qpArr', 'machineModelArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Machine::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'washing_machine_type_id' => 'required|not_in:0',
                    'machine_no' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('machine/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }
        
        $target->washing_machine_type_id = $request->washing_machine_type_id;
        $target->machine_no = $request->machine_no;
        $target->description = $request->description;
        $target->status = ($request->status == '1') ? '1' : '0';

        if ($target->save()) {
            Session::flash('success', __('label.WASHING_MACHINE_UPDATED_SUCCESSFULLY'));
            return redirect('machine' . $pageNumber);
        } else {
            Session::flash('error', __('label.WASHING_MACHINE_COULD_NOT_BE_UPDATED'));
            return redirect('machine/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Machine::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        $dependencyArr = [
            'BatchCard' => ['1' => 'machine_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('machine' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.WASHING_MACHINE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.WASHING_MACHINE_COULD_NOT_BE_DELETED'));
        }
        return redirect('machine' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&machine_model=' . $request->machine_model . '&status=' . $request->status;
        return Redirect::to('machine?' . $url);
    }

}
