<?php

namespace App\Http\Controllers;

use Validator;
use App\DryerCategory;
use Session;
use Redirect;
use Illuminate\Http\Request;

class DryerCategoryController extends Controller {

    private $controller = 'DryerCategory';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = DryerCategory::orderBy('name', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = DryerCategory::select('name')->orderBy('name','asc')->get();
        $status = array('' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '0' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if($request->status != ''){
            $targetArr = $targetArr->where('dryer_category.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
         if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('dryerCategory?page=' . $page);
        }

        return view('dryerCategory.index')->with(compact('targetArr', 'qpArr','nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('dryerCategory.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:dryer_category',
        ]);

        if ($validator->fails()) {
            return redirect('dryerCategory/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new DryerCategory;
        $target->name = $request->name;
        $target->status = ($request->status == '1') ? '1' : '0';
        if ($target->save()) {
            Session::flash('success', __('label.DRYER_CATEGORY_CREATED_SUCCESSFULLY'));
            return redirect('dryerCategory');
        } else {
            Session::flash('error', __('label.DRYER_CATEGORY_COULD_NOT_BE_CREATED'));
            return redirect('dryerCategory/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = DryerCategory::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('dryerCategory');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('dryerCategory.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = DryerCategory::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:dryer_category,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('dryerCategory/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->status = ($request->status == '1') ? '1' : '0';
        
        if ($target->save()) {
            Session::flash('success', __('label.DRYER_CATEGORY_UPDATED_SUCCESSFULLY'));
            return redirect('dryerCategory' . $pageNumber);
        } else {
            Session::flash('error', __('label.DRYER_CATEGORY_COULD_NOT_BE_UPDATED'));
            return redirect('dryerCategory/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = DryerCategory::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'DryerType' => ['1' => 'dryer_category_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('dryerCategory' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.DRYER_CATEGORY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.DRYER_CATEGORY_COULD_NOT_BE_DELETED'));
        }
        return redirect('dryerCategory' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('dryerCategory?' . $url);
    }

}