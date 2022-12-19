@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CREATE_NEW_RECIPE')
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'class' => '','id' => 'createRecipe')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}} 
            <h4 class="font-red sbold">@lang('label.BASIC_INFO')</h4>
            <hr />
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="buyerId">@lang('label.SELECT_BUYER'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('buyer_id', $buyerArr, null, ['id' => 'buyerId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="control-label">@lang('label.DATE') :<span class="text-danger"> *</span>
                        </label>
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
                            <label class="control-label" for="factoryId">@lang('label.SELECT_GARMENTS_FACTORY'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('factory_id', $factoryArr, null, ['class' => 'form-control js-source-states','id' => 'factoryId']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="referenceNo">@lang('label.REFERENCE_NO'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::text('reference_no', null, ['id' => 'referenceNo', 'class' => 'form-control', 'autocomplete' => 'off', 'readonly']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!--<div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="washId">@lang('label.SELECT_WASH'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('wash_id', $washArr, null, ['id' => 'washId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="seasonId">@lang('label.SEASON'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('season_id', $seasonArr, null, ['class' => 'form-control js-source-states','id' => 'seasonId']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="colorId">@lang('label.COLOR'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('color_id', $colorArr, null, ['class' => 'form-control js-source-states','id' => 'colorId']) !!}
                        </div>
                    </div>
                </div>

                <h4 class="font-red sbold">@lang('label.WASHING_MACHINE_INFO')</h4>
                <hr />
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="machineModelId">@lang('label.SELECT_WASHING_MACHINE_TYPE'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('machine_model_id', $machineModelArr, null, ['id' => 'machineModelId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="shadeId">@lang('label.SHADE'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('shade_id', $shadeList, null, ['id' => 'shadeId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="washLotQuantityWeight">@lang('label.WASH_LOT_QUANTITY'):<span class="text-danger"> *</span>
                            </label>
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
                            <label class="control-label" for="styleId">@lang('label.SELECT_STYLE'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('style_id', $styleArr, null, ['id' => 'styleId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="orderNumber">@lang('label.ORDER_NUMBER'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::text('order_no', null, ['id' => 'orderNumber', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="garmentsTypeId">@lang('label.SELECT_TYPE_OF_GARMENTS'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('garments_type_id', $garmentsTypeArr, null, ['id' => 'garmentsTypeId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="supplierId">@lang('label.FABRIC_SUPPLIER'):<span class="text-danger"> *</span>
                            </label>
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
                            <label class="control-label" for="dryerTypeId">@lang('label.DRYER_TYPE') :<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('dryer_type_id', $dryerTypeArr, null, ['id' => 'dryerTypeId','class' => 'form-control js-source-states']) !!}
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
                            <label class="control-label" for="dryerTypeCapacity">@lang('label.DRYER_TYPE_CAPACITY'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::text('dryer_type', null, ['id' => 'dryerTypeCapacity', 'class' => 'form-control','readonly']) !!}
                            <span class="text-muted">(@lang('label.STEAM_GAS_N_CAPACITY'))</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="dryerLoadQty">@lang('label.DRYER_LOAD_QTY'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::text('dryer_load_qty', null, ['id' => 'dryerLoadQty', 'class' => 'form-control']) !!}
                            <span class="text-muted">(@lang('label.LOAD_QUANTITY_IN_PIECES_N_IN_KG'))</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="dryingTemperature">@lang('label.DRYING_TEMPERATURE'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::text('drying_temperature', null, ['id' => 'dryingTemperature', 'class' => 'form-control interger-decimal-only']) !!}
                            <span class="text-muted">(@lang('label.IN_DEGREE_CELSIUS'))</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="dryingTime">@lang('label.DRYING_TIME'):<span class="text-danger"> *</span>
                            </label>
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

                <h4 class="font-red bold">@lang('label.BULK_WASH_RECIPE')</h4>
                <hr/>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="processId">@lang('label.SELECT_PROCESS'):<span class="text-danger"> *</span>
                            </label>
                            {!! Form::select('process_id', $processArr, null, ['id' => 'processId', 'class' => 'form-control js-source-states']) !!}
                        </div>
                    </div>

                    <div id="showProductHolder" style="display: none;"></div>

                    <div id="waterRatioHolder" style="display: none;" class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="waterRatio">@lang('label.WATER_RATIO'):<span class="text-danger"> *</span>
                            </label>
                            <div class="input-group prefix">                           
                                <span class="input-group-addon">1 :</span>
                                {!! Form::text('main_water_ratio', null, ['id' => 'mainWaterRatio', 'class' => 'form-control interger-decimal-only', 'maxlength' => '2']) !!}
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
                                            <th class="text-center vcenter" colspan="2">@lang('label.TOTAL_QTY') (@lang('label.IN_KG')) <span class="text-danger"> *</span>
                                            </th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.WATER') </th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.PH')</th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.TEMP_DEGREE_CELSIUS') <span class="text-danger"> *</span>
                                            </th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.WATER_RATIO')</th>
                                            <th class="text-center vcenter" rowspan="2">@lang('label.TIME_IN_MINUTES') <span class="text-danger"> *</span>
                                            </th>
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
                                        <tr id="hideNodata">
                                            <td colspan="16">@lang('label.NO_DATA_SELECT_YET')</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="editRowId" value="">
                        <input type="hidden" id="total" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="form">
                        <label class="control-label col-md-1" for="washTypeId">@lang('label.WASH_TYPE') :<span class="text-danger"> *</span>
                        </label>
                        <div class="col-md-3">
                            {!! Form::select('wash_type_id', $washTypeArr, null, ['id' => 'washTypeId','class' => 'form-control js-source-states']) !!}
                            <span class="text-danger">{{ $errors->first('wash_type_id') }}</span>
                        </div>
                    </div>
                    <div class="form">
                        <label class="control-label col-md-1" for="processNo">@lang('label.PROCESS'):<span class="text-danger">*</span></label>
                        <div class="col-md-3">
                            {!! Form::select('process[]', $processList, null, ['class' => 'form-control mt-multiselect btn btn-default', 'id' => 'processNo','multiple','data-width' => '100%']) !!} 
                        </div>
                    </div>
                    <div class="row margin-bottom-75" id="addItemBtn">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label" for="addProcess">&nbsp;</label>
                                <span class="btn green tooltips" type="button" id="addProcess"  title="@lang('label.ADD_ITEM')">
                                    <i class="fa fa-plus text-white"></i><span>&nbsp;&nbsp;@lang('label.ADD_ITEM')</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-bottom-75" id="updateItemBtn" style="display: none;">
                        <div class="form-group">
                            <label class="control-label" for="updateProcess">&nbsp;</label>
                            <span class="btn green tooltips" type="button" id="updateProcess"  title="@lang('label.UPDATE_ITEM')">
                                <i class="fa fa-refresh text-white"></i><span>&nbsp;&nbsp;@lang('label.UPDATE_ITEM')</span>
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
                                    <tr id="divNoProcessdata">
                                        <td colspan="6">@lang('label.NO_DATA_SELECT_YET')</td>
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
                            {!! Form::hidden('add_btn', '1', ['id' => 'addBtn']) !!}
                            <button class="btn btn-circle red button-submit" type="submit" id="saveDraft" value="1" disabled>
                                <i class="fa fa-edit"></i> @lang('label.SAVE_AS_DRAFT')
                            </button>

                            <a href="{{ URL::to('recipe'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <!-- END BORDERED TABLE PORTLET-->
    </div>
</div>
@include('recipe.createJs')
@stop