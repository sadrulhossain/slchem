<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use Redirect;
use App\Product;
use App\Supplier;
use App\Manufacturer;
use App\ProductToSupplier;
use App\ProductToManufacturer;
use App\ProductCheckInDetails;
use App\HazardCategory;
use App\Ppe;
use App\Buyer;
use App\Certificate;
use App\ProductCheckInMaster;
use App\SubstanceCas;
use App\SubstanceEc;
use App\Department;
use App\HazardClass;
use App\ProductCategory;
use Helper;
use Common;
use DB;
use Auth;
use Input;
use PDF;
use Excel;
use Illuminate\Http\Request;

class ComplianceReportController extends Controller {

//Stock Summary Report
    public function index(Request $request) {
        $productCategoryArr = array('0' => __('label.SELECT_PRODUCT_CATEGORY_OPT')) + ProductCategory::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $productArr = array('0' => __('label.SELECT_PRODUCT_OPT')) + Product::orderBy('name', 'asc')->where('product_category_id',$request->product_category_id)->where('approval_status',1)->where('status', '1')->pluck('name', 'id')->toArray();
        $hazardCategoryArr = HazardCategory::pluck('name', 'id')->toArray();
        $supplierArr = Supplier::pluck('name', 'id')->toArray();
        $manufacturerArr = Manufacturer::pluck('name', 'id')->toArray();
        $buyerArr = Buyer::pluck('name', 'id')->toArray();
        $ppeArr = Ppe::pluck('name', 'id')->toArray();
        $certificateArr = Certificate::pluck('name', 'id')->toArray();
        $departmentArr = Department::pluck('name', 'id')->toArray();
        $hazardClassArr = HazardClass::pluck('name', 'hazard_cat_id')->toArray();
        $approvalStatusArr = ['' => __('label.SELECT_APPROVAL_STATUS_OPT'), '0' => 'Waiting For Approval', '1' => 'Approved'];
        $casNameArr = SubstanceCas::pluck('cas_name', 'cas_no')->toArray();
        $ecNameArr = SubstanceEc::pluck('ec_name', 'ec_no')->toArray();
        // After given generate button
        //if ($request->generate == 'true') {
        $targetArr = Product::with('productToManufacturer', 'productToSupplier', 'productToPpe'
                        , 'ProductToCertificate', 'ProductToBpl', 'ProductToMpl', 'productToGl'
                        , 'productToCheckInDetails', 'productToHazardCat', 'productToCas', 'productToEc')
                ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                ->leftJoin('product_function', 'product_function.id', '=', 'product.product_function_id')
                ->join('measure_unit', 'measure_unit.id', '=', 'product.primary_unit_id')
                ->where('product.status','1')->where('product.approval_status', 1);

        //echo '<pre>';print_r($targetArr);exit;

        if (!empty($request->product_id)) {
            $targetArr = $targetArr->where('product.id', $request->product_id);
        }

        if (!empty($request->product_category_id)) {
            $targetArr = $targetArr->where('product.product_category_id', $request->product_category_id);
        }

        $targetArr = $targetArr->select('product.name as product', 'product.*', 'product_function.name as funciton'
                                , 'measure_unit.name as unit_name', 'product_category.name as product_category')
                        ->orderBy('product', 'asc')->get();
//        echo '<pre>';print_r($targetArr->toArray());exit;
        $glIndex = ['rsl', 'mrsl'];
        $productToGlArr = [];
        
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $item) {
                if (!$item->productToGl->isEmpty()) {
                    foreach ($item->productToGl as $gl) {
                        $rsl[$item->id][$gl->buyer_id] = !empty($rsl[$item->id][$gl->buyer_id]) ? $rsl[$item->id][$gl->buyer_id] : 0;
                        $rsl[$item->id][$gl->buyer_id] += !empty($gl->rsl) ? 1 : 0;
                        
                        $mrsl[$item->id][$gl->buyer_id] = !empty($mrsl[$item->id][$gl->buyer_id]) ? $mrsl[$item->id][$gl->buyer_id] : 0;
                        $mrsl[$item->id][$gl->buyer_id] += !empty($gl->mrsl) ? 1 : 0;
                        
                        $productToGlArr[$item->id][$gl->buyer_id]['rsl'] = ($rsl[$item->id][$gl->buyer_id] != 0) ? 1 : 0;
                        $productToGlArr[$item->id][$gl->buyer_id]['mrsl'] = ($mrsl[$item->id][$gl->buyer_id] != 0) ? 1 : 0;
                    }
                }
            }
        }

        $productToHazardCatArr = [];
        if (!$targetArr->isEmpty()) {
            foreach ($targetArr as $item) {
                if (!$item->productToHazardCat->isEmpty()) {
                    foreach ($item->productToHazardCat as $hazardCat) {
                        $productToHazardCatArr[$item->id][$hazardCat->hazard_cat_id] = $hazardCat->hazard_cat_id;
                    }
                }
            }
        }
        //echo '<pre>';print_r($productToHazardCatArr);exit;
        //}
		
        $userAccessArr = Common::userAccess();
		
        if ($request->view == 'pdf') {
            if(empty($userAccessArr[60][17])){
                return redirect('dashboard');
            }
            $pdf = PDF::loadView('complianceReport.print.compliance', compact('request', 'targetArr', 'productArr', 'hazardCategoryArr'
                                    , 'supplierArr', 'manufacturerArr', 'ppeArr', 'buyerArr', 'certificateArr', 'hazardClassArr'
                                    , 'departmentArr', 'productToGlArr', 'glIndex', 'productToHazardCatArr', 'approvalStatusArr'
                                    ,'casNameArr','ecNameArr','productCategoryArr'))
                    ->setPaper('a1', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);

            return $pdf->download('product_compliance_report.pdf');
        } elseif ($request->view == 'excel') {
            if(empty($userAccessArr[60][17])){
                return redirect('dashboard');
            }
            $this->downloadExcel($request, $targetArr, $productArr, $hazardCategoryArr, $supplierArr, $manufacturerArr, $ppeArr, $buyerArr
                    , $certificateArr, $productToGlArr, $glIndex, $productToHazardCatArr, $hazardClassArr, $departmentArr,$casNameArr,$ecNameArr);
        } else {
            return view('complianceReport.compliance')->with(compact('request', 'targetArr', 'productArr', 'hazardCategoryArr'
                                    , 'supplierArr', 'manufacturerArr', 'ppeArr', 'buyerArr', 'certificateArr', 'hazardClassArr'
                                    , 'departmentArr', 'productToGlArr', 'glIndex', 'productToHazardCatArr', 'approvalStatusArr'
                                    ,'casNameArr','ecNameArr','productCategoryArr'));
        }
    }

    public function filter(Request $request) {
        return redirect('complianceReport?generate=true&product_id='
                . $request->product_id . '&product_category_id=' . $request->product_category_id);
    }

    private function downloadExcel($request, $targetArr, $productArr
    , $hazardCategoryArr, $supplierArr, $manufacturerArr, $ppeArr, $buyerArr
    , $certificateArr, $productToGlArr, $glIndex, $productToHazardCatArr, $hazardClassArr
    , $departmentArr,$casNameArr,$ecNameArr) {
        return Excel::create('Product_Compliance_Report', function($excel) use ($targetArr, $productArr
                        , $hazardCategoryArr, $supplierArr, $manufacturerArr, $ppeArr, $buyerArr
                        , $certificateArr, $productToGlArr, $glIndex, $productToHazardCatArr, $hazardClassArr
                        , $departmentArr,$casNameArr,$ecNameArr) {
                    $excel->sheet('mySheet', function($sheet) use ($targetArr, $productArr, $hazardCategoryArr
                            , $supplierArr, $manufacturerArr, $ppeArr, $buyerArr, $certificateArr
                            , $productToGlArr, $glIndex, $productToHazardCatArr, $hazardClassArr, $departmentArr,$casNameArr,$ecNameArr) {
                        $cellIndex = [];
                        $chr = 65;
                        $prefix = '';
                        $prefixCount = 0;
                        for ($i = 0; $i <= 500; $i++) {

                            if ($chr > 90) {
                                $chr = 65;
                                $prefix = chr(65 + $prefixCount);
                                $prefixCount++;
                            }

                            $cellIndex[$i] = $prefix . chr($chr);
                            $chr++;
                        }

                        $hIndex = 0;
                        $headerArr = [
                            0 => __('label.SL_NO'),
                            1 => __('label.CHEMICAL_NAME_EXCEL'),
                            2 => __('label.MANUFACTURER'),
                            3 => __('label.SUPPLIER'),
                            4 => __('label.SDS'),
                            5 => __('label.TDS'),
                            6 => __('label.SDS_VERSION'),
                            7 => __('label.DATE'),
							8 => __('label.CAS_NO_EINCES_NO_OF_THE_INGREDIENTS_EXCEL'),
                        ];

                        //$sheet->getColumnDimension()->setAutoSize(true);
                        foreach ($headerArr as $item) {
                            $sheet->mergeCells($cellIndex[$hIndex] . '1:' . $cellIndex[$hIndex] . '2');
                            $sheet->cell($cellIndex[$hIndex] . '1', function($cell) use($item) {
                                $cell->setValue($item)
                                        ->setAlignment('center')
                                        ->setValignment('center');
                            });
                            $hIndex++;
                        }//foreach
                        
                        foreach ($buyerArr as $buyerId => $buyerName) {
                            $nextIndex = $hIndex + 1;
                            $sheet->mergeCells($cellIndex[$hIndex] . '1:' . $cellIndex[$nextIndex] . '1');

                            $sheet->cell($cellIndex[$hIndex] . '1', function($cell) use($buyerName) {
                                $cell->setValue($buyerName)->setAlignment('center')
                                        ->setValignment('center');
                            });
                            $hIndex+=2;
                        }

                        //HAZARD CLASSIFICATION HEADER 1
                        $nextIndex = $hIndex + ((count($hazardCategoryArr) * 2) - 1);

                        $sheet->mergeCells($cellIndex[$hIndex] . '1:' . $cellIndex[$nextIndex] . '1');
                        $sheet->cell($cellIndex[$hIndex] . '1', function($cell) {
                            $cell->setValue(__('label.HAZARD_CLASSIFICATION'))
                                    ->setAlignment('center')
                                    ->setValignment('center');
                        });

                        $hIndex = $nextIndex + 1;
                        $header1Arr = [
                            0 => __('label.FUNCTION'),
                            1 => __('label.AREA_OF_USE_EXCEL'),
                            2 => __('label.PPE'),
                            3 => __('label.STORAGE_CONDITION_RECOMMENDED_IN_MSDS_EXCEL'),
                            4 => __('label.LOCATION_OF_STORAGE_EXCEL'),
                            5 => __('label.BATCH_REMARKS')
                        ];
                        foreach ($header1Arr as $item) {
                            $sheet->mergeCells($cellIndex[$hIndex] . '1:' . $cellIndex[$hIndex] . '2');
                            $sheet->cell($cellIndex[$hIndex] . '1', function($cell) use($item) {
                                $cell->setValue($item)
                                        ->setAlignment('center')
                                        ->setValignment('center');
                            });
                            $hIndex++;
                        }//foreach

                        /* PREPARE HEADER 2 */
                        $hIndex = 9;
                        $header2Arr = [0 => __('label.RSL'), 1 => __('label.MRSL')];
                        foreach ($buyerArr as $buyerId => $buyerName) {
                            foreach ($header2Arr as $item) {
                                $sheet->cell($cellIndex[$hIndex] . '2', function($cell) use($item) {
                                    $cell->setValue($item)
                                            ->setAlignment('center')
                                            ->setValignment('center');
                                });
                                $hIndex++;
                            }
                        }


                        foreach ($hazardCategoryArr as $hazardCatId => $hazardCatValue) {
                            $header2Arr = [0 => $hazardCatValue, 1 => __('label.IF_YES_PLEASE_SPECIFIC_HAZARD_TYPE_EXCEL')];
                            foreach ($header2Arr as $item) {
                                $sheet->cell($cellIndex[$hIndex] . '2', function($cell) use($item) {
                                    $cell->setValue($item)
                                            ->setAlignment('center')
                                            ->setValignment('center');
                                });
                                $hIndex++;
                            }
                        }
                        /* END HEADER 2 */

                        /* DATA */
                        $sl = 1;
                        $vIndex = 3;
                        foreach ($targetArr as $target) {
                            //echo '<pre>'; print_r($target->toArray());exit;
                            $hIndex = 0;
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $sl++);
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $target->product);

                            //prepare Manufacturer
                            $manufacturerName = '';
                            $comma = ' , ';
                            $i = 1;
                            if (!$target->productToManufacturer->isEmpty()) {
                                foreach ($target->productToManufacturer as $manufacturer) {
                                    $manufacturerName .= $manufacturerArr[$manufacturer->manufacturer_id];
                                    if ((count($target->productToManufacturer)) > 1) {
                                        $manufacturerName .= $comma;
                                    }//if
                                    $i++;
                                    if (count($target->productToManufacturer) == $i) {
                                        $comma = '';
                                    }
                                }//foreach
                            }//if
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $manufacturerName);

                            //prepare supplier
                            $supplierName = '';
                            $comma = ' , ';
                            $i = 1;
                            if (!$target->productToSupplier->isEmpty()) {
                                foreach ($target->productToSupplier as $supplier) {
                                    $supplierName .= $supplierArr[$supplier->supplier_id];

                                    if (count($target->productToSupplier) > 1) {
                                        $supplierName .= $comma;
                                    }
                                    $i++;
                                    if ((count($target->productToSupplier)) == $i) {
                                        $comma = '';
                                    }
                                }
                            }

                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $supplierName);
                            //SDS
//                            dd($target);
                            if ($target->sds == '1') {
                                $sheet->cell($cellIndex[$hIndex++] . $vIndex, 'Y');
                            } else {
                                $sheet->cell($cellIndex[$hIndex++] . $vIndex, 'N');
                            }

                            //TDS
                            if ($target->tds == '1') {
                                $sheet->cell($cellIndex[$hIndex++] . $vIndex, 'Y');
                            } else {
                                $sheet->cell($cellIndex[$hIndex++] . $vIndex, 'N');
                            }
                            //SDS Version
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $target->sds_version);
							$sheet->cell($cellIndex[$hIndex++] . $vIndex, $target->date);

                            //CAS EC
                            $casNo = '';
                            $comma = ', ';
                            $i = 1;
                            if (!$target->productToCas->isEmpty()) {
                                foreach ($target->productToCas as $cas) {
                                    $casNo .= array_key_exists($cas->cas_no, $casNameArr) ? $cas->cas_no.' ('.$casNameArr[$cas->cas_no].')' : '';
                                    if (count($target->productToCas) > 1) {
                                        $casNo .= $comma;
                                    }
                                    $i++;
                                    if ((count($target->productToCas)) == $i) {
                                        $comma = '';
                                    }
                                }
                            }
                            $ecNo = '';
                            $comma = ', ';
                            $i = 1;
                            if (!$target->productToEc->isEmpty()) {
                                foreach ($target->productToEc as $ec) {
                                    $ecNo .= array_key_exists($ec->ec_no, $ecNameArr) ? $ec->ec_no.' ('.$ecNameArr[$ec->ec_no].')':'';
                                    if (count($target->productToEc) > 1) {
                                        $ecNo .=$comma;
                                    }
                                    $i++;
                                    if ((count($target->productToEc)) == $i) {
                                        $comma = '';
                                    }
                                }
                            }

                            
                            $casAndEcNo = '';
                            if (!empty($casNo) && !empty($ecNo)) {
                                $casAndEcNo = __('label.CAS') . ': '.$casNo . ' + ' .__('label.EC') . ': '. $ecNo;
                            }else if(!empty($casNo)){
                                $casAndEcNo = __('label.CAS') . ': '.$casNo;
                            }else if(!empty($ecNo)){
                                $casAndEcNo = __('label.EC') . ': '.$ecNo;
                            }
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $casAndEcNo);

                            //buyer rsl & mrsl
                            foreach ($buyerArr as $buyerId => $buyerName) {
                                foreach ($glIndex as $index) {
                                    if (isset($productToGlArr[$target->id])) {
                                        if (isset($productToGlArr[$target->id][$buyerId])) {
                                            //dd($productToGlArr[$target->id][$buyerId]);
                                            if ($productToGlArr[$target->id][$buyerId][$index] == '1') {
                                                $sheet->cell($cellIndex[$hIndex++] . $vIndex, 'Y');
                                            } else {
                                                $sheet->cell($cellIndex[$hIndex++] . $vIndex, 'N');
                                            }
                                        } else {
                                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, 'N');
                                        }
                                    } else {
                                        $sheet->cell($cellIndex[$hIndex++] . $vIndex, 'N/A');
                                    }
                                }
                            }

                            //HAZARD CATAGORY
                            if (!empty($hazardCategoryArr)) {
                                foreach ($hazardCategoryArr as $catId => $catName) {
                                    if (isset($productToHazardCatArr[$target->id])) {
                                        if (in_array($catId, $productToHazardCatArr[$target->id])) {
                                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, __('label.YES'));
                                        } else {
                                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, __('label.NO'));
                                        }
                                    } else {
                                        $sheet->cell($cellIndex[$hIndex++] . $vIndex, __('label.NO'));
                                    }
                                    if (isset($productToHazardCatArr[$target->id])) {
                                        if (in_array($catId, $productToHazardCatArr[$target->id])) { {
                                                $sheet->cell($cellIndex[$hIndex++] . $vIndex, $hazardClassArr[$catId]);
                                            }
                                        } else {
                                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, __('label.BLANK'));
                                        }
                                    } else {
                                        $sheet->cell($cellIndex[$hIndex++] . $vIndex, __('label.BLANK'));
                                    }
                                }
                            }

                            //function
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $target->funciton);

                            //Local of use
                            $purpose = '';
                            if (!empty($target->productToCheckInDetails)) {
//                                dd($target->productToCheckInDetails);
                                foreach ($target->productToCheckInDetails as $checkInData) {
                                    if ($checkInData->purpose != 0) {
                                        $purpose .= $departmentArr[$checkInData->purpose];
                                    } else {
                                        $purpose .= '';
                                    }
                                    if (count($target->productToCheckInDetails) > 1) {
                                        $purpose .= ',';
                                    }
                                }
                            }
                            //dd($checkInData->purpose);
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $purpose);

                            //PPE
                            $ppeName = '';
                            $plus = ' + ';
                            $i = 1;
                            if (!empty($target->productToPpe)) {
                                foreach ($target->productToPpe as $ppe) {
                                    $ppeName .= $ppeArr[$ppe->ppe_id];
                                    if (count($target->productToPpe) > 1) {
                                        $ppeName .= $plus;
                                    }
                                    $i++;
                                    if ((count($target->productToPpe)) == $i) {
                                        $plus = '';
                                    }
                                }
                            }

                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $ppeName);
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $target->storage_condition);
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $target->storage_location);

                            //remarks
                            $remarksBuyer = '';
                            $comma = ', ';
                            $i = 1;
                            if (!empty($target->productToGl)) {
                                foreach ($target->productToGl as $gl) {
                                    $remarksBuyer .= $buyerArr[$gl->buyer_id] . '  Positive List';
                                    if (count($target->productToGl) > 1) {
                                        $remarksBuyer .= $comma;
                                    }
                                    $i++;
                                    if (count($target->productToGl) == $i) {
                                        $comma = '';
                                    }
                                }
                            }
                            $remarksCertificate = '';
                            $i = 1;
                            $comma = ', ';
                            if (!empty($target->productToCertificate)) {
                                foreach ($target->productToCertificate as $certificate) {
                                    $remarksCertificate .= $certificateArr[$certificate->certificate_id];

                                    if (count($target->productToCertificate) > 1) {
                                        $remarksCertificate .= ', ';
                                    }
                                    $i++;
                                    if (count($target->productToCertificate) == $i) {
                                        $comma = '';
                                    }
                                }
                            }
                            $remarks = '';
                            if (!empty($remarksBuyer) && !empty($remarksCertificate)) {
                                $remarks = $remarksBuyer . ' + ' . $remarksCertificate;
                            }else if(!empty($remarksBuyer)){
                                $remarks = $remarksBuyer;
                            }else if(!empty($remarksCertificate)){
                                $remarks = $remarksCertificate;
                            }
                            $sheet->cell($cellIndex[$hIndex++] . $vIndex, $remarks);
                            $vIndex++;
                        }

                        // $sheet->setBorder('A1:' . $cellIndex[($totalCellNumber - 1)] . $cellNumber);
//                        $sheet->cells('A1:H2', function ($cells) {
//                            $cells->setBackground('#008000');
//                        });
                    });
                })->download('xlsx');
    }

    //get approval status wise product
    public function getProduct(Request $request) {
        $productArr = array('0' => __('label.SELECT_PRODUCT_OPT')) + Product::orderBy('name', 'asc')->where('product_category_id', $request->product_category)->where('status', '1')->where('approval_status', 1)->pluck('name', 'id')->toArray();

        $view = view('complianceReport.loadProduct', compact('productArr'))->render();
        return response()->json(['html' => $view]);
    }

}
