<?php

namespace App\Http\Controllers;

use Validator;
use App\HydroMachine;
use Session;
use Redirect;
use Illuminate\Http\Request;

class HydroMachineController extends Controller {

    private $controller = 'HydroMachine';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = HydroMachine::orderBy('machine_no', 'asc');

        //begin filtering
        $searchText = $request->search;
        $machineNoArr = HydroMachine::select('machine_no')->get();
        $status = array('' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '0' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('machine_no', 'LIKE', '%' . $searchText . '%');
            });
        }
        if($request->status != ''){
            $targetArr = $targetArr->where('hydro_machine.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
         if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('hydroMachine?page=' . $page);
        }

        return view('hydroMachine.index')->with(compact('targetArr', 'qpArr', 'machineNoArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('hydroMachine.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'machine_no' => 'required|unique:hydro_machine',
        ]);

        if ($validator->fails()) {
            return redirect('hydroMachine/create' . $pageNumber)
                            ->withInput()->withErrors($validator);
        }

        $target = new HydroMachine;
        $target->machine_no = $request->machine_no;
        $target->description = $request->description;
        $target->status = ($request->status == '1') ? '1' : '0';

        if ($target->save()) {
            Session::flash('success', __('label.HYDRO_MACHINE_CREATED_SUCCESSFULLY'));
            return redirect('hydroMachine');
        } else {
            Session::flash('error', __('label.HYDRO_MACHINE_COULD_NOT_BE_CREATED'));
            return redirect('hydroMachine/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = HydroMachine::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('hydroMachine');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('hydroMachine.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = HydroMachine::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'machine_no' => 'required|unique:hydro_machine,machine_no,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('hydroMachine/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->machine_no = $request->machine_no;
        $target->description = $request->description;
        $target->status = ($request->status == '1') ? '1' : '0';
        
        if ($target->save()) {
            Session::flash('success', __('label.HYDRO_MACHINE_UPDATED_SUCCESSFULLY'));
            return redirect('hydroMachine' . $pageNumber);
        } else {
            Session::flash('error', __('label.HYDRO_MACHINE_COULD_NOT_BE_UPDATED'));
            return redirect('hydroMachine/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = HydroMachine::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency
        $dependencyArr = [
            'BatchCard' => ['1' => 'hydro_machine_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('hydroMachine' . $pageNumber);
                }
            }
        }
        
        if ($target->delete()) {
            Session::flash('error', __('label.HYDRO_MACHINE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.HYDRO_MACHINE_COULD_NOT_BE_DELETED'));
        }
        return redirect('hydroMachine' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('hydroMachine?' . $url);
    }

}