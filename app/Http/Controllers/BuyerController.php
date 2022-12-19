<?php

namespace App\Http\Controllers;

use Validator;
use App\Buyer;
use Session;
use Redirect;
use Input;
use Auth;
use File;
use Illuminate\Http\Request;

class BuyerController extends Controller {

    private $controller = 'Buyer';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Buyer::select('buyer.*')->orderBy('name', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Buyer::select('name')->orderBy('name','asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('buyer.status' , '=' ,$request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
         if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/buyer?page=' . $page);
        }

        return view('buyer.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'status'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('buyer.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update
        
        $rules = [
            'name' => 'required|unique:buyer',
            'logo' => 'required',
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        
        if ($validator->fails()) {
            return redirect('buyer/create' . $pageNumber)
                            ->withInput(Input::except('logo'))
                            ->withErrors($validator);
        }

        //file upload
        $file = $request->file('logo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/buyer', $fileName);
        }
        

        $target = new Buyer;
        $target->name = $request->name;
        $target->description = $request->description;
        $target->status = $request->status;
        $target->logo = !empty($fileName) ? $fileName : '';

        if ($target->save()) {
            Session::flash('success', __('label.BUYER_CREATED_SUCCESSFULLY'));
            return redirect('buyer');
        } else {
            Session::flash('error', __('label.BUYER_COULD_NOT_BE_CREATED'));
            return redirect('buyer/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Buyer::find($id);
        //echo '<pre>';print_r($target);exit;
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('buyer');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('buyer.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
        $target = Buyer::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update
        
        $rules = [
            'name' => 'required|unique:buyer,name,' . $id,
        ];

        if (!empty($request->logo)) {
            $rules = [
                'logo' => 'max:1024|mimes:jpeg,png,jpg'
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        
        if ($validator->fails()) {
            return redirect('buyer/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        if (!empty($request->logo)) {
            $prevfileName = 'public/uploads/buyer/' . $target->logo;

            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }
        }

        $file = $request->file('logo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/buyer', $fileName);
        }

        $target->name = $request->name;
        $target->description = $request->description;
        $target->status = $request->status;
        $target->logo = !empty($fileName) ? $fileName : $target->logo;
        
        if ($target->save()) {
            Session::flash('success', __('label.BUYER_UPDATED_SUCCESSFULLY'));
            return redirect('buyer' . $pageNumber);
        } else {
            Session::flash('error', __('label.BUYER_COULD_NOT_BE_UPDATED'));
            return redirect('buyer/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Buyer::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //dependency
        $dependencyArr = [
            'Recipe' => ['1' => 'buyer_id'],
            'BatchRecipe' => ['1' => 'buyer_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('buyer' . $pageNumber);
                }
            }
        }
        
        $fileName = 'public/uploads/buyer/' . $target->logo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        if ($target->delete()) {
            Session::flash('error', __('label.BUYER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BUYER_COULD_NOT_BE_DELETED'));
        }
        return redirect('buyer' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('buyer?' . $url);
    }

}