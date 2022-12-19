<div class="form-group">
    <label class="control-label" for="dryerMachineId">@lang('label.DRYER_MC_NO') :</label>
    {!! Form::select('dryer_machine_id', $dryerMachineArr, null, ['id'=> 'dryerMachineId', 'class' => 'form-control js-source-states']) !!} 
    <span class="text-danger">{{ $errors->first('dryer_machine_id') }}</span>
</div>