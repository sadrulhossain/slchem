@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.DEMAND_TO_DELIVER')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'demandToDeliver/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12 margin-bottom-20">
                        <div class="col-md-3">
                            <div class="form">
                                <label class="control-label" for="refNo">@lang('label.REFERENCE_NO')</label>
                                <div>
                                    {!! Form::text('voucher_no',  Request::get('voucher_no'), ['class' => 'form-control tooltips', 'title' => 'Reference', 'placeholder' => 'Reference','list'=>'refNo', 'autocomplete'=>'off']) !!} 
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
                                <label class="control-label">@lang('label.DATE_OF_SUBSTORE') :</label>
                                <div class="input-group date datepicker" style="z-index:0!important;" data-date-end-date="+0d">
                                    {!! Form::text('adjustment_date', Request::get('adjustment_date'), ['id'=> 'substoreDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="substoreDate">
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
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.DATE_OF_SUBSTORE')</th>
                            <th class="vcenter">@lang('label.REFERENCE_NO')</th>
                            <th>@lang('label.GENERATED_BY')</th>
                            <th class="vcenter">@lang('label.GENERATED_AT')</th>
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
                            <td class="vcenter"> {!! $target->adjustment_date !!}</td>
                            <td class="vcenter"> {!! $target->voucher_no !!}</td>
                            <td class="vcenter">{!! $userArr[$target->created_by] !!}</td>
                            <td class="vcenter"> 
                                {!! !empty($target->created_at) ? Helper::printDateFormat($target->created_at) : '---' !!}
                            </td>
                            <td class="text-center vcenter">
                                @if(!empty($userAccessArr[52][5]))
                                <button type="button" class="btn yellow btn-xs tooltips details-btn" title="@lang('label.VIEW_DELIVERABLE_PRODUCT_DETAILS')" id="detailsBtn-{{$target->voucher_no}}" data-target="#productDetails" data-toggle="modal" data-id="{{$target->voucher_no}}" data-demand-id="{{ $target->id }}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                                @if(!empty($userAccessArr[52][9]))
                                {!! Form::open(array('url' => 'demandToDeliver/consume')) !!}
                                {!! Form::hidden('master_id',$target->id) !!}
                                <button class="btn bg-purple bg-font-purple btn-xs deliver-product tooltips" type="submit" data-placement="top" data-rel="tooltip" data-original-title="@lang('label.CLICK_HERE_TO_DELIVER')">
                                    <i class="fa fa-shopping-cart"></i>
                                </button>
                                {!! Form::close() !!}
                                @endif
                                @if(!empty($userAccessArr[52][6]))
                                <a href="{{ URL::to('demandToDeliver/getProductDetails/'.$target->id.'?view=print') }}" target="_blank"  class="btn btn-xs green-meadow tooltips" title="@lang('label.CLICK_HERE_TO_PRINT_DEMAND_LIST')">
                                    <i class="fa fa-print text-white"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('label.NO_SUBSTORED_DEMAND_ITEM_FOUND_TO_DELIVER')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @include('layouts.paginator')
        </div>	
    </div>
</div>

<!-- START:: Substore Details Modal -->
<div class="modal container fade" id="productDetails" role="dialog">
    <div id="showProductDetails">
    </div>
</div>
<!-- END:: Substore Details Modal -->

<script type="text/javascript">
    //START:: To View Substore Details
    $(document).on('click', '.details-btn', function() {
        var refNo = $(this).attr("data-id");
        var demandId = $(this).attr("data-demand-id");
        $.ajax({
            url: "{{URL::to('demandToDeliver/getProductDetails')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                voucher_no: refNo,
                demand_id: demandId
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
    //END:: To View Substore Details

    $(document).on('click', '.deliver-product', function(e) {
        e.preventDefault();
        $('#showProductDetails').modal('hide');
        var form = $(this).parents('form');

        swal({
            title: "Are you sure to Deliver Substore Product ?",
            type: "warning",
            animation: "slide-from-top",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Deliver It",
        }, function(isConfirm) {
            if (isConfirm)
                form.submit();

        });
    });
</script>
@stop