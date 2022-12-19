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
                <h2>@lang('label.DB_BACKUP_DOWNLOAD_LOG_REPORT')</h2>
            </div>
        </div>

        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="bg-blue-hoki bg-font-blue-hoki">
                    <h5 style="padding: 10px;">
                        {{__('label.FROM_DATE')}} : <strong>{{ !empty($request->from_date) ? $request->from_date : __('label.N_A') }} |</strong> 
                        {{__('label.TO_DATE')}} : <strong>{{ !empty($request->to_date) ? $request->to_date : __('label.N_A') }} </strong>
                    </h5>
                </div>
            </div>
        </div>
        <table class="margin-top-10 table table-bordered table-striped table-head-fixer-color " id="dataTable">
            <thead>
                <tr class="blue-light">
                    <th class="text-center vcenter bold" rowspan="2">@lang('label.SL_NO')</th>
                    <th class="text-center vcenter bold" rowspan="2">@lang('label.DATE_TIME')</th>
                    <th class="text-center vcenter bold" rowspan="2">@lang('label.DOWNLOADED_BY')</th>
                    <th class="text-center vcenter bold" rowspan="2">@lang('label.DOWNLOADED_FILE')</th>
                </tr>
            </thead>
            <tbody>

                @if(!$logInfo->isEmpty())
                <?php $sl = 0; ?>
                @foreach($logInfo as $info)
                <tr>
                    <td class="text-center vcenter">{{++$sl}}</td>
                    <td class="text-center vcenter">{{date('d F Y h:i:s A', strtotime($info->log_time))}}</td>
                    <td class="vcenter">{{$info->user}}</td>
                    <td class="vcenter">{{$info->downloaded_file}}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="vcenter text-danger" colspan="4">@lang('label.NO_DATA_FOUND')</td>
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