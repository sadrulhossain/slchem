<?php

namespace App\Http\Controllers;

use Validator;
use App\SecondaryUnit;
use Session;
use Redirect;
use Illuminate\Http\Request;

class SecondaryUnitController extends Controller {

    private $controller = 'SecondaryUnit';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = SecondaryUnit::select('secondary_unit.*')->orderBy('name', 'asc');
         $nameArr = SecondaryUnit::select('name')->orderBy('name','asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('secondary_unit.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/secondaryUnit?page=' . $page);
        }

        return view('secondaryUnit.index')->with(compact('targetArr', 'qpArr','status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('secondaryUnit.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:secondary_unit',
        ]);

        if ($validator->fails()) {
            return redirect('secondaryUnit/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new SecondaryUnit;
        $target->name = $request->name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.SECONDARY_UNIT_CREATED_SUCCESSFULLY'));
            return redirect('secondaryUnit');
        } else {
            Session::flash('error', __('label.SECONDARY_UNIT_COULD_NOT_BE_CREATED'));
            return redirect('secondaryUnit/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = SecondaryUnit::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('secondaryUnit');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('secondaryUnit.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = SecondaryUnit::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:secondary_unit,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('secondaryUnit/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->status = $request->status;
        
        if ($target->save()) {
            Session::flash('success', __('label.SECONDARY_UNIT_UPDATED_SUCCESSFULLY'));
            return redirect('secondaryUnit' . $pageNumber);
        } else {
            Session::flash('error', __('label.SECONDARY_UNIT_COULD_NOT_BE_UPDATED'));
            return redirect('secondaryUnit/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = SecondaryUnit::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'Product' => ['1' => 'secondary_unit_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return redirect('secondaryUnit' . $pageNumber);
                }
            }
        }
        
        if ($target->delete()) {
            Session::flash('error', __('label.SECONDARY_UNIT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.SECONDARY_UNIT_COULD_NOT_BE_DELETED'));
        }
        return redirect('secondaryUnit' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('secondaryUnit?' . $url);
    }

}