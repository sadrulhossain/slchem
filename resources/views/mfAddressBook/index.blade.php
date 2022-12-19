@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.MANUFACTURER_ADDRESS_BOOK_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[11][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('mfAddressBook/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_MANUFACTURER_ADDRESS_BOOK')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                {!! Form::open(array('group' => 'form', 'url' => 'mfAddressBook/filter','class' => 'form-horizontal')) !!}

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.TITLE')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Title', 'placeholder' => 'Title', 'list'=>'search', 'autocomplete'=>'off']) !!} 
                            <datalist id="search">
                                @if(!empty($titleArr))
                                @foreach($titleArr as $title)
                                <option value="{{$title->title}}"></option>
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="country">@lang('label.COUNTRY')</label>
                        <div class="col-md-8">
                            {!! Form::select('country', $FiltercountryArr, Request::get('country'), ['class' => 'form-control js-source-states']) !!} 
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="manufecturer">@lang('label.MANUFACTURER')</label>
                        <div class="col-md-8">
                            {!! Form::select('manufecturer', $manufacturerArr, Request::get('manufecturer'), ['class' => 'form-control js-source-states']) !!} 
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form text-right">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> @lang('label.FILTER')
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}

            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">@lang('label.SL_NO')</th>
                            <th>@lang('label.MANUFACTURER')</th>
                            <th>@lang('label.TITLE')</th>
                            <th>@lang('label.COUNTRY')</th>
                            <th>@lang('label.PHONE')</th>
                            <th>@lang('label.EMAIL')</th>
                            <th>@lang('label.ADDRESS')</th>
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
                            <td class="text-center">{{ ++$sl }}</td>
                            <td>{{ $target->mf_name }}</td>
                            <td>{{ $target->title }}</td>
                            <td>{{ $countryArr[$target->country_id] }}</td>
                            <td class="vcenter">
                                @if(!empty($phoneDataArr[$target->id]))
                                {!!  implode(',',$phoneDataArr[$target->id]) !!}
                                @endif
                            </td>
                            <td class="vcenter">
                                @if(!empty($emailDataArr[$target->id]))
                                {!!  implode(",\n",$emailDataArr[$target->id]) !!}
                                @endif
                            </td>
                            <td>{{ $target->address }}</td>
                            <td>
                                <div class="text-center">
                                    @if(!empty($userAccessArr[11][3]))
                                    <a class="btn btn-xs btn-primary tooltips" title="Edit" href="{{ URL::to('mfAddressBook/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[11][4]))
                                    {{ Form::open(array('url' => 'mfAddressBook/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
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
                            <td colspan="8">@lang('label.NO_MANUFACTURER_ADDRESS_BOOK_FOUND')</td>
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