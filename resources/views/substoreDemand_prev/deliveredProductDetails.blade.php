<div class="modal-header">
    <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>
    @if(!empty($userAccessArr[53][6]))
    <a href="{{ URL::to('deliveredDemandList/getProductDetails/'.$demandInfo['id'].'?view=print') }}" target="_blank" class="btn btn-md green-meadow pull-right margin-right-10">
        <i class="fa fa-print text-white"></i> @lang('label.PRINT')
    </a>
    @endif
    <h4 class="modal-title"><strong>@lang('label.VIEW_PRODUCT_DETAILS')</strong></h4>
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
                        <h4 class="bold">@lang('label.DEMAND_PAPER_OF_SUBSTORE_PRODUCT')</h4>
                    </div>
                </div>
                </br>

                <table class="table table-bordered table-hover">
                    <tr class="info">
                        <td class="vcenter bold" colspan="2"><strong>@lang('label.DATE_OF_SUBSTORE')</strong>:  {!! $demandInfo['adjustment_date'] !!}</td>
                        <td class="vcenter bold" colspan="2"><strong>@lang('label.REFERENCE_NO')</strong>: {!! $demandInfo['voucher_no'] !!}</td>
                    </tr>
                    <tr>
                        <td class="vcenter"><strong>@lang('label.GENERATED_BY')</strong></td>
                        <td class="vcenter">{!! $userArr[$demandInfo['created_by']] !!}</td>
                        <td class="vcenter"><strong>@lang('label.GENERATED_AT')</strong></td>
                        <td class="vcenter">{!! Helper::printDateFormat($demandInfo['created_at']) !!}</td>
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
                <table class="table table-striped table-bordered">
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
                        $rowspan = ($demandInfo['delivered'] == '1') ? (isset($productWithLotArr[$item->product_id]) && !empty($productWithLotArr[$item->product_id])) ? sizeof($productWithLotArr[$item->product_id]) : '1' : '1';
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
                            if ($rowspan > 1) { //If Product is consumed from Multiple Lot
                                ?> 
                                @foreach($productWithLotArr[$item->product_id] as $lotInfo)
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
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][0]['lot_number'])? $productWithLotArr[$item->product_id][0]['lot_number'] : '--'  !!}</td>
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][0]['quantity'])? Helper::numberformat($productWithLotArr[$item->product_id][0]['quantity'],6) : '--' !!}</td>
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][0]['quantity'])? Helper::unitConversion($productWithLotArr[$item->product_id][0]['quantity']) : '--' !!}</td>
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][0]['rate'])? $productWithLotArr[$item->product_id][0]['rate']: '--' !!}</td>
                        <td class="text-center vcenter">{!! isset($productWithLotArr[$item->product_id][0]['amount'])? Helper::numberformat($productWithLotArr[$item->product_id][0]['amount']) : '--' !!}</td>
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
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
</div>

<!-- Get Lot Wise Product Adjustment Details in modal -->
<div class="modal container fade" id="adjustDetails" aria-hidden="true">

    <div class="modal-header">
        <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>
        <h4 class="modal-title"><strong>@lang('label.VIEW_LOT_WISE_PRODUCT_DETAILS')</strong></h4>
    </div>
    <div class="modal-body" id="showAdjustDetails">
    </div>
    <div class="modal-footer">
        <a type="button" class="btn dark btn-outline" id="closeAdjustDetails">@lang('label.CLOSE')</a>
    </div>
</div>
<!-- End of modal -->
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).on('click', '#closeAdjustDetails', function () {
    $('#adjustDetails').modal('hide');
});
$(document).on('click', '.lot-wise-details-btn', function () {

    var consumptionDetailsId = $(this).attr("data-id");
    $.ajax({
        url: "{{URL::to('productConsumption/getLotWiseProductDetails')}}",
        type: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            details_id: consumptionDetailsId
        },
        beforeSend: function () {
            App.blockUI({boxed: true});
        },
        success: function (res) {
            $('#showAdjustDetails').html(res.html);
            App.unblockUI();
        },
    });
});

function convertedUnit(totalQty) {
    var totalQtyArr = totalQty.toString().split(".");
    var kgAmnt = totalQtyArr[0];
    var gmAmntStr = totalQtyArr[1];
    var kgFinalAmntStr = '';
    if (kgAmnt > 0) {
        kgFinalAmntStr = parseInt(kgAmnt) + " @lang('label.UNIT_KG')";
    }

    //var lengthOfGm = gmAmntStr.length;//length of amount after decimal point
    //var zeroPadLength = (6 - (lengthOfGm)); //6 is fixed as 1KG is equal to 1000000 mg (0.000001 KG => 6 digit after decimal point)
    var pad = '000000';
    var totalAmntStr = (gmAmntStr + pad).substring(0, pad.length);
    var gmStr = totalAmntStr.substring(0, 3);//Subtract gram aamount
    var gmFinalAmntStr = "";
    if (gmStr > 0) {
        gmFinalAmntStr = parseInt(gmStr) + " @lang('label.GM')";
    }
    var miliGmStr = totalAmntStr.substring(3, 6);//Subtract miligram aamount
    var mgFinalAmntStr = "";
    if (miliGmStr > 0) {
        mgFinalAmntStr = parseInt(miliGmStr) + " @lang('label.MG')";
    }

    var text = kgFinalAmntStr + " " + gmFinalAmntStr + " " + mgFinalAmntStr;

    return text;
}



</script> 