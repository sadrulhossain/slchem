@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.PENDING_FOR_APPROVAL_PRODUCT_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'product/pendingFilter','class' => 'form-horizontal')) !!}
                {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search">@lang('label.SEARCH')</label>
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
                        <label class="control-label col-md-4" for="productFunction">@lang('label.PRODUCT_FUNCTION')</label>
                        <div class="col-md-8">
                            {!! Form::select('product_function',  $productFunctionArr, Request::get('product_function'), ['class' => 'form-control js-source-states','id'=>'productFunction']) !!}
                        </div>
                    </div>
                </div>
                
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

                <div class="col-md-5">
                    <div class="form text-center">
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
                            <th class="text-center">@lang('label.SL_NO')</th>
                            <th>@lang('label.PRODUCT_CATEGORY')</th>
                            <th>@lang('label.PRODUCT_FUNCTION')</th>
                            <th>@lang('label.NAME')</th>
                            <th>@lang('label.PRODUCT_CODE')</th>
<!--                            <th class="text-center">@lang('label.STATUS')</th>-->
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
                            <td class="text-center vcenter">{{ ++$sl }}</td>
                            <td class="vcenter">{{ $target->product_category }}</td>
                            <td class="vcenter">{{ $target->product_function }}</td>
                            <td class="vcenter">{{ $target->name }}</td>
                            <td class="vcenter">{{ $target->product_code }}</td>
<!--                            <td class="text-center vcenter">
                                <span class="label label-sm label-{{$statusArr[$target->approval_status]['label']}}">{{ $statusArr[$target->approval_status]['status'] }}</span>
                            </td>-->
                            <td class="text-center vcenter">

                                {{ Form::open(array('url' => 'product/doApprove/' . $target->id.Helper::queryPageStr($qpArr))) }}
                                <button class="btn btn-xs btn-primary approve-product tooltips" type="submit" data-placement="top" data-rel="tooltip" data-original-title="@lang('label.APPROVE_PRODUCT')" data-product-name="{{ $target->name }}">
                                    <i class="fa fa-check-circle"></i>
                                </button>
                                <!--                                <a href="{{URL::to('result?course_id='.$target->id)}}" class="btn btn-xs btn-success tooltips" data-placement="top" data-rel="tooltip" data-original-title="@lang('label.VIEW_RESULT_DETAILS')">
                                                                    <i class="fa fa-table text-white"></i>
                                                                </a>-->

                                {{ Form::close() }}

                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7">@lang('label.NO_PRODUCT_FOUND_FOR_APPROVAL')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>


<script type="text/javascript">

    $('.approve-product').on('click', function(e) {
        e.preventDefault();
        var form = $(this).parents('form');
        var productName = $(this).attr('data-product-name');

        swal({
            title: "Are you sure to Approve " + productName + "?",
            text: "You can not undo this action!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Approve It",
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm)
                form.submit();
        });
    });

</script>
@stop