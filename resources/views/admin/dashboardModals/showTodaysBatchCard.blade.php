<div class="modal-header clone-modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.TODAYS_BATCH_CARD')</strong></h4>
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
                        <span class="label label-md bold label-blue-steel">@lang('label.TODAYS_BATCH_CARD') : {!! !empty($todaysBatchCardCount) ? $todaysBatchCardCount : 0 !!}</span>
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
                                <th class=" vcenter text-center">@lang('label.SL_NO')</th>
                                <th class=" vcenter text-center">@lang('label.DATE')</th>
                                <th class=" vcenter text-center">@lang('label.TIME')</th>
                                <th class=" vcenter text-center" width="120">@lang('label.REFERENCE_NO')</th>
                                <th class=" vcenter text-center">@lang('label.RECIPE')</th>
                                <th class=" vcenter text-center">@lang('label.STYLE')</th>
                                <th class=" vcenter text-center">@lang('label.SEASON')</th>
                                <th class=" vcenter text-center">@lang('label.COLOR')</th>
                                <th class=" vcenter text-center">@lang('label.WASH_TYPE')</th>
                                <th class=" vcenter text-center">@lang('label.WASH_MC_NO')</th>
                                <th class=" vcenter">@lang('label.OPERATOR_NAME')</th>
                                <th class=" vcenter text-center">@lang('label.SHIFT_NAME')</th>
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
                                <td class="vcenter vcenter text-center">{{ ++$sl }}</td>
                                <td class=" vcenter text-center">{{ Helper::dateFormat($target->date) }}</td>
                                <td class=" vcenter text-center">{{ Helper::dateFormat($target->created_at) }}<br />{{ date('h:iA',strtotime($target->created_at)) }}</td>
                                <td class=" vcenter text-center">{{ $target->reference_no }}</td>
                                <td class=" vcenter text-center">{{ $target->recipe_reference_no }}</td>
                                <td class=" vcenter text-center">{{ $target->style }}</td>
                                <td class=" vcenter text-center">{{ $target->season }}</td>
                                <td class=" vcenter text-center">{{ $target->color }}</td>
                                <td class=" vcenter text-center">{{ !empty($target->wash_type_id)? $washTypeArr[$target->wash_type_id]: '' }}</td>
                                <td class=" vcenter text-center">{{ $target->Machine->machine_no }}</td>
                                <td class=" vcenter">{{ $target->operator_name }}</td>
                                <td class=" vcenter text-center">{{ !empty($target->shift_id)?$shiftArr[$target->shift_id]:'' }}</td>

                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="15" class="vcenter">@lang('label.NO_BATCH_CARD_FOUND')</td>
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