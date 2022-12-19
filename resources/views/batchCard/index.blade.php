@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.BATCH_CARD_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[46][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('batchCard/create'.Helper::queryPageStr($qpArr)) }}">
                    @lang('label.GENERATE_NEW_BATCH_CARD')<i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'batchCard/filter','class' => 'form-horizontal')) !!}
                <div class="col-md-12">
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
                            <label class="control-label col-md-4" for="search">@lang('label.REFERENCE_NO')</label>
                            <div class="col-md-8">
                                {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Reference No', 'placeholder' => 'Reference No', 'list'=>'search', 'autocomplete'=>'off','id' => 'search']) !!} 
                                <div id="tokenNo"></div>
                                <span id="character-count"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="recipe">@lang('label.RECIPE_NO')</label>
                            <div class="col-md-8">
                                {!! Form::select('recipe',  $recipeArr, Request::get('recipe'), ['class' => 'form-control js-source-states','id'=>'recipe']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="styleId">@lang('label.STYLE')</label>
                            <div class="col-md-8">
                                {!! Form::select('style',  $styleArr, Request::get('style_id'), ['class' => 'form-control js-source-states','id'=>'styleId']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="machine">@lang('label.WASH_MC_NO')</label>
                            <div class="col-md-8">
                                {!! Form::select('machine',  $machineArr, Request::get('machine'), ['class' => 'form-control js-source-states','id'=>'machine']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="shiftId">@lang('label.SHIFT')</label>
                            <div class="col-md-8">
                                {!! Form::select('shift', $shiftArr, Request::get('shift'),['class' => 'form-control js-source-states','id'=>'shiftId']) !!} 

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="operatorName">@lang('label.OPERATOR_NAME')</label>
                            <div class="col-md-8">
                                {!! Form::text('operator_name',  Request::get('operator_name'), ['class' => 'form-control tooltips', 'title' => 'Operator Name', 'placeholder' => 'Operator Name', 'list'=>'operator', 'autocomplete'=>'off']) !!} 
                                <datalist id="operator">
                                    @if(!empty($opreratorArr))
                                    @foreach($opreratorArr as $oprerator)
                                    <option value="{{$oprerator->operator_name}}"></option>
                                    @endforeach
                                    @endif
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="washTypeId">@lang('label.WASH_TYPE')</label>
                            <div class="col-md-8">
                                {!! Form::select('wash_type_id', $washTypeArr, Request::get('wash_type_id'),['class' => 'form-control js-source-states','id'=>'washTypeId']) !!} 

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="season">@lang('label.SEASON')</label>
                            <div class="col-md-8">
                                {!! Form::select('season_id', $seasonArr, Request::get('season_id'), ['class' => 'form-control js-source-states','id'=>'seasonId']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="color">@lang('label.COLOR')</label>
                            <div class="col-md-8">
                                {!! Form::select('color_id', $colorArr, Request::get('color_id'), ['class' => 'form-control js-source-states','id'=>'colorId']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="color">@lang('label.FACTORY')</label>
                            <div class="col-md-8">
                                {!! Form::select('factory', $factoryArr, Request::get('factory'), ['class' => 'form-control js-source-states','id'=>'factoryId']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form text-center">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                <i class="fa fa-search"></i> @lang('label.FILTER')
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- End Filter -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th class=" vcenter text-center">@lang('label.SL_NO')</th>
                            <th class=" vcenter text-center">@lang('label.DATE')</th>
                            <th class=" vcenter text-center">@lang('label.TIME')</th>
                            <th class=" vcenter text-center" width="120">@lang('label.REFERENCE_NO')</th>
                            <th class=" vcenter text-center">@lang('label.RECIPE')</th>
                            <th class=" vcenter text-center">@lang('label.STYLE')</th>
                            <th class=" vcenter text-center">@lang('label.SEASON')</th>
                            <th class=" vcenter text-center">@lang('label.COLOR')</th>
                            <th class=" vcenter text-center">@lang('label.WASH_TYPE')</th>
                            <th class=" vcenter text-center">@lang('label.WASH_MC_NO')</th>
                            <th class=" vcenter">@lang('label.OPERATOR_NAME')</th>
                            <th class=" vcenter text-center">@lang('label.SHIFT')</th>
                            <th class=" vcenter text-center">@lang('label.FACTORY')</th>
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
                            <td class="vcenter vcenter text-center">{{ ++$sl }}</td>
                            <td class=" vcenter text-center">{{ Helper::dateFormat($target->date) }}</td>
                            <td class=" vcenter text-center">{{ Helper::dateFormat($target->created_at) }}<br />{{ date('h:iA',strtotime($target->created_at)) }}</td>
                            <td class=" vcenter text-center">{{ $target->reference_no }}</td>
                            <td class=" vcenter text-center">{{ $target->recipe_reference_no }}</td>
                            <td class=" vcenter text-center">{{ $target->style }}</td>
                            <td class=" vcenter text-center">{{ $target->season }}</td>
                            <td class=" vcenter text-center">{{ $target->color }}</td>
                            <td class=" vcenter text-center">{{ !empty($target->wash_type_id)? $washTypeArr[$target->wash_type_id]: '' }}</td>
                            <td class=" vcenter text-center">{{ $target->Machine->machine_no }}</td>
                            <td class=" vcenter">{{ $target->operator_name }}</td>
                            <td class=" vcenter text-center">{{ !empty($target->shift_id)?$shiftArr[$target->shift_id]:'' }}</td>
                            <td class=" vcenter text-center">{{ $target->factory }}</td>

                            <td class="text-center vcenter td-actions">
                                @if(!empty($userAccessArr[46][5]))
                                <button type="button" class="btn yellow btn-xs tooltips details-btn" title="@lang('label.VIEW_BATCH_CARD_DETAILS')" id="detailsBtn-{{$target->id}}" data-target="#batchDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                                @if(!empty($userAccessArr[46][14]))
                                <button type="button" class="btn blue btn-xs tooltips manage-info" title="@lang('label.MANAGE_INFORMATION_OF_BATCH_CARD')" id="namageBtn-{{$target->id}}" data-target="#manageBatch" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-cog"></i>
                                </button>
                                @endif
                                @if(!empty($userAccessArr[46][5]))
                                <button type="button" class="btn red btn-xs tooltips recipe-details" title="@lang('label.NEW_RECIPE_DETAILS')" id="recipeDetailsBtn-{{$target->id}}" data-target="#recipeDetails" data-toggle="modal" data-id="{{$target->id}}" data-batch-recipe-id="{{$target->batch_recipe_id}}" >
                                    <i class="fa fa-info text-white"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="15" class="vcenter">@lang('label.NO_BATCH_CARD_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>

<!-- START:: batch card details modal -->
<div class="modal container fade" id="batchDetails" tabindex="-1" aria-hidden="true" role="dialog">
    <div id="showbatchDetails">
        <!-- ajax will be load here-->
    </div>
    
</div>
<!-- END:: batch card details modal -->

<!-- START:: recipe details modal -->
<div class="modal container fade" id="recipeDetails" tabindex="-1">
    <div id="showRecipeDetails">
        <!--    ajax will be load here-->
    </div>
</div>
<!-- END:: recipe details modal -->

<!-- Modal Of More Information -->
<div class="modal fade" id="manageBatch" tabindex="-1">
    <div id="showManageBatch">
        <!--    ajax will be load here-->
    </div>
</div>
<!-- End of Modal -->
<script type="text/javascript">
    $(function () {
        // START:: Batch card details here 
        $(document).on('click', '.details-btn', function (event) {
            event.preventDefault();
            var batchId = $(this).data("id");

            $.ajax({
                url: "{{URL::to('batchCard/getDetails')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    batch_id: batchId
                },
                beforeSend: function () {
                    //App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showbatchDetails').html(res.html);
                    //App.unblockUI();
                }
            });
        });
        // END:: Batch card details here 

        //START:: Recipe Details here
        $(document).on('click', '.recipe-details', function (event) {
            event.preventDefault();
            var batchId = $(this).data("id");
            var batchRecipeId = $(this).data("batch-recipe-id");

            $.ajax({
                url: "{{URL::to('batchCard/getRecipeDetails')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    batch_id: batchId,
                    batch_recipe_id: batchRecipeId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showRecipeDetails').html(res.html);
                    App.unblockUI();
                }
            });
        });
        //END:: Recipe Details here


        //Manage Batch Card More Information
        $(document).on('click', '.manage-info', function () {

            var batchId = $(this).data("id");
            $.ajax({
                url: "{{URL::to('batchCard/manageInfo')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    batch_id: batchId
                },
                beforeSend: function () {
                    // App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showManageBatch').html(res.html);
                    $('.js-source-states').select2();
                    //App.unblockUI();
                },
            });
        });

        /* Load Refference No */

        $(document).ready(function () {
            $('#search').keyup(function(e) {
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
						url: "{{URL::to('batchCard/loadBatchToken')}}",
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						data: {
							'search_keyword': value
						},
						beforeSend: function() {
							App.blockUI({boxed: true});
						},
						success: function(res) {
							//we need to check if the value is the same
							//Receiving the result of search here
							$('#tokenNo').html(res.html);
							$("#searchResult li").bind("click", function() {
								setText(this);
								$('#searchResult').css('border','0px');
							});
							$('span#character-count').text('');
							App.unblockUI();
						}
					});
				}
			});

            //For Click Outside of loaded element
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

        function setText(element) {
            var value = $(element).text();
            var id = $(element).val();
            if (id == '') {
                $("#searchResult").click(function (event) {
                    event.stopPropagation();
                });
            } else {
                $("#search").val(value);
                $("#searchResult").empty();
            }

        }
    });
</script>
@stop