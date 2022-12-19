<div class="modal-header  clone-modal-header">
    <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>
    <h4 class="modal-title"><strong>@lang('label.VIEW_PRODUCT_DETAILS')</strong></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-body">
                    <table class="table table-bordered table-hover">
                        <tr class="info">
                            <td class="vcenter bold" colspan="2">@lang('label.CHECK_OUT_DATE') : {!! $adjustmentInfo['adjustment_date'] !!}</td>
                            <td class="vcenter bold" colspan="2">@lang('label.REFERENCE_NO') : {!! $adjustmentInfo['voucher_no'] !!}</td>
                        </tr>
                        <tr>
                            <td class="vcenter bold" width="25%">@lang('label.ADJUSTED_BY')</td>
                            <td class="vcenter" width="25%">{!! $userArr[$adjustmentInfo['created_by']]!!}</td>
                            <td class="vcenter bold" width="25%">@lang('label.ADJUSTED_AT')</td>
                            <td class="vcenter" width="25%">{!! Helper::printDateFormat($adjustmentInfo['created_at']) !!}</td>
                        </tr>
                        @if($adjustmentInfo['status'] == 1 && $adjustmentInfo['source'] == '1')
                        @if(isset($userArr[$adjustmentInfo['approved_by']]) && isset($adjustmentInfo['approved_at']))
                        <tr>
                            <td class="vcenter bold" width="25%"><b>@lang('label.APPROVED_BY')</b></td>
                            <td class="vcenter" width="25%">{!! $userArr[$adjustmentInfo['approved_by']]!!}</td>
                            <td class="vcenter bold" width="25%">@lang('label.APPROVED_AT')</td>
                            <td class="vcenter" width="25%">{!! Helper::printDateFormat($adjustmentInfo['approved_at']) !!}</td>
                        </tr>
                        @endif
                        @endif
                    </table>
                    <br />
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr class="info">
                                <th class="text-center vcenter" rowspan="2"><strong>@lang('label.SL_NO')</strong></th>
                                <th class="vcenter" rowspan="2"><strong>@lang('label.CATEGORY')</strong></th>
                                <th class="vcenter" rowspan="2"><strong>@lang('label.PRODUCT')</strong></th>
                                <th class="text-center vcenter" rowspan="2"><strong>@lang('label.QUANTITY')&nbsp; (@lang('label.IN_KG'))</strong></th>
                                <th class="text-center vcenter" rowspan="2"><strong>@lang('label.QUANTITY')&nbsp; (@lang('label.DETAILS'))</strong></th>
                                @if(($adjustmentInfo['status'] == 1 && $adjustmentInfo['source'] == '1'))
                                <th class="text-center vcenter"  colspan="6"><strong>@lang('label.LOT_INFORMATION')</strong></th>
                                @endif
                            </tr>
                            @if(!empty($productWithLotArr) && ($adjustmentInfo['status'] == 1 && $adjustmentInfo['source'] == '1'))
                            <tr class="info">
                                <th class="text-center vcenter bold">@lang('label.SL_NO')</th>
                                <th class="vcenter bold">@lang('label.LOT_NUMBER')</th>
                                <th class="text-center vcenter bold">@lang('label.QUANTITY_IN_KG')</th>
                                <th class="vcenter text-center bold">@lang('label.QUANTITY')&nbsp;@lang('label.DETAILS')</th>
                                <th class="text-center vcenter bold">@lang('label.RATE')</th>
                                <th class="text-center vcenter bold">@lang('label.AMOUNT')</th>
                            </tr>
                            @endif
                        </thead>
                        <tbody>
                            @if (!$adjustmentDetailsArr->isEmpty())
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($adjustmentDetailsArr as $item)
                            <?php
                            $rowspan = (($adjustmentInfo['status'] == 1) && ($adjustmentInfo['source'] == '1')) ? (isset($productWithLotArr[$item->product_id][$item->id]) && !empty($productWithLotArr[$item->product_id][$item->id])) ? sizeof($productWithLotArr[$item->product_id][$item->id]) : '1' : '1';
                            ?>
                            <tr>
                                <td class="text-center vcenter" rowspan="{{ $rowspan }}">{!! ++$sl !!}</td>
                                <td class="vcenter" rowspan="{{ $rowspan }}">{!! $item->category_name !!}</td>
                                <td class="vcenter" rowspan="{{ $rowspan }}">{!! $item->product_name !!}</td>
                                <td class="text-center vcenter" rowspan="{{ $rowspan }}">{!! $item->quantity !!}</td>
                                <td class="text-center vcenter" rowspan="{{ $rowspan }}">{!! Helper::unitConversion($item->quantity) !!}</td>
                                @if(($adjustmentInfo['status'] == 1 && $adjustmentInfo['source'] == '1'))
                                <?php
                                $lotSl = 0;
                                if ($rowspan >= 1) { //If Product is consumed from Multiple Lot
                                    ?>
                                    @foreach($productWithLotArr[$item->product_id][$item->id] as $lotInfo)
                                    @if($lotSl == 0)
                                    <td class="text-center vcenter">{!! ++$lotSl !!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['lot_number']) ? $lotInfo['lot_number'] : '---' !!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['quantity']) ? Helper::numberformat($lotInfo['quantity']) : '---' !!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['quantity']) ? Helper::unitConversion($lotInfo['quantity']) : '---' !!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['rate']) ? $lotInfo['rate'] : '---'!!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['amount']) ? Helper::numberformat($lotInfo['amount']) : '---' !!}</td>

                                </tr>
                                @else
                                <tr>
                                    <td class="text-center vcenter">{!! ++$lotSl !!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['lot_number']) ? $lotInfo['lot_number'] : '---' !!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['quantity']) ? Helper::numberformat($lotInfo['quantity']) : '---' !!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['quantity']) ? Helper::unitConversion($lotInfo['quantity']) : '---' !!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['rate']) ? $lotInfo['rate'] : '---'!!}</td>
                                    <td class="text-center vcenter">{!! isset($lotInfo['amount']) ? Helper::numberformat($lotInfo['amount']) : '---' !!}</td>
                                </tr>
                                @endif
                                @endforeach
                                <?php
                            } else { //If Product is consumed from Single Lot OR NO Lot no is found (In case of product marked as "Don't show at store")
                                ?>                       
                            <td class="text-center vcenter">{!! ++$lotSl !!}</td>
                            <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['lot_number'])? $productWithLotArr[$product->id][$item->id]['lot_number'] : '--'  !!}</td>
                            <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['quantity'])? Helper::numberformat($productWithLotArr[$item->product_id][$item->id]['quantity'],6) : '--' !!}</td>
                            <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['quantity'])? Helper::unitConversion($productWithLotArr[$item->product_id][$item->id]['quantity']) : '--' !!}</td>
                            <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['rate'])? $productWithLotArr[$item->product_id][$item->id]['rate']: '--' !!}</td>
                            <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['amount'])? Helper::numberformat($productWithLotArr[$item->product_id][$item->id]['amount']) : '--' !!}</td>
                            </tr>                        
                            <?php
                        }
                        ?>
                        @else
                        </tr>
                        @endif
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6">@lang('label.EMPTY_DATA')</td>
                        </tr>
                        @endif 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
    </div>
</div>
