<div class="modal-header">
    <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>
    <h4 class="modal-title"><strong>@lang('label.VIEW_PRODUCT_DETAILS')</strong></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="tab-content" id="display-basic-information-from">
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3"><strong>@lang('label.CHECKIN_DATE')</strong> : {!! $target['checkin_date'] !!}</div>
                                <div class="col-md-3"><strong>@lang('label.REFERENCE_NO')</strong> : {!! $target['ref_no'] !!}</div>
                                <div class="col-md-3"><b>@lang('label.CHALLAN_NO')</b> : {!!  $target['challan_no'] !!}</div>
                                <div class="col-md-3"><b>@lang('label.CHECKIN_BY')</b> : {!! $target['first_name'].' '.$target['last_name'] !!}</div>
                            </div> 
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4"><b>@lang('label.CHECKIN_AT')</b> : {!! Helper::printDateFormat($target['created_at']) !!}</div>
                                <div class="col-md-2"><b>@lang('label.MSDS')</b> : {!!  ($target['msds'] == 1) ? 'Yes' : 'No' !!}</div>
                                <div class="col-md-2"><b>@lang('label.M_LABEL')</b> : {!!  ($target['has_mlabel'] == 1) ? 'Yes' : 'No' !!}</div>
                                <div class="col-md-2"><b>@lang('label.FACTORY_LABEL')</b> : {!! ($target['factory_label']== 1) ? 'Yes' : 'No' !!}</div>
                                <div class="col-md-2">
                                    <b>@lang('label.SOURCE')</b> : 
                                    <span class="label label-sm label-{{$sourceArr[$target->source]['label']}}">{{ $sourceArr[$target->source]['status'] }}</span>
                                </div>
                            </div> 
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center vcenter"><strong>@lang('label.SL_NO')</strong></th>
                                        <th class="vcenter"><strong>@lang('label.CATEGORY')</strong></th>
                                        <th class="vcenter"><strong>@lang('label.PRODUCT')</strong></th>
                                        <th class="vcenter"><strong>@lang('label.PURPOSE')</strong></th>
                                        <th class="vcenter"><strong>@lang('label.SUPPLIER')</strong></th>
                                        <th class="vcenter"><strong>@lang('label.SUPPLIER_ADDRESS')</strong></th>
                                        <th class="vcenter"><strong>@lang('label.MANUFACTURER')</strong></th>
                                        <th class="vcenter"><strong>@lang('label.MANUFACTURER_ADDRESS')</strong></th>
                                        <th class="vcenter"><strong>@lang('label.LOT_NO')</strong></th>
                                        <th class="text-center vcenter"><strong>@lang('label.QUANTITY')&nbsp; (@lang('label.IN_KG'))</strong></th>
                                        <th class="text-center vcenter"><strong>@lang('label.QUANTITY')&nbsp; (@lang('label.DETAILS'))</strong></th>
                                        <th class="text-right vcenter"><strong>@lang('label.RATE')</strong></th>
                                        <th class="text-right vcenter"><strong>@lang('label.TOTAL_PRICE')</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$targetArr->isEmpty())
                                    <?php
                                    $sl = $totalPrice = 0;
                                    ?>
                                    @foreach($targetArr as $item)
                                    <?php
                                    $totalPrice += $item->rate * $item->quantity;
                                    ?>
                                    <tr>
                                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                                        <td class="vcenter">{!! $item->category_name !!}</td>
                                        <td class="vcenter">{!! $item->product_name !!}</td>
                                        <td class="vcenter">{!! $item->purpose !!}</td>
                                        <td class="vcenter">{!! $item->supplier_name !!}</td>
                                        <td class="vcenter">{!! $item->saddress !!}</td>
                                        <td class="vcenter">{!! $item->manufacturer_name !!}</td>
                                        <td class="vcenter">{!! $item->maddress !!}</td>
                                        <td class="vcenter">{!! $item->lot_number !!}</td>
                                        <td class="text-center vcenter">{!! $item->quantity !!}</td>
                                        <td class="text-center vcenter">{!! Helper::unitConversion($item->quantity) !!}</td>
                                        <td class="text-right vcenter">{!! Helper::numberformat($item->rate) !!}</td>
                                        <td class="text-right vcenter">{!! Helper::numberformat($item->rate * $item->quantity) !!}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="12"><b>@lang('label.TOTAL') : </b></td>
                                        <td class="text-right">{!! Helper::numberformat($totalPrice) !!}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="14">@lang('label.EMPTY_DATA')</td>
                                    </tr>
                                    @endif 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>