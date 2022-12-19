<div class="modal-header clone-modal-header">
    <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLICK_HERE_TO_CLOSE')">@lang('label.CLOSE')</button>
    <h3 class="modal-title text-center">
        @lang('label.RELATED_PRODUCTS_OF', ['name' => $certificateInfo->name])
    </h3>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive webkit-scrollbar" style="max-height: 600px;">
                <table class="table table-bordered table-hover table-head-fixer-color">
                    <thead>
                        <tr>
                            <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                            <th class="vcenter" rowspan="2">@lang('label.PRODUCT_FUNCTION')</th>
                            <th class="vcenter" rowspan="2">@lang('label.PRODUCT_CATEGORY')</th>
                            <th class="vcenter" rowspan="2">@lang('label.PRODUCT')</th>
                            <th class="text-center" colspan="2">@lang('label.QUANTITY') (@lang('label.STOCK'))</th>
                        </tr>
                        <tr>
                            <th class="text-center vcenter"><strong>(@lang('label.IN_KG'))</strong></th>
                            <th class="text-center vcenter"><strong>(@lang('label.DETAILS'))</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$productIdArr->isEmpty())
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($productIdArr as $item)
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $item->product_function ?? __('label.N_A') !!}</td>
                            <td class="vcenter">{!! $item->product_category ?? __('label.N_A') !!}</td>
                            <td class="vcenter">{!! $item->product_name ?? __('label.N_A') !!}</td>
                            <td class="vcenter">{!! $item->available_quantity !!}</td>
                            <td class="vcenter">{!! Helper::unitConversion($item->available_quantity) !!}</td>
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
