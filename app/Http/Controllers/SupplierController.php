<?php

namespace App\Http\Controllers;

use Validator;
use App\Supplier; //model class
use App\SupplierType; //model class
use App\SupplierToPhone; //model class
use App\SupplierToEmail; //model class
use Session;
use Redirect;
use Illuminate\Http\Request;

class SupplierController extends Controller {

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $supplierTypeArr = array('0' => __('label.SELECT_SUPPLIER_TYPE_OPT')) + SupplierType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $targetArr = Supplier::leftJoin('supplier_type', 'supplier_type.id', '=', 'supplier.supplier_type_id')
                        ->select('supplier.*', 'supplier_type.name as supplier_type')->orderBy('supplier.name', 'asc');

        //begin filtering
        $searchText = $request->search;
        $nameArr = Supplier::select('name')->orderBy('name','asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('supplier.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->supplier_type)){
            $targetArr = $targetArr->where('supplier.supplier_type_id' , '=' ,$request->supplier_type);
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('supplier.status' , '=' ,$request->status);
        }
        

        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/supplier?page=' . $page);
        }
        
        $supplierPhoneArr = SupplierToPhone::orderBy('supplier_id','asc')->get();
        
        $phoneDataArr = $emailDataArr = [];
        foreach($supplierPhoneArr as $supplierPhone){
            $phoneDataArr[$supplierPhone->supplier_id][] = $supplierPhone->phone;
        
        }
        //echo '<pre>';print_r($phoneDataArr);exit;
        $supplierEmailArr = SupplierToEmail::orderBy('supplier_id','asc')->get();
        
        foreach($supplierEmailArr as $supplierEmail){
            $emailDataArr[$supplierEmail->supplier_id][] = $supplierEmail->email;
        
        }

        return view('supplier.index')->with(compact('qpArr', 'targetArr', 'supplierTypeArr', 'emailDataArr', 'phoneDataArr', 'nameArr', 'status'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $supplierTypeArr = array('0' => __('label.SELECT_SUPPLIER_TYPE_OPT')) + SupplierType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        return view('supplier.create')->with(compact('qpArr', 'supplierTypeArr'));
    }

    public function store(Request $request) {

        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];


        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect('supplier/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Supplier;
        $target->supplier_type_id = $request->supplier_type_id;
        $target->name = $request->name;
        $target->address = $request->address;
        $target->contact_person = $request->contact_person;
        $target->web_address = $request->web_address;
        $target->status = $request->status;

        $phoneArr = $emailArr = [];
        if ($target->save()) {

            if (!empty($request->phone)) {
                foreach ($request->phone as $key => $phone) {
                    $phoneArr[$key]['supplier_id'] = $target->id;
                    $phoneArr[$key]['phone'] = $phone;
                }
            }

            SupplierToPhone::where('supplier_id', $target->id)->delete();
            SupplierToPhone::insert($phoneArr);

            if (!empty($request->email)) {
                foreach ($request->email as $key => $email) {
                    $emailArr[$key]['supplier_id'] = $target->id;
                    $emailArr[$key]['email'] = $email;
                }
            }
            
            SupplierToEmail::where('supplier_id', $target->id)->delete();
            SupplierToEmail::insert($emailArr);

            Session::flash('success', __('label.SUPPLIER_CREATED_SUCCESSFULLY'));
            return redirect('supplier');
        } else {
            Session::flash('error', __('label.SUPPLIER_NOT_BE_CREATED'));
            return redirect('supplier/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Supplier::find($id);
        $supplierToPhoneArr = SupplierToPhone::where('supplier_id',$id)->get();
        $supplierToEmailArr = SupplierToEmail::where('supplier_id',$id)->get();

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('supplier');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $supplierTypeArr = array('0' => __('label.SELECT_SUPPLIER_TYPE_OPT')) + SupplierType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        return view('supplier.edit')->with(compact('target', 'qpArr', 'supplierTypeArr','supplierToPhoneArr','supplierToEmailArr'));
    }

    public function update(Request $request, $id) {
        $target = Supplier::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = [
            'name' => 'required|unique:supplier,name,' . $id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('supplier/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->supplier_type_id = $request->supplier_type_id;
        $target->name = $request->name;
        $target->address = $request->address;
        $target->contact_person = $request->contact_person;
        $target->web_address = $request->web_address;
        $target->status = $request->status;

        $phoneArr = $emailArr = [];
        
        if ($target->save()) {
            if (!empty($request->phone)) {
                foreach ($request->phone as $key => $phone) {
                    $phoneArr[$key]['supplier_id'] = $target->id;
                    $phoneArr[$key]['phone'] = $phone;
                }
            }

            SupplierToPhone::where('supplier_id', $target->id)->delete();
            SupplierToPhone::insert($phoneArr);

            if (!empty($request->email)) {
                foreach ($request->email as $key => $email) {
                    $emailArr[$key]['supplier_id'] = $target->id;
                    $emailArr[$key]['email'] = $email;
                }
            }
            
            SupplierToEmail::where('supplier_id', $target->id)->delete();
            SupplierToEmail::insert($emailArr);
            
            Session::flash('success', __('label.SUPPLIER_UPDATED_SUCCESSFULLY'));
            return redirect('supplier' . $pageNumber);
        } else {
            Session::flash('error', __('label.SUPPLIER_COULD_NOT_BE_UPDATED'));
            return redirect('supplier/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Supplier::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        
        //Dependency
        $dependencyArr = [
            'ProductToSupplier' => ['1' => 'supplier_id'],
            'Recipe' => ['1' => 'supplier_id'],
            'BatchRecipe' => ['1' => 'supplier_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('supplier' . $pageNumber);
                }
            }
        }
        
        
        if ($target->delete()) {
            Session::flash('error', __('label.SUPPLIER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.SUPPLIER_COULD_NOT_BE_DELETED'));
        }
        return redirect('supplier' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&supplier_type=' . $request->supplier_type . '&status=' . $request->status;
        return Redirect::to('supplier?' . $url);
    }

}
