@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-file-photo-o"></i>@lang('label.EDIT_PICTOGRAM')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('pictogram.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.DESCRIPTION') :</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {{ Form::textarea('description', null, ['class' => 'form-control summernote_1','size' => '20x5', 'id'=>'summernote_1']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group last">
                            <label class="control-label col-md-4" for="logo">@lang('label.LOGO') :<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">
                                        @if(isset($target->logo))
                                        <img src="{{URL::to('/')}}/public/uploads/pictogram/{{$target->logo}}" alt="{{ $target->name}}"/>
                                        @else
                                        <img src="{{URL::to('/')}}/public/img/no-image.png" alt=""> 
                                        @endif

                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;"> </div>
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> @lang('label.SELECT_IMAGE') </span>
                                            <span class="fileinput-exists"> @lang('label.CHANGE') </span>
                                            {!! Form::file('logo',['id'=> 'logo']) !!}
                                        </span>
                                        <span class="help-block text-danger">{!! $errors->first('logo') !!}</span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> @lang('label.REMOVE') </a>
                                    </div>
                                </div>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.BRAND_IMAGE_FOR_IMAGE_DESCRIPTION')
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
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/pictogram'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop