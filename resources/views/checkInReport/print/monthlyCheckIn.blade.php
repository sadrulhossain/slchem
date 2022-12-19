<html>
    <head>
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="row">
            <div class="col-md-12 text-center check-padding">
                <img src="{{URL::to('/')}}/public/img/Sterling_Laundry_Logo.png" alt="sterling-laundry-logo"/><br/>
                @lang('label.DEMAND_PRINT_HEADER_TWO')<br/>
                <h2 class="bold">@lang('label.MONTHLY_CHECK_IN_REPORT') ({!! date('F- Y',strtotime(Request::get('checkin_month'))) !!})</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
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
                            <td colspan="15" class="vcenter">@lang('label.NO_PRODUCT_FOUND_TO_CHECKIN_AT_THIS_MONTH')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
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
    </body>
</html>
<style>
    @media print {
        @page {
            size: landscape
        }
    }
</style>