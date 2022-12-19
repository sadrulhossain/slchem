<div class="modal-header clone-modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.TOTAL_RECONCILIATION_MISMATCH')</strong></h4>
    </div>

    <div class="col-md-6">
        <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>

    </div>
</div>
<div class="modal-body">
    <div class="form-body">

        <div class="row">
            <div class="col-md-12">
                <ul class="padding-left-0">
                    
                    <li class="list-style-item-none display-inline-block margin-top-10">
                        <span class="label label-md bold label-red-soft">@lang('label.TOTAL_MISMATCH') : {!! !empty($productStatusArr['mismatch']) ? $productStatusArr['mismatch'] : 0 !!}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover table-head-fixer-color">
                        <thead>
                            <tr>
                                <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                                <th class="vcenter" rowspan="2">@lang('label.PRODUCT_CATEGORY')</th>
                                <th class="vcenter" rowspan="2">@lang('label.NAME')</th>
                                <th class="vcenter" rowspan="2">@lang('label.PRODUCT_CODE')</th>
                                <th class="text-center" colspan="2">@lang('label.QUANTITY') (@lang('label.STOCK'))</th>
                                <th class="text-center" colspan="2">@lang('label.QUANTITY') (@lang('label.LEDGER'))</th>
                            </tr>
                            <tr>
                                <th class="text-center vcenter"><strong>(@lang('label.IN_KG'))</strong></th>
                                <th class="text-center vcenter"><strong>(@lang('label.DETAILS'))</strong></th>
                                <th class="text-center vcenter"><strong>(@lang('label.IN_KG'))</strong></th>
                                <th class="text-center vcenter"><strong>(@lang('label.DETAILS'))</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$targetArr->isEmpty())
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($targetArr as $target)
                            <?php
                            $availableQuantity = !empty($target->available_quantity) ? Helper::numberFormat($target->available_quantity, 6) : Helper::numberFormat(0, 6);
                            $balanceQuantity = !empty($balanceArr[$target->id]['quantity']) ? Helper::numberFormat($balanceArr[$target->id]['quantity'], 6) : Helper::numberFormat(0, 6);
                            ?>
                            @if($availableQuantity != $balanceQuantity)
                            <tr>
                                <td class="text-center vcenter">{!! ++$sl !!}</td>
                                <td class="vcenter">{!! $target->product_category !!}</td>
                                <td class="vcenter">{!! $target->product !!}</td>
                                <td class="vcenter">{!! $target->product_code !!}</td>
                                <td class="text-right vcenter">{!! $availableQuantity !!}</td>
                                <td class="text-right vcenter">{!! Helper::unitConversion($availableQuantity) !!}</td>
                                <td class="text-right vcenter">{!! $balanceQuantity !!}</td>
                                <td class="text-right vcenter">{!! Helper::unitConversion($balanceQuantity) !!}</td>

                            </tr>
                            @endif
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
</div>