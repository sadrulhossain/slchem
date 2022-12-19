@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.PRODUCT_COMPLIANCE_REPORT')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'complianceReport/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="productCategory">@lang('label.PRODUCT_CATEGORY'):</label>
                                {!! Form::select('product_category_id', $productCategoryArr, Request::get('product_category_id'), ['class' => 'form-control js-source-states', 'id' => 'productCategory']) !!}
                            </div>

                            <div class="col-md-3">
                                <div id="loadProduct">
                                    <label class="control-label" for="productId">@lang('label.SELECT_PRODUCT'):</label>
                                    {!! Form::select('product_id', $productArr, Request::get('product_id'), ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3 margin-top-20">
                                <label class="control-label">&nbsp;</label>
                                <button type="submit" class="btn btn-md green btn-outline filter-submit">
                                    <i class="fa fa-search"></i> @lang('label.GENERATE')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>
            @if($request->generate == 'true')
            <div class="row">
                <div class="col-md-offset-8 col-md-4" id="manageEvDiv">
                    <!--                    <a class="btn btn-md btn-success vcenter" target="_blank"  href="{!! URL::full().'&view=print' !!}">
                                            <i class="fa fa-print"></i> @lang('label.PRINT')
                                        </a>-->
                    @if(!empty($userAccessArr[60][17]))
                    <a class="btn btn-md btn-warning tooltips vcenter" title="Click here to Download PDF" href="{!! URL::full().'&view=pdf' !!}">
                        <i class="fa fa-file-pdf-o"></i>  @lang('label.PDF_DOWNLOAD')
                    </a>
                    <a class="btn btn-md purple-seance tooltips vcenter" href="{{url('complianceReport?product_id='.Request::get('product_id').'&view=excel')}}" title="Click here to Download Excel">
                        <i class="fa fa-file-excel-o"></i> @lang('label.EXCEL_DOWNLOAD')
                    </a>
                    @endif
                </div>
            </div>
            <div class="table-responsive" style="overflow: scroll; max-height: 600px;">
                <table class="table table-bordered table-hover table-wrapper-scroll-y" id="dataTable">
                    <thead>
                        <tr class="info">
                            <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                            <th class="vcenter" rowspan="2">@lang('label.CHEMICAL_NAME')</th>
                            <th class="vcenter" rowspan="2">@lang('label.MANUFACTURER')</th>
                            <th class="vcenter" rowspan="2">@lang('label.SUPPLIER')</th>
                            <th class="vcenter" rowspan="2">@lang('label.SDS')</th>
                            <th class="vcenter" rowspan="2">@lang('label.TDS')</th>
                            <th class="vcenter" rowspan="2">@lang('label.SDS_VERSION')</th>
                            <th class="vcenter" rowspan="2">@lang('label.DATE')</th>
                            <th class="vcenter" rowspan="2">@lang('label.CAS_NO_EINCES_NO_OF_THE_INGREDIENTS')</th>

                            @if(!empty($buyerArr))
                            @foreach($buyerArr as $buyerId => $buyerName)
                            <th class="text-center vcenter" colspan="2">{!! $buyerName !!}</th>
                            @endforeach
                            @endif

                            <th class="text-center vcenter" colspan="{{ count($hazardCategoryArr) * 2 }}">@lang('label.HAZARD_CLASSIFICATION')</th>
                            <th class="vcenter" rowspan="2">@lang('label.FUNCTION')</th>
                            <th class="vcenter" rowspan="2">@lang('label.AREA_OF_USE')</th>
                            <th class="vcenter" rowspan="2">@lang('label.COMPLIANCE_PPE')</th>
                            <th class="vcenter" rowspan="2">@lang('label.STORAGE_CONDITION_RECOMMENDED_IN_MSDS')</th>
                            <th class="vcenter" rowspan="2">@lang('label.LOCATION_OF_STORAGE')</th>
                            <th class="vcenter" rowspan="2">@lang('label.REMARKS')</th>
                        </tr>
                        <tr class="info">
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
                                @if(!empty($target->sds_file) && !empty($userAccessArr[60][17]))
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
                                @if(!empty($target->tds_file) && !empty($userAccessArr[60][17]))
                                <a href="{{URL::to('public/uploads/technicalDataSheet/'.$target->tds_file)}}"
                                   class="btn yellow-crusta btn-sm tooltips" title="Safety Data Sheet Preview" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                @endif
                                @else
                                <label class="label label-danger"> @lang('label.N')  </label>
                                @endif
                            </td>
                            <td class="vcenter"> {!! $target->sds_version !!}</td>
                            <td class="vcenter"> {!! $target->date !!}</td>
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
                                    $casName .= array_key_exists($cas->cas_no, $casNameArr) ? $cas->cas_no . ' (' . $casNameArr[$cas->cas_no] . ')' : '';
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
                                    $ecName .= array_key_exists($ec->ec_no, $ecNameArr) ? $ec->ec_no . ' (' . $ecNameArr[$ec->ec_no] . ')' : '';
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
            </div>
            @endif
        </div>	
    </div>
</div>
<script type="text/javascript">
    $(function () {
        
        
        $(document).on("change", '#productCategory', function () {
            var productCategory = $('#productCategory').val();
            $.ajax({
                url: "{{URL::to('complianceReport/getProduct')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_category: productCategory,
                },
                success: function (res) {
                    $('#loadProduct').html(res.html);
                    $('.js-source-states').select2();
                },
            });
        });
        $("#dataTable").tableHeadFixer({"left": 2});
    });
</script>
@stop