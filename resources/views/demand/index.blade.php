@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>
                @lang('label.LIST_OF_DEMAND_LETTERS')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'demand/filter','class' => 'form-horizontal')) !!}
            <div class="row">
                <div class="col-md-4" >
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Token No', 'placeholder' => 'Token No' ,'autocomplete'=>'off','id' => 'search']) !!}
                            <div id="tokenNo"></div>
                            <span id="character-count"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="control-label col-md-4">@lang('label.DATE') </label>
                    <div class="input-group date datepicker col-md-8" style="z-index:0!important;" data-date-end-date="+0d">
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
                        <label class="control-label col-md-4" for="batch">@lang('label.BATCH_CARD')</label>
                        <div class="col-md-8">
                            {!! Form::text('batch_card',Request::get('batch_card_ref'), ['class' => 'form-control tooltips', 'title' => 'Batch No', 'placeholder' => 'Batch Card No' ,'autocomplete'=>'off','id' => 'batchCard']) !!}
                            {!! Form::hidden('batch_card_id',Request::get('batch_card_id'), ['class' => 'form-control tooltips', 'title' => 'Batch No', 'placeholder' => 'Batch Card No' ,'autocomplete'=>'off','id' => 'batchCardId']) !!}
                            <div id="batchCardNo"></div>
                            <span id="batchcard-character-count"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="styleId">@lang('label.STYLE') </label>
                        <div class="col-md-8">
                            {!! Form::select('style_id', $styleArr, Request::get('style_id'), ['class' => 'form-control js-source-states', 'id' => 'styleId']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status">@lang('label.STATUS') </label>
                        <div class="col-md-8">
                            {!! Form::select('status', $filterStatusArr, Request::get('status'), ['class' => 'form-control js-source-states', 'id' => 'status']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
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
            {!! Form::open(array('group' => 'form', 'url' => 'demand/printDemandList/?view=print','class' => 'form-horizontal','target'=> "_blank")) !!}
            <button class="btn btn-info tooltips vcenter margin-bottom-20 print-button" type="submit" title="Print" disabled>
                <i class="fa fa-print text-white"></i> @lang('label.PRINT_SELECTED_DEMAND_LETTER')
            </button>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="center">
                            <th class="vcenter text-center check-box2">
                                @if((!empty($qpArr)) && (!empty(Request::get('batch_card_id'))))
                                @if((count($targetArr)>=1))
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
                            <th class="vcenter text-center">@lang('label.SL_NO')</th>
                            <th class="vcenter text-center">@lang('label.TOKEN_NO')</th>
                            <th class="vcenter">@lang('label.BATCH_CARD_NO')</th>
                            <th class="vcenter">@lang('label.MACHINE_NO')</th>
                            <th class="vcenter">@lang('label.DATE')</th>
                            <th class="vcenter">@lang('label.STYLE')</th>
                            <th class="vcenter">@lang('label.BUYER')</th>
                            <th class="vcenter">@lang('label.GARMENTS_TYPE')</th>
                            <th class="vcenter text-center">@lang('label.DELIVERED_BY')</th>
                            <th class="text-center vcenter">@lang('label.DELIVERED_AT')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
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
                        $checked = !empty(Session::get('demand_id.' . $target->id)) ? 'checked' : '';
                        $rowColor = ($target->status == 1) ? 'info' : 'green';
                        ?>
                        <tr class="{{ $rowColor }}">
                            <td class="vcenter check-box2">
                                
                                @if($target->status != '1' )
                                <div class="md-checkbox has-success">
                                    {!! Form::checkbox('demand_id['.$target->id.']', $target->id, $checked, ['id' => 'demandNo-'.$target->id, 'data-id'=>$target->id, 'class'=> 'md-check demand']) !!}
                                    <label for="demandNo-{!! $target->id !!}">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                                @elseif($target->status == '1' )
                                {!! Form::checkbox('demand_id['.$target->id.']', $target->id, '1', ['id' => 'demandNo-'.$target->id, 'data-id'=>$target->id, 'class'=> 'md-check delivered-demand','style' => 'display:none;']) !!}
                                 @elseif($target->status == '1' && $target->status != '1')
                                {!! Form::checkbox('demand_id['.$target->id.']', $target->id, $checked, ['id' => 'demandNo-'.$target->id, 'data-id'=>$target->id, 'class'=> 'md-check demand','style' => 'display:none;']) !!}
                                
                                @endif
                            </td>
                            <td class="vcenter text-center">{{ ++$sl }}</td>
                            <td class="vcenter text-center">
                                <span class="label label-danger">{{ $target->token_no }}</span>
                            </td>
                            <td class="vcenter">{{ $target->batch_card }}</td>
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

                            @if($target->status == 1 )
                            <td class="vcenter text-center">
                                <span class="label label-sm label-success">@lang('label.DELIVERED')</span>
                            </td>
                            @else
                            <td class="vcenter text-center">
                                <span class="label label-sm label-warning">@lang('label.DEMAND_GENERATED')</span>
                            </td>
                            @endif
                            <td class="vcenter text-center">
                                @if(!empty($userAccessArr[48][6]))
                                @if($target->status != '1' )
                                <a class="btn btn-xs btn-info tooltips vcenter" title="Print" href="{{ URL::to('demand/getDetails/' . $target->id.'?view=print&' .Helper::queryPageStr($qpArr)) }}" target="_blank">
                                    <i class="fa fa-print text-white"></i>
                                </a>
                                @endif
                                @endif
                                @if(!empty($userAccessArr[48][5]))
                                <button type="button" class="btn btn-xs yellow tooltips vcenter details-btn" title="View Details" id="detailsBtn-{{$target->id}}" data-target="#demandDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="13">@lang('label.NO_DEMAND_PAPER_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <button class="btn btn-info tooltips vcenter print-button" type="submit" title="Print" disabled>
                    <i class="fa fa-print text-white"></i> @lang('label.PRINT_SELECTED_DEMAND_LETTER')
                </button>
                {!! Form::close() !!}
            </div>
            @include('layouts.paginator')
            @endif
        </div>	
    </div>
</div>
<div class="modal container fade" id="demandDetails" role="basic">
    <div id="showDemandDetails">
        <!--    ajax will be load here-->
    </div>
</div>
<script type="text/javascript">



    $(document).on("click", ".deliver", function () {
        var demandId = $(this).data('demand-id');
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
            title: "Are you sure, you want to Save?",
            text: "You can not undo this action!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Deliver It",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ URL::to('demand/deliver')}}",
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
                        //toastr.success(res.data, 'Chemical Delivered Successfully', options);
                        // similar behavior as an HTTP redirect
//                         window.location = '{!! URL::to("deliveredChemicalsList") !!}';
//                        App.unblockUI();
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
                        App.unblockUI();
                    }
                });
            }
        });
    });

    // View of Demand Letter Details
    $(document).on('click', '.details-btn', function () {
        var demandId = $(this).data("id");
        $.ajax({
            url: "{{URL::to('demand/getDetails')}}",
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
        $('.demand').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status

        if ($('#' + this.id).is(":checked")) {
            $('.print-button').attr('disabled', false);//enable print button if check box checked

        } else {
            $('.print-button').attr('disabled', true);//disable print button if check box checked
        }

    });

    $('.demand').change(function () {

        var demandVal = 0;
        var demandId = $('#' + this.id).val();
        var elemID = this.id;
        if ($('#' + this.id).is(':checked')) {
            demandVal = 1;
            $('.print-button').attr('disabled', false);//enable print button if check box checked
        } else {
            $('.print-button').attr('disabled', true);//disable print button if check box checked
        }
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        $.ajax({
            url: '{{URL::to("demand/getDemandId/")}}',
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
                } else {
                    toastr.error('Error', 'Something went wrong', options);
                }
                App.unblockUI();
            }

        });
    });

    /* Load Token No */
    $(document).ready(function () {
        var demandCount = $('input.delivered-demand:checkbox:checked').length;
        var demandGenerateCount = $('input.demand:checkbox:checked').length;
        //console.log(demandCount);
        //console.log(demandGenerateCount)
        <?php if (!empty(Request::get('batch_card_id'))) { ?>
                if (demandCount > 0 && demandGenerateCount == 0) {
                    $('.check-box2').hide();
                     $('.print-button').hide();
                }
        <?php } ?>
            
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
                    url: "{{URL::to('demand/loadToken')}}",
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
                    url: "{{URL::to('demand/loadBatchToken')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'search_keyword': value
                    },
                    beforeSend: function () {
                        //App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        //we need to check if the value is the same
                        //Receiving the result of search here
                        $('#batchCardNo').html(res.html);
                        $("#searchResult li").bind("click", function () {
                            setBatchText(this);
                        });
                        $('span#batchcard-character-count').text('');
                        //App.unblockUI();
                    }
                });
            }
        });

        $(document).mouseup(function (e)
        {
            var batchContainer = $("#searchResult"); // YOUR CONTAINER SELECTOR

            if (!batchContainer.is(e.target) // if the target of the click isn't the container...
                    && batchContainer.has(e.target).length === 0) // ... nor a descendant of the container
            {
                batchContainer.hide();
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