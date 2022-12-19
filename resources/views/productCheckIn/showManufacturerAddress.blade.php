<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="addressId">@lang('label.MANUFACTURER_ADDRESS'): <span class="text-danger"> *</span></label>

            {!! Form::select('address_id', $addressArr, null, ['class' => 'form-control js-source-states', 'id' => 'addressId']) !!}

    </div>
</div>