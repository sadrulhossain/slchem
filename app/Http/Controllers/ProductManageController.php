<?php

namespace App\Http\Controllers;

use Validator;
use App\ProductToCas;
use App\ProductToEc;
use App\SubstanceCas;
use App\SubstanceEc;
use App\ProductToCertificate;
use App\ProductToGl;
use App\ProductToBpl;
use App\ProductToMpl;
use App\ProductToPpe;
use App\ProductToHazardCat;
use App\ProductToSupplier;
use App\ProductToManufacturer;
use Auth;
use Session;
use Redirect;
use Helper;
use Response;
use Illuminate\Http\Request;

class ProductManageController extends Controller {

    private $controller = 'ProductManage';
    private $fileSize = '10240';

    public function saveSubstance(Request $request) {
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

        $data = [];
        if ($request->hasFile('certificate_file')) {
            if (!empty($certificateArr)) {
                foreach ($certificateArr as $key => $fileName) {
                    $fileNames = uniqid() . $key . "." . $fileName->getClientOriginalExtension();
                    $fileOriginalNames = $fileName->getClientOriginalName();
                    $uploadSuccess = $fileName->move('public/uploads/productToCertificate', $fileNames);
                    $data[$key]['certificate_file'] = $fileNames;
                }
            }
        }
        if (!empty($request->certificate_id)) {
            foreach ($request->certificate_id as $key => $certificateId) {
                $data[$key]['certificate_id'] = $certificateId;
                $data[$key]['product_id'] = $request->product_id;
                $data[$key]['remarks'] = !empty($request->remarks[$key]) ? $request->remarks[$key] : '';
                $data[$key]['certificate_file'] = !empty($data[$key]['certificate_file']) ? $data[$key]['certificate_file'] : (!empty($request->certificate_prev_file[$certificateId]) ? $request->certificate_prev_file[$certificateId] : '');
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
                $rules['bpl_file.' . $key] = 'max:'.$this->fileSize.'|mimes:pdf';
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
                $rules['mpl_file.' . $key] = 'max:'.$this->fileSize.'|mimes:pdf';
                $index = array_search($key, array_keys($request->buyer_id));
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

}
