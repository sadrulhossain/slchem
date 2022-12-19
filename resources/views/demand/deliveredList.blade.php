@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>
                @lang('label.DELIVERED_CHECMICALS_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'deliveredChemicalsList/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',Request::get('search'), ['class' => 'form-control tooltips','id' => 'search', 'title' => 'Token No', 'placeholder' => 'Token No', 'list'=>'search', 'autocomplete'=>'off']) !!} 
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="shift">@lang('label.SHIFT')</label>
                        <div class="col-md-8">
                            {!! Form::select('shift',  $shiftArr, Request::get('shift'), ['class' => 'form-control js-source-states', 'id'=>'shift']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="factory">@lang('label.FACTORY')</label>
                        <div class="col-md-8">
                            {!! Form::select('factory',  $factoryArr, Request::get('factory'), ['class' => 'form-control js-source-states', 'id'=>'factory']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form text-center">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->

            <div class="margin-bottom-20">
                @if(!empty($userAccessArr[50][5]))
                {{ Form::hidden('allItem', null, array('id'=>'allItem'))}}
                <button class="btn btn-info btn-large allRequest" type="button" data-placement="top" data-rel="tooltip" name="request" data-original-title="Request All" value="request" data-target="#multipleDemandDetails" data-toggle="modal" disabled>
                    <i class="fa fa-eye text-white"></i>  @lang('label.VIEW_DETAILS')
                </button>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th class="vcenter text-center">
                                @if((count($targetArr)>=1))
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
                            <th class=" vcenter ">@lang('label.SHIFT')</th>
                            <th class=" vcenter ">@lang('label.FACTORY')</th>
                            <th class=" vcenter ">@lang('label.MACHINE_NO')</th>
                            <th class=" vcenter ">@lang('label.DATE')</th>
                            <th class=" vcenter ">@lang('label.STYLE')</th>
                            <th class=" vcenter ">@lang('label.BUYER')</th>
                            <th class=" vcenter ">@lang('label.TYPE_OF_GARMENTS')</th>
                            <th class="vcenter text-center">@lang('label.DELIVERED_BY')</th>
                            <th class="text-center vcenter">@lang('label.DELIVERED_AT')</th>
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
                        <?php
                        $checked = !empty(Session::get('multiple_demand_id.' . $target->id)) ? 'checked' : '';
                        ?>
                        <tr>
                            <td class="vcenter">
                                <div class="md-checkbox">
                                    {!! Form::checkbox('demand_id.'.$target->id, $target->id, $checked, ['id' => $target->id,'data-id'=>$target->id ,'class'=> 'md-check demand-details-check' ]) !!}
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
                            <td class="vcenter">{{ $target->shift }}</td>
                            <td class="vcenter">{{ $target->factory }}</td>
                            <td class="vcenter">{{ $target->machine_no }}</td>
                            <td class="vcenter">{{ $target->date }}</td>
                            <td class="vcenter">{{ $target->style }}</td>
                            <td class="vcenter">{{ $target->buyer }}</td>
                            <td class="vcenter">{{ $target->garments_type }}</td>
                            @if (!empty($target->delivered_by))
                            <td class="text-center vcenter">{!! $userFirstNameArr[$target->delivered_by].' '.$userLastNameArr[$target->delivered_by] !!}</td>
                            @else
                            <td class="text-center vcenter">---</td>
                            @endif

                            @if (!empty($target->delivered_at))
                            <td class="text-center vcenter">{{Helper::printDateFormat($target->delivered_at) }}</td>
                            @else
                            <td class="text-center vcenter">---</td>
                            @endif
                            <td class="text-center vcenter">
                                @if($request->chemical == 'chemical')
                                @if(!empty($userAccessArr[49][7]))
                                <button type="button" class="btn btn-sm purple-plum tooltips deliver" id="deliver-{!! $target->id !!}" data-demand-id="{!! $target->id !!}" title="Deliver">
                                    @lang('label.DELIVERED')
                                </button>
                                @endif
                                @else
                                @if(!empty($userAccessArr[50][6]))
                                <a class="btn btn-xs btn-info tooltips vcenter" title="Print" href="{{ URL::to('deliveredChemicalsList/getDetails/' . $target->id.'?view=print&' .Helper::queryPageStr($qpArr)) }}" target="_blank">
                                    <i class="fa fa-print text-white"></i>
                                </a>
                                @endif
                                @endif
                                @if(!empty($userAccessArr[50][5]))
                                <button type="button" class="btn btn-xs yellow tooltips vcenter details-btn" title="View Details" id="detailsBtn-{{$target->id}}" data-target="#demandDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="13">@lang('label.NO_DELIVERED_CHEMICAL_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div>
                    @if(!empty($userAccessArr[50][5]))
                    {{ Form::hidden('allItem', null, array('id'=>'allItem'))}}
                    <button class="btn btn-info btn-large allRequest" type="button" data-placement="top" data-target="#multipleDemandDetails" data-toggle="modal" data-rel="tooltip" name="request" data-original-title="Request All" value="request" disabled>
                        <i class="fa fa-eye text-white"></i>  @lang('label.VIEW_DETAILS')
                    </button>
                    @endif
                </div>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>
<div class="modal container fade" id="demandDetails" tabindex="-1">
    <div id="showDemandDetails">
        <!--    ajax will be load here-->
    </div>

</div>
<!-- START:: Show Multiple Demand Details !-->
<div class="modal container fade" id="multipleDemandDetails" tabindex="-1" aria-hidden="true">
    <div id="showMultiDemandDetails">
        <!--    ajax will be load here-->
    </div>
</div>
<!-- END:: Show Multiple Demand Details !-->

<script type="text/javascript">

    $(document).on("click", ".deliver", function () {

        var demandId = $(this).data('demand-id');
        //alert(demandId);return ;

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
        swal({
            title: "Are you want to Save?",
            text: "You can not undo this action!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Deliver It",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                // alert('1');return;
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
                        demand_id: demandId,
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        //alert('1');return false;
                        toastr.success(res.data, 'Chemical Delivered Successfully', options);
                        // similar behavior as an HTTP redirect
                        window.location = '{!! URL::to("deliverChemicals/{chemical?}") !!}';
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        //alert(jqXhr);return ;
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
                        App.unblockUI();
                    }
                });
            }
        });
    });
    //Get Single Demand Letter Details
    $(document).on('click', '.details-btn', function () {

        var demandId = $(this).data("id");
        $.ajax({
            url: "{{URL::to('deliveredChemicalsList/getDetails')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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

    $('#checkAll').change(function () {  //'check all' change
        $('.demand-details-check').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status
        if ($('#' + this.id).is(":checked")) {
            var newStr = ',';
            $(".demand-details-check").each(function (index) {
                $('#' + this.id).prop('checked', true);
                newStr = newStr.concat($('#' + this.id).attr('data-id') + ',');
            });
            $('#allItem').val(newStr);
            $('.allRequest').attr('disabled', false);
        } else {
            $(".demand-details-check").each(function (index) {
                $('#' + this.id).prop('checked', false);
            });
            $('#allItem').val('');
            $('.allRequest').attr('disabled', true);
        }
    });
    $(document).on("click", '.demand-details-check', function (e) {
        var deleteAllStr = $('#allItem').val();
        var dataId = $('#' + this.id).attr('data-id');
        if ($('#' + this.id).is(":checked")) {
            $('#allItem').val('');
            if (deleteAllStr == '') {
                var newStr = ',' + dataId + ',';
            } else {
                var newStr = deleteAllStr + dataId + ',';
            }

        } else {

            var commaCount = deleteAllStr.split(",").length - 1;
            if (commaCount == 2) {
                var newStr = deleteAllStr.replace(',' + dataId + ',', '');
            } else {
                var newStr = deleteAllStr.replace(dataId + ',', '');
            }
        }

        if (newStr == '') {
            $('.allRequest').attr('disabled', true);
        }

        $('#allItem').val(newStr);
    });
    //Demand Check for Different Batch Card
    $('.demand-details-check').change(function () {
        var demandVal = 0;
        var demandId = $('#' + this.id).val();
        var deleteAllStr = $('#allItem').val();
        var dataId = $('#' + this.id).attr('data-id');
        var elemID = this.id;
        if ($('#' + this.id).is(':checked')) {
            demandVal = 1;
            $('.allRequest').attr('disabled', false);
        }
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            url: '{{URL::to("deliveredChemicalsList/makeDemandId/")}}',
            type: 'post',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                demand_id: demandId,
                demand_data: demandVal,
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
                    } else {
                        var newStr = deleteAllStr.replace(dataId + ',', '');
                    }
                    if (newStr == '') {
                        $('.allRequest').attr('disabled', true);
                    }
                    $('#allItem').val(newStr);
                } else {
                    toastr.error('Error', 'Something went wrong', options);
                }
                App.unblockUI();
            }

        });
    });
    $(document).on("click", ".allRequest", function (e) {

        e.preventDefault();
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        var item = $('#allItem').val();
        $.ajax({
            url: "{{URL::to('deliveredChemicalsList/viewMultipleDetails')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                all_item: item,
            },
            beforeSend: function () {
                $('#showMultiDemandDetails').html('');
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showMultiDemandDetails').html(res.html);
                App.unblockUI();
            }, error: function (jqXhr, ajaxOptions, thrownError) {
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
            }
        });
    });
    /* Load Token No */

    $('#search').keyup(function (e) {
        e.preventDefault();
        var maxlength = 3;
        var value = $(this).val();
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
                url: "{{URL::to('deliveredChemicalsList/loadTokenforDelivered')}}",
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
            debug: false, positionClass: "toast-bottom-right",
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
                url: "{{URL::to('deliveredChemicalsList/loadBatchTokenForDeliveredDemand')}}",
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
    $(document).mouseup(function (e)
    {
        var container = $("#searchResult"); // YOUR CONTAINER SELECTOR
        if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.hide();
        }
    });
    $(document).mouseup(function (e)
    {
        var tokenContainer = $("#tokenResult"); // YOUR CONTAINER SELECTOR

        if (!tokenContainer.is(e.target) // if the target of the click isn't the container...
                && tokenContainer.has(e.target).length === 0) // ... nor a descendant of the container
        {
            tokenContainer.hide();
        }
    });
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

    }

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

    }

</script>

@stop