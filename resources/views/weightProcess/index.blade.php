@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-balance-scale"></i>@lang('label.WEIGHT_PROCESS_LIST')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('weightProcess/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_WEIGHT_PROCESS')
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'weightProcess/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                            <div class="col-md-8">
                                {!! Form::text('search',Input::old('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name']) !!} 
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
                            <th>@lang('label.NAME')</th>
                            <th class="text-center">@lang('label.STATUS')</th>
                            <th class="td-actions text-center">@lang('label.ACTION')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$targetArr->isEmpty())
                        <?php
                        $page = Input::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * __('label.PAGINATION_COUNT');
                        ?>
                        @foreach($targetArr as $target)
                        <tr>
                            <td>{{ ++$sl }}</td>
                            <td>{{ $target->name }}</td>
                            <td class="text-center">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-center">
                                    {{ Form::open(array('url' => 'weightProcess/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('weightProcess/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {{ Form::close() }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('label.NO_WEIGHT_PROCESS_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6">{{ $targetArr->appends(Input::all())->links() }}</div>
                <div class="col-md-6 text-right">					
                    <?php $start = empty($targetArr->total()) ? 0 : (($targetArr->currentPage() - 1) * $targetArr->perPage() + 1);
                    $end = ($targetArr->currentPage() * $targetArr->perPage() > $targetArr->total()) ? $targetArr->total() : ($targetArr->currentPage() * $targetArr->perPage());
                    ?> @lang('label.SHOWING') {{ $start }} @lang('label.TO') {{$end}} @lang('label.OF')  {{$targetArr->total()}} @lang('label.RECORDS')
                </div>
            </div>
        </div>	
    </div>
</div>
@stop