<?php
$v1 = 'x' . uniqid();
$v2 = 'y' . uniqid();
$v3 = 'z' . uniqid();
?>
<div class="col-md-12">
    <div class="col-md-3">
        <label class="control-label">@lang('label.CHOOSE_BUYER'): <span class="text-danger">*</span></label>
        {!! Form::select('buyer_id['.$v3.']', $buyerArr, null, ['class' => 'form-control js-source-states', 'id' => 'buyerId_'.$v3]) !!}
        <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
    </div>
    <div class="col-md-1">
        <label for="{{ $v1 }}"> &nbsp;&nbsp;&nbsp;@lang('label.RSL') </label>
        <div class="checkbox-center md-checkbox">
            {!! Form::checkbox('rsl['.$v3.']',1,false, ['id' => $v1, 'class'=> 'md-check']) !!}
            <label for="{{ $v1 }}">
                <span class="inc"></span>
                <span class="check mark-caheck"></span>
                <span class="box mark-caheck"></span>
            </label>
        </div>
    </div>
    <div class="col-md-1">
        <label for="{{ $v2 }}">&nbsp;&nbsp;&nbsp;@lang('label.MRSL')</label>
        <div class="checkbox-center md-checkbox">
            {!! Form::checkbox('mrsl['.$v3.']',1,false,['id' => $v2, 'class'=> 'md-check']) !!}
            <label for="{{ $v2 }}">
                <span class="inc"></span>
                <span class="check mark-caheck"></span>
                <span class="box mark-caheck"></span>
            </label>
        </div>
    </div>
    <div class="col-md-1">
        <label class="control-label">@lang('label.VERSION'):</label>
        {!! Form::text('version['.$v3.']', null, ['id'=> 'version'.$v3, 'class' => 'form-control interger-only','maxlength'=> "4"]) !!} 
    </div>

    <div class="col-md-2">
        <label class="control-label">@lang('label.ATTACHMENT'):</label>
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <span class="btn green btn-file">
                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                {!! Form::file('gl_file['.$v3.']',['id'=> 'glFile_'.$v3]) !!}
            </span>
            <span class="fileinput-filename"></span> &nbsp;
            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
        </div>
    </div>
    <div class="col-md-3">
        <label class="control-label">@lang('label.DATE') :</label>
        <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
            {!! Form::text('date['.$v3.']',null,['id'=> 'date_'.$v3, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
            <span class="input-group-btn">
                <button class="btn default reset-date" type="button" remove="date_{{ $v3 }}">
                    <i class="fa fa-times"></i>
                </button>
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>      
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
