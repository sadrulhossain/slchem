<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductCategory;
use App\ProductFunction;
use App\Buyer;
use App\Manufacturer;
use App\Ppe;
use App\SubstanceEc;
use App\SubstanceCas;
use App\ProductToCas;
use App\ProductToEc;
use App\ProductToPpe;
use App\Certificate;
use App\HazardCategory;
use App\MeasureUnit;
use App\SecondaryUnit;
use App\ProductToCertificate;
use App\ProductToHazardCat;
use App\ProductToBpl;
use App\ProductToMpl;
use App\ProductToGl;
use App\Configuration;
use App\Supplier;
use App\ProductToSupplier;
use App\ProductToManufacturer;
use App\ProductToProcess;
use App\RecipeToProduct;
use App\ProductConsumptionDetails;
use App\User;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use File;
use Response;
use DB;
use Illuminate\Http\Request;

class ProductController extends Controller {

    private $statusArr = [0 => ['status' => 'Pending', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];
    private $ratioArr = ['1' => ['ratio' => 'g/L', 'label' => 'success'],
        '2' => ['ratio' => '%', 'label' => 'warning']
        , '3' => ['ratio' => 'Direct Amount', 'label' => 'primary']];
    private $fileSize = '10240';

    public function __construct() {
        Validator::extend('greaterDosage', function($attribute, $value, $parameters) {
            $toDosage = $value;
            $fromDosage = $parameters[1];

            if ($toDosage >= $fromDosage) {
                return true;
            }
            return false;
        });
    }

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $nameArr = Product::select('name')->orderBy('product_code', 'asc')->get();
        $productCategoryArr = array('0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')) + ProductCategory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $userFirstNameArr = User::orderBy('first_name', 'asc')->pluck('first_name', 'id')->toArray();
        $userLastNameArr = User::orderBy('last_name', 'asc')->pluck('last_name', 'id')->toArray();
        $approvalStatusArr = ['' => __('label.SELECT_APPROVAL_STATUS_OPT'), '0' => 'Pending', '1' => 'Approved'];

        $targetArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->leftJoin('product_function', 'product_function.id', '=', 'product.product_function_id')
                ->select('product.*', 'product_category.name as product_category', 'product_function.name as product_function');

        //begin filtering
        $searchText = $request->search;
        $productCodeArr = Product::select('product_code')->get();
        $productFunctionArr = ['0' => __('label.SELECT_PRODUCT_FUNCTION_OPT')] + ProductFunction::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {

                $query->where('product.name', 'LIKE', '%' . $searchText . '%');
            });
        }

        if ($request->approval_status != '') {
            $targetArr = $targetArr->where('product.approval_status', $request->approval_status);
        }

        if (!empty($request->product_category)) {
            $targetArr = $targetArr->where('product.product_category_id', $request->product_category);
        }

        if (!empty($request->product_code)) {
            $targetArr = $targetArr->where('product.product_code', $request->product_code);
        }

        if (!empty($request->product_function)) {
            $targetArr = $targetArr->where('product.product_function_id', $request->product_function);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('product.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->orderBy('product.name', 'asc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/product?page=' . $page);
        }

        $statusArr = $this->statusArr;
        $ratioArr = $this->ratioArr;
        return view('product.index')->with(compact('qpArr', 'targetArr', 'approvalStatusArr', 'productCategoryArr', 'statusArr', 'userFirstNameArr', 'userLastNameArr', 'nameArr', 'productCodeArr', 'status', 'productFunctionArr', 'ratioArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $productCategoryArr = array('0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')) + ProductCategory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productFunctionArr = array('0' => __('label.SELECT_PRODUCT_FUNCTION_OPT')) + ProductFunction::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $primaryUnitArr = array('0' => __('label.SELECT_PRIMARY_UNIT_OPT')) + MeasureUnit::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $secondaryUnitArr = array('0' => __('label.SELECT_SECONDARY_UNIT_OPT')) + SecondaryUnit::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $ratioArr = array('0' => __('label.SELECT_TYPE_OF_DOSING_RATIO_OPT')) + ['1' => __('label.GRAM_LITRE'), '2' => __('label.PERCENTAGE'), '3' => __('label.DIRECT_AMOUNT')];
        $productCodeCount = Product::select(DB::raw('count(id) as total_item'))->first();
        $productCode = $productCodeCount->total_item + 1;
        return view('product.create')->with(compact('qpArr', 'productCategoryArr'
                                , 'productFunctionArr'
                                , 'primaryUnitArr', 'secondaryUnitArr', 'productCode', 'ratioArr'));
    }

    public function store(Request $request) {
        
        //passing param for custom function
//        $sdsFile = $request->file('sds_file');
//        $sdsFileRealName = $sdsFile->getClientOriginalName();
//        dd($sdsFileRealName);
        
        $qpArr = $request->all();
//        dd($qpArr);
        $pageNumber = $qpArr['filter'];

        $rules = [
            'product_category_id' => 'required|not_in:0',
            'product_function_id' => 'required|not_in:0',
            'primary_unit_id' => 'required|not_in:0',
            'name' => 'required|unique:product',
            'reorder_level' => 'required|numeric|min:1',
            'date' => 'required',
            'type_of_dosage_ratio' => 'required|not_in:0',
        ];
        $userAccessArr = Common::userAccess();
        if (!empty($userAccessArr[20][16])) {
            $rules['product_code'] = 'required|unique:product';
        } 
        
        if (!empty($request->sds_file)) {
            $rules['sds_file'] = 'max:' . $this->fileSize . '|mimes:pdf';
        }

        if (!empty($request->tds_file)) {
            $rules['tds_file'] = 'max:' . $this->fileSize . '|mimes:pdf';
        }

        if (!empty($request->type_of_dosage_ratio) && (in_array($request->type_of_dosage_ratio, ['1', '2']))) {
            $rules['from_dosage'] = 'required';
            $rules['to_dosage'] = 'required|greater_dosage:,' . $request->from_dosage;
        }

        $messages = array(
            'to_dosage.greater_dosage' => __('label.TO_DOSAGE_SHOULD_BE_GREATER_THAN_FROM_DOSAGE'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            return redirect('product/create' . $pageNumber)
                            ->withInput(Input::except('sds_file', 'tds_file'))
                            ->withErrors($validator);
        }

        //file upload
        $sdsFile = $request->file('sds_file');
        if (!empty($sdsFile)) {
            $sdsFileRealName = $sdsFile->getClientOriginalName();
            $sdsFileName = uniqid() . "_" . Auth::user()->id . "." . $sdsFile->getClientOriginalExtension();
            $sdsFile->move('public/uploads/safetyDataSheet', $sdsFileName);
        }

        $tdsFile = $request->file('tds_file');
        if (!empty($tdsFile)) {
            $tdsFileRealName = $tdsFile->getClientOriginalName();
            $tdsFileName = uniqid() . "_" . Auth::user()->id . "." . $tdsFile->getClientOriginalExtension();
            $tdsFile->move('public/uploads/technicalDataSheet', $tdsFileName);
        }

//checking with product code
        $productMaximum = Configuration::select('serial_code')->first();
        if (($request->product_code) > ($productMaximum->serial_code)) {
            Session::flash('error', __('label.YOU_ARE_NOT_ALLOWED_TO_USE_THIS_PRODUCT_CODE'));
            return redirect('product');
        } else {
            $target = new Product;
            $target->product_category_id = $request->product_category_id;
            $target->product_function_id = $request->product_function_id;
            $target->primary_unit_id = $request->primary_unit_id;
            $target->secondary_unit_id = $request->secondary_unit_id;
            $target->available_quantity = $request->available_quantity;
            $target->name = $request->name;
            $target->description = $request->description;
            $target->recommended_dosage = $request->recommended_dosage;
            $target->type_of_dosage_ratio = $request->type_of_dosage_ratio;
            $target->from_dosage = $request->from_dosage;
            $target->to_dosage = $request->to_dosage;
            $target->product_code = $request->product_code;
            $target->available_quantity = 0;
            $target->status = $request->status;
            $target->show_in_report = empty($request->show_in_report) ? '0' : '1';
            $target->approval_status = 0;
            $target->storage_condition = $request->storage_condition;
            $target->storage_location = $request->storage_location;
            $target->reorder_level = $request->reorder_level;
            $target->sds_version = $request->sds_version;
            $target->date = $request->date;
            $target->sds = empty($request->sds) ? '0' : '1';
            $target->tds = empty($request->tds) ? '0' : '1';
            $target->sds_file = !empty($sdsFileName) ? $sdsFileName : '';
            $target->tds_file = !empty($tdsFileName) ? $tdsFileName : '';
            $target->sds_file_name = !empty($sdsFileRealName) ? $sdsFileRealName : '';
            $target->tds_file_name = !empty($tdsFileRealName) ? $tdsFileRealName : '';

            if ($target->save()) {
                Session::flash('success', __('label.PRODUCT_CREATED_SUCCESSFULLY'));
                return redirect('product');
            } else {
                Session::flash('error', __('label.PRODUCT_COULD_NOT_BE_CREATED'));
                return redirect('product/create' . $pageNumber);
            }
        }
    }

    public function edit(Request $request, $id) {
        $target = Product::find($id);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('product');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $productCategoryArr = array('0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')) + ProductCategory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productFunctionArr = array('0' => __('label.SELECT_PRODUCT_FUNCTION_OPT')) + ProductFunction::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $primaryUnitArr = array('0' => __('label.SELECT_PRIMARY_UNIT_OPT')) + MeasureUnit::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $secondaryUnitArr = array('0' => __('label.SELECT_SECONDARY_UNIT_OPT')) + SecondaryUnit::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $ratioArr = array('0' => __('label.SELECT_TYPE_OF_DOSING_RATIO_OPT')) + ['1' => __('label.GRAM_LITRE'), '2' => __('label.PERCENTAGE'), '3' => __('label.DIRECT_AMOUNT')];
        $showInReport = ($target->show_in_report == '1') ? true : false;
        return view('product.edit')->with(compact('target', 'qpArr', 'productCategoryArr', 'productFunctionArr'
                                , 'primaryUnitArr', 'secondaryUnitArr', 'showInReport', 'ratioArr'));
    }

    public function update(Request $request, $id) {
        
        $target = Product::find($id);
//        echo '<pre>';
//                var_dump($request->all());exit;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'product_category_id' => 'required|not_in:0',
            'product_function_id' => 'required|not_in:0',
            'primary_unit_id' => 'required|not_in:0',
            'reorder_level' => 'required|numeric|min:1',
            'name' => 'required|unique:product,name,' . $id,
            'date' => 'required',
            'type_of_dosage_ratio' => 'required|not_in:0',
        ];
        
        $userAccessArr = Common::userAccess();
        if (!empty($userAccessArr[20][16])) {
            $rules['product_code'] = 'required|unique:product,product_code,'.$id;
        } 

        if (!empty($request->type_of_dosage_ratio) && (in_array($request->type_of_dosage_ratio, ['1', '2']))) {
            $rules['from_dosage'] = 'required';
            $rules['to_dosage'] = 'required|greater_dosage:,' . $request->from_dosage;
        }

        $messages = array(
            'to_dosage.greater_dosage' => __('label.TO_DOSAGE_SHOULD_BE_GREATER_THAN_FROM_DOSAGE'),
        );

        if (!empty($request->sds_file)) {
            $rules['sds_file'] = 'max:' . $this->fileSize . '|mimes:pdf';
        }

        if (!empty($request->tds_file)) {
            $rules['tds_file'] = 'max:' . $this->fileSize . '|mimes:pdf';
        }



        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('product/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all())
                            ->withErrors($validator);
        }


        if (!empty($request->sds_file)) {
            $prevSdsfileName = 'public/uploads/safetyDataSheet/' . $target->sds_file;

            if (File::exists($prevSdsfileName)) {
                File::delete($prevSdsfileName);
            }
        }
        if (!empty($request->tds_file)) {
            $prevTdsfileName = 'public/uploads/technicalDataSheet/' . $target->tds_file;

            if (File::exists($prevTdsfileName)) {
                File::delete($prevTdsfileName);
            }
        }

        //file upload
        $sdsFile = $request->file('sds_file');

        if (!empty($sdsFile)) {
            $sdsFileRealName = $sdsFile->getClientOriginalName();
            $sdsFileName = uniqid() . "_" . Auth::user()->id . "." . $sdsFile->getClientOriginalExtension();
            $sdsFile->move('public/uploads/safetyDataSheet', $sdsFileName);
        }

        $tdsFile = $request->file('tds_file');
        if (!empty($tdsFile)) {
            $tdsFileRealName = $tdsFile->getClientOriginalName();
            $tdsFileName = uniqid() . "_" . Auth::user()->id . "." . $tdsFile->getClientOriginalExtension();
            $tdsFile->move('public/uploads/technicalDataSheet', $tdsFileName);
        }


        $productMaximum = Configuration::select('serial_code')->first();
        if (($request->product_code) > ($productMaximum->serial_code)) {

            Session::flash('error', __('label.YOU_ARE_NOT_ALLOWED_TO_USE_THIS_PRODUCT_CODE'));
            return redirect('product');
        } else {
            $target->product_category_id = $request->product_category_id;
            $target->product_function_id = $request->product_function_id;
            $target->primary_unit_id = $request->primary_unit_id;
            $target->secondary_unit_id = $request->secondary_unit_id;
            $target->name = $request->name;
            $target->description = $request->description;
            $target->recommended_dosage = $request->recommended_dosage;
            $target->type_of_dosage_ratio = $request->type_of_dosage_ratio;
            $target->from_dosage = $request->from_dosage;
            $target->to_dosage = $request->to_dosage;
            $target->product_code = $request->product_code;
            $target->status = $request->status;
            $target->show_in_report = empty($request->show_in_report) ? '0' : '1';
            $target->storage_condition = $request->storage_condition;
            $target->storage_location = $request->storage_location;
            $target->reorder_level = $request->reorder_level;
            $target->sds_version = $request->sds_version;
            $target->date = $request->date;
            $target->sds = empty($request->sds) ? '0' : '1';
            $target->tds = empty($request->tds) ? '0' : '1';
            $target->sds_file = !empty($sdsFileName) ? $sdsFileName : $target->sds_file;
            $target->tds_file = !empty($tdsFileName) ? $tdsFileName : $target->tds_file;
            $target->sds_file_name = !empty($sdsFileRealName) ? $sdsFileRealName : $target->sds_file_name;
            $target->tds_file_name = !empty($tdsFileRealName) ? $tdsFileRealName : $target->tds_file_name;
            //echo '<pre>';print_r($target);exit;
            if ($target->save()) {
                Session::flash('success', __('label.PRODUCT_UPDATED_SUCCESSFULLY'));
                return redirect('product' . $pageNumber);
            } else {
                Session::flash('error', __('label.PRODUCT_COULD_NOT_BE_UPDATED'));
                return redirect('product/' . $id . '/edit' . $pageNumber);
            }
        }
    }

    public function destroy(Request $request, $id) {
        $target = Product::find($id);
//begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
//end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }


//dependency
        $dependencyArr = [
            'ProductConsumptionDetails' => ['1' => 'product_id'],
            'ProductToCas' => ['1' => 'product_id'],
            'ProductToEc' => ['1' => 'product_id'],
            'ProductToCertificate' => ['1' => 'product_id'],
            'ProductToGl' => ['1' => 'product_id'],
            'ProductToBpl' => ['1' => 'product_id'],
            'ProductToMpl' => ['1' => 'product_id'],
            'ProductToPpe' => ['1' => 'product_id'],
            'ProductToManufacturer' => ['1' => 'product_id'],
            'ProductToSupplier' => ['1' => 'product_id'],
            'ProductToProcess' => ['1' => 'product_id'],
            'RecipeToProduct' => ['1' => 'product_id'],
            'BatchRecipeToProduct' => ['1' => 'product_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                    return redirect('product' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            ProductToCas::where('product_id', $id)->delete();
            ProductToEc::where('product_id', $id)->delete();
            ProductToCertificate::where('product_id', $id)->delete();
            ProductToGl::where('product_id', $id)->delete();
            ProductToBpl::where('product_id', $id)->delete();
            ProductToMpl::where('product_id', $id)->delete();
            ProductToPpe::where('product_id', $id)->delete();
            ProductToManufacturer::where('product_id', $id)->delete();
            ProductToSupplier::where('product_id', $id)->delete();
            ProductToProcess::where('product_id', $id)->delete();
            RecipeToProduct::where('product_id', $id)->delete();
            ProductConsumptionDetails::where('product_id', $id)->delete();
            Session::flash('error', __('label.PRODUCT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PRODUCT_COULD_NOT_BE_DELETED'));
        }
        return redirect('product' . $pageNumber);
    }

    public function filter(Request $request) {
//dd($request->all());
        $url = 'search=' . $request->search . '&approval_status=' . $request->approval_status . '&product_category=' . $request->product_category . '&product_code=' . $request->product_code . '&status=' . $request->status . '&product_function=' . $request->product_function;
        return Redirect::to('product?' . $url);
    }

    public function approvalProduct(Request $request) {
//passing param for custom function
        $qpArr = $request->all();
        $nameArr = Product::select('name')->where('approval_status', '0')->orderBy('product_code', 'asc')->get();
        $targetArr = Product::join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->join('product_function', 'product_function.id', '=', 'product.product_function_id')
                ->where('product.approval_status', 0)
                ->select('product.*', 'product_category.name as product_category', 'product_function.name as product_function');

//begin filtering
        $searchText = $request->search;
        $productCategoryArr = array('0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')) + ProductCategory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productFunctionArr = ['0' => __('label.SELECT_PRODUCT_FUNCTION_OPT')] + ProductFunction::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productCodeArr = Product::select('product_code')->get();
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('product.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->product_category)) {
            $targetArr = $targetArr->where('product.product_category_id', '=', $request->product_category);
        }
        if (!empty($request->product_function)) {
            $targetArr = $targetArr->where('product.product_function_id', '=', $request->product_function);
        }
        if (!empty($request->product_code)) {
            $targetArr = $targetArr->where('product.product_code', '=', $request->product_code);
        }
//end filtering

        $targetArr = $targetArr->orderBy('product.name', 'asc')->paginate(Session::get('paginatorCount'));

//change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/product/approvalProduct?page=' . $page);
        }

        $statusArr = $this->statusArr;
        return view('product.approvalProduct')->with(compact('targetArr', 'qpArr', 'statusArr', 'nameArr', 'productCategoryArr', 'productFunctionArr', 'productCodeArr'));
    }

    public function pendingFilter(Request $request) {
        $url = 'search=' . $request->search . '&product_category=' . $request->product_category . '&product_function=' . $request->product_function . '&product_code=' . $request->product_code;
        return Redirect::to('product/approvalProduct?' . $url);
    }

    public function doApprove(Request $request, $id) {

//begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
//end back same page after update

        $target = Product::find($id);

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('product/approvalProduct' . $pageNumber);
        }

        if ($target->approval_status == '1') {
            Session::flash('success', __('label.PRODUCT_HAS_ALREADY_BEEN_APPROVED'));
            return redirect('product/approvalProduct' . $pageNumber);
        }

        $target->approval_status = 1; //approved
        $target->approved_by = Auth::user()->id;
        $target->approved_at = date('Y-m-d H:i:s');

        if ($target->save()) {
            Session::flash('success', __('label.PRODUCT_HAS_BEEN_APPROVED'));
            return redirect('product' . $pageNumber);
        }
    }

    public function generateSubstanceName(Request $request) {
        $casSubstanceName = SubstanceCas::select('cas_name', 'id')->where('cas_no', $request->cas_no)->first();
        if (!empty($casSubstanceName)) {
            return Response::json(['casSubstanceName' => $casSubstanceName['cas_name']]);
        } else {
            return Response::json(['message' => 'Invalid CAS Substance Name!']);
        }
    }

    public function generateEcSubstanceName(Request $request) {
        $ecSubstanceName = SubstanceEc::select('ec_name', 'id')->where('ec_no', $request->ec_no)->first();
        if (!empty($ecSubstanceName)) {
            return Response::json(['ecSubstanceName' => $ecSubstanceName['ec_name']]);
        } else {
            return Response::json(['message' => 'Invalid EC Substance Name!']);
        }
    }

    public function manageProduct($id) {
        $target = Product::select('name', 'id')->where('id', $id)->first();
        $ppeArr = Ppe::orderBy('name', 'asc')->get();
        $supplierArr = Supplier::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $manufactureArr = Manufacturer::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
//$manufacturerArr = Ppe::orderBy('name', 'asc')->get();
        $hazardCatArr = HazardCategory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $casNoArr = ProductToCas::select('cas_no', 'cas_percentage')->where('product_id', $id)->get();
        $casArr = SubstanceCas::pluck('cas_name', 'cas_no')->toArray();
        $ecArr = SubstanceEc::pluck('ec_name', 'ec_no')->toArray();
        $ecNoArr = ProductToEc::select('ec_no', 'ec_percentage')->where('product_id', $id)->get();
        $existingData = ProductToPpe::select('ppe_id')->where('product_id', $id)->get();
        $existingHazardCatData = ProductToHazardCat::select('hazard_cat_id')->where('product_id', $id)->get();
        $existingSupplierData = ProductToSupplier::select('supplier_id')->where('product_id', $id)->get();
        $existingManufacturerData = ProductToManufacturer::select('manufacturer_id')->where('product_id', $id)->get();
//previous data of PPE
        $previousPpe = [];
        if (!empty($existingData)) {
            foreach ($existingData as $item) {
                $previousPpe[$item->ppe_id] = $item->ppe_id;
            }
        }
//previous data of Hazard Category
        $previousHazardCat = [];
        if (!empty($existingHazardCatData)) {
            foreach ($existingHazardCatData as $item) {
                $previousHazardCat[$item->hazard_cat_id] = $item->hazard_cat_id;
            }
        }

        $previousSupplier = [];
        if (!empty($existingSupplierData)) {
            foreach ($existingSupplierData as $supplierData) {
                $previousSupplier[$supplierData->supplier_id] = $supplierData->supplier_id;
            }
        }


        $previousManufacturer = [];
        if (!empty($existingManufacturerData)) {
            foreach ($existingManufacturerData as $manufacturerData) {
                $previousManufacturer[$manufacturerData->manufacturer_id] = $manufacturerData->manufacturer_id;
            }
        }

        $certificateArr = ['' => __('label.SELECT_CERTIFICATE_OPT')] + Certificate::pluck('name', 'id')->toArray();
        $buyerArr = ['' => __('label.SELECT_BUYER_OPT')] + Buyer::pluck('name', 'id')->toArray();
        $manufacturerArr = ['' => __('label.SELECT_MANUFACTURER_OPT')] + Manufacturer::pluck('name', 'id')->toArray();
        $previousCeritificateArr = ProductToCertificate::where('product_id', $id)->get();
        $previousBplArr = ProductToBpl::where('product_id', $id)->get();
        $previousGlArr = ProductToGl::where('product_id', $id)->get();
        $previousMplArr = ProductToMpl::where('product_id', $id)->get();

        return view('product.manageProduct')->with(compact('target', 'ppeArr', 'previousPpe', 'casNoArr'
                                , 'ecNoArr', 'casArr', 'ecArr', 'previousCeritificateArr', 'certificateArr', 'buyerArr'
                                , 'manufacturerArr', 'previousBplArr', 'previousMplArr', 'previousGlArr'
                                , 'hazardCatArr', 'previousHazardCat', 'supplierArr', 'manufactureArr', 'previousSupplier', 'previousManufacturer'));
    }

    public function newCertificateRow() {
        $certificateArr = ['' => __('label.SELECT_CERTIFICATE_OPT')] + Certificate::pluck('name', 'id')->toArray();
        $view = view('product.newCertificateRow', compact('certificateArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function newGlRow() {

        $buyerArr = ['' => __('label.SELECT_BUYER_OPT')] + Buyer::pluck('name', 'id')->toArray();
        $view = view('product.newGlRow', compact('buyerArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function newBplRow() {
        $buyerArr = ['' => __('label.SELECT_BUYER_OPT')] + Buyer::pluck('name', 'id')->toArray();
        $view = view('product.newBplRow', compact('buyerArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function newMplRow() {
        $manufacturerArr = ['' => __('label.SELECT_MANUFACTURER_OPT')] + Manufacturer::pluck('name', 'id')->toArray();
        $view = view('product.newMplRow', compact('manufacturerArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function lowQuantityProduct() {
        $productCatArr = ProductCategory::pluck('name', 'id')->toArray();
        $unitArr = MeasureUnit::pluck('name', 'id')->toArray();
        $targetArr = Product::whereColumn('reorder_level', '>', 'available_quantity')->where('status', '1')->where('approval_status', 1)->paginate(Session::get('paginatorCount'));
//change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/product/lowQuantityProduct?page=' . $page);
        }
        return view('product.lowQtyProduct')->with(compact('qpArr', 'targetArr', 'productCatArr', 'unitArr'));
    }

    public function saveSubstance(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
        if (!empty($request->cas_no)) {
            foreach ($request->cas_no as $key => $casNo) {
                $casInfo = SubstanceCas::select('cas_name')->where('cas_no', $casNo)->first();

                if (empty($casInfo)) {
                    return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INVALID_CAS_SUBSTANCE_NO')], 401);
                }
            }
        }

        if (!empty($request->ec_no)) {
            foreach ($request->ec_no as $key => $ecNo) {
                $ecInfo = SubstanceEc::select('ec_name')->where('ec_no', $ecNo)->first();
                if (empty($ecInfo)) {
                    return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.INVALID_EC_SUBSTANCE_NO')], 401);
                }
            }
        }
        $casData = $ecData = [];
        //echo '<pre>';print_r($request->all());exit;
        //if (!empty($request->cas_no) || !empty($request->ec_no)) {

        if (!empty($request->cas_no)) {
            foreach ($request->cas_no as $key => $casNo) {
                $casData[$key]['product_id'] = $request->product_id;
                $casData[$key]['cas_no'] = $casNo;
            }
        }

        if (!empty($request->cas_percentage)) {
            foreach ($request->cas_percentage as $key => $casPercent) {
                $casData[$key]['cas_percentage'] = $casPercent;
            }
        }
        ProductToCas::where('product_id', $request->product_id)->delete();
        ProductToCas::insert($casData);

        //insert ec-no

        if (!empty($request->ec_no)) {
            foreach ($request->ec_no as $key => $ecNo) {
                $ecData[$key]['product_id'] = $request->product_id;
                $ecData[$key]['ec_no'] = $ecNo;
            }
        }

        if (!empty($request->ec_percentage)) {
            foreach ($request->ec_percentage as $key => $ecPercent) {
                $ecData[$key]['ec_percentage'] = $ecPercent;
            }
        }
        ProductToEc::where('product_id', $request->product_id)->delete();
        ProductToEc::insert($ecData);

        if (empty($casData) && empty($ecData)) {
            return Response::json(['success' => false, 'heading' => 'Caution', 'message' => __('label.NO_SUBSTANCE_HAS_BEEN_ADDED')], 401);
        }
        return Response::json(['success' => true, 'message' => 'Substance Added successfully'], 200);
    }

    public function saveCertificate(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
        $certificateArr = $request->file('certificate_file');

        $rules = $message = array();

        if (!empty($request->certificate_id)) {
            $row = 0;
            foreach ($request->certificate_id as $key => $certificateId) {
                $rules['certificate_id.' . $key] = 'required';
                $message['certificate_id.' . $key . '.required'] = __('label.CERTIFICATE_NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        if ($request->hasFile('certificate_file')) {
            foreach ($certificateArr as $key => $certificate) {
                $rules['certificate_file.' . $key] = 'max:' . $this->fileSize . '|mimes:pdf';
                $index = array_search($key, array_keys($request->certificate_id));
                $message['certificate_file.' . $key . '.mimes'] = 'Invalid File Format for Row No ' . ($index + 1);
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $data = $certificateData = [];
        //if (!empty($request->certificate_id)) {
        if ($request->hasFile('certificate_file')) {
            if (!empty($certificateArr)) {
                foreach ($certificateArr as $key => $fileName) {
                    $fileNames = uniqid() . $key . "." . $fileName->getClientOriginalExtension();
                    $fileOriginalNames = $fileName->getClientOriginalName();
                    $uploadSuccess = $fileName->move('public/uploads/productToCertificate', $fileNames);
                    $certificateData[$key]['certificate_file'] = $fileNames;
                }
            }
        }
        if (!empty($request->certificate_id)) {
            foreach ($request->certificate_id as $key => $certificateId) {
                $data[$key]['certificate_id'] = $certificateId;
                $data[$key]['product_id'] = $request->product_id;
                $data[$key]['remarks'] = !empty($request->remarks[$key]) ? $request->remarks[$key] : '';
                $data[$key]['certificate_file'] = !empty($certificateData[$key]['certificate_file']) ? $certificateData[$key]['certificate_file'] : (!empty($request->certificate_prev_file[$certificateId]) ? $request->certificate_prev_file[$certificateId] : '');
            }
        }

        ProductToCertificate::where('product_id', $request->product_id)->delete();
        ProductToCertificate::insert($data);
        return Response::json(['success' => true], 200);
//        } else {
//            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.NO_CERTIFICATE_HAS_BEEN_ADDED')], 401);
//        }
    }

    public function saveGl(Request $request) {
        // echo '<pre>';print_r($request->all());exit;
        $glArr = $request->file('gl_file');

        $rules = $message = array();
        if (!empty($request->buyer_id)) {
            $row = 0;
            foreach ($request->buyer_id as $key => $buyerId) {
                $rules['buyer_id.' . $key] = 'required';
                $message['buyer_id.' . $key . '.required'] = __('label.BUYER_NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1 );
                $row++;
            }
        }
        if ($request->hasFile('gl_file')) {
            foreach ($glArr as $key => $gl) {
                $rules['gl_file.' . $key] = 'max:' . $this->fileSize . '|mimes:pdf';
                $index = array_search($key, array_keys($request->buyer_id));
                $message['gl_file.' . $key . '.mimes'] = __('label.INVALID_FILE_FORMAT_FOR_ROW_NO') . ($index + 1);
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $data = [];
        //if (!empty($request->buyer_id)) {
        if ($request->hasFile('gl_file')) {
            if (!empty($glArr)) {
                foreach ($glArr as $key => $fileName) {
                    $fileNames = uniqid() . $key . "." . $fileName->getClientOriginalExtension();
                    $fileOriginalNames = $fileName->getClientOriginalName();
                    $uploadSuccess = $fileName->move('public/uploads/productToGl', $fileNames);
                    $data[$key]['gl_file'] = $fileNames;
                }
            }
        }
        if (!empty($request->buyer_id)) {
            foreach ($request->buyer_id as $key => $buyerId) {
                $data[$key]['buyer_id'] = $buyerId;
                $data[$key]['product_id'] = $request->product_id;
                $data[$key]['rsl'] = is_array($request->rsl) ? (array_key_exists($key, $request->rsl) ? '1' : '0') : '0';
                $data[$key]['mrsl'] = is_array($request->mrsl) ? (array_key_exists($key, $request->mrsl) ? '1' : '0') : '0';
                $data[$key]['version'] = !empty($request->version[$key]) ? $request->version[$key] : '';
                $data[$key]['date'] = !empty($request->date[$key]) ? $request->date[$key] : NULL;
                $data[$key]['gl_file'] = !empty($data[$key]['gl_file']) ? $data[$key]['gl_file'] : (!empty($request->gl_prev_file[$buyerId]) ? $request->gl_prev_file[$buyerId] : '');
            }
        }
        ProductToGl::where('product_id', $request->product_id)->delete();
        ProductToGl::insert($data);

        return Response::json(['success' => true], 200);
//        } else {
//            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.NO_GL_HAS_BEEN_ADDED')], 401);
//        }
    }

    public function savePl(Request $request) {
        $bplArr = $request->file('bpl_file');
        $mplArr = $request->file('mpl_file');

        $rules = $message = array();
        if (!empty($request->buyer_id)) {
            $row = 0;
            foreach ($request->buyer_id as $key => $buyerId) {
                $rules['buyer_id.' . $key] = 'required';
                $message['buyer_id.' . $key . '.required'] = __('label.BUYER_NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }
        if ($request->hasFile('bpl_file')) {
            foreach ($bplArr as $key => $bpl) {
                $rules['bpl_file.' . $key] = 'max:' . $this->fileSize . '|mimes:pdf';
                $index = array_search($key, array_keys($request->buyer_id));
                $message['bpl_file.' . $key . '.mimes'] = __('label.INVALID_FILE_FORMAT_FOR_ROW_NO') . ($index + 1);
            }
        }

        //For manufacturer positive List
        if (!empty($request->manufacturer_id)) {
            $row = 0;
            foreach ($request->manufacturer_id as $key => $manufacturerId) {
                $rules['manufacturer_id.' . $key] = 'required';
                $message['manufacturer_id.' . $key . '.required'] = __('label.MANUFACTURER_NAME_IS_REQUIRED_FOR_ROW_NO') . ($row + 1);
                $row++;
            }
        }

        if ($request->hasFile('mpl_file')) {
            foreach ($mplArr as $key => $mpl) {
                $rules['mpl_file.' . $key] = 'max:' . $this->fileSize . '|mimes:pdf';
                $index = array_search($key, array_keys($request->manufacturer_id));
                $message['mpl_file.' . $key . '.mimes'] = __('label.MANUFACTURER_INVALID_FILE_FORMAT_FOR_ROW_NO') . ($index + 1);
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $data = $mData = [];
        if ($request->hasFile('bpl_file')) {
            if (!empty($bplArr)) {
                foreach ($bplArr as $key => $fileName) {
                    $fileNames = uniqid() . $key . "." . $fileName->getClientOriginalExtension();
                    $fileOriginalNames = $fileName->getClientOriginalName();
                    $uploadSuccess = $fileName->move('public/uploads/buyerPositiveList', $fileNames);
                    $data[$key]['bpl_file'] = $fileNames;
                }
            }
        }
        //if (!empty($request->buyer_id) && !empty($request->manufacturer_id)) {
        if (!empty($request->buyer_id)) {
            foreach ($request->buyer_id as $key => $buyerId) {
                $data[$key]['buyer_id'] = $buyerId;
                $data[$key]['product_id'] = $request->product_id;
                $data[$key]['level'] = !empty($request->level[$key]) ? $request->level[$key] : '';
                $data[$key]['version'] = !empty($request->version[$key]) ? $request->version[$key] : '';
                $data[$key]['date'] = !empty($request->date[$key]) ? $request->date[$key] : NULL;
                $data[$key]['bpl_file'] = !empty($data[$key]['bpl_file']) ? $data[$key]['bpl_file'] : (!empty($request->bpl_prev_file[$buyerId]) ? $request->bpl_prev_file[$buyerId] : '');
            }
        }
        ProductToBpl::where('product_id', $request->product_id)->delete();
        ProductToBpl::insert($data);



        if ($request->hasFile('mpl_file')) {
            if (!empty($mplArr)) {
                foreach ($mplArr as $key => $fileName) {
                    $fileNames = uniqid() . $key . "." . $fileName->getClientOriginalExtension();
                    $fileOriginalNames = $fileName->getClientOriginalName();
                    $uploadSuccess = $fileName->move('public/uploads/mPositiveList', $fileNames);
                    $mData[$key]['mpl_file'] = $fileNames;
                }
            }
        }

        if (!empty($request->manufacturer_id)) {
            foreach ($request->manufacturer_id as $key => $manufacturerId) {
                $mData[$key]['manufacturer_id'] = $manufacturerId;
                $mData[$key]['product_id'] = $request->product_id;
                $mData[$key]['m_level'] = !empty($request->m_level[$key]) ? $request->m_level[$key] : '';
                $mData[$key]['m_version'] = !empty($request->m_version[$key]) ? $request->m_version[$key] : '';
                $mData[$key]['m_date'] = !empty($request->m_date[$key]) ? $request->m_date[$key] : NULL;
                $mData[$key]['mpl_file'] = !empty($mData[$key]['mpl_file']) ? $mData[$key]['mpl_file'] : (!empty($request->mpl_prev_file[$manufacturerId]) ? $request->mpl_prev_file[$manufacturerId] : '');
            }
        }
        ProductToMpl::where('product_id', $request->product_id)->delete();
        ProductToMpl::insert($mData);

        return Response::json(['success' => true], 200);
//        } else {
//            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.NO_POSITIVE_LIST_HAS_BEEN_ADDED')], 401);
//        }
    }

    public function savePpe(Request $request) {
        $ppeData = [];
        if (!empty($request->ppe_id)) {
            foreach ($request->ppe_id as $key => $ppeId) {
                $ppeData[$key]['product_id'] = $request->product_id;
                $ppeData[$key]['ppe_id'] = $ppeId;
            }
        }
        ProductToPpe::where('product_id', $request->product_id)->delete();
        ProductToPpe::insert($ppeData);

        return Response::json(['success' => true], 200);
//        } else {
//            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.NO_PPE_HAS_BEEN_SELECTED')], 401);
//        }
    }

    public function saveHazardCat(Request $request) {
        $hazardCatData = [];
        if (!empty($request->hazard_cat_id)) {
            foreach ($request->hazard_cat_id as $key => $catId) {
                $hazardCatData[$key]['product_id'] = $request->product_id;
                $hazardCatData[$key]['hazard_cat_id'] = $catId;
            }
        }
        ProductToHazardCat::where('product_id', $request->product_id)->delete();
        ProductToHazardCat::insert($hazardCatData);
        return Response::json(['success' => true], 200);

//        } else {
//            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.NO_HAZARD_CAT_HAS_BEEN_SELECTED')], 401);
//        }
    }

    public function saveSupplier(Request $request) {

        $supplierData = [];

        if (!empty($request->supplier_id)) {
            foreach ($request->supplier_id as $key => $supplierId) {
                $supplierData[$key]['product_id'] = $request->product_id;
                $supplierData[$key]['supplier_id'] = $supplierId;
                $supplierData[$key]['created_by'] = Auth::user()->id;
                $supplierData[$key]['created_at'] = date('Y-m-d h:i:s');
            }
        }
        ProductToSupplier::where('product_id', $request->product_id)->delete();
        ProductToSupplier::insert($supplierData);
        return Response::json(['success' => true], 200);

//        } else {
//            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.NO_PPE_HAS_BEEN_SELECTED')], 401);
//        }
    }

    public function saveManufacturer(Request $request) {

        $manufacturerData = [];

        if (!empty($request->manufacturer_id)) {
            foreach ($request->manufacturer_id as $key => $manufacturerId) {
                $manufacturerData[$key]['product_id'] = $request->product_id;
                $manufacturerData[$key]['manufacturer_id'] = $manufacturerId;
                $manufacturerData[$key]['created_by'] = Auth::user()->id;
                $manufacturerData[$key]['created_at'] = date('Y-m-d h:i:s');
            }
        }
        ProductToManufacturer::where('product_id', $request->product_id)->delete();
        ProductToManufacturer::insert($manufacturerData);
        return Response::json(['success' => true], 200);

//        } else {
//            return Response::json(['success' => false, 'heading' => __('label.ERROR'), 'message' => __('label.NO_PPE_HAS_BEEN_SELECTED')], 401);
//        }
    }
    
    public function loadProductNameCreate(Request $request) {
        return Common::loadProductName($request);
    }
    
    public function loadProductNameEdit(Request $request) {
        return Common::loadProductName($request);
    }
}
