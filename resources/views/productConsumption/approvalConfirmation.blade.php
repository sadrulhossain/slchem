@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
        <div class="caption">
                <i class="fa fa-list"></i>@lang('label.PENDING_FOR_APPROVAL_CONSUMED_PRODUCT_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'productConsumptionApproval/pendingFilter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12 margin-bottom-20">
                        <div class="col-md-3">
                            <div class="form">
                                <label class="control-label" for="refNo">@lang('label.REFERENCE_NO')</label>
                                <div>
                                    {!! Form::text('ref_no',  Request::get('ref_no'), ['class' => 'form-control tooltips', 'title' => 'Reference', 'placeholder' => 'Reference','list'=>'refNo', 'autocomplete'=>'off']) !!} 
                                    <datalist id="refNo">
                                        @if(!empty($refNoArr))
                                        @foreach($refNoArr as $refNo)
                                        <option value="{{$refNo->voucher_no}}"></option>
                                        @endforeach
                                        @endif
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form">
                                <label class="control-label">@lang('label.CHECK_OUT_DATE') :</label>
                                <div class="input-group date datepicker" style="z-index:0!important;" data-date-end-date="+0d">
                                    {!! Form::text('adjustment_date', Request::get('adjustment_date'), ['id'=> 'checkoutDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="checkoutDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger">{{ $errors->first('adjustment_date') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 margin-top-20">
                            <div class="form">
                                <label class="control-label">&nbsp;</label>
                                <button type="submit" class="btn btn-md green btn-outline filter-submit">
                                    <i class="fa fa-search"></i> @lang('label.FILTER')
                                </button>
                            </div>
                        </div>
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
                            <th class="vcenter">@lang('label.CHECK_OUT_DATE')</th>
                            <th class="vcenter">@lang('label.REFERENCE_NO')</th>
                            <th class="vcenter">@lang('label.CONSUMED_BY')</th>
                            <th class="vcenter">@lang('label.ADJUSTED_AT')</th>
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
                            <td class="text-center">{!! ++$sl !!}</td>
                            <td>{!! $target->adjustment_date !!}</td>
                            <td>{!! $target->voucher_no !!}</td>
                            <td>
                                {!! $target->first_name.' '.$target->last_name !!}
                            </td>
                            @if(!empty($target->created_at))
                            <td> 
                               {!! Helper::printDateFormat($target->created_at) !!}
                            </td>
                            @else
                            <td>---</td>
                            @endif
                            <td class="text-center vcenter">
                                @if(!empty($userAccessArr[42][10]))
                                {!! Form::open(array('url' => 'productConsumptionApproval/doApprove')) !!}
                                {!! Form::hidden('master_id',$target->id) !!}
                                <button class="btn btn-xs btn-primary approve-product tooltips" type="submit" data-placement="top" data-rel="tooltip" data-original-title="@lang('label.APPROVE_PRODUCT')">
                                    <i class="fa fa-check-circle"></i>
                                </button>
                                {!! Form::close() !!}
                                @endif
                                @if(!empty($userAccessArr[42][5]))
                                <button type="button" class="btn yellow btn-xs tooltips details-btn" title="View Checked out product Details" id="detailsBtn-{{$target->voucher_no}}" data-target="#productDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9">@lang('label.NO_CONSUMED_PRODUCT_FOUND_FOR_APPROVAL')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>

<!-- details modal -->

<div class="modal container fade" id="productDetails" role="dialog">
    <div id="showProductDetails">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.details-btn', function() {
        var adjustmentId = $(this).attr("data-id");
        $.ajax({
            url: "{{URL::to('productConsumptionApproval/getProductDetails')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                adjustment_id: adjustmentId
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

    $(document).ready(function() {
        $('.approve-product').on('click', function(e) {
            e.preventDefault();
            $('#productDetails').modal('hide');
            var form = $(this).parents('form');

            swal({
                title: "Are you sure to Approve ?",
                text: "You can not undo this action!",
                type: "warning",
                animation: "slide-from-top",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Approve It",
            }, function(isConfirm) {
                if (isConfirm)
                    form.submit();

            });
        });
    });

</script>
@stop