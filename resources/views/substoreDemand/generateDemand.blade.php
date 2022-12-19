@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.GENERATE_SUBSTORE_DEMAND')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new tooltips" title="@lang('label.CURRENT_SYSTEM_TIME')"> 
                    <b>   {{ $substoreTime }} </b>
                </a>
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form','class' => 'form-horizontal','id' => 'submit_form')) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">

                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="refNo">@lang('label.REFERENCE_NO'):</label>
                            {!! Form::text('voucher_no',$refNo,['id' => 'refNo','class' => 'form-control','readonly']) !!}
                        </div>
                    </div>


                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="productId">@lang('label.PRODUCT'):<span class="text-danger"> *</span></label>
                            {!! Form::select('product_id', $productArr, null, ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                            <div id="displayProductHints"></div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="quantity">@lang('label.QUANTITY'):<span class="text-danger"> *</span></label>
                            {!! Form::text('quantity',null, ['id'=> 'quantity', 'class' => 'form-control  interger-decimal-only qty','autocomplete' => 'off']) !!} 
                            <div id="displayQtyDetails"></div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="remarks">@lang('label.REMARKS'):</label>
                            {!! Form::textarea('remarks',null,['id' => 'remarks','class' => 'form-control','cols'=> '50','rows' => '5']) !!}
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label">@lang('label.DATE_OF_SUBSTORE') :</label>
                            {!! Form::text('adjustment_date', $substoreDate, ['id'=> 'substoreDate', 'class' => 'form-control start-date', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="attachment">@lang('label.ATTACHMENT'):</label>
                            {!! Form::file('attachment', null, ['id'=> 'attachment', 'class' => 'form-control attachment']) !!}
                            <div class="clearfix margin-top-10">
                                <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.FILE_FORMAT')
                            </div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3 margin-top-20">
                            <label class="control-label">&nbsp;</label>
                            <span class="btn green tooltips" type="button" id="addItem"  title="Add Item">
                                <i class="fa fa-plus text-white"></i>&nbsp;<span>@lang('label.ADD_ITEM')</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3><b><u>@lang('label.SUBSTORE_PRODUCT_LIST'):</u></b></h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                    <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                                    <th class="text-center vcenter">@lang('label.ACTION')</th>
                                </tr>
                            </thead>
                            <tbody id="itemRows">
                                <tr id="netTotalTr">
                                </tr>
                                <tr id="hideNodata">
                                    <td colspan="6">@lang('label.NO_DATA_SELECT_YET')</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id="editRowId" value="">
                    <input type="hidden" id="total" value="">
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green button-submit" id="subBtn"  type="submit" disabled>
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <!-- END BORDERED TABLE PORTLET-->
</div>

<script>
    $(document).ready(function () {
        var count = 1;
        $('#addItem').click(function () {
            var productId = $('#productId').val();
            var quantity = $('#quantity').val();
            var countNumber = count++;

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            if (productId == '0') {
                toastr.error("Please select  product", "Error", options);
                return false;
            }

            if (quantity == '') {
                toastr.error('Please, provide product\'s quantity !', "Error", options);
                return false;
            }

            //when i edit one row then delete previous row
            var editRow = $("#editRowId").val();
            if (editRow != '') {
                var prevItemTotalPrice = parseFloat($('#totalPrice_' + editRow).val());
                $('#rowId_' + editRow).remove();
            }


            if (isNaN(prevItemTotalPrice)) {
                var prevItemTotalPrice = 0;
            }


            $.ajax({
                url: "{{ URL::to('substoreDemand/purchaseNew')}}",
                type: "POST",
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                    quantity: quantity,
                    type: 3
                },
                success: function (result) {
                    $("#hideNodata").css({"display": "none"});
                    var netTotal = parseFloat($('#netTotal').val());
                    if (editRow != '') {
                        var netTotal = netTotal - prevItemTotalPrice;
                    }
                    var qty_details = unitConvert(quantity);
                    var row = '<tr id="rowId_' + productId + '_' + countNumber + '">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
                    <input type="hidden" id="editFlag_' + productId + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="quantity_' + productId + '_' + countNumber + '" name="quantity[]"  value="' + parseFloat(quantity).toFixed(6) + '">\n\
                    <input type="hidden" id="productId_' + productId + '_' + countNumber + '" name="product_id[]"  value="' + productId + '">\n\
                    ' + result.productName + '</td>\n\
                <td class="text-center">' + parseFloat(quantity).toFixed(6) + '</td><td class="text-center">' + qty_details + '\n\
               <td class="text-center">\n\
                    <button class="btn btn-xs btn-primary tooltips vcenter" id="editBtn' + productId + '_' + countNumber + '" title="Edit Product" style="cursor:pointer" onclick="editproduct(' + productId + ',' + countNumber + ');"><i class="fa fa-edit text-white"></i></button>\n\
                    <button onclick="removeItem(' + productId + ',' + countNumber + ');" class="btn btn-xs btn-danger tooltips vcenter" id="deleteBtn' + productId + '_' + countNumber + '"  title="Remove Product" style="cursor:pointer"><i class="fa fa-trash text-white"></i></button>\n\
                </td>\n\
                </tr>';

                    $("#netTotalTr").before(row);
                    $('#subBtn').attr("disabled", false);
                }, error: function (jqXhr, ajaxOptions, thrownError) {

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

        $('#productId').on('change', function () {
            var productId = $(this).val();
            var quantity = $('#quantity').val();
            var availQuantity = $('#availNumber').val();
            var newQuantity = availQuantity - quantity;
            if (productId != '0') {
                $.ajax({
                    url: "{{URL::to('substoreDemand/productHints')}}",
                    type: "POST",
                    data: {'product_id': productId},
                    dataType: 'json',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                }).done(function (response) {
                    $("#displayProductHints").html('<div id="pInform" class="text-success">Hints : ' + response['quantity'] + ' ' + response['unit_name'] + ' available</div>');
                });
            }
        });


    });

    function removeItem(productId, countNumber) {
        $('#rowId_' + productId + '_' + countNumber).remove();
        var rowCount = $('tbody#itemRows tr').length;
        if (rowCount == 2) {
            $('tr#netTotalRow').remove();
            $('#hideNodata').show();
            $('#subBtn').prop("disabled", true);
        }
    }

    function editproduct(editId, countNumber) {
        var quantity = $('#quantity_' + editId + '_' + countNumber).val();
        var productId = $('#productId_' + editId + '_' + countNumber).val();
        var editRowId = $('#editRowId').val();

        $('#quantity').val(quantity);
        $('#productId').val(productId).select2();
        $("#editRowId").val(editId + '_' + countNumber);

        $('#editBtn' + editId + '_' + countNumber).attr('disabled', true);
        $('#deleteBtn' + editId + '_' + countNumber).attr('disabled', true);

        if (editRowId != '') {
            $('#editBtn' + editRowId).prop('disabled', true);
            $('#deleteBtn' + editRowId).prop('disabled', true);
        }
    }

    //save-data for substore demand
    $(document).on("click", ".button-submit", function (e) {
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
        var formData = new FormData($('#submit_form')[0]);
        swal({
            title: "Are you want to submit ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            closeOnConfirm: true,
            closeOnCancel: true,
        }, function (isConfirm) {
            if (isConfirm)
                $.ajax({
                    url: "{{URL::to('/substoreDemand/generateDemand')}}",
                    type: "POST",
                    dataType: 'json', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (res) {
                        $('.button-submit').prop('disabled', true);
                        toastr.success(res.data, 'Substore Demand generated Successfully', options);
                        // similar behavior as an HTTP redirect
                        function explode() {
                            window.location.replace('{{URL::to("/substoreDemand")}}');
                        }
                        setTimeout(explode, 2000);

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
                        $('.button-submit').prop('disabled', false);
                        App.unblockUI();
                    }
                });
        });
    });


    $(document).on('keyup', '.qty', function () {
        var quantity = $('#quantity').val();
        if (quantity != '') {
            validateNumberInput(quantity); // Check Qty 6 digits after decimal point
            var totalQty = parseFloat(quantity);
            var details = unitConvert(totalQty);
            $('#displayQtyDetails').html('<div id="pInform" class="text-danger">' + details + '</div>');
        } else {
            $('#displayQtyDetails').html('');
        }
    });


    function validateNumberInput(totalQty) {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        //Find out the position of "." at Quantity
        var totalQtyPointPos = totalQty.toString().indexOf(".");
        if (totalQtyPointPos != -1) {
            var totalQtyArr = totalQty.toString().split(".");
            var kgAmnt = totalQtyArr[0];
            var gmAmntStr = totalQtyArr[1];
            var gmAmntStrLen = gmAmntStr.length;
            if (gmAmntStrLen > 6) {
                var allowedQtyStr = kgAmnt + "." + gmAmntStr.substring(0, 6);
                $('#quantity').val(allowedQtyStr);
                toastr.error('Error', "@lang('label.MAX_DIGITS')", options);
                return false;
            }//EOF - if length
        }//EOF - if -1
    }//EOF - function

    function unitConvert(totalQty) {
        var totalQtyArr = totalQty.toString().split(".");
        var kgAmnt = totalQtyArr[0];
        var gmAmntStr = totalQtyArr[1];
        var kgFinalAmntStr = '';
        if (kgAmnt > 0) {
            kgFinalAmntStr = parseInt(kgAmnt) + " @lang('label.UNIT_KG')";
        }

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
@stop

