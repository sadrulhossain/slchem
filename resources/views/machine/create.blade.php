@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.CREATE_WASHING_MACHINE')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'machine', 'class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="washingMachineTypeId">@lang('label.SELECT_WASHING_MACHINE_TYPE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('washing_machine_type_id', $machineModelArr, null, ['id'=> 'washingMachineTypeId', 'class' => 'form-control js-source-states']) !!}
                                <span class="text-danger">{{ $errors->first('washing_machine_type_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="machine_no">@lang('label.WASHING_MACHINE_NO') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('machine_no',null, ['id'=> 'machine_no', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('machine_no') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="description">@lang('label.DESCRIPTION') :</label>
                            <div class="col-md-8">
                                {{ Form::textarea('description', null, ['id'=> 'description', 'class' => 'form-control','size' => '30x5']) }}
                                <span class="text-danger">{{ $errors->first('description') }}</span>
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
                        <a href="{{ URL::to('machine'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop