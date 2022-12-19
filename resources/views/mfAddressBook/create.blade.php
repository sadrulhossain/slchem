@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.CREATE_MANUFACTURER_ADDRESS_BOOK')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'mfAddressBook','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="manufacturerId">@lang('label.MANUFACTURER') :<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                {!! Form::select('manufacturer_id', $manufacturerArr, null, ['class' => 'form-control js-source-states', 'id' => 'manufacturerId']) !!}
                                <span class="text-danger">{!! $errors->first('manufacturer_id') !!}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="countryId">@lang('label.COUNTRY') :<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                {!! Form::select('country_id', $countryArr, null, ['class' => 'form-control js-source-states', 'id' => 'countryId']) !!}
                                <span class="text-danger">{!! $errors->first('country_id') !!}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.TITLE') :<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                {!! Form::text('title', null, ['id'=> 'title', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{!! $errors->first('title') !!}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-offset-3 col-md-9">
                                    <label class="control-label" for="phone">@lang('label.PHONE'):</label>
                                    <button class="btn btn-success add-phone tooltips" title="Click here to add more Phone Number" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group"  id="newPhoneRow"></div>
                        
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-offset-3 col-md-9">
                                    <label class="control-label" for="email">@lang('label.EMAIL'):</label>
                                    <button class="btn btn-success add-email tooltips" title="Click here to add more Email" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group"  id="newEmailRow"></div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('label.ADDRESS') :<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    {!! Form::textarea('address', null, ['class' => 'form-control','size' => '50x5', 'id'=>'address']) !!}
                                    <span class="text-danger">{!! $errors->first('address') !!}</span>
                                </div>
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
                        <a href="{{ URL::to('/mfAddressBook'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var count = 1;
        var emailCount = 1;
        $(document).on('click', '.add-phone', function() {
            var coun = count++;
            $("#newPhoneRow").append('<div class="col-md-12"><div class="col-md-offset-4 col-md-7"><br/><input class="form-control phone-no" data-id="' + coun + '" name="phone[]" type="text" placeholder= "Phone Number"></div>\
                           <br/><button class="btn btn-danger remove tooltips" title="Remove" type="button"><i class="fa fa-remove"></i>\
                          </button></div>');
        });
        
        $(document).on('click', '.add-email', function() {
            var emailCountNumber = emailCount++;
            $("#newEmailRow").append('<div class="col-md-12"><div class="col-md-offset-4 col-md-7"><br/><input class="form-control email" data-id="' + emailCountNumber + '" name="email[]" type="text" placeholder= "Email Address"></div>\
                           <br/><button class="btn btn-danger remove tooltips" title="Remove" type="button"><i class="fa fa-remove"></i>\
                          </button></div>');
        });
        
        $(document).on('click', '.remove', function() {
            $(this).parent().remove();
            return false;
        });


    });
</script>
@stop