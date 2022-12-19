<?php

namespace App\Http\Controllers;

use App\DryerCategory;
use Validator;
use App\DryerType;
use Session;
use Redirect;
use Illuminate\Http\Request;

class DryerTypeController extends Controller {

    private $controller = 'DryerType';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = DryerType::with('DryerCategory')->orderBy('name', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = DryerType::select('name')->orderBy('name','asc')->get();
        $dryerCategoryArr = [0 => __('label.SELECT_DRYER_CATEGORY_OPT')] + DryerCategory::pluck('name', 'id')->toArray();
        $status = array('' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '0' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->dryer_category_id)){
            $targetArr = $targetArr->where('dryer_type.dryer_category_id' , '=' ,$request->dryer_category_id);
        }
        if($request->status != ''){
            $targetArr = $targetArr->where('dryer_type.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
         if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('dryerType?page=' . $page);
        }

        return view('dryerType.index')->with(compact('targetArr', 'qpArr','nameArr', 'status','dryerCategoryArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $dryerCategoryArr = [0 => __('label.SELECT_DRYER_CATEGORY_OPT')] + DryerCategory::pluck('name', 'id')->toArray();
        return view('dryerType.create')->with(compact('qpArr','dryerCategoryArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'dryer_category_id' => 'required|not_in:0',
                    'name' => 'required|unique:dryer_type',
                    'humidity' => 'required',
                    'capacity' => 'required',
                    'brand_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('dryerType/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new DryerType;
        $target->dryer_category_id = $request->dryer_category_id;
        $target->name = $request->name;
        $target->humidity = $request->humidity;
        $target->capacity = $request->capacity;
        $target->brand_name = $request->brand_name;
        $target->description = $request->description;
        $target->status = ($request->status == '1') ? '1' : '0';

        if ($target->save()) {
            Session::flash('success', __('label.DRYER_TYPE_CREATED_SUCCESSFULLY'));
            return redirect('dryerType');
        } else {
            Session::flash('error', __('label.DRYER_TYPE_COULD_NOT_BE_CREATED'));
            return redirect('dryerType/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = DryerType::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('dryerType');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $dryerCategoryArr = [0 => __('label.SELECT_DRYER_CATEGORY_OPT')] + DryerCategory::pluck('name', 'id')->toArray();

        return view('dryerType.edit')->with(compact('target', 'qpArr','dryerCategoryArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = DryerType::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:dryer_type,name,' . $id,
                    'dryer_category_id' => 'required|not_in:0',
                    'humidity' => 'required',
                    'capacity' => 'required',
                    'brand_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('dryerType/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->dryer_category_id = $request->dryer_category_id;
        $target->name = $request->name;
        $target->humidity = $request->humidity;
        $target->capacity = $request->capacity;
        $target->brand_name = $request->brand_name;
        $target->description = $request->description;
        $target->status = ($request->status == '1') ? '1' : '0';
        
        if ($target->save()) {
            Session::flash('success', __('label.DRYER_TYPE_UPDATED_SUCCESSFULLY'));
            return redirect('dryerType' . $pageNumber);
        } else {
            Session::flash('error', __('label.DRYER_TYPE_COULD_NOT_BE_UPDATED'));
            return redirect('dryerType/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = DryerType::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //Dependency
        $dependencyArr = [
            'BatchCard' => ['1' => 'dryer_type_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('dryerType' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.DRYER_TYPE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.DRYER_TYPE_COULD_NOT_BE_DELETED'));
        }
        return redirect('dryerType' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&dryer_category_id=' . $request->dryer_category_id .'&status=' . $request->status;
        return Redirect::to('dryerType?' . $url);
    }

}