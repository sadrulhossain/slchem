@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.DETAILED_LEDGER_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">

                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'detailedLedgerReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productId">@lang('label.PRODUCT') <span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('product_id',  $productArr, Request::get('product_id'), ['class' => 'form-control js-source-states','id'=>'productId']) !!}
                            <span class="text-danger">{{ $errors->first('product_id') }}</span>
                        </div>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') </label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker">
                                {!! Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'yyyy-mm-dd', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!} 
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
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') </label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker">
                                {!! Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'yyyy-mm-dd', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!} 
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
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit">
                            @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
            @if(Request::get('generate') == 'true')
            <div class="row margin-top-20">
                <div class="col-md-12 text-right">
                    @if(!empty($request->generate) && $request->generate == 'true')
                    @if(!empty($ledgerArr))
                    @if(!empty($userAccessArr[71][6]))
                    <a class="btn btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.CLICK_HERE_TO_PRINT')">
                        <i class="fa fa-print"></i> @lang('label.PRINT')
                    </a>
                    @endif
                    @endif
                    @endif
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.PRODUCT')}} : <strong>{{ !empty($productArr[$request->product_id]) && $request->product_id != 0 ? $productArr[$request->product_id] : __('label.N_A') }} |</strong> 
                            {{__('label.FROM_DATE')}} : <strong>{{ !empty($request->from_date) ? $request->from_date : __('label.N_A') }} |</strong> 
                            {{__('label.TO_DATE')}} : <strong>{{ !empty($request->to_date) ? $request->to_date : __('label.N_A') }} </strong>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                    <table class="table table-bordered ">
                        <thead>
                            <tr>
                                <th class="vcenter text-center bold" colspan="2">@lang('label.NET_BALANCE')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="vcenter">@lang('label.QUANTITY') (@lang('label.KG'))</td>
                                <td class="vcenter text-right">{!! !empty($totalBalance['quantity']) ? Helper::numberFormat($totalBalance['quantity'], 6) : Helper::numberFormat(0, 6) !!}</td>
                            </tr>
                            <tr>
                                <td class="vcenter">@lang('label.QUANTITY') (@lang('label.DETAILS'))</td>
                                <td class="vcenter text-right">{!! !empty($totalBalance['quantity']) ? Helper::unitConversion($totalBalance['quantity']) : '' !!}</td>
                            </tr>
                            <tr>
                                <td class="vcenter">@lang('label.AMOUNT') (@lang('label.TAKA'))</td>
                                <td class="vcenter text-right">{!! !empty($totalBalance['amount']) ? Helper::numberFormat($totalBalance['amount']) : Helper::numberFormat(0) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                    <table class="table table-bordered ">
                        <thead>
                            <tr>
                                <th class="vcenter text-center bold" colspan="5">@lang('label.LOT_WISE_NET_BALANCE_UNFINISHED_LOTS')</th>
                            </tr>
                            <tr>
                                <th class="text-center vcenter bold">@lang('label.SL_NO')</th>
                                <th class="text-center vcenter bold">@lang('label.LOT_NUMBER')</th>
                                <th class="text-center vcenter bold">@lang('label.QUANTITY') (@lang('label.KG'))</th>
                                <th class="text-center vcenter bold">@lang('label.QUANTITY') (@lang('label.DETAILS'))</th>
                                <th class="text-center vcenter bold">@lang('label.AMOUNT') (@lang('label.TAKA'))</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($lotWiseBalanceArr))
                            <?php $lotWiseSl = 0; ?>
                            @foreach($lotWiseBalanceArr as $lotNumber => $info)
                            @if(!empty($info['quantity']) && $info['quantity'] != 0)
                            <tr>
                                <td class="text-center vcenter">{!! ++$lotWiseSl !!}</td>
                                <td class="vcenter">{!! $lotNumber ?? '' !!}</td>
                                <td class="vcenter text-right">{!! !empty($info['quantity']) ? Helper::numberFormat($info['quantity'], 6) : Helper::numberFormat(0, 6) !!}</td>
                                <td class="vcenter text-right">{!! !empty($info['quantity']) ? Helper::unitConversion($info['quantity']) : '' !!}</td>
                                <td class="vcenter text-right">{!! !empty($info['amount']) ? Helper::numberFormat($info['amount']) : Helper::numberFormat(0) !!}</td>
                            </tr>
                            @endif
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5">@lang('label.NO_DATA_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div style="max-height: 500px;" class="tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-striped table-head-fixer-color " id="dataTable">
                            <thead>
                                <tr class="blue-light">
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.DATE_N_TIME')</th>
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.DESCRIPTION')</th>
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.SOURCE')</th>
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.LOT_NUMBER')</th>
                                    <th class="text-center vcenter bold" colspan="2">@lang('label.QUANTITY')</th>
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.RATE') (@lang('label.PER_KG'))</th>
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.AMOUNT') (@lang('label.TAKA'))</th>
                                    <th class="text-center vcenter bold" colspan="3">@lang('label.BALANCE')</th>
                                </tr>
                                <tr class="blue-light">
                                    <th class="text-center vcenter bold">@lang('label.IN_KG')</th>
                                    <th class="text-center vcenter bold">@lang('label.DETAILS')</th>
                                    <th class="text-center vcenter bold">@lang('label.QUANTITY') (@lang('label.KG'))</th>
                                    <th class="text-center vcenter bold">@lang('label.QUANTITY') (@lang('label.DETAILS'))</th>
                                    <th class="text-center vcenter bold">@lang('label.AMOUNT') (@lang('label.TAKA'))</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty(Request::get('from_date')))
                                <tr>
                                    <th class="text-center vcenter blue-dark bold font-size-16" colspan="8">@lang('label.PREVIOUS_BALANCE')</th>
                                    <th class="vcenter blue-grey font-size-16 text-right">{!! !empty($previousBalance['quantity']) ? Helper::numberFormat($previousBalance['quantity'], 6) : Helper::numberFormat(0, 6) !!}</th>
                                    <th class="vcenter blue-grey font-size-16 text-right">{!! !empty($previousBalance['quantity']) ? Helper::unitConversion($previousBalance['quantity']) : '' !!}</th>
                                    <th class="vcenter blue-grey font-size-16 text-right">{!! !empty($previousBalance['amount']) ? Helper::numberFormat($previousBalance['amount']) : Helper::numberFormat(0) !!}</th>
                                </tr>
                                @endif

                                @if(!empty($ledgerArr))
                                @foreach($ledgerArr as $dateTime => $lotInfo)
                                @foreach($lotInfo as $lotNumber => $rateInfo)
                                @foreach($rateInfo as $rate => $typeInfo)
                                @foreach ($typeInfo as $type => $details)
                                @foreach ($details as $id => $info)
                                <?php
                                if ($type == 'checkin') {
                                    $inc = '<span class="bold">+</span>';
                                    $supplier = '<span class="bold">' . __('label.SUPPLIER') . ':</span>&nbsp;' . $info['supplier'] ?? __('label.N_A');
                                    $challan = '<br/><span class="bold">' . __('label.CHALLAN_NO') . ':</span>&nbsp;' . $info['challan_no'] ?? __('label.N_A');
                                    $ref = '<br/><span class="bold">' . __('label.REFERENCE_NO') . ':</span>&nbsp;' . $info['ref_no'] ?? __('label.N_A');
                                    $description = $supplier . $challan . $ref;
                                } elseif ($type == 'consume') {
                                    $inc = '<span class="bold">-</span>';
                                    $description = '<span class="bold">' . __('label.REFERENCE_NO') . ':</span>&nbsp;' . $info['ref_no'] ?? __('label.N_A');
                                } elseif ($type == 'substore') {
                                    $inc = '<span class="bold">-</span>';
                                    $description = '<span class="bold">' . __('label.REFERENCE_NO') . ':</span>&nbsp;' . $info['ref_no'] ?? __('label.N_A');
                                }
                                ?>
                                <tr>
                                    <td class="text-center vcenter">{!! !empty($dateTime) ? Helper::printDateFormat($dateTime) : '--' !!}</td>
                                    <td class="vcenter">{!! $description !!}</td>
                                    <td class="vcenter">{!! $info['source'] ?? __('label.N_A') !!}</td>
                                    <td class="vcenter">{!! $lotNumber ?? __('label.N_A') !!}</td>
                                    <td class="text-right vcenter">{!! !empty($info['quantity']) ? $inc . Helper::numberFormat($info['quantity'], 6) : Helper::numberFormat(0, 6) !!}</td>
                                    <td class="text-right vcenter">{!! !empty($info['quantity']) ? Helper::unitConversion($info['quantity']) : '' !!}</td>
                                    <td class="text-right vcenter">{!! !empty($rate) ? Helper::numberFormat($rate) : Helper::numberFormat(0) !!}</td>
                                    <td class="text-right vcenter">{!! !empty($info['amount']) ? $inc . Helper::numberFormat($info['amount']) : Helper::numberFormat(0) !!}</td>
                                    <td class="text-right vcenter">{!! !empty($balanceArr[$dateTime][$lotNumber][$rate][$type][$id]['quantity']) ? Helper::numberFormat($balanceArr[$dateTime][$lotNumber][$rate][$type][$id]['quantity'], 6) : Helper::numberFormat2Digit(0, 6) !!}</td>
                                    <td class="text-right vcenter">{!! !empty($balanceArr[$dateTime][$lotNumber][$rate][$type][$id]['quantity']) ? Helper::unitConversion($balanceArr[$dateTime][$lotNumber][$rate][$type][$id]['quantity']) : '' !!}</td>
                                    <td class="text-right vcenter">{!! !empty($balanceArr[$dateTime][$lotNumber][$rate][$type][$id]['amount']) ? Helper::numberFormat($balanceArr[$dateTime][$lotNumber][$rate][$type][$id]['amount']) : Helper::numberFormat2Digit(0) !!}</td>
                                </tr>
                                @endforeach
                                @endforeach
                                @endforeach
                                @endforeach
                                @endforeach
                                <tr>
                                    <th class="text-right vcenter blue-dark bold" colspan="8">@lang('label.NET_BALANCE')=</th>
                                    <th class="vcenter blue-grey text-right">{!! !empty($totalBalance['quantity']) ? Helper::numberFormat($totalBalance['quantity'], 6) : Helper::numberFormat(0, 6) !!}</th>
                                    <th class="vcenter blue-grey text-right">{!! !empty($totalBalance['quantity']) ? Helper::unitConversion($totalBalance['quantity']) : '' !!}</th>
                                    <th class="vcenter blue-grey text-right">{!! !empty($totalBalance['amount']) ? Helper::numberFormat($totalBalance['amount']) : Helper::numberFormat(0) !!}</th>
                                </tr>
                                @else
                                <tr>
                                    <td class="vcenter text-danger" colspan="11">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>	
    </div>
</div>
<!-- Modal start -->
<!--shipment details-->
<div class="modal fade" id="modalShipmentDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentDetails"></div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        //table header fix
        $("#dataTable").tableHeadFixer();

        //shipment details modal
        $(".shipment-details").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/detailedLedgerReport/shipment')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId
                },
                success: function (res) {
                    $("#showShipmentDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

//        $('.sample').floatingScrollbar();
    });
</script>
@stop