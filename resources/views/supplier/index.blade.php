@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.SUPPLIER_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[13][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('supplier/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_SUPPLIER')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'supplier/filter','class' => 'form-horizontal')) !!}
                {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
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
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="supplierType">@lang('label.SUPPLIER_TYPE')</label>
                        <div class="col-md-8">
                            {!! Form::select('supplier_type',  $supplierTypeArr, Request::get('supplier_type'), ['class' => 'form-control js-source-states','id'=>'supplierType']) !!}
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


                <div class="col-md-12">
                    <div class="form  text-right">
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
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.SUPPLIER_TYPE')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="vcenter">@lang('label.WEB_ADDRESS')</th>
                            <th class="vcenter">@lang('label.PHONE')</th>
                            <th class="vcenter">@lang('label.EMAIL')</th>
                            <th class="text-center vcenter">@lang('label.ADDRESS')</th>
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
                            <td class="vcenter">{{ $target->supplier_type }}</td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="vcenter">{{ $target->web_address }}</td>
                            <td class="vcenter">
                                @if(!empty($phoneDataArr[$target->id]))
                                {!!  implode(",\n",$phoneDataArr[$target->id]) !!}
                                @endif
                            </td>
                            <td class="vcenter">
                                @if(!empty($emailDataArr[$target->id]))
                                {!!  implode(",\n",$emailDataArr[$target->id]) !!}
                                @endif
                            </td>
                            <td class="vcenter">{{ $target->address }}</td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                <div>
                                    @if(!empty($userAccessArr[13][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('supplier/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[13][4]))
                                    {{ Form::open(array('url' => 'supplier/' . $target->id.'/'.Helper::queryPageStr($qpArr))) }}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
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
                            <td colspan="12" class="vcenter">@lang('label.NO_SUPPLIER_FOUND')</td>
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