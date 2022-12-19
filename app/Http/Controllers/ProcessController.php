<?php

namespace App\Http\Controllers;

use App\ProductToProcess;
use App\RecipeToProcess;
use Validator;
use App\ProcessType;
use App\Process;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class ProcessController extends Controller {

    private $controller = 'Process';

    public function __construct() {

        Validator::extend('duplicateProcess', function($attribute, $value, $parameters) {
            $processId = $parameters[1];

            $checkProductToProcess = ProductToProcess::where('process_id', $processId)->first();

            $checkRecipeToProcess = RecipeToProcess::where('process_id', $processId)->first();

            if (empty($checkProductToProcess) || empty($checkRecipeToProcess)){
                return true;
            }
            return false;

        });
    }

    public function index(Request $request) {
        
        //passing param for custom function
        $qpArr = $request->all();
        $nameArr = Process::select('name')->orderBy('name','asc')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        $processTypeList = array('0' => __('label.SELECT_PROCESS_TYPE_OPT')) + ProcessType::pluck('name', 'id')->toArray();
        
        $targetArr = Process::join('process_type','process_type.id', '=', 'process.process_type_id')
                ->select('process.*','process_type.name as process_type')
                ->orderBy('process_type.order', 'asc')
                ->orderBy('process.order', 'asc');
        
        
        
        //begin filtering
        $searchText = $request->search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('process.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->status)){
            $targetArr = $targetArr->where('process.status' , '=' ,$request->status);
        }
        if(!empty($request->process_type_id)){
            $targetArr = $targetArr->where('process.process_type_id' , '=' ,$request->process_type_id);
        }
        //end filtering
        
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        
        //echo '<pre>';
        //print_r($targetArr->toArray());
        //exit;
        

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/process?page=' . $page);
        }

        return view('process.index')->with(compact('targetArr', 'qpArr', 'status','nameArr','processTypeList'));
    }

    public function create(Request $request) { 
        
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $processTypeList = array('0' => __('label.SELECT_PROCESS_TYPE_OPT')) + ProcessType::pluck('name', 'id')->toArray();
        
        return view('process.create')->with(compact('qpArr', 'orderList','processTypeList'));
    }

    public function store(Request $request) {
        
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:process',
                    'process_type_id' => 'required|not_in:0',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('process/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Process;
        $target->name = $request->name;
        $target->process_type_id = $request->process_type_id;
        $target->description = $request->description;
        $target->order = 0;
        $target->water = empty($request->water) ? '0' : '1';
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.PROCESS_CREATED_SUCCESSFULLY'));
            return redirect('process');
        } else {
            Session::flash('error', __('label.PROCESS_COULD_NOT_BE_CREATED'));
            return redirect('process/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Process::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('process');
        }

        //passing param for custom function
        $qpArr = $request->all();
        
        $processTypeList = array('0' => __('label.SELECT_PROCESS_TYPE_OPT')) + ProcessType::pluck('name', 'id')->toArray();

        return view('process.edit')->with(compact('target', 'qpArr', 'orderList','processTypeList'));
    }

    public function update(Request $request, $id) {

        $target = Process::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        //end back same page after update
        $rules = $messages = [];
        $rules = [
            'name' => 'required|unique:process,name,' . $id,
            'process_type_id' => 'required|not_in:0',
            'order' => 'required|not_in:0'
        ];

        if(($target->water) != ($request->water)){
            $rules = [
                'water' => 'duplicate_process:,' . $id,
            ];

        }

        $messages = array(
            'water.duplicate_process' => __('label.COULD_NOT_CHANGE_WATER_IT_ALREADY_ADDED_WITH_PRODUCT_AND_RECIPE'),
        );



        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('process/' . $id . '/edit' . $pageNumber)
                            ->withInput()->withErrors($validator);
        }

        $target->name = $request->name;
        $target->process_type_id = $request->process_type_id;
        $target->description = $request->description;
        $target->order = $request->order;
        $target->status = $request->status;
        $target->water = $request->water;


        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.PROCESS_UPDATED_SUCCESSFULLY'));
            return redirect('process' . $pageNumber);
        } else {
            Session::flash('error', __('label.PROCESS_COULD_NOT_BE_UPDATED'));
            return redirect('process/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Process::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        //dependency
        $dependencyArr = [
            'ProductToProcess' => ['1' => 'process_id'],
            'Recipe' => ['1' => 'process_id']
        ];
        foreach ($dependencyArr as $model => $val) {
            foreach ($val as $index => $key) {
                $namespacedModel = '\\App\\' . $model;
                $dependentData = $namespacedModel::where($key, $id)->first();
                if (!empty($dependentData)) {
                    Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model );
                    return redirect('process' . $pageNumber);
                }
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.PROCESS_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PROCESS_COULD_NOT_BE_DELETED'));
        }
        return redirect('process' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status . '&process_type_id=' . $request->process_type_id;
        return Redirect::to('process?' . $url);
    }

}
