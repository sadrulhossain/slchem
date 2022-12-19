@extends('layouts.default.master') @section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.RECIPE_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[44][2]))
                <a class="btn btn-default btn-sm create-new tooltips recipe-btn" title="Click here to create new recipe" href="{{ URL::to('recipe/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_RECIPE')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'recipe/filter','class' => 'form-horizontal')) !!} {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.REFERENCE_NO')</label>
                        <div class="col-md-8">
                            {!! Form::text('search', Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name', 'list'=>'search', 'autocomplete'=>'off']) !!}
                            <datalist id="search">
                                @if(!empty($recipeArr))
                                @foreach($recipeArr as $recipe)
                                <option value="{{$recipe->reference_no}}"></option>
                                @endforeach
                                @endif
                            </datalist>
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
                        <label class="control-label col-md-4" for="factory">@lang('label.FACTORY_LIST')</label>
                        <div class="col-md-8">
                            {!! Form::select('factory', $factoryArr, Request::get('factory'), ['class' => 'form-control js-source-states','id'=>'factory']) !!}
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="seasonId">@lang('label.SEASON'):<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('season_id', $seasonArr, Request::get('season_id'), ['class' => 'form-control js-source-states','id' => 'seasonId']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="colorId">@lang('label.COLOR'):<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            {!! Form::select('color_id', $colorArr, Request::get('color_id'), ['class' => 'form-control js-source-states','id' => 'colorId']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="approvalstatusId">@lang('label.APPROVAL_STATUS') </label>
                        <div class="col-md-8">
                            {!! Form::select('fil_status', $filterApprovalStatusArr, Request::get('fil_status'), ['class' => 'form-control js-source-states', 'id' => 'approvalstatusId']) !!}
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="statusId">@lang('label.STATUS') </label>
                        <div class="col-md-8">
                            {!! Form::select('fil_active_status', $filterStatusArr, Request::get('fil_active_status'), ['class' => 'form-control js-source-states', 'id' => 'statusId']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="buyer">@lang('label.BUYER')</label>
                        <div class="col-md-8">
                            {!! Form::select('buyer', $buyerArr, Request::get('buyer'), ['class' => 'form-control js-source-states','id'=>'buyer']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="shadeId">@lang('label.SHADE')</label>
                        <div class="col-md-8">
                            {!! Form::select('shade_id',$shadeArr, Request::get('shade_id'), ['class' => 'form-control js-source-states','id'=>'shadeId']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="styleId">@lang('label.STYLE')</label>
                        <div class="col-md-8">
                            {!! Form::select('style_id',$styleArr, Request::get('style_id'), ['class' => 'form-control js-source-states','id'=>'styleId']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="machineModel">@lang('label.WASHING_MACHINE_TYPE')</label>
                        <div class="col-md-8">
                            {!! Form::select('washing_machine_type', $machineModelArr, Request::get('washing_machine_type'), ['class' => 'form-control js-source-states','id'=>'machineModel']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->

            <div style="max-height: 600px;">
                <table class="table table-bordered table-hover table-responsive" id="dataTable">
                    <thead>
                        <tr class="info">
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.REFERENCE_NO')</th>
                            <th class="vcenter">@lang('label.STYLE')</th>
                            <th class="vcenter text-center">@lang('label.DATE')</th>
                            <th class="vcenter text-center">@lang('label.SEASON')</th>
                            <th class="vcenter text-center">@lang('label.COLOR')</th>
                            <th class="vcenter text-center">@lang('label.SHADE')</th>
                            <th class="vcenter">@lang('label.WASHING_MACHINE_TYPE')</th>
                            <th class="vcenter text-center">@lang('label.APPROVAL_STATUS')</th>
                            <th class="vcenter text-center">@lang('label.STATUS')</th>
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
                            <td class="vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $target->reference_no }}</td>
                            <td class="vcenter" width="60">{{ $target->style }}</td>
                            <td class="vcenter text-center">{{ Helper::dateFormat($target->date) }}</td>
                            <td class="vcenter text-center">{{ $target->season }}</td>
                            <td class="vcenter text-center">{{ $target->color }}</td>
                            <td class="vcenter text-center">{{ $target->shade }}</td>
                            <td class="vcenter">{{ $target->machine_model }}</td>
                            <td class="text-center vcenter">
                                <span class="label label-sm label-{{ $approvalStatusArr[$target->approval_status]['label']}}">{!! $approvalStatusArr[$target->approval_status]['status'] !!}</span>
                            </td>
                            <td class="text-center vcenter">
                                <span class="label label-sm label-{{ $statusArr[$target->status]['label']}}">{!! $statusArr[$target->status]['status'] !!}</span>
                            </td>
                            <td class="text-center vcenter">
                                <div width="100%">
                                    @if(($target->status== '2') && ($target->approval_status== '1'))
                                    @if(!empty($userAccessArr[44][12]))
                                    <a class="btn btn-xs btn-info tooltips active-recipe" title="@lang('label.ACTIVE')" id="approve-{{$target->id}}" href="{{ URL::to('recipe/active/'. $target->id) }}">
                                        <i class="fa fa-play"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[44][11]))
                                    <a class="btn btn-xs green tooltips finalize-recipe" title="@lang('label.FINALIZE')" id="finalize-{{$target->id}}" data-id="{!! $target->id !!}">
                                        <i class="fa fa-check"></i>
                                    </a>
                                    @endif
                                    @endif
                                    @if(($target->status== '1') && ($target->approval_status== '1'))
                                    @if(!empty($userAccessArr[44][13]))
                                    <a class="btn btn-xs btn-danger tooltips deactive-recipe" title="@lang('label.DEACTIVE')" id="deny-{{$target->id}}" href="{{ URL::to('recipe/deactive/' . $target->id) }}">
                                        <i class="fa fa-ban"></i>
                                    </a>
                                    @endif
                                    @endif 
                                    @if(($target->status== '1') && ($target->approval_status== '2'))
                                    @if(!empty($userAccessArr[44][13]))
                                    <button class="btn btn-xs btn-danger tooltips make-deactivate" type="button" title="@lang('label.DEACTIVE')" data-id="{!! $target->id !!}" data-target="#deactiveCause" data-toggle="modal">
                                        <i class="fa fa-ban"></i>
                                    </button> 
                                    @endif
                                    @endif 
                                    @if(($target->status== '2') && ($target->approval_status== '2'))
                                    @if(!empty($userAccessArr[44][12]))
                                    <button class="btn btn-xs btn-info tooltips make-activate" type="button" title="@lang('label.ACTIVE')" data-id="{!! $target->id !!}" data-target="#activeCause" data-toggle="modal">
                                        <i class="fa fa-play"></i>
                                    </button> 
                                    @endif
                                    @endif 
                                    @if($target->approval_status == '1') 
                                    @if(!empty($userAccessArr[44][3]))
                                    <a class="btn btn-xs btn-primary tooltips recipe-btn" title="Edit" href="{{ URL::to('recipe/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[44][4]))
                                    {{ Form::open(array('url' => 'recipe/' . $target->id.'/'.Helper::queryPageStr($qpArr),'class' => 'inline')) }} 
                                    {{ Form::hidden('_method','DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button> 
                                    {{ Form::close() }} 
                                    @endif
                                    @endif
                                    @if(!empty($userAccessArr[44][5]))
                                    <button type="button" class="btn yellow btn-xs tooltips details-btn" title="Click here to view recipe details" id="detailsBtn-{{$target->id}}" data-target="#recipeDetails" data-toggle="modal" data-id="{{$target->id}}">
                                        <i class="fa fa-th-list"></i>
                                    </button>
                                    @endif
                                    @if(!empty($userAccessArr[44][17]))
                                    <a class="btn btn-xs btn-warning tooltips vcenter" title="Click here to Download PDF" href="{{ URL::to('recipe/getDetails/'.$target->id.'?view=pdf') }}">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                    @endif
                                    @if(!empty($target->act_deact_cause))
                                    @if(!empty($userAccessArr[44][12]) || !empty($userAccessArr[44][13]))
                                    <button class="btn btn-xs btn-primary tooltips history" type="button" title="@lang('label.DEACTIVATION_ACTIVATION_HISTORY')" data-id="{!! $target->id !!}" data-target="#historyDetails" data-toggle="modal">
                                        <i class="fa fa-info"></i>
                                    </button> 
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @endforeach 
                        @else
                        <tr>
                            <td colspan="20" class="vcenter">@lang('label.NO_RECIPE_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>
    </div>
</div>
<!-- details modal -->

<div class="modal container fade" id="recipeDetails" tabindex="-1">
    <div id="showrecipeDetails">
        <!--    ajax will be load here-->
    </div>

</div>

<!-- Modal Of More Information -->
<div class="modal fade" id="deactiveCause" tabindex="-1">
    <div id="showDeactivate">
        <!--    ajax will be load here-->
    </div>
</div>
<!-- End of Modal -->

<!-- Modal Of More Information -->
<div class="modal fade" id="activeCause" tabindex="-1">
    <div id="showActivate">
        <!--    ajax will be load here-->
    </div>
</div>
<!-- End of Modal -->

<!-- Modal Of More Information -->
<div id="historyDetails" class="modal container fade" tabindex="-1">
    <div id="showHistory">

    </div>
</div>
<!-- End of Modal -->


<script type="text/javascript">
    $(function () {
        $("#dataTable").tableHeadFixer();

        // Confirmation for Draft Recipe Activate
        $('.active-recipe').on('click', function (e) {
            e.preventDefault();
            var url = $('#' + this.id).attr('href');
            swal({
                title: "@lang('label.RECIPE_ACTIVATION_WARNING')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('label.YES_ACTIVATE')",
                closeOnConfirm: false,
            }, function (isConfirm) {
                if (isConfirm) {
                    window.location = url;
                }

            });
        });


        // Confirmation for Draft Recipe Deactivate
        $('.deactive-recipe').on('click', function (e) {
            e.preventDefault();
            var url = $('#' + this.id).attr('href');
            swal({
                title: "@lang('label.RECIPE_DEACTIVATION_WARNING')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('label.YES_DEACTIVATE')",
                closeOnConfirm: false,
            }, function (isConfirm) {
                if (isConfirm) {
                    window.location = url;
                }

            });
        });


        //View Recipe Details
        $(document).on('click', '.details-btn', function () {

            var recipeId = $(this).data("id");
            $.ajax({
                url: "{{ URL::to('recipe/getDetails') }}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    recipe_id: recipeId
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true
                    });
                },
                success: function (res) {
                    $('#showrecipeDetails').html(res.html);
                    App.unblockUI();
                }
            });
        });

    });

    //Do Finalized and Active Recipe Deactivate
    $(document).on('click', '.make-deactivate', function () {
        var recipeId = $(this).data("id");
        $.ajax({
            url: "{{URL::to('recipe/showDeactivateDiv')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                recipe_id: recipeId
            },
            beforeSend: function () {
                App.blockUI({
                    boxed: true
                });
            },
            success: function (res) {
                $('#showDeactivate').html(res.html);
                App.unblockUI();
            }
        });
    });

    //Do Finalized and Deactivate Recipe Activate
    $(document).on('click', '.make-activate', function () {

        var recipeId = $(this).data("id");
        $.ajax({
            url: "{{URL::to('recipe/showActivateDiv')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                recipe_id: recipeId
            },
            beforeSend: function () {
                App.blockUI({
                    boxed: true
                });
            },
            success: function (res) {
                $('#showActivate').html(res.html);
                App.unblockUI();
            }
        });
    });


    //Manage Recipe Deactivation/ Activation Process History
    $(document).on('click', '.history', function () {
        var recipeId = $(this).data("id");
        $.ajax({
            url: "{{URL::to('recipe/showHistoryDiv')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                recipe_id: recipeId
            },
            beforeSend: function () {
                App.blockUI({
                    boxed: true
                });
            },
            success: function (res) {
                $('#showHistory').html(res.html);
                App.unblockUI();
            }
        });
    });


    // Confirmation for Draft Recipe make Finalize
    $(document).on('click', '.finalize-recipe', function (e) {
        e.preventDefault();
        var recipeId = $(this).data("id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        swal({
            title: "@lang('label.RECIPE_FINALIZE_WARNING')",
            type: "warning",
            text: "@lang('label.FURTHER_MODIFY_TITLE')",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('label.YES_FINALIZE')",
            closeOnConfirm: true,
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{URL::to('recipe/finalize')}}",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        recipe_id: recipeId
                    },
                    beforeSend: function () {
                        App.blockUI({
                            boxed: true
                        });
                    },
                    success: function (res) {
                        toastr.success(res.message, res.heading, options);
                        location.reload();
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
                        App.unblockUI();
                    }
                });
            }

        });
    });
    $(document).on("click", ".recipe-btn", function () {
        $("#addFullMenuClass").addClass("page-sidebar-closed");
        $("#addsidebarFullMenu").addClass("page-sidebar-menu-closed");
    });
</script>
@stop