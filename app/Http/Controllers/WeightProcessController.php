<?php

namespace App\Http\Controllers;

use Validator;
use App\WeightProcess;
use Auth;
use Session;
use Redirect;
use Illuminate\Http\Request;

class WeightProcessController extends Controller {

    private $controller = 'WeightProcess';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = WeightProcess::select('weight_process.*')->orderBy('name', 'asc');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering

        $targetArr = $targetArr->paginate(__('label.PAGINATION_COUNT'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/weightProcess?page=' . $page);
        }

        return view('weightProcess.index')->with(compact('targetArr', 'qpArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('weightProcess.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:weight_process'
        ]);

        if ($validator->fails()) {
            return redirect('weightProcess/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new WeightProcess;
        $target->name = $request->name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.WEIGHT_PROCESS_CREATED_SUCCESSFULLY'));
            return redirect('weightProcess');
        } else {
            Session::flash('error', __('label.WEIGHT_PROCESS_COULD_NOT_BE_CREATED'));
            return redirect('weightProcess/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = WeightProcess::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('weightProcess');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('weightProcess.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = WeightProcess::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:wash,name,' . $id
        ]);

        if ($validator->fails()) {
            return redirect('weightProcess/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->status = $request->status;
        
        if ($target->save()) {
            Session::flash('success', __('label.WEIGHT_PROCESS_UPDATED_SUCCESSFULLY'));
            return redirect('weightProcess' . $pageNumber);
        } else {
            Session::flash('error', __('label.WEIGHT_PROCESS_COULD_NOT_BE_UPDATED'));
            return redirect('weightProcess/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = WeightProcess::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Session::flash('error', __('label.WEIGHT_PROCESS_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.WEIGHT_PROCESS_COULD_NOT_BE_DELETED'));
        }
        return redirect('weightProcess' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search;
        return Redirect::to('weightProcess?' . $url);
    }

}