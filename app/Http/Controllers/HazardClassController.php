<?php

namespace App\Http\Controllers;

use Validator;
use App\HazardCategory;
use App\HazardClass;
use App\HazardClassLogo;
use App\Pictogram;
use Auth;
use Session;
use Redirect;
use Helper;
use File;
use Response;
use Illuminate\Http\Request;

class HazardClassController extends Controller {

    private $controller = 'HazardClass';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $categoryArr = HazardCategory::pluck('name', 'id')->toArray();
        $pictogramArr = Pictogram::orderBy('order','asc')->pluck('logo', 'id')->toArray();
        $pictogramNameArr = Pictogram::pluck('name', 'id')->toArray();
        
        
        $targetArr = HazardClass::with('hazardClassLogo');

        //begin filtering
        $searchText = $request->search;
        
        $nameArr = HazardClass::select('name')->orderBy('name','asc')->get();
        $FilterHazardcategoryArr = ['0' => __('label.SELECT_HAZARD_CATEGORY_OPT')] + HazardCategory::pluck('name', 'id')->toArray();
        
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('hazard_class.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->hazard_category)){
            $targetArr = $targetArr->where('hazard_class.hazard_cat_id' , '=' ,$request->hazard_category);
        }
        //end filtering

        $targetArr = $targetArr->orderBy('name', 'asc')->paginate(Session::get('paginatorCount'));
        


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/hazardClass?page=' . $page);
        }

        return view('hazardClass.index')->with(compact('targetArr', 'qpArr', 'categoryArr', 'pictogramArr','pictogramNameArr', 'nameArr', 'FilterHazardcategoryArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $categoryArr = ['0' => __('label.SELECT_HAZARD_CATEGORY_OPT')] + HazardCategory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $pictogramArr = Pictogram::orderBy('order','asc')->get();
        return view('hazardClass.create')->with(compact('qpArr', 'categoryArr', 'pictogramArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        //echo '<pre>';print_r($qpArr);exit;
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'name' => 'required|unique:hazard_class',
            'hazard_cat_id' => 'required|not_in:0',
            'pictogram_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('hazardClass/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new HazardClass;
        $target->hazard_cat_id = $request->hazard_cat_id;
        $target->name = $request->name;
        //echo '<pre>';print_r($target);exit;

        if ($target->save()) {
            $data = array();
            if (!empty($request->pictogram_id)) {
                foreach ($request->pictogram_id as $key => $pictogramId) {
                    $data[$key]['hazard_class_id'] = $target->id;
                    $data[$key]['pictogram_id'] = $pictogramId;
                }
            }

            if (!empty($data)) {
                HazardClassLogo::where('hazard_class_id', $target->id)->delete();
                HazardClassLogo::insert($data);
            }
            Session::flash('success', __('label.HAZARD_CLASS_CREATED_SUCCESSFULLY'));
            return redirect('hazardClass');
        } else {
            Session::flash('error', __('label.HAZARD_CLASS_COULD_NOT_BE_CREATED'));
            return redirect('hazardClass/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = HazardClass::find($id);
        $categoryArr = ['0' => __('label.SELECT_HAZARD_CATEGORY_OPT')] + HazardCategory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $pictogramArr = Pictogram::orderBy('order','asc')->get();
        $hazardClassLogoArr = HazardClassLogo::select('pictogram_id')->where('hazard_class_id', $id)->get();
        $prevLogoData = [];
        if (!empty($hazardClassLogoArr)) {
            foreach ($hazardClassLogoArr as $classLogo) {
                $prevLogoData[$classLogo->pictogram_id] = $classLogo->pictogram_id;
            }
        }
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('hazardClass');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('hazardClass.edit')->with(compact('target', 'qpArr','categoryArr','pictogramArr','prevLogoData'));
    }

    public function update(Request $request,$id) {
        $target = HazardClass::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page = '.$qpArr['page'] : '';
        //end back same page after update

       $rules = [
            'name' => 'required|unique:hazard_class,name,' . $id,
            'hazard_cat_id' => 'required|not_in:0',
            'pictogram_id' => 'required',   
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('hazardClass/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }
        $target->hazard_cat_id = $request->hazard_cat_id ;
        $target->name = $request->name;

        if ($target->save()) {
            $data = array();
            if (!empty($request->pictogram_id)) {
                foreach ($request->pictogram_id as $key => $pictogramId) {
                    $data[$key]['hazard_class_id'] = $target->id;
                    $data[$key]['pictogram_id'] = $pictogramId;
                }
            }

            if (!empty($data)) {
                HazardClassLogo::where('hazard_class_id', $id)->delete();
                HazardClassLogo::insert($data);
            }
            Session::flash('success', __('label.HAZARD_CLASS_UPDATED_SUCCESSFULLY'));
            return redirect('hazardClass' . $pageNumber);
        } else {
           Session::flash('error', __('label.HAZARD_CLASS_COULD_NOT_BE_UPDATED'));
            return redirect('hazardClass/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = HazardClass::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page = ' . $qpArr['page'] : '?page = ';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            HazardClassLogo::where('hazard_class_id', $id)->delete();
            Session::flash('error', __('label.HAZARD_CLASS_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.HAZARD_CLASS_COULD_NOT_BE_DELETED'));
        }
        return redirect('hazardClass' . $pageNumber);
    }

    public function filter(Request $request) {
        //dd($request->all());
        $url = 'search=' . $request->search . '&hazard_category=' . $request->hazard_category;
        //dd($url);
        return Redirect::to('hazardClass?' . $url);
    }
}
