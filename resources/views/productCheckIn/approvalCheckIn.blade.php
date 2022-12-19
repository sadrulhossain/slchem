@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.PENDING_FOR_APPROVAL_CHECK_IN_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.REFERENCE_NO')</th>
                            <th class="vcenter">@lang('label.CHECKED_IN_BY')</th>
                            <th class="text-center vcenter">@lang('label.STATUS')</th>
                            <th class="text-center vcenter">@lang('label.ACTION')</th>
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

                            <td class="text-center">{!! ++$sl !!}</td>
                            <td>{!! $target->ref_no !!}</td>
                             <td>
                                {!! $target->first_name.' '.$target->last_name.' by '. $target->created_at !!}
                            </td>
                            <td class="text-center">
                                <span class="label label-sm label-{{$statusArr[$target->status]['label']}}">{!! $statusArr[$target->status]['status'] !!}</span>
                            </td>
                            <td class="text-center vcenter">
                                {!! Form::open(array('url' => 'productCheckIn/doApprove')) !!}
                                {!! Form::hidden('ref_no',$target->ref_no) !!}
                                <button class="btn btn-xs btn-primary approve-product tooltips" type="submit" data-placement="top" data-rel="tooltip" data-original-title="@lang('label.APPROVE_PRODUCT')" data-product-name="{{ $target->name }}">
                                    <i class="fa fa-check-circle"></i>
                                </button>
                                <button type="button" class="btn yellow btn-xs tooltips details-btn" title="View Checked in product Details" id="detailsBtn-{{$target->ref_no}}" data-target="#productDetails" data-toggle="modal" data-id="{{$target->ref_no}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9">@lang('label.NO_CHECKED_IN_PRODUCT_FOUND_FOR_APPROVAL')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6">{{ $targetArr->appends(Input::all())->links() }}</div>
                <div class="col-md-6 text-right">					
                    <?php
                    $start = empty($targetArr->total()) ? 0 : (($targetArr->currentPage() - 1) * $targetArr->perPage() + 1);
                    $end = ($targetArr->currentPage() * $targetArr->perPage() > $targetArr->total()) ? $targetArr->total() : ($targetArr->currentPage() * $targetArr->perPage());
                    ?> @lang('label.SHOWING') {{ $start }} @lang('label.TO') {{$end}} @lang('label.OF')  {{$targetArr->total()}} @lang('label.RECORDS')
                </div>
            </div>
        </div>	
    </div>
</div>

<!-- details modal -->

<div class="modal" id="productDetails" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><strong>@lang('label.VIEW_PRODUCT_DETAILS')</strong></h4>
            </div>
            <div class="modal-body" id="showProductDetails">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">

    $('.approve-product').on('click', function(e) {
        e.preventDefault();
        var form = $(this).parents('form');
        var productName = $(this).attr('data-product-name');

        swal({
            title: "Are you sure to Approve " + productName + "?",
            text: "Your can not undo this action!",
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


    $(document).on('click', '.details-btn', function() {

        var refNo = $(this).attr("data-id");
        //alert(refNo);return false;
        $.ajax({
            url: "{{URL::to('productCheckInList/getProductDetails')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                ref_no: refNo
            },
            beforeSend: function() {
                App.blockUI({boxed: true});
            },
            success: function(res) {
                $('#showProductDetails').html(res.html);
                App.unblockUI();
            },
        });
    });

</script>
@stop