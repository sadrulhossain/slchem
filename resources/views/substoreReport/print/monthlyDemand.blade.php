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
                <p><b>@lang('label.MONTHLY_SUBSTORE_DEMAND_REPORT') {!! 'for '. '('. date('F- Y',strtotime($request->substore_month.'-01')).')' !!}</b></p>

            </div>
        </div>

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
                    <td class="text-center" colspan="5">
                        <strong>@lang('label.DATE'):&nbsp;{!! Helper::dateFormat($date) !!} 
                            | @lang('label.REFERENCE_NO'):&nbsp;{!! $voucherNo !!}</strong>
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