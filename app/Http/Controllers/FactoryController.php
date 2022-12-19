<?php

namespace App\Http\Controllers;

use Validator;
use App\Factory;
use Session;
use Redirect;
use Illuminate\Http\Request;

class FactoryController extends Controller {

    private $controller = 'Factory';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Factory::select('factory.*')->orderBy('name', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Factory::select('name')->orderBy('name', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {

                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('factory.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/factory?page=' . $page);
        }

        return view('factory.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('factory.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:factory',
                    'code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('factory/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Factory;
        $target->name = $request->name;
        $target->code = $request->code;
        $target->description = $request->description;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.FACTORY_CREATED_SUCCESSFULLY'));
            return redirect('factory');
        } else {
            Session::flash('error', __('label.FACTORY_COULD_NOT_BE_CREATED'));
            return redirect('factory/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Factory::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('factory');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('factory.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Factory::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:factory,name,' . $id,
                    'code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('factory/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->code = $request->code;
        $target->description = $request->description;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.FACTORY_UPDATED_SUCCESSFULLY'));
            return redirect('factory' . $pageNumber);
        } else {
            Session::flash('error', __('label.FACTORY_COULD_NOT_BE_UPDATED'));
            return redirect('factory/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Factory::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency
        $dependencyArr = [
            'Recipe' => ['1' => 'factory_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return redirect('factory' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.FACTORY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.FACTORY_COULD_NOT_BE_DELETED'));
        }
        return redirect('factory' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('factory?' . $url);
    }

}
