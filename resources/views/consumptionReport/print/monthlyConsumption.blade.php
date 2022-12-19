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
                <h2 class="bold">@lang('label.MONTHLY_CONSUMPTION_REPORT')</h2>
            </div>
        </div>
        <div class="report-title">
            <p>@lang('label.ADJUSTMENT_MONTH'): {!! date('F- Y',strtotime($request->checkout_month.'-01')) !!}</p>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
                <tr class="header-color">
                    <th class="vcenter" rowspan = "2">@lang('label.CHECK_OUT_DATE')</th>
                    <th rowspan = "2">@lang('label.NAME')</th>
                    <th class="text-center" colspan="2">@lang('label.QUANTITY')</th>
                </tr>
                <tr class="header-color">
                    <th class="vcenter">@lang('label.IN_KG')</th>
                    <th>@lang('label.QTY_DETAILS')</th>
                </tr>
            </thead>
            <tbody>
                @if (!($targetArr)->isEmpty())
                @foreach($targetArr as $target)
                <tr>
                    <td class="vcenter">{!! Helper::dateFormat($target->adjustment_date)  !!}</td>
                    <td class="vcenter">{!! $target->name !!}</td>
                    <td class="text-right vcenter">{!! Helper::numberFormat($target->quantity,6) !!}</td>
                    <td class="text-right vcenter">{!! Helper::unitConversion($target->quantity) !!}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="5" class="vcenter">@lang('label.NO_PRODUCT_FOUND_FOR_ADJUSTMENT')</td>
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
    </body>