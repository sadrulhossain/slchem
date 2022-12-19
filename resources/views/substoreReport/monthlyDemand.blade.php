@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.MONTHLY_SUBSTORE_DEMAND')
            </div>
        </div>
        <div class="portlet-body">

            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'monthlySubstoreReport/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">@lang('label.MONTHLY_SUBSTORE_DEMAND_MONTH') :<span class="text-danger"> *</span></label>
                            <div class="col-md-3">
                                <div class="input-group input-medium date month-date-picker" data-date-format="yyyy-mm" data-date-viewmode="years" data-date-minviewmode="months">
                                    {!! Form::text('substore_month', Request::get('substore_month'), ['id'=> 'substoreMonth', 'class' => 'form-control', 'placeholder' => 'yyyy-mm', 'readonly']) !!}
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <!-- /input-group -->
                                <span class="help-block">{{ $errors->first('substore_month') }}</span>
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
                    @if(!empty($userAccessArr[65][6]))
                    <a class="btn btn-md btn-success vcenter tooltips" target="_blank" title="Click here to Print Monthly Substore Demand Report"  href="{!! URL::full().'&view=print' !!}">
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
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($targetArr as $date => $item)
                        @foreach($item as $voucherNo => $substoreInfo)
                        <tr class="bg-default">
                            <td class="text-center bold" colspan="5">
                                @lang('label.DATE'):&nbsp;{!! Helper::dateFormat($date) !!} 
                                | @lang('label.REFERENCE_NO'):&nbsp;{!! $voucherNo !!}
                            </td>
                        </tr>
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($substoreInfo['data'] as $products)
                        <tr>
                            <td>{!! ++$sl !!}</td>
                            <td>{!! $products['name'] !!}</td>
                            <td>{!! Helper::numberFormat($products['quantity'],6) !!}</td>
                            <td>{!! Helper::unitConversion($products['quantity']) !!}</td>
                        </tr>
                        @endforeach 
                        @endforeach                        
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="vcenter">@lang('label.NO_SUBSTORE_DEMAND_FOUND')</td>
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