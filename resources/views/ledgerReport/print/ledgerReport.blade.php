<html>
    <head>
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="{{URL::to('/')}}/public/img/Sterling_Laundry_Logo.png" alt="sterling-laundry-logo"/>
                <br/>@lang('label.DEMAND_PRINT_HEADER_TWO')
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="bold">@lang('label.DAILY_LEDGER')</h2>
            </div>
        </div>
        <div class="">
            <p>@lang('label.PRODUCT'): {{ $productArr[$request->product_id] }} &nbsp; &nbsp; &nbsp; &nbsp;
                @lang('label.DATE'): {!! $request->from_date .' to '. $request->to_date!!}
            </p>
        </div>
        <table class="table table-bordered table-hover table-responsive">
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

        <table class="no-border">
            <tr>
                <td class="no-border text-left">@lang('label.REPORT_GENERATED_ON') {{ Helper::printDateTime(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}</td>
                <td class="no-border text-right col-md-6">
                    @lang('label.GENERATED_BY_RAJAKINI_SOFTWARE'),<span>&nbsp;@lang('label.POWERED_BY')</span><b>&nbsp;&nbsp;@lang('label.SWAPNOLOKE')</b>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
        <style type="text/css">
            @media print {
                @page {
                    size: landscape
                }
            }
        </style>
    </body>