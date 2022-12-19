<div class="modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.VIEW_DEMAND_LETTER_DETAILS')</strong></h4>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>
        @if(!empty($userAccessArr[48][6]))
        <a href="{{ URL::to('demand/getDetails/'.$target->id.'?view=print') }}" target="_blank" class="btn btn-md green-meadow pull-right margin-right-10">
            <i class="fa fa-print text-white"></i> @lang('label.PRINT')
        </a>
        @endif
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <img src="{{URL::to('/')}}/public/img/Sterling_Laundry_Logo.png" alt="sterling-laundry-logo"/>
                        <h6 class="bold">@lang('label.DEMAND_PRINT_HEADER_TWO')</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h4 class="bold">@lang('label.DEMAND_PAPER_OF_CHEMICALS')</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3 class="bold">@lang('label.TOKEN_NO') :  <span class="badge-btn badge-danger bold">{!! $target->token_no !!}</span></h3>
                    </div>
                </div>

                <table class="table table-bordered table-hover">
                    <tr class="info">
                        <td class="vcenter bold" colspan="2">@lang('label.BATCH_CARD_NO') : {!! $target->batch_card !!}</td>
                        <td class="vcenter bold" >@lang('label.RECIPE_NO') : {!! $target->recipe_no !!}</td>
                        <td class="vcenter bold" >@lang('label.SHIFT') : {!! $target->shift !!}</td>
                    </tr>

                    <tr>
                        <td class="vcenter" width="25%">@lang('label.DATE')</td>
                        <td class="vcenter" width="25%">{!! $target->date !!}</td>
                        <td class="vcenter" width="25%">@lang('label.MACHINE_NO')</td>
                        <td class="vcenter" width="25%">{!! $target->machine_no !!}</td>
                    </tr>
                    <tr>
                        <td class="vcenter">@lang('label.STYLE')</td>
                        <td class="vcenter"><strong>{!! $target->style !!}</strong></td>
                        <td class="vcenter">@lang('label.FACTORY')</td>
                        <td class="vcenter"><strong>{!! $target->factory !!}</strong></td>
                    </tr>
                    <tr>
                        <td class="vcenter">@lang('label.BUYER')</td>
                        <td class="vcenter">{!! $target->buyer !!}</td>
                        <td class="vcenter">@lang('label.GARMENTS_TYPE')</td>
                        <td class="vcenter">{!! $target->garments_type !!}</td>
                    </tr>

                    <tr>
                        <td class="vcenter">@lang('label.WASH_LOAD_QUANTITY_IN_KG_N_IN_PCS')</td>
                        <td class="vcenter"><strong>{!! $target->wash_lot_quantity_weight !!} &amp; {!!$target->wash_lot_quantity_piece !!}</strong></td>
                        <td class="vcenter">@lang('label.STATUS')</td>
                        <td class="vcenter">
                            @if($target->status == '1')
                            <span class="label label-sm label-success">{{$statusArr[$target->status]}}</span>
                            @else
                            <span class="label label-sm label-warning">{{$statusArr[$target->status]}}</span>
                            @endif
                        </td>
                    </tr>
                </table>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="info">
                            <th class="text-center vcenter bold" rowspan="2">@lang('label.SL_NO')</th>
                            <th class="vcenter bold" rowspan="2">@lang('label.NAME_OF_CHEMICALS')</th>
                            <th class="vcenter text-center bold" rowspan="2">@lang('label.DELIVERABLE_FROM_STORE')</th>
                            <th class="vcenter text-center bold" rowspan="2">@lang('label.QUANTITY_IN_KG')</th>
                            <th class="vcenter text-center bold" rowspan="2">@lang('label.QUANTITY')&nbsp;@lang('label.DETAILS')</th>
                            @if($target->status == '1')
                            <th class="vcenter text-center bold" colspan="6">@lang('label.LOT_INFORMATION')</th>
                            @endif
                        </tr>
                        @if(!empty($productWithLotArr) && ($target->status == '1'))
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
                        @if (!$productArr->isEmpty())
                        <?php
                        $sl = 0;
                        ?>
                        @foreach($productArr as $product)
                        <?php
                        $deliveredFromSubstore = ($product->show_in_report == '1') ? 'line-cut' : '';
                        $rowspan = (isset($productWithLotArr[$product->id]) && !empty($productWithLotArr[$product->id])) ? sizeof($productWithLotArr[$product->id]) : '1';
                        ?>
                        <tr>
                            <td class="vcenter text-center {{  $deliveredFromSubstore }}" rowspan="{!! $rowspan !!}">{{ ++$sl }}</td>
                            <td class="vcenter {{  $deliveredFromSubstore }}" rowspan="{!! $rowspan !!}">{{ $product->name }}</td>
                            <td class="vcenter text-center" rowspan="{!! $rowspan !!}">
                                {!! ($product->show_in_report == '1') ? '<span class="label label-danger">No</span>' : '<span class="label label-info">Yes</span>' !!}
                            </td>
                            <td class="vcenter text-right {{  $deliveredFromSubstore }}" rowspan="{!! $rowspan !!}">{{ Helper::numberformat($product->total_qty, 6) }}</td>
                            <td class="vcenter text-right {{  $deliveredFromSubstore }}" rowspan="{!! $rowspan !!}">{{ Helper::unitConversion($product->total_qty) }}</td>

                            @if($target->status == '1')
                            <?php
                            $lotSl = 0;
                            if ($rowspan > 1) { //If Product is consumed from Multiple Lot
                                ?> 

                                @foreach($productWithLotArr[$product->id] as $lotInfo)
                                @if($lotSl == 0)
                                <td class="text-center vcenter">{!! ++$lotSl !!}</td>
                                <td class="text-center vcenter">{!! $lotInfo['lot_number'] !!}</td>
                                <td class="text-center vcenter">{!! Helper::numberformat($lotInfo['quantity'],6) !!}</td>
                                <td class="text-center vcenter">{!! Helper::unitConversion($lotInfo['quantity']) !!}</td>
                                <td class="text-center vcenter">{!! $lotInfo['rate'] !!}</td>
                                <td class="text-center vcenter">{!! Helper::numberformat($lotInfo['amount']) !!}</td>
                            </tr>
                            @else
                            <tr>
                                <td class="text-center vcenter">{!! ++$lotSl !!}</td>
                                <td class="text-center vcenter">{!! $lotInfo['lot_number'] !!}</td>
                                <td class="text-center vcenter">{!! Helper::numberformat($lotInfo['quantity']) !!}</td>
                                <td class="text-center vcenter">{!! Helper::unitConversion($lotInfo['quantity']) !!}</td>
                                <td class="text-center vcenter">{!! $lotInfo['rate'] !!}</td>
                                <td class="text-center vcenter">{!! Helper::numberformat($lotInfo['amount']) !!}</td>
                            </tr>
                            @endif
                            @endforeach
                            <?php
                        } else { //If Product is consumed from Single Lot OR NO Lot no is found (In case of product marked as "Don't show at store")
                            ?>                       
                        <td class="text-center vcenter">{!! ++$lotSl !!}</td>
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$product->id][0]['lot_number'])? $productWithLotArr[$product->id][0]['lot_number'] : '--'  !!}</td>
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$product->id][0]['quantity'])? Helper::numberformat($productWithLotArr[$product->id][0]['quantity'],6) : '--' !!}</td>
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$product->id][0]['quantity'])? Helper::unitConversion($productWithLotArr[$product->id][0]['quantity']) : '--' !!}</td>
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$product->id][0]['rate'])? $productWithLotArr[$product->id][0]['rate']: '--' !!}</td>
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$product->id][0]['amount'])? Helper::numberformat($productWithLotArr[$product->id][0]['amount']) : '--' !!}</td>
                        </tr>                        
                        <?php
                    }
                    ?>
                    @else
                    </tr>
                    @endif
                    @endforeach
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