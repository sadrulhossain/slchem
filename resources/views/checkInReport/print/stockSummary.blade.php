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
                <br/>
                @lang('label.DEMAND_PRINT_HEADER_TWO')
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>@lang('label.STOCK_SUMMARY_REPORT')</h2>
            </div>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
					<th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
					<th class="vcenter" rowspan="2">@lang('label.PRODUCT_CATEGORY')</th>
					<th class="vcenter" rowspan="2">@lang('label.NAME')</th>
					<th rowspan="2">@lang('label.PRODUCT_CODE')</th>
					<th class="text-center" colspan="2">@lang('label.QUANTITY')</th>
				</tr>
				<tr>
					<th class="text-center vcenter"><strong>(@lang('label.IN_KG'))</strong></th>
					<th class="text-center vcenter"><strong>(@lang('label.DETAILS'))</strong></th>
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
                    <td class="vcenter">{!! $target->product_category !!}</td>
                    <td class="vcenter">{!! $target->product !!}</td>
                    <td class="vcenter">{!! $target->product_code !!}</td>
                    <td class="text-center vcenter">{!! Helper::numberFormat($target->available_quantity,6) !!}</td>
					<td class="text-center vcenter">{!! Helper::unitConversion($target->available_quantity) !!}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="6" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
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
    </body>