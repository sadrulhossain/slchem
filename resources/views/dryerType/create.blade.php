@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.CREATE_DRYER_TYPE')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'dryerType', 'class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="dryerCategoryId">@lang('label.SELECT_DRYER_CATEGORY') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('dryer_category_id', $dryerCategoryArr, null, ['id'=> 'dryerCategoryId', 'class' => 'form-control js-source-states']) !!}
                                <span class="text-danger">{{ $errors->first('dryer_category_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name',null, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.HUMIDITY') @lang('label.IN_PERCENT'): <span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('humidity',null, ['id'=> 'humidity', 'class' => 'form-control','autocomplete' => 'off','placeholder' => '%']) !!}
                                <span class="text-danger">{{ $errors->first('humidity') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.CAPACITY') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('capacity',null, ['id'=> 'capacity', 'class' => 'form-control integer-only','autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('capacity') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="brandName">@lang('label.BRAND_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('brand_name',null, ['id'=> 'brandName', 'class' => 'form-control','autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('brand_name') }}</span>
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
                        <a href="{{ URL::to('dryerType'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop