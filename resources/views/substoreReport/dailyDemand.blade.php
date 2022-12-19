@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.DAILY_SUBSTORE_DEMAND')
            </div>
        </div>
        <div class="portlet-body">

            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'dailySubstoreReport/filter','class' => 'form-horizontal')) !!}
                <div class="col-md-12 margin-bottom-20">
                    <div class="col-md-3">
                        <div class="form">
                            <label class="control-label">@lang('label.FROM_DATE') :<span class="text-danger"> *</span></label>
                            <div>
                                <div class="input-group date datepicker" data-date-end-date="+0d">
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
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form">
                            <label class="control-label">@lang('label.TO_DATE') :<span class="text-danger"> *</span></label>
                            <div>
                                <div class="input-group date datepicker" data-date-end-date="+0d">
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
                        </div>
                    </div>
                    <div class="col-md-3 margin-top-20">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            @if($request->generate == 'true')

            <div class="row">
                <div class="col-md-offset-8 col-md-4" id="manageEvDiv">
                    @if(!empty($userAccessArr[64][6]))
                    <a class="btn btn-md btn-success vcenter tooltips" target="_blank" title="Click here to Print Daily Substore Demand Report"  href="{!! URL::full().'&view=print' !!}">
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
                            <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                            <th class="vcenter" rowspan="2">@lang('label.NAME')</th>
                            <th class="text-center vcenter" colspan="2">@lang('label.QUANTITY')</th>
                        </tr>
                        <tr class="header-color">
                            <th class="text-center vcenter">@lang('label.IN_KG')</th>
                            <th class="text-center vcenter">@lang('label.DETAILS')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($targetArr))
                        @foreach($targetArr as $date => $item)
                        @foreach($item as $voucherNo => $substoreInfo)
                        <tr class="bg-default">
                            <td class="text-center bold" colspan="4">
                                @lang('label.DATE'):&nbsp;{!! Helper::dateFormat($date) !!} 
                                | @lang('label.REFERENCE_NO'):&nbsp;{!! $voucherNo !!}
                                | @lang('label.DELIVERED_AT'):&nbsp;{!! date('H:i A',strtotime($substoreInfo['authority']['delivered_at'])) !!}
                                | @lang('label.DELIVERED_BY'):&nbsp;{!! $substoreInfo['authority']['delivered_by'] !!}
                            </td>
                        </tr>
                        <?php $sl = 0; ?>
                        @foreach($substoreInfo['data'] as $products)
                        <tr>
                            <td>{!! ++$sl !!}</td>
                            <td>{!! $products['name'] !!}</td>
                            <td>{!! Helper::numberFormat($products['total'],6) !!}</td>
                            <td>{!! Helper::unitConversion($products['total']) !!}</td>
                        </tr>
                        @endforeach
                        @endforeach
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="vcenter">@lang('label.NO_SUBSTORE_DEMAND_FOUND')</td>
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