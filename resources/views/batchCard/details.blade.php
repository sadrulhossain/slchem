<div class="modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.VIEW_BATCH_CARD_DETAILS')</strong></h4>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>
        @if(!empty($userAccessArr[46][6]))
        <a href="{{ URL::to('recipe/getDetails/'.$batchCardArr->recipe_id.'?view=print') }}" target="_blank" class="btn btn-md btn-info pull-right margin-right-10">
            <i class="fa fa-print text-white"></i> @lang('label.RECIPE_PRINT')
        </a>
        <a href="{{ URL::to('batchCard/getDetails/' .$batchCardArr->id.'?view=print') }}" target="_blank" class="btn btn-md btn-success pull-right margin-right-10">
            <i class="fa fa-print text-white"></i> @lang('label.BATCH_CARD_PRINT')
        </a>
        @endif
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <!--                        <div class="col-md-4 text-center">
                                                    <img src="{{URL::to('/')}}/public/img/sterling-Group.png" alt="sterling-group-logo"/>
                                                </div>-->
                        <!--                        <div class="col-md-12 text-center">
                                                    <h2 class="bold">@lang('label.STERLING_LAUNDRY_GROUP_LIMITED')</h2>
                                                </div>-->
                        <img src="{{URL::to('/')}}/public/img/Sterling_Laundry_Logo.png" alt="sterling-laundry-logo"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h5 class="bold">@lang('label.DEMAND_PRINT_HEADER_TWO')</h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <table class="table table-bordered border-style">
                            <tr class="text-center">
                                <td colspan="2"><h4><strong>@lang('label.PRODUCTION_BATCH_CARD')</strong></h4></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <table class="table table-bordered">
                            <tr class="text-center info">
                                <td><h4><strong>@lang('label.BATCH_WASH_MC_NO')</strong></h4></td>
                                <td class="text-center vcenter" width="100"><h4><strong>{{ $batchCardArr->machine }}</strong></h4></td>
                                <td><h4><strong>@lang('label.BATCH_LOT_WEIGHT')</strong></h4></td>
                                <td class="vcenter"><h4><strong>{{ $recipeArr->wash_lot_quantity_weight }}</strong></h4></td>
                            </tr>

                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_REFERENCE_NO')</strong></h4></td>
                                    <td class="vcenter" width="50%"><strong>{{ $batchCardArr->reference_no }}</strong></td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_DATE')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->date }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_FACTORY')</strong></h4></td>
                                    <td class="vcenter" width="50%"><strong>{{ $recipeArr->factory }}</strong></td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_BUYER')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $recipeArr->buyer }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_STYLE')</strong></h4></td>
                                    <td class="vcenter" width="50%"><strong>{{ $recipeArr->style }}</strong></td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_COLOR')</strong></h4></td>
                                    <td class="vcenter" width="50%"><b>{{ $recipeArr->color }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 pull-right">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_OPARETOR_NAME')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->operator_name }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_WASH_TYPE')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->wash_type_name }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_SHIFT')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ !empty($batchCardArr->shift_id)?$shiftArr[$batchCardArr->shift_id]:'' }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_LOT_QTY_PIECE')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $recipeArr->wash_lot_quantity_piece }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_MACHINE_IN_TIME')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->machine_in_time }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_MACHINE_OUT_TIME')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->machine_out_time }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_HYDRO_M/C_NO')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->hydro_machine }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.HYDRO_IN_TIME')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->hydro_in_time }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.HYDRO_OUT_TIME')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->hydro_out_time }}</td>
                                </tr>
                                <tr class="bold">
                                    <td colspan="2" class="text-center"><h4><strong>@lang('label.QUALITY_ASSURANCE_REPORT')</strong></h4></td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_OK_QKY')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->ok_qty }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_NOT_OK_QTY')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->not_ok_qty }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 pull-right">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_DRYING_TEMPERATURE')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $recipeArr->drying_temperature }}   @lang('label.DEGREE_CELSIUS')</td>
                                </tr>
                                <tr>
                                    <td width="50%"><h4><strong>@lang('label.BATCH_DRYER_M/C_NO')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $recipeArr->dryer_machine }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_DRYER_TYPE')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $recipeArr->dryer_type }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_DRYER_IN_TIME')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->in_time }}</td>
                                </tr>
                                <tr>
                                    <td><h4><strong>@lang('label.BATCH_DRYER_OUT_TIME')</strong></h4></td>
                                    <td class="vcenter" width="50%">{{ $batchCardArr->out_time }}</td>
                                </tr>



                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <tr class="info">
                                <td><h4><strong>@lang('label.BATCH_REMARKS') : &nbsp;&nbsp;&nbsp;&nbsp;{{ $batchCardArr->remarks }}</strong></h4></td>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="pull-left col-md-6">
                        <br/>
                        <br/>

                        -------------------------
                        <br/>
                        @lang('label.SHIFT_IN_CHARGE')
                    </div>
                    <div class="pull-right col-md-6">
                        <br/>
                        <br/>
                        -------------------------
                        <br/>
                        @lang('label.QC/QA/QM')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
</div>