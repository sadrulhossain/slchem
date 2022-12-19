@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.DAILY_LEDGER')
            </div>
        </div>
        <div class="portlet-body">

            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'ledgerReport/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('product_id', $productArr, Request::get('product_id'), ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                                    <span class="text-danger">{{ $errors->first('product_id') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4">@lang('label.FROM_DATE') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <div class="input-group date datepicker">
                                        {!! Form::text('from_date',Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="fromDate">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                     <span class="text-danger">{{ $errors->first('from_date') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4">@lang('label.TO_DATE') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <div class="input-group date datepicker">
                                        {!! Form::text('to_date',Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                        <span class="input-group-btn">
                                            <button class="btn default reset-date" type="button" remove="toDate">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('to_date') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-5 col-md-2">
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
                    @if(!empty($userAccessArr[63][6]))
                    <a class="btn btn-md btn-success vcenter" target="_blank"  href="{!! URL::full().'&view=print' !!}">
                        <i class="fa fa-print"></i> @lang('label.PRINT')
                    </a>
                    @endif
                </div>
            </div>
            <div class="table-responsive" style="overflow: scroll; max-height: 600px;">
                <table class="table table-bordered table-hover table-responsive table-wrapper-scroll-y" id="dataTable">
                    <thead>
                        <tr class="info">
                            <th class="vcenter" rowspan="3">@lang('label.DATE')</th>
                            <th class="vcenter" rowspan="3">@lang('label.SUPPLIER')</th>
                            <th class="text-center vcenter" rowspan="3">@lang('label.CHALLAN_NO')</th>
                            <th class="vcenter text-center border-double-right" colspan="5">@lang('label.PRODUCT') @lang('label.CHECK_IN')</th>
                            <th class="vcenter text-center border-double-right" colspan="5">@lang('label.PRODUCT') @lang('label.ISSUE')</th>
                            <th class="vcenter text-center" colspan="4">@lang('label.PRODUCT') @lang('label.BALANCE')</th>
                            <th class="vcenter text-center" rowspan="3">@lang('label.REMARKS')</th>
                        </tr>
                        <tr class="info">
                            <!--RECEIVED SIDE-->
                            <th class="text-center vcenter" rowspan="2">@lang('label.LOT_NUMBER')</th>
                            <th class="text-center vcenter" colspan="2">@lang('label.QUANTITY')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.RATE')</th>
                            <th class="text-center vcenter border-double-right" rowspan="2">@lang('label.AMOUNT')</th>

                            <!--ISSUE SIDE-->
                            <th class="text-center vcenter" rowspan="2">@lang('label.LOT_NUMBER')</th>
                            <th class="vcenter text-center" colspan="2">@lang('label.QUANTITY')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.RATE')</th>
                            <th class="vcenter text-center border-double-right" rowspan="2">@lang('label.AMOUNT')</th>
                            <th class="text-center vcenter" colspan="2">@lang('label.QUANTITY')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.RATE')</th>
                            <th class="text-center vcenter" rowspan="2">@lang('label.AMOUNT')</th>
                        </tr>

                        <tr class="info">
                            <!--RECEIVED SIDE-->
                            <th class="text-center vcenter">@lang('label.IN_KG')</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.IN_KG')</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.IN_KG')</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: lightgoldenrodyellow;">
                            <!--displaying before checkin and before consumption data-->
                            @if(!empty($bfTargetArr))
                            <!--td for product checkin--> 
                            <td colspan="3" class="bold">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="vcenter text-center">{!! Helper::numberFormat($bfTargetArr['checkin_qty'],6) !!}</td>
                            <td class="vcenter text-center">{!! Helper::unitConversion($bfTargetArr['checkin_qty']) !!}</td>
                            <td class="vcenter text-center">{!! Helper::numberFormat($bfTargetArr['checkin_rate']) !!}</td>
                            <td class="vcenter text-center border-double-right">{!! Helper::numberFormat($bfTargetArr['checkin_amount']) !!}</td>
                            <!--td for product consumption--> 
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="vcenter text-center">{!! Helper::numberFormat($bfTargetArr['consump_qty'],6) !!}</td>
                            <td class="vcenter text-center">{!! Helper::unitConversion($bfTargetArr['consump_qty']) !!}</td>
                            <td class="vcenter text-center">{!! Helper::numberFormat($bfTargetArr['consump_rate']) !!}</td>
                            <td class="vcenter text-center border-double-right">{!! Helper::numberFormat($bfTargetArr['consump_amount']) !!}</td>
                            <td class="vcenter text-center">{!! Helper::numberFormat($bfTargetArr['consump_qty_balance'],6) !!}</td>
                            <td class="vcenter text-center">{!! Helper::unitConversion($bfTargetArr['consump_qty_balance']) !!}</td>
                            <td class="vcenter text-center">{!! Helper::numberFormat($bfTargetArr['consump_rate_balance']) !!}</td>
                            <td class="vcenter text-center">{!! Helper::numberFormat($bfTargetArr['consump_amount_balance']) !!}</td>
                            <td>&nbsp;</td>
                            @endif
                        </tr>

                        <!--displaying current checkin and before consumption data-->
                        @if(!empty($datesArr))
                        @foreach($datesArr as $date => $rowSpan)                                           
                        @for($i=0; $i<$rowSpan; $i++)
                        <tr>
                            @if($i==0)
                            <td class="text-center vcenter" rowspan="{!! $rowSpan !!}">{!! Helper::dateFormat($date) !!}</td> 
                            @endif
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['checkIn'][$i]['supplier'])? $infoTree[$date]['checkIn'][$i]['supplier'] : '-' !!}</td>
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['checkIn'][$i]['challan_no'])? $infoTree[$date]['checkIn'][$i]['challan_no'] : '-' !!}</td>
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['checkIn'][$i]['lot_number'])? $infoTree[$date]['checkIn'][$i]['lot_number'] : '-' !!}</td>
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['checkIn'][$i]['quantity'])? Helper::numberFormat($infoTree[$date]['checkIn'][$i]['quantity'],6) : Helper::numberFormat(0,6) !!}</td>
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['checkIn'][$i]['quantity'])? Helper::unitConversion($infoTree[$date]['checkIn'][$i]['quantity']) : '' !!}</td>
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['checkIn'][$i]['rate'])? Helper::numberFormat($infoTree[$date]['checkIn'][$i]['rate']) : Helper::numberFormat(0) !!}</td>
                            <td class="text-center vcenter border-double-right">{!! isset($infoTree[$date]['checkIn'][$i]['amount'])? Helper::numberFormat($infoTree[$date]['checkIn'][$i]['amount']) : Helper::numberFormat(0) !!}</td>                        
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['consumption'][$i]['lot_number']) ? $infoTree[$date]['consumption'][$i]['lot_number'] : '-' !!}</td>
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['consumption'][$i]['quantity']) ? Helper::numberFormat($infoTree[$date]['consumption'][$i]['quantity'],6) : Helper::numberFormat(0,6) !!}</td>
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['consumption'][$i]['quantity']) ? Helper::unitConversion($infoTree[$date]['consumption'][$i]['quantity']) : '' !!}</td>
                            <td class="text-center vcenter">{!! isset($infoTree[$date]['consumption'][$i]['rate']) ? Helper::numberFormat($infoTree[$date]['consumption'][$i]['rate']) : Helper::numberFormat(0) !!}</td>
                            <td class="text-center vcenter border-double-right">{!! isset($infoTree[$date]['consumption'][$i]['amount']) ? Helper::numberFormat($infoTree[$date]['consumption'][$i]['amount']) : Helper::numberFormat(0) !!}</td>
                            <td class="text-center vcenter">{!! isset($balanceArr[$date][$i]['quantity'])? Helper::numberFormat($balanceArr[$date][$i]['quantity'],6) : '-' !!}</td>
                            <td class="text-center vcenter">{!! isset($balanceArr[$date][$i]['quantity'])? Helper::unitConversion($balanceArr[$date][$i]['quantity']) : '-' !!}</td>
                            <td class="text-center vcenter">{!! isset($balanceArr[$date][$i]['rate'])? Helper::numberFormat($balanceArr[$date][$i]['rate']) : '-' !!}</td>
                            <td class="text-center vcenter">{!! isset($balanceArr[$date][$i]['amount'])? Helper::numberFormat($balanceArr[$date][$i]['amount']) : '-' !!}</td>
                            <td class="text-center vcenter">&nbsp;</td>
                        </tr>
                        @endfor
                        @endforeach 
                        @else
                        <tr>
                            <td colspan="15" class="vcenter">@lang('label.NO_DATE_FOUND')</td>
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
        $("#dataTable").tableHeadFixer({"left": 3});
    });
</script>
@stop