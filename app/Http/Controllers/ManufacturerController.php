<?php

namespace App\Http\Controllers;

use Validator;
use App\Manufacturer;
use App\ManufacturerCertification;
use Session;
use Redirect;
use Illuminate\Http\Request;

class ManufacturerController extends Controller {

    private $controller = 'Manufacturer';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Manufacturer::orderBy('name', 'asc');
        $nameArr = Manufacturer::select('name')->orderBy('name','asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');

        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('manufacturer.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/manufacturer?page=' . $page);
        }

        return view('manufacturer.index')->with(compact('targetArr', 'qpArr','nameArr','status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('manufacturer.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:manufacturer',
        ]);

        if ($validator->fails()) {
            return redirect('manufacturer/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Manufacturer;
        $target->name = $request->name;
        $target->address = $request->address;
        $target->web_address = $request->web_address;
        $target->description = $request->description;
        $target->status = !empty($request->status) ? '1' : '0';

        if ($target->save()) {
            Session::flash('success', __('label.MANUFACTURER_CREATED_SUCCESSFULLY'));
            return redirect('manufacturer');
        } else {
            Session::flash('error', __('label.MANUFACTURER_COULD_NOT_BE_CREATED'));
            return redirect('manufacturer/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Manufacturer::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('manufacturer');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('manufacturer.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Manufacturer::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:manufacturer,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('manufacturer/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }
        $target->name = $request->name;
        $target->address = $request->address;
        $target->web_address = $request->web_address;
        $target->description = $request->description;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.MANUFACTURER_UPDATED_SUCCESSFULLY'));
            return redirect('manufacturer' . $pageNumber);
        } else {
            Session::flash('error', __('label.MANUFACTURER_COULD_NOT_BE_UPDATED'));
            return redirect('manufacturer/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Manufacturer::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        
        //Dependency
        $dependencyArr = [
            'MfAddressBook' => ['1' => 'manufacturer_id'],
            'ProductToManufacturer' => ['1' => 'manufacturer_id'],
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return redirect('manufacturer' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.MANUFACTURER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.MANUFACTURER_COULD_NOT_BE_DELETED'));
        }
        return redirect('manufacturer' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('manufacturer?' . $url);
    }

}
