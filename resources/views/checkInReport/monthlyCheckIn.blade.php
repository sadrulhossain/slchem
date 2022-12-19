@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.MONTHLY_CHECK_IN')
            </div>
        </div>
        <div class="portlet-body">

            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'monthlyCheckInReport/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">@lang('label.MONTHLY_CHECK_IN_DATE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-3">
                                <div class="input-group input-medium date month-date-picker" data-date-format="yyyy-mm" data-date-viewmode="years" data-date-minviewmode="months">
                                    {!! Form::text('checkin_month', Request::get('checkin_month'), ['id'=> 'checkinMonth', 'class' => 'form-control', 'placeholder' => 'yyyy-mm', 'readonly']) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <!-- /input-group -->
                                <span class="text-danger">{{ $errors->first('checkin_month') }}</span>
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

            @if(!empty($qpArr))

            <div class="row">
                <div class="col-md-offset-8 col-md-4" id="manageEvDiv">
                    @if(!empty($userAccessArr[56][6]))
                    <a class="btn btn-md btn-success vcenter tooltips" target="_blank" title="Click here to Print Monthly CheckIn Report"  href="{!! URL::full().'&view=print' !!}">
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
                            <th class="vcenter" rowspan="2">@lang('label.CHECKIN_DATE')</th>
                            <th class="vcenter" rowspan="2">@lang('label.NAME')</th>
                            <th class="vcenter" rowspan="2">@lang('label.SUPPLIER')</th>
                            <th class="vcenter" rowspan="2">@lang('label.MANUFACTURER')</th>
                            <th class="text-center vcenter" colspan="2">@lang('label.QUANTITY')</th>
                            <th class="vcenter" rowspan="2">@lang('label.LOT_NUMBER')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.RATE')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.AMOUNT')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.REFERENCE_NO')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.CHALLAN_NO')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.M_LABEL')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.MSDS')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.FACTORY_LABEL')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.CHECKIN_BY')</th>
                        </tr>
                        <tr class="header-color">
                            <th class="text-center vcenter">(@lang('label.IN_KG'))</th>
                            <th class="text-center vcenter">(@lang('label.DETAILS'))</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $totalAmount = $amount = 0;
                        ?>
                        @foreach($targetArr as $target)
                        <?php
                        $amount = isset($target->amount) ? $target->amount : '0.00';
                        $totalAmount += $amount;
                        ?>
                        <tr>
                            <td class="vcenter">{!! Helper::dateFormat($target->checkin_date) !!}</td>
                            <td class="vcenter">{!! $target->name !!}</td>
                            <td class="vcenter">{!! $target->supplier_name !!}</td>
                            <td class="vcenter">{!! $target->manufacturer_name !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($target->quantity,6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($target->quantity) !!}</td>
                            <td class="vcenter">{!! $target->lot_number !!}</td>
                            <td class="text-center vcenter">{!! $target->rate !!}</td>
                            <td class="text-center vcenter">{!! $target->amount !!}</td>
                            <td class="text-center vcenter">{!! $target->ref_no !!}</td>
                            <td class="text-center vcenter">{!! $target->challan_no !!}</td>
                            <td class="text-center vcenter">{!! ($target->has_mlabel == '1') ? '<i class="fa fa-check-square"></i>'  : '<i class="fa fa-remove"></i>' !!}</td>
                            <td class="text-center vcenter">{!! ($target->msds == '1') ? '<i class="fa fa-check-square"></i>' : '<i class="fa fa-remove"></i>' !!}</td>
                            <td class="text-center vcenter">{!! ($target->factory_label == '1') ? '<i class="fa fa-check-square"></i>' : '<i class="fa fa-remove"></i>' !!}</td>
                            <td class="text-center vcenter">{!! $target->first_name.' '.$target->last_name !!}</td>
                        </tr>
                        @endforeach
                        <tr class="total-color">
                            <td class="text-right" colspan="8"><strong>@lang('label.TOTAL')</strong></td>
                            <td class="text-center vcenter"><strong>{!! Helper::numberFormat($totalAmount) !!}</strong></td>
                            <td class="vcenter">&nbsp;</td>
                            <td class="vcenter">&nbsp;</td>
                            <td class="vcenter">&nbsp;</td>
                            <td class="vcenter">&nbsp;</td>
                            <td class="vcenter">&nbsp;</td>
                            <td class="vcenter">&nbsp;</td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="16" class="vcenter">@lang('label.NO_PRODUCT_FOUND_TO_CHECKIN_AT_THIS_MONTH')</td>
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