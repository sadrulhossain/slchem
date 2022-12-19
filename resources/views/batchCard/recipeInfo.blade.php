<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::hidden('recipe_origin_id', $target->origin_id) }}
                <label class="control-label col-md-2" for="washTypeId">@lang('label.WASH_TYPE') :<span class="text-danger"> *</span></label>
                <div class="col-md-3">
                    {!! Form::select('wash_type_id', $processedWashTypeArr, null,['id'=> 'washTypeId', 'class' => 'form-control js-source-states']) !!} 
                    <span class="text-danger">{{ $errors->first('wash_type_id') }}</span>
                </div>
                <label class="control-label col-md-2" for="factory">@lang('label.FACTORY') : </label>
                <div class="col-md-2">
                    {!! Form::text('factory', $target->factory, ['id'=> 'factory', 'class' => 'form-control', 'readonly']) !!} 
                </div>
                <label class="control-label col-md-1" for="buyer">@lang('label.BUYER') : </label>
                <div class="col-md-2">
                    {!! Form::text('buyer', $target->buyer, ['id'=> 'buyer', 'class' => 'form-control', 'readonly']) !!} 
                </div>
                <!--                <label class="control-label col-md-2" for="style">@lang('label.STYLE') : </label>
                                <div class="col-md-2">
                                    {!! Form::text('style', $target->style, ['id'=> 'style', 'class' => 'form-control', 'readonly']) !!}
                                </div>-->

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <!--
                <label class="control-label col-md-2" for="wash">@lang('label.WASH') : </label>
<div class="col-md-3">
    {!! Form::text('wash', $target->wash, ['id'=> 'wash', 'class' => 'form-control', 'readonly']) !!}
</div>--> 
                <label class="control-label col-md-2" for="washType">@lang('label.WASHING_MACHINE_TYPE') : </label>
                <div class="col-md-3">
                    {!! Form::text('machine', $target->machine_model, ['id'=> 'washType', 'class' => 'form-control', 'readonly']) !!} 
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-2" for="machineId">@lang('label.WASH_MC_NO') :<span class="text-danger"> *</span></label>
                <div class="col-md-3">
                    {!! Form::select('machine_id', $machineArr, null, ['id'=> 'machineId', 'class' => 'form-control js-source-states']) !!} 
                    <span class="text-danger">{{ $errors->first('machine_id') }}</span>
                </div>
                <label class="control-label col-md-2" for="lotWeight">@lang('label.WASH_LOT_QUANTITY') : <span class="text-danger"> * </span></label>
                <div class="col-md-2">
                    {!! Form::text('lot_weight', $target->wash_lot_quantity_weight, ['id'=> 'lotWeight', 'class' => 'form-control']) !!} 
                </div>
                <label class="control-label col-md-2" for="lotQty">@lang('label.LOT_QTY_PCS') : <span class="text-danger"> * </span></label>
                <div class="col-md-1">
                    {!! Form::text('lot_qty', $target->wash_lot_quantity_piece, ['id'=> 'lotQty', 'class' => 'form-control']) !!} 
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label col-md-2" for="dryerType">@lang('label.DRYER_TYPE') : </label>
                <div class="col-md-3">
                    {!! Form::text('dryer_type', $target->dryer_type, ['id'=> 'dryerType', 'class' => 'form-control', 'readonly']) !!}
                </div>
                <label class="control-label col-md-2" for="dryingTemperature">@lang('label.DRYING_TEMPERATURE') : </label>
                <div class="col-md-2">
                    {!! Form::text('drying_temperature', $target->drying_temperature, ['id'=> 'dryingTemperature', 'class' => 'form-control', 'readonly']) !!}
                </div>
            </div>
        </div>
    </div>
</div>