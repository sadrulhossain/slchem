@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-file-photo-o"></i>@lang('label.PICTOGRAM_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[18][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('pictogram/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_PICTOGRAM')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'pictogram/filter','class' => 'form-horizontal')) !!}
                {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name', 'list'=>'search', 'autocomplete'=>'off']) !!} 
                            <datalist id="search">
                                @if(!empty($nameArr))
                                @foreach($nameArr as $name)
                                <option value="{{$name->name}}"></option>
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
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.PICTOGRAM_NAME')</th>
                            <th class="text-center vcenter">@lang('label.DESCRIPTION')</th>
                            <th class="text-center vcenter">@lang('label.ORDER')</th>
                            <th class="text-center vcenter">@lang('label.PHOTO')</th>
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
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $target->name !!}</td>
                            <td class="vcenter">{!! $target->description !!}</td>
                            <td class="text-center vcenter">{!! $target->order !!}</td>
                            <td class="vcenter">
                                @if(isset($target->logo))
                                <img width="80" height="80" src="{{URL::to('/')}}/public/uploads/pictogram/{{$target->logo}}" alt="{{ $target->name}}"/>
                                @else
                                <img width="80" height="80" src="{{URL::to('/')}}/public/img/no-image.png" alt="{{ $target->name}}"/>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                <div>
                                    @if(!empty($userAccessArr[18][3]))
                                    <a class="btn btn-icon-only btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('pictogram/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[18][4]))
                                    {!! Form::open(array('url' => 'pictogram/' . $target->id.'/'.Helper::queryPageStr($qpArr))) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button class="btn btn-icon-only btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {!! Form::close() !!}
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_PICTOGRAM_FOUND')</td>
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