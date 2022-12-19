@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <h4 class="page-title form-section">
        <strong>@lang('label.MANAGE_ATTRIBUTES_FOR')&nbsp;{!! '" '.$target->name.' "' !!}</strong>
    </h4>
    <div class="tabbable-line boxless tabbable-reversed">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">@lang('label.SUBSTANCE_CAS_EC')</a></li>
            <li><a href="#tab_2" data-toggle="tab">@lang('label.CERTIFICATES') </a></li>
            <li><a href="#tab_3" data-toggle="tab">@lang('label.GL') </a></li>
            <li><a href="#tab_4" data-toggle="tab">@lang('label.PL') </a></li>
            <li><a href="#tab_5" data-toggle="tab">@lang('label.PPE') </a></li>
            <li><a href="#tab_6" data-toggle="tab">@lang('label.HAZARD_CATEGORY') </a></li>
            <li><a href="#tab_7" data-toggle="tab">@lang('label.SUPPLIER') </a></li>
            <li><a href="#tab_8" data-toggle="tab">@lang('label.MANUFACTURER') </a></li>
        </ul>
        <div class="tab-content">

            <div class="tab-pane active" id="tab_1">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-clone"></i>@lang('label.SUBSTANCE_CAS_EC')
                        </div>
                    </div>

                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitForm')) !!}
                        {!! Form::hidden('product_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="form-body">
                            @if(!$casNoArr->isEmpty())
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-2" for="casNo"><strong>@lang('label.SUBSTANCE_CAS'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-cas tooltips" title="Click here to add more CAS No" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @foreach($casNoArr as $casNo)
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-offset-2 col-md-2">
                                        <label class="control-label" for="casNo">@lang('label.CAS_NO'):<span class="text-danger"> *</span></label>
                                        {!! Form::text('cas_no[]',$casNo->cas_no, ['id'=> 'casNo', 'class' => 'form-control integer-only cas-no abc', 'data-id'=>'0','placeholder' => 'CAS No']) !!} 
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label" for="casName">@lang('label.CAS_NAME'):</label>
                                        {!! Form::text('cas_name',isset($casArr[$casNo->cas_no]) ? $casArr[$casNo->cas_no] : '', ['id'=> 'displayCasSubstanceName0', 'class' => 'form-control tooltips','readonly', 'title' => isset($casArr[$casNo->cas_no]) ? $casArr[$casNo->cas_no] : ''  ]) !!} 
                                        <span id="casData0" class="text-danger"></span>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="control-label" for="casPercentage">@lang('label.CAS_PERCENTAGE'):<span class="text-danger"> *</span></label>
                                        {!! Form::text('cas_percentage[]',$casNo->cas_percentage, ['id'=> 'casPercentage0', 'class' => 'form-control integer-only','placeholder'=> '%']) !!} 
                                    </div>
                                    <br/>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger remove" type="button" data-id="">
                                            <i class="fa fa-remove"></i>&nbsp;@lang('label.DELETE')
                                        </button>
                                    </div>
                                </div>

                            </div>
                            @endforeach
                            @else
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-2" for="casNo"><strong>@lang('label.SUBSTANCE_CAS'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-cas tooltips" title="Click here to add more CAS No" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group" id="newCasRow"></div>


                            @if(!$ecNoArr->isEmpty())
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-2" for="ecNo"><strong>@lang('label.SUBSTANCE_EC'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-ec tooltips" title="Click here to add more EC No" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div> 
                            </div>
                            @foreach($ecNoArr as $ecNo)
                            <div class="form-group">

                                <div class="col-md-12">
                                    <div class="col-md-offset-2 col-md-2">
                                        <label class="control-label" for="ecNo">@lang('label.EC_NO'): <span class="text-danger"> *</span></label>
                                        {!! Form::text('ec_no[]',$ecNo->ec_no, ['id'=> 'ecNo', 'class' => 'form-control ec-no', 'data-id'=>'0','placeholder' => 'EC No']) !!} 
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label" for="ecName">@lang('label.EC_NAME'): </label>
                                        {!! Form::text('ec_name',isset($ecArr[$ecNo->ec_no]) ? $ecArr[$ecNo->ec_no] : '', ['id'=> 'displayEcSubstanceName0', 'class' => 'form-control tooltips','readonly', 'title' => isset($ecArr[$ecNo->ec_no]) ? $ecArr[$ecNo->ec_no] : '' ]) !!} 
                                        <span id="casData0" class="text-danger"></span>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="control-label" for="ecPercentage">@lang('label.EC_PERCENTAGE'): <span class="text-danger"> *</span></label>
                                        {!! Form::text('ec_percentage[]',$ecNo->ec_percentage, ['id'=> 'ecPercentage0', 'class' => 'form-control integer-only','placeholder'=> '%']) !!} 
                                    </div>
                                    <br/>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger remove" type="button" data-id="">
                                            <i class="fa fa-remove"></i>&nbsp;@lang('label.DELETE')
                                        </button>
                                    </div>
                                </div>

                            </div>
                            @endforeach
                            @else
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-2" for="ecNo"><strong>@lang('label.SUBSTANCE_EC'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-ec tooltips" title="Click here to add more EC No" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="form-group" id="newEcRow">
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="button" class="btn green substance-submit">@lang('label.SUBMIT')</button>
                                    <a href="{{ URL::to('/product') }}" class="btn grey-salsa btn-outline">@lang('label.CANCEL')</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_2">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-pdf-o"></i>@lang('label.CERTIFICATE')
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitFormCertificate')) !!}
                        {!! Form::hidden('product_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="form-body">
                            @if(!$previousCeritificateArr ->isEmpty())
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-2" ><strong>@lang('label.CERTIFICATES'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-certificate tooltips" title="Click here to add more Certificate" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @foreach($previousCeritificateArr as  $certificate)
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">@lang('label.CHOOSE_CERTIFICATE'):<span class="text-danger">*</span></label>
                                        {!! Form::select('certificate_id['.$certificate->certificate_id.']',$certificateArr,$certificate->certificate_id,['class' => 'form-control js-source-states manage-product-select', 'id' => 'certificateId_'.$certificate->certificate_id]) !!}
                                        <span class="text-danger">{!! $errors->first('certificate_id') !!}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">@lang('label.ATTACHMENT'):</label>
                                        <br/>
                                        @if(!empty($certificate->certificate_file))
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn green btn-file">
                                                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                {!! Form::file('certificate_file['.$certificate->certificate_id.']',null,['id'=> 'certificateFile_'.$certificate->certificate_id]) !!}
                                                {!! Form::hidden('certificate_prev_file['.$certificate->certificate_id.']',$certificate->certificate_file) !!}
                                            </span>
                                            <a href="{{URL::to('public/uploads/productToCertificate/'.$certificate->certificate_file)}}"
                                               class="btn yellow-crusta btn-sm tooltips" title="Certificate Preview" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                            <span class="fileinput-filename"></span>&nbsp;
                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                        </div>
                                        @else
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn green btn-file">
                                                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                {!! Form::file('certificate_file['.$certificate->certificate_id.']',null,['id'=> 'certificateFile_'.$certificate->certificate_id]) !!}
                                            </span>
                                            <span class="fileinput-filename"></span>&nbsp;
                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3"> 
                                        <label class="control-label">@lang('label.REMARKS'):</label>
                                        {!! Form::textarea('remarks['.$certificate->certificate_id.']', $certificate->remarks, ['id'=> 'remarks_'.$certificate->certificate_id, 'class' => 'form-control','size' => '50x3']) !!}
                                    </div>
                                    <br/>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger remove" type="button" data-id="">
                                            <i class="fa fa-remove"></i>&nbsp;@lang('label.DELETE')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-2" for="certificateId"><strong>@lang('label.CERTIFICATES'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-certificate tooltips" title="Click here to add more Certificate" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group" id="newCertificateRow">

                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="button" class="btn green certificate-submit">@lang('label.SUBMIT')</button>
                                    <a href="{{ URL::to('/product') }}" class="btn grey-salsa btn-outline">@lang('label.CANCEL')</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_3">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-text"></i>@lang('label.GL')
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitFormGl')) !!}
                        {!! Form::hidden('product_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="form-body">

                            @if(!$previousGlArr ->isEmpty())
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-1" for="guranteeListId"><strong>@lang('label.GL_LABEL'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-gl tooltips" title="Click here to add more Gl" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @foreach($previousGlArr as  $previousGl)
                            <div class="form-group">

                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">@lang('label.CHOOSE_BUYER'): <span class="text-danger">*</span></label>
                                        {!! Form::select('buyer_id['.$previousGl->buyer_id.']',$buyerArr, $previousGl->buyer_id, ['class' => 'form-control js-source-states manage-product-select', 'id' => 'buyerId_'.$previousGl->buyer_id]) !!}
                                        <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                    </div>
                                    <?php
                                    $check = null;
                                    if ($previousGl->rsl == 1) {
                                        $check = true;
                                    } else {
                                        $check = false;
                                    }
                                    ?>
                                    <div class="col-md-1">
                                        <label for="rsl-{{$previousGl->id}}"> &nbsp;&nbsp;&nbsp;@lang('label.RSL') </label>
                                        <div class="checkbox-center md-checkbox">

                                            {!! Form::checkbox('rsl['.$previousGl->buyer_id.']',$previousGl->rsl,$check,['id' => 'rsl-'.$previousGl->id, 'class'=> 'md-check']) !!}
                                            <label for="rsl-{{$previousGl->id}}">
                                                <span class="inc"></span>
                                                <span class="check mark-caheck"></span>
                                                <span class="box mark-caheck"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                    $mCheck = null;
                                    if ($previousGl->mrsl == 1) {
                                        $mCheck = true;
                                    } else {
                                        $mCheck = false;
                                    }
                                    ?>
                                    <div class="col-md-1">
                                        <label for="mrsl-{{$previousGl->id}}">&nbsp;&nbsp;&nbsp;@lang('label.MRSL')</label>
                                        <div class="checkbox-center md-checkbox">

                                            {!! Form::checkbox('mrsl['.$previousGl->buyer_id.']',$previousGl->mrsl,$mCheck,['id' => 'mrsl-'.$previousGl->id, 'class'=> 'md-check']) !!}
                                            <label for="mrsl-{{$previousGl->id}}">
                                                <span class="inc"></span>
                                                <span class="check mark-caheck"></span>
                                                <span class="box mark-caheck"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="control-label">@lang('label.VERSION'):</label>
                                        {!! Form::text('version['.$previousGl->buyer_id.']', $previousGl->version, ['id'=> 'version_'.$previousGl->buyer_id, 'class' => 'form-control interger-only','maxlength'=> "4"]) !!} 
                                    </div>

                                    <div class="col-md-2">
                                        <label class="control-label">@lang('label.ATTACHMENT'):</label>
                                        @if(!empty($previousGl->gl_file))

                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn green btn-file">
                                                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                {!! Form::file('gl_file['.$previousGl->buyer_id.']',null,['id'=> 'glFile_'.$previousGl->buyer_id]) !!}
                                                {!! Form::hidden('gl_prev_file['.$previousGl->buyer_id.']',$previousGl->gl_file) !!}
                                            </span>
                                            <a href="{{URL::to('public/uploads/productToGl/'.$previousGl->gl_file)}}" class="btn yellow-crusta btn-sm tooltips" title="Gurantee Letter Preview" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                            <span class="fileinput-filename"></span>&nbsp;
                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                        </div>
                                        @else
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn green btn-file">
                                                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                {!! Form::file('gl_file['.$previousGl->buyer_id.']',null,['id'=> 'glFile_'.$previousGl->buyer_id]) !!}
                                            </span>
                                            <span class="fileinput-filename"></span> &nbsp;
                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">@lang('label.DATE') :</label>
                                        <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                            {!! Form::text('date['.$previousGl->buyer_id.']', $previousGl->date, ['id'=> 'date_'.$previousGl->buyer_id, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                            <span class="input-group-btn">
                                                <button class="btn default reset-date" type="button" remove="date_{{$previousGl->buyer_id}}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn default date-set" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger remove" type="button" data-id="">
                                            <i class="fa fa-remove"></i>&nbsp;@lang('label.DELETE')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-3" for="guranteeListId"><strong>@lang('label.GL'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-gl tooltips" title="Click here to add more Gl" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group" id="newGlRow">

                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="button" class="btn green gl-submit">@lang('label.SUBMIT')</button>
                                    <a href="{{ URL::to('/product') }}" class="btn grey-salsa btn-outline">@lang('label.CANCEL')</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_4">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-text"></i>@lang('label.PL')
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitFormPl')) !!}
                        {!! Form::hidden('product_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="form-body">
                            @if(!$previousBplArr ->isEmpty())
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-2" ><strong>@lang('label.BUYER_PL'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-bpl tooltips" title="Click here to add more Buyer Positive List" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @foreach($previousBplArr as  $previousBpl)
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">@lang('label.CHOOSE_BUYER'): <span class="text-danger">*</span></label>
                                        {!! Form::select('buyer_id['. $previousBpl->buyer_id.']', $buyerArr, $previousBpl->buyer_id, ['class' => 'form-control js-source-states manage-product-select', 'id' => 'buyerId_'. $previousBpl->buyer_id]) !!}
                                        <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="control-label">@lang('label.LEVEL'):</label>
                                        {!! Form::text('level['.$previousBpl->buyer_id.']', $previousBpl->level, ['id'=> 'level_'. $previousBpl->buyer_id, 'class' => 'form-control interger-only','maxlength'=> "4"]) !!} 
                                    </div>
                                    <div class="col-md-1">
                                        <label class="control-label">@lang('label.VERSION'):</label>
                                        {!! Form::text('version['.$previousBpl->buyer_id.']', $previousBpl->version, ['id'=> 'version', 'class' => 'form-control interger-only','maxlength'=> "4"]) !!} 
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">@lang('label.ATTACHMENT'):</label>
                                        <br>
                                        @if(!empty($previousBpl->bpl_file))
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn green btn-file">
                                                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                {!! Form::file('bpl_file['.$previousBpl->buyer_id.']',null,['id'=> 'bplFile_'.$previousBpl->buyer_id]) !!}
                                                {!! Form::hidden('bpl_prev_file['.$previousBpl->buyer_id.']',$previousBpl->bpl_file) !!}
                                            </span>
                                            <a href="{{URL::to('public/uploads/buyerPositiveList/'.$previousBpl->bpl_file)}}" class="btn yellow-crusta btn-sm tooltips" title="Buyer Positive List Preview" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>

                                            <span class="fileinput-filename"></span>&nbsp;
                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                        </div>
                                        @else
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn green btn-file">
                                                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                {!! Form::file('bpl_file['.$previousBpl->buyer_id.']',null,['id'=> 'bplFile_'.$previousBpl->buyer_id]) !!}
                                            </span>
                                            <span class="fileinput-filename"></span> &nbsp;
                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">@lang('label.DATE') :</label>
                                        <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                            {!! Form::text('date['.$previousBpl->buyer_id.']', $previousBpl->date, ['id'=> 'date_'.$previousBpl->buyer_id, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                            <span class="input-group-btn">
                                                <button class="btn default reset-date" type="button" remove="date">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn default date-set" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger remove" type="button" data-id="">
                                            <i class="fa fa-remove"></i>&nbsp;@lang('label.DELETE')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="form-group">
                                <label class="control-label col-md-3" for="positiveListId"><strong>@lang('label.BUYER_PL'):</strong></label>
                                <div class="col-md-1">
                                    <button class="btn btn-success add-bpl tooltips" title="Click here to add more Buyer Positive List" type="button">
                                        <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                    </button>
                                </div>
                            </div>
                            @endif
                            <div class="form-group" id="newBplRow"> </div>

                            @if(!$previousMplArr ->isEmpty())
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="col-md-2"><strong>@lang('label.MANUFACTURER_PL'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-mpl tooltips" title="Click here to add more Manufacturer Positive List" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @foreach($previousMplArr as  $previousMpl)
                            <div class="form-group">   

                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label">@lang('label.CHOOSE_MANUFACTURER'): <span class="text-danger">*</span></label>
                                        {!! Form::select('manufacturer_id['.$previousMpl->manufacturer_id.']', $manufacturerArr,$previousMpl->manufacturer_id, ['class' => 'form-control js-source-states manage-product-select', 'id' => 'manufacturerId_'.$previousMpl->manufacturer_id]) !!}
                                        <span class="text-danger">{{ $errors->first('manufacturer_id') }}</span>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="control-label">@lang('label.LEVEL'):</label>
                                        {!! Form::text('m_level['.$previousMpl->manufacturer_id.']', $previousMpl->m_level, ['id'=> 'mLevel_'.$previousMpl->manufacturer_id, 'class' => 'form-control interger-only','maxlength'=> "4"]) !!} 
                                    </div>
                                    <div class="col-md-1">
                                        <label>@lang('label.VERSION'):</label>
                                        {!! Form::text('m_version['.$previousMpl->manufacturer_id.']', $previousMpl->m_version, ['id'=> 'mVersion_'.$previousMpl->manufacturer_id, 'class' => 'form-control interger-only','maxlength'=> "4"]) !!} 
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">@lang('label.ATTACHMENT'):</label>
                                        <br/>
                                        @if(!empty($previousMpl->mpl_file))

                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn green btn-file">
                                                <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                                <span class="fileinput-exists">@lang('label.CHANGE')</span>
                                                {!! Form::file('mpl_file['.$previousMpl->manufacturer_id.']',null,['id'=> 'mplFile_'.$previousMpl->manufacturer_id]) !!}
                                                {!! Form::hidden('mpl_prev_file['.$previousMpl->manufacturer_id.']',$previousMpl->mpl_file) !!}
                                            </span>
                                            <a href="{{URL::to('public/uploads/mPositiveList/'.$previousMpl->mpl_file)}}" class="btn yellow-crusta btn-sm tooltips" 
                                               title="Manufacturer Positive List Preview" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                            <span class="fileinput-filename"></span>&nbsp;
                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"></a>
                                        </div>
                                        @else
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn green btn-file">
                                                <span class="fileinput-new"> Select file </span>
                                                <span class="fileinput-exists"> Change </span>
                                                {!! Form::file('mpl_file['.$previousMpl->manufacturer_id.']',null,['id'=> 'mplFile_'.$previousMpl->manufacturer_id]) !!}
                                            </span>
                                            <span class="fileinput-filename"></span> &nbsp;
                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">@lang('label.DATE') :</label>
                                        <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                            {!! Form::text('m_date['.$previousMpl->manufacturer_id.']', $previousMpl->m_date, ['id'=> 'mDate_'.$previousMpl->manufacturer_id, 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                            <span class="input-group-btn">
                                                <button class="btn default reset-date" type="button" remove="date">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn default date-set" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger remove" type="button" data-id="">
                                            <i class="fa fa-remove"></i>&nbsp;@lang('label.DELETE')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label class="control-label col-md-3" for="positiveListId"><strong>@lang('label.MANUFACTURER_PL'):</strong></label>
                                    <div class="col-md-1">
                                        <button class="btn btn-success add-mpl tooltips" title="Click here to add more Manufacturer Positive List" type="button">
                                            <i class="fa fa-plus"></i>&nbsp;@lang('label.ADD')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group" id="newMplRow">

                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="btn green pl-submit">@lang('label.SUBMIT')</button>
                                    <a href="{{ URL::to('/product') }}" class="btn grey-salsa btn-outline">@lang('label.CANCEL')</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_5">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-text"></i>@lang('label.PPE')
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitFormPpe')) !!}
                        {!! Form::hidden('product_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="ppeId"><strong>@lang('label.RELATE_PPE'):</strong></label>
                                @if(!$ppeArr->isEmpty())
                                @foreach($ppeArr as $ppe)
                                <?php
                                $check = null;
                                if (!empty($previousPpe)) {
                                    if (in_array($ppe->id, $previousPpe)) {
                                        $check = 'true';
                                    }
                                }
                                ?>
                                <div class="md-checkbox-inline md-checkbox has-success">
                                    {!! Form::checkbox('ppe_id[]',$ppe->id,$check, ['id' => 'ppeId_'.$ppe->id, 'class'=>'md-check']) !!} 
                                    <label for="ppeId_<?php echo $ppe->id ?>">
                                        <span></span>
                                        <span class="check tooltips" title="{{ __('label.UNCHECK_TO_DROP').' '.$ppe->name }}"></span>
                                        <span class="box tooltips" title="{{ __('label.CHECK_TO_RELATE_WITH').' '.$ppe->name }}"></span>
                                        <?php if (!empty($ppe->logo)) { ?>
                                            <img class="tooltips img-thumbnail pictogram-space" width="50" height="50" src="{{URL::to('/')}}/public/uploads/ppe/{{ $ppe->logo }}" alt="{{ $ppe->name}}" title="{{ $ppe->name}}"/>
                                        <?php } else { ?>
                                            <img width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                        <?php } ?>
                                    </label>
                                </div>
                                @endforeach
                                <div> <span class="text-danger">{{ $errors->first('ppe_id') }}</span></div>
                                @endif
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="btn green ppe-submit">@lang('label.SUBMIT')</button>
                                    <a href="{{ URL::to('/product') }}" class="btn grey-salsa btn-outline">@lang('label.CANCEL')</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>
            </div>

            <!--  Begin here Product to Hazard Category -->
            <div class="tab-pane" id="tab_6">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-text"></i>@lang('label.HAZARD_CATEGORY')
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitFormHazardCat')) !!}
                        {!! Form::hidden('product_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="hazardCatId"><strong>@lang('label.RELATE_HAZARD_CATEGORY'):</strong></label>
                                <div class="col-md-9">
                                    <div class="mt-checkbox-inline">
                                        @if(!empty($hazardCatArr))
                                        @foreach($hazardCatArr as $hazardCatId => $hazardCatName)
                                        <?php
                                        $check = null;
                                        if (!empty($previousHazardCat)) {
                                            if (in_array($hazardCatId, $previousHazardCat)) {
                                                $check = 'true';
                                            }
                                        }
                                        ?>
                                        <label class="mt-checkbox" for="hazardCatId_<?php echo $hazardCatId ?>">
                                            {!! Form::checkbox('hazard_cat_id[]',$hazardCatId,$check, ['id' => 'hazardCatId_'.$hazardCatId]) !!} {!! $hazardCatName !!}
                                            <span></span>
                                            <span class="box tooltips" title="{{ __('label.CHECK_TO_RELATE_WITH').' '.$hazardCatName }}"></span>
                                        </label>
                                        <span class="text-danger">{{ $errors->first('hazard_cat_id') }}</span>

                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="btn green hazard-cat-submit">@lang('label.SUBMIT')</button>
                                    <a href="{{ URL::to('/product') }}" class="btn grey-salsa btn-outline">@lang('label.CANCEL')</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
            <!-- End Product to Hazard Category -->

            <!-- Begin to manage Supplier !-->
            <div class="tab-pane" id="tab_7">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-text"></i>@lang('label.MANAGE_SUPPLIER')
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitFormSupplier')) !!}
                        {!! Form::hidden('product_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center vcenter">#</th>
                                            <th class="vertical-center">@lang('label.SUPPLIER')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($supplierArr))
                                        @foreach($supplierArr as $supplierId => $supplierName)
                                        <tr>
                                            <td>
                                                <div class="md-checkbox">
                                                    <?php
                                                    $check = null;
                                                    if (!empty($previousSupplier)) {
                                                        if (in_array($supplierId, $previousSupplier)) {
                                                            $check = 'true';
                                                        }
                                                    }
                                                    ?>
                                                    {!! Form::checkbox('supplier_id[]', $supplierId, $check, ['id' => 'supplierId_'.$supplierId, 'class'=> 'md-check']) !!}
                                                    <label for="supplierId_{{ $supplierId }}">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> 
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="vcenter"> {{ $supplierName }} </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="8">@lang('label.NO_SUPPLIER_FOUND')</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="btn green" id="supplier-submit">@lang('label.SUBMIT')</button>
                                    <a href="{{ URL::to('/product') }}" class="btn grey-salsa btn-outline">@lang('label.CANCEL')</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
            <!-- End to manage Supplier !-->


            <!-- Begin to manage Manufacturer !-->
            <div class="tab-pane" id="tab_8">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-text"></i>@lang('label.MANAGE_MANUFACTURER')
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitFormManufacturer')) !!}
                        {!! Form::hidden('product_id',$target->id) !!}
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center vcenter">#</th>
                                            <th class="vertical-center">@lang('label.MANUFACTURER')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($manufactureArr))
                                        @foreach($manufactureArr as $manufacturerId => $manufacturerName)
                                        <tr>
                                            <td>
                                                <div class="md-checkbox">
                                                    <?php
                                                    $check = null;
                                                    if (!empty($previousManufacturer)) {
                                                        if (in_array($manufacturerId, $previousManufacturer)) {
                                                            $check = 'true';
                                                        }
                                                    }
                                                    ?>
                                                    {!! Form::checkbox('manufacturer_id[]', $manufacturerId, $check, ['id' => 'manufacturerId_'.$manufacturerId, 'class'=> 'md-check']) !!}
                                                    <label for="manufacturerId_{{ $manufacturerId }}">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> 
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="vcenter"> {{ $manufacturerName }} </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="8">@lang('label.NO_MANUFACTURER_FOUND')</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="btn green" id="manufacturer-submit">@lang('label.SUBMIT')</button>
                                    <a href="{{ URL::to('/product') }}" class="btn grey-salsa btn-outline">@lang('label.CANCEL')</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
            <!-- End to manage Manufacturer !-->
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var count = 1;
        var esCount = 1;
        //$(".js-source-states").select2();
        $(document).on('click', '.add-cas', function() {
            var coun = count++;
            $("#newCasRow").append('<div class="col-md-12"><div class="col-md-offset-2 col-md-2"><label class="control-label">CAS No :</label><span class="text-danger">*</span><input class="form-control cas-no integer-only" data-id="' + coun + '" name="cas_no[]" type="text" placeholder= "CAS No"></div><div class="col-md-2"><label class="control-label">CAS Name :</label><input name="cas_name" class="form-control integer-only"  id="displayCasSubstanceName' + coun + '" readonly>\
                           <span id="casData' + coun + '" class="text-danger"></span></div><div class="col-md-3"><label class="control-label">CAS Percentage :</label>\n\
                           <span class="text-danger">*</span><input id="casPercentage' + coun + '" class="form-control integer-only" name="cas_percentage[]" type="text" placeholder = "%"></div><div class="col-md-2"><br/><button class="btn btn-danger remove tooltips" title="Remove" type="button"><i class="fa fa-remove"></i>&nbsp;Delete\
                          </button></div></div>');
        });
        $(document).on('click', '.add-ec', function() {
            var esCountNumber = esCount++;
            $("#newEcRow").append('<div class="col-md-12"><div class="col-md-offset-2 col-md-2"><label class="control-label">EC No :</label><span class="text-danger">*</span><input class="form-control ec-no integer-only" data-id="' + esCountNumber + '" name="ec_no[]" type="text" placeholder= "Ec No"></div><div class="col-md-2"><label class="control-label">EC Name :</label><input name="ec_name" class="form-control integer-only"  id="displayEcSubstanceName' + esCountNumber + '" readonly>\
                           <span id="ecData' + esCountNumber + '" class="text-danger"></span></div><div class="col-md-3"><label class="control-label">EC Percentage :</label>\n\
                           <span class="text-danger">*</span><input id="ecPercentage' + esCountNumber + '" class="form-control integer-only" name="ec_percentage[]" type="text" placeholder = "%"></div><div class="col-md-2"><br/><button class="btn btn-danger remove tooltips" title="Remove" type="button"><i class="fa fa-remove"></i>&nbsp;Delete\
                          </button></div></div>');
        });
        $(document).on('click', '.remove', function() {
            $(this).parent().parent().remove();
            return false;
        });



        $(document).on("click", ".add-certificate", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            $.ajax({
                url: "{{URL::to('product/newCertificateRow')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#newCertificateRow").append(res.html);
                    $(".js-source-states").select2();
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                    });

                    $('button.reset-date').click(function() {
                        var remove = $(this).attr('remove');
                        $('#' + remove).val('');
                    });
                },
            });

        });

        $(document).on("click", ".add-gl", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            $.ajax({
                url: "{{URL::to('product/newGlRow/')}}",
                type: "POST",
                dataType: "json",
                // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#newGlRow").append(res.html);
                    $(".js-source-states").select2();
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                    });

                    $('button.reset-date').click(function() {
                        var remove = $(this).attr('remove');
                        $('#' + remove).val('');
                    });
                },
            });

        });

        //Buyer positive List
        $(document).on("click", ".add-bpl", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            $.ajax({
                url: "{{URL::to('product/newBplRow')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#newBplRow").append(res.html);
                    $(".js-source-states").select2();
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                    });

                    $('button.reset-date').click(function() {
                        var remove = $(this).attr('remove');
                        $('#' + remove).val('');
                    });
                },
            });

        });

        //Manufacturer Positive List
        $(document).on("click", ".add-mpl", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            $.ajax({
                url: "{{URL::to('product/newMplRow')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#newMplRow").append(res.html);
                    $(".js-source-states").select2();
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                    });

                    $('button.reset-date').click(function() {
                        var remove = $(this).attr('remove');
                        $('#' + remove).val('');
                    });
                },
            });

        });

        $(document).on('blur', '.cas-no', function() {
            var casNo = $(this).val();
            var lastCasNo = $('.cas-no').val();
            var casId = $(this).data('id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            if (casNo == '') {
                $('#displayCasSubstanceName' + casId).val('');
                $('#casData' + casId).text('');
                return false;
            }

            //$('.cas-no').each(function(){
            //alert($(this).val());
//                if (casNo == lastCasNo) {
//                    toastr.error('Please provide unique CAS No', "Error", options);
//                    $('.cas-no').val('');
//                    return false;
//                }
            //});

            $.ajax({
                url: " {{ URL::to('product/generateSubstanceName')}}",
                data: {
                    cas_no: casNo,
                },
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    App.blockUI({boxed: true});
                },
                success: function(res) {
                    if (res.casSubstanceName != null) {
                        $('#casData' + casId).text('');
                        $('#displayCasSubstanceName' + casId).show();
                        $('#displayCasSubstanceName' + casId).val(res.casSubstanceName);
                    } else {
                        $('#displayCasSubstanceName' + casId).hide();
                        $('#casData' + casId).text(res.message);
                    }
                    App.unblockUI();

                }, error: function(jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        // toastr.error(errors, jqXhr.responseJSON.heading, options);
                        //                    $('#displayCasSubstanceName' + casId).hide();
                        //                    $('#casData' + casId).text(errors);
                    }
                    App.unblockUI();
                }
            }); //ajax

        });

        $(document).on('blur', '.ec-no', function() {
            var ecNo = $(this).val();
            var ecId = $(this).data('id');
            if (ecNo == '') {
                $('#displayEcSubstanceName' + ecId).val('');
                $('#ecData' + ecId).text('');
                return false;
            }
            $.ajax({
                url: " {{ URL::to('product/generateEcSubstanceName')}}",
                data: {
                    ec_no: ecNo,
                },
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    App.blockUI({boxed: true});
                },
                success: function(res) {
                    if (res.ecSubstanceName != null) {
                        $('#ecData' + ecId).text('');
                        $('#displayEcSubstanceName' + ecId).show();
                        $('#displayEcSubstanceName' + ecId).val(res.ecSubstanceName);
                    } else {
                        $('#displayEcSubstanceName' + ecId).hide();
                        $('#ecData' + ecId).text(res.message);
                    }
                    App.unblockUI();

                }, error: function(jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        // toastr.error(errors, jqXhr.responseJSON.heading, options);
                        //$('#displayEcSubstanceName' + ecId).html('<div id="ecSubstance"><p class="text-danger">' + errors + '</p></div>');
                    }
                    App.unblockUI();
                }
            }); //ajax

        });

        $(document).on("click", ".substance-submit", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var formData = new FormData($('#submitForm')[0]);

            $.ajax({
                url: "{{URL::to('/product/saveSubstance')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(res) {
                    //console.log(res);
                    toastr.success(res.data, res.message, options);
                    //                    function explode() {
//
//                        window.location.replace('{{URL::to("/product")}}');
//                    }
//                    setTimeout(explode, 2000);
                },
                error: function(jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });

        });

        //Save certificate
        $(document).on("click", ".certificate-submit", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            // Serialize the form data
            var formData = new FormData($('#submitFormCertificate')[0]);

            $.ajax({
                url: "{{URL::to('/product/saveCertificate')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(res) {
                    toastr.success(res.data, 'Certificate Added Successfully', options);
                    // similar behavior as an HTTP redirect
//                    function explode() {
//
//                        window.location.replace('{{URL::to("/product")}}');
//                    }
//                    setTimeout(explode, 2000);

                },
                error: function(jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });

        });

        //Save Gurantee Letter
        $(document).on("click", ".gl-submit", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            // Serialize the form data
            var formData = new FormData($('#submitFormGl')[0]);

            $.ajax({
                url: "{{URL::to('/product/saveGl')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(res) {
                    toastr.success(res.data, 'Gurantee Letter Added Successfully', options);
                    // similar behavior as an HTTP redirect
//                    function explode() {
//
//                        window.location.replace('{{URL::to("/product")}}');
//                    }
//                    setTimeout(explode, 2000);

                },
                error: function(jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });

        });

        //save positive list
        $(document).on("click", ".pl-submit", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            // Serialize the form data
            var formData = new FormData($('#submitFormPl')[0]);

            $.ajax({
                url: "{{URL::to('/product/savePl')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(res) {
                    toastr.success(res.data, 'Positive List Added Successfully', options);
                    // similar behavior as an HTTP redirect
//                    function explode() {
//
//                        window.location.replace('{{URL::to("/product")}}');
//                    }
//                    setTimeout(explode, 2000);

                },
                error: function(jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });

        });

        //save ppe
        $(document).on("click", ".ppe-submit", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            // Serialize the form data
            var formData = new FormData($('#submitFormPpe')[0]);

            $.ajax({
                url: "{{URL::to('/product/savePpe')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(res) {
                    toastr.success(res.data, 'PPE  Added Successfully', options);
                    // similar behavior as an HTTP redirect
//                    function explode() {
//
//                        window.location.replace('{{URL::to("/product")}}');
//                    }
//                    setTimeout(explode, 2000);

                },
                error: function(jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });

        });


        //Save Product to Hazard Catgory :: insert peoduct_to_hazard_cat table
        $(document).on("click", ".hazard-cat-submit", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            // Serialize the form data
            var formData = new FormData($('#submitFormHazardCat')[0]);

            $.ajax({
                url: "{{URL::to('/product/saveHazardCat')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(res) {
                    toastr.success(res.data, 'Hazard Category  Added Successfully', options);
                    // similar behavior as an HTTP redirect
//                    function explode() {
//
//                        window.location.replace('{{URL::to("/product")}}');
//                    }
//                    setTimeout(explode, 2000);

                },
                error: function(jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });

        });


        //save supplier
        $(document).on("click", "#supplier-submit", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            // Serialize the form data
            var formData = new FormData($('#submitFormSupplier')[0]);

            $.ajax({
                url: "{{URL::to('/product/saveSupplier')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(res) {
                    toastr.success(res.data, 'Supplier  Added Successfully', options);
                    // similar behavior as an HTTP redirect
//                    function explode() {
//
//                        window.location.replace('{{URL::to("/product")}}');
//                    }
//                    setTimeout(explode, 2000);

                },
                error: function(jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });

        });


        //save manufacturer
        $(document).on("click", "#manufacturer-submit", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };


            // Serialize the form data
            var formData = new FormData($('#submitFormManufacturer')[0]);

            $.ajax({
                url: "{{URL::to('/product/saveManufacturer')}}",
                type: "POST",
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(res) {
                    toastr.success(res.data, 'Manufacturer  Added Successfully', options);
                    // similar behavior as an HTTP redirect
//                    function explode() {
//
//                        window.location.replace('{{URL::to("/product")}}');
//                    }
//                    setTimeout(explode, 2000);

                },
                error: function(jqXhr, ajaxOptions, thrownError) {

                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });

        });
        

    });
    
    $(document).ready(function() {
        $(".integer-decimal-only").each(function() {
            $(this).keypress(function(e) {
                var code = e.charCode;
                if (((code >= 48) && (code <= 57)) || code == 0 || code == 46) {
                    return true;
                } else {
                    return false;
                }
            });
        });
    
    });
</script>
@stop