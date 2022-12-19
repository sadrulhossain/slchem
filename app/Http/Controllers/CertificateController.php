<?php

namespace App\Http\Controllers;

use Validator;
use App\Certificate;
use Session;
use Redirect;
use Illuminate\Http\Request;

class CertificateController extends Controller {

    private $controller = 'Certificate';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Certificate::orderBy('id', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Certificate::select('name')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/certificate?page=' . $page);
        }

        return view('certificate.index')->with(compact('targetArr', 'qpArr','nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('certificate.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update
        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:certificate',
        ]);

        if ($validator->fails()) {
            return redirect('certificate/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Certificate;
        $target->name = $request->name;

        if ($target->save()) {
            Session::flash('success', __('label.CERTIFICATE_CREATED_SUCCESSFULLY'));
            return redirect('certificate');
        } else {
            Session::flash('error', __('label.CERTIFICATE_COULD_NOT_BE_CREATED'));
            return redirect('certificate/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Certificate::find($id);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('certificate');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('certificate.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = Certificate::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update
        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:certificate,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect('certificate/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;

        if ($target->save()) {
            Session::flash('success', __('label.CERTIFICATE_UPDATED_SUCCESSFULLY'));
            return redirect('certificate' . $pageNumber);
        } else {
            Session::flash('error', __('label.CERTIFICATE_COULD_NOT_BE_UPDATED'));
            return redirect('certificate/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Certificate::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //dependency
        $dependencyArr = [
            'ProductToCertificate' => ['1' => 'certificate_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('certificate' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Session::flash('error', __('label.CERTIFICATE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CERTIFICATE_COULD_NOT_BE_DELETED'));
        }
        return redirect('certificate' . $pageNumber);
    }
    
    public function filter(Request $request) {
        $url = 'search=' . $request->search ;
        return Redirect::to('certificate?' . $url);
    }

}
