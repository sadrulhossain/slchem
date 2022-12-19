@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.EDIT_WASHING_MACHINE_TYPE')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('machineModel.update', $target->id), 'method' => 'PATCH', 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name',null, ['id'=> 'name', 'class' => 'form-control', 'autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="rpm">@lang('label.RPM') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('rpm',null, ['id'=> 'rpm', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('rpm') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4">@lang('label.CATEGORY'):</label>
                            <div class="col-md-8">
                                {!! Form::text('category',null, ['id'=> 'category', 'class' => 'form-control', 'autocomplete' => 'off','placeholder' => 'e.g: Front Loading/ Top Loading']) !!}
                                <span class="text-danger">{{ $errors->first('category') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4">@lang('label.TYPE'):<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('type',null, ['id'=> 'type', 'class' => 'form-control', 'autocomplete' => 'off','placeholder' => 'Computerized/ Analog/ Digitalized']) !!}
                                <span class="text-danger">{{ $errors->first('type') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="capacity">@lang('label.MACHINE_CAPACITY') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('capacity',null, ['id'=> 'capacity', 'class' => 'form-control integer-only', 'autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('capacity') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4">@lang('label.BRAND_NAME'):</label>
                            <div class="col-md-8">
                                {!! Form::text('brand_name',null, ['id'=> 'brandName', 'class' => 'form-control', 'autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('brand_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], null, ['class' => 'form-control', 'id' => 'status']) !!}
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
                        <a href="{{ URL::to('/machineModel'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop