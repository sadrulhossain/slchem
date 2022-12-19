@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.EDIT_RECIPE')
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::model($target,array('group' => 'form', 'class' => '','id' => 'editRecipe')) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {!! Form::hidden('id', $target->id) !!}
            {{csrf_field()}}
            <h4 class="font-red sbold">@lang('label.BASIC_INFO')</h4>
            <hr />
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="buyerId">@lang('label.SELECT_BUYER'):<span class="text-danger"> *</span></label>
                            {!! Form::select('buyer_id', $buyerArr, null, ['id' => 'buyerId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">@lang('label.DATE') :<span class="text-danger"> *</span></label>
                        <div class="input-group date datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" >
                            {!! Form::text('date', null, ['id'=> 'date', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                            <span class="input-group-btn">
                                <button class="btn default reset-date date" type="button" remove="date">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button class="btn default date-set" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="factoryId">@lang('label.SELECT_GARMENTS_FACTORY'):<span class="text-danger"> *</span></label>
                            {!! Form::select('factory_id', $factoryArr, null, ['id' => 'factoryId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="referenceNo">@lang('label.REFERENCE_NO'):<span class="text-danger"> *</span></label>
                            {!! Form::text('reference_no', null, ['id' => 'referenceNo', 'class' => 'form-control', 'readonly']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="seasonId">@lang('label.SEASON'):<span class="text-danger"> *</span></label>
                            {!! Form::select('season_id', $seasonArr, null, ['class' => 'form-control js-source-states','id' => 'seasonId']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="colorId">@lang('label.COLOR'):<span class="text-danger"> *</span></label>
                            {!! Form::select('color_id', $colorArr, null, ['class' => 'form-control js-source-states','id' => 'colorId']) !!}
                        </div>
                    </div>
                </div>
                <h4 class="font-red sbold">@lang('label.WASHING_MACHINE_INFO')</h4>
                <hr/>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="machineModelId">@lang('label.SELECT_WASHING_MACHINE_TYPE'):<span class="text-danger"> *</span></label>
                            {!! Form::select('machine_model_id', $machineModelArr, null, ['id' => 'machineModelId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="shadeId">@lang('label.SHADE'):<span class="text-danger"> *</span></label>
                            {!! Form::select('shade_id', $shadeList, null, ['id' => 'shadeId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="washLotQuantityWeight">@lang('label.WASH_LOT_QUANTITY'):<span class="text-danger"> *</span></label>
                            {!! Form::text('wash_lot_quantity_weight', null, ['id' => 'washLotQuantityWeight', 'class' => 'form-control interger-decimal-only wash-lot-calculator']) !!}
                            <span class="text-muted">(@lang('label.IN_KG'))</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="">&nbsp;</label>
                            {!! Form::text('wash_lot_quantity_piece', null, ['id' => 'washLotQuantityPiece', 'class' => 'form-control interger-decimal-only wash-lot-calculator']) !!}
                            <span class="text-muted">(@lang('label.IN_PCS'))</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="weightOnePiece">@lang('label.WEIGHT_OF_ONE_PCS_GMTS'): </label>
                            {!! Form::text('weight_one_piece', null, ['id' => 'weightOnePiece', 'class' => 'form-control', 'readonly']) !!}
                        </div>
                    </div>
                </div>
                <h4 class="font-red sbold">@lang('label.STYLE_INFO')</h4>
                <hr />
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="styleId">@lang('label.SELECT_STYLE'):<span class="text-danger"> *</span></label>
                            {!! Form::select('style_id', $styleArr, null, ['id' => 'styleId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="orderNumber">@lang('label.ORDER_NUMBER'):<span class="text-danger"> *</span></label>
                            {!! Form::text('order_no', null, ['id' => 'orderNumber', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="garmentsTypeId">@lang('label.SELECT_TYPE_OF_GARMENTS'):<span class="text-danger"> *</span></label>
                            {!! Form::select('garments_type_id', $garmentsTypeArr, null, ['id' => 'garmentsTypeId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="supplierId">@lang('label.FABRIC_SUPPLIER'):<span class="text-danger"> *</span></label>
                            {!! Form::text('supplier_id', null, ['id' => 'supplierId', 'class' => 'form-control','autocomplete' => 'off']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label" for="fabricRef">@lang('label.FABRIC_REFERENCE'): </label>
                            {!! Form::text('fabric_ref', null, ['id' => 'fabricRef', 'class' => 'form-control','autocomplete' => 'off','placeholder' => 'Code and Composition, e.g. 27% Ctn,23% Poly']) !!}
                        </div>
                    </div>
                </div>

                <h4 class="font-red sbold">@lang('label.DRYERS_INFO')</h4>
                <hr />
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="dryerTypeId">@lang('label.DRYER_TYPE') :<span class="text-danger"> *</span></label>
                            {!! Form::select('dryer_type_id', $dryerTypeArr, null, ['id'=> 'dryerTypeId', 'class' => 'form-control js-source-states']) !!}
                            <span class="text-danger">{{ $errors->first('dryer_type_id') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3" id="showDryerMachine">
                        <div class="form-group">
                            <label class="control-label" for="dryerMachineId">@lang('label.DRYER_MC_NO') :</label>
                            {!! Form::select('dryer_machine_id', $dryerMachineArr, null, ['id'=> 'dryerMachineId', 'class' => 'form-control js-source-states']) !!} 
                            <span class="text-danger">{{ $errors->first('dryer_machine_id') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="dryerTypeCapacity">@lang('label.DRYER_TYPE_CAPACITY'):<span class="text-danger"> *</span></label>
                            {!! Form::text('dryer_type', null, ['id' => 'dryerTypeCapacity', 'class' => 'form-control','readonly']) !!}
                            <span class="text-muted">(@lang('label.STEAM_GAS_N_CAPACITY'))</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="dryerLoadQty">@lang('label.DRYER_LOAD_QTY'):<span class="text-danger"> *</span></label>
                            {!! Form::text('dryer_load_qty', null, ['id' => 'dryerLoadQty', 'class' => 'form-control']) !!}
                            <span class="text-muted">(@lang('label.LOAD_QUANTITY_IN_PIECES_N_IN_KG'))</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="dryingTemperature">@lang('label.DRYING_TEMPERATURE'):<span class="text-danger"> *</span></label>
                            {!! Form::text('drying_temperature', null, ['id' => 'dryingTemperature', 'class' => 'form-control interger-decimal-only']) !!}
                            <span class="text-muted">(@lang('label.IN_DEGREE_CELSIUS'))</span>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="dryingTime">@lang('label.DRYING_TIME'):<span class="text-danger"> *</span></label>
                            {!! Form::text('drying_time', null, ['id' => 'dryingTime', 'class' => 'form-control interger-decimal-only']) !!}
                            <span class="text-muted">(@lang('label.IN_MINUTES'))</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="dryProcessInfo">@lang('label.DRY_PROCESS_INFO'): </label>
                            {!! Form::text('dry_process_info', null, ['id' => 'dryProcessInfo', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                <h4 class="font-red sbold">@lang('label.BULK_WASH_RECIPE')</h4>
                <hr />
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="processId">@lang('label.SELECT_PROCESS'):<span class="text-danger"> *</span></label>
                            {!! Form::select('process_id',$processedArr, null, ['id' => 'processId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>

                    <div id="showProductHolder" style="display: none;"></div>

                    <div id="waterRatioHolder" style="display: none;">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="mainWaterRatio">@lang('label.WATER_RATIO'):<span class="text-danger"> *</span> </label>
                                <div class="input-group prefix">                           
                                    <span class="input-group-addon">1 :</span>
                                    {!! Form::text('main_water_ratio', null, ['id' => 'mainWaterRatio', 'class' => 'form-control interger-decimal-only', 'maxlength' => '2']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="marging-top-20" id="addBtn">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="addItem">&nbsp;</label>
                                <span class="btn green tooltips" type="button" id="addItem"  title="@lang('label.ADD_ITEM')">
                                    <i class="fa fa-plus text-white"></i><span>  @lang('label.ADD_ITEM')</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="marging-top-20" id="updateBtn" style="display: none;">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="updateItem">&nbsp;</label>
                                <span class="btn green tooltips" type="button" id="updateItem"  title="@lang('label.UPDATE_ITEM')">
                                    <i class="fa fa-refresh text-white"></i><span>  @lang('label.UPDATE_ITEM')</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                </div>

                <div class="row margin-bottom-75">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label class="control-label" for="order">@lang('label.ORDER') :</label>
                            {!! Form::select('order', $orderList, null, ['class' => 'form-control js-source-states', 'id' => 'order']) !!} 
                            <span class="text-danger">{{ $errors->first('order') }}</span>
                        </div>
                    </div>
                </div>
                </br>

            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="recipeTable">
                                <thead>
                                    <tr>
                                        <th class="vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                        <th class="vcenter" rowspan="2">@lang('label.PROCESS')</th>
                                        <th class="vcenter" rowspan="2">@lang('label.PRODUCT_CHEMICAL_NAME')</th>
                                        <th class="vcenter text-center" colspan="3">@lang('label.FORMULA')</th>
                                        <th class="text-center vcenter" rowspan="2">@lang('label.DOSING_RATIO')</th>
                                        <th class="text-center vcenter" colspan="2">@lang('label.TOTAL_QTY') (@lang('label.IN_KG'))<span class="text-danger"> *</span></th>
                                        <th class="text-center vcenter" rowspan="2">@lang('label.WATER') </th>
                                        <th class="text-center vcenter" rowspan="2">@lang('label.PH')</th>
                                        <th class="text-center vcenter" rowspan="2">@lang('label.TEMP_DEGREE_CELSIUS') <span class="text-danger"> *</span></th>
                                        <th class="text-center vcenter" rowspan="2">@lang('label.WATER_RATIO')</th>
                                        <th class="text-center vcenter" rowspan="2">@lang('label.TIME_IN_MINUTES') <span class="text-danger"> *</span></th>
                                        <th class="text-center vcenter" rowspan="2">@lang('label.REMARKS') </th>
                                        <th class="text-center vcenter" rowspan="2">@lang('label.ACTION')</th>
                                    </tr>
                                    <tr>
                                        <th class="vcenter text-center">@lang('label.G_L')</th>
                                        <th class="vcenter text-center">%</th>
                                        <th class="vcenter text-center">@lang('label.DIRECT_AMOUNT')</th>
                                        <th class="vcenter text-center">@lang('label.IN_KG')</th>
                                        <th class="vcenter text-center">@lang('label.QTY_DETAILS')</th>
                                    </tr>
                                </thead>
                                <tbody id="itemRows">
                                    @if (!empty($targetArr))
                                    <?php
                                    $sl = 0;
                                    $trClassSl = 0;
                                    ?>
                                    @foreach($targetArr as $process)
                                    <?php
                                    //$identifier = uniqid(); 
                                    $identifier = $process['identifier'];
                                    ?>
                                    @if($process['process_type_id'] == '2')
                                    <tr class="process-header-{!! $identifier . '-' . $process['org_process_id'] !!} process-header  process-header-processHeader-{!! $identifier . '-' . $process['org_process_id'] !!} tr-order-{!! ++$trClassSl !!}">
                                        <td class=" vcenter process-counter" id="processHeader-{!! $identifier . '-' . $process['org_process_id'] !!}" data-name="{!! $process['process'] !!}">{{ ++$sl }}</td>
                                        <td class="vcenter" id="processHeader-{!! $identifier . '-' . $process['org_process_id'] !!}"><span>{!! $process['process'] !!}</span></td>
                                        <td class=" vcenter" colspan="8">
                                            {!! Form::text('dry_chemical[' . $identifier . '][' . $process['org_process_id'] . ']', $process['dry_chemical'], ['id' => 'dryChemical-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control','autocomplete' => 'off']) !!}
                                            {!! Form::hidden('qty[' . $identifier . '][' . $process['org_process_id'] . ']', 'dry', ['id' => 'qty-' . $identifier . '-' . $process['org_process_id']]) !!}
                                        </td>
                                        <td class="vcenter">
                                            {!! Form::text('ph[' . $identifier . '][' . $process['org_process_id'] . ']', $process['ph'], ['id' => 'dryPh-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control text-right','autocomplete' => 'off']) !!}
                                        </td>
                                        <td class="vcenter">
                                            {!! Form::text('temperature[' . $identifier . '][' . $process['org_process_id'] . ']', $process['temperature'], ['id' => 'dryTemperature-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
                                        </td>
                                        <td class="vcenter text-center">&nbsp;</td><!-- water ratio-->
                                        <td class="text-center vcenter">
                                            {!! Form::text('time[' . $identifier . '][' . $process['org_process_id'] . ']', $process['time'], ['id' => 'dryTime-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control text-right interger-decimal-only','autocomplete' => 'off']) !!}
                                        </td>
                                        <td class="text-center vcenter">
                                            {!! Form::textarea('remarks[' . $identifier . '][' . $process['org_process_id'] . ']', $process['remarks'], ['id' => 'dryRemarks-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control no-padding','autocomplete' => 'off', 'rows' => '2', 'cols' => '30']) !!}
                                        </td>
                                        <td class="text-center vcenter">
                                            <button id="remove-{!! $process['org_process_id'] !!}" class="btn btn-xs btn-danger tooltips vcenter tooltips remove-process" title="Remove Process" data-id="{!! $identifier . '-' . $process['org_process_id'] !!}"><i class="fa fa-trash text-white"></i></button>
                                            <button id="edit-{!! $process['org_process_id'] !!}" type="button" class="btn btn-xs btn-primary tooltips vcenter tooltips edit" title="Edit Process" data-id="{!! $identifier . '-' . $process['org_process_id'] !!}" data-order="{!! $trClassSl !!}"><i class="fa fa-edit text-white"></i></button>
                                        </td>
                                    </tr>
                                    @else

                                    @if($process['process_type_id'] == '1' && $process['water_type'] == '1')
                                    <tr class="process-header-{{$identifier.'-'. $process['org_process_id']}} process-header  process-header-processHeader-{!! $identifier . '-' . $process['org_process_id'] !!} tr-order-{!! ++$trClassSl !!}">
                                        <td class="vcenter process-counter" id="processHeader-{{$identifier . '-' . $process['org_process_id']}}"  data-name="{!! $process['process'] !!}">{{ ++$sl }}</td>
                                        <td class=" vcenter processHeader-{!! $identifier . '-' . $process['org_process_id'] !!}">
                                            <span>{!! $process['process'] !!}</span>
                                        </td>
                                        {!! Form::hidden('qty[' . $identifier . '][' . $process['org_process_id'] . ']', 'water', ['id' => 'qty-' . $identifier . '-' . $process['org_process_id']]) !!}

                                        <td class=" vcenter" colspan="7">
                                            @lang('label.WATER')
                                        </td>
                                        <td class="vcenter">
                                            {!! Form::text('water[' . $identifier . '][' . $process['org_process_id']. ']', $process['water'], ['id' => 'water-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control text-right water no-padding','readonly']) !!}
                                        </td>
                                        <td class="text-center vcenter">&nbsp;</td>
                                        <td class="text-center vcenter">
                                            {!! Form::text('temperature[' . $identifier . '][' . $process['org_process_id'] . ']', $process['temperature'], ['id' => 'temperature-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
                                        </td>
                                        <td class="vcenter text-center">1 : {!! $process['water_ratio'] !!}
                                            {!! Form::hidden('water_ratio[' . $identifier . '][' . $process['org_process_id'] . ']', $process['water_ratio'], ['id' => 'waterRatio-'.$identifier . '-' . $process['org_process_id']]) !!}
                                        </td>
                                        <td class="vcenter">
                                            {!! Form::text('time[' . $identifier . '][' . $process['org_process_id'] . ']', $process['time'], ['id' => 'time-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control text-right interger-decimal-only','autocomplete' => 'off']) !!}
                                        </td>
                                        <td class="vcenter">
                                            {!! Form::textarea('remarks[' . $identifier . '][' . $process['org_process_id'] . ']', $process['remarks'], ['id' => 'remarks-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control no-padding','autocomplete' => 'off', 'rows' => '2', 'cols' => '15']) !!}
                                        </td>
                                        <td class="text-center vcenter">
                                            <button id="remove-{!! $process['org_process_id'] !!}" class="btn btn-xs btn-danger tooltips vcenter tooltips remove-process" title="Remove Process" data-id="{!! $identifier . '-' . $process['org_process_id'] !!}"><i class="fa fa-trash text-white"></i></button>
                                            <button id="edit-{!! $process['org_process_id'] !!}" type="button" class="btn btn-xs btn-primary tooltips vcenter tooltips edit" title="Edit Process" data-id="{!! $identifier . '-' . $process['org_process_id'] !!}" data-order="{!! $trClassSl !!}"><i class="fa fa-edit text-white"></i></button>
                                        </td>
                                    </tr>
                                    @elseif($process['process_type_id'] == '1' && $process['water_type'] != '1')
                                    <tr class="process-header-{{$identifier.'-'. $process['org_process_id']}} process-header  process-header-processHeader-{!! $identifier . '-' . $process['org_process_id'] !!} tr-order-{!! ++$trClassSl !!}">
                                        <td class="vcenter process-counter" id="processHeader-{{$identifier . '-' . $process['org_process_id']}}" rowspan="{{count($productArr[$process['process_id']])}}" 
                                            data-name="{!! $process['process'] !!}">{{ ++$sl }}</td>
                                        <td class="vcenter" rowspan="{{count($productArr[$process['process_id']])}}">{{ $process['process'] }}</td>
                                        @if(isset($process['process_id']))
                                        <?php $i = 0; ?>

                                        @foreach($productArr[$process['process_id']] as $product)
                                        <?php
                                            $readonly = $totalQtyReadonly = '';
                                            $disabled = 'disabled';
                                            if(!empty($product['type_of_dosage_ratio']) && in_array($product['type_of_dosage_ratio'],[1,2])){
                                                $totalQtyReadonly = 'readonly';
                                            }
                                            $qtyReadonly = !empty($product['type_of_dosage_ratio']) ? ($product['type_of_dosage_ratio'] == '3') ? 'readonly':'':'';
                                            $qtyRead = empty($product['type_of_dosage_ratio']) ? 'readonly' : '';
                                        ?>
                                        <td class="vcenter">
                                            {{ $product['name'] }}
                                            @if(empty($product['type_of_dosage_ratio']))
                                            <i class="fa fa-warning tooltips pull-right text-danger" title="@lang('label.DOSAGE_RATIO_IS_NOT_SET_FOR_THIS_PRODUCT')"></i>
                                            @endif
                                            {!! Form::hidden('edit_product', $product['product_id'], ['class' => 'edit-product-'.$identifier . '-' . $process['org_process_id']] )!!}
                                        </td>
                                        <td>
                                            <label class="radio-container">
                                                {{ Form::radio('formula[' . $identifier . '][' . $process['org_process_id'] . '][' . $product['product_id'] . ']', '1' , !empty($product['type_of_dosage_ratio']) && $product['type_of_dosage_ratio'] == '1' ? true : false,['id' => 'gL-'.$identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'class' => 'formula selected-formula-'. $identifier . '-' . $process['org_process_id'].'-'.$product['product_id'], 'data-formula' => '1', 'data-process-product-id' => $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'data-process-id' => $identifier . '-' . $process['org_process_id'],$disabled ]) }}
                                                {{ Form::hidden('formula[' . $identifier . '][' . $process['org_process_id'] . '][' . $product['product_id'] . ']',  !empty($product['type_of_dosage_ratio'])? $product['type_of_dosage_ratio'] : '',['id' => 'gL-'.$identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'class' => 'formula selected-formula-'. $identifier . '-' . $process['org_process_id'].'-'.$product['product_id'], 'data-formula' => '1', 'data-process-product-id' => $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'data-process-id' => $identifier . '-' . $process['org_process_id']]) }}
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="radio-container">
                                                {{ Form::radio('formula[' . $identifier . '][' . $process['org_process_id'] . '][' . $product['product_id'] . ']', '2' , !empty($product['type_of_dosage_ratio']) && $product['type_of_dosage_ratio'] == '2' ? true : false,['id' => 'percent-'.$identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'class' => 'formula selected-formula-'.$identifier . '-' . $process['org_process_id'].'-'.$product['product_id'], 'data-formula' => '2', 'data-process-product-id' => $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'data-process-id' => $identifier . '-' . $process['org_process_id'],$disabled ]) }}
                                                {{ Form::hidden('formula[' . $identifier . '][' . $process['org_process_id'] . '][' . $product['product_id'] . ']', !empty($product['type_of_dosage_ratio']) ?  $product['type_of_dosage_ratio'] : '',['id' => 'percent-'.$identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'class' => 'formula selected-formula-'.$identifier . '-' . $process['org_process_id'].'-'.$product['product_id'], 'data-formula' => '2', 'data-process-product-id' => $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'data-process-id' => $identifier . '-' . $process['org_process_id']]) }}
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="radio-container">
                                                {{ Form::radio('formula[' . $identifier . '][' . $process['org_process_id']. '][' . $product['product_id'] . ']', '3' , !empty($product['type_of_dosage_ratio']) && $product['type_of_dosage_ratio'] == '3' ? true : false,['id' => 'directAmount-'.$identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'class' => 'formula direct-amount-formula selected-formula-'.$identifier . '-' . $process['org_process_id'].'-'.$product['product_id'], 'data-formula' => '3', 'data-process-product-id' => $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'data-process-id' => $identifier . '-' . $process['org_process_id'],$disabled ]) }}
                                                {{ Form::hidden('formula[' . $identifier . '][' . $process['org_process_id']. '][' . $product['product_id'] . ']', !empty($product['type_of_dosage_ratio']) ?  $product['type_of_dosage_ratio'] : '',['id' => 'directAmount-'.$identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'class' => 'formula direct-amount-formula selected-formula-'.$identifier . '-' . $process['org_process_id'].'-'.$product['product_id'], 'data-formula' => '3', 'data-process-product-id' => $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'data-process-id' => $identifier . '-' . $process['org_process_id']]) }}
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td id="" class="vcenter">
                                            {!! Form::text('qty[' . $identifier . '][' . $process['org_process_id'] . '][' . $product['product_id'] . ']', !empty($product['type_of_dosage_ratio']) && in_array($product['type_of_dosage_ratio'],[1,2]) ? $product['qty'] : '', ['id' => 'qty-' . $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'class' => 'form-control integer-decimal-only no-padding qty text-center selected-qty-'.$identifier . '-' . $process['org_process_id'].'-'.$product['product_id'],'autocomplete' => 'off', 'data-process-product-id' => $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'data-process-id' => $identifier . '-' . $process['org_process_id'], $readonly,$qtyRead,$qtyReadonly,'placeholder' => !empty($product['from_dosage'] && $product['to_dosage']) ? Helper::numberFormat($product['from_dosage'],1).' - '.Helper::numberFormat($product['to_dosage'],1) : '']) !!}
                                             {!! Form::hidden('from_dosage', $product['from_dosage'], ['id' => 'fromDosage-'.$identifier . '-' . $process['org_process_id'].'-' .$product['product_id'] ])!!}
                                            {!! Form::hidden('to_dosage', $product['to_dosage'], ['id' => 'toDosage-'.$identifier . '-' . $process['org_process_id'].'-' .$product['product_id']] )!!}
            
                                        </td>
                                        <td class="vcenter">
                                            {!! Form::text('total_qty[' . $identifier . '][' . $process['org_process_id'] . '][' . $product['product_id'] . ']', !empty($product['type_of_dosage_ratio']) ? $product['total_qty'] : '', ['id' => 'totalQty-' . $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'class' => 'form-control integer-decimal-only total-qty no-padding text-right selected-total_qty-'.$identifier . '-' . $process['org_process_id'].'-'.$product['product_id'], 'data-process-product-id' => $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'],  $readonly,$totalQtyReadonly]) !!}
                                        </td>
                                        <td class="vcenter">
                                            {!! Form::text('total_qty_detail[' . $identifier . '][' . $process['org_process_id'] . '][' . $product['product_id'] . ']', !empty($product['type_of_dosage_ratio']) ? Helper::unitConversion($product['total_qty']) : '', ['id' => 'totalQtyDetail-' . $identifier . '-' . $process['org_process_id'] . '-' . $product['product_id'], 'class' => 'form-control no-padding text-right selected-total_qty_detail-'.$identifier . '-' . $process['org_process_id'].'-'.$product['product_id'], 'readonly' => 'readonly']) !!}
                                        </td>
                                        @if($i == 0)
                                        <td rowspan="{!! count($productArr[$process['process_id']]) !!}" class="vcenter">
                                            {!! Form::text('water[' . $identifier . '][' . $process['org_process_id']. ']', $process['water'], ['id' => 'water-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control text-right water no-padding','readonly']) !!}
                                        </td>
                                        <td class="vcenter" rowspan="{!! count($productArr[$process['process_id']]) !!}">
                                            {!! Form::text('ph[' . $identifier . '][' . $process['org_process_id'] . ']', $process['ph'], ['id' => 'ph-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
                                        </td>
                                        <td class="vcenter" rowspan="{{count($productArr[$process['process_id']])}}">
                                            {!! Form::text('temperature[' . $identifier . '][' . $process['org_process_id'] . ']', $process['temperature'], ['id' => 'temperature-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
                                        </td>
                                        <td rowspan="{!!count($productArr[$process['process_id']]) !!}" class="vcenter text-right">1 : {!! $process['water_ratio'] !!}
                                            {!! Form::hidden('water_ratio[' . $identifier . '][' . $process['org_process_id'] . ']', $process['water_ratio'], ['id' => 'waterRatio-'.$identifier . '-' . $process['org_process_id']]) !!}
                                        </td>

                                        <td class="vcenter text-right" rowspan="{{count($productArr[$process['process_id']])}}">
                                            {!! Form::text('time[' . $identifier . '][' . $process['org_process_id'] . ']', $process['time'], ['id' => 'time-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
                                        </td>
                                        <td class="vcenter" rowspan="{!!count($productArr[$process['process_id']]) !!}">
                                            {!! Form::textarea('remarks[' . $identifier . '][' . $process['org_process_id'] . ']', $process['remarks'], ['id' => 'remarks-' . $identifier . '-' . $process['org_process_id'], 'class' => 'form-control no-padding','autocomplete' => 'off', 'rows' => '2', 'cols' => '15']) !!}
                                        </td>
                                        <td class="vcenter text-center" rowspan="{{count($productArr[$process['process_id']])}}">
                                            <button id="remove-{{$identifier.'-'. $process['org_process_id']}}" class="btn btn-xs btn-danger tooltips vcenter tooltips remove-process" title="Remove Process" data-id="{{$identifier.'-'. $process['org_process_id']}}"><i class="fa fa-trash text-white"></i>
                                            </button>
                                            <button id="edit-{!! $process['org_process_id'] !!}" type="button" class="btn btn-xs btn-primary tooltips vcenter tooltips edit" title="Edit Process" data-id="{!! $identifier . '-' . $process['org_process_id'] !!}" data-order="{!! $trClassSl !!}"><i class="fa fa-edit text-white"></i>
                                            </button>
                                        </td>
                                        @endif
                                    </tr>
                                    @if($i != (count($productArr[$process['process_id']]) -1))
                                    <tr class="process-header-{{$identifier.'-'. $process['org_process_id']}}">
                                        @endif
                                        <?php $i++; ?> 
                                        @endforeach
                                        @endif
                                        @endif
                                        @endif
                                        @endforeach
                                        @if(!empty($washTypeToWaterArr))
                                        @foreach($washTypeToWaterArr as $washKey => $waterVal)	
                                    <tr>
                                        <td colspan="9" class="text-right"><strong>@lang('label.TOTAL_WATER') @lang('label.OF') {!! $washTypeArr[$washKey] !!}</strong></td>
                                        <td class="text-right"><input type="text" class="form-control text-right no-padding" id="washTypeWater" readonly value="{{ $waterVal }}" /></td>
                                        <td colspan="6">&nbsp;</td>
                                    </tr>
                                    @endforeach
                                    @endif    
                                    <tr id="netTotalRow">
                                        <td colspan="9" class="text-right vcenter"><strong>@lang('label.TOTAL_WATER')</strong></td>
                                        <td id="netTotal" class="text-right"><input type="text" class="form-control text-right no-padding" id="totalWater" readonly value="{{ $totalWater }}" /></td>
                                        <td colspan="6">&nbsp;</td>
                                    </tr>
                                    @else
                                    <tr id="hideNodata">
                                        <td colspan="16">@lang('label.NO_DATA_SELECT_YET')</td>
                                    </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="editRowId" value="">
                    <input type="hidden" id="total" value="">
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 form">
                    <label class="control-label col-md-3" for="washTypeId">@lang('label.WASH_TYPE') :<span class="text-danger"> *</span>
                    </label>
                    <div class="col-md-5">
                        <select class="form-control js-source-states" name="wash_type_id" id="washTypeId">
                            @foreach($washTypeArr as $washTypeId => $washTypeName)
                            <option value="{!! $washTypeId !!}" id="washTypeId"
                            <?php
                            if (array_key_exists($washTypeId, $processedWashTypeArr)) {
                                echo 'disabled="disabled"';
                            } else {
                                echo '';
                            }
                            ?>>
                                {!! $washTypeName !!} 
                            </option>
                            @endforeach
                        </select>
                        <span class="text-danger">{{ $errors->first('wash_type_id') }}</span>
                    </div>
                </div>
                <div class="col-md-5 form">
                    <label class="control-label col-md-3" for="processNo">@lang('label.PROCESS'):<span class="text-danger">*</span></label>
                    <div class="col-md-5">
                        <select class="form-control mt-multiselect btn btn-default" name="process[]" id="processNo" multiple data-width ='100%'>
                            @foreach($processInfoArr as $processId => $processName)

                            <option value="{!! $processId !!}" id="processNo"
                            <?php
                            $processedIdArr = explode('-', $processId);
                            $processedId = $processedIdArr[0];

                            foreach ($processedWashTypeArr as $washTypeId => $processIdInfo) {
                                if (array_key_exists($processedId, $processIdInfo)) {
                                    echo 'disabled="disabled"';
                                } else {
                                    echo '';
                                }
                            }
                            ?>>
                                {!! $processName !!} 
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row margin-bottom-75 col-md-2" id="addItemBtn">
                    <div class="form">
                        <label class="control-label" for="addProcess">&nbsp;</label>
                        <span class="btn green tooltips" type="button" id="addProcess"  title="@lang('label.ADD_ITEM')">
                            <i class="fa fa-plus text-white"></i>&nbsp;<span>@lang('label.ADD_ITEM')</span>
                        </span>
                    </div>
                </div>

                <div class="row margin-bottom-75 col-md-2" id="updateItemBtn" style="display: none;">
                    <div class="form">
                        <label class="control-label" for="updateProcess">&nbsp;</label>
                        <span class="btn green tooltips" type="button" id="updateProcess"  title="@lang('label.UPDATE_ITEM')">
                            <i class="fa fa-refresh text-white"></i>&nbsp;<span>@lang('label.UPDATE_ITEM')</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b><u>@lang('label.WASH_TYPE_TO_PROCESS_LIST'):</u></b></p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter">@lang('label.WASH_TYPE')</th>
                                    <th class="vcenter">@lang('label.PROCESS')</th>
                                    <th class="vcenter">@lang('label.ACTION')</th>
                                </tr>
                            </thead>
                            <tbody id="processRows">
                                @if(!empty($processedWashTypeArr))
                                <?php
                                $countNumber = 1;
                                $newIdentifier = '';
                                ?>
                                @foreach($processedWashTypeArr as $washTypeId => $processIdInfo)
                                <tr class="item-list-{!! $washTypeId .'_'.$countNumber !!}" id="rowId_{!! $washTypeId . '_' . $countNumber !!}">
                                    <td>{!! $washTypeArr[$washTypeId] !!}</td>
                                    <td>
                                        <?php
                                        $processName = '';
                                        $processNewId = '';
                                        $slash = ' || ';
                                        $comma = ',';
                                        $i = 1;
                                        ?>
                                        @foreach($processIdInfo as $key => $processId)

                                        <?php
                                        $processName .= $processNameList[$processId];
                                        $processNewId .= $key . '-' . $processId;
                                        if (count($processIdInfo) > 1) {
                                            $processName .= $slash;
                                            $processNewId .= $comma;
                                        }//if
                                        $i++;
                                        if (count($processIdInfo) == $i) {
                                            $slash = '';
                                            $comma = '';
                                        }
                                        ?>
                                        @endforeach
                                        @if(!empty($washTypeToWaterArr))
                                        @foreach($washTypeToWaterArr as $washKey => $waterVal)
                                        <input type="hidden" id="waterVal_{!! $washKey.'_'.$countNumber !!}" name="wash_water[{!! $washKey !!}]" value="{!! $waterVal !!}">
                                        @endforeach
                                        @endif

                                        <input type="hidden" id="processNo_{!! $washTypeId .'_'.$countNumber !!}" name="process_no[{!! $washTypeId !!}]" value="{!! $processNewId !!}">

                                        <input type="hidden" id="washTypeId_{!! $washTypeId .'_'.$countNumber !!}" name="wash_type_id[]"  value="{!! $washTypeId !!}">
                                        {!! $processName !!}
                                    </td>
                                    <td class="text-center">
                                        <button onclick="editProcess({!! $washTypeId.','.$countNumber !!});" class="btn btn-xs btn-primary tooltips vcenter" id="editBtn{!!$washTypeId .'_'. $countNumber !!}" title="@lang('label.EDIT_PRODUCT')" data-id="{!! $processNewId !!}"><i class="fa fa-edit text-white"></i></button>
                                        <button onclick="deleteItem({!! $washTypeId.','.$countNumber !!});" class="btn btn-xs btn-danger tooltips vcenter" id="deleteBtn{!! $washTypeId .'_'. $countNumber !!}"  title="@lang('label.REMOVE_ITEM')"><i class="fa fa-trash text-white"></i></button>
                                    </td>
                                </tr>
                                <?php $countNumber++; ?>
                                @endforeach
                                @else
                                <tr id="hideNoProcessdata">
                                    <td colspan="3">@lang('label.NO_DATA_SELECT_YET')</td>
                                </tr>
                                @endif 
                                <tr id="divNoProcessdata" style="display:none;">
                                    <td colspan="3">@lang('label.NO_DATA_SELECT_YET')</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id="editRowsId" value="">
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-circle red button-update-submit" type="submit" id="saveDraft" value="1">
                            <i class="fa fa-edit"></i> @lang('label.SAVE_AS_DRAFT')
                        </button>
                        <a href="{{ URL::to('recipe'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- END BORDERED TABLE PORTLET-->
@include('recipe.editJs')
<script type="text/javascript">
            $(document).ready(function() {
    //add default order value
    var rowCount = $('tbody#itemRows tr.process-header').length;
            //set order by insertion value
            var options = rowCount;
            for (i = 1; i <= options; i++) {
    $('#order').append($("<option></option>")
            .attr("value", i)
            .text(i));
    }

    $('#productId').multiselect();
    });
</script>
@stop

