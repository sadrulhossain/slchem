<html>

    <head>
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <div class="header">
            @lang('label.RECIPE_DETAILS')<br />
            @lang('label.RECIPE_PRINT_HEADER_ONE')<br />
            @lang('label.RECIPE_PRINT_HEADER_TWO')
        </div>

        <table class="no-border recipe-table">
            <thead>  
                <tr class="info header-color">
                    <td colspan="4"><strong>@lang('label.RECIPE_REFERENCE_NUMBER') : {!! $target->reference_no !!}</strong></td>
                </tr>
                <tr>
                    <td width="25%">@lang('label.DATE')</td>
                    <td width="25%">{!! Helper::dateFormat($target->date) !!}</td>
                    <td width="25%">@lang('label.STYLE_NAME')</td>
                    <td width="25%"><strong>{!! $target->style !!}</strong></td>
                </tr>
                <tr>
                    <td>@lang('label.BUYER')</td>
                    <td>{!! $target->buyer !!}</td>
                    <td class="vcenter">@lang('label.FACTORY')</td>
                    <td class="vcenter"><strong>{!! $target->factory !!}</strong></td>
                </tr>
                <tr>
                    <td>@lang('label.ORDER_NUMBER')</td>
                    <td>{!! $target->order_no !!}</td>
                    <td>@lang('label.SEASON')</td>
                    <td>{!! $target->season !!}</td>
                </tr>
                <tr>
                    <td>@lang('label.TYPE_OF_GARMENTS')</td>
                    <td>{!! $target->garments_type !!}</td>
                    <td>@lang('label.COLOR')</td>
                    <td><b>{!! $target->color !!}</b></td>
                </tr>
                <tr>
                    <td>@lang('label.FABRIC_SUPPLIER')</td>
                    <td>{!! $target->supplier_id !!}</td>
                    @if(!empty($target->wash))
                    <td class="vcenter">@lang('label.WASH')</td>
                    <td class="vcenter">{!! $target->wash !!}</td>
                    @else
                    <td class="vcenter">@lang('label.WASH_TYPE')</td>
                    <td class="vcenter"><strong>{!! $target->wash_type !!}</strong></td>		
                    @endif
                </tr>
                <tr>
                    <td>@lang('label.FABRIC_REF')</td>
                    <td>{!! $target->fabric_ref !!}</td>
                    <td>@lang('label.SHADE')</td>
                    <td><strong>{!! $target->shade_name !!}</strong></td>
                </tr>
                <tr class="info header-color">
                    <td colspan="2"><strong>@lang('label.WASHING_MACHINE_INFO')</strong></td>
                    <td colspan="2"><strong>@lang('label.DRYERS_INFO')</strong></td>
                </tr>
                <tr>
                    <td>@lang('label.WASH_MACHINE_TYPE_N_CAPACITY')</td>
                    <td>{!! $target->machine_model !!}</td>
                    <td>@lang('label.DRYER_TYPE_STEAM_GAS_N_CAPACITY')</td>
                    <td>{!! $target->dryer_type !!}</td>
                </tr>
                <tr>
                    <td>@lang('label.WASH_LOAD_QUANTITY_IN_KG_N_IN_PCS')</td>
                    <td class="vcenter"><strong>{!! $target->wash_lot_quantity_weight !!} &amp; {!!$target->wash_lot_quantity_piece !!}</strong></td>
                    <td>@lang('label.DRYER_LOAD_QTY_IN_PCS_N_IN_KG')</td>
                    <td>{!! $target->dryer_load_qty !!}</td>
                </tr>
                <tr>
                    <td>@lang('label.WASHING_MACHINE_RPM')</td>
                    <td>{!! $target->rpm !!}</td>
                    <td>@lang('label.DRYING_TEMPERATURE')</td>
                    <td>{!! $target->drying_temperature !!} &deg;C</td>
                </tr>
                <tr>
                    <td>@lang('label.WEIGHT_OF_ONE_PCS_GMTS')</td>
                    <td>{!! $target->weight_one_piece !!}</td>
                    <td>@lang('label.DRYING_TIME')</td>
                    <td>{!! $target->drying_time !!} @lang('label.MINUTES')</td>
                </tr>
                <tr>
                    <td class="vcenter" colspan="2">@lang('label.DRY_PROCESS_INFO') : {!! $target->dry_process_info !!}</td>
                    <td class="vcenter">@lang('label.DRYER_TYPE')</td>
                    <td class="vcenter">{!! $target->dryer_type_name !!}</td>
                </tr>
        </table>

        <table class="no-border recipe-table">
            <thead>
                <tr class="info header-color">
                    <th class="text-center" colspan="13">@lang('label.BULK_WASH_RECIPE')</th>
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
                    <td class="vcenter text-center">&nbsp;</td><!-- total qty details -->
                    <td class="vcenter text-center">{{ $process['water'] }}</td>
                    <td class="vcenter text-center">&nbsp;</td><!--ph -->
                    <td class="vcenter text-center">{{ $process['temperature'] }}</td>
                    <td class="vcenter text-center">1:{{ $process['water_ratio'] }}</td>
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
                    <td class="vcenter text-right">{{ $product['qty'] }}</td>
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
        <br/>
        <table class="no-border">
            <tr>
                <td class="no-border text-left col-md-4">

                    -------------------------
                    <br/>
                    @lang('label.CHEMIST')
                </td>
                <td class="no-border text-right col-md-4">

                    -------------------------<br/>
                    @lang('label.INCHARGE/APM')
                </td>
                <td class="no-border text-right col-md-4">

                    -------------------------<br/>
                    @lang('label.DGM/GM')
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