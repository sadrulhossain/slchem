<div class="modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.ACTIVATION')</strong></h4>
    </div>
    <button type="button" class="btn dark btn-outline pull-right" data-dismiss="modal">@lang('label.CLOSE')</button>
</div>
<div class="modal-body">
    {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submitActivation')) !!} 
    {!! Form::hidden('id',$target->id) !!} 
    {!! Form::hidden('type','activation') !!} 
    {{csrf_field()}}
    
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <p>@lang('label.CAUSE_OF_ACTIVATION')<span class="text-danger"> *</span></p>
                {{ Form::textarea('cause', null, ['id'=>'activeCause','class' => 'form-control activation-reason','maxlength'=>300,'rows' => 5, 'cols' => 10, 'required']) }}
                <span id="characterCount"></span>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" id="confirmation">@lang('label.CONFIRM_SUBMIT')</button>
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
</div>

<script type="text/javascript">
    $(document).on("click", "#confirmation", function(e) {
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
        var formData = new FormData($('#submitActivation')[0]);
        $.ajax({
            url: "{{URL::to('recipe/activate')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(res) {
                toastr.success(res.data, res.message, options);
                location.reload();
            },
            error: function(jqXhr, ajaxOptions, thrownError) {

                if (jqXhr.status == 400) {
                    var errorsHtml = '';
                    var errors = jqXhr.responseJSON.message;
                    $.each(errors, function(key, value) {
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

    //Counting Character for Cause of Deactivation
    $(document).on('keyup', '.activation-reason', function() {
        var max = 300;
        var len = $(this).val().length;
        var ch = max - len;
        $('span#characterCount').text(ch + ' characters left').css("color", "blue");
        if (len >= max) {
            $('span#characterCount').css("color", "red");
            return false;
        }

    });
</script>