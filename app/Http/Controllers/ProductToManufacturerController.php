<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Manufacturer;
use App\ProductToManufacturer;
use Response;
use Auth;
use Illuminate\Http\Request;

class ProductToManufacturerController extends Controller {

    public function index() {
        $manufacturerArr = array('0' => __('label.SELECT_MANUFACTURER_OPT')) + Manufacturer::where('status',1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        return view('productToManufacturer.index')->with(compact('manufacturerArr'));
    }

    public function getProducts(Request $request) {
        
        $productArr = Product::join('product_category', 'product_category.id', 'product.product_category_id')
                ->select('product.*', 'product_category.name as category_name')
                ->where('product.status','1')
                ->where('product.approval_status',1)
                ->orderBy('name', 'asc')
                ->get();
        
        $previousDataArr = ProductToManufacturer::select('product_to_manufacturer.*')
                        ->where('product_to_manufacturer.manufacturer_id', $request->manufacturer_id)
                        ->get()->toArray();

        $view = view('productToManufacturer.showProducts', compact('productArr', 'previousDataArr', 'qpArr'))->render();
        return response()->json(['html' => $view]);
    }

    public function saveProducts(Request $request) {
        
        
        $productIdArr = $request->product_id;
        
        $rules = array();
        $rules['manufacturer_id'] = 'required'; 
        $rules['product_id'] = 'required';
        

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        //delete before inserted value with manufacturer_id
        ProductToManufacturer::where('manufacturer_id', $request->manufacturer_id)->delete();
        
        $data = array();
        $i = 0;
        if (!empty($productIdArr)) {
            foreach ($productIdArr as $product) {

                $data[$i]['product_id'] = $product;
                $data[$i]['manufacturer_id'] = $request->manufacturer_id;
                $data[$i]['created_by'] = Auth::user()->id;
                $data[$i]['created_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }
        
        if (!empty($data)) {
            ProductToManufacturer::insert($data);
            return Response::json(array('heading' => 'Success', 'message' => __('label.PRODUCT_HAS_BEEN_RELATED_TO_MANUFACTURER_SUCCESSFULLY')), 201);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.NO_PRODUCT_HAS_BEEN_RELATED_TO_MANUFACTURER')), 401);
        }
    }

}
