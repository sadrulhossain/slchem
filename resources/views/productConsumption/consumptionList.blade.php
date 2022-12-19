@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.CONSUMED_ITEM_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'productConsumptionList/filter','class' => 'form-horizontal')) !!}
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
                        <tr class="center">
                            <th class="text-center">@lang('label.SL_NO')</th>
                            <th>@lang('label.CHECK_OUT_DATE')</th>
                            <th>@lang('label.REFERENCE_NO')</th>
                            <th>@lang('label.CONSUMED_BY')</th>
                            <th class="vcenter">@lang('label.ADJUSTED_AT')</th>
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
                            <td class="text-center">{!! ++$sl !!}</td>
                            <td> {!! $target->adjustment_date !!}</td>
                            <td> {!! $target->voucher_no !!}</td>
                            <td>{!! $target->first_name.' '.$target->last_name !!}</td>
                            @if(!empty($target->created_at))
                            <td> 
                                {!! Helper::printDateFormat($target->created_at) !!}
                            </td>
                            @else
                            <td>---</td>
                            @endif
                            <td class="text-center"> 
                                @if(!empty($userAccessArr[43][5]))
                                <button type="button" class="btn yellow btn-xs tooltips details-btn" title="View Checked out product Details" id="detailsBtn-{{$target->voucher_no}}" data-target="#productDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('label.NO_ADJUST_ITEM_FOUND')</td>
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
<div class="modal container fade" id="productDetails">
    <div id="showProductDetails">
    </div>
</div>

<div id="showProductDetailsWait" style="display:none;">
    <div class="modal-header  clone-modal-header">
    <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>
    <h4 class="modal-title"><strong>@lang('label.VIEW_PRODUCT_DETAILS')</strong></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
               <strong>@lang('label.LOADING_PLEASE_WAIT')</strong>
			</div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
    </div>
</div>

</div>

<script type="text/javascript">

    $(document).on('click', '.details-btn', function() {

        var adjustmentId = $(this).attr("data-id");
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        $.ajax({
            url: "{{URL::to('productConsumptionList/getProductDetails')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                adjustment_id: adjustmentId
            },
            beforeSend: function() {
               $('#showProductDetails').html($('#showProductDetailsWait').html());
            },
            success: function(res) {
                $('#showProductDetails').html(res.html);
                App.unblockUI();
            },
        });
    });

</script>
@stop