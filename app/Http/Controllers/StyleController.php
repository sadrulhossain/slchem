<?php

namespace App\Http\Controllers;

use Validator;
use App\Style;
use Session;
use Redirect;
use Illuminate\Http\Request;

class StyleController extends Controller {

    private $controller = 'Style';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Style::select('style.*')->orderBy('name', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Style::select('name')->orderBy('name','asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('style.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
         if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/style?page=' . $page);
        }

        return view('style.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('style.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:style',
        ]);

        if ($validator->fails()) {
            return redirect('style/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Style;
        $target->name = $request->name;
        $target->description = $request->description;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.STYLE_CREATED_SUCCESSFULLY'));
            return redirect('style');
        } else {
            Session::flash('error', __('label.STYLE_COULD_NOT_BE_CREATED'));
            return redirect('style/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Style::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('style');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('style.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Style::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:style,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('style/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->description = $request->description;
        $target->status = $request->status;
        
        if ($target->save()) {
            Session::flash('success', __('label.STYLE_UPDATED_SUCCESSFULLY'));
            return redirect('style' . $pageNumber);
        } else {
            Session::flash('error', __('label.STYLE_COULD_NOT_BE_UPDATED'));
            return redirect('style/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Style::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency
        $dependencyArr = [
            'Recipe' => ['1' => 'style_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('style' . $pageNumber);
                }
            }
        }
        
        if ($target->delete()) {
            Session::flash('error', __('label.STYLE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.STYLE_COULD_NOT_BE_DELETED'));
        }
        return redirect('style' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('style?' . $url);
    }

}