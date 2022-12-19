@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.LOW_QTY_PRODUCTS')
            </div>
        </div>
        <div class="portlet-body">
            <!--            <div class="row">
                            <div class="col-md-offset-8 col-md-4" id="manageEvDiv">
                                <a class="btn btn-md btn-success vcenter tooltips" target="_blank" title="Click here to Print Stock Summary Report"  href="{!! URL::full().'&view=print' !!}">
                                    <i class="fa fa-print"></i> @lang('label.PRINT')
                                </a>
                                                    <a class="btn btn-icon-only btn-warning tooltips vcenter" title="Download PDF" href="{!! URL::full().'&view=pdf' !!}">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                            </div>
                        </div>-->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.PRODUCT_CATEGORY')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th >@lang('label.PRODUCT_CODE')</th>
                            <th class="text-center">@lang('label.QUANTITY')</th>
                            <th >@lang('label.UNIT')</th>
                            <th class="text-center">@lang('label.REORDER_LEVEL')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Input::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $productCatArr[$target->product_category_id] !!}</td>
                            <td class="vcenter">{!! $target->name !!}</td>
                            <td class="vcenter vcenter">{!! $target->product_code !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($target->available_quantity) !!}</td>
                            <td class="vcenter">{!! $unitArr[$target->primary_unit_id] !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($target->reorder_level) !!}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>
@stop