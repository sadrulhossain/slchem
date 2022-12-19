@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.GENERATE_BATCH_CARD')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'generateBatchCard')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2" for="recipeId">@lang('label.SELECT_RECIPE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-4">
                                {!! Form::select('recipe_id', $recipeArr, null, ['id'=> 'recipeId', 'class' => 'form-control js-source-states']) !!}
                                <span class="text-danger">{{ $errors->first('recipe_id') }}</span>
                            </div>

                            <div id="referenceHolder"></div>

                        </div>
                        <div class="form-group" id="recipeDataHolder" style="display: none; "></div>

                        <div class="form-group">

                            <label class="control-label col-md-2" for="operatorName">@lang('label.OPERATOR_NAME') :</label>
                            <div class="col-md-2">
                                {!! Form::text('operator_name',null, ['id'=> 'operatorName', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                            </div>
                            <label class="control-label col-md-1" for="shiftId">@lang('label.SHIFT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-2">
                                {!! Form::select('shift_id', $shiftArr, null,['id'=> 'shiftId', 'class' => 'form-control js-source-states']) !!} 
                                <span class="text-danger">{{ $errors->first('shift_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2" for="remarks">@lang('label.REMARKS') :</label>
                            <div class="col-md-10">
                                {!! Form::textarea('remarks', null, ['class' => 'form-control', 'id' => 'remarks', 'rows' => '3', 'cols' => 40]) !!}
                                <span class="text-danger">{{ $errors->first('remarks') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-circle green" type="submit" id="saveBatchCard" disabled>
                            <i class="fa fa-check"></i> @lang('label.SAVE_AND_FINALIZE')
                        </button>
                        <a href="{{ URL::to('batchCard'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        $(document).on("change", "#recipeId", function () {
            generateRecipeInfo();

        });

        function generateRecipeInfo() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var recipeId = $('#recipeId').val();
            if (recipeId == '0') {
                $('#recipeDataHolder').html('');
                $('#referenceHolder').html('');
                $('#saveBatchCard').prop('disabled', true);
                App.unblockUI();
                return false;
            }

            $.ajax({
                url: "{{URL::to('batchCard/getRecipeInfo')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    recipe_id: recipeId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#recipeDataHolder').show();
                    $('#referenceHolder').show();
                    $('#recipeDataHolder').html(res.html);
                    $('#referenceHolder').html(res.reference);
                    $('#saveBatchCard').prop('disabled', false);
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
            });
        }

        $(document).on("click", "#saveBatchCard", function (e) {
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

            var formData = new FormData($('#generateBatchCard')[0]);
            swal({
                title: "Are you sure, you want to Save and Finalize this Batch Card ?",
                type: "warning",
                text: "You will not be able to further modify this!",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Confirmed",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('batchCard/saveBatchCard')}}",
                        type: "POST",
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            $('#saveBatchCard').prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success("@lang('label.BATCH_CARD_CREATED_SUCCESSFULLY')", 'Success', options);
                            window.location.href = '{!! URL::to("batchCard") !!}';
                            App.unblockUI();

                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {

                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            $('#saveBatchCard').prop('disabled', false);
                            App.unblockUI();
                        }

                    });
                }
            });
        });

    });

</script>

@stop