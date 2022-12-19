<html>
    <head>
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/print.css'}}" rel="stylesheet" type="text/css" />
        <link href="{{ base_path().'/public/assets/layouts/layout/css/downloadPdfPrint/pdf.css'}}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="{{base_path().'/public/img/Sterling_Laundry_Logo.png'}}" alt="sterling-laundry-logo"/>
                <br/>@lang('label.DEMAND_PRINT_HEADER_TWO')
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="bold">@lang('label.PRODUCT_COMPLIANCE_REPORT')</h2>
            </div>
        </div>

        <div class="report-title">
            <p>
                @lang('label.PRODUCT_CATEGORY'): {!! !empty($request->product_category_id) ? $productCategoryArr[$request->product_category_id] : __('label.ALL') !!},
                @lang('label.PRODUCT'): {!! !empty($request->product_id) ? $productArr[$request->product_id] : __('label.ALL') !!}
            </p>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="header-color">
                    <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                    <th class="vcenter" rowspan="2">@lang('label.CHEMICAL_NAME')</th>
                    <th class="vcenter" rowspan="2">@lang('label.MANUFACTURER')</th>
                    <th class="vcenter" rowspan="2">@lang('label.SUPPLIER')</th>
                    <th class="vcenter" rowspan="2">@lang('label.SDS')</th>
                    <th class="vcenter" rowspan="2">@lang('label.TDS')</th>
                    <th class="vcenter" rowspan="2">@lang('label.SDS_VERSION')</th>
                    <th class="vcenter" rowspan="2">@lang('label.CAS_NO_EINCES_NO_OF_THE_INGREDIENTS')</th>

                    @if(!empty($buyerArr))
                    @foreach($buyerArr as $buyerId => $buyerName)
                    <th class="text-center vcenter" colspan="2">{!! $buyerName !!}</th>
                    @endforeach
                    @endif
                    <th class="text-center vcenter" colspan="{{ count($hazardCategoryArr) * 2 }}">@lang('label.HAZARD_CLASSIFICATION')</th>
                    <th class="vcenter" rowspan="2">@lang('label.FUNCTION')</th>
                    <th class="vcenter" rowspan="2">@lang('label.AREA_OF_USE')</th>
                    <th class="vcenter" rowspan="2">@lang('label.PPE')</th>
                    <th class="vcenter" rowspan="2">@lang('label.STORAGE_CONDITION_RECOMMENDED_IN_MSDS')</th>
                    <th class="vcenter" rowspan="2">@lang('label.LOCATION_OF_STORAGE')</th>
                    <th class="vcenter" rowspan="2">@lang('label.REMARKS')</th>
                </tr>
                <tr class="header-color">
                    @if(!empty($buyerArr))
                    @foreach($buyerArr as $buyerId => $buyerName)
                    <th class="text-center vcenter">@lang('label.RSL')</th>
                    <th class="text-center vcenter">@lang('label.MRSL')</th>
                    @endforeach
                    @endif
                    @if(!empty($hazardCategoryArr))
                    @foreach($hazardCategoryArr as $catId => $catName)
                    <th class="text-center vcenter">{!! $catName !!}</th>
                    <th class="text-center vcenter">@lang('label.IF_YES_PLEASE_SPECIFIC_HAZARD_TYPE')</th>
                    @endforeach
                    @endif

                </tr>
            </thead>
            <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $target->product !!}</td>
                            <td class="vcenter">
                                <?php
                                $manufacturerName = '';
                                $comma = ' , ';
                                $i = 1;
                                ?>

                                @if(!$target->productToManufacturer->isEmpty())
                                @foreach($target->productToManufacturer as $manufacturer)
                                <?php
                                $manufacturerName .= $manufacturerArr[$manufacturer->manufacturer_id];
                                if ((count($target->productToManufacturer)) > 1) {
                                    $manufacturerName .= $comma;
                                }//if
                                $i++;
                                if (count($target->productToManufacturer) == $i) {
                                    $comma = '';
                                }
                                ?>

                                @endforeach
                                {!! $manufacturerName !!}
                                @endif
                            </td>
                            <td class="vcenter">
                                <?php
                                $supplierName = '';
                                $comma = ', ';
                                $i = 1;
                                ?>
                                @if(!$target->productToSupplier->isEmpty())
                                @foreach($target->productToSupplier as $supplier)
                                <?php
                                $supplierName .= $supplierArr[$supplier->supplier_id];
                                if (count($target->productToSupplier) > 1) {
                                    $supplierName .= $comma;
                                }
                                $i++;
                                if (count($target->productToSupplier) == $i) {
                                    $comma = '';
                                }
                                ?>
                                @endforeach
                                {{ $supplierName }}
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($target->sds == '1') 
                                <label class="label label-info">@lang('label.Y')</label>
                                @if(!empty($target->sds_file))
                                <a href="{{URL::to('public/uploads/safetyDataSheet/'.$target->sds_file)}}"
                                   class="btn yellow-crusta btn-sm tooltips" title="Safety Data Sheet Preview" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                @endif
                                @else
                                <label class="label label-danger"> @lang('label.N')</label>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($target->tds == '1') 
                                <label class="label label-info">@lang('label.Y')</label>
                                @if(!empty($target->tds_file))
                                <a href="{{URL::to('public/uploads/technicalDataSheet/'.$target->tds_file)}}"
                                   class="btn yellow-crusta btn-sm tooltips" title="Safety Data Sheet Preview" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                @endif
                                @else
                                <label class="label label-danger"> @lang('label.N')  </label>
                                @endif
                            </td>
                            <td class="vcenter">{!! $target->sds_version !!}</td>
                            <td class="vcenter">
                                <p>
                                    <?php
                                    $casName = '';
                                    $comma = ', ';
                                    $i = 1;
                                    ?>

                                    @if(!$target->productToCas->isEmpty())
                                        <span><b>CAS:</b></span>
                                        @foreach($target->productToCas as $cas)
                                            <?php
                                            $casName .= array_key_exists($cas->cas_no, $casNameArr) ?$cas->cas_no .' ('.$casNameArr[$cas->cas_no].')' : '';
                                            if (count($target->productToCas) > 1) {
                                                $casName .= $comma;
                                            }
                                            $i++;
                                            if (count($target->productToCas) == $i) {
                                                $comma = '';
                                            }
                                            ?>
                                        @endforeach
                                        {{ $casName }}
                                    @endif

                                </p>
                                <p>
                                    <?php
                                    $ecName = '';
                                    $comma = ', ';
                                    $i = 1;
                                    ?>
                                    @if(!$target->productToEc->isEmpty())
                                        <span><b>EC:</b></span>
                                        @foreach($target->productToEc as $ec)
                                            <?php
                                            $ecName .= array_key_exists($ec->ec_no,$ecNameArr) ? $ec->ec_no .' ('.$ecNameArr[$ec->ec_no].')' : '';
                                            if (count($target->productToEc) > 1) {
                                                $ecName .= $comma;
                                            }
                                            $i++;
                                            if (count($target->productToEc) == $i) {
                                                $comma = '';
                                            }
                                            ?>
                                        @endforeach
                                        {{ $ecName }}
                                    @endif
                                </p>
                            </td>

                            @foreach($buyerArr as $buyerId => $buyerName)
                            @foreach($glIndex as $index)
                            <td class="text-center vcenter"> 
                                @if(isset($productToGlArr[$target->id]))
                                @if(isset($productToGlArr[$target->id][$buyerId]))
                                @if($productToGlArr[$target->id][$buyerId][$index] == '1')
                                @lang('label.Y')
                                @else
                                @lang('label.N')
                                @endif
                                @else
                                @lang('label.N')    
                                @endif
                                @else
                                @lang('label.N_A')
                                @endif
                            </td>
                            @endforeach
                            @endforeach


                            @if(!empty($hazardCategoryArr))
                            @foreach($hazardCategoryArr as $catId => $catName)
                            <td class="text-center vcenter">
                                @if(isset($productToHazardCatArr[$target->id]))
                                @if(in_array($catId,$productToHazardCatArr[$target->id]))
                                @lang('label.YES')
                                @else
                                @lang('label.NO')
                                @endif       
                                @else
                                @lang('label.NO')       
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if(isset($productToHazardCatArr[$target->id]))
                                @if(in_array($catId,$productToHazardCatArr[$target->id]))
                                {!! $hazardClassArr[$catId] !!}
                                @else
                                @lang('label.BLANK')
                                @endif       
                                @else
                                @lang('label.BLANK')
                                @endif
                            </td>
                            @endforeach
                            @endif
                            <td class="vcenter">{!! $target->funciton !!}</td>
                            <td class="vcenter">
                                @if(!empty($target->productToCheckInDetails))
                                @foreach($target->productToCheckInDetails as $checkInData)
                                {!! ($checkInData->purpose != 0) ? $departmentArr[$checkInData->purpose] : '' !!}
                                <?php
                                if (count($target->productToCheckInDetails) > 1) {
                                    echo ',';
                                }
                                ?>
                                @endforeach
                                @endif
                            </td>
                            <td class="vcenter">
                                <?php
                                $ppeName = '';
                                $plus = ' + ';
                                $i = 1;
                                ?>
                                @if(!empty($target->productToPpe))
                                @foreach($target->productToPpe as $ppe)
                                
                                <?php
                                $ppeName .= $ppeArr[$ppe->ppe_id];
                                if (count($target->productToPpe) > 1) {
                                        $ppeName .= $plus;
                                    }
                                    $i++;
                                    if ((count($target->productToPpe)) == $i) {
                                        $plus = '';
                                    }
                                ?>
                                @endforeach
                                {!! $ppeName !!}
                                @endif
                            </td>

                            <td class="vcenter">{!! $target->storage_condition !!}</td>
                            <td class="vcenter">{!! $target->storage_location !!}</td>
                            <td class="vcenter">
                                @foreach($target->productToGl as $gl)
                                {{ $buyerArr[$gl->buyer_id].'  Positive List' }}
                                <?php
                                if (count($target->productToGl) > 1) {
                                    echo ',';
                                }
                                ?>
                                @endforeach
                                @foreach($target->productToCertificate as $certificate)
                                {{ $certificateArr[$certificate->certificate_id] }}
                                <?php
                                if (count($target->productToCertificate) > 1) {
                                    echo ',';
                                }
                                ?>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="{{ (count($buyerArr)*2) + (count($hazardCategoryArr)*2)+ 12}}" class="vcenter">
                                <div class="alert alert-danger">
                                    <strong>@lang('label.WAITING_FOR_APPROVAL_FROM_ADMINISTRATOR')</strong>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
        </table>
        <table class="no-border">
            <tr>
                <td class="no-border col-md-6">
                    @lang('label.GENERATED_BY_RAJAKINI_SOFTWARE')
                    ,<span>&nbsp;@lang('label.POWERED_BY')</span><b>&nbsp;&nbsp;@lang('label.SWAPNOLOKE')</b>
                </td>
                <td class="no-border text-right">@lang('label.REPORT_GENERATED_ON') {{ Helper::printDateFormat(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}</td>
            </tr>
        </table>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function(event) {
                window.print();
            });
        </script>
    </body>