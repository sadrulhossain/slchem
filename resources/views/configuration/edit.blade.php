@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <!-- END PORTLET-->
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>@lang('label.UPDATE_CONFIGURATION')
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            {!! Form::model($target,['route' => array('configuration.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal']) !!}
            {!!csrf_field()!!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-md-5 control-label">@lang('label.CHECK_IN_CUT_OFF_TIME') :</label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    {!! Form::text('check_in_time',date("h:i A",strtotime($target->check_in_time)), ['id'=> 'checkInTime', 'class' => 'form-control clockface_1', 'placeholder' => 'Enter Time','data-format'=> "hh:mm A", 'autocomplete' => 'off']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-5 control-label">@lang('label.PRODUCT_SERIAL_CODE'):</label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    {!! Form::text('serial_code',null,['id'=> 'serialCode', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-circle green">Submit</button>
                        <a href="{{URL::to('configuration')}}">
                            <button type="button" class="btn btn-circle grey-salsa btn-outline">Cancel</button> 
                        </a>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
            <!-- END FORM-->
        </div>
    </div>
</div>
@stop
