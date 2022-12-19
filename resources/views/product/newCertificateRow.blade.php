<?php
$v3 = 'z' . uniqid();
?>
<div class="col-md-12">
    <div class="col-md-3">
        <label class="control-label">@lang('label.CHOOSE_CERTIFICATE'):<span class="text-danger">*</span></label>
        {!! Form::select('certificate_id['.$v3.']', $certificateArr, null, ['class' => 'form-control js-source-states', 'id' => 'certificateId_'.$v3]) !!}
        <span class="text-danger">{{ $errors->first('certificate_id') }}</span>
    </div>
    <div class="col-md-4">
        <label class="control-label">@lang('label.ATTACHMENT'):</label>
        <br>
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <span class="btn green btn-file">
                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                {!! Form::file('certificate_file['.$v3.']',['id'=> 'certificateFile_'.$v3]) !!}
            </span>
            <span class="fileinput-filename"></span> &nbsp;
            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
        </div>
    </div>
    <div class="col-md-3">
        <label class="control-label">@lang('label.REMARKS'):</label>
        {!! Form::textarea('remarks['.$v3.']', null, ['id'=> 'remarks_'.$v3, 'class' => 'form-control','size' => '50x3']) !!}
    </div>
    <br/>
    <div class="col-md-1">
        <button class="btn btn-danger remove tooltips" title="Remove" type="button">
            <i class="fa fa-remove"></i>&nbsp;@lang('label.DELETE')
        </button>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.remove', function() {
            $(this).parent().parent().remove();
            return false;
        });
    });
</script>
