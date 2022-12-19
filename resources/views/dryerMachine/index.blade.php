@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.DRYER_MACHINE_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[34][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('dryerMachine/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_DRYER_MACHINE')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'dryerMachine/filter','class' => 'form-horizontal')) !!}
                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="search">@lang('label.SEARCH')</label>
                            <div class="col-md-9">
                                {!! Form::text('search',Input::old('search'), ['class' => 'form-control tooltips', 'title' => __('label.MACHINE_NO'), 'placeholder' => __('label.MACHINE_NO'), 'list'=>'search', 'autocomplete'=>'off']) !!} 
                                <datalist id="search">
                                    @if(!empty($machineNoArr))
                                    @foreach($machineNoArr as $machineNo)
                                    <option value="{{$machineNo->machine_no}}"></option>
                                    @endforeach
                                    @endif
                                </datalist>
                            
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="dryerTypeId">@lang('label.SELECT_DRYER_TYPE')</label>
                            <div class="col-md-8">
                                {!! Form::select('dryer_type_id',  $dryerTypeArr, Request::get('dryer_type_id'), ['class' => 'form-control js-source-states','id'=>'dryerTypeId']) !!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS')</label>
                            <div class="col-md-8">
                                {!! Form::select('status',  $status, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']) !!}
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
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th>@lang('label.SL_NO')</th>
                            <th>@lang('label.DRYER_TYPE')</th>
                            <th>@lang('label.MACHINE_NO')</th>
                            <th>@lang('label.DESCRIPTION')</th>
                            <th class="text-center">@lang('label.STATUS')</th>
                            <th class="td-actions text-center">@lang('label.ACTION')</th>
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
                            <td>{{ ++$sl }}</td>
                            <td>{{ $target->DryerType->name }}</td>
                            <td>{{ $target->machine_no }}</td>
                            <td>{{ $target->description }}</td>
                            <td class="text-center">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-center">
                                    @if(!empty($userAccessArr[34][3]))
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('dryerMachine/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[34][4]))
                                    {{ Form::open(array('url' => 'dryerMachine/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('label.NO_DRYER_MACHINE_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>
@stop