<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Supplier;
use App\ProductToSupplier;
use Response;
use Auth;
use Illuminate\Http\Request;

class ProductToSupplierController extends Controller {

    public function index() {
        $supplierArr = array('0' => __('label.SELECT_SUPPLIER_OPT')) + Supplier::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        return view('productToSupplier.index')->with(compact('supplierArr'));
    }

    public function getProducts(Request $request) {
        $productArr = Product::join('product_category', 'product_category.id', 'product.product_category_id')
                        ->select('product.*', 'product_category.name as category_name')
                        ->where('product.status', '1')
                        ->where('product.approval_status', 1)
                        ->orderBy('id', 'asc')->get();
        $previousDataArr = ProductToSupplier::select('product_to_supplier.*')
                        ->where('product_to_supplier.supplier_id', $request->supplier_id)
                        ->get()->toArray();

        $view = view('productToSupplier.showProducts', compact('productArr', 'previousDataArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function saveProducts(Request $request) {

        $productIdArr = $request->product_id;

        $rules = array();
        $rules['supplier_id'] = 'required';
        $rules['product_id'] = 'required';


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        //delete before inserted value with year wise
        ProductToSupplier::where('supplier_id', $request->supplier_id)->delete();

        $data = array();
        $i = 0;
        if (!empty($productIdArr)) {
            foreach ($productIdArr as $product) {

                $data[$i]['product_id'] = $product;
                $data[$i]['supplier_id'] = $request->supplier_id;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }


        if (!empty($data)) {
            ProductToSupplier::insert($data);
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_HAS_BEEN_RELATED_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.NO_PRODUCT_HAS_BEEN_RELATED_TO_SUPPLIER')), 401);
        }
    }

}
