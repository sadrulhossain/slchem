@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-list"></i>@lang('label.SET_INITIAL_BALANCE')
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new tooltips" title="@lang('label.CURRENT_SYSTEM_TIME')"> 
                    <b>   {{ $balanceSetTime }} </b>
                </a>
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '', 'class' => 'form-horizontal','id' => 'submit_form')) !!}
            {!! Form::hidden('checkin_date', $checkinDate) !!}
            {{csrf_field()}}

            <div class="form-body">
                <div class="row">
                    <div class="form">
                        <!--                        <div class="col-md-3">
                                                    <label class="control-label">@lang('label.CHECK_IN_DATE') :<span class="text-danger"> *</span></label>
                                                    <div class="input-group date datepicker">
                                                        {!! Form::text('checkin_date', null, ['id'=> 'checkinDate', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                                        <span class="input-group-btn">
                                                            <button class="btn default reset-date" type="button" remove="checkinDate">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="text-danger">{{ $errors->first('checkin_date') }}</span>
                                                    </div>
                                                </div>-->
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="challanNo">@lang('label.CHALLAN_NO'):<span class="text-danger"> *</span></label>
                            {!! Form::text('challan_no', null, ['id' => 'challanNo','class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="refNo">@lang('label.REFERENCE_NO'):</label>
                            {!! Form::text('ref_no',$referenceNo,['id' => 'refNo','class' => 'form-control','readonly']) !!}
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-2">
                            <label class="control-label" for="mLabel">@lang('label.M_LABEL'):</label>
                            <div class="checkbox-center md-checkbox">
                                {!! Form::checkbox('m_label',1,true,['id' => 'mLabel', 'class'=> 'md-check']) !!}
                                <label for="mLabel">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-2">
                            <label class="control-label" for="msds">@lang('label.MSDS'):</label>
                            <div class="checkbox-center md-checkbox">
                                {!! Form::checkbox('msds',1,true,['id' => 'msds', 'class'=> 'md-check']) !!}
                                <label for="msds">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>

                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form">
                        <div class="col-md-2">
                            <label class="control-label" for="factoryLabel">@lang('label.FACTORY_LABEL') : </label>
                            <div class="checkbox-center md-checkbox">
                                {!! Form::checkbox('factory_label',1,true, ['id' => 'factoryLabel', 'class'=> 'md-check']) !!}
                                <label for="factoryLabel">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="purposeId">@lang('label.PURPOSE'):<span class="text-danger"> *</span></label>
                            {!! Form::select('purpose', $purposeArr, null, ['class' => 'form-control js-source-states', 'id' => 'purposeId']) !!}
                        </div>
                    </div>
                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="productId">@lang('label.PRODUCT'):<span class="text-danger"> *</span></label>
                            {!! Form::select('product_id', $productArr, null, ['class' => 'form-control js-source-states', 'id' => 'productId']) !!}
                            <div id="displayProductHints"></div>
                        </div>
                    </div>

                    <div id='showSupplierManufacturer'>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="supplierId">@lang('label.SUPPLIER'):<span class="text-danger"> *</span></label>
                                {!! Form::select('supplier_id', $supplierArr, null, ['class' => 'form-control js-source-states', 'id' => 'supplierId']) !!}
                            </div>
                        </div>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="manufacturerId">@lang('label.MANUFACTURER'):<span class="text-danger"> *</span></label>
                                {!! Form::select('manufacturer_id', $manufacturerArr, null, ['class' => 'form-control js-source-states', 'id' => 'manufacturerId']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div id='showManufacturerAddress'>
                        <div class="form">
                            <div class="col-md-3">
                                <label class="control-label" for="addressId">@lang('label.MANUFACTURER_ADDRESS'):<span class="text-danger"> *</span></label>
                                {!! Form::select('address_id', $addressArr, null, ['class' => 'form-control js-source-states', 'id' => 'addressId']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="lotNumber">@lang('label.LOT_NO'):<span class="text-danger"> *</span></label>
                            {!! Form::text('lot_number', null, ['id'=> 'lotNumber', 'class' => 'form-control']) !!} 
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
                            <label class="control-label" for="rate">@lang('label.RATE'):<span class="text-danger"> *</span></label>
                            {!! Form::text('rate',null, ['id' => 'rate','class' => 'form-control  interger-decimal-only','autocomplete' => 'off']) !!}
                        </div>
                    </div>

                </div>
                <div class="row">

                    <div class="form">
                        <div class="col-md-3">
                            <label class="control-label" for="amount">@lang('label.AMOUNT'):<span class="text-danger"> *</span></label>
                            {!! Form::text('amount',null,['id' => 'amount','class' => 'form-control  interger-decimal-only','readonly']) !!}
                        </div>
                    </div>
                    <br/>
                    <div class="form">
                        <div class="col-md-3 margin-top-10">

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
                        <p><b><u>@lang('label.SET_ITEM_LIST'):</u></b></p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter">@lang('label.PRODUCT')</th>
                                    <th class="vcenter">@lang('label.LOT_NO')</th>
                                    <th class="text-center vcenter">@lang('label.QUANTITY')</th>
                                    <th class="text-right vcenter">@lang('label.RATE')</th>
                                    <th class="text-right vcenter">@lang('label.AMOUNT')</th>
                                    <th class="text-center vcenter">@lang('label.ACTION')</th>
                                </tr>
                            </thead>
                            <tbody id="itemRows">
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
                        <button class="btn btn-circle green button-submit" id="submitButton" type="submit" disabled>
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

<script type="text/javascript">

    $(document).ready(function() {
        $(document).on("change", '#productId', function() {
            var productId = $("#productId").val();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            $.ajax({
                url: "{{URL::to('initialBalance/getSupplierManufacturer')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                },
                success: function(res) {
                    $('#showSupplierManufacturer').html(res.html);
                    $('.js-source-states').select2();
                },
                error: function(jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        toastr.error(errors, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                }
            });
        });

        $(document).on("change", '#manufacturerId', function() {
            var manufacturerId = $('#manufacturerId').val();
            $.ajax({
                url: "{{URL::to('initialBalance/getManufacturerAddress')}}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    manufacturer_id: manufacturerId,
                },
                success: function(res) {
                    $('#showManufacturerAddress').html(res.html);
                    $('.js-source-states').select2();
                },
            });
        });

        var count = 1;
        $('#addItem').click(function() {
            var productId = $('#productId').val();
            var supplierId = $('#supplierId').val();
            var purpose = $('#purposeId').val();
            var manufacturerId = $('#manufacturerId').val();
            var addressId = $('#addressId').val();
            var quantity1 = $('#quantity').val();
            var rate1 = $('#rate').val();
            var amount = $('#amount').val();
            var lotNumber = $('#lotNumber').val();
            var challanNo = $('#challanNo').val();
            var countNumber = count++;

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            if (purpose == '0') {
                toastr.error("Please select  Purpose", "Error", options);
                return false;
            }

            if (productId == '0') {
                toastr.error("Please select  Product", "Error", options);
                return false;
            }

            if (supplierId == '0') {
                toastr.error("Please select  Supplier", "Error", options);
                return false;
            }

            if (manufacturerId == '0') {
                toastr.error("Please select  Manufacturer", "Error", options);
                return false;
            }

            if (addressId == '0') {
                toastr.error("Please select  Manufacturer Address", "Error", options);
                return false;
            }


            if (lotNumber == '') {
                toastr.error("Please insert  Lot Number", "Error", options);
                return false;
            }
            if (quantity1 == '') {
                toastr.error("Please insert  quantity", "Error", options);
                return false;
            }

            if (rate1 == '') {
                toastr.error("Please insert  rate", "Error", options);
                return false;
            }

            if (challanNo == '') {
                toastr.error("Please insert  Challan No", "Error", options);
                return false;
            }


            //when i edit one row then delete previous row
            var editRow = $("#editRowId").val();
            if (editRow != '') {
                $('#rowId_' + editRow).remove();
            }


            $.ajax({
                url: "{{ URL::to('initialBalance/purchaseNew')}}",
                type: "POST",
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: productId,
                },
                beforeSend: function() {
                    App.blockUI({boxed: true});
                }
            }).done(function(result) {
                //$('#displayProductHints').remove();
//                $('#productId').val('0').select2();
//                $('#supplierId').val('0').select2();
//                $('#manufacturerId').val('0').select2();
//                $('#addressId').val('0').select2();
//                $('#quantity').val('');
//                $('#rate').val('');
//                $('#amount').val('');
//                $('#lotNumber').val('');
//                $("#mLabel").prop("checked", false);
//                $("#msds").prop("checked", false);
//                $("#factoryLabel").prop("checked", false);
                $("#hideNodata").css({"display": "none"});
                var rowCount = $('tbody#itemRows tr').length;

                row = '<tr id="rowId_' + productId + '_' + countNumber + '" class="item-list">\n\
                <td>\n\
                    \n\<input type="hidden" name="add_btn" value="1">\n\
                    <input type="hidden" id="editFlag_' + productId + '_' + countNumber + '"  value="">\n\
                    <input type="hidden" id="purpose_' + productId + '_' + countNumber + '" name="purpose[]" value="' + purpose + '">\n\
                    <input type="hidden" id="supplierId_' + productId + '_' + countNumber + '" name="supplier_id[]" value="' + supplierId + '">\n\
                    <input type="hidden" id="manufacturerId_' + productId + '_' + countNumber + '" name="manufacturer_id[]"  value="' + manufacturerId + '">\n\
                    <input type="hidden" id="addressId_' + productId + '_' + countNumber + '" name="address_id[]"  value="' + addressId + '">\n\
                    <input type="hidden" id="quantity_' + productId + '_' + countNumber + '"  name="quantity[]"  value="' + parseFloat(quantity1).toFixed(3) + '">\n\
                    <input type="hidden" id="rate_' + productId + '_' + countNumber + '"  name="rate[]"  value="' + parseFloat(rate1).toFixed(3) + '">\n\
                    <input type="hidden" id="amount_' + productId + '_' + countNumber + '"  name="amount[]" class="item-amount"  value="' + amount + '">\n\
                    <input type="hidden" id="lotNumber_' + productId + '_' + countNumber + '"  name="lot_number[]" value="' + lotNumber + '">\n\
                   <input type="hidden" id="productId_' + productId + '_' + countNumber + '" name="product_id[]"  value="' + productId + '">\n\
                    ' + result.productName + '</td>\n\
                <td>' + lotNumber + '</td>\n\
                <td class="text-center">' + parseFloat(quantity1).toFixed(3) + ' ' + result.productUnit + '</td>\n\
                <td class="text-right">' + parseFloat(rate1).toFixed(3) + '</td>\n\
                <td class="text-right">' + amount + '</td>\n\
                <td class="text-center">\n\
                    <button class="btn btn-xs btn-primary tooltips vcenter" id="editBtn' + productId + '_' + countNumber + '" title="Edit Product" onclick="editProduct(' + productId + ',' + countNumber + ');"><i class="fa fa-edit text-white"></i></button>\n\
                    <button onclick="removeItem(' + productId + ',' + countNumber + ');" class="btn btn-xs btn-danger tooltips vcenter" id="deleteBtn' + productId + '_' + countNumber + '"  title="Remove Item"><i class="fa fa-trash text-white"></i></button>\n\
                </td></tr>';
                // get total amount

                if (rowCount == 1) {
                    row += '<tr id="netTotalRow">\n\
                    <td colspan="4" class="text-right">Total</td>\n\
                    <td id="netTotal" class="text-right interger-decimal-only"></td>\n\
                    <td></td>\n\
                    </tr>';
                    $('#itemRows').append(row);
                } else {
                    $('#itemRows tr:last').before(row);
                }

                var netTotal = 0;
                $(".item-amount").each(function() {
                    netTotal += parseFloat($(this).val());
                });

                $('#netTotal').text(netTotal.toFixed(3));
                $('#productId').focus();
                $('#submitButton').attr("disabled", false);
                App.unblockUI();
            });
        });


        $(document).on('keyup', '#quantity,#rate', function() {
            getAmount();
        });

        function getAmount() {
            var quantity1 = $('#quantity').val();
            var rate1 = $('#rate').val();

            var unitPricex = getParsedAmount(rate1);
            var quantity = getParsedAmount(quantity1);
            var totalPricex = (unitPricex) * (quantity);
            var amount = parseFloat(totalPricex).toFixed(3);

            $('#amount').val(amount);
        }


        /*
         * 
         * Parse a Number to Float. If "NaN", convert to "0.00"
         * @param {Varchar} InitVal
         * @returns {Float Number}
         * 
         */
        function getParsedAmount(InitVal) {
            var OutVal = parseFloat(InitVal);
            if (isNaN(OutVal)) {
                OutVal = 0.00;
            }
            return OutVal;
        }


        $('#productId').on('change', function() {
            var productId = $(this).val();

            if (productId != '0') {
                $.ajax({
                    url: "{{URL::to('initialBalance/productHints')}}",
                    type: "POST",
                    data: {'product_id': productId},
                    dataType: 'json',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                }).done(function(response) {
                    $("#displayProductHints").html('<div id="pInform" class="text-success">Hints : ' + response['quantity'] + ' ' + response['unit_name'] + ' available</div>');

                });
            }
        });

        //save-data for checkin
        $(document).on("click", ".button-submit", function(e) {
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
                title: "Are you sure to set Initial Balance ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Set It",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function(isConfirm) {
                if (isConfirm)
                    //$("#submitButton").prop("disabled",true);
                    $.ajax({
                        url: "{{URL::to('initialBalance/set/')}}",
                        type: "POST",
                        dataType: 'json', // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function() {
                            $('.button-submit').prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function(res) {
                            //alert('1');return false;
                            toastr.success(res.data, 'Initial Balance Set Successfully', options);
                            App.unblockUI();
                            // similar behavior as an HTTP redirect
                            function explode() {
                                window.location.replace('{{URL::to("productCheckInList")}}');
                            }
                            setTimeout(explode, 3000);

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
                            $('.button-submit').prop('disabled', false);
                            App.unblockUI();
                        }
                    });
            });
        });

    });

    //remove item
    function removeItem(productId, countNumber) {

        $('#rowId_' + productId + '_' + countNumber).remove();
        var rowCount = $('tbody#itemRows tr').length;
        if (rowCount == 2) {
            $('tr#netTotalRow').remove();
            $('#hideNodata').show();
        }

        var netTotal = 0;
        $(".item-amount").each(function() {
            netTotal += parseFloat($(this).val());
        });

        $('#netTotal').text(netTotal);
        $('#submitButton').attr("disabled", true);

    }

    //edit item
    function editProduct(editId, countNumber) {
        var quantity1 = $('#quantity_' + editId + '_' + countNumber).val();
        var rate1 = $('#rate_' + editId + '_' + countNumber).val();
        var amount1 = $('#amount_' + editId + '_' + countNumber).val();
        var lotNumber = $('#lotNumber_' + editId + '_' + countNumber).val();
        var productId = $('#productId_' + editId + '_' + countNumber).val();
        var supplierId = $('#supplierId_' + editId + '_' + countNumber).val();
        var purpose = $('#purpose_' + editId + '_' + countNumber).val();
        var manufacturerId = $('#manufacturerId_' + editId + '_' + countNumber).val();
        var addressId = $('#addressId_' + editId + '_' + countNumber).val();
        var editRowId = $('#editRowId').val();

        var quantity = parseFloat(quantity1).toFixed(3);
        var rate = parseFloat(rate1).toFixed(3);
        var amount = parseFloat(amount1).toFixed(3);

        $('#quantity').val(quantity);
        $('#rate').val(rate);
        $('#amount').val(amount);
        $('#lotNumber').val(lotNumber);
        $('#productId').val(productId).select2();
        $('#supplierId').val(supplierId).select2();
        $('#purpose').val(purpose).select2();
        $('#manufacturerId').val(manufacturerId).select2();
        $('#addressId').val(addressId).select2();
        $("#editRowId").val(editId + '_' + countNumber);

        $('#editBtn' + editId + '_' + countNumber).attr('disabled', true);
        $('#deleteBtn' + editId + '_' + countNumber).attr('disabled', true);

        if (editRowId != '') {
            $('#editBtn' + editRowId).prop('disabled', true);
            $('#deleteBtn' + editRowId).prop('disabled', true);
        }
    }
    
    $(document).on('keyup', '.qty', function() {
        var quantity = $('#quantity').val();
        if (quantity != '') {
            validateNumberInput(quantity); // Check Qty 6 digits after decimal point
            var totalQty = parseFloat(quantity);
            var detailsText = unitConvert(totalQty);
            $('#displayQtyDetails').html('<div id="pInform" class="text-danger">' + detailsText + '</div>');
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
            kgFinalAmntStr = parseInt(kgAmnt) + "@lang('label.UNIT_KG')";
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
@stop

