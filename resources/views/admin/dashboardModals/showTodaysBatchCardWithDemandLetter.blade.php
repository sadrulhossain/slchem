<div class="modal-header clone-modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.TODAYS_ BATCH_CARD_WITH_DEMAND_LETTER')</strong></h4>
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
                        <span class="label label-md bold label-blue-steel">@lang('label.TODAYS_ BATCH_CARD_WITH_DEMAND_LETTER') : {!! !empty($todaysTotalBatchCardWithDemandLetterCount) ? $todaysTotalBatchCardWithDemandLetterCount : 0 !!}</span>
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
                                <th class="vcenter">@lang('label.BATCH_CARD_NO')</th>
                                <th class="vcenter">@lang('label.MACHINE_NO')</th>
                                <th class="vcenter">@lang('label.DATE')</th>
                                <th class="vcenter">@lang('label.STYLE')</th>
                                <th class="vcenter">@lang('label.BUYER')</th>
                                <th class="vcenter">@lang('label.GARMENTS_TYPE')</th>
                                <th class="vcenter">@lang('label.TOKEN_NO')</th>
                                <th class="vcenter text-center">@lang('label.DELIVERED_BY')</th>
                                <th class="text-center vcenter">@lang('label.DELIVERED_AT')</th>
                                <th class="text-center vcenter">@lang('label.DEMAND_LETTER_STATUS')</th>
                                <th class="text-center vcenter">@lang('label.STATUS')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($batchCardDemandArr))                        
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($batchCardDemandArr as $batchCardId => $batchCardInfo)
                            <tr>
                                <td class="vcenter text-center" rowspan="{{!empty($rowSpanArr[$batchCardId]) ? $rowSpanArr[$batchCardId] : 1}}">{{ ++$sl }}</td>
                                <td class="vcenter" rowspan="{{!empty($rowSpanArr[$batchCardId]) ? $rowSpanArr[$batchCardId] : 1}}">{{ $batchCardInfo['batch_card'] }}</td>
                                <td class="vcenter" rowspan="{{!empty($rowSpanArr[$batchCardId]) ? $rowSpanArr[$batchCardId] : 1}}">{{ $batchCardInfo['machine_no'] }}</td>
                                <td class="vcenter" rowspan="{{!empty($rowSpanArr[$batchCardId]) ? $rowSpanArr[$batchCardId] : 1}}">{{ $batchCardInfo['date'] }}</td>
                                <td class="vcenter" rowspan="{{!empty($rowSpanArr[$batchCardId]) ? $rowSpanArr[$batchCardId] : 1}}">{{ $batchCardInfo['style'] }}</td>
                                <td class="vcenter" rowspan="{{!empty($rowSpanArr[$batchCardId]) ? $rowSpanArr[$batchCardId] : 1}}">{{ $batchCardInfo['buyer'] }}</td>
                                <td class="vcenter" rowspan="{{!empty($rowSpanArr[$batchCardId]) ? $rowSpanArr[$batchCardId] : 1}}">{{ $batchCardInfo['garments_type'] }}</td>
                                @if(!empty($batchCardInfo['demand']))
                                <?php $i = 0; ?>
                                @foreach($batchCardInfo['demand'] as $demandId => $demandInfo)
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter text-center">
                                    <span class="label label-danger">{{ $demandInfo['token_no'] }}</span>
                                </td>
                                @if (!empty($demandInfo['delivered_by']))
                                <td class="text-center vcenter">{!! $userFirstNameArr[$demandInfo['delivered_by']].' '.$userLastNameArr[$demandInfo['delivered_by']] !!}</td>
                                @else
                                <td class="text-center vcenter">---</td>
                                @endif

                                @if (!empty($demandInfo['delivered_at']))
                                <td class="text-center vcenter">{{Helper::printDateFormat($demandInfo['delivered_at']) }}</td>
                                @else
                                <td class="text-center vcenter">---</td>
                                @endif

                                <td class="vcenter text-center">
                                    @if($demandInfo['demand_status'] == '1' )
                                    <span class="label label-sm label-success">@lang('label.DELIVERED')</span>
                                    @else
                                    <span class="label label-sm label-warning">@lang('label.DEMAND_GENERATED')</span>
                                    @endif
                                </td>

                                @if($i == 0)
                                <td class="vcenter text-center" rowspan="{{!empty($rowSpanArr[$batchCardId]) ? $rowSpanArr[$batchCardId] : 1}}">
                                     @if($batchCardInfo['batch_status'] > 0)
                                     @if($batchCardInfo['batch_status'] == $rowSpanArr[$batchCardId])
                                     <span class="label label-sm label-success">@lang('label.DELIVERED')</span>
                                     @else
                                     <span class="label label-sm label-info">@lang('label.IN_PROGRESS')</span>
                                     @endif
                                     @elseif($batchCardInfo['batch_status'] == 0)
                                     <span class="label label-sm label-warning">@lang('label.NOT_DELIVERED')</span>
                                     @endif
                                </td>
                                @endif

                                <?php
                                if ($i < ($rowSpanArr[$batchCardId] - 1)) {
                                    echo '</tr>';
                                }
                                $i++;
                                ?>
                                @endforeach
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