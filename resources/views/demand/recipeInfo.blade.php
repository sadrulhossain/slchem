<div class="row">
    <div class="col-md-12">
        <h3><b>@lang('label.RECIPE_INFO'):</b></h3>
    </div>
</div>

<table class="table table-bordered table-hover">
    <tr>
        <td class="vcenter" width="25%">@lang('label.MACHINE_NO') (@lang('label.WASHING_MACHINE_TYPE'))</td>
        <td class="vcenter" width="25%">{!! $batchCardInfo->machine_no.' ('.$batchCardInfo->model.')' !!}</td>
        <td class="vcenter" width="25%">@lang('label.BATCH_DATE')</td>
        <td class="vcenter" width="25%">
            {!! $batchCardInfo->date !!}
            {!! Form::hidden('date', $batchCardInfo->date) !!}
        </td>
    </tr>
    <tr>
        <td class="vcenter">@lang('label.STYLE')</td>
        <td class="vcenter"><strong>{!! $batchCardInfo->style !!}</strong></td>
        <td class="vcenter">@lang('label.BUYER')</td>
        <td class="vcenter">{!! $batchCardInfo->buyer !!}</td>
    </tr>
    <tr>
        <td class="vcenter">@lang('label.TYPE_OF_GARMENTS')</td>
        <td class="vcenter">{!! $batchCardInfo->garments_type !!}</td>
        <td class="vcenter">@lang('label.NO_OF_GARMENTS')</td>
        <td class="vcenter"><strong>{!! $batchCardInfo->wash_lot_quantity_weight.' KG / '.$batchCardInfo->wash_lot_quantity_piece.' PCS' !!}</strong></td>
    </tr>
</table>
<div class="row">
    <div class="col-md-12">
        <div class="text-right margin-bottom-20">
            <span class="label label-md bg-red-sunglo">@lang('label.WILL_BE_GENERATED')</span>
            <span class="label label-md bg-green-turquoise">@lang('label.ALREADY_GENERATED')</span>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr class="info">
                <th class="text-center bold" colspan="14">@lang('label.BULK_WASH_RECIPE')</th>
            </tr>
            <tr class="info">
                @if($batchCardInfo->demand_finish != '1')
                <th class="vcenter text-center">
                    <div class="md-checkbox has-success tooltips"  data-placement="top" data-rel="tooltip" data-original-title="CheckAll">
                        {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check']) !!}
                        <label for="checkAll">
                            <span class="inc"></span>
                            <span class="check"></span>
                            <span class="box"></span>
                        </label>
                    </div>
                </th>
                @endif
                <th class="vcenter">@lang('label.SL_NO')</th>
                <th class="vcenter">@lang('label.PROCESS')</th>
                <th class="vcenter">@lang('label.PRODUCT')</th>
                <th class="vcenter">@lang('label.FORMULA')</th>
                <th class="text-center vcenter">@lang('label.DOSING_RATIO')</th>
                <th class="text-center vcenter">@lang('label.TOTAL_QTY')&nbsp; (@lang('label.IN_KG'))</th>
                <th class="text-center vcenter">@lang('label.TOTAL_QTY')&nbsp; (@lang('label.DETAILS'))</th>
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
            @if($process['water_type'] == '1')
            <tr>
                <td class="text-center vcenter">{{ ++$sl }}</td>
                <td class="vcenter text-center">{{ $process['process'] }}</td>
                <td class="vcenter text-center">@lang('label.WATER')</td>
                <td class="vcenter text-center">&nbsp;</td><!--doge -->
                <td class="vcenter text-center">{{ $process['water'] }}</td>
                <td class="vcenter text-center">&nbsp;</td><!--ph -->
                <td class="vcenter text-center">{{ $process['temperature'] }}</td>
                <td class="vcenter text-center">&nbsp;</td><!--lr -->
                <td class="vcenter text-center">{{ $process['time'] }}</td>
                <td class="vcenter text-center">{{ $process['remarks'] }}</td>
            </tr>
            @else 
            <?php
            $rowColor = !in_array($process['id'], $prevDemandArr) ? "reds" : "greens";
            ?>

            <tr class="{{ $rowColor }}">
                @if($batchCardInfo->demand_finish != '1')
                <td class="vcenter text-center" rowspan="{{count($productArr[$process['id']])}}">
                    @if(!in_array($process['id'],$prevDemandArr))
                    <div class="md-checkbox">
                        {!! Form::checkbox('rtp_id['.$process['id'].']', $process['id'], null, ['id' => 'processNo-'. $process['id'],'data-rtp-id'=> $process['id'],'class' => 'md-check process-check']) !!}
                        <label for="processNo-{!! $process['id'] !!}">
                            <span class="inc"></span>
                            <span class="check"></span>
                            <span class="box"></span>
                        </label>
                    </div>
                    @endif
                </td>
                @endif
                <td class="text-center vcenter" rowspan="{{count($productArr[$process['id']])}}">{{ ++$sl }}</td>
                <td class="vcenter text-center" rowspan="{{count($productArr[$process['id']])}}">{{ $process['process'] }}</td>
                <?php $i = 0; ?>
                @foreach($productArr[$process['id']] as $product)

                <td class="vcenter text-center">{{ $product['name'] }}</td>
                <td class="text-center vcenter">
                    <span class="label label-sm label-{{ $formulaArr[$product['formula']]['label']}}">{!! $formulaArr[$product['formula']]['formula'] !!}</span>
                </td>
                <td class="vcenter text-center">{{ isset($product['qty']) ? $product['qty'] : '' }}</td>
                <td class="vcenter text-center">{{ Helper::numberformat($product['total_qty'], 6) }}</td>
                <td class="vcenter text-center">{{ Helper::unitConversion($product['total_qty']) }}</td>
                @if($i == 0)
                <td class="vcenter text-center" rowspan="{{count($productArr[$process['id']])}}">{{ $process['water'] }}</td>
                <td class="vcenter text-center" rowspan="{{count($productArr[$process['id']])}}">{{ $process['ph'] }}</td>
                <td class="vcenter text-center" rowspan="{{count($productArr[$process['id']])}}">{{ $process['temperature'] }}</td>
                <td class="vcenter text-center" rowspan="{{count($productArr[$process['id']])}}">1:{{ $process['water_ratio'] }}</td>
                @endif
                @if($i == 0)
                <td class="vcenter text-center" rowspan="{{count($productArr[$process['id']])}}">{{ $process['time'] }}</td>
                <td class="vcenter text-center" rowspan="{{count($productArr[$process['id']])}}">{{ $process['remarks'] }}</td>
<!--                <td class="vcenter text-center" rowspan="{{count($productArr[$process['id']])}}">
                    @if(!in_array($process['id'],$prevDemandArr))
                    <div class="md-checkbox">
                        {!! Form::checkbox('rtp_id['.$process['id'].']', $process['id'], null, ['id' => 'processNo-'. $process['id'],'class' => 'md-check']) !!}
                        <label for="processNo-{!! $process['id'] !!}">
                            <span class="inc"></span>
                            <span class="check"></span>
                            <span class="box"></span>
                        </label>
                    </div>
                    @endif
                </td>-->
                @endif
            </tr>
            <tr class="{{ $rowColor }}">
                <?php $i++; ?>
                @endforeach
                @endif
                @endforeach
                @endif

        </tbody>
    </table>
</div>


<style type="text/css">
    .mt-element-step .step-line .mt-step-number{
        font-size: 18px;
        height: 50px;
        width: 50px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('#checkAll').change(function () { //'check all' change
            $('.process-check').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status
            if (this.checked) {
                $('#saveDemandPaper').prop('disabled', false);
            } else {
                $('#saveDemandPaper').prop('disabled', true);
            }
        });

        /**** START:: Click Single Process ****/
        $('.process-check').change(function () { //'check all' change
            if (this.checked) {
                $('#saveDemandPaper').prop('disabled', false);
            } else {
                var flag = 0;
                $(".process-check").each(function () {
                    if (this.checked) {
                        flag = 1;
                    }
                });
                if (flag == 1) {
                    $('#saveDemandPaper').prop('disabled', false);
                } else {
                    $('#saveDemandPaper').prop('disabled', true);
                }
            }
        });
        /**** END:: Click Single Process ****/


<?php if ($batchCardInfo->demand_finish != '1') { ?>
            $('#saveDemandPaper').prop('disabled', false);//Make button disabled
<?php } else { ?>
            $('#saveDemandPaper').prop('disabled', true);//Make button disabled

<?php } ?>

    });
</script>    