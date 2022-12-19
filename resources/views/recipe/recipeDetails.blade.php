<?php
if ($processInfo->process_type_id == '2') {
    ?>
    <tr class="process-header-{!! $identifier . '-' . $processInfo->id !!} process-header process-header-processHeader-{!! $identifier . '-' . $processInfo->id !!}">
        <td class="vcenter process-counter"  id="processHeader-{!! $identifier . '-' . $processInfo->id !!}" data-name="{!! $processInfo->name !!}">{!! !empty($serialNo) ? $serialNo : '' !!}
        </td>
        <td class=" vcenter process-name" id="processHeader-{!! $identifier . '-' . $processInfo->id !!}">
            <span>{!! $processInfo->name !!}</span>
        </td>
        <td class=" vcenter" colspan="8">
            {!! Form::text('dry_chemical[' . $identifier . '][' . $processInfo->id . ']', !empty($dryChemical) ? $dryChemical : null, ['id' => 'dryChemical-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control','autocomplete' => 'off']) !!}
            {!! Form::hidden('qty[' . $identifier . '][' . $processInfo->id . ']', 'dry', ['id' => 'dry-' . $identifier . '-' . $processInfo->id]) !!}
        </td>
        <td class="vcenter">
            {!! Form::text('ph[' . $identifier . '][' . $processInfo->id . ']', !empty($ph) ? $ph : null, ['id' => 'ph-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control text-right','autocomplete' => 'off']) !!}
        </td>
        <td class="vcenter">
            {!! Form::text('temperature[' . $identifier . '][' . $processInfo->id . ']', !empty($temperature) ? $temperature : null, ['id' => 'temperature-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
        </td>
        <td class="vcenter text-center">&nbsp;</td><!-- water ratio-->
        <td class=" vcenter">
            {!! Form::text('time[' . $identifier . '][' . $processInfo->id . ']', !empty($time) ? $time : null, ['id' => 'time-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control text-right interger-decimal-only','autocomplete' => 'off']) !!}
        </td>
        <td class="vcenter">
            {!! Form::textarea('remarks[' . $identifier . '][' . $processInfo->id . ']', !empty($remarks) ? $remarks : null, ['id' => 'remarks-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control no-padding','autocomplete' => 'off', 'rows' => '2', 'cols' => '15']) !!}
        </td>
        <td class="text-center vcenter">
            <button id="remove-{!! $processInfo->id !!}" class="btn btn-xs btn-danger tooltips vcenter tooltips remove-process" title="Remove Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"><i class="fa fa-trash text-white"></i></button>  
            <button id="edit-{!! $processInfo->id !!}" type="button" class="btn btn-xs btn-primary tooltips vcenter tooltips edit" title="Edit Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"><i class="fa fa-edit text-white" ></i></button>
        </td>
    </tr>
    <?php
} else {
    if ($processInfo->water == '1') {
        ?>
        <tr class="process-header-{!! $identifier . '-' . $processInfo->id !!} process-header process-header-processHeader-{!! $identifier . '-' . $processInfo->id !!}">
            <td class="vcenter process-counter" id="processHeader-{!! $identifier . '-' . $processInfo->id !!}" data-name="{!! $processInfo->name !!}" >
                {!! !empty($serialNo) ? $serialNo : '' !!}
            </td>
            <td class="vcenter process-name" id="processHeader-{!! $identifier . '-' . $processInfo->id !!}"><span>{!! $processInfo->name !!}</span></td>
            {!! Form::hidden('qty[' . $identifier . '][' . $processInfo->id . ']', 'water', ['id' => 'qty-' . $identifier . '-' . $processInfo->id]) !!}
            <td class=" vcenter" colspan="7">
                @lang('label.WATER')
            </td>
            <td class="text-right vcenter">
                {!! Form::text('water[' . $identifier . '][' . $processInfo->id . ']', $water, ['id' => 'water-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control text-right water no-padding','readonly']) !!}
            </td>
            <td class="text-center vcenter">&nbsp;</td><!-- ph -->
            <td class="text-center vcenter">
                {!! Form::text('temperature[' . $identifier . '][' . $processInfo->id . ']', !empty($temperature) ? $temperature : null, ['id' => 'temperature-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
            </td>
            <td class="vcenter text-center">1 : {!! Request::get('water_ratio') !!}
                {!! Form::hidden('water_ratio[' . $identifier . '][' . $processInfo->id . ']', Request::get('water_ratio'), ['id' => 'waterRatio-'.$identifier . '-' . $processInfo->id]) !!}
            </td>
            <td class="vcenter">
                {!! Form::text('time[' . $identifier . '][' . $processInfo->id . ']', !empty($time) ? $time : null, ['id' => 'time-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
            </td>
            <td class="vcenter">
                {!! Form::textarea('remarks[' . $identifier . '][' . $processInfo->id . ']', !empty($remarks) ? $remarks : null, ['id' => 'remarks-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control no-padding','autocomplete' => 'off', 'rows' => '2', 'cols' => '15']) !!}
            </td>
            <td class="text-center vcenter">
                <button id="remove-{!! $processInfo->id !!}" class="btn btn-xs btn-danger tooltips vcenter tooltips remove-process" title="Remove Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"><i class="fa fa-trash text-white"></i></button>
                <button id="edit-{!! $processInfo->id !!}" type="button" class="btn btn-xs btn-primary tooltips vcenter tooltips edit" title="Edit Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"
                        ><i class="fa fa-edit text-white"></i></button>
            </td>
        </tr>
        <?php
    } else {
        $readonly = '';
        $disabled = 'disabled';
        if (!empty($recipeInfo)) {
            $readonly = isset($recipeInfo['qty'][$identifier][$processInfo->id][$productArr[0]->id]) ? '' : 'readonly';
        }
        
        $qtyRead = !empty($productArr[0]->type_of_dosage_ratio) ? ($productArr[0]->type_of_dosage_ratio == '3') ? 'readonly' : '':"readonly";
        $totalQtyReadonly = !empty($productArr[0]->type_of_dosage_ratio) ? ($productArr[0]->type_of_dosage_ratio == '1' || $productArr[0]->type_of_dosage_ratio == '2') ? 'readonly':'':'readonly';
        ?>
        <tr class=" process-header-{!! $identifier . '-' . $processInfo->id !!}  process-header process-header-processHeader-{!! $identifier . '-' . $processInfo->id !!}">
            <td rowspan="{!! count($productArr) !!}" class="vcenter process-counter" id="processHeader-{!! $identifier . '-' . $processInfo->id !!}" data-name="{!! $processInfo->name !!}"> {!! !empty($serialNo) ? $serialNo : '' !!}
            </td>
            <td rowspan="{!! count($productArr) !!}" class="vcenter">
                {!! $processInfo->name !!} 
                {!! Form::hidden('edit_product', $productArr[0]->id, ['class' => 'edit-product-'.$identifier . '-' . $processInfo->id] )!!}               
            </td>
            <td class="vcenter product-{!! $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id !!}" title="">
                <span>{!! $productArr[0]->name !!}
                    @if(empty($productArr[0]->type_of_dosage_ratio))
                    <i class="fa fa-warning tooltips pull-right text-danger" title="@lang('label.DOSAGE_RATIO_IS_NOT_SET_FOR_THIS_PRODUCT')"></i>
                    @endif
                </span></td>
            <td class="text-center vcenter">
                <label class="radio-container">
                    {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']', '1' , !empty($productArr[0]-> type_of_dosage_ratio) && $productArr[0]-> type_of_dosage_ratio == '1' ? true : false, ['id' => 'gL-'.$identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id .'-' . $productArr[0]->id, 'data-formula' => '1', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id ,$disabled]) }}
                     {!! Form::hidden('formula[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']',!empty($productArr[0]-> type_of_dosage_ratio) ?  $productArr[0]-> type_of_dosage_ratio : '', ['class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id .'-' . $productArr[0]->id,'id' => 'gL-'.$identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-formula' => '1', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id] )!!}
                    <span class="checkmark"></span>
                </label>
            </td>
            <td class="vcenter text-center">
                <label class="radio-container">
                    {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']', '2' ,  !empty($productArr[0]-> type_of_dosage_ratio) && $productArr[0]-> type_of_dosage_ratio == '2' ? true : false,['id' => 'percent-'.$identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' . $productArr[0]->id, 'data-formula' => '2', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id,$disabled]) }}
                    {!! Form::hidden('formula[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']',!empty($productArr[0]-> type_of_dosage_ratio) ?  $productArr[0]-> type_of_dosage_ratio : '', ['class' =>  'formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' . $productArr[0]->id,'id' => 'percent-'.$identifier . '-' . $processInfo->id . '-' . $productArr[0]->id,'data-formula' => '2', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id] )!!}
                    <span class="checkmark"></span>
                </label>
            </td>
            <td class="vcenter text-center">
                <label class="radio-container">
                    {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']', '3' , !empty($productArr[0]-> type_of_dosage_ratio) && $productArr[0]-> type_of_dosage_ratio == '3' ? true : false, ['id' => 'directAmount-'.$identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' . $productArr[0]->id, 'data-formula' => '3', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id,$disabled ]) }}
                    {!! Form::hidden('formula[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']',!empty($productArr[0]-> type_of_dosage_ratio) ?  $productArr[0]-> type_of_dosage_ratio : '' ,['class' =>  'formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' . $productArr[0]->id,'id' => 'directAmount-'.$identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-formula' => '3', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id,] )!!}
                    <span class="checkmark"></span>
                </label>
            </td>
            <td id="" class="vcenter">
                {!! Form::text('qty[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']', isset($recipeInfo['qty'][$identifier][$processInfo->id][$productArr[0]->id]) ? $recipeInfo['qty'][$identifier][$processInfo->id][$productArr[0]->id] : null, ['id' => 'qty-' . $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'form-control interger-decimal-only qty no-padding text-center selected-qty-'.$identifier . '-' . $processInfo->id.'-' .$productArr[0]->id,'autocomplete' => 'off', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'data-process-id' => $identifier . '-' . $processInfo->id,'data-product-id' => $productArr[0]->id, $qtyRead,'placeholder'=> !empty($productArr[0]->from_dosage && $productArr[0]->to_dosage) ? Helper::numberFormat($productArr[0]->from_dosage,1).' - '.Helper::numberFormat($productArr[0]->to_dosage,1) : '']) !!}
                {!! Form::hidden('from_dosage', $productArr[0]->from_dosage, ['id' => 'fromDosage-'.$identifier . '-' . $processInfo->id.'-' .$productArr[0]->id] )!!}
                {!! Form::hidden('to_dosage', $productArr[0]->to_dosage, ['id' => 'toDosage-'.$identifier . '-' . $processInfo->id.'-' .$productArr[0]->id] )!!}
            </td>
            <td class="text-center vcenter">
                {!! Form::text('total_qty[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']',isset($recipeInfo['total_qty'][$identifier][$processInfo->id][$productArr[0]->id]) ? $recipeInfo['total_qty'][$identifier][$processInfo->id][$productArr[0]->id] : null, ['id' => 'totalQty-' . $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'form-control integer-decimal-only text-right no-padding total-qty selected-total_qty-'.$identifier . '-' . $processInfo->id.'-' .$productArr[0]->id,'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id,'data-product-id' => $productArr[0]->id,  $totalQtyReadonly]) !!}
            </td>
            <td class="text-center vcenter">
                {!! Form::text('total_qty_detail[' . $identifier . '][' . $processInfo->id . '][' . $productArr[0]->id . ']',isset($recipeInfo['total_qty_detail'][$identifier][$processInfo->id][$productArr[0]->id]) ? $recipeInfo['total_qty_detail'][$identifier][$processInfo->id][$productArr[0]->id] : null, ['id' => 'totalQtyDetail-' . $identifier . '-' . $processInfo->id . '-' . $productArr[0]->id, 'class' => 'form-control text-right no-padding selected-total_qty_detail-'.$identifier . '-' . $processInfo->id.'-' .$productArr[0]->id, 'readonly'=>'readonly']) !!}
            </td>
            <td rowspan="{!! count($productArr) !!}" class="vcenter">
                {!! Form::text('water[' . $identifier . '][' . $processInfo->id . ']', $water, ['id' => 'water-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control text-right water no-padding','readonly']) !!}
            </td>
            <td class="vcenter" rowspan="{!! count($productArr) !!}">
                {!! Form::text('ph[' . $identifier . '][' . $processInfo->id . ']', !empty($ph) ? $ph : null, ['id' => 'ph-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
            </td>
            <td class="vcenter" rowspan="{!! count($productArr) !!}">
                {!! Form::text('temperature[' . $identifier . '][' . $processInfo->id . ']', !empty($temperature) ? $temperature : null, ['id' => 'temperature-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control interger-decimal-only text-right','autocomplete' => 'off']) !!}
            </td>
            <td rowspan="{!! count($productArr) !!}" class="vcenter text-center">1 : {!! Request::get('water_ratio') !!}
                {!! Form::hidden('water_ratio[' . $identifier . '][' . $processInfo->id . ']', Request::get('water_ratio'), ['id' => 'waterRatio-'.$identifier . '-' . $processInfo->id]) !!}
            </td>
            <td class="vcenter" rowspan="{!! count($productArr) !!}">
                {!! Form::text('time[' . $identifier . '][' . $processInfo->id . ']',  !empty($time) ? $time : null, ['id' => 'time-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control text-right interger-decimal-only','autocomplete' => 'off']) !!}
            </td>
            <td class=" vcenter" rowspan="{!! count($productArr) !!}">
                {!! Form::textarea('remarks[' . $identifier . '][' . $processInfo->id . ']',  !empty($remarks) ? $remarks : null, ['id' => 'remarks-' . $identifier . '-' . $processInfo->id, 'class' => 'form-control no-padding','autocomplete' => 'off', 'rows' => '2', 'cols' => '15']) !!}
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
                $readonly = '';
                $disabled = 'disabled';
                if (!empty($recipeInfo)) {
                    $readonly = isset($recipeInfo['qty'][$identifier][$processInfo->id][$item->id]) ? '' : 'readonly';
                }
                $qtyRead = !empty($item->type_of_dosage_ratio) ? ($item->type_of_dosage_ratio == '3') ? 'readonly' : '' : 'readonly';
                $totalQtyReadonly = !empty($item->type_of_dosage_ratio) ? ($item->type_of_dosage_ratio == '1' || $item->type_of_dosage_ratio == '2') ? 'readonly':'':'readonly';
                ?>
                <tr class=" process-header-{!!$identifier . '-' . $processInfo->id !!}">
                    <td class="vcenter">
                        <span>{!! $item->name !!}
                        @if(empty($item->type_of_dosage_ratio))
                            <i class="fa fa-warning tooltips pull-right text-danger" title="Dosage Ratio is not set for this Product"></i>
                        @endif
			</span>
                        {!! Form::hidden('edit_product', $item->id, ['class' => 'edit-product-'.$identifier . '-' . $processInfo->id] )!!}
                        
                    </td>
                    <td class="text-center vcenter">
                        <label class="radio-container">
                            {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', '1' , !empty($item-> type_of_dosage_ratio) && $item->type_of_dosage_ratio == '1' ? true : false, ['id' => 'gL-'.$identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' .$item->id, 'data-formula' => '1', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id,$disabled]) }}
                            {!! Form::hidden('formula[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']',!empty($item->type_of_dosage_ratio) ? $item->type_of_dosage_ratio : '', ['class' =>  'formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' . $item->id,'id' => 'gL-'.$identifier . '-' . $processInfo->id . '-' . $item->id, 'data-formula' => '1', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id] )!!}
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td class="vcenter text-center">
                        <label class="radio-container">
                            {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', '2' ,!empty($item-> type_of_dosage_ratio) && $item->type_of_dosage_ratio == '2' ? true : false, ['id' => 'percent-'.$identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' .$item->id, 'data-formula' => '2', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id,$disabled]) }}
                            {!! Form::hidden('formula[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']',!empty($item->type_of_dosage_ratio) ? $item->type_of_dosage_ratio : '', ['class' =>  'formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' . $item->id,'id' => 'percent-'.$identifier . '-' . $processInfo->id . '-' . $item->id, 'data-formula' => '2', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id] )!!}
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td class="vcenter text-center">
                        <label class="radio-container">
                            {{ Form::radio('formula[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', '3' ,!empty($item-> type_of_dosage_ratio) && $item->type_of_dosage_ratio == '3' ? true : false, ['id' => 'directAmount-'.$identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'formula direct-amount-formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' .$item->id, 'data-formula' => '3', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id,$disabled]) }}
                            {!! Form::hidden('formula[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']',!empty($item->type_of_dosage_ratio) ? $item->type_of_dosage_ratio : '',  ['class' =>  'formula selected-formula-'.$identifier . '-' . $processInfo->id.'-' . $item->id,'id' => 'directAmount-'.$identifier . '-' . $processInfo->id . '-' . $item->id, 'data-formula' => '3', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id] )!!}
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td id="" class="vcenter">
                        {!! Form::text('qty[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', isset($recipeInfo['qty'][$identifier][$processInfo->id][$item->id]) ? $recipeInfo['qty'][$identifier][$processInfo->id][$item->id] : null, ['id' => 'qty-' . $identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'form-control no-padding interger-decimal-only qty text-center selected-qty-'.$identifier . '-' . $processInfo->id.'-' .$item->id,'autocomplete' => 'off', 'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id, 'data-process-id' => $identifier . '-' . $processInfo->id ,'data-product-id' => $item->id, $qtyRead,'placeholder'=> !empty($item->from_dosage && $item->to_dosage) ? Helper::numberFormat($item->from_dosage,1).' - '.Helper::numberFormat($item->to_dosage,1) : '']) !!}
                        {!! Form::hidden('from_dosage', $item->from_dosage, ['id' => 'fromDosage-'.$identifier . '-' . $processInfo->id . '-' . $item->id] )!!}
                        {!! Form::hidden('to_dosage', $item->to_dosage, ['id' => 'toDosage-'.$identifier . '-' . $processInfo->id . '-' . $item->id] )!!}
                    </td>
                    <td class="vcenter">
                        {!! Form::text('total_qty[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']', isset($recipeInfo['total_qty'][$identifier][$processInfo->id][$item->id]) ? $recipeInfo['total_qty'][$identifier][$processInfo->id][$item->id] : null, ['id' => 'totalQty-' . $identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'form-control integer-decimal-only text-right no-padding total-qty selected-total_qty-'.$identifier . '-' . $processInfo->id.'-' .$item->id,'data-process-product-id' => $identifier . '-' . $processInfo->id . '-' . $item->id,'data-product-id' => $item->id, $totalQtyReadonly]) !!}
                    </td>
                    <td class="text-center vcenter">
                        {!! Form::text('total_qty_detail[' . $identifier . '][' . $processInfo->id . '][' . $item->id . ']',isset($recipeInfo['total_qty_detail'][$identifier][$processInfo->id][$item->id]) ? $recipeInfo['total_qty_detail'][$identifier][$processInfo->id][$item->id] : null, ['id' => 'totalQtyDetail-' . $identifier . '-' . $processInfo->id . '-' . $item->id, 'class' => 'form-control text-right no-padding selected-total_qty_detail-'.$identifier . '-' . $processInfo->id.'-' .$item->id,'readonly'=>'readonly']) !!}
                    </td>
                </tr>       
                <?php
            }//if
            $i++;
        }//foreach
    }//if
}//if
?>
