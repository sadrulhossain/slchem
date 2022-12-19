<label class="control-label col-md-2" for="referenceNo">@lang('label.REFERENCE_NO') :</label>
<div class="col-md-3">
    {!! Form::text('reference_no', $target->reference_no.'-'.$target->color.'-'.$batchReferenceNo, ['id'=> 'referenceNo', 'class' => 'form-control', 'readonly']) !!}
</div>

