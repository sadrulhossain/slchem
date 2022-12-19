<html>

    <head>
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="{{URL::to('/')}}/public/img/Sterling_Laundry_Logo.png" alt="sterling-laundry-logo"/><br/>
                @lang('label.DEMAND_PRINT_HEADER_TWO')
            </div>
        </div>

        <div class="col-md-12 text-center">
            <h4 class="bold">@lang('label.DEMAND_PAPER_OF_CHEMICALS')</h4>
        </div>

        <div class="row">
            <div class="demand-letter-offset demand-letter">
                <table class="table table-bordered demand-token box-size">
                    <tr class="text-center">
                        <td class="demand-letter-offset demand-letter"><strong>@lang('label.TOKEN_NO') : {!! $target->token_no !!}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <table class="no-border">
            <thead>        
                <tr class="info">
                    <td class="vcenter" colspan="2"><strong>@lang('label.BATCH_CARD_NO') : {!! $target->batch_card !!}</strong></td>
                    <td class="vcenter"><strong>@lang('label.RECIPE_NO') : {!! $target->recipe_no !!}</strong></td>
                    <td class="vcenter"><strong>@lang('label.SHIFT') : {!! $target->shift !!}</strong></td>
                </tr>
                <tr>
                    <td width="25%">@lang('label.DATE')</td>
                    <td width="25%">{!! $target->date !!}</td>
                    <td width="25%">@lang('label.MACHINE_NO')</td>
                    <td width="25%">{!! $target->machine_no !!}</td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.STYLE')</td>
                    <td class="vcenter"><strong>{!! $target->style !!}</strong></td>
                    <td class="vcenter">@lang('label.FACTORY')</td>
                    <td class="vcenter"><strong>{!! $target->factory !!}</strong></td>
                </tr>
                <tr>
                    <td>@lang('label.BUYER')</td>
                    <td class="vcenter">{!! $target->buyer !!}</td>
                    <td>@lang('label.GARMENTS_TYPE')</td>
                    <td>{!! $target->garments_type !!}</td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.WASH_LOAD_QUANTITY_IN_KG_N_IN_PCS')</td>
                    <td class="vcenter"><strong>{!! $target->wash_lot_quantity_weight !!} &amp; {!!$target->wash_lot_quantity_piece !!}</strong></td>
                    <td>@lang('label.STATUS')</td>
                    <td><span class ="label-warning">{{$statusArr[$target->status]}}</span></td>
                </tr>
        </table>
        <table class="no-border">
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
                    <th class="text-center vcenter"><strong>@lang('label.SL_NO')</strong></th>
                    <th class="vcenter"><strong>@lang('label.LOT_NUMBER')</strong></th>
                    <th class="text-center vcenter"><strong>@lang('label.QUANTITY')</strong></th>
                    <th class="vcenter text-center">@lang('label.QUANTITY')&nbsp;@lang('label.DETAILS')</th>
                    <th class="text-center vcenter"><strong>@lang('label.RATE')</strong></th>
                    <th class="text-center vcenter"><strong>@lang('label.AMOUNT')</strong></th>
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
                    $deliveredFromSubstore = ($product->show_in_report == '1') ? 'line-cut':'';
                    $rowspan = (isset($productWithLotArr[$product->id]) && !empty($productWithLotArr[$product->id]))? sizeof($productWithLotArr[$product->id]) : '1';
                ?>
                <tr>
                    <td class="vcenter text-center {{  $deliveredFromSubstore }}" rowspan="{!! $rowspan !!}">{{ ++$sl }}</td>
                    <td class="vcenter text-left {{  $deliveredFromSubstore }}" rowspan="{!! $rowspan !!}">{{ $product->name }}</td>
                    <td class="vcenter text-center" rowspan="{!! $rowspan !!}">
                        {!! ($product->show_in_report == '1') ? '<span class="label-danger">No</span>' : '<span class="label-success">Yes</span>' !!}
                    </td>
                    <td class="vcenter text-right {{  $deliveredFromSubstore }}" rowspan="{!! $rowspan !!}">{{ Helper::numberFormat($product->total_qty,6) }}</td>
                    <td class="vcenter text-right {{  $deliveredFromSubstore }}" rowspan="{!! $rowspan !!}">{{ Helper::unitConversion($product->total_qty) }}</td>
                
                @if($target->status == '1')
                <?php
                $lotSl = 0;
                if($rowspan > 1){ //If Product is consumed from Multiple Lot
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
                        <td class="text-center vcenter">{!! Helper::numberformat($lotInfo['quantity'],6) !!}</td>
                        <td class="text-center vcenter">{!! Helper::unitConversion($lotInfo['quantity']) !!}</td>
                        <td class="text-center vcenter">{!! $lotInfo['rate'] !!}</td>
                        <td class="text-center vcenter">{!! Helper::numberformat($lotInfo['amount']) !!}</td>
                    </tr>
                    @endif
                @endforeach
                <?php
                }else{ //If Product is consumed from Single Lot OR NO Lot no is found (In case of product marked as "Don't show at store")
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

        <table class="no-border">
            <tr>
                <td class="no-border text-left col-md-6">
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    -------------------------
                    <br/>
                    @lang('label.OFFICERS_SIGNATURE')
                </td>
                <td class="no-border text-right col-md-6">
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    -------------------------
                    <br/>
                    @lang('label.SUPERVISORS_SIGNATURE')
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <table class="no-border">
            <tr>
                <td class="no-border text-left col-xs-6">
                    @lang('label.PREPARED_ON')
                    {{ Helper::printDateFormat(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                </td>
                <td class="no-border text-right col-xs-6">
                    @lang('label.GENERATED_BY_RAJAKINI_SOFTWARE')
                    ,<span>&nbsp;@lang('label.POWERED_BY')</span><b>&nbsp;&nbsp;@lang('label.SWAPNOLOKE')</b>
                </td>

            </tr>
        </table>

        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html>