<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\ProductToSupplier;
use App\ProductToManufacturer;
use App\ProductCheckInMaster;
use App\ProductCheckInDetails;
use App\Department;
use App\MfAddressBook;
use App\Configuration;
use Response;
use Common;
use DB;
use Redirect;
use Session;
use Auth;
use Illuminate\Http\Request;

class ProductCheckInListController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Checked In'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];
    private $sourceArr = [0 => ['status' => 'CheckIn', 'label' => 'warning']
        , 1 => ['status' => 'Initial Balance Set', 'label' => 'success']];


    public function index(Request $request) {
        $qpArr = $request->all();
        $challanNoArr = ProductCheckInMaster::select('challan_no')->orderBy('id', 'asc')->get();
        $refNoArr = ProductCheckInMaster::select('ref_no')->orderBy('id', 'asc')->get();
        $targetArr = ProductCheckInMaster::join('users', 'users.id', '=', 'product_checkin_master.created_by');

        //begin filtering
        if (!empty($request->ref_no)) {
            $targetArr = $targetArr->where('product_checkin_master.ref_no', 'LIKE', '%' . $request->ref_no . '%');
        }

        if (!empty($request->challan_no)) {
            $targetArr = $targetArr->where('product_checkin_master.challan_no', '=', $request->challan_no);
        }

        if (!empty($request->checkin_date)) {
            $targetArr = $targetArr->where('product_checkin_master.checkin_date', '=', $request->checkin_date);
        }
        //end filtering

        $targetArr = $targetArr->select('product_checkin_master.*', 'users.first_name', 'users.last_name')
                        ->orderBy('product_checkin_master.checkin_date', 'desc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/productcheckinlist?page=' . $page);
        }

        return view('productCheckIn.checkInList')->with(compact('targetArr', 'productArr', 'challanNoArr', 'refNoArr'));
    }

    public function filter(Request $request) {
        $url = 'checkin_date=' . $request->checkin_date . '&ref_no=' . $request->ref_no
                . '&challan_no=' . $request->challan_no;
        return Redirect::to('productCheckInList?' . $url);
    }

    public function getProductDetails(Request $request) {
        $target = ProductCheckInMaster::join('users', 'users.id', '=', 'product_checkin_master.created_by')
                        ->select('product_checkin_master.*', 'users.first_name', 'users.last_name')
//                        ->where('product_checkin_master.ref_no', $request->ref_no)
                        ->where('product_checkin_master.id', $request->master_id)->first();

        $targetArr = ProductCheckInDetails::where('master_id', $target['id'])
                        ->join('product', 'product.id', '=', 'product_checkin_details.product_id')
                        ->join('department', 'department.id', '=', 'product_checkin_details.purpose')
                        ->join('measure_unit', 'measure_unit.id', '=', 'product.primary_unit_id')
                        ->join('supplier', 'supplier.id', '=', 'product_checkin_details.supplier_id')
                        ->join('manufacturer', 'manufacturer.id', '=', 'product_checkin_details.manufacturer_id')
                        ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name as product_name', 'product_category.name as category_name'
                                , 'supplier.name as supplier_name', 'supplier.address as saddress'
                                , 'manufacturer.name as manufacturer_name', 'manufacturer.address as maddress'
                                , 'product_checkin_details.lot_number', 'department.name as purpose'
                                , 'product_checkin_details.quantity', 'product_checkin_details.rate'
                                , 'measure_unit.name as unit_name'
                        )->get();

        $statusArr = $this->viewStatusArr;
        $sourceArr = $this->sourceArr;

        $view = view('productCheckIn.productDetails', compact('targetArr', 'target', 'statusArr', 'sourceArr'))->render();
        return response()->json(['html' => $view]);
    }

}
