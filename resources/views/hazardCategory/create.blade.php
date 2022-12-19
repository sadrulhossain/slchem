@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.CREATE_HAZARD_CATEGORY')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'hazardCategory','files'=> 'true','class' => 'form-horizontal','id'=> 'submit_form')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('name',null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green button-submit" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/hazardCategory'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop