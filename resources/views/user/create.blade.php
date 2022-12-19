@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>@lang('label.CREATE_USER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'user', 'files'=> true, 'class' => 'form-horizontal')) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="groupId">@lang('label.USER_GROUP') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('group_id', $groupList, null, ['class' => 'form-control js-source-states', 'id' => 'groupId']) !!}
                                <span class="text-danger">{{ $errors->first('group_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="departmentId">@lang('label.DEPARTMENT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('department_id', $departmentList, null, ['class' => 'form-control js-source-states', 'id' => 'departmentId']) !!}
                                <span class="text-danger">{{ $errors->first('department_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="designationId">@lang('label.DESIGNATION') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('designation_id', $designationList, null, ['class' => 'form-control js-source-states', 'id' => 'designationId']) !!}
                                <span class="text-danger">{{ $errors->first('department_id') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="employeeId">@lang('label.EMPLOYEE_ID') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('employee_id', null, ['id'=> 'employeeId', 'class' => 'form-control integer-only']) !!} 
                                <span class="text-danger">{{ $errors->first('employee_id') }}</span>
                                
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="firstName">@lang('label.FIRST_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('first_name', null, ['id'=> 'firstName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="lastName">@lang('label.LAST_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('last_name', null, ['id'=> 'lastName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="nickName">@lang('label.NICK_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('nick_name', null, ['id'=> 'nickName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('nick_name') }}</span>
                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="userEmail">@lang('label.EMAIL') :</label>
                            <div class="col-md-8">
                                {!! Form::email('email', null, ['id'=> 'userEmail', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="phone">@lang('label.PHONE') :</label>
                            <div class="col-md-8">
                                {!! Form::text('phone', null, ['id'=> 'phone', 'class' => 'form-control integer-only']) !!} 
                                <span class="text-danger">{{ $errors->first('user_phone') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="userName">@lang('label.USERNAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('username', null, ['id'=> 'userName', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('username') }}</span>
                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="password">@lang('label.PASSWORD') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::password('password', ['id'=> 'password', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span>
                                    @lang('label.COMPLEX_PASSWORD_INSTRUCTION')
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="confPassword">@lang('label.CONF_PASSWORD') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::password('conf_password', ['id'=> 'confPassword', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('conf_password') }}</span>
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <label class="control-label col-md-4" for="photo">@lang('label.PHOTO') :</label>
                            <div class="col-md-8">
                                {!! Form::file('photo', null, ['id'=> 'photo', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('photo') }}</span>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.USER_IMAGE_FOR_IMAGE_DESCRIPTION')
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control js-source-states-2', 'id' => 'status']) !!}
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
                        <a href="{{ URL::to('/user'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop