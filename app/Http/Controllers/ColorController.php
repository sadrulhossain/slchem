<?php

namespace App\Http\Controllers;

use Validator;
use App\Color;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class ColorController extends Controller {

    private $controller = 'Color';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Color::select('color.*')->orderBy('name', 'asc');

        //begin filtering
        
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        $nameArr = Color::select('name')->orderBy('name','asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('color.status' , '=' ,$request->status);
        }
        
        
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/color?page=' . $page);
        }

        return view('color.index')->with(compact('targetArr', 'qpArr', 'status','nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('color.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:color'
        ]);

        if ($validator->fails()) {
            return redirect('color/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Color;
        $target->name = $request->name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.COLOR_CREATED_SUCCESSFULLY'));
            return redirect('color');
        } else {
            Session::flash('error', __('label.COLOR_COULD_NOT_BE_CREATED'));
            return redirect('color/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Color::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('color');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('color.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = Color::find($id);
        $presentOrder = $target->order;
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:color,name,' . $id
        ]);

        if ($validator->fails()) {
            return redirect('color/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->status = $request->status;
        
        if ($target->save()) {
            Session::flash('success', __('label.COLOR_UPDATED_SUCCESSFULLY'));
            return redirect('color' . $pageNumber);
        } else {
            Session::flash('error', __('label.COLOR_COULD_NOT_BE_UPDATED'));
            return redirect('color/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Color::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //Dependency
        $dependencyArr = [
            'Recipe' => ['1' => 'color_id'],
			'BatchRecipe' => ['1' => 'color_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('color' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.COLOR_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.COLOR_COULD_NOT_BE_DELETED'));
        }
        return redirect('color' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('color?' . $url);
    }

}