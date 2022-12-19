@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.RELATE_PRODUCT_TO_MANUFACTURER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal', 'id' => 'submit_form')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="manufacturerId">@lang('label.SELECT_MANUFACTURER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('manufacturer_id', $manufacturerArr, null, ['class' => 'form-control js-source-states', 'id' => 'manufacturerId']) !!}
                                <span class="text-danger">{{ $errors->first('manufacturer_id') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div id="showProducts">


                </div>
            </div>

            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script type="text/javascript">
    $(document).on('change', '#manufacturerId', function() {
        var manufacturerId = $('#manufacturerId').val();
        if(manufacturerId == '0'){
             $('#showProducts').html('');
             return false;
        }
        $.ajax({
            url: '{{URL::to("productToManufacturer/getProducts/")}}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                manufacturer_id: manufacturerId
            },
            beforeSend: function() {
                App.blockUI({boxed: true});
            },
            success: function(res) {
                $('#showProducts').html(res.html);
                App.unblockUI();
            },
        });
    });

    //insert product to manufacturer
    $(document).on("click", ".btn-submit", function() {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        // Serialize the form data
        var form_data = new FormData($('#submit_form')[0]);
        $.ajax({
            url: "{{URL::to('productToManufacturer/saveProducts')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function(res) {
                toastr.success(res.message, res.heading, options);
                setTimeout(2000);
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
            }
        });

    });

</script>
@stop