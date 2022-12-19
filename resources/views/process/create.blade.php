@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cog"></i>@lang('label.CREATE_PROCESS')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'process', 'class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="processTypeId">@lang('label.PROCESS_TYPE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('process_type_id', $processTypeList, null, ['class' => 'form-control js-source-states', 'id' => 'processTypeId']) !!}
                                <span class="text-danger">{{ $errors->first('process_type_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name',null, ['id'=> 'name', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="description">@lang('label.DESCRIPTION') :</label>
                            <div class="col-md-8">
                                {{ Form::textarea('description', null, ['id'=> 'description', 'class' => 'form-control','size' => '30x5']) }}
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group" id="waterMarkHolder">
                            <label class="col-md-4 control-label" for="water">@lang('label.MARK_AS_WATER')</label>
                            <div class="col-md-8">
                                <div class="mt-checkbox-inline">
                                    <label class="mt-checkbox">
                                        {!! Form::checkbox('water', 1, false, ['id' => 'water']) !!}
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="order">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('order', $orderList, null, ['class' => 'form-control js-source-states', 'id' => 'order']) !!} 
                                <span class="text-danger">{{ $errors->first('order') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/process'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#processTypeId').on('change', function () {
            var processTypeId = $(this).val();

            if (processTypeId == '2') {
                $("#waterMarkHolder").hide();
            } else {
                $("#waterMarkHolder").show();
            }
        })
    });
</script>
@stop