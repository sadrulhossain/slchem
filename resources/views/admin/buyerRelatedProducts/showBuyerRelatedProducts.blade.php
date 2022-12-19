<div class="modal-header clone-modal-header">
    <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLICK_HERE_TO_CLOSE')">@lang('label.CLOSE')</button>
    <h3 class="modal-title text-center">
        @lang('label.RELATED_PRODUCTS_OF', ['name' => $buyerInfo->name])
    </h3>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive webkit-scrollbar" style="max-height: 600px;">
                <table class="table table-bordered table-hover table-head-fixer-color">
                    <thead>
                        <tr>
                            <th class="text-center vcenter">@lang('label.SL_NO')</th>
                            <th class="vcenter">@lang('label.PRODUCT_FUNCTION')</th>
                            <th class="vcenter">@lang('label.PRODUCT_CATEGORY')</th>
                            <th class="vcenter">@lang('label.PRODUCT')</th>
                            <th class="text-center">@lang('label.RSL')</th>
                            <th class="text-center">@lang('label.MRSL')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($productInfoArr))
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($productInfoArr as $item)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $item['product_function'] ?? __('label.N_A') !!}</td>
                            <td class="vcenter">{!! $item['product_category'] ?? __('label.N_A') !!}</td>
                            <td class="vcenter">{!! $item['product_name'] ?? __('label.N_A') !!}</td>
                            <td class="text-center vcenter">
                                @if($item['rsl'] == '1')
                                <span class="label label-sm label-success">@lang('label.Y')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.N')</span>
                                @endif
                            </td>
                            <td class="text-center vcenter">
                                @if($item['mrsl'] == '1')
                                <span class="label label-sm label-success">@lang('label.Y')</span>
                                @else
                                <span class="label label-sm label-warning">@lang('label.N')</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="vcenter">@lang('label.NO_DATA_FOUND')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>	
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" data-dismiss="modal" data-placement="left" class="btn btn-outline grey-mint pull-right tooltips" title="@lang('label.CLICK_HERE_TO_CLOSE')">@lang('label.CLOSE')</button>
</div>
