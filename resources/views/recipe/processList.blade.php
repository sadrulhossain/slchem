<tr class="process-header-{!! $identifier . '-' . $processInfo->id !!} process-header process-header-processHeader-{!! $identifier . '-' . $processInfo->id !!}">
    <td class="vcenter process-counter">
        <div class="md-checkbox has-success">
            {!! Form::checkbox('process_no['.$processInfo->id.']', $processInfo->id, null, ['id' => 'processNo_'.$processInfo->id,'data-id'=>$processInfo->id,'class'=> 'md-check']) !!}
            <label for="processNo_{!! $processInfo->id !!}">
                <span class="inc"></span>
                <span class="check"></span>
                <span class="box"></span>
            </label>
        </div>
    </td>
    <td class=" vcenter" id="processHeader-{!! $identifier . '-' . $processInfo->id !!}">
        <span>{!! $processInfo->name !!}</span>
    </td>
    <td class="text-center vcenter">
        <button id="remove-{!! $processInfo->id !!}" class="btn btn-xs btn-danger tooltips vcenter tooltips remove-process" title="Remove Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"><i class="fa fa-trash text-white"></i></button>  
        <button id="edit-{!! $processInfo->id !!}" type="button" class="btn btn-xs btn-primary tooltips vcenter tooltips edit" title="Edit Process" data-id="{!! $identifier . '-' . $processInfo->id !!}"><i class="fa fa-edit text-white"></i></button>
    </td>
</tr>