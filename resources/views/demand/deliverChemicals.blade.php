@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i> @lang('label.LIST_OF_CHEMICALS_TO_DELVER')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'deliverChemicals/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',Request::get('search'), ['class' => 'form-control tooltips','id' => 'search', 'title' => 'Token No', 'placeholder' => 'Token No','autocomplete'=>'off']) !!} 
                            <div id="tokenNo"></div>
                            <span id="character-count"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="control-label col-md-4">@lang('label.DATE') </label>
                    <div class="input-group date datepicker col-md-8 date-width" style="z-index:0!important;" data-date-end-date="+0d">
                        {!! Form::text('date', Request::get('date'), ['id'=> 'date', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                        <span class="input-group-btn">
                            <button class="btn default reset-date date" type="button" remove="date">
                                <i class="fa fa-times"></i>
                            </button>
                            <button class="btn default date-set" type="button">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="machine">@lang('label.MACHINE_NO')</label>
                        <div class="col-md-8">
                            {!! Form::select('machine',  $machineArr, Request::get('machine'), ['class' => 'form-control js-source-states','id'=>'machine']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="batch">@lang('label.BATCH_CARD')</label>
                        <div class="col-md-8">
                            {!! Form::text('batch_card',Request::get('batch_card_ref'), ['class' => 'form-control tooltips', 'title' => 'Batch No', 'placeholder' => 'Batch Card No' ,'autocomplete'=>'off','id' => 'batchCard']) !!}
                            {!! Form::hidden('batch_card_id',Request::get('batch_card_id'), ['class' => 'form-control tooltips', 'title' => 'Batch No', 'placeholder' => 'Batch Card No' ,'autocomplete'=>'off','id' => 'batchCardId']) !!}
                            <div id="batchCardNo"></div>
                            <span id="batchcard-character-count"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="garments">@lang('label.TYPE_OF_GARMENTS')</label>
                        <div class="col-md-8">
                            {!! Form::select('garments',  $garmentsArr, Request::get('garments'), ['class' => 'form-control js-source-states', 'id'=>'garments']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="style">@lang('label.STYLE')</label>
                        <div class="col-md-8">
                            {!! Form::select('style',  $styleArr, Request::get('style'), ['class' => 'form-control js-source-states', 'id'=>'style']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>

                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
            @if (!empty($qpArr))
            <div class="margin-bottom-20">
                <button type="button" class="btn purple margin-bottom-20 pull-right" id="pageRefresh">
                    <i class="fa fa-refresh"></i> @lang('label.REFRESH') 
                </button>
                @if(!empty($userAccessArr[49][7]))
                {{ Form::hidden('allItem', null, array('id'=>'allItem'))}}
                {{ Form::hidden('allRtpItem', null, array('id'=>'allRtpItem'))}}
                <button class="btn btn-success btn-large allRequest" type="button" data-placement="top" data-rel="tooltip" name="request" data-original-title="Request All" value="request" disabled>
                    <i class="fa fa-check text-white"></i>  @lang('label.DELIVER_SELECTED_CHEMICALS')
                </button>
                @endif

            </div>
            <div class="table-responsive">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="vcenter text-center">
                                @if(count($targetArr)>=1)
                                @if((!empty($qpArr)) && (!empty(Request::get('batch_card_id'))))
                                <div class="md-checkbox has-success tooltips"  data-placement="top" data-rel="tooltip" data-original-title="CheckAll">
                                    {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check']) !!}
                                    <label for="checkAll">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                                @else
                                #
                                @endif
                                @else
                                #
                                @endif
                            </th>
                            <th class=" vcenter ">@lang('label.SL_NO')</th>
                            <th class=" vcenter ">@lang('label.TOKEN_NO')</th>
                            <th class=" vcenter ">@lang('label.BATCH_CARD_NO')</th>
                            <th class=" vcenter ">@lang('label.MACHINE_NO')</th>
                            <th class=" vcenter">@lang('label.DATE')</th>
                            <th class=" vcenter ">@lang('label.STYLE')</th>
                            <th class=" vcenter ">@lang('label.BUYER')</th>
                            <th class=" vcenter ">@lang('label.TYPE_OF_GARMENTS')</th>
                            <th class=" vcenter ">@lang('label.CREATED_BY')</th>
                            <th class=" vcenter text-center">@lang('label.CREATED_AT')</th>
                            <th class="td-actions text-center vcenter">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Input::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td class="vcenter">
                                <div class="md-checkbox has-success">
                                    {!! Form::checkbox('demand_id.'.$target->id, $target->id, null, ['id' => $target->id,'data-id'=>$target->id ,'data-rtp-id'=>$target->rtp_id ,'class'=> 'md-check demand-deliver-check' ]) !!}
                                    <label for="{{ $target->id }}">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                            </td>
                            <td class="vcenter">{{ ++$sl }}</td>
                            <td class="token-width vcenter">
                                <span class="label label-danger">{{ $target->token_no }}</span>
                            </td>
                            <td class="vcenter">{{ $target->batch_card }}</td>
                            <td class="vcenter">{{ $target->machine_no }}</td>
                            <td class="vcenter">{{ $target->date }}</td>
                            <td class="vcenter">{{ $target->style }}</td>
                            <td class="vcenter">{{ $target->buyer }}</td>
                            <td class="vcenter">{{ $target->garments_type }}</td>
                            @if (!empty($target->created_by))
                            <td class=" vcenter">{!! $userFirstNameArr[$target->created_by].' '.$userLastNameArr[$target->created_by] !!}</td>
                            @else
                            <td class=" vcenter">---</td>
                            @endif

                            @if (!empty($target->created_at))
                            <td class="text-center vcenter">{{Helper::printDateFormat($target->created_at) }}</td>
                            @else
                            <td class="text-center vcenter">---</td>
                            @endif
                            <td class="text-center">
                                <!--                                <button type="button" class="btn btn-xs btn-success tooltips deliver" id="deliver-{!! $target->id !!}" data-demand-id="{!! $target->id !!}" data-rtp-id="{{ $target->rtp_id }}" title="Deliver">
                                                                    <i class="fa fa-check"></i>
                                                                </button>-->
                                @if(!empty($userAccessArr[49][6]))
                                <a class="btn btn-xs btn-info tooltips vcenter" title="Print" href="{{ URL::to('deliverChemicals/getDetails/' . $target->id.'?view=print&' .Helper::queryPageStr($qpArr)) }}" target="_blank">
                                    <i class="fa fa-print text-white"></i>
                                </a>
                                @endif
                                @if(!empty($userAccessArr[49][5]))
                                <button type="button" class="btn btn-xs yellow tooltips vcenter details-btn" title="View Details" id="detailsBtn-{{$target->id}}" data-target="#demandDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="13">@lang('label.NO_DEMAND_LETTER_FOUND_TO_DELIVER')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div>
                    @if(!empty($userAccessArr[49][7]))
                    {{ Form::hidden('allItem', null, array('id'=>'allItem'))}}
                    {{ Form::hidden('allRtpItem', null, array('id'=>'allRtpItem'))}}
                    <button class="btn btn-success btn-large allRequest" type="button" data-placement="top" data-rel="tooltip" name="request" data-original-title="Request All" value="request" disabled>
                        <i class="fa fa-check text-white"></i>  @lang('label.DELIVER_SELECTED_CHEMICALS')
                    </button>
                    @endif
                </div>

            </div>
            @include('layouts.paginator')
            @endif
        </div>	
    </div>
</div>
<div class="modal container fade" id="demandDetails" tabindex="-1">
    <div id="showDemandDetails">
        <!--    ajax will be load here-->
    </div>
</div>
<script type="text/javascript">
    $(document).on('click', '.details-btn', function () {
        var demandId = $(this).data("id");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{URL::to('deliverChemicals/getDetails')}}",
            type: 'POST',
            dataType: 'json',
            data: {
                demand_id: demandId
            },
            beforeSend: function () {
                $('#showDemandDetails').html('');
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showDemandDetails').html(res.html);
                App.unblockUI();
            },
        });
    });


    $(document).on('click', '#pageRefresh', function () {
        window.location.reload();
    });


    //check all checkboxes
    $('#checkAll').change(function () {  //'check all' change
        $('.demand-deliver-check').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status
        if ($('#' + this.id).is(":checked")) {
            var newStr = ',';
            var newRtpStr = ',';
            $(".demand-deliver-check").each(function (index) {
                $('#' + this.id).prop('checked', true);
                newStr = newStr.concat($('#' + this.id).attr('data-id') + ',');
                newRtpStr = newRtpStr.concat($('#' + this.id).attr('data-rtp-id') + ',');

            });
            $('#allItem').val(newStr);
            $('#allRtpItem').val(newRtpStr);
            $('.allRequest').attr('disabled', false);
        } else {
            $(".demand-deliver-check").each(function (index) {
                $('#' + this.id).prop('checked', false);
            });
            $('#allItem').val('');
            $('#allRtpItem').val('');
            $('.allRequest').attr('disabled', true);
        }

    });

    $(document).on("click", '.demand-deliver-check', function (e) {
        var deleteAllStr = $('#allItem').val();
        var deleteAllRtpStr = $('#allRtpItem').val();
        var dataId = $('#' + this.id).attr('data-id');
        var dataRtpId = $('#' + this.id).attr('data-rtp-id');
        if ($('#' + this.id).is(":checked")) {
            $('#allItem').val('');
            $('#allRtpItem').val('');
            if (deleteAllStr == '') {
                var newStr = ',' + dataId + ',';
                var newRtpStr = ',' + dataRtpId + ',';
            } else {
                var newStr = deleteAllStr + dataId + ',';
                var newRtpStr = deleteAllRtpStr + dataRtpId + ',';
            }
            $('.allRequest').attr('disabled', false);

        } else {
            var commaCount = deleteAllStr.split(",").length - 1;
            if (commaCount == 2) {
                var newStr = deleteAllStr.replace(',' + dataId + ',', '');
                var newRtpStr = deleteAllRtpStr.replace(',' + dataRtpId + ',', '');
            } else {
                var newStr = deleteAllStr.replace(dataId + ',', '');
                var newRtpStr = deleteAllRtpStr.replace(dataRtpId + ',', '');
            }
            $('.allRequest').attr('disabled', true);
        }
        $('#allItem').val(newStr);
        $('#allRtpItem').val(newRtpStr);
    });


    $('.demand-deliver-check').change(function () {

        var demandVal = 0;
        var demandId = $('#' + this.id).val();
        var elemID = this.id;
        var deleteAllStr = $('#allItem').val();
        var deleteAllRtpStr = $('#allRtpItem').val();
        var dataId = $('#' + this.id).attr('data-id');
        var dataRtpId = $('#' + this.id).attr('data-rtp-id');
        if ($('#' + this.id).is(':checked')) {
            demandVal = 1;
            $('.allRequest').attr('disabled', false);//enable print button if check box checked
        } else {
            $('.allRequest').attr('disabled', true);//disable print button if check box checked
        }
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        $.ajax({
            url: '{{URL::to("deliverChemicals/getDemandId/")}}',
            type: 'post',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                demand_id: demandId,
                demand_val: demandVal,
            },
            success: function (res) {
                console.log(res);
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
                    $('#' + elemID).attr('checked', false);
                    var commaCount = deleteAllStr.split(",").length - 1;
                    if (commaCount == 2) {
                        var newStr = deleteAllStr.replace(',' + dataId + ',', '');
                        var newRtpStr = deleteAllRtpStr.replace(',' + dataRtpId + ',', '');
                    } else {
                        var newStr = deleteAllStr.replace(dataId + ',', '');
                        var newRtpStr = deleteAllRtpStr.replace(dataRtpId + ',', '');
                    }

                    if ((newStr == '') && (newRtpStr == '')) {
                        $('.allRequest').attr('disabled', true);
                    }
                    $('#allItem').val(newStr);
                    $('#allRtpItem').val(newRtpStr);
                } else {
                    toastr.error('Error', 'Something went wrong', options);
                }
                App.unblockUI();
            }

        });
    });


    $(document).on("click", ".allRequest", function (e) {
        e.preventDefault();

        var rtpItem = $('#allRtpItem').val();
        var item = $('#allItem').val();
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        //alert(item);return;
        swal({
            title: "Are you sure, you want to Deliver?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Deliver It",
            closeOnConfirm: true,
            closeOnCancel: true,
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ URL::to('deliverChemicals/deliver')}}",
                    type: "POST",
                    dataType: 'json',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        source: 2,
                        all_item: item,
                        all_rtp_item: rtpItem,
                    },
                    beforeSend: function () {
                        $('.allRequest').prop('disabled', true);
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        toastr.success(res.data, 'Chemical Delivered Successfully', options);
                        // similar behavior as an HTTP redirect
                        function explode() {
                            window.location.replace('{!! URL::to("deliverChemicals") !!}');
                        }
                        setTimeout(explode, 2000);
                        //App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        if (jqXhr.status == 400) {
                            //var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
//                            $.each(errors, function (key, value) {
//                                errorsHtml += '<li>' + value[0] + '</li>';
//                            });
                            toastr.error(errors, jqXhr.responseJSON.heading, options);
                        } else if (jqXhr.status == 401) {
                            var errorsHtml = '';
                            var errors = jqXhr.responseJSON.message;
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value + '</li>';
                            });
                            toastr.error(errorsHtml, '', options);
                        } else {
                            toastr.error('Error', 'Something went wrong', options);
                        }
                        $('.allRequest').prop('disabled', false);
                        App.unblockUI();
                    }
                });
            }

        });
    });

    /* Load Token No */

    $(document).ready(function () {
        $('#search').keyup(function (e) {
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
                $('#tokenNo').html('');
                $('span#character-count').text('');
                return false;
            }
            var valueLength = value.length;
            var char = maxlength - valueLength;
            if (char > 1) {
                var characters = 'characters';
            } else {
                var characters = 'character';
            }

            if ((valueLength <= maxlength) && (char != 0)) {
                $('span#character-count').text("Please Insert " + char + ' more ' + characters);
                $('span#character-count').css("color", "blue");
                return false;

            }

            if (value.length >= maxlength) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: "{{URL::to('deliverChemicals/loadTokenToDeliver')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'search_keyword': value
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        //we need to check if the value is the same
                        //Receiving the result of search here
                        $('#tokenNo').html(res.html);
                        $("#tokenResult li").bind("click", function () {
                            setText(this);
                        });
                        $('span#character-count').text('');
                        App.unblockUI();
                    }
                });
            }
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
                $("#batchCardId").val('');
                $('#batchCardNo').html('');
                $('span#batchcard-character-count').text('');
                return false;
            }

            var valueLength = value.length;
            var char = maxlength - valueLength;
            if (char > 1) {
                var characters = 'characters';
            } else {
                var characters = 'character';
            }

            if ((valueLength <= maxlength) && (char != 0)) {
                $('span#batchcard-character-count').text("Please Insert " + char + ' more ' + characters);
                $('span#batchcard-character-count').css("color", "blue");
                return false;

            }

            if (value.length >= maxlength) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: "{{URL::to('deliverChemicals/loadBatchTokenForDeliver')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'search_keyword': value
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        //we need to check if the value is the same
                        //Receiving the result of search here
                        $('#batchCardNo').html(res.html);
                        $("#searchResult li").bind("click", function () {
                            setBatchText(this);
                        });
                        $('span#batchcard-character-count').text('');
                        App.unblockUI();
                    }
                });
            }
        });


        // AFTER SEARCH BY BATCH TOKEN, CLICK ANOTHER SPACE AND HIDE THE TOKEN CONTAINER :: START    
        $(document).mouseup(function (e)
        {
            var container = $("#searchResult"); // YOUR CONTAINER SELECTOR
            if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.hide();
            }
        });
        // AFTER SEARCH BY BATCH TOKEN, CLICK ANOTHER SPACE AND HIDE THE TOKEN CONTAINER :: END

        // AFTER SEARCH BY DEMAND TOKEN, CLICK ANOTHER SPACE AND HIDE THE TOKEN CONTAINER :: START 
        $(document).mouseup(function (e)
        {
            var tokenContainer = $("#tokenResult"); // YOUR CONTAINER SELECTOR

            if (!tokenContainer.is(e.target) // if the target of the click isn't the container...
                    && tokenContainer.has(e.target).length === 0) // ... nor a descendant of the container
            {
                tokenContainer.hide();
            }
        });
        // AFTER SEARCH BY DEMAND TOKEN, CLICK ANOTHER SPACE AND HIDE THE TOKEN CONTAINER :: END 

    }); // EOF --FUNCTION




    // SET DEMAND TOKEN NO AFTER KEYUP IN FILTER :: START
    function setText(element) {
        var value = $(element).text();
        var id = $(element).val();
        if (id == '') {
            $("#tokenResult").click(function (event) {
                event.stopPropagation();
            });
        } else {
            $("#search").val(value);
            $("#tokenResult").empty();
        }

    } //EOF --FUNCTION
    // SET DEMAND TOKEN NO AFTER KEYUP IN FILTER :: END

    //Set BatchCard after Type
    function setBatchText(element) {
        var value = $(element).text();
        var id = $(element).val();
        if (id == '') {
            $("#searchResult").click(function (event) {
                event.stopPropagation();
            });
        } else {
            $("#batchCard").val(value);
            $("#batchCardId").val(id);
            $("#searchResult").empty();
        }

    }// EOF --FUNCTION
</script>

@stop