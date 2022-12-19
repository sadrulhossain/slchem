<?php

namespace App\Http\Controllers;

use Validator;
use App\SubstanceCas;
use Session;
use Redirect;
use Illuminate\Http\Request;

class SubstanceCasController extends Controller {

    private $controller = 'SubstanceCas';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = SubstanceCas::orderBy('id', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = SubstanceCas::select('cas_name')->orderBy('cas_name','asc')->get();
        $casArr = SubstanceCas::select('cas_no')->orderBy('cas_no','asc')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('cas_name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->cas_no)){
            $targetArr = $targetArr->where('substance_cas.cas_no' , '=' ,$request->cas_no);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
         if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/substanceCas?page=' . $page);
        }

        return view('substanceCas.index')->with(compact('targetArr', 'qpArr','nameArr', 'casArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('substanceCas.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'cas_name' => 'required|unique:substance_cas',
                    'cas_no' => 'required|unique:substance_cas',
        ]);

        if ($validator->fails()) {
            return redirect('substanceCas/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new SubstanceCas;
        $target->cas_name = $request->cas_name;
        $target->cas_no = $request->cas_no;

        if ($target->save()) {
            Session::flash('success', __('label.CAS_CREATED_SUCCESSFULLY'));
            return redirect('substanceCas');
        } else {
            Session::flash('error', __('label.CAS_COULD_NOT_BE_CREATED'));
            return redirect('substanceCas/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = SubstanceCas::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('substanceCas');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('substanceCas.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = SubstanceCas::find($id);
        
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'cas_name' => 'required|unique:substance_cas,cas_name,' . $id,
                    'cas_no' => 'required|numeric|unique:substance_cas,cas_no,'. $id,
        ]);

        if ($validator->fails()) {
            return redirect('substanceCas/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }
		
		$target->cas_no = $request->cas_no;
        $target->cas_name = $request->cas_name;
        
        
        if ($target->save()) {
            Session::flash('success', __('label.CAS_UPDATED_SUCCESSFULLY'));
            return redirect('substanceCas' . $pageNumber);
        } else {
            Session::flash('error', __('label.CAS_COULD_NOT_BE_UPDATED'));
            return redirect('substanceCas/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = SubstanceCas::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //dependency
        $dependencyArr = [
            'ProductToCas' => ['1' => 'cas_no']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $target->cas_no)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('substanceCas' . $pageNumber);
                }
            }
        }
        
        
        if ($target->delete()) {
            Session::flash('error', __('label.CAS_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CAS_COULD_NOT_BE_DELETED'));
        }
        return redirect('substanceCas' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&cas_no=' . $request->cas_no;
        return Redirect::to('substanceCas?' . $url);
    }

}