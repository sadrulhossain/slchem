<?php

namespace App\Http\Controllers;

use Validator;
use App\Ppe;
use Session;
use Redirect;
use Auth;
use File;
use Input;
use Illuminate\Http\Request;

class PpeController extends Controller {

    private $controller = 'Ppe';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Ppe::orderBy('name', 'asc');

//        begin filtering
        $searchText = $request->search;
        $nameArr = Ppe::select('name')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
//        end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/ppe?page=' . $page);
        }

        return view('ppe.index')->with(compact('targetArr', 'qpArr','nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('ppe.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'name' => 'required',
            'logo' => 'required'
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('ppe/create' . $pageNumber)
                            ->withInput(Input::except('logo'))
                            ->withErrors($validator);
        }

        //file upload
        $file = $request->file('logo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/ppe', $fileName);
        }

        $target = new Ppe;
        $target->name = $request->name;
        $target->logo = !empty($fileName) ? $fileName : '';

        if ($target->save()) {
            Session::flash('success', __('label.PPE_CREATED_SUCCESSFULLY'));
            return redirect('ppe');
        } else {
            Session::flash('error', __('label.PPE_COULD_NOT_BE_CREATED'));
            return redirect('ppe/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Ppe::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('ppe');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('ppe.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = Ppe::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter']; 
        //end back same page after update
        $rules = [
            'name' => 'required|unique:ppe,name,' . $id,
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'mimes:jpeg,png,jpg|max:1024';
        }
        

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect('ppe/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        if (!empty($request->logo)) {
            $prevfileName = 'public/uploads/ppe/' . $target->logo;

            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }
        }

        $file = $request->file('logo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/ppe', $fileName);
        }


        $target->name = $request->name;
        $target->logo = !empty($fileName) ? $fileName : $target->logo;

        if ($target->save()) {
            Session::flash('success', __('label.PPE_UPDATED_SUCCESSFULLY'));
            return redirect('ppe' . $pageNumber);
        } else {
            Session::flash('error', __('label.PPE_COULD_NOT_BE_UPDATED'));
            return redirect('ppe/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Ppe::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //dependency
        $dependencyArr = [
            'ProductToPpe' => ['1' => 'ppe_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('ppe' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.PPE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PPE_COULD_NOT_BE_DELETED'));
        }
        return redirect('ppe' . $pageNumber);
    }
    
    public function filter(Request $request) {
        $url = 'search=' . $request->search ;
        return Redirect::to('ppe?' . $url);
    }

}
