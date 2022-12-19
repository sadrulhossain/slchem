@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cog"></i>@lang('label.SHADE_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[23][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('shade/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_SHADE')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'shade/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                            <div class="col-md-8">
                                {!! Form::text('search',Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name','list' => 'search','autocomplete' => 'off']) !!} 
                                <datalist id="search">
                                @if (!$nameArr->isEmpty())
                                @foreach($nameArr as $item)
                                <option value="{{$item->name}}" />
                                @endforeach
                                @endif
                            </datalist>
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
                        <tr>
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter text-center">@lang('label.DESCRIPTION')</th>
                            <th class="vcenter text-center">@lang('label.ORDER')</th>
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
                        <tr>
                            <td class="vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="vcenter text-center">{{ $target->description }}</td>
                            <td class="text-center vcenter">{{ $target->order }}</td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="vcenter text-center">
                                <div>
                                    @if(!empty($userAccessArr[23][3]))
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('shade/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[23][4]))
                                    {{ Form::open(array('url' => 'shade/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
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
                            <td colspan="6" class="vcenter">@lang('label.NO_PROCESS_TYPE_FOUND')</td>
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