@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.EDIT_HAZARD_CLASS')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('hazardClass.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal','id'=> 'submit_form'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3" for="hazardCatId">@lang('label.HAZARD_CATEGORY') : <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('hazard_cat_id', $categoryArr, null, ['class' => 'form-control js-source-states', 'id' => 'hazardCatId']) !!}
                                    <span class="text-danger">{{ $errors->first('hazard_cat_id') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::text('name',null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-md-3" for="pictogram">@lang('label.RELATE_PICTOGRAM'): <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    @if(!$pictogramArr->isEmpty())
                                    @foreach($pictogramArr as $pictogram)
                                    <?php
                                    $check = null;
                                    if (!empty($prevLogoData)) {
                                        if (in_array($pictogram->id, $prevLogoData)) {
                                            $check = 'true';
                                        }
                                    }
                                    ?>
                                    <div class="md-checkbox-inline md-checkbox has-success">
                                        {!! Form::checkbox('pictogram_id[]',$pictogram->id,$check, ['id' => 'pictogramId_'.$pictogram->id, 'class'=>'md-check']) !!} 
                                        <label for="pictogramId_<?php echo $pictogram->id ?>">
                                            <span></span>
                                            <span class="check tooltips" title="{{ __('label.UNCHECK_TO_DROP').' '.$pictogram->name }}"></span>
                                            <span class="box tooltips" title="{{ __('label.CHECK_TO_RELATE_WITH').' '.$pictogram->name }}"></span>
                                            <?php if (!empty($pictogram->logo)) { ?>
                                                <img class="tooltips img-thumbnail pictogram-space" width="50" height="50" src="{{URL::to('/')}}/public/uploads/pictogram/{{ $pictogram->logo }}" alt="{{ $pictogram->name}}" title="{{ $pictogram->name}}"/>
                                            <?php } else { ?>
                                                <img width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                            <?php } ?>
                                        </label>
                                    </div>
                                    @endforeach
                                    <div> <span class="text-danger">{{ $errors->first('pictogram_id') }}</span></div>
                                    @endif
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
                        <a href="{{ URL::to('/hazardClass'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop