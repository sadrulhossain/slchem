@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.BATCH_CARD_REPORT')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="row">
                    <!-- Begin Filter-->
                    {!! Form::open(array('group' => 'form', 'url' => 'batchCardReport/filter','class' => 'form-horizontal')) !!}
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
                                <label class="control-label col-md-4" for="buyerId">@lang('label.BUYER')</label>
                                <div class="col-md-8">
                                    {!! Form::select('buyer_id',  $buyerArr, Request::get('buyer_id'), ['class' => 'form-control js-source-states','id'=>'buyerId']) !!}
                                    <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="recipe">@lang('label.RECIPE_NO')</label>
                                <div class="col-md-8">
                                    {!! Form::select('recipe',  $recipeArr, Request::get('recipe'), ['class' => 'form-control js-source-states','id'=>'recipe']) !!}
                                </div>
                            </div>
                        </div>
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
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="shiftId">@lang('label.SHIFT_NAME')</label>
                                <div class="col-md-8">
                                    {!! Form::select('shift', $shiftArr, Request::get('shift'),['class' => 'form-control js-source-states','id'=>'shiftId']) !!} 
                                </div>
                            </div>
                        </div>

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
                    </div>  

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="factoryId">@lang('label.FACTORY')</label>
                                <div class="col-md-8">
                                    {!! Form::select('factory_id',  $factoryArr, Request::get('factory_id'), ['class' => 'form-control js-source-states','id'=>'factoryId']) !!}
                                    <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="seasonId">@lang('label.SEASON')</label>
                                <div class="col-md-8">
                                    {!! Form::select('season_id',  $seasonArr, Request::get('season_id'), ['class' => 'form-control js-source-states','id'=>'seasonId']) !!}
                                    <span class="text-danger">{{ $errors->first('season_id') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="colorId">@lang('label.COLOR')</label>
                                <div class="col-md-8">
                                    {!! Form::select('color_id',  $colorArr, Request::get('color_id'), ['class' => 'form-control js-source-states','id'=>'colorId']) !!}
                                    <span class="text-danger">{{ $errors->first('color_id') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form text-center col-md-4">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.GENERATE')
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- End Filter -->
            @if (!empty($qpArr))
            <div class="row elements">
                <div class="col-md-6">
                    <?php $btnQty = !empty($userAccessArr[54][6]) ? 'btn-qty-1' : 'btn-qty-2' ?>
                    <button class="btn mt-ladda-btn ladda-button btn-circle bg-blue-dark bg-font-blue-dark {{ $btnQty }}">
                        <span><b>@lang('label.TOTAL_QTY') (@lang('label.PCS')) : {{ $totalQty }}</b></span>
                    </button>
                </div>
                <div class="col-md-6 pull-right" id="manageEvDiv">
                    @if(!empty($userAccessArr[54][6]))
                    <a class="btn btn-md btn-success vcenter tooltips" target="_blank" title="Click here to Print Daily CheckIn Report"  href="{!! URL::full().'&view=print' !!}">
                        <i class="fa fa-print"></i> @lang('label.PRINT')
                    </a>
                    @endif
                </div>
            </div>
            <div class="row filter-header">
                <div class="col-md-12">
                    <h5 class="bold bg-blue-dark bg-font-blue-dark" style="padding: 10px;">
                        <span>@lang('label.DATE') : {{!empty($request->date)? Helper::dateFormat($request->date) :__('label.ALL')}}</span> | 
                        <span>@lang('label.REFERENCE_NO') : {{!empty($request->search)? $request->search : __('label.ALL')}}</span> |
                        <span>@lang('label.BUYER') : {{ !empty($request->buyer_id) ? !empty($buyerArr[$request->buyer_id])? $buyerArr[$request->buyer_id] : __('label.ALL'): __('label.ALL')}}</span> |
                        <span>@lang('label.RECIPE') : {{ !empty($request->recipe) ? !empty($recipeArr[$request->recipe])? $recipeArr[$request->recipe]:__('label.ALL') : __('label.ALL') }}</span> | 
                        <span>@lang('label.STYLE') : {{ !empty($request->style_id) ? !empty($styleArr[$request->style_id])? $styleArr[$request->style_id] : __('label.ALL') : __('label.ALL')}}</span> | 
                        <span>@lang('label.SEASON') : {{ !empty($request->season_id) ? !empty($seasonArr[$request->season_id])? $seasonArr[$request->season_id] : __('label.ALL') : __('label.ALL')}}</span> | 
                        <span>@lang('label.COLOR') : {{ !empty($request->color_id) ? !empty($colorArr[$request->color_id])? $colorArr[$request->color_id] : __('label.ALL') : __('label.ALL')}}</span> | 
                        <span>@lang('label.WASH_MC_NO') : {{ !empty($request->machine) ? !empty($machineArr[$request->machine])? $machineArr[$request->machine] : __('label.ALL') : __('label.ALL')}}</span> | 
                        <span>@lang('label.SHIFT') : {{ !empty($request->shift) ? !empty($shiftArr[$request->shift])? $shiftArr[$request->shift] : __('label.ALL') : __('label.ALL')}}</span> | 
                        <span>@lang('label.OPERATOR_NAME') : {{!empty($request->operator_name)? $request->operator_name : __('label.ALL')}}</span> |
                        <span>@lang('label.WASH_TYPE') : {{ !empty($request->wash_type_id) ? !empty($washTypeArr[$request->wash_type_id])? $washTypeArr[$request->wash_type_id] : __('label.ALL') : __('label.ALL')}}</span> | 
                        <span>@lang('label.FACTORY') : {{ !empty($request->factory_id) ? !empty($factoryArr[$request->factory_id])? $factoryArr[$request->factory_id] : __('label.ALL') : __('label.ALL')}}</span>
                    </h5>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th class=" vcenter text-center">@lang('label.SL_NO')</th>
                            <th class=" vcenter text-center">@lang('label.DATE')</th>
                            <th class=" vcenter text-center">@lang('label.TIME')</th>
                            <th class=" vcenter text-center" width="10%">@lang('label.REFERENCE_NO')</th>
                            <th class=" vcenter text-center">@lang('label.RECIPE')</th>
                            <th class=" vcenter text-center">@lang('label.STYLE')</th>
                            <th class=" vcenter text-center">@lang('label.SEASON')</th>
                            <th class=" vcenter text-center">@lang('label.COLOR')</th>
                            <th class=" vcenter text-center">@lang('label.BUYER')</th>
                            <th class=" vcenter text-center">@lang('label.FACTORY')</th>
                            <th class=" vcenter text-center">@lang('label.WASH_TYPE')</th>
                            <th class=" vcenter text-center">@lang('label.WASH_MC_NO')</th>
                            <th class=" vcenter text-center">@lang('label.QTY') (@lang('label.PCS'))</th>
                            <th class=" vcenter">@lang('label.OPERATOR_NAME')</th>
                            <th class=" vcenter text-center">@lang('label.SHIFT_NAME')</th>
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
                            <td class=" vcenter text-center" width="10%">{{ $target->recipe_reference_no }}</td>
                            <td class=" vcenter text-center">{{ $target->style }}</td>
                            <td class=" vcenter text-center">{{ $target->season }}</td>
                            <td class=" vcenter text-center">{{ $target->color }}</td>
                            <td class=" vcenter text-center">{{ !empty($target->buyer_id)? !empty($buyerArr[$target->buyer_id]) ?$buyerArr[$target->buyer_id]: '' : '' }}</td>
                            <td class=" vcenter text-center">{{ !empty($target->factory_id)? !empty($factoryArr[$target->factory_id]) ? $factoryArr[$target->factory_id]: '' : '' }}</td>
                            <td class=" vcenter text-center">{{ !empty($target->wash_type_id)? !empty($washTypeArr[$target->wash_type_id]) ? $washTypeArr[$target->wash_type_id]: '' : '' }}</td>
                            <td class=" vcenter text-center">{{ $target->Machine->machine_no }}</td>
                            <td class=" vcenter text-center">{{ $target->wash_lot_quantity_piece }}</td>
                            <td class=" vcenter">{{ $target->operator_name }}</td>
                            <td class=" vcenter text-center">{{ !empty($target->shift_id)? !empty($target->shift_id)? $shiftArr[$target->shift_id]:'' :''}}</td>
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
            @endif
        </div>	
    </div>
</div>
<script type="text/javascript">
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
                    url: "{{URL::to('batchCardReport/loadBatchToken')}}",
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
</script>
@stop