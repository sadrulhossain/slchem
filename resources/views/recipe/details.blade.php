<div class="modal-header clone-modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.RECIPE_DETAILS')</strong></h4>
    </div>

    <div class="col-md-6">
        <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>
        @if(!empty($userAccessArr[44][6]))
        <a href="{{ URL::to('recipe/getDetails/'.$target->id.'?view=print') }}" target="_blank" class="btn btn-md btn-success pull-right margin-right-10">
            <i class="fa fa-print text-white"></i> @lang('label.PRINT')
        </a>
        @endif
        @if(!empty($userAccessArr[44][17]))
        <a class="btn btn-md btn-warning pull-right tooltips margin-right-10" title="Click here to Download PDF" href="{{ URL::to('recipe/getDetails/'.$target->id.'?view=pdf') }}">
            <i class="fa fa-file-pdf-o"></i> @lang('label.PDF_DOWNLOAD')
        </a>
        @endif
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-hover">
                <tr class="info">
                    <td class="vcenter bold" colspan="4">
                        @lang('label.RECIPE_REFERENCE_NUMBER') : {!! $target->reference_no !!}
                    </td>
                </tr>
                <tr>
                    <td class="vcenter" width="25%">@lang('label.DATE')</td>
                    <td class="vcenter" width="25%">{!! Helper::dateFormat($target->date) !!}</td>
                    <td class="vcenter" width="25%">@lang('label.STYLE_NAME')</td>
                    <td class="vcenter" width="25%"><strong>{!! $target->style !!}</strong></td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.BUYER')</td>
                    <td class="vcenter">{!! $target->buyer !!}</td>
                    <td class="vcenter">@lang('label.FACTORY')</td>
                    <td class="vcenter"><strong>{!! $target->factory !!}</strong></td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.ORDER_NUMBER')</td>
                    <td class="vcenter">{!! $target->order_no !!}</td>
                    <td class="vcenter">@lang('label.SEASON')</td>
                    <td class="vcenter">{!! $target->season !!}</td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.TYPE_OF_GARMENTS')</td>
                    <td class="vcenter">{!! $target->garments_type !!}</td>
                    <td class="vcenter">@lang('label.COLOR')</td>
                    <td class="vcenter"><b>{!! $target->color !!}</b></td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.FABRIC_REF')</td>
                    <td class="vcenter">{!! $target->fabric_ref !!}</td>
                    @if(!empty($target->wash))
                    <td class="vcenter">@lang('label.WASH')</td>
                    <td class="vcenter">{!! $target->wash !!}</td>
                    @else
                    <td class="vcenter"></td>
                    <td class="vcenter"></td>
                    @endif                
                </tr>
                <tr>
                    <td>@lang('label.FABRIC_SUPPLIER')</td>
                    <td>{!! $target->supplier_id !!}</td>
                    <td>@lang('label.SHADE')</td>
                    <td><strong>{!! $target->shade_name !!}</strong></td>
                </tr>

                <tr class="info">
                    <td class="vcenter bold" colspan="2">@lang('label.WASHING_MACHINE_INFO')</td>
                    <td class="vcenter bold" colspan="2">@lang('label.DRYERS_INFO')</td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.WASH_MACHINE_TYPE_N_CAPACITY')</td>
                    <td class="vcenter">{!! $target->machine_model !!}</td>
                    <td class="vcenter">@lang('label.DRYER_TYPE_STEAM_GAS_N_CAPACITY')</td>
                    <td class="vcenter">{!! $target->dryer_type !!}</td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.WASH_LOAD_QUANTITY_IN_KG_N_IN_PCS')</td>
                    <td class="vcenter"><strong>{!! $target->wash_lot_quantity_weight !!} &amp; {!!$target->wash_lot_quantity_piece !!}</strong></td>
                    <td class="vcenter">@lang('label.DRYER_LOAD_QTY_IN_PCS_N_IN_KG')</td>
                    <td class="vcenter">{!! $target->dryer_load_qty !!}</td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.WASHING_MACHINE_RPM')</td>
                    <td class="vcenter">{!! $target->rpm !!}</td>
                    <td class="vcenter">@lang('label.DRYING_TEMPERATURE')</td>
                    <td class="vcenter">{!! $target->drying_temperature !!} &deg;C</td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.WEIGHT_OF_ONE_PCS_GMTS')</td>
                    <td class="vcenter">{!! $target->weight_one_piece !!}</td>
                    <td class="vcenter">@lang('label.DRYING_TIME')</td>
                    <td class="vcenter">{!! $target->drying_time !!} @lang('label.MINUTES')</td>
                </tr>
                <tr>
                    <td class="vcenter" colspan="2">@lang('label.DRY_PROCESS_INFO') : {!! $target->dry_process_info !!}</td>
                    <td class="vcenter">@lang('label.DRYER_TYPE')</td>
                    <td class="vcenter">{!! $target->dryer_type_name !!}</td>
                </tr>
            </table>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="info header-color">
                            <th class="text-center bold" colspan="13">@lang('label.BULK_WASH_RECIPE')</th>
                        </tr>
                        <tr>
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.PROCESS')</th>
                            <th class="vcenter">@lang('label.PRODUCT')</th>
                            <th class="vcenter text-center">@lang('label.FORMULA')</th>
                            <th class="vcenter text-center">@lang('label.DOSING_RATIO')</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_QTY') (@lang('label.IN_KG'))</th>
                            <th class="vcenter text-center">@lang('label.TOTAL_QTY') (@lang('label.DETAILS'))</th>
                            <th class="text-center vcenter">@lang('label.WATER_IN_LTR')</th>
                            <th class="text-center vcenter">@lang('label.PH')</th>
                            <th class="text-center vcenter">@lang('label.TEMP_DEGREE_CELSIUS')</th>
                            <th class="text-center vcenter">@lang('label.WATER_RATIO')</th>
                            <th class="text-center vcenter">@lang('label.TIME_IN_MINUTES')</th>
                            <th class="text-center vcenter">@lang('label.REMARKS')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($targetArr))
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($targetArr as $process)

                        @if($process['process_type_id'] == '2')
                        <tr>
                            <td class="vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $process['process'] }}</td>
                            <td class="vcenter" colspan="6">{{ $process['dry_chemical'] }}</td>
                            <td class="vcenter text-center">{{ $process['ph'] }}</td>
                            <td class="vcenter text-center">{{ $process['temperature'] }}</td>
                            <td class="vcenter text-center">&nbsp;</td><!--water ratio -->
                            <td class="vcenter text-center">{{ $process['time'] }}</td>
                            <td class="vcenter text-center">{{ $process['remarks'] }}</td>
                        </tr>
                        @else

                        @if($process['process_type_id'] == '1' && $process['water_type'] == '1')
                        <tr>
                            <td class="vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $process['process'] }}</td>
                            <td class="vcenter">@lang('label.WATER')</td>
                            <td class="vcenter text-center">&nbsp;</td><!-- formula -->
                            <td class="vcenter text-center">&nbsp;</td><!--qty -->
                            <td class="vcenter text-center">&nbsp;</td><!-- total qty -->
                            <td class="vcenter text-center">&nbsp;</td><!-- total qty detail -->
                            <td class="vcenter text-center">{{ $process['water'] }}</td>
                            <td class="vcenter text-center">&nbsp;</td><!--ph -->
                            <td class="vcenter text-center">{{ $process['temperature'] }}</td>
                            <td class="vcenter text-center">1:{{ $process['water_ratio'] }}</td><!--water ratio -->
                            <td class="vcenter text-center">{{ $process['time'] }}</td>
                            <td class="vcenter text-center">{{ $process['remarks'] }}</td>
                        </tr>
                        @elseif($process['process_type_id'] == '1' && $process['water_type'] != '1') 
                        <tr>
                            <td class="vcenter" rowspan="{{count($productArr[$process['process_id']])}}">{{ ++$sl }}</td>
                            <td class="vcenter" rowspan="{{count($productArr[$process['process_id']])}}">{{ $process['process'] }}</td>
                            @if(isset($process['process_id']))
                            <?php $i = 0; ?>

                            @foreach($productArr[$process['process_id']] as $product)
                            <td class="vcenter">{{ $product['name'] }}</td>
                            <td class="text-center vcenter">
                                <span class="label label-sm label-{{ $formulaArr[$product['formula']]['label']}}">{!! $formulaArr[$product['formula']]['formula'] !!}</span>
                            </td>
                            @if($product['formula'] == '3')
                            <td class="vcenter text-right">&nbsp;</td>
                            @else
                            <td class="vcenter text-center">{{ $product['qty'] }}</td>
                            @endif
                            <td class="vcenter text-right">{{ Helper::numberformat($product['total_qty'],6) }}</td>
                            <td class="vcenter text-right">{{ Helper::unitConversion($product['total_qty']) }}</td>
                            @if($i == 0)
                            <td class="vcenter text-center" rowspan="{{count($productArr[$process['process_id']])}}">{{ $process['water'] }}</td>
                            <td class="vcenter text-center" rowspan="{{count($productArr[$process['process_id']])}}">{{ $process['ph'] }}</td>
                            <td class="vcenter text-center" rowspan="{{count($productArr[$process['process_id']])}}">{{ $process['temperature'] }}</td>
                            <td class="vcenter text-center" rowspan="{{count($productArr[$process['process_id']])}}">1:{{ $process['water_ratio'] }}</td>
                            @endif

                            @if($i == 0)
                            <td class="vcenter text-center" rowspan="{{count($productArr[$process['process_id']])}}">{{ $process['time'] }}</td>
                            <td class="vcenter text-center" rowspan="{{count($productArr[$process['process_id']])}}">{{ $process['remarks'] }}</td>
                            @endif
                            <?php $i++; ?>
                        </tr>
                        @endforeach
                        @endif
                        @endif

                        @endif
                        @endforeach
                        @if(!empty($washTypeToWaterArr))
                        @foreach($washTypeToWaterArr as $washKey => $waterVal)	
                        <tr>
                            <td colspan="7" class="text-right"><strong>@lang('label.TOTAL_WATER') @lang('label.OF') {!! $washTypeArr[$washKey] !!}</strong></td>
                            <td class="text-center"><strong>{!! $waterVal !!}</strong></td>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        @endforeach
                        @endif
                        <tr>
                            <td colspan="7" class="text-right"><strong>@lang('label.TOTAL_WATER')</strong></td>
                            <td class="text-center"><strong>{!! $totalWater !!}</strong></td>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if(!empty($processedWashTypeArr))
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b><u>@lang('label.WASH_TYPE_TO_PROCESS_LIST'):</u></b></p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter">@lang('label.SL_NO')</th>
                                    <th class="vcenter">@lang('label.WASH_TYPE')</th>
                                    <th class="vcenter">@lang('label.PROCESS')</th>
                                </tr>
                            </thead>
                            <tbody id="processRows">

                                <?php
                                $serial = 0;
                                ?>
                                @foreach($processedWashTypeArr as $washTypeId => $processIdInfo)
                                <tr>
                                    <td width="10%">{!! ++$serial !!}</td>
                                    <td>{!! $washTypeArr[$washTypeId] !!}</td>
                                    <td>
                                        <?php
                                        $processName = '';
                                        $slash = '0';
                                        $i = 1;
                                        ?>
                                        @foreach($processIdInfo as $key => $processId)

                                        <?php
                                        if (count($processIdInfo) >= 1) {
                                            ++$slash;
                                            $processName.= $slash . '. ';
                                        }//if
                                        $i++;
                                        // if (count($processIdInfo) == $i) {
                                        // $slash = '1';
                                        // $processName .= $slash
                                        // }
                                        $processName .= $processNameList[$processId] . '<br/>';
                                        ?>
                                        @endforeach
                                        {!! $processName !!}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            @endif	
        </div>
    </div>
</div>
<div class="modal-footer">
    @if(!empty($userAccessArr[44][11]))
    @if($target->approval_status == '1')
    <button class="btn green finalize-recipe" type="button" id="finalizeBtn" value="1" data-id="{!! $target->id !!}">
        <i class="fa fa-check"></i> @lang('label.FINALIZE')
    </button>
    @endif
    @endif
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
</div>
<script type="text/javascript">
    // Confirmation for Draft Recipe make Finalize
    $(document).on('click', '.finalize-recipe', function(e) {
        e.preventDefault();
        var recipeId = $(this).data("id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        swal({
            title: "@lang('label.RECIPE_FINALIZE_WARNING')",
            type: "warning",
            text: "@lang('label.FURTHER_MODIFY_TITLE')",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('label.YES_FINALIZE')",
            closeOnConfirm: true,
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{URL::to('recipe/finalize')}}",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        recipe_id: recipeId
                    },
                    beforeSend: function() {
                        App.blockUI({
                            boxed: true
                        });
                    },
                    success: function(res) {
                        toastr.success(res.message, res.heading, options);
                        location.reload();
                        App.unblockUI();
                    },
                    error: function(jqXhr, ajaxOptions, thrownError) {
                        if (jqXhr.status == 400) {
                            var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function(key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                        } else if (jqXhr.status == 401) {
                            toastr.error(jqXhr.responseJSON.message, '', options);
                        } else {
                            toastr.error('Error', 'Something went wrong', options);
                        }
                        App.unblockUI();
                    }
                });
            }

        });
    });
</script>
