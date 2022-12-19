<div class="modal-header clone-modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.TODAYS_TOTAL_DEMAND_LETTER')</strong></h4>
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
                        <span class="label label-md bold label-blue-steel">@lang('label.TODAYS_TOTAL_DEMAND_LETTER') : {!! !empty($todaysTotalDemandLetterCount) ? $todaysTotalDemandLetterCount : 0 !!}</span>
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
                                <th class="vcenter text-center">@lang('label.SL_NO')</th>
                                <th class="vcenter text-center">@lang('label.TOKEN_NO')</th>
                                <th class="vcenter">@lang('label.BATCH_CARD_NO')</th>
                                <th class="vcenter">@lang('label.MACHINE_NO')</th>
                                <th class="vcenter">@lang('label.DATE')</th>
                                <th class="vcenter">@lang('label.STYLE')</th>
                                <th class="vcenter">@lang('label.BUYER')</th>
                                <th class="vcenter">@lang('label.GARMENTS_TYPE')</th>
                                <th class="vcenter text-center">@lang('label.DELIVERED_BY')</th>
                                <th class="text-center vcenter">@lang('label.DELIVERED_AT')</th>
                                <th class="text-center vcenter">@lang('label.STATUS')</th>
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
                            <?php
                            $checked = !empty(Session::get('demand_id.' . $target->id)) ? 'checked' : '';
                            $rowColor = ($target->status == 1) ? 'info' : 'green';
                            ?>
                            <tr class="{{ $rowColor }}">
                                <td class="vcenter text-center">{{ ++$sl }}</td>
                                <td class="vcenter text-center">
                                    <span class="label label-danger">{{ $target->token_no }}</span>
                                </td>
                                <td class="vcenter">{{ $target->batch_card }}</td>
                                <td class="vcenter">{{ $target->machine_no }}</td>
                                <td class="vcenter">{{ $target->date }}</td>
                                <td class="vcenter">{{ $target->style }}</td>
                                <td class="vcenter">{{ $target->buyer }}</td>
                                <td class="vcenter">{{ $target->garments_type }}</td>

                                @if (!empty($target->delivered_by))
                                <td class="text-center vcenter">{!! $userFirstNameArr[$target->delivered_by].' '.$userLastNameArr[$target->delivered_by] !!}</td>
                                @else
                                <td class="text-center vcenter">---</td>
                                @endif

                                @if (!empty($target->delivered_at))
                                <td class="text-center vcenter">{{Helper::printDateFormat($target->delivered_at) }}</td>
                                @else
                                <td class="text-center vcenter">---</td>
                                @endif

                                @if($target->status == 1 )
                                <td class="vcenter text-center">
                                    <span class="label label-sm label-success">@lang('label.DELIVERED')</span>
                                </td>
                                @else
                                <td class="vcenter text-center">
                                    <span class="label label-sm label-warning">@lang('label.DEMAND_GENERATED')</span>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="13">@lang('label.NO_DEMAND_PAPER_FOUND')</td>
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