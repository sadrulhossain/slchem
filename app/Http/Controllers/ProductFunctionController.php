<?php

namespace App\Http\Controllers;

use Validator;
use App\ProductFunction;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class ProductFunctionController extends Controller {

    private $controller = 'ProductFunction';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = ProductFunction::select('product_function.*')->orderBy('name', 'asc');

//        //begin filtering
        $searchText = $request->search;
        $nameArr = ProductFunction::select('name')->orderBy('name', 'asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('product_function.status' , '=' ,$request->status);
        }
//        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/productFunction?page=' . $page);
        }

        return view('productFunction.index')->with(compact('targetArr', 'qpArr','nameArr', 'status'));
    }

    public function create(Request $request) {
        $qpArr = $request->all();
        return view('productFunction.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //echo '<pre>';
        //print_r($request->all());exit;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:product_function',
        ]);

        if ($validator->fails()) {
            return redirect('productFunction/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new ProductFunction;
        $target->name = $request->name;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.PRODUCT_FUNCTION_CREATED_SUCCESSFULLY'));
            return redirect('productFunction');
        } else {
            Session::flash('error', __('label.PRODUCT_FUNCTION_COULD_NOT_BE_CREATED'));
            return redirect('productFunction/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = ProductFunction::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('productFunction');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('productFunction.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = ProductFunction::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:product_function,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('productFunction/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->status = $request->status;
        
        if ($target->save()) {
            Session::flash('success', __('label.PRODUCT_FUNCTION_UPDATED_SUCCESSFULLY'));
            return redirect('productFunction' . $pageNumber);
        } else {
            Session::flash('error', __('label.PRODUCT_FUNCTION_COULD_NOT_BE_UPDATED'));
            return redirect('productFunction/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = ProductFunction::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Dependency
        $dependencyArr = [
            'Product' => ['1' => 'product_function_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return redirect('productFunction' . $pageNumber);
                }
            }
        }
        
        if ($target->delete()) {
            Session::flash('error', __('label.PRODUCT_FUNCTION_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_FUNCTION_COULD_NOT_BE_DELETED'));
        }
        return redirect('productFunction' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search  . '&status=' . $request->status;
        return Redirect::to('productFunction?' . $url);
    }

}