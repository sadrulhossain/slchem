@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.CREATE_CAS')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'substanceCas', 'class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="casNo">@lang('label.CAS_NO') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('cas_no',null, ['id'=> 'casNo', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('cas_no') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="casName">@lang('label.CAS_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('cas_name',null, ['id'=> 'casName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('cas_name') }}</span>
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
                        <a href="{{ URL::to('/substanceCas'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop