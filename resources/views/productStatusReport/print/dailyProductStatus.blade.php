<html>
    <head>
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/pdf.css')}}" rel="stylesheet" type="text/css" />
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
                <h2 class="bold">@lang('label.DAILY_PRODUCT_STATUS_REPORT')&nbsp;({!! Helper::dateFormat($request->date) !!})</h2>
            </div>
        </div>
        <!--        <div class="report-title">
                    <p>@lang('label.DATE'): </p>
                </div>-->
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="info">
                    <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                    <th class="vcenter" rowspan="2">@lang('label.CHEMICAL_NAME')</th>
                    <th class="vcenter" rowspan="2">@lang('label.LOCATION')</th>
                    <th class="text-center vcenter" colspan="3">@lang('label.PREVIOUS_BALANCE')</th>
                    <th class="text-center vcenter" colspan="3">@lang('label.CHECK_IN') @lang('label.TODAY')</th>
                    <th class="text-center vcenter" colspan="3">@lang('label.TOTAL')</th>
                    <th class="text-center vcenter" colspan="3">@lang('label.ISSUE') @lang('label.TODAY')</th>
                    <th class="text-center vcenter" colspan="4">@lang('label.BALANCE') @lang('label.TODAY')</th>
                </tr>
                <tr class="info">
                    <th class="text-center vcenter">@lang('label.BEFORE')&nbsp;@lang('label.QTY')<br />(@lang('label.KG'))</th>
                    <th class="text-center vcenter">@lang('label.QTY')&nbsp;@lang('label.DETAILS')</th>
                    <th class="text-center vcenter">@lang('label.BEFORE')&nbsp;@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                    <th class="text-center vcenter">@lang('label.TODAY')&nbsp;@lang('label.QTY')<br />(@lang('label.KG'))</th>
                    <th class="text-center vcenter">@lang('label.DETAILS')</th>
                    <th class="text-center vcenter">@lang('label.TOTAL')&nbsp;@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                    <th class="text-center vcenter">@lang('label.QTY')<br />(@lang('label.KG'))</th>
                    <th class="text-center vcenter">@lang('label.DETAILS')</th>
                    <th class="text-center vcenter">@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                    <th class="text-center vcenter">@lang('label.QTY')<br />(@lang('label.KG'))</th>
                    <th class="text-center vcenter">@lang('label.DETAILS')</th>
                    <th class="text-center vcenter">@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                    <th class="text-center vcenter">@lang('label.QTY')<br />(@lang('label.KG'))</th>
                    <th class="text-center vcenter">@lang('label.DETAILS')</th>
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
                $totalPreAmnt += $data['prev_date_balance_amount'];
                $totalRcvAmnt += $data['this_date_amount'];
                $totalAmnt += ($data['total_amount']);
                $totalIssueAmnt += $data['issue_amount'];
                $totalBalanceAmnt += $data['balance_amount'];
                ?>
                <tr>
                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                    <td class="vcenter">{!! $data['name'] !!}</td>
                    <td class="vcenter">{!! $data['location'] !!}</td>
                    <td class="text-center vcenter">{!! Helper::numberFormat($data['prev_date_balance_qty'],6) !!}</td>
                    <td class="text-center vcenter">{!! Helper::unitConversion($data['prev_date_balance_qty']) !!}</td>
                    <td class="text-center vcenter">{!! Helper::numberFormat($data['prev_date_balance_amount']) !!}</td>
                    <td class="text-center vcenter">{!! Helper::numberFormat($data['this_date_qty'],6) !!}</td>
                    <td class="text-center vcenter">{!! Helper::unitConversion($data['this_date_qty']) !!}</td>
                    <td class="text-center vcenter">{!! Helper::numberFormat($data['this_date_amount']) !!}</td>
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
        <table class="no-border">
            <tr>
                <td class="no-border text-left">@lang('label.REPORT_GENERATED_ON') {{ Helper::printDateTime(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}</td>
                <td class="no-border text-right col-md-6">
                    @lang('label.GENERATED_BY_RAJAKINI_SOFTWARE'),<span>&nbsp;@lang('label.POWERED_BY')</span><b>&nbsp;&nbsp;@lang('label.SWAPNOLOKE')</b>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function(event) {
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