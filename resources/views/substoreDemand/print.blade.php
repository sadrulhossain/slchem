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
        <div class="row">
            <div class="col-md-12 text-center">
                <h4 class="bold">@lang('label.DEMAND_PAPER_OF_SUBSTORE_PRODUCT')</h4>
            </div>
        </div>
        </br>
        <table class="no-border">
            <thead>        
                <tr class="info">
                    <td class="vcenter" colspan="2"> <strong>@lang('label.DATE_OF_SUBSTORE')</strong> : {!! $demandInfo['adjustment_date'] !!}</td>
                    <td class="vcenter"  colspan="2"><strong>@lang('label.REFERENCE_NO')</strong> : {!! $demandInfo['voucher_no'] !!}</td>
                </tr>
                <tr>
                    <td width="25%"><strong>@lang('label.GENERATED_BY')</strong></td>
                    <td width="25%">{!! $userArr[$demandInfo['created_by']] !!}</td>
                    <td width="25%"><strong>@lang('label.GENERATED_AT')</strong></td>
                    <td width="25%">{!! Helper::printDateFormat($demandInfo['created_at']) !!}</td>
                </tr>
                @if($demandInfo['delivered'] == '1')
                <tr>
                    <td class="vcenter"><strong>@lang('label.DELIVERED_BY')</strong></td>
                    <td class="vcenter">{!! $userArr[$demandInfo['delivered_by']] !!}</td>
                    <td class="vcenter"><strong>@lang('label.DELIVERED_AT')</strong></td>
                    <td class="vcenter">{!! Helper::printDateFormat($demandInfo['delivered_at']) !!}</td>
                </tr>
                @endif
        </table>
        <br/>
        <br/>
        <table class="no-border">
            <thead>
                <tr class="info">
                    <th class="text-center vcenter" rowspan="2"><strong>@lang('label.SL_NO')</strong></th>
                    <th class="vcenter" rowspan="2"><strong>@lang('label.CATEGORY')</strong></th>
                    <th class="vcenter" rowspan="2"><strong>@lang('label.PRODUCT')</strong></th>
                    <th class="text-center vcenter" rowspan="2"><strong>@lang('label.QUANTITY')&nbsp; (@lang('label.IN_KG'))</strong></th>
                    <th class="text-center vcenter" rowspan="2"><strong>@lang('label.QUANTITY')&nbsp; (@lang('label.DETAILS'))</strong></th>
                    @if($demandInfo['delivered'] == '1')
                    <th class="vcenter text-center bold" colspan="6">@lang('label.LOT_INFORMATION')</th>
                    @endif
                </tr>
                @if(!empty($productWithLotArr) && ($demandInfo['delivered'] == '1'))
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
                @if (!$demandDetailsArr->isEmpty())
                <?php
                $sl = 0;
                ?>
                @foreach($demandDetailsArr as $item)
                <?php
                $rowspan = ($demandInfo['delivered'] == '1') ? (isset($productWithLotArr[$item->product_id][$item->id]) && !empty($productWithLotArr[$item->product_id][$item->id])) ? sizeof($productWithLotArr[$item->product_id][$item->id]) : '1' : '1';
                ?>
                <tr>
                    <td class="text-center vcenter" rowspan="{!! $rowspan !!}">{!! ++$sl !!}</td>
                    <td class="vcenter" rowspan="{!! $rowspan !!}">{!! $item->category_name !!}</td>
                    <td class="vcenter" rowspan="{!! $rowspan !!}">{!! $item->product_name !!}</td>
                    <td class="text-center vcenter" rowspan="{!! $rowspan !!}">{!! $item->quantity !!}</td>
                    <td class="text-center vcenter" rowspan="{!! $rowspan !!}">{!! Helper::unitConversion($item->quantity) !!}</td>
                    @if($demandInfo['delivered'] == '1')
                    <?php
                    $lotSl = 0;
                    if ($rowspan >= 1) { //If Product is consumed from Multiple Lot
                        ?> 
                        @foreach($productWithLotArr[$item->product_id][$item->id] as $lotInfo)
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
                } else { //If Product is consumed from Single Lot OR NO Lot no is found (In case of product marked as "Don't show at store")
                    ?>                       
                <td class="text-center vcenter">{!! ++$lotSl !!}</td>
                <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['lot_number'])? $productWithLotArr[$product->id][$item->id]['lot_number'] : '--'  !!}</td>
                <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['quantity'])? Helper::numberformat($productWithLotArr[$item->product_id][$item->id]['quantity'],6) : '--' !!}</td>
                <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['quantity'])? Helper::unitConversion($productWithLotArr[$item->product_id][$item->id]['quantity']) : '--' !!}</td>
                <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['rate'])? $productWithLotArr[$item->product_id][$item->id]['rate']: '--' !!}</td>
                <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][$item->id]['amount'])? Helper::numberformat($productWithLotArr[$item->product_id][$item->id]['amount']) : '--' !!}</td>
            </tr>                           
            <?php
        }
        ?>
        @else
    </tr>
    @endif
    @endforeach
    @else
    <tr>
        <td colspan="6">@lang('label.EMPTY_DATA')</td>
    </tr>
    @endif 
</tbody>
</table>
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