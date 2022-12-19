<?php

namespace App\Http\Controllers;

use Validator;
use App\Pictogram;
use Session;
use Redirect;
use Auth;
use File;
use Input;
use Helper;
use Illuminate\Http\Request;

class PictogramController extends Controller {

    private $controller = 'Pictogram';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Pictogram::orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Pictogram::select('name')->orderBy('name','asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //echo '<pre>';print_r($targetArr);exit;
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/pictogram?page=' . $page);
        }

        return view('pictogram.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('pictogram.create')->with(compact('qpArr','orderList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'name' => 'required',
            'logo' => 'required',
            'order' => 'required|not_in:0'
        ];

        if (!empty($request->logo)) {
            $rules['logo'] = 'max:1024|mimes:jpeg,png,jpg';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('pictogram/create' . $pageNumber)
                            ->withInput(Input::except('logo'))
                            ->withErrors($validator);
        }

        //file upload
        $file = $request->file('logo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/pictogram', $fileName);
        }

        $target = new Pictogram;
        $target->name = $request->name;
        $target->description = $request->description;
        $target->order = 0;
        $target->logo = !empty($fileName) ? $fileName : '';

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.PICTOGRAM_CREATED_SUCCESSFULLY'));
            return redirect('pictogram');
        } else {
            Session::flash('error', __('label.PICTOGRAM_COULD_NOT_BE_CREATED'));
            return redirect('pictogram/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Pictogram::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('pictogram');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('pictogram.edit')->with(compact('target', 'qpArr','orderList'));
    }

    public function update(Request $request, $id) {
        $target = Pictogram::find($id);
        $presentOrder = $target->order;
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update
        $rules = [
            'name' => 'required|unique:pictogram,name,' . $id,
            'order' => 'required|not_in:0'
        ];

        if (!empty($request->logo)) {
            $rules = [
                'logo' => 'max:1024|mimes:jpeg,png,jpg'
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        
        if ($validator->fails()) {
            return redirect('pictogram/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }

        if (!empty($request->logo)) {
            $prevfileName = 'public/uploads/pictogram/' . $target->logo;

            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }
        }

        $file = $request->file('logo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/pictogram', $fileName);
        }


        $target->name = $request->name;
        $target->description = $request->description;
        $target->order = $request->order;
        $target->logo = !empty($fileName) ? $fileName : $target->logo;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.PICTOGRAM_UPDATED_SUCCESSFULLY'));
            return redirect('pictogram' . $pageNumber);
        } else {
            Session::flash('error', __('label.PICTOGRAM_COULD_NOT_BE_UPDATED'));
            return redirect('pictogram/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Pictogram::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //Dependency
        $dependencyArr = [
            'HazardClassLogo' => ['1' => 'pictogram_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('pictogram' . $pageNumber);
                }
            }
        }
        $fileName = 'public/uploads/pictogram/' . $target->logo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        
        
        if ($target->delete()) {
             Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.PICTOGRAM_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PICTOGRAM_COULD_NOT_BE_DELETED'));
        }
        return redirect('pictogram' . $pageNumber);
    }
    
    public function filter(Request $request) {
        $url = 'search=' . $request->search;
        return Redirect::to('pictogram?' . $url);
    }

}
