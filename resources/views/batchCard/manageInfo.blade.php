<div class="modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.MANAGE_BATCH_CARD_INFORMATION')</strong></h4>
    </div>
    <button type="button" class="btn dark btn-outline pull-right" data-dismiss="modal">@lang('label.CLOSE')</button>
</div>

<div class="modal-body">
    {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitForm')) !!}
    {!! Form::hidden('batch_card_id',$request->batch_id) !!}
    {{csrf_field()}}
    <div class="form-body">
        <!-- START: Block for Machine In Time, Out Time -->
        <div class="row">
            <div class="form">
                <div class="col-md-6">
                    <label class="control-label" for="machineInTime">@lang('label.MACHINE_IN_TIME') :</label>
                    {!! Form::text('machine_in_time', $prevBatchData->machine_in_time, ['id'=> 'machineInTime', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                    <span class="text-danger">{{ $errors->first('machine_in_time') }}</span>
                </div>
            </div>
            <div class="form">
                <div class="col-md-6">
                    <label class="control-label" for="machineOutTime">@lang('label.MACHINE_OUT_TIME') :</label>
                    {!! Form::text('machine_out_time', $prevBatchData->machine_out_time, ['id'=> 'machineOutTime', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                    <span class="text-danger">{{ $errors->first('machine_out_time') }}</span>
                </div>
            </div>
        </div>
        <!-- END: Block for Machine In Time, Out Time -->
        
        <!-- START: Block for Hydro Machine -->
        <div class="row">
            <div class="form">
                <div class="col-md-6">
                    <label class="control-label" for="hydroMachineId">@lang('label.HYDRO_MC_NO') :</label>
                    {!! Form::select('hydro_machine_id', $hydroMachineArr, $prevBatchData->hydro_machine_id, ['id'=> 'hydroMachineId', 'class' => 'form-control js-source-states']) !!} 
                    <span class="text-danger">{{ $errors->first('hydro_machine_id') }}</span>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="form">
                <div class="col-md-6">
                    <label class="control-label" for="hydroInTime">@lang('label.HYDRO_FORM_IN_TIME') :</label>
                    {!! Form::text('hydro_in_time', $prevBatchData->hydro_in_time, ['id'=> 'hydroInTime', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                    <span class="text-danger">{{ $errors->first('hydro_in_time') }}</span>
                </div>
            </div>
            <div class="form">
                <div class="col-md-6">
                    <label class="control-label" for="hydroOutTime">@lang('label.HYDRO_FORM_OUT_TIME') :</label>
                    {!! Form::text('hydro_out_time', $prevBatchData->hydro_out_time, ['id'=> 'hydroOutTime', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                    <span class="text-danger">{{ $errors->first('shift') }}</span>
                </div>
            </div>
        </div>
         <!-- END: Block for Hydro Machine -->
          <!-- START: Block for Dryer Machine -->
        <div class="row">
            <div class="form">
                <div class="col-md-6">
                    <label class="control-label" for="inTime">@lang('label.DRYER_IN_TIME') :</label>
                    {!! Form::text('in_time', $prevBatchData->in_time, ['id'=> 'inTime', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                    <span class="text-danger">{{ $errors->first('in_time') }}</span>
                </div>
            </div>
            <div class="form">
                <div class="col-md-6">
                    <label class="control-label" for="outTime">@lang('label.DRYER_OUT_TIME') :</label>
                    {!! Form::text('out_time', $prevBatchData->out_time, ['id'=> 'outTime', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                    <span class="text-danger">{{ $errors->first('out_time') }}</span>
                </div>
            </div>
        </div>
         <!-- END: Block for Dryer Machine -->
        <div class="row">
            <div class="form">
                <div class="col-md-6">
                    <label class="control-label" for="okQty">@lang('label.OK_QTY') :</label>
                    {!! Form::text('ok_qty', $prevBatchData->ok_qty, ['id'=> 'okQty', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                    <span class="text-danger">{{ $errors->first('ok_qty') }}</span>
                </div>
            </div>
            <div class="form">
                <div class="col-md-6">
                    <label class="control-label" for="notOkQty">@lang('label.NOT_OK_QTY') : </label>
                    {!! Form::text('not_ok_qty', $prevBatchData->not_ok_qty, ['id'=> 'notOkQty', 'class' => 'form-control','autocomplete' => 'off']) !!} 
                    <span class="text-danger">{{ $errors->first('not_ok_qty') }}</span>
                </div>
            </div>
        </div>

    </div>
    {!! Form::close() !!}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" id="updateInfo">@lang('label.SUBMIT')</button>
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
</div>

<script type="text/javascript">
    //save-data for checkin
    $(document).on("click", "#updateInfo", function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        // Serialize the form data
        var formData = new FormData($('#submitForm')[0]);
        $.ajax({
            url: "{{URL::to('/batchCard/updateInformation')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (res) {
                toastr.success(res.data, 'Batch Card Information Added Successfully', options);
                window.location.replace('{{URL::to("/batchCard")}}');
            },
            error: function (jqXhr, ajaxOptions, thrownError) {

                if (jqXhr.status == 400) {
                    var errorsHtml = '';
                    var errors = jqXhr.responseJSON.message;
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                } else if (jqXhr.status == 401) {
                    toastr.error(jqXhr.responseJSON.message, '', options);
                } else {
                    toastr.error('Error', 'Something went wrong', options);
                }
                App.unblockUI();
            }
        });

    });
</script>