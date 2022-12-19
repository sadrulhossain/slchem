<html>

    <head>
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico"/>
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" media="all"/>
    </head>

    <body>
        <div class="row">
            <div class="col-xs-12 text-center">
                <img src="{{URL::to('/')}}/public/img/Sterling_Laundry_Logo.png" alt="sterling-laundry-logo"/>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center">
                <h6 class="bold">@lang('label.DEMAND_PRINT_HEADER_TWO')</h6>
            </div>
        </div>
        <!--        <div class="row">
                    <div class="col-xs-5 text-center">
                        <h6 class="bold">@lang('label.PRODUCTION_BATCH_CARD')</h6>
                    </div>
                </div>-->

        <div class="row">
            <div class="col-xs-offset-4 col-xs-4">
                <table class="table table-bordered border-style box-size">
                    <tr class="text-center">
                        <td colspan="2"><h6><strong>@lang('label.PRODUCTION_BATCH_CARD')</strong></h6></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-offset-2 col-xs-8">
                <table class="table table-bordered box-size">
                    <tr class="text-center info">
                        <td><h6><strong>@lang('label.BATCH_WASH_MC_NO')</strong></h6></td>
                        <td class="text-center vcenter" width="100"><h6><strong>{{ $batchCardArr->machine }}</strong></h6></td>
                        <td><h6><strong>@lang('label.BATCH_LOT_WEIGHT')</strong></h6></td>
                        <td class="vcenter"><h6><strong>{{ $recipeArr->wash_lot_quantity_weight }}</strong></h6></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <table class="table table-bordered box-size">
                    <tbody>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_REFERENCE_NO')</strong></h6></td>
                            <td class="vcenter" width="50%"><strong>{{ $batchCardArr->reference_no }}</strong></td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_DATE')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->date }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_FACTORY')</strong></h6></td>
                            <td class="vcenter" width="50%"><strong>{{ $recipeArr->factory }}</strong></td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_BUYER')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $recipeArr->buyer }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_STYLE')</strong></h6></td>
                            <td class="vcenter" width="50%"><strong>{{ $recipeArr->style }}</strong></td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_COLOR')</strong></h6></td>
                            <td class="vcenter" width="50%"><b>{{ $recipeArr->color }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 pull-right">
                <table class="table table-bordered box-size">
                    <tbody>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_OPARETOR_NAME')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->operator_name }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_WASH_TYPE')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->wash_type_name }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_SHIFT')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ !empty($batchCardArr->shift_id)?$shiftArr[$batchCardArr->shift_id]:'' }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_LOT_QTY_PIECE')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $recipeArr->wash_lot_quantity_piece }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_MACHINE_IN_TIME')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->machine_in_time }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_MACHINE_OUT_TIME')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->machine_out_time }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">

                <table class="table table-bordered box-size">
                    <tbody>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_HYDRO_M/C_NO')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->hydro_machine }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.HYDRO_IN_TIME')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->hydro_in_time }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.HYDRO_OUT_TIME')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->hydro_out_time }}</td>
                        </tr>
                        <tr class="bold">
                            <td colspan="2" class="text-center"><h6><strong>@lang('label.QUALITY_ASSURANCE_REPORT')</strong></h6></td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_OK_QKY')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->ok_qty }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_NOT_OK_QTY')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->not_ok_qty }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 pull-right">
                <table class="table table-bordered box-size">
                    <tbody>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_DRYING_TEMPERATURE')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $recipeArr->drying_temperature }}   @lang('label.DEGREE_CELSIUS')</td>
                        </tr>
                        <tr>
                            <td width="50%"><h6><strong>@lang('label.BATCH_DRYER_M/C_NO')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $recipeArr->dryer_machine }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_DRYER_TYPE')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $recipeArr->dryer_type }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_DRYER_IN_TIME')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->in_time }}</td>
                        </tr>
                        <tr>
                            <td><h6><strong>@lang('label.BATCH_DRYER_OUT_TIME')</strong></h6></td>
                            <td class="vcenter" width="50%">{{ $batchCardArr->out_time }}</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <table class="table no-border box-size">
                    <tr class="info no-border">
                        <td class="no-border"><h6><strong>@lang('label.BATCH_REMARKS') : &nbsp;&nbsp;&nbsp;&nbsp;{{ $batchCardArr->remarks }}</strong></h6></td>

                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <table class="no-border">
                <tr>
                    <td class="no-border text-left col-xs-6">
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        -------------------------
                        <br/>
                        @lang('label.SHIFT_IN_CHARGE')
                    </td>
                    <td class="no-border text-right col-xs-6">
                        <br/>
                        <br/>
                        <br/><br/>
                        -------------------------
                        <br/>
                        @lang('label.QC/QA/QM')
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row">
        <div class="col-xs-12">
            <table class="no-border">
                <tr>
                    <td class="no-border text-left col-xs-6">
                        @lang('label.PREPARED_ON')
                        {{ Helper::printDateFormat(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                    </td>
                    <td class="no-border text-right col-xs-6">
                        @lang('label.GENERATED_BY_RAJAKINI_SOFTWARE')
                        ,<span>&nbsp;@lang('label.POWERED_BY')</span><b>&nbsp;&nbsp;@lang('label.SWAPNOLOKE')</b>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function (event) {
            window.print();
        });
    </script>
</body>
</html>