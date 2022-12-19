<?php

namespace App\Http\Controllers;

use Validator;
use App\HazardCategory;
use Session;
use Redirect;
use Illuminate\Http\Request;

class HazardCategoryController extends Controller {

    private $controller = 'HazardCategory';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = HazardCategory::orderBy('id', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = HazardCategory::select('name')->orderBy('name','asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/hazardCategory?page=' . $page);
        }

        return view('hazardCategory.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('hazardCategory.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update
        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:hazard_category',
        ]);

        if ($validator->fails()) {
            return redirect('hazardCategory/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new HazardCategory;
        $target->name = $request->name;

        if ($target->save()) {
            Session::flash('success', __('label.HAZARD_CATEGORY_CREATED_SUCCESSFULLY'));
            return redirect('hazardCategory');
        } else {
            Session::flash('error', __('label.HAZARD_CATEGORY_COULD_NOT_BE_CREATED'));
            return redirect('hazardCategory/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = HazardCategory::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('hazardCategory');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('hazardCategory.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = HazardCategory::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update
        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:hazard_category,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('hazardCategory/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;

        if ($target->save()) {
            Session::flash('success', __('label.HAZARD_CATEGORY_UPDATED_SUCCESSFULLY'));
            return redirect('hazardCategory' . $pageNumber);
        } else {
            Session::flash('error', __('label.HAZARD_CATEGORY_COULD_NOT_BE_UPDATED'));
            return redirect('hazardCategory/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = HazardCategory::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //Dependency
        $dependencyArr = [
            'HazardClass' => ['1' => 'hazard_cat_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('hazardCategory' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.HAZARD_CATEGORY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.HAZARD_CATEGORY_COULD_NOT_BE_DELETED'));
        }
        return redirect('hazardCategory' . $pageNumber);
    }
    
    public function filter(Request $request) {
        $url = 'search=' . $request->search;
        return Redirect::to('hazardCategory?' . $url);
    }
}
