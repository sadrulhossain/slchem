<?php

namespace App\Http\Controllers;

use Validator;
use App\SupplierType;
use Session;
use Redirect;
use Illuminate\Http\Request;

class SupplierTypeController extends Controller {

    private $controller = 'SupplierType';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = SupplierType::select('supplier_type.*')->orderBy('name', 'asc');

        //begin filtering
        $nameArr = SupplierType::select('name')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('supplier_type.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/supplierType?page=' . $page);
        }

        return view('supplierType.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('supplierType.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:supplier_type',
        ]);

        if ($validator->fails()) {
            return redirect('supplierType/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new SupplierType;
        $target->name = $request->name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.SUPPLIER_TYPE_CREATED_SUCCESSFULLY'));
            return redirect('supplierType');
        } else {
            Session::flash('error', __('label.SUPPLIER_TYPE_COULD_NOT_BE_CREATED'));
            return redirect('supplierType/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = SupplierType::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('supplierType');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('supplierType.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = SupplierType::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:supplier_type,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('supplierType/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->status = $request->status;
        
        if ($target->save()) {
            Session::flash('success', __('label.SUPPLIER_TYPE_UPDATED_SUCCESSFULLY'));
            return redirect('supplierType' . $pageNumber);
        } else {
            Session::flash('error', __('label.SUPPLIER_TYPE_COULD_NOT_BE_UPDATED'));
            return redirect('supplierType/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = SupplierType::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        
        //Dependency
        $dependencyArr = [
            'Supplier' => ['1' => 'supplier_type_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('supplierType' . $pageNumber);
                }
            }
        }
        if ($target->delete()) {
            Session::flash('error', __('label.SUPPLIER_TYPE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.SUPPLIER_TYPE_COULD_NOT_BE_DELETED'));
        }
        return redirect('supplierType' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('supplierType?' . $url);
    }

}