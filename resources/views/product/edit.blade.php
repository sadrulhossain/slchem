@extends('layouts.default.master')
@section('data_count')
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cubes"></i>@lang('label.EDIT_PRODUCT')
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($target, ['route' => array('product.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
                {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
                {{csrf_field()}}
                <div class="form-body">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productCatId">@lang('label.PRODUCT_CATEGORY') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::select('product_category_id', $productCategoryArr, null, ['class' => 'form-control select2me', 'id' => 'productCatId']) !!}
                            <span class="text-danger">{{ $errors->first('product_category_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productFuncitonId">@lang('label.PRODUCT_FUNCITON') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::select('product_function_id', $productFunctionArr, null, ['class' => 'form-control select2me', 'id' => 'productFuncitonId']) !!}
                            <span class="text-danger">{{ $errors->first('product_function_id') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                             <div id="productName"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4" for="description">@lang('label.DESCRIPTION') :</label>
                        <div class="col-md-4">
                            {{ Form::textarea('description', null, ['id'=> 'description', 'class' => 'form-control','size' => '30x5']) }}
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="typeOfDosageRatio">@lang('label.TYPE_OF_DOSAGE_RATIO') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::select('type_of_dosage_ratio', $ratioArr, null, ['class' => 'form-control js-source-states', 'id' => 'typeOfDosageRatio','tabindex' => '5']) !!}
                            <span class="text-danger">{{ $errors->first('type_of_dosage_ratio') }}</span>
                        </div>
                    </div>
                    @if((in_array($target->type_of_dosage_ratio,['1','2'])) || $errors->first('from_dosage') || $errors->first('to_dosage'))
                    <div class="form-group" id='doseDiv'>
                        <label class="control-label col-md-4">@lang('label.DOSAGE_RATIO') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('from_dosage', null, ['id'=> 'fromDosage', 'placeholder' => 'From','class' => 'form-control integer-decimal-only from-dosage','autocomplete' => 'off','tabindex' => '6','placeholder' => 'Ex: 0.1']) !!} 
                                <span class="input-group-addon"> @lang('label.TO') </span>
                                {!! Form::text('to_dosage', null, ['id'=> 'toDosage', 'placeholder' => 'To','class' => 'form-control integer-decimal-only to-dosage','autocomplete' => 'off','tabindex' => '6','placeholder' => 'Ex: 0.2']) !!} 
                            </div>
                            <div class="">
                                <span class="text-danger from-span">{{ $errors->first('from_dosage') }}</span>
                                 <span class="text-danger to-span">{{ $errors->first('to_dosage') }}</span>
                           </div>
                        </div>
                    </div>
                    @else
                    <div class="form-group" id='doseDiv' style="display: none;">
                        <label class="control-label col-md-4">@lang('label.DOSAGE_RATIO') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('from_dosage', null, ['id'=> 'fromDosage', 'placeholder' => 'From','class' => 'form-control integer-decimal-only from-dosage','autocomplete' => 'off','tabindex' => '6','placeholder' => 'Ex: 0.1']) !!} 
                                <span class="input-group-addon"> @lang('label.TO') </span>
                                {!! Form::text('to_dosage', null, ['id'=> 'toDosage', 'placeholder' => 'To','class' => 'form-control integer-decimal-only to-dosage','autocomplete' => 'off','tabindex' => '6','placeholder' => 'Ex: 0.2']) !!} 
                            </div>
                            <div class="">
                                <span class="text-danger from-span">{{ $errors->first('from_dosage') }}</span>
                                 <span class="text-danger to-span">{{ $errors->first('to_dosage') }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="control-label col-md-4" for="recommendedDosage">@lang('label.RECOMMENDED_DOSAGE') :</label>
                        <div class="col-md-4">
                            {{ Form::textarea('recommended_dosage', null, ['id'=> 'recommendedDosage', 'class' => 'form-control','size' => '30x5']) }}
                            <span class="text-danger">{{ $errors->first('recommended_dosage') }}</span>
                        </div>
                    </div>

                    @if(!empty($userAccessArr[20][16]))
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productCode">@lang('label.PRODUCT_CODE') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::text('product_code', null, ['id'=> 'productCode', 'class' => 'form-control integer-only','maxlength' => '4','autocomplete' => 'off']) !!} 
                            <span class="text-danger">{{ $errors->first('product_code') }}</span>
                        </div>
                    </div>
                    @else
                    {!! Form::hidden('product_code', null, ['id'=> 'productCode']) !!}
                    @endif


                    <div class="form-group">
                        <label class="control-label col-md-4" for="primaryUnitId">@lang('label.PRIMARY_UNIT') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::select('primary_unit_id', $primaryUnitArr, null, ['class' => 'form-control select2me', 'id' => 'primaryUnitId']) !!}
                            <span class="text-danger">{{ $errors->first('primary_unit_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="secondaryUnitId">@lang('label.SECONDARY_UNIT') :</label>
                        <div class="col-md-4">
                            {!! Form::select('secondary_unit_id', $secondaryUnitArr, null, ['class' => 'form-control select2me', 'id' => 'secondaryUnitId']) !!}
                            <span class="text-danger">{{ $errors->first('secondary_unit_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="sds">@lang('label.SAFETY_DATA_SHEET') : </label>
                        <div class="col-md-4">
                            <div class="mt-checkbox-list">
                                <label class="mt-checkbox">
                                    {!! Form::checkbox('sds',null, null, ['id'=> 'sds']) !!}@lang('label.YES')&nbsp;@lang('label.IF_YES_UPLOAD_ATTACHMENT')
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="sdsHolder" style="display:none;">
                        <label class="control-label col-md-4" for="sds">@lang('label.ATTACHMENT') : </label>
                        <div class="col-md-4">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <span class="btn green btn-file">
                                    <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                    <span class="fileinput-exists"> @lang('label.CHANGE')</span>
                                    {!! Form::file('sds_file',['id'=> 'sdsFile']) !!}
                                </span>
                                @if(!empty($target->sds_file))
                                <a href="{{URL::to('public/uploads/safetyDataSheet/'.$target->sds_file)}}"
                                   class="btn yellow-crusta btn-sm tooltips" title="{{ !empty($target->sds_file_name) ? $target->sds_file_name : __('label.SAFETY_DATA_SHEET_PREVIEW') }}" target="_blank">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>
                                @endif
                                <span class="help-block text-danger">{!! $errors->first('sds_file') !!}</span>
                                <span class="fileinput-filename"></span> &nbsp;
                                <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                            </div>
                            <div>
                                <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.FILE_INSTRUCTION')
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4" for="tds">@lang('label.TECHNICAL_DATA_SHEET') : </label>
                        <div class="col-md-4">
                            <div class="mt-checkbox-list">
                                <label class="mt-checkbox">
                                    {!! Form::checkbox('tds', null, null, ['id'=> 'tds']) !!}@lang('label.YES')&nbsp;@lang('label.IF_YES_UPLOAD_ATTACHMENT')
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="tdsHolder" style="display:none;">
                        <label class="control-label col-md-4" for="tds">@lang('label.ATTACHMENT') : </label>
                        <div class="col-md-4">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <span class="btn green btn-file">
                                    <span class="fileinput-new">@lang('label.SELECT_FILE')</span>
                                    <span class="fileinput-exists"> @lang('label.CHANGE')</span>
                                    {!! Form::file('tds_file',['id'=> 'tdsFile']) !!}
                                </span>
                                @if(!empty($target->tds_file))
                                <a href="{{URL::to('public/uploads/technicalDataSheet/'.$target->tds_file)}}"
                                   class="btn yellow-crusta btn-sm tooltips" title="{{ !empty($target->tds_file_name) ? $target->tds_file_name : __('label.TECHNICAL_DATA_SHEET_PREVIEW') }}" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                @endif
                                <span class="help-block text-danger">{!! $errors->first('tds_file') !!}</span>
                                <span class="fileinput-filename"></span> &nbsp;
                                <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                            </div>
                            <div>
                                <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.FILE_INSTRUCTION')
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4" for="storageCondition">@lang('label.STORAGE_CONDITION') :</label>
                        <div class="col-md-4">
                            {!! Form::text('storage_condition', null, ['id'=> 'storageCondition', 'class' => 'form-control']) !!} 
                            <span class="text-danger">{{ $errors->first('storage_condition') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="storageLocation">@lang('label.STORAGE_LOCATION') :</label>
                        <div class="col-md-4">
                            {!! Form::text('storage_location', null, ['id'=> 'storageLocation', 'class' => 'form-control']) !!} 
                            <span class="text-danger">{{ $errors->first('storage_location') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="sdsVersion">@lang('label.SDS_VERSION') :</label>
                        <div class="col-md-4">
                            {!! Form::text('sds_version', null, ['id'=> 'sdsVersion', 'class' => 'form-control']) !!}
                            <span class="text-danger">{{ $errors->first('sds_version') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">@lang('label.DATE') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            <div class="input-group date datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="+0d" >
                                {!! Form::text('date', null, ['id'=> 'date', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!}
                                <span class="input-group-btn">
                                    <button class="btn default reset-date date" type="button" remove="date">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>

                                </span>

                            </div>
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="reorderLevel">@lang('label.REORDER_LEVEL') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::number('reorder_level', null, ['id'=> 'reorderLevel', 'class' => 'form-control integer-decimal-only','autocomplete' => 'off']) !!} 
                            <span class="text-danger">{{ $errors->first('reorder_level') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="showInReport">@lang('label.DONT_SHOW_AT_STORE_REPORT')</label>
                        <div class="col-md-8">
                            <div class="mt-checkbox-inline">
                                <label class="mt-checkbox">
                                    {!! Form::checkbox('show_in_report', 1, $showInReport, ['id' => 'showInReport']) !!}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                        <div class="col-md-4 tooltips" title="Active Ingredients">
                            {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], null, ['class' => 'form-control select2me', 'id' => 'status']) !!}
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn green" type="submit">
                                <i class="fa fa-check"></i> @lang('label.SUBMIT')
                            </button>
                            <a href="{{ URL::to('/product'.Helper::queryPageStr($qpArr)) }}" class="btn btn-outline grey-salsa">@lang('label.CANCEL')</a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>	
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(function() {
    @if($target -> sds == '1')
            $('#sdsHolder').show();
            @endif
            $("#sds").change(function() {
    if (this.checked) {
    $('#sdsHolder').show('slow');
    } else {
    $('#sdsHolder').hide('slow');
    }
    });
            @if($target -> tds == '1')
            $('#tdsHolder').show();
            @endif
            $("#tds").change(function() {
    if (this.checked) {
    $('#tdsHolder').show('slow');
    } else {
    $('#tdsHolder').hide('slow');
    }
    });
            @if(($target -> type_of_dosage_ratio == '1') || ($target -> type_of_dosage_ratio == '2'))
            $('#doseDiv').show();
            @else
            $('#doseDiv').hide();
            @endif


            $("#typeOfDosageRatio").change(function() {
    var dosingRatio = $(this).val();
            if ((dosingRatio == '1') || (dosingRatio == '2')) {
    $('#fromDosage').val('');
            $('#toDosage').val('');
            $('#doseDiv').show();
    } else {
    $('#doseDiv').hide();
    }
    });
            /************** START :: JS for Unit Conversion ***********/

            $(document).on('keyup', '#fromDosage', function() {
            var fromDosage = $('#fromDosage').val();
            if (!isNaN(fromDosage)) {
                validateNumberInputVariable('#fromDosage',fromDosage, 1); // Check Qty 6 digits after decimal point
            }
        });


        $(document).on('keyup', '#toDosage', function() {
            var toDosage = $('#toDosage').val();
            if (!isNaN(toDosage)) {
                validateNumberInputVariable('#toDosage',toDosage, 1); // Check Qty 6 digits after decimal point
            }
        });


        function validateNumberInputVariable(id,totalQty, length) {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            //Find out the position of "." at Quantity
            var totalQtyPointPos = totalQty.toString().indexOf(".");
            if (totalQtyPointPos != -1) {
                var totalQtyArr = totalQty.toString().split(".");
                var kgAmnt = totalQtyArr[0];
                var gmAmntStr = totalQtyArr[1];
                var gmAmntStrLen = gmAmntStr.length;
                if (gmAmntStrLen > length) {
                    var allowedQtyStr = kgAmnt + "." + gmAmntStr.substring(0, length);
                    //$('#qty-' + processProductId).val(allowedQtyStr);
                    if (totalQty != '') {
                        if(id == '#fromDosage'){
                            $('#fromDosage').val(allowedQtyStr);
                        }else if(id == '#toDosage'){
                            $('#toDosage').val(allowedQtyStr);
                        }
                    }
                    toastr.error('Error', "Only 1 Digit after Decimal point is allowed!", options);
                    return false;
                }//EOF - if length
            }//EOF - if -1
        }//EOF - function
        /************** END :: JS for Unit Conversion ***********/
        
        $('#name').keyup(function(e) {
            e.preventDefault();
            var maxlength = 1;
            var value = $(this).val();

            if (value == '') {
                $('#productName').html('');
                $('span#character-count').text('');
                return false;
            }

            if (value.length >= maxlength) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: "{{URL::to('product/loadProductNameEdit')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'product_name': value
                    },
                    beforeSend: function() {
                        App.blockUI({boxed: true});
                    },
                    success: function(res) {
                        //we need to check if the value is the same
                        //Receiving the result of search here
                        $('#productName').html(res.html);
                        
                        $("#searchResult li").bind("click", function() {
                            setText(this);
                            $('#searchResult').css('border', '0px');
                        });
                        App.unblockUI();
                    }
                });
            }
        });

        //For Click Outside of loaded element
        $(document).mouseup(function(e)
        {
            var container = $("#searchResult"); // YOUR CONTAINER SELECTOR
            if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.hide();
            }
        });

        function setText(element) {
            var value = $(element).text();
            if (value == '') {
                $("#searchResult").click(function(event) {
                    event.stopPropagation();
                });
            } else {
                $("#name").val(value);
                $("#searchResult").empty();
            }

        }
    });
</script>
@stop