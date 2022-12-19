@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.STOCK_SUMMARY_REPORT')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'checkInReport/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4"  for="product">@lang('label.PRODUCT')</label>
                        <div class="col-md-8">
                            <?php $productList = explode(",", Request::get('product')); ?>
                            {!! Form::select('product[]', $productArr, $productList, ['class' => 'form-control mt-multiselect btn btn-default', 'id' => 'product', 'multiple' => 'multiple', 'data-width' => '100%']) !!}
                            <span class="text-danger">{{ $errors->first('product') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->


            <div class="row">
                <div class="col-md-offset-8 col-md-4" id="manageEvDiv">
                    @if(!empty($userAccessArr[57][6]) && !$targetArr->isEmpty())
                    <?php $view = $request->generate == 'true' ? '&' :  '?'; ?>
                    <a class="btn btn-md btn-success vcenter tooltips" target="_blank" title="Click here to Print Stock Summary Report"  href="{!! URL::full().$view.'view=print' !!}">
                        <i class="fa fa-print"></i> @lang('label.PRINT')
                    </a>
                    @endif
                    <!--                    <a class="btn btn-icon-only btn-warning tooltips vcenter" title="Download PDF" href="{!! URL::full().'&view=pdf' !!}">
                                            <i class="fa fa-download"></i>
                                        </a>-->
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                            <th class="vcenter" rowspan="2">@lang('label.PRODUCT_CATEGORY')</th>
                            <th class="vcenter" rowspan="2">@lang('label.NAME')</th>
                            <th rowspan="2">@lang('label.PRODUCT_CODE')</th>
                            <th class="text-center" colspan="2">@lang('label.QUANTITY')</th>
                        </tr>
                        <tr>
                            <th class="text-center vcenter"><strong>(@lang('label.IN_KG'))</strong></th>
                            <th class="text-center vcenter"><strong>(@lang('label.DETAILS'))</strong></th>
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
                            <td class="vcenter">{!! $target->product_category !!}</td>
                            <td class="vcenter">{!! $target->product !!}</td>
                            <td class="vcenter">{!! $target->product_code !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($target->available_quantity,6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($target->available_quantity) !!}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>	
    </div>
</div>
<script type="text/javascript">
    $(function () {
        var productAllSelected = false;
        $('#product').multiselect({
            numberDisplayed: 0,
            includeSelectAllOption: true,
            buttonWidth: '100%',
            maxHeight: 250,
            nonSelectedText: "@lang('label.SELECT_PRODUCT')",
//        enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            onSelectAll: function () {
                productAllSelected = true;
            },
            onChange: function () {
                productAllSelected = false;
            }
        });
    });

    $(document).on("change", '#productId', function () {
        var productId = $("#productId").val();
        //alert(productId);return false;
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        $.ajax({
            url: "{{URL::to('checkInReport/getSupplierManufacturer')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
            },
            success: function (res) {
                $('#showSupplierManufacturer').html(res.html);
                $('.js-source-states').select2();
            },
        });
    });</script>
@stop