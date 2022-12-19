@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.GENERATE_DEMAND_LETTER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'generateDemandPaper')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-5" for="batchCardId">@lang('label.BATCH_CARD') :<span class="text-danger"> *</span></label>
                            <div class="col-md-7">
                                {!! Form::text('batch_card',Request::get('batch_card_ref'), ['class' => 'form-control tooltips', 'title' => 'Batch No', 'placeholder' => 'Batch Card No' ,'autocomplete'=>'off','id' => 'batchCard']) !!}
                                {!! Form::hidden('batch_card_id',null,['class' => 'form-control tooltips', 'title' => 'Batch No', 'placeholder' => 'Batch Card No' ,'autocomplete'=>'off','id' => 'batchCardId']) !!}
                                <div id="batchCardNo"></div>
                                <span id="character-count"></span>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="form">
                            <div id="recipeDataHolder">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-circle green" type="submit" id="saveDemandPaper" disabled>
                            <i class="fa fa-check"></i> @lang('label.GENERATE')
                        </button>
                        <a href="{{ URL::to('generateDemand') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        $(document).on("click", "#saveDemandPaper", function (e) {
            e.preventDefault();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: "Are you sure to generate this Demand?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Generate Demand",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
					 $('#saveDemandPaper').prop('disabled',true);
                    var formData = new FormData($('#generateDemandPaper')[0]);
                    // alert(formData);return;
                    $.ajax({
                        url: "{{URL::to('generateDemand/saveDemand')}}",
                        type: "POST",
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        beforeSend: function () {
                            $('#saveDemandPaper').prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.data, "@lang('label.DEMAND_PAPER_CREATED_SUCCESSFULLY')", options);
                            window.location.href = "{{URL::to('demand')}}";
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
                            $('#saveDemandPaper').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
                }//if isConfirm
            });
        });

        //Type Batch Card
        $('#batchCard').keyup(function (e) {
            e.preventDefault();
            var maxlength = 3;
            var value = $(this).val();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            if (value == '') {
                $('#batchCardNo').html('');
                $('#recipeDataHolder').html('');
                $('#saveDemandPaper').prop('disabled', true);//Make button disabled
                $('span#character-count').text('');
                return false;
            }
            var valueLength = value.length;
            var char = maxlength - valueLength;
            if (char > 1) {
                var characters = 'characters';
            }else{
                 var characters = 'character';
            }

            if ((valueLength <= maxlength) && (char != 0)) {
                $('span#character-count').text("Please Insert " + char + ' more '+ characters);
                $('span#character-count').css("color", "blue");
                return false;

            }

            if (value.length >= maxlength) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: "{{URL::to('generateDemand/loadBatchToken')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'search_keyword': value
                    },
                    beforeSend: function () {
                        // App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        //we need to check if the value is the same
                        //Receiving the result of search here
                        $('#batchCardNo').html(res.html);
                        $('span#character-count').text('');
                        $("#searchResult li").bind("click", function () {
                            setBatchText(this);
                            //get Recipe Information after select batch card number
                            var batchCardId = $('#batchCardId').val();
                            if (batchCardId == '0') {
                                $("#searchResult").click(function (event) {
                                    event.stopPropagation();
                                });
                                $('#recipeDataHolder').html('');
                                $('#saveDemandPaper').prop('disabled', true);//Make button disabled
                                return false;
                            }

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url: "{{URL::to('generateDemand/getRecipeInfo')}}",
                                type: 'POST',
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    batch_card_id: batchCardId
                                },
                                beforeSend: function () {
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    $('#recipeDataHolder').html(res.html);
                                    $('#saveDemandPaper').prop('disabled',true);//Make button disabled
                                    App.unblockUI();
                                },
                            });
                        });
                        //App.unblockUI();
                    }
                });
            }
        });

        $(document).mouseup(function (e)
        {
            var container = $("#searchResult"); // YOUR CONTAINER SELECTOR
            if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.hide();
            }
        });
    });

    //Set BatchCard after Type
    function setBatchText(element) {
        var value = $(element).text();
        var id = $(element).val();
        if (id == '') {
            $("#searchResult").click(function (event) {
                event.stopPropagation();
            });
            $("#batchCardId").val('0');
            $('#recipeDataHolder').html('');
            return false;
        } else {
            $("#batchCard").val(value);
            $("#batchCardId").val(id);
            $("#searchResult").empty();
        }
    }

</script>

@stop