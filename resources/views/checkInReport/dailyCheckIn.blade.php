@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.DAILY_CHECK_IN')
            </div>
        </div>
        <div class="portlet-body">

            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'dailyCheckInReport/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="supplierId">@lang('label.SUPPLIER') :</label>
                                {!! Form::select('supplier_id', $supplierArr, Request::get('supplier_id'), ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="manufacturerId">@lang('label.MANUFACTURER') :</label>
                                {!! Form::select('manufacturer_id', $manufacturerArr, Request::get('manufacturer_id'), ['class' => 'form-control js-source-states', 'id' => 'manufacturerId']) !!}
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">@lang('label.FROM_DATE') :<span class="text-danger"> *</span></label>
                                <div class="input-group date datepicker" style="z-index:0!important;" data-date-end-date="+0d">
                                    {!! Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="fromDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger">{{ $errors->first('from_date') }}</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="control-label">@lang('label.TO_DATE') :<span class="text-danger"> *</span></label>
                                <div class="input-group date datepicker" style="z-index:0!important;" data-date-end-date="+0d">
                                    {!! Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="toDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger">{{ $errors->first('to_date') }}</span>
                                </div>
                            </div>
                            <div class="col-md-offset-4 col-md-6 margin-top-20">
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

            @if (!empty($qpArr))
            <div class="row">
                <div class="col-md-offset-8 col-md-4" id="manageEvDiv">
                    @if(!empty($userAccessArr[55][6]))
                    <a class="btn btn-md btn-success vcenter tooltips" target="_blank" title="Click here to Print Daily CheckIn Report"  href="{!! URL::full().'&view=print' !!}">
                        <i class="fa fa-print"></i> @lang('label.PRINT')
                    </a>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="header-color">
                            <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
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
                        $sl = 0;
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="text-center vcenter">{!! Helper::dateFormat($target->checkin_date) !!}</td>
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
                        @else
                        <tr>
                            <td colspan="17" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
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