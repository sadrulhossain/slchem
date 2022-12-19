<div class="modal-header clone-modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.TODAYS_DELIVERD_STORE_DEMAND_LETTER')</strong></h4>
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
                        <span class="label label-md bold label-blue-steel">@lang('label.TODAYS_DELIVERD_STORE_DEMAND_LETTER') : {!! !empty($todaysDeliverdStoreDemandLetterCount) ? $todaysDeliverdStoreDemandLetterCount : 0 !!}</span>
                    </li>

                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover table-head-fixer-color">

                        <thead>
                            <tr class="center">
                                <th class="text-center">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.DATE_OF_SUBSTORE')</th>
                                <th class="vcenter">@lang('label.REFERENCE_NO')</th>
                                <th class="vcenter">@lang('label.GENERATED_BY')</th>
                                <th class="vcenter">@lang('label.GENERATED_AT')</th>
                                <th class="vcenter">@lang('label.DELIVERED_BY')</th>
                                <th class="vcenter">@lang('label.DELIVERED_AT')</th>
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
                                <td class="vcenter">{!! $userArr[$target->delivered_by] !!}</td>
                                <td class="vcenter"> 
                                    {!! !empty($target->delivered_at) ? Helper::printDateFormat($target->delivered_at) : '---' !!}
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8">@lang('label.NO_DELIVERED_SUBSTORE_DEMAND_FOUND')</td>
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