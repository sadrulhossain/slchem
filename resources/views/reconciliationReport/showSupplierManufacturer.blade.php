<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="supplierId">@lang('label.SUPPLIER'):<span class="text-danger"> *</span></label>
        {!! Form::select('supplier_id', $supplierArr, Request::get('supplier_id'), ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
    </div>
</div>
<div class="form">
    <div class="col-md-3">
        <label class="control-label" for="manufacturerId">@lang('label.MANUFACTURER'):<span class="text-danger"> *</span></label>
        {!! Form::select('manufacturer_id', $manufacturerArr, Request::get('manufacturer_id'), ['class' => 'form-control js-source-states', 'id' => 'manufacturerId']) !!}
    </div>
</div>