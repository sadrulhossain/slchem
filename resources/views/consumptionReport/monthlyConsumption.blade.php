@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.MONTHLY_CONSUMPTION')
            </div>
        </div>
        <div class="portlet-body">

            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'monthlyConsumptionReport/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">@lang('label.MONTH_OF_ADJUSTMENT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-3">
                                <div class="input-group input-medium date month-date-picker" data-date-format="yyyy-mm" data-date-viewmode="years" data-date-minviewmode="months">
                                    {!! Form::text('checkout_month', Request::get('checkout_month'), ['id'=> 'checkoutMonth', 'class' => 'form-control', 'placeholder' => 'yyyy-mm', 'readonly']) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <!-- /input-group -->
                                <span class="help-block">{{ $errors->first('checkout_month') }}</span>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
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
                    @if(!empty($userAccessArr[59][6]))
                    <a class="btn btn-md btn-success vcenter tooltips" target="_blank" title="Click here to Print this report" href="{!! URL::full().'&view=print' !!}">
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
                        <tr class="header-color">
                            <th class="vcenter" rowspan = "2">@lang('label.CHECK_OUT_DATE')</th>
                            <th rowspan = "2">@lang('label.NAME')</th>
                            <th class="text-center" colspan="2">@lang('label.QUANTITY')</th>
                        </tr>
                        <tr class="header-color">
                            <th class="vcenter">@lang('label.IN_KG')</th>
                            <th>@lang('label.QTY_DETAILS')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="vcenter">{!! Helper::dateFormat($target->adjustment_date)  !!}</td>
                            <td class="vcenter">{!! $target->name !!}</td>
                            <td class="text-right vcenter">{!! Helper::numberFormat($target->quantity,6) !!}</td>
                            <td class="text-right vcenter">{!! Helper::unitConversion($target->quantity) !!}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif
        </div>	
    </div>
</div>
@stop