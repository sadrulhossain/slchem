<?php
if ($processInfo->water == '1') {
    ?>
    <tr class="process-header-{!! $identifier . '-' . $processInfo->id !!} process-header">
        <td class="text-center vcenter process-counter" id="processHeader-{!! $identifier . '-' . $processInfo->id !!}">&nbsp</td>
        <td class="text-center vcenter" id="processHeader-{!! $identifier . '-' . $processInfo->id !!}"><span>{!! $processInfo->name !!}</span></td>
        {!! Form::hidden('qty[' . $identifier . '][' . $processInfo->id . ']', 'water', ['id' => 'qty-' . $identifier . '-' . $processInfo->id]) !!}
        <td class=" vcenter">
            @lang('label.WATER')
        </td>
        <td class="text-center vcenter">&nbsp;</td>
        <td class="text-center vcenter">&nbsp;</td>
        <td class="text-center vcenter">&nbsp;</td>
        <td class="text-center vcenter">&nbsp;</td>
        <td class="text-center vcenter">&nbsp;</td>
        <td class="text-right vcenter">
            {!! Form::text('water[' . $identifier . '][' . $processInfo->id . ']', null, ['id' => 'water-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control text-right i-am-water water']) !!}
        </td>
        <td class="text-center vcenter">&nbsp;</td>
        <td class="text-center vcenter">
            {!! Form::text('temperature[' . $identifier . '][' . $processInfo->id . ']', null, ['id' => 'temperature-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only','autocomplete' => 'off']) !!}
        </td>
        <td class="text-center vcenter">&nbsp;</td>
        <td class="text-center vcenter">
            {!! Form::text('time[' . $identifier . '][' . $processInfo->id . ']', null, ['id' => 'time-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only','autocomplete' => 'off']) !!}
        </td>
        <td class="text-center vcenter">
            <button id="remove-{!! $processInfo->id !!}" class="btn btn-xs btn-danger tooltips vcenter tooltips remove-process" title="Remove Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"><i class="fa fa-trash text-white"></i></button>
            <button id="edit-{!! $processInfo->id !!}" type="button" class="btn btn-xs btn-primary tooltips vcenter tooltips edit" title="Edit Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"><i class="fa fa-edit text-white"></i></button>
        </td>
    </tr>
    <?php
} else {
    ?>

    <tr class=" process-header-{!! $identifier . '-' . $processInfo->id !!}  process-header">
        <td rowspan="{!! count($productArr) !!}" class="text-center vcenter process-counter" id="processHeader-{!! $identifier . '-' . $processInfo->id !!}">&nbsp;</td>
        <td rowspan="{!! count($productArr) !!}" class="vcenter text-center">
            {!! $processInfo->name !!}
            {!! Form::hidden('edit_product', $productArr[0]->id, ['class' => 'product-'.$identifier . '-' . $processInfo->id] )!!}
        </td>
        <td class="vcenter product-{!! $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id !!}" title="{!! $tooltips !!}"><span>{!! $productArr[0]->name !!}</span></td>
        <td class="text-center vcenter">
            <label class="radio-container">
                {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']', '1' , false,['id' => 'gL-'.$identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id, 'data-formula' => '1', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id ]) }}
                <span class="checkmark"></span>
            </label>
        </td>
        <td class="vcenter text-center">
            <label class="radio-container">
                {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']', '2' , false,['id' => 'percent-'.$identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id, 'data-formula' => '2', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id]) }}
                <span class="checkmark"></span>
            </label>
        </td>
        <td class="vcenter text-center">
            <label class="radio-container">
                {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']', '3' , false,['id' => 'directAmount-'.$identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id, 'data-formula' => '3', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id ]) }}
                <span class="checkmark"></span>
            </label>
        </td>
        <td id="" class="vcenter">
            {!! Form::text('qty[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']', null, ['id' => 'qty-' . $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'form-control interger-decimal-only qty text-right selected-qty-'.$identifier . '-' . $processInfo->id,'autocomplete' => 'off', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id]) !!}
        </td>
        <td class="text-center vcenter">
            {!! Form::text('total_qty[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']', null, ['id' => 'totalQty-' . $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'form-control text-right selected-total_qty-'.$identifier . '-' . $processInfo->id,'readonly']) !!}
        </td>
        <td rowspan="{!! count($productArr) !!}" class="vcenter">
            {!! Form::text('water[' . $identifier . '][' . $processInfo->id . ']', $water, ['id' => 'water-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control text-right water','readonly']) !!}
        </td>
        <td class="vcenter" rowspan="{!! count($productArr) !!}">
            {!! Form::text('ph[' . $identifier . '][' . $processInfo->id . ']', null, ['id' => 'ph-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
        </td>
        <td class="vcenter" rowspan="{!! count($productArr) !!}">
            {!! Form::text('temperature[' . $identifier . '][' . $processInfo->id . ']', null, ['id' => 'temperature-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
        </td>
        <td rowspan="{!! count($productArr) !!}" class="vcenter text-center">1 : {!! Request::get('water_ratio') !!}
            {!! Form::hidden('water_ratio[' . $identifier . '][' . $processInfo->id . ']', Request::get('water_ratio'), ['id' => 'waterRatio-'.$identifier . '-' . $processInfo->id]) !!}
        </td>
        <td class="vcenter text-center" rowspan="{!! count($productArr) !!}">
            {!! Form::text('time[' . $identifier . '][' . $processInfo->id . ']', null, ['id' => 'time-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only','autocomplete' => 'off']) !!}
        </td>
        <td class="vcenter text-center" rowspan="{!! count($productArr) !!}">
            <button id="remove-{!! $processInfo->id !!}" class="btn btn-xs btn-danger tooltips vcenter tooltips remove-process" title="Remove Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"><i class="fa fa-trash text-white"></i></button>
            <button id="edit-{!! $processInfo->id !!}" type="button" class="btn btn-xs btn-primary tooltips vcenter tooltips edit" title="Edit Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"><i class="fa fa-edit text-white"></i></button>
        </td>
    </tr>

    <?php
    $i = 0;
    foreach ($productArr as $item) {
        if ($i != 0) {
            if ($item->percent_based == '1') {
                $tooltips = __('label.PERCENT_BASED');
            } else {
                $tooltips = __('label.NOT_PERCENT_BASED');
            }
            ?>   
            <tr class=" process-header-{!!$identifier . '-' . $processInfo->id !!}">
                <td class="vcenter" title="{!! $tooltips !!}" >
                    <span>{!! $item->name !!}</span>
                    {!! Form::hidden('edit_product', $item->id, ['class' => 'product-'.$identifier . '-' . $processInfo->id] )!!}
                </td>
                <td class="text-center vcenter">
                    <label class="radio-container">
                        {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', '1' , false,['id' => 'gL-'.$identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id, 'data-formula' => '1', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id ]) }}
                        <span class="checkmark"></span>
                    </label>
                </td>
                <td class="vcenter text-center">
                    <label class="radio-container">
                        {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', '2' , false,['id' => 'percent-'.$identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id, 'data-formula' => '2', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id]) }}
                        <span class="checkmark"></span>
                    </label>
                </td>
                <td class="vcenter text-center">
                    <label class="radio-container">
                        {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', '3' , false,['id' => 'directAmount-'.$identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id, 'data-formula' => '3', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id]) }}
                        <span class="checkmark"></span>
                    </label>
                </td>
                <td id="" class="text-center vcenter">
                    {!! Form::text('qty[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', null, ['id' => 'qty-' . $identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'form-control interger-decimal-only qty text-right selected-qty-'.$identifier . '-' . $processInfo->id,'autocomplete' => 'off', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id]) !!}
                </td>
                <td class="text-center vcenter">
                    {!! Form::text('total_qty[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', null, ['id' => 'totalQty-' . $identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'form-control text-right selected-total_qty-'.$identifier . '-' . $processInfo->id,'readonly']) !!}
                </td>
            </tr>       
            <?php
        }//if
        $i++;
    }//foreach
}//if

?>
            
<script type="text/javascript">

    $(document).ready(function () {
        
        $('.formula').on('change', function () {
            var formulaId = $(this).data("formula");
            var processProductId = $(this).data("process-product-id");
            var processId = $(this).data("process-id");

            var washLotQuantityWeight = $('#washLotQuantityWeight').val();
            var waterRatio = $('#waterRatio-' + processId).val();
            var qtyVal = $('#qty-' + processProductId).val();
            
             if (formulaId == 3) {
                $('#qty-' + processProductId).attr("readonly", true);
                $('#qty-' + processProductId).val('');
                $('#totalQty-' + processProductId).attr("readonly", false);
                $('#totalQty-' + processProductId).val('');
            } else {
                $('#qty-' + processProductId).attr("readonly", false);
                $('#totalQty-' + processProductId).attr("readonly", true);
                $('#totalQty-' + processProductId).val('');
            }

            //calculation for g/l or percent wise
            if (formulaId == 1) {
                var totalQty = (washLotQuantityWeight * waterRatio) / qtyVal;
                if (totalQty != 'Infinity') {
                    $('#totalQty-' + processProductId).val(totalQty.toFixed(3));
                }

            } else if (formulaId == 2) {
                var totalQty = (washLotQuantityWeight * qtyVal) / 100;
                if (totalQty != '') {
                    $('#totalQty-' + processProductId).val(totalQty.toFixed(3));
                }
            }
            //end calculation for g/l or percent wise
        });

        //calculation for g/l or percent wise
        $(document).on('keyup', '.qty', function () {

            var processProductId = $(this).data("process-product-id");
            var processId = $(this).data("process-id");
            
            

            var washLotQuantityWeight = $('#washLotQuantityWeight').val();
            var waterRatio = $('#waterRatio-' + processId).val();
            var qtyVal = $('#qty-' + processProductId).val();
            

            //calculation for g/l wise
            if ($('#gL-' + processProductId).is(':checked')) {
                var totalQty = (washLotQuantityWeight * waterRatio) / qtyVal;
                $('#totalQty-' + processProductId).val(totalQty.toFixed(3));
            }
            //calculation for percent wise    
            else if ($('#percent-' + processProductId).is(':checked')) {
                var totalQty = (washLotQuantityWeight * qtyVal) / 100;
                $('#totalQty-' + processProductId).val(totalQty.toFixed(3));
            }
        });
        
    });
</script>
