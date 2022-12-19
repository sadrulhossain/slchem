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
                <h2>@lang('label.RECONCILIATION_REPORT')</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <ul class="padding-left-0">
                    <li class="list-style-item-none display-inline-block margin-top-10">
                        <span class="label label-md bold label-blue-steel">@lang('label.TOTAL_PRODUCT') : {!! !empty($productStatusArr['total']) ? $productStatusArr['total'] : 0 !!}</span>
                    </li>
                    <li class="list-style-item-none display-inline-block margin-top-10">
                        <span class="label label-md bold label-green-seagreen">@lang('label.TOTAL_MATCH') : {!! !empty($productStatusArr['match']) ? $productStatusArr['match'] : 0 !!}</span>
                    </li>
                    <li class="list-style-item-none display-inline-block margin-top-10">
                        <span class="label label-md bold label-red-soft">@lang('label.TOTAL_MISMATCH') : {!! !empty($productStatusArr['mismatch']) ? $productStatusArr['mismatch'] : 0 !!}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                <th class="vcenter" rowspan="2">@lang('label.PRODUCT_CATEGORY')</th>
                                <th class="vcenter" rowspan="2">@lang('label.NAME')</th>
                                <th class="vcenter" rowspan="2">@lang('label.PRODUCT_CODE')</th>
                                <th class="text-center" colspan="2">@lang('label.QUANTITY') (@lang('label.STOCK'))</th>
                                <th class="text-center" colspan="2">@lang('label.QUANTITY') (@lang('label.LEDGER'))</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.MATCH') / @lang('label.MISMATCH')</th>
                            </tr>
                            <tr>
                                <th class="text-center vcenter"><strong>(@lang('label.IN_KG'))</strong></th>
                                <th class="text-center vcenter"><strong>(@lang('label.DETAILS'))</strong></th>
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
                                <td class="text-right vcenter">{!! Helper::numberFormat($target->available_quantity,6) !!}</td>
                                <td class="text-right vcenter">{!! Helper::unitConversion($target->available_quantity) !!}</td>
                                <td class="text-right vcenter">{!! Helper::numberFormat($balanceArr[$target->id]['quantity'],6) !!}</td>
                                <td class="text-right vcenter">{!! Helper::unitConversion($balanceArr[$target->id]['quantity']) !!}</td>
                                <td class="text-center vcenter">
                                    @if($balanceArr[$target->id]['match'] == 1)
                                    <span class="badge badge-green-seagreen tooltips" title="@lang('label.MATCH')"><i class="fa fa-check"></i></span>
                                    @else
                                    <span class="badge badge-red-soft tooltips" title="@lang('label.MISMATCH')"><i class="fa fa-close"></i></span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
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

        <script src="{{asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}"  type="text/javascript"></script>


        <!-- BEGIN THEME GLOBAL SCRIPTS -->
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