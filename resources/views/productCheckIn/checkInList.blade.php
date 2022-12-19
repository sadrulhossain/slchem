@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.PURCHASED_ITEM_LIST')
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'productCheckInList/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12 margin-bottom-20">
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="refNo">@lang('label.REFERENCE_NO')</label>
                                <div>
                                    {!! Form::text('ref_no',Request::get('ref_no'), ['class' => 'form-control tooltips', 'title' => 'Reference', 'placeholder' => 'Reference', 'list'=>'refNo', 'autocomplete'=>'off']) !!} 
                                    <datalist id="refNo">
                                        @if(!empty($refNoArr))
                                        @foreach($refNoArr as $refNo)
                                        <option value="{{$refNo->ref_no}}"></option>
                                        @endforeach
                                        @endif
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="chalanNo">@lang('label.CHALLAN_NO')</label>
                                <div>
                                    {!! Form::text('challan_no',Request::get('challan_no'), ['class' => 'form-control tooltips', 'title' => 'Challan No', 'placeholder' => 'Challan No', 'list'=>'challanNo', 'autocomplete'=>'off']) !!} 
                                    <datalist id="challanNo">
                                        @if(!empty($challanNoArr))
                                        @foreach($challanNoArr as $challanNo)
                                        <option value="{{$challanNo->challan_no}}"></option>
                                        @endforeach
                                        @endif
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">@lang('label.CHECKIN_DATE') :</label>
                                <div class="input-group date datepicker" style="z-index:0!important;" data-date-end-date="+0d">
                                    {!! Form::text('checkin_date', Request::get('checkin_date'), ['id'=> 'checkinDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="checkinDate">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger">{{ $errors->first('checkin_date') }}</span>
                                </div>
                            </div>
                            <div class="col-md-2 margin-top-20">
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
                            <th>@lang('label.CHECKIN_DATE')</th>
                            <th>@lang('label.REFERENCE_NO')</th>
                            <th>@lang('label.CHALLAN_NO')</th>
                            <th>@lang('label.M_LABEL')</th>
                            <th>@lang('label.MSDS')</th>
                            <th>@lang('label.FACTORY_LABEL')</th>
                            <th>@lang('label.CHECKIN_BY')</th>
                            <th>@lang('label.CHECKIN_AT')</th>
                            <th>@lang('label.ACTION')</th>
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
                            <td>{!! $target->checkin_date !!}</td>
                            <td>{!! $target->ref_no !!}</td>
                            <td>{!! $target->challan_no !!}</td>
                            <td class="text-center vcenter">{!! ($target['has_mlabel'] == '1') ? '<i class="fa fa-check-square"></i>'  : '<i class="fa fa-remove"></i>' !!}</td>
                            <td class="text-center vcenter">{!! ($target['msds'] == '1') ? '<i class="fa fa-check-square"></i>' : '<i class="fa fa-remove"></i>' !!}</td>
                            <td class="text-center vcenter">{!! ($target['factory_label'] == '1') ? '<i class="fa fa-check-square"></i>' : '<i class="fa fa-remove"></i>' !!}</td>
                            <td>
                                {!! $target['first_name'].' '.$target['last_name'] !!}
                            </td>
                            <td>
                                {!! Helper::printDateFormat($target['created_at']) !!}
                            </td>
                            <td class="text-center">
                                @if(!empty($userAccessArr[40][5]))
                                <button type="button" class="btn yellow btn-xs tooltips details-btn" title="View Checked in product Details" id="detailsBtn-{{$target->id}}" data-target="#productDetails" data-toggle="modal" data-id="{{$target->id}}">
                                    <i class="fa fa-navicon text-white"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="10">@lang('label.NO_PUCHASED_ITEM_FOUND')</td>
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
        <!--    ajax will be load here-->
    </div>
    <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.details-btn', function() {

        var masterId = $(this).attr("data-id");
        //alert(refNo);return false;
        $.ajax({
            url: "{{URL::to('productCheckInList/getProductDetails')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                master_id: masterId,
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