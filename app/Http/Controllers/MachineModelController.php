<?php

namespace App\Http\Controllers;

use Validator;
use App\MachineModel;
use App\DryerType;
use Session;
use Redirect;
use Illuminate\Http\Request;

class MachineModelController extends Controller {

    private $controller = 'MachineModel';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = MachineModel::select('machine_model.*')->orderBy('name', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = MachineModel::select('name')->orderBy('name','asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('machine_model.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('machine_model.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/machineModel?page=' . $page);
        }

        return view('machineModel.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $dryerTypeArr = ['0'=> __('label.SELECT_DRYER_TYPE_OPT')] + DryerType::pluck('name', 'id')->toArray();
        return view('machineModel.create')->with(compact('qpArr', 'dryerTypeArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:machine_model',
                    'rpm' => 'required',
                    'type' => 'required',
                    'capacity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect('machineModel/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new MachineModel;
        $target->name = $request->name;
        $target->rpm = $request->rpm;
        $target->category = $request->category;
        $target->type = $request->type;
        $target->capacity = $request->capacity;
        $target->brand_name = $request->brand_name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.WASHING_MACHINE_TYPE_CREATED_SUCCESSFULLY'));
            return redirect('machineModel');
        } else {
            Session::flash('error', __('label.WASHING_MACHINE_TYPE_COULD_NOT_BE_CREATED'));
            return redirect('machineModel/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = MachineModel::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('machineModel');
        }
        
        //passing param for custom function
        $qpArr = $request->all();
        return view('machineModel.edit')->with(compact('target', 'qpArr', 'dryerTypeArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = MachineModel::find($id);
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; 
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:machine_model,name,' . $id,
                    'rpm' => 'required',
                    'type' => 'required',
                    'capacity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect('machineModel/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->rpm = $request->rpm;
        $target->category = $request->category;
        $target->type = $request->type;
        $target->capacity = $request->capacity;
        $target->brand_name = $request->brand_name;
        $target->status = $request->status;
        
        if ($target->save()) {
            Session::flash('success', __('label.WASHING_MACHINE_TYPE_UPDATED_SUCCESSFULLY'));
            return redirect('machineModel' . $pageNumber);
        } else {
            Session::flash('error', __('label.WASHING_MACHINE_TYPE_COULD_NOT_BE_UPDATED'));
            return redirect('machineModel/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = MachineModel::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //dependency
        $dependencyArr = [
            'Machine' => ['1' => 'washing_machine_type_id'],
            'Recipe' =>  ['1' => 'machine_model_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('machineModel' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.WASHING_MACHINE_TYPE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.WASHING_MACHINE_TYPE_COULD_NOT_BE_DELETED'));
        }
        return redirect('machineModel' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('machineModel?' . $url);
    }
}