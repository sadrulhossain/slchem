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

class ProductCheckInController extends Controller {

    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Checked In'];
    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
        , 1 => ['status' => 'Approved', 'label' => 'success']];
    private $sourceArr = [0 => ['status' => 'CheckIn', 'label' => 'warning']
        , 1 => ['status' => 'Initial Balance Set', 'label' => 'success']];

    public function create() {

        $productArr = ['0' => __('label.SELECT_PRODUCT_OPT')] + Product::orderBy('name', 'asc')->where('status', '1')->where('approval_status', 1)->pluck('name', 'id')->toArray();
        $purposeArr = ['0' => __('label.SELECT_PURPOSE_OPT')] + Department::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')];
        $manufacturerArr = ['0' => __('label.SELECT_MANUFACTURER_OPT')];
        $addressArr = ['0' => __('label.SELECT_ADDRESS_OPT')];
        $setCutOffTime = Configuration::select('check_in_time')->first();
        $purchaseTime = date('H:i:s');

        if (strtotime($purchaseTime) <= strtotime($setCutOffTime->check_in_time)) {
            $checkinDate = (date('Y-m-d', strtotime("-1 days")));
        } else {
            $checkinDate = date('Y-m-d');
        }
        $checkInArr = ProductCheckInMaster::select(DB::raw('count(id) as total'))->where('checkin_date', $checkinDate)->first();
        $checkIn = $checkInArr->total + 1;
        $referenceNo = 'PO-' . date('ymd', strtotime($checkinDate)) . str_pad($checkIn, 4, '0', STR_PAD_LEFT);

        return view('productCheckIn.purchase')->with(compact('productArr', 'supplierArr', 'manufacturerArr', 'referenceNo'
                                , 'purposeArr', 'addressArr', 'checkinDate', 'purchaseTime'));
    }

    public function getSupplierManufacturer(Request $request) {
        //product wise supplier
        $loadView = 'productCheckIn.showSupplierManufacturer';
        return Common::getSupplierManufacturer($request, $loadView);
    }

    public function getManufacturerAddress(Request $request) {
        //manufacturer wise address
        return Common::getManufacturerAddress($request);
    }

    public function purchaseNew(Request $request) {
        return Common::purchaseNew($request);
    }

    public function purchaseProduct(Request $request) {

        $rules['challan_no'] = 'required';
        $rules['product_id'] = 'required|not_in:0';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        /* data insertion into product check in master table */
        $target = new ProductCheckInMaster;
        $target->ref_no = $request->ref_no;
        $target->challan_no = $request->challan_no;
        $target->has_mlabel = !empty($request->m_label) ? '1' : '0';
        $target->msds = !empty($request->msds) ? '1' : '0';
        $target->factory_label = !empty($request->factory_label) ? '1' : '0';
        $target->source = '0';
        $target->checkin_date = $request->checkin_date;

        if (!empty($request->add_btn)) {
            
            DB::beginTransaction();
            try {
                if ($target->save()) {
                    $data = [];
                    $i = 0;

                    foreach ($request->product_id as $key => $productId) {
                        $data[$i]['master_id'] = $target->id;
                        $data[$i]['product_id'] = $productId;
                        $data[$i]['supplier_id'] = $request->supplier_id[$key];
                        $data[$i]['purpose'] = $request->purpose[$key];
                        $data[$i]['manufacturer_id'] = $request->manufacturer_id[$key];
                        $data[$i]['address_id'] = $request->address_id[$key];
                        $data[$i]['quantity'] = $request->quantity[$key];
                        $data[$i]['rate'] = $request->rate[$key];
                        $data[$i]['amount'] = $request->amount[$key];
                        $data[$i]['lot_number'] = $request->lot_number[$key];
                        $data[$i]['remaining_quantity'] = $request->quantity[$key];
                        $data[$i]['consumed'] = '0';
                        $i++;
                    }

                    /* insert data into product check in details table */
                    $insertNewProductCheckInInfo = ProductCheckInDetails::insert($data);

                    if (!$insertNewProductCheckInInfo) {//If failed to Insert in Consumption Details Table, Roll Back Insertion operation of Master Table
                        //ProductConsumptionMaster::where('id', $target->id)->delete();
                        DB::rollback();
                        return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                    } else {
                        $productDetails = ProductCheckInDetails::where('master_id', $target->id)->lockForUpdate()->get();
                        if (!empty($productDetails)) {
                            foreach ($productDetails as $item) {
                                Product::where('id', $item->product_id)->increment('available_quantity', $item->quantity);
                                Product::where('id', $item->product_id)->update(array('initiated' => '1'));
                            }
                        } else {
                            DB::rollback();
                            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
                            //$error .= __('label.QUANTITY_IS_ALREADY_CONSUMED_FOR') . $availableArr[$data['product_id']]['name']. '<br />';
                        }
                        DB::commit();
                        return Response::json(['success' => true], 200);
                    }
                } //EOF-IF Target->SAVE()
            } catch (\Throwable $e) {
                DB::rollback();
                //print_r($e->getMessage());
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.DATA_COULD_NOT_BE_SAVE')], 401);
            }
        } else {
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.YOU_HAVE_NO_NEW_PRODUCT_FOR_SAVE')], 401);
        }
    }

    public function productHints(Request $request) {
        return Common::productHints($request);
    }

}
