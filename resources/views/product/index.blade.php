@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.PRODUCT_LIST')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[20][2]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('product/create'.Helper::queryPageStr($qpArr)) }}"> @lang('label.CREATE_NEW_PRODUCT')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">

            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'product/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.NAME')</label>
                        <div class="col-md-8">
                            {!! Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Name', 'placeholder' => 'Name','list' => 'productName','autocomplete' => 'off']) !!} 
                            <datalist id="productName">
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
                        <label class="control-label col-md-4" for="productCategory">@lang('label.PRODUCT_CATEGORY')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_category',  $productCategoryArr, Request::get('product_category'), ['class' => 'form-control js-source-states','id'=>'productCategory']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="approvalStatus">@lang('label.APPROVAL_STATUS'):</label>
                        <div class="col-md-8">
                            {!! Form::select('approval_status', $approvalStatusArr, Request::get('approval_status'), ['class' => 'form-control js-source-states', 'id' => 'approvalStatus']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productCode">@lang('label.PRODUCT_CODE')</label>
                        <div class="col-md-8">
                            {!! Form::text('product_code',  Request::get('product_code'), ['class' => 'form-control tooltips', 'title' => 'Product Code', 'placeholder' => 'Product Code', 'list' => 'productCode', 'autocomplete' => 'off']) !!} 
                            <datalist id="productCode">
                                @if (!$productCodeArr->isEmpty())
                                @foreach($productCodeArr as $productCode)
                                <option value="{{$productCode->product_code}}" />
                                @endforeach
                                @endif
                            </datalist>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="productFunction">@lang('label.PRODUCT_FUNCTION')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_function',  $productFunctionArr, Request::get('product_function'), ['class' => 'form-control js-source-states','id'=>'productFunction']) !!}
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
            </div>
            <div class="row">

                <div class="col-md-12">
                    <div class="form text-right">
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
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.PRODUCT_CATEGORY')</th>
                            <th class="vcenter">@lang('label.PRODUCT_FUNCTION')</th>
                            <th class="vcenter">@lang('label.NAME')</th>
                            <th class="text-center">@lang('label.PRODUCT_CODE')</th>
                            <th class="text-center">@lang('label.TYPE_OF_DOSAGE_RATIO')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="vcenter">@lang('label.CREATED_BY')</th>
                            <th class="text-center vcenter">@lang('label.APPROVAL_STATUS')</th>
                            <th class="vcenter">@lang('label.APPROVED_BY')</th>
                            <th class="text-center vcenter">@lang('label.APPROVED_AT')</th>
                            <th class="text-center vcenter">@lang('label.ACTION')</th>
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
                            <td class="vcenter">{!! $target->product_category !!}</td>
                            <td class="vcenter">{!! $target->product_function !!}</td>
                            <td class="vcenter">{!! $target->name !!}</td>
                            <td class="text-center vcenter">{!! $target->product_code !!}</td>
                            <td class="text-center vcenter">
                                <span class="label label-sm label-{{!empty($ratioArr[$target->type_of_dosage_ratio]) ? $ratioArr[$target->type_of_dosage_ratio]['label'] : ''}}">
                                    {{ !empty($ratioArr[$target->type_of_dosage_ratio]) ? $ratioArr[$target->type_of_dosage_ratio]['ratio'] : '' }}</span>
                            </td>
                            <td class="text-center vcenter">
                                @if($target->status == '1')
                                <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">{!! $userFirstNameArr[$target->created_by].' '.$userLastNameArr[$target->created_by] !!}</td>
                            <td class="text-center vcenter">
                                <span class="label label-sm label-{{$statusArr[$target->approval_status]['label']}}">{{ $statusArr[$target->approval_status]['status'] }}</span>
                            </td>
                            <td class="text-center vcenter">{!! (!empty($target->approved_by)) ?  $userFirstNameArr[$target->approved_by].' '.$userLastNameArr[$target->approved_by] : '--' !!}</td>
                            <td class="text-center vcenter">{!! (is_null($target->approved_at)) ? '--' : Helper::printDateFormat($target->approved_at) !!}</td>
                            <td class="text-center vcenter">
                                <div>
                                    @if(!empty($userAccessArr[20][3]))
                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="{{ URL::to('product/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!empty($userAccessArr[20][4]))
                                    {!! Form::open(array('url' => 'product/' . $target->id.'/'.Helper::queryPageStr($qpArr))) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    {!! Form::close() !!}
                                    @endif
                                    @if(!empty($userAccessArr[20][14]))
                                    <a class="btn yellow btn-xs tooltips manage-btn" title="Manage Products"  href="{{ URL::to('product/manageProduct/' . $target->id) }}">
                                        <i class="fa fa-navicon text-white"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
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