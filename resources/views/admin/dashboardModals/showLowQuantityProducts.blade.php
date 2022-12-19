<div class="modal-header clone-modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.LOW_QUANTITY_PRODUCTS')</strong></h4>
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
                        <span class="label label-md bold label-blue-steel">@lang('label.LOW_QUANTITY_PRODUCTS') : {!! !empty($lowQuantityProductsCount) ? $lowQuantityProductsCount : 0 !!}</span>
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
                                <th class="text-center vcenter"rowspan="2">@lang('label.SL_NO')</th>
                                <th class="vcenter" rowspan="2">@lang('label.PRODUCT_CATEGORY')</th>
                                <th class="vcenter" rowspan="2">@lang('label.PRODUCT_FUNCTION')</th>
                                <th class="vcenter" rowspan="2">@lang('label.NAME')</th>
                                <th class="text-center" rowspan="2">@lang('label.PRODUCT_CODE')</th>
                                
                                <th class="text-center" colspan="2">@lang('label.AVAILABLE_QUANTITY')</th>
                                <th class="text-center" colspan="2">@lang('label.REORDER_LEVEL')</th>
                                
                                <th class="text-center vcenter" rowspan="2">@lang('label.STATUS')</th>
                                <th class="vcenter" rowspan="2">@lang('label.CREATED_BY')</th>
                                <th class="vcenter" rowspan="2">@lang('label.APPROVED_BY')</th>
                                <th class="text-center vcenter" rowspan="2">@lang('label.APPROVED_AT')</th>
                            </tr>
                            <tr>
                                <th class="text-center vcenter">@lang('label.IN_KG')</th>
                                <th class="text-center vcenter">@lang('label.DETAILS')</th>
                                <th class="text-center vcenter">@lang('label.IN_KG')</th>
                                <th class="text-center vcenter">@lang('label.DETAILS')</th>
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
                                <td class="vcenter">{!! $target->product_category !!}</td>
                                <td class="vcenter">{!! $target->product_function !!}</td>
                                <td class="vcenter">{!! $target->name !!}</td>
                                <td class="text-center vcenter">{!! $target->product_code !!}</td>
                                <td class="text-center vcenter">{!! $target->available_quantity !!}</td>
                                <td class="text-center vcenter">{!! Helper::unitConversion($target->available_quantity) !!}</td>
                                <td class="text-center vcenter">{!! $target->reorder_level !!}</td>
                                <td class="text-center vcenter">{!! Helper::unitConversion($target->reorder_level) !!}</td>
                                
                                <td class="text-center vcenter">
                                    @if($target->status == '1')
                                    <span class="label label-sm label-success">@lang('label.ACTIVE')</span>
                                    @else
                                    <span class="label label-sm label-warning">@lang('label.INACTIVE')</span>
                                    @endif
                                </td>
                                <td class="text-center vcenter">{!! $userFirstNameArr[$target->created_by].' '.$userLastNameArr[$target->created_by] !!}</td>
                                
                                <td class="text-center vcenter">{!! (!empty($target->approved_by)) ?  $userFirstNameArr[$target->approved_by].' '.$userLastNameArr[$target->approved_by] : '--' !!}</td>
                                <td class="text-center vcenter">{!! (is_null($target->approved_at)) ? '--' : Helper::printDateFormat($target->approved_at) !!}</td>
                               
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="12" class="vcenter">@lang('label.NO_PRODUCT_FOUND')</td>
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