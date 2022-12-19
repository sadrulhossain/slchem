<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Process;
use App\ProductToProcess;
use App\Manufacturer;
use App\Supplier;
use Response;
use Auth;
use Illuminate\Http\Request;

class ProductToProcessController extends Controller {

    public function index() {
        $processArr = array('0' => __('label.SELECT_PROCESS_OPT')) + Process::where('status',1)
                ->where('process_type_id', '1')
                ->orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        return view('productToProcess.index')->with(compact('processArr'));
    }

    public function getProducts(Request $request) {

         $manufacturerArr = Manufacturer::pluck('name','id')->toArray();
         $supplierArr = Supplier::pluck('name','id')->toArray();

         //check process for water type
        $checkProcess = Process::find($request->process_id);

        $productArr = Product::with('productToManufacturer','productToSupplier')
            ->join('product_category', 'product_category.id', 'product.product_category_id')
            ->select('product.*', 'product_category.name as category_name')
            ->where('product.status','1')
            ->where('product.approval_status',1)
            ->orderBy('product.name', 'asc')->get();

        $previousDataArr = ProductToProcess::select('product_to_process.*')
            ->where('product_to_process.process_id', $request->process_id)
            ->get()->toArray();
        $view = view('productToProcess.showProducts', compact('productArr', 'previousDataArr','supplierArr','manufacturerArr'))->render();
        return response()->json(['html' => $view,'checkProcess' => $checkProcess->water] );
    }

    public function saveProducts(Request $request) {
        
        $productIdArr = $request->product_id;
        
        $rules = array();
        $rules['process_id'] = 'required'; 
        $rules['product_id'] = 'required';
        

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

         //delete before insert value
        ProductToProcess::where('process_id', $request->process_id)->delete();
        
        $data = array();
        $i = 0;
        if (!empty($productIdArr)) {
            foreach ($productIdArr as $product) {

                $data[$i]['product_id'] = $product;
                $data[$i]['process_id'] = $request->process_id;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }
        
        if (!empty($data)) {
            ProductToProcess::insert($data);
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_HAS_BEEN_RELATED_TO_PROCESS_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.NO_PRODUCT_HAS_BEEN_RELATED_TO_PROCESS')), 401);
        }
    }

}
