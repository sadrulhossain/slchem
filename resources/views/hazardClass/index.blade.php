@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-industry"></i>@lang('label.HAZARD_CLASS_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[19][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('hazardClass/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_HAZARD_CLASS')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'hazardClass/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                            <div class="col-md-8">
                                {!! Form::text('search',Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name', 'list'=>'search', 'autocomplete'=>'off']) !!} 
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
                    
                    <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="hazardCategory">@lang('label.HAZARD_CATEGORY')</label>
                        <div class="col-md-8">
                            {!! Form::select('hazard_category',  $FilterHazardcategoryArr, Request::get('hazard_category'), ['class' => 'form-control js-source-states','id'=>'hazardCategory']) !!}
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
                            <th class="text-center">@lang('label.SL_NO')</th>
                             <th>@lang('label.HAZARD_CATEGORY')</th>
                            <th>@lang('label.HAZARD_CLASS')</th>
                            <th class="text-center">@lang('label.LOGO')</th>
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
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $categoryArr[$target->hazard_cat_id] }}</td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="text-center">
                                @if(!empty($target->hazardClassLogo))
                                @foreach($target->hazardClassLogo as $classLogo)
                                @if (!empty($classLogo->pictogram_id))
                                <img class="pictogram-min-space tooltips" width="50" height="50" src="{{URL::to('/')}}/public/uploads/pictogram/{{ $pictogramArr[$classLogo->pictogram_id] }}" alt="{{ $target->logo_name}}" title="{{ $pictogramNameArr[$classLogo->pictogram_id] }}"/>
                                @else 
                                <img width="50" height="50" src="{{URL::to('/')}}/public/img/no_image.png" alt=""/>
                                @endif
                                @endforeach
                                @endif
                            </td>
                            <td class="vcenter">
                                <div class="text-center">
                                    @if(!empty($userAccessArr[19][3]))
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('hazardClass/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[19][4]))
                                    {{ Form::open(array('url' => 'hazardClass/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
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
                            <td colspan="8">@lang('label.NO_HAZARD_CLASS_FOUND')</td>
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