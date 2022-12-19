<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Sterling Group" name="description" />
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />

        <link href="{{asset('public/fonts/css.css?family=Open Sans')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />


        <!--BEGIN THEME LAYOUT STYLES--> 
        <!--<link href="{{asset('public/assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />-->
        <link href="{{asset('public/assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" /> 

        <style type="text/css" media="print">
            @page { size: landscape; }
            * {
                -webkit-print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="{{URL::to('/')}}/public/img/Sterling_Laundry_Logo.png" alt="sterling-laundry-logo"/>
                <br/>
                @lang('label.DEMAND_PRINT_HEADER_TWO')
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>@lang('label.DETAILED_LEDGER_REPORT')</h2>
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
        <table class="no-border margin-top-10">
            <tr class="no-border">
                <td class="vtop no-border" width="40%">
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
                </td>
                <td class="vtop no-border" width="60%">
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
                </td>
            </tr>
        </table>
        <table class="margin-top-10 table table-bordered table-striped table-head-fixer-color " id="dataTable">
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
        <table class="no-border">
            <tr>
                <td class="no-border text-left">@lang('label.REPORT_GENERATED_ON') {{ Helper::printDateTime(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}</td>
                <td class="no-border text-right col-md-6">
                    @lang('label.GENERATED_BY_RAJAKINI_SOFTWARE'),<span>&nbsp;@lang('label.POWERED_BY')</span><b>&nbsp;&nbsp;@lang('label.SWAPNOLOKE')</b>
                </td>
            </tr>
        </table>
        <script src="{{asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}"  type="text/javascript"></script>


        <!-- BEGIN THEME GLOBAL SCRIPTS 
        <script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>

        <script type="text/javascript">
document.addEventListener("DOMContentLoaded", function (event) {
    window.print();
});
        </script>
    </body>
</html>