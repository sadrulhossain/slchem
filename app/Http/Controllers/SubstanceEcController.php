<?php

namespace App\Http\Controllers;

use Validator;
use App\SubstanceEc;
use Session;
use Redirect;
use Illuminate\Http\Request;

class SubstanceEcController extends Controller {

    private $controller = 'SubstanceEc';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = SubstanceEc::orderBy('id', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = SubstanceEc::select('ec_name')->orderBy('ec_name','asc')->get();
        $ecNOArr = SubstanceEc::select('ec_no')->orderBy('ec_no','asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('ec_name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->ec_no)){
            $targetArr = $targetArr->where('substance_ec.ec_no' , '=' ,$request->ec_no);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
         if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/substanceEc?page=' . $page);
        }

        return view('substanceEc.index')->with(compact('targetArr', 'qpArr', 'nameArr', 'ecNOArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('substanceEc.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'ec_no' => 'required|unique:substance_ec',
                    'ec_name' => 'required|unique:substance_ec',
        ]);

        if ($validator->fails()) {
            return redirect('substanceEc/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new SubstanceEc;
        $target->ec_no = $request->ec_no;
        $target->ec_name = $request->ec_name;

        if ($target->save()) {
            Session::flash('success', __('label.EC_CREATED_SUCCESSFULLY'));
            return redirect('substanceEc');
        } else {
            Session::flash('error', __('label.EC_COULD_NOT_BE_CREATED'));
            return redirect('substanceEc/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = SubstanceEc::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('substanceEc');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('substanceEc.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = SubstanceEc::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'ec_name' => 'required|unique:substance_ec,ec_name,' . $id,
                    'ec_no' => 'required|unique:substance_ec,ec_no,'. $id,
        ]);

        if ($validator->fails()) {
            return redirect('substanceEc/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->ec_no = $request->ec_no;
        $target->ec_name = $request->ec_name;
        
        if ($target->save()) {
            Session::flash('success', __('label.EC_UPDATED_SUCCESSFULLY'));
            return redirect('substanceEc' . $pageNumber);
        } else {
            Session::flash('error', __('label.EC_COULD_NOT_BE_UPDATED'));
            return redirect('substanceEc/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = SubstanceEc::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //dependency
        $dependencyArr = [
            'ProductToEc' => ['1' => 'ec_no']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $target->ec_no)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('substanceEc' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.EC_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.EC_COULD_NOT_BE_DELETED'));
        }
        return redirect('substanceEc' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&ec_no=' . $request->ec_no;
        return Redirect::to('substanceEc?' . $url);
    }

}