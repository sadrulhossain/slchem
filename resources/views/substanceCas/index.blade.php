@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.SUBSTANCE_CAS_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[15][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('substanceCas/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_CAS')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                {!! Form::open(array('group' => 'form', 'url' => 'substanceCas/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                            <div class="col-md-8">
                                {!! Form::text('search',Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name', 'list'=>'search', 'autocomplete'=>'off']) !!} 
                                <datalist id="search">
                                    @if(!empty($nameArr))
                                    @foreach($nameArr as $name)
                                    <option value="{{$name->cas_name}}"></option>
                                    @endforeach
                                    @endif
                                </datalist>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.CAS_NO')</label>
                            <div class="col-md-8">
                                {!! Form::text('cas_no',Request::get('cas_no'), ['class' => 'form-control tooltips', 'title' => 'CasNo', 'placeholder' => 'Cas No', 'list'=>'casNo', 'autocomplete'=>'off']) !!} 
                                <datalist id="casNo">
                                    @if(!empty($casArr))
                                    @foreach($casArr as $casNO)
                                    <option value="{{$casNO->cas_no}}"></option>
                                    @endforeach
                                    @endif
                                </datalist>
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
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">@lang('label.SL_NO')</th>
							<th class="text-center">@lang('label.CAS_NO')</th>
                            <th>@lang('label.CAS_NAME')</th>
                            <th class="text-center">@lang('label.ACTION')</th>
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
                            <td class="text-center">{{ ++$sl }}</td>
							<td class="text-center">{{ $target->cas_no }}</td>
                            <td>{{ $target->cas_name }}</td>
                            <td>
                                <div class="text-center">
                                    @if(!empty($userAccessArr[15][3]))
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('substanceCas/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[15][4]))
                                    {{ Form::open(array('url' => 'substanceCas/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
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
                            <td colspan="8">@lang('label.NO_CAS_FOUND')</td>
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