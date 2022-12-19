@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.MONTHLY_SATUS_REPORT')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'monthlyProductStatusReport/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4"  for="month">@lang('label.SELECT_MONTH') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group input-medium date month-date-picker" data-date-format="yyyy-mm" data-date-viewmode="years" data-date-minviewmode="months">
                                {!! Form::text('month', Request::get('month'), ['id'=> 'month', 'class' => 'form-control', 'placeholder' => 'yyyy-mm', 'readonly']) !!}
                                <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                            <span class="help-block">{{ $errors->first('month') }}</span>
                        </div>
                    </div>
                </div>
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

            @if($request->generate == 'true')

            <div class="row">
                <div class="col-md-offset-8 col-md-4" id="manageEvDiv">
                    @if(!empty($userAccessArr[62][6]))
                    <a class="btn btn-md btn-success vcenter tooltips" target="_blank"  href="{!! URL::full().'&view=print' !!}" title="Click here to Print this Report">
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
                        <tr class="info">
                            <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                            <th class="vcenter" rowspan="2">@lang('label.CHEMICAL_NAME')</th>
                            <th class="vcenter" rowspan="2">@lang('label.LOCATION')</th>
                            <th class="text-center vcenter" colspan="3">@lang('label.PREVIOUS_BALANCE')</th>
                            <th class="text-center vcenter" colspan="3">@lang('label.CHECK_IN') @lang('label.THIS_MONTH')</th>
                            <th class="text-center vcenter" colspan="3">@lang('label.TOTAL')</th>
                            <th class="text-center vcenter" colspan="3">@lang('label.ISSUE') @lang('label.THIS_MONTH')</th>
                            <th class="text-center vcenter" colspan="4">@lang('label.BALANCE')</th>
                        </tr>
                        <tr class="info">
                            <th class="text-center vcenter">@lang('label.BEFORE')&nbsp;@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.BEFORE')&nbsp;@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.BEFORE')&nbsp;@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.TODAY')&nbsp;@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.TODAY')&nbsp;@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.TOTAL')&nbsp;@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.RATE')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($targetArr))
                        <?php
                        $sl = $totalPreAmnt = $totalRcvAmnt = $totalIssueAmnt = $totalAmnt = $totalBalanceAmnt = 0;
                        ?>
                        @foreach($targetArr as $data)
                        <?php
                        $totalPreAmnt += $data['prev_month_balance_amount'];
                        $totalRcvAmnt += $data['this_month_amount'];
                        $totalAmnt += ($data['total_amount']);
                        $totalIssueAmnt += $data['issue_amount'];
                        $totalBalanceAmnt += $data['balance_amount'];
                        ?>
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $data['name'] !!}</td>
                            <td class="vcenter">{!! $data['location'] !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['prev_month_balance_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['prev_month_balance_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['prev_month_balance_amount']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['this_month_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['this_month_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['this_month_amount']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['total_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['total_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['total_amount']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['issue_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['issue_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['issue_amount']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['balance_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['balance_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['balance_rate']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['balance_amount']) !!}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-right vcenter" colspan="5"><strong>@lang('label.TOTAL_TAKA')</strong></td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalPreAmnt) !!}</b></td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalRcvAmnt) !!}</b></td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalAmnt) !!}</b></td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalIssueAmnt) !!}</b></td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalBalanceAmnt) !!}</b></td>
                        </tr>
                        @else
                        <tr>
                            <td class="vcenter" colspan="19">@lang('label.NO_PRODUCT_FOUND_AT_THIS_MONTH')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif
        </div>	
    </div>
</div>
<script>

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
</script>
@stop