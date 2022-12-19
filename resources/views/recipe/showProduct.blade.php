@if(!empty($productList))
<div class="col-md-6" style="top: 25px;">
    <div class="form-group">
        <div class="col-md-3">
            <label class="control-label" for="productId">@lang('label.SELECT_PRODUCT'):<span class="text-danger"> *</span></label>
        </div>
        <div class="col-md-9">
            {!! Form::select('product_id[]', $productList, !empty($producIdArr) ? $producIdArr : null, ['class' => 'form-control mt-multiselect btn btn-default','id' => 'productId','multiple','data-width' => '100%']) !!}
        </div>
    </div>
</div>
@endif
<script type="text/javascript">
    $(document).ready(function () {
        $('#productId').multiselect();
    });
</script>