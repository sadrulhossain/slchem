@extends('layouts.default.master') @section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.FINALIZED_RECIPE_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <div class="row">
                {!! Form::open(array('group' => 'form', 'url' => 'finalizedRecipe/filter','class' => 'form-horizontal')) !!} {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}

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
                        <label class="control-label col-md-4" for="buyer">@lang('label.BUYER')</label>
                        <div class="col-md-8">
                            {!! Form::select('buyer', $buyerArr, Request::get('buyer'), ['class' => 'form-control js-source-states','id'=>'buyer']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="styleId">@lang('label.STYLE')</label>
                        <div class="col-md-8">
                            {!! Form::select('style', $styleArr, Request::get('style_id'), ['class' => 'form-control js-source-states','id'=>'styleId']) !!}
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

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="shadeId">@lang('label.SHADE')</label>
                        <div class="col-md-8">
                            {!! Form::select('shade_id',$shadeArr, Request::get('shade_id'), ['class' => 'form-control js-source-states','id'=>'shadeId']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- End Filter -->

            <div class="table-responsive" style="overflow: scroll; max-height: 600px;">
                <table class="table table-bordered table-hover table-wrapper-scroll-y" id="dataTable">
                    <thead>
                        <tr class="info">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.REFERENCE_NO')</th>
                            <th class="vcenter" width="60">@lang('label.STYLE')</th>
                            <th class="vcenter">@lang('label.DATE')</th>
                            <th class="vcenter">@lang('label.SEASON')</th>
                            <th class="vcenter">@lang('label.COLOR')</th>
                            <th class="vcenter">@lang('label.SHADE')</th>
                            <th class="vcenter">@lang('label.WASHING_MACHINE_TYPE')</th>
                            <th class="vcenter">@lang('label.STATUS')</th>
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
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $target->reference_no }}</td>
                            <td class="vcenter" width="60">{{ $target->style }}</td>
                            <td class="vcenter">{{ Helper::dateFormat($target->date) }}</td>
                            <td class="vcenter">{{ $target->season }}</td>
                            <td class="vcenter">{{ $target->color }}</td>
                            <td class="vcenter text-center">{{ $target->shade }}</td>
                            <td class="vcenter">{{ $target->machine_model }}</td>
                            <td class="text-center vcenter">
                                <span class="label label-sm label-{{ $statusArr[$target->status]['label']}}">{!! $statusArr[$target->status]['status'] !!}</span>
                            </td>
                            <td class="text-center vcenter">
                                
                                <div>
                                    @if(!empty($userAccessArr[45][17]))
                                    <a class="btn btn-xs btn-warning vcenter" href="{{ URL::to('finalizedRecipe/getDetails/'.$target->id.'?view=pdf') }}">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[45][5]))
                                    <button type="button" class="btn yellow btn-xs tooltips details-btn" title="Click here to view recipe details" id="detailsBtn-{{$target->id}}" data-target="#recipeDetails" data-toggle="modal" data-id="{{$target->id}}">
                                        <i class="fa fa-th-list"></i>
                                    </button>
                                    @endif
                                </div>
                                @if(!empty($userAccessArr[45][12]))
                                @if($target->status== '2')
                                <button class="btn btn-xs btn-info tooltips make-activate" type="button" title="@lang('label.ACTIVE')" data-id="{!! $target->id !!}" data-target="#activeCause" data-toggle="modal">
                                    <i class="fa fa-play"></i>
                                </button> 
                                @endif 
                                @endif
                                @if(!empty($userAccessArr[45][13]))
                                @if($target->status== '1')
                                <button class="btn btn-xs btn-danger tooltips make-deactivate" type="button" title="@lang('label.DEACTIVE')" data-id="{!! $target->id !!}" data-target="#deactiveCause" data-toggle="modal">
                                    <i class="fa fa-ban"></i>
                                </button> 
                                @endif 
                                @endif
                                @if(!empty($userAccessArr[45][12]) || !empty($userAccessArr[45][13]))
                                @if(!empty($target->act_deact_cause))
                                <button class="btn btn-xs btn-primary tooltips history" type="button" title="@lang('label.DEACTIVATION_ACTIVATION_HISTORY')" data-id="{!! $target->id !!}" data-target="#historyDetails" data-toggle="modal">
                                    <i class="fa fa-info"></i>
                                </button> 
                                @endif
                                @endif
                            </td>

                        </tr>
                        @endforeach 
                        @else
                        <tr>
                            <td colspan="13" class="vcenter">@lang('label.NO_FINALIZED_RECIPE_FOUND')</td>
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
    <div id="showFinalizedDeactivate">
        <!--    ajax will be load here-->
    </div>
</div>
<!-- End of Modal -->

<!-- Modal Of More Information -->
<div class="modal fade" id="activeCause" tabindex="-1">
    <div id="showFinalizedActivate">
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
        $("#dataTable").tableHeadFixer({
            "left": 2
        });

        // View Recipe Details
        $(document).on('click', '.details-btn', function () {

            var recipeId = $(this).data("id");
            $.ajax({
                url: "{{URL::to('finalizedRecipe/getDetails')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    recipe_id: recipeId
                },
                beforeSend: function () {
                    // App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showrecipeDetails').html(res.html);
                    // App.unblockUI();
                },
            });
        });
    });

    //Do Finalized and Active Recipe Deactivate
    $(document).on('click', '.make-deactivate', function () {
        var recipeId = $(this).data("id");
        $.ajax({
            url: "{{URL::to('finalizedRecipe/showDeactivateDiv')}}",
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
                $('#showFinalizedDeactivate').html(res.html);
                App.unblockUI();
            }
        });
    });


    //Do Finalized and Deactivate Recipe Activate
    $(document).on('click', '.make-activate', function () {

        var recipeId = $(this).data("id");
        $.ajax({
            url: "{{URL::to('finalizedRecipe/showActivateDiv')}}",
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
                $('#showFinalizedActivate').html(res.html);
                App.unblockUI();
            }
        });
    });


    //Manage Recipe Deactivation/ Activation Process History
    $(document).on('click', '.history', function () {
        var recipeId = $(this).data("id");
        $.ajax({
            url: "{{URL::to('finalizedRecipe/showHistoryDiv')}}",
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
</script>
@stop