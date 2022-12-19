<script type="text/javascript">
    $(document).ready(function () {
        $("#addFullMenuClass").addClass("page-sidebar-closed");
        $("#addsidebarFullMenu").addClass("page-sidebar-menu-closed");
        $("#processNo").multiselect();
        $('#processId').on('change', function () {

            var processId = $(this).val();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            if (processId != '0') {
                $.ajax({
                    url: "{{URL::to('recipe/getProducts')}}",
                    type: "POST",
                    data: {
                        'process_id': processId
                    },
                    dataType: 'json',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        //water proccess work only wet process
                        if (response.processInfo['process_type_id'] == '2') {
                            $("#waterRatioHolder").hide();
                            $("#showProductHolder").html(response.html).hide();
                        } else {
                            $("#waterRatioHolder").show();
                        }

                        if (response.processInfo['process_type_id'] == '1' && response.processInfo['water'] == '1') {
                            $("#showProductHolder").hide();
                            toastr.success('@lang("label.THIS_IS_WATER_TYPE_PROCESS")', 'Success', options);
                        }

                        if (response.html != '') {
                            $("#showProductHolder").html(response.html).show();
                            $('#productId').multiselect();
                            $('.js-source-states').select2();
                        } else {
                            $("#showProductHolder").html(response.html).hide();
                            toastr.error('@lang("label.NO_PRODUCT_ADD_WITH_THIS_PROCESS")', 'Error', options);
                        }

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
                            toastr.error('Error', '@lang("label.SOMETHING_WENT_WRONG")', options);
                        }
                    }
                });
            }
        });
        $('#factoryId').on('change', function () {

            var factoryId = $(this).val();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            if (factoryId != '0') {
                $.ajax({
                    url: "{{URL::to('recipe/getFactoryCode')}}",
                    type: "POST",
                    data: {
                        'factory_id': factoryId
                    },
                    dataType: 'json',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.html != '') {
                            $("#referenceNo").val(response.factoryCode);
                        } else {
                            $("#referenceNo").val('Reference not found');
                        }
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('Error', '@lang("label.SOMETHING_WENT_WRONG")', options);
                    }
                });
            }
        });
        //get Dryer Type Wise Dryer Machine
        $('#dryerTypeId').on('change', function () {

            var dryerType = $(this).val();
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            if (dryerType != '0') {
                $.ajax({
                    url: "{{URL::to('recipe/getDryerMachine')}}",
                    type: "POST",
                    data: {
                        'dryer_type_id': dryerType
                    },
                    dataType: 'json',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#showDryerMachine').html(response.html);
                        $('#dryerTypeCapacity').val(response.dryerTypeCapacity);
                        $('.js-source-states').select2();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('Error', '@lang("label.SOMETHING_WENT_WRONG")', options);
                    }
                });
            }
        });
        $('#addItem').click(function () {
            $("#washLotQuantityWeight").prop('readonly', true);
            var processId = $('#processId').val();
            var processName = $("#processId option:selected").text();
            var productId = $('#productId').val();
            var washLotQuantityWeight = $('#washLotQuantityWeight').val();
            var waterRatio = $('#mainWaterRatio').val();
            if (washLotQuantityWeight == '') {
                toastr.error('Wash Lot Quantity is Required', 'Error', options);
                $("#washLotQuantityWeight").prop('readonly', false);
                return false;
            }
            if (processId == 0) {
                toastr.error('@lang("label.PROCESS_IS_REQUIRED")', 'Error', options);
                return false;
            }


            if ($('#waterRatioHolder').css('display') != 'none')
            {
                if (waterRatio == '') {
                    toastr.error('@lang("label.WATER_RATIO_IS_REQUIRED")', 'Error', options);
                    return false;
                }
            }

            //at the time of editing
            var editRow = $("#editRowId").val();
            if (editRow != '') {
                $('#rowId_' + editRow).remove();
            }

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ URL::to('recipe/addProcess')}}",
                type: "POST",
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    process_id: processId,
                    product_id: productId,
                    wash_lot_quantity_weight: washLotQuantityWeight,
                    water_ratio: waterRatio,
                },
                beforeSend: function () {
                    //App.blockUI({boxed: true});
                },
                success: function (result) {

                    $("#hideNodata").hide();
                    //start order wise and insert data
                    var order = parseFloat($('#order').val());

                    if (order != 0) {

                        var serialCounterArr = [];
                        $(".process-counter").each(function () {
                            serialCounterArr.push($(this).text());
                        });
                        if ($.inArray(order, serialCounterArr)) {
                            var reOrder = order + 1;
                            $("#itemRows tr.tr-order-" + order).before(result.html);

                            var i = 1;
                            var processInfoArr = [];
                            $(".process-counter").each(function () {
                                $('#' + this.id).html(i);
                                $('#itemRows tr').removeClass('tr-order-' + i);
                                $('.process-header-' + this.id).addClass('tr-order-' + i);
                                i++;
                                processInfoArr.push($(this).attr('id') + '_' + $(this).data('name') + '_' + $(this).text());
                            });
                            //set order by insertion value
                            var rowCount = $('tbody#itemRows tr.process-header').length;
                            var options = rowCount;
                            $('#order').append($("<option/>")
                                    .attr("value", options)
                                    .text(options));

                            //START:: Reload WashType Process No		
                            $("#processNo option").remove();
                            $.each(processInfoArr, function (index, value) {
                                var infoArr = value.split('_');
                                var processInfo = infoArr[0];
                                var idInfo = processInfo.split('-');
                                var idenifier = idInfo[1];
                                var processId = idInfo[2];
                                var processName = infoArr[1];
                                var processSl = infoArr[2];
                                $('#processNo').append($("<option/>")
                                        .attr("value", idenifier + '-' + processId)
                                        .text(processSl + '.' + processName));
                            });

                            $('#processNo').multiselect("rebuild");
                            $('#processNo').multiselect({
                                includeSelectAllOption: true
                            });
                            //END:: Reload WashType Process No		

                            options++;
                            return false;
                        }
                    } else {
                        $('#itemRows tr:last').before(result.html);
                    }
                    //end order wise and insert data

                    var rowCount = $('tbody#itemRows tr.process-header').length;
                    //set order by insertion value
                    var options = rowCount;
                    $('#order').append($("<option/>")
                            .attr("value", options)
                            .text(options));

                    //Start:: Load Process based on Selected Process
                    $('#processNo').append($("<option/>")
                            .attr("value", result.identifier + '-' + processId)
                            .text(options + '.' + processName));
                    $('#processNo').multiselect('rebuild');
                    $('#processNo').multiselect({
                        includeSelectAllOption: true
                    });
                    //End:: Load Process based on Selected Process

                    options++;
                    //add total water           
                    if (rowCount == 1) {
                        var row = '<tr id="netTotalRow">\n\
                              <td colspan="9" class="text-right vcenter"><b>Total Water</b></td>\n\
                              <td id="netTotal" colspan="1" class="text-right">\n\
                                  <input type="text" class="form-control text-right no-padding" id="totalWater" readonly />\n\
                              </td>\n\
                              <td colspan="6"></td>\n\
                              </tr>';
                        $('#itemRows').append(row);
                    }

                    //get water ratio wise total water
                    calculateTotalWater();
                    $(".interger-decimal-only").each(function () {
                        $(this).keypress(function (e) {
                            var code = e.charCode;
                            if (((code >= 48) && (code <= 57)) || code == 0 || code == 46) {
                                return true;
                            } else {
                                return false;
                            }
                        });
                    });
                    //add serial number
                    var i = 1;
                    $(".process-counter").each(function () {
                        $('#' + this.id).html(i);
                        //add class depend on idenifier 
                        $('.process-header-' + this.id).addClass('tr-order-' + i);
                        i++;
                    });

                    // $('#processList tr:last').before(result.view);
                    $('#saveDraft').attr('disabled', false);
                    $('#finalizeBtn').attr('disabled', false);
                    App.unblockUI();
                    $('.tooltips').tooltip();
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
                        toastr.error('Error', '@lang("label.SOMETHING_WENT_WRONG")', options);
                    }
                    App.unblockUI();
                }
            });
        });
        $(document).on('click', '.remove-process', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $('.process-header-' + id).remove();
            var i = 1;
            var processInfoArr = [];
            $(".process-counter").each(function () {
                $('#' + this.id).html(i);
                $('#itemRows tr').removeClass('tr-order-' + i);
                $('.process-header-' + this.id).addClass('tr-order-' + i);
                i++;
                processInfoArr.push($(this).attr('id') + '_' + $(this).data('name') + '_' + $(this).text());
            });
            var rowCount = $('tbody#itemRows tr.process-header').length;

            //decreasing order
            var options = rowCount;
            options += 1;
            //decreasing order

            $("#order option[value='" + options + "']").remove();
            $("#processNo option").remove();

            //START:: After Delete load Process Name
            $.each(processInfoArr, function (index, value) {
                var infoArr = value.split('_');
                var processInfo = infoArr[0];
                var idInfo = processInfo.split('-');
                var idenifier = idInfo[1];
                var processId = idInfo[2];
                var processName = infoArr[1];
                var processSl = infoArr[2];
                $('#processNo').append($("<option/>")
                        .attr("value", idenifier + '-' + processId)
                        .text(processSl + '.' + processName));

            });

            $('#processNo').multiselect("rebuild");
            $('#processNo').multiselect({
                includeSelectAllOption: true
            });

            //END:: After Delete load Process Name


            if (rowCount == 0) {
                $("#hideNodata").show();
                $("#netTotalRow").remove();
                $('#saveDraft').attr('disabled', true);
                $('#updateRecipe').attr('disabled', true);
                $('#finalizeBtn').attr('disabled', true);
            }
            calculateTotalWater();
        });

        $(document).on("keyup", ".i-am-water", function (e) {
            calculateTotalWater();
        });

        $(document).on('keyup', '#washLotQuantityWeight', function () {
            $(".i-am-doge").each(function (e) {
                setLrFromDoge(this.id);
            });
        });
        function calculateTotalWater() {
            var totalWater = 0;
            $(".water").each(function () {
                totalWater += +$(this).val();
            });
            $("#totalWater").val(totalWater);
        }

        function setLrFromDoge(id) {
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            var processId = $('#' + id).attr('data-process-id');
            var doge = $('#' + id).val();
            var water = $('#water-' + processId).val();
            var weightQuantity = $('#washLotQuantityWeight').val();
            var percentVal = $('#percent-' + id.substring(5)).val();
            if (percentVal == 1) {
                if (weightQuantity != '') {
                    var lr = Math.round((parseFloat(doge) * 100)) / (parseFloat(weightQuantity) * 1000);
                } else {
                    toastr.error('@lang("label.PLEASE_GIVE_WASH_LOT_QTY")', 'Error', options);
                }
            }

            if (water != '' && doge != '') {
                var lr = '1:' + Math.round((parseFloat(doge) / parseFloat(water)) * 100) / 100;
            }
            $('#lr-' + id.substring(5)).val(lr);
        }


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
            var formData = new FormData($('#createRecipe')[0]);
            var saveDraftId = $('#saveDraft').val();
            //var finalizeId = $('#finalizeBtn').val();
            var processId = $('#processId').val();
            if (processId == '0') {
                toastr.error('@lang("label.PLEASE_SELECT_PROCESS")', 'Error', options);
                return false;
            }

            if (this.id == 'saveDraft') {
                formData.append('save_draft', saveDraftId);
                var msg = 'Save as Draft';
                var title = '';
                var text = '';
            }
            swal({
                title: "Are you sure, you want to " + msg + " ?",
                type: "warning",
                text: text,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Confirmed",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm)
                    //$("#submitButton").prop("disabled",true);
                    $.ajax({
                        url: "{{URL::to('recipe/saveRecipe')}}",
                        type: "POST",
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            //toastr.success(res.data, 'Recipe created Successfully', options);
                            toastr.success(res.message, res.heading, options);
                            window.location = '{!! URL::to("recipe") !!}';
                            // similar behavior as an HTTP redirect

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
        });
        $(document).on("click", ".button-update-submit", function (e) {
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
            var formData = new FormData($('#editRecipe')[0]);
            var saveDraftId = $('#saveDraft').val();
            var updateId = $('#updateRecipe').val();
            if (this.id == 'saveDraft') {
                formData.append('save_draft', saveDraftId);
                var msg = 'Save as Draft';
                var title = '';
            } else if (this.id == 'updateRecipe') {
                formData.append('submit_data', updateId);
                var msg = 'Finalize';
                var text = 'You will not be able to further modify this!';
            }
            swal({
                title: "Are you sure, you want to " + msg + " ?",
                type: "warning",
                text: text,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Confirmed",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm)
                    //$("#submitButton").prop("disabled",true);
                    $.ajax({
                        url: "{{URL::to('recipe/updateRecipe')}}",
                        type: "POST",
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            toastr.success(res.message, res.heading, options);
                            window.location = '{!! URL::to("recipe") !!}';
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
        });
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
                $('#totalQtyDetail-' + processProductId).val('');
            } else {
                $('#qty-' + processProductId).attr("readonly", false);
                $('#totalQty-' + processProductId).attr("readonly", true);
                $('#totalQty-' + processProductId).val('');
            }

            //calculation for g/l or percent wise
            if (formulaId == 1) {
                //var totalQty = (washLotQuantityWeight * waterRatio) / qtyVal;
                var totalQty = parseFloat((washLotQuantityWeight * waterRatio) * (qtyVal / 1000));
                if (totalQty != 'Infinity') {
                    $('#totalQty-' + processProductId).val(totalQty.toFixed(6));
                }
                var totalAmnt = totalQty.toFixed(6);
            } else if (formulaId == 2) {
                var totalQty = parseFloat((washLotQuantityWeight * qtyVal) / 100);
                if (totalQty != '') {
                    $('#totalQty-' + processProductId).val(totalQty.toFixed(6));
                }
                var totalAmnt = totalQty.toFixed(6);
            } else {
                var totalQty = 0;
                var totalAmnt = "";
            }


            unitConversion(totalAmnt, processProductId);
            //end calculation for g/l or percent wise
        });
        //calculation for g/l or percent wise
        $(document).on('keyup', '.qty', function () {

            var processProductId = $(this).data("process-product-id");
            var processId = $(this).data("process-id");
            var washLotQuantityWeight = $('#washLotQuantityWeight').val();
            var waterRatio = $('#waterRatio-' + processId).val();
            var qtyVal = parseFloat($('#qty-' + processProductId).val());
            var fromDosage = parseFloat($('#fromDosage-' + processProductId).val());
            var toDosage = parseFloat($('#toDosage-' + processProductId).val());

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            validateNumberInput(qtyVal, processProductId); // Check Qty 1 digits after decimal point


            //calculation for g/l wise
            if ($('#gL-' + processProductId).is(':checked')) {
                //var totalQty = (washLotQuantityWeight * waterRatio) / qtyVal;
                var totalQty = parseFloat((washLotQuantityWeight * waterRatio) * (qtyVal / 1000));
                $('#totalQty-' + processProductId).val(totalQty.toFixed(6));
            }
            //calculation for percent wise    
            else if ($('#percent-' + processProductId).is(':checked')) {
                var totalQty = parseFloat((washLotQuantityWeight * qtyVal) / 100);
                $('#totalQty-' + processProductId).val(totalQty.toFixed(6));
            } else {
                toastr.error('Error', "@lang('label.FORMULA_SHOULD_BE_SELCTED')", options);
            }

            if (!isNaN(totalQty)) {
                var totalAmnt = totalQty.toFixed(6);
                unitConversion(totalAmnt, processProductId);
            } else {
                $('#totalQty-' + processProductId).val(" ");
                $('#totalQtyDetail-' + processProductId).val(" ");
            }
        });


        $(document).on('keyup', '.wash-lot-calculator', function () {
            var weight = $('#washLotQuantityWeight').val();
            var piece = $('#washLotQuantityPiece').val();
            if (weight != '' && piece != '') {
                var weightOncePiece = (weight / piece).toFixed(2);
                $('#weightOnePiece').val(weightOncePiece);
            }
        });
        $(document).on('click', '.edit', function () {
            $('#addBtn').hide();
            $('#updateBtn').show();
            var identifierProcessId = $(this).data("id");
            var separator = identifierProcessId.split("-");
            var identifier = separator[0];
            var processId = separator[1];
            $.each(separator, function (key, value) {
                var option = $("#processNo option[value='" + value + "']");
                option.attr("disabled", "disabled");
                $("#processNo option[value='" + value + "']").prop("selected", false);
            });

            $("#processNo").multiselect("refresh");
            var seletedItemOrder = $('#processHeader-' + identifierProcessId).text();
            //set bg color to identify editted process
            $('.process-header-' + identifierProcessId).addClass('bg-default');
            //show water ratio
            var waterRatio = $('#waterRatio-' + identifierProcessId).val();
            $('#waterRatioHolder').show();
            $('#mainWaterRatio').val(waterRatio);
            //show process
            $("#processId").val(processId);
            $("#order").val(seletedItemOrder);
            $('.js-source-states').select2();
            //set value update btn
            $("#updateItem").val(identifierProcessId);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            //get process wise product list
            if (processId != '0') {

                var producIdArr = new Array();
                $(".edit-product-" + identifierProcessId).each(function () {
                    producIdArr.push($(this).val());
                });
                $.ajax({
                    url: "{{URL::to('recipe/getProducts')}}",
                    type: "POST",
                    data: {
                        'process_id': processId,
                        'product_id': producIdArr,
                    },
                    dataType: 'json',
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.processInfo['process_type_id'] == '2') {
                            $("#showProductHolder").html(response.html).hide();
                            $("#waterRatioHolder").hide();
                        } else {
                            if (response.html != '') {
                                $("#showProductHolder").html(response.html).show();
                                $('#productId').multiselect();
                                $('.js-source-states').select2();
                            } else if (response.processInfo['water'] == '1') {
                                $("#showProductHolder").html(response.html).hide();
                                toastr.success('This is Water Type Process', 'Success', options);
                            } else {
                                $("#showProductHolder").html(response.html).hide();
                                toastr.error('No Product is Added with this Process.Please, relate with Product', 'Error', options);
                            }
                        }
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                    }
                });
            }
        });
        $('#updateItem').click(function (e) {
            e.preventDefault();
            var identifierProcessId = $(this).val();
            var separator = identifierProcessId.split("-");
            var identifier = separator[0];
            var processId = separator[1];
            var productIdArr = $('#productId').val(); //get dropdown product id
//            var productId = $('.edit-product-' + identifierProcessId).val();//get editted product id
            var newProcessId = $('#processId').val();
            var newProcessName = $("#processId option:selected").text();
            var washLotQuantityWeight = $('#washLotQuantityWeight').val();
            var waterRatio = $('#mainWaterRatio').val();
            var recipeSl = $('#processHeader-' + identifierProcessId).text();
            var selectedFormulaArr = [];
            var selectedQtyArr = [];
            var selectedTotalQtyArr = [];
            var selectedTotalQtyDetailArr = [];
            var selectedProductArr = [];
            //get product wise formula,qty, totalQty
            $(".edit-product-" + identifierProcessId).each(function () {
                var productId = $(this).val();
                selectedProductArr.push(productId);
//                var selectedProductId = identifierProcessId + "-"+ $(this).val();

                $(".selected-formula-" + identifierProcessId + "-" + productId + ":checked").each(function () {
                    var formula = $(this).val();
                    selectedFormulaArr.push({
                        productId, formula
                    });
                });
                $(".selected-qty-" + identifierProcessId + "-" + productId).each(function () {
                    var qty = $(this).val();
                    selectedQtyArr.push({productId, qty});
                });
                $(".selected-total_qty-" + identifierProcessId + "-" + productId).each(function () {
                    var totalQty = $(this).val();
                    selectedTotalQtyArr.push({
                        productId, totalQty
                    });
                });

                $(".selected-total_qty_detail-" + identifierProcessId + "-" + productId).each(function () {
                    var totalQtyDetail = $(this).val();
                    console.log(totalQtyDetail);
                    selectedTotalQtyDetailArr.push({
                        productId, totalQtyDetail
                    });
                });
            });
            var selectedDryChemical = $('#dryChemical-' + identifierProcessId).val();
            var selectedDry = $('#dry-' + identifierProcessId).val();
            var selectedRemarks = $('#remarks-' + identifierProcessId).val();
            var selectedPh = $('#ph-' + identifierProcessId).val();
            var selectedTemperature = $('#temperature-' + identifierProcessId).val();
            var selectedTime = $('#time-' + identifierProcessId).val();
            var selectedWater = $('#water-' + identifierProcessId).val();
            var order = parseFloat($('#order').val());

            if (washLotQuantityWeight == '') {
                toastr.error('Wash Lot Quantity is Required', 'Error', options);
                $("#washLotQuantityWeight").prop('readonly', false);
                return false;
            }

            if (selectedDry == '') {
                if (waterRatio == '') {
                    toastr.error('water Ratio is Required', 'Error', options);
                    return false;
                }
            }


            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{ URL::to('recipe/updateProcess')}}",
                type: "POST",
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    identifier: identifier,
                    serial_no: recipeSl,
                    new_process_id: newProcessId,
                    process_id: processId,
                    product_id: productIdArr,
                    wash_lot_quantity_weight: washLotQuantityWeight,
                    water_ratio: waterRatio,
                    selected_formula: selectedFormulaArr,
                    selected_qty: selectedQtyArr,
                    selected_total_qty: selectedTotalQtyArr,
                    selected_total_qty_detail: selectedTotalQtyDetailArr,
                    selected_ph: selectedPh,
                    selected_temperature: selectedTemperature,
                    selected_time: selectedTime,
                    selected_water: selectedWater,
                    selected_dry_chemical: selectedDryChemical,
                    selected_remarks: selectedRemarks,
                    selected_product: selectedProductArr,
                },
                beforeSend: function () {
                    //App.blockUI({boxed: true});
                },
                success: function (result) {

                    var selectedOrder = result.selectedOrder;
                    var newIdentifier = result.newIdentifier;
                    $('#updateBtn').hide();
                    $('#addBtn').show();
                    if (order != 0) {
                        var serialCounterArr = [];
                        $(".process-counter").each(function () {
                            serialCounterArr.push($(this).text());
                        });
                        if ($.inArray(order, serialCounterArr)) {
                            //find maximum number in counted array
                            var maxNumber = Math.max.apply(Math, serialCounterArr);
                            $('#itemRows tr.process-header-' + identifierProcessId).remove();
                            if (selectedOrder <= order) {
                                var reOrder = order + 1;
                                if (order != maxNumber) {
                                    $("#itemRows tr.tr-order-" + reOrder).before(result.html);
                                } else {
                                    $("#itemRows tr:last").before(result.html);
                                }
                            } else {
                                $("#itemRows tr.tr-order-" + order).before(result.html);
                            }
                            var i = 1;
                            var processInfoArr = [];
                            $(".process-counter").each(function () {
                                $('#' + this.id).html(i);
                                $('#itemRows tr').removeClass('tr-order-' + i);
                                $('.process-header-' + this.id).addClass('tr-order-' + i);
                                i++;
                                //reload process no after update: order wise process
                                processInfoArr.push($(this).attr('id') + '_' + $(this).data('name') + '_' + $(this).text());
                            });

                            $("#processNo option").remove();
                            $.each(processInfoArr, function (index, value) {
                                var infoArr = value.split('_');
                                var processInfo = infoArr[0];
                                var processName = infoArr[1];
                                var processSl = infoArr[2];
                                var idInfo = processInfo.split('-');
                                var idenifier = idInfo[1];
                                var processId = idInfo[2];

                                $('#processNo').append($("<option/>")
                                        .attr("value", idenifier + '-' + processId)
                                        .text(processSl + '.' + processName));

                            });

                            $('#processNo').multiselect("rebuild");
                            $('#processNo').multiselect({
                                includeSelectAllOption: true,
                            });
                            return false;

                        }
                    } else {
                        $("#itemRows tr.tr-order-" + selectedOrder).before(result.html);
                        $('#itemRows tr.process-header-' + identifierProcessId).remove();
                        $('.process-header-processHeader-' + newIdentifier + '-' + newProcessId).addClass('tr-order-' + selectedOrder);

                        //START:: Reload WashType Process No	
                        var processInfoArr = [];
                        $(".process-counter").each(function () {
                            processInfoArr.push($(this).attr('id') + '_' + $(this).data('name') + '_' + $(this).text());
                        });

                        $("#processNo option").remove();
                        $.each(processInfoArr, function (index, value) {
                            var infoArr = value.split('_');
                            var processInfo = infoArr[0];
                            var idInfo = processInfo.split('-');
                            var idenifier = idInfo[1];
                            var processId = idInfo[2];
                            var processName = infoArr[1];
                            var processSl = infoArr[2];
                            $('#processNo').append($("<option/>")
                                    .attr("value", idenifier + '-' + processId)
                                    .text(processSl + '.' + processName));
                            $.each(idInfo, function (key, value) {
                                var option = $("#processNo option[value='" + value + "']");
                                option.attr("disabled", "disabled");
                                $("#processNo option[value='" + value + "']").prop("selected", false);
                            });
                            $("#processNo").multiselect("refresh");

                        });

                        $('#processNo').multiselect("rebuild");
                        $('#processNo').multiselect({
                            includeSelectAllOption: true
                        });
                        //END:: Reload WashType Process No	
                    }

                    //set bg color to identify editted process
                    $('.process-header-' + identifierProcessId).removeClass('bg-default');
                    $("#washLotQuantityWeight").prop('readonly', false);
                    //get water ratio wise total water
                    calculateTotalWater();
                    $(".interger-decimal-only").each(function () {
                        $(this).keypress(function (e) {
                            var code = e.charCode;
                            if (((code >= 48) && (code <= 57)) || code == 0 || code == 46) {
                                return true;
                            } else {
                                return false;
                            }
                        });
                    });
                    $('.tooltips').tooltip();

                    App.unblockUI();

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
    });


    /************** START :: JS for Unit Conversion ***********/
    $(document).on('keyup', '.total-qty', function () {
        var processProductId = $(this).data("process-product-id");
        //alert($(this).data());
        if ($('#directAmount-' + processProductId).is(':checked')) {
            //alert("Hello There...");
            var totalQty = parseFloat($('#totalQty-' + processProductId).val());

            validateNumberInput(totalQty, processProductId); // Check Qty 6 digits after decimal point
            if (!isNaN(totalQty)) {
                var totalAmnts = totalQty.toFixed(6);
                unitConversion(totalAmnts, processProductId);
            } else {
                $('#totalQtyDetail-' + processProductId).val('');
            }
        } else {
            $('#totalQty-' + processProductId).val('')
            $('#totalQtyDetail-' + processProductId).val('');
        }
    });


    function validateNumberInput(totalQty, processProductId) {
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
                //$('#qty-' + processProductId).val(allowedQtyStr);
                if (totalQty != '') {
                    $('#totalQty-' + processProductId).val(allowedQtyStr);
                }
                toastr.error('Error', "@lang('label.MAX_DIGITS')", options);
                return false;
            }//EOF - if length
        }//EOF - if -1
    }//EOF - function

    function unitConversion(totalQty, processProductId) {
        //Find out the position of "." at Quantity
        var totalQtyPointPos = totalQty.toString().indexOf(".");
        if (totalQtyPointPos != -1) {
            //alert("Hello...");
            var totalQtyArr = totalQty.toString().split(".");
            var kgAmnt = totalQtyArr[0];
            var gmAmntArr = totalQtyArr[1];
            var kgFinalAmntStr = '';
            if (kgAmnt > 0) {
                kgFinalAmntStr = parseInt(kgAmnt) + " @lang('label.UNIT_KG')";
            }

            var lengthOfGm = gmAmntArr.length;//length of amount after decimal point
            //var zeroPadLength = (6 - (lengthOfGm)); //6 is fixed as 1KG is equal to 1000000 mg (0.000001 KG => 6 digit after decimal point)
            var pad = '000000';
            var totalAmntStr = (gmAmntArr + pad).substring(0, pad.length);
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
            $('#totalQtyDetail-' + processProductId).val(text);
        }//EOF - if totalQtyPointPos != -1
    }
    /************** END :: JS for Unit Conversion ***********/




    //remove item
    function removeItem(productId, countNumber) {
        $('#rowId_' + productId + '_' + countNumber).remove();
        var rowCount = $('tbody#itemRows tr').length;
        if (rowCount == 2) {
            $('tr#netTotalRow').remove();
            $('#hideNodata').show();
        }

        calculateTotalWater();
    }

    // After Click Add Process with Wash Type
    var count = 1;
    $('#addProcess').click(function () {
        var washTypeId = $('#washTypeId').val();
        var washTypeName = $("#washTypeId option:selected").text();

        var processId = $("#processNo option:selected").map(function () {
            var processIdvalue = this.value;
            var processIdArr = processIdvalue.split('-');
            var identifier = processIdArr[0];
            var processId = processIdArr[1];
            return identifier + '-' + processId;
        }).get();


        var processName = $("#processNo option:selected").map(function () {
            var processNamevalue = this.text;
            var processNameArr = processNamevalue.split('.');
            var serialNo = processNameArr[0];
            var processName = processNameArr[1];
            return processName;
        }).get().join(' || ');

        var countNumber = count++;
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        if (washTypeId == '0') {
            toastr.error('Error', 'Please select WashType', options);
            return false;
        }

        if (processId == '') {
            toastr.error('Error', 'Please select Process', options);
            return false;
        }

        //when i edit one row then delete previous row
        // var editRow = $("#editRowsId").val();
        // if (editRow != '') {
        // $('#rowId_' + editRow).remove();
        // }
        $("#hideNoProcessdata").hide();
        $("#divNoProcessdata").css({"display": "none"});
        var rowCount = $('tbody#processRows tr').length;

        var row = '<tr class="item-list-' + washTypeId + '_' + countNumber + '" id="rowId_' + washTypeId + '_' + countNumber + '">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
                <input type="hidden" id="editFlag_' + washTypeId + '_' + countNumber + '"  value="">\n\
                <input type="hidden" id="processNo_' + washTypeId + '_' + countNumber + '" name="process_no[' + washTypeId + ']" value="' + processId + '">\n\
                <input type="hidden" id="waterVal_' + washTypeId + '" name="wash_water[' + washTypeId + ']" value="">\n\
                <input type="hidden" id="washTypeId_' + washTypeId + '_' + countNumber + '" name="wash_type_id[]"  value="' + washTypeId + '">\n\
            ' + washTypeName + '</td>\n\
            <td>' + processName + '</td>\n\
            <td class="text-center">\n\
            <button class="btn btn-xs btn-primary tooltips vcenter" id="editBtn' + washTypeId + '_' + countNumber + '" title="@lang("label.EDIT_PRODUCT")" onclick="editProcess(' + washTypeId + ',' + countNumber + ');" data-id="' + processId + '"><i class="fa fa-edit text-white"></i></button>\n\
            <button onclick="deleteItem(' + washTypeId + ',' + countNumber + ');" class="btn btn-xs btn-danger tooltips vcenter" id="deleteBtn' + washTypeId + '_' + countNumber + '"  title="Remove Item"><i class="fa fa-trash text-white"></i></button>\n\
            </td></tr>';
        var previousWashTypeId = $("#washTypeId_" + washTypeId + '_' + countNumber).val();
        if (washTypeId != previousWashTypeId) {
            $('#processRows tr:last').before(row);
        } else {
            toastr.error('Error', "@lang('label.WASH_TYPE_IS_ALREADY_ADDED')", options);
            return false;
        }
        var multiProcessId = processId.toString();
        var seperatorArr = multiProcessId.split(",");
        var total = 0;
        $.each(seperatorArr, function (key, value) {
            var option = $("#processNo option[value='" + value + "']");
            option.attr("disabled", "disabled");
            $("#processNo option[value='" + value + "']").prop("selected", false);
            var totalnew = parseFloat($("#water-" + value).val());
            if (isNaN(totalnew)) {
                var totalnew = 0;
            }
            console.log(totalnew);
            total = total + totalnew;

        });
        $('#waterVal_' + washTypeId).val(total);
        $("#processNo").multiselect("refresh");

        var option = $("#washTypeId option[value='" + washTypeId + "']");
        option.attr("disabled", "disabled");
        $("#washTypeId option[value='" + washTypeId + "']").prop("selected", false);
        $('.js-source-states').select2();

        App.unblockUI();
    });

    /* Remove WashType wise Process */
    function deleteItem(washTypeId, countNumber) {

        var option = $("#washTypeId option[value='" + washTypeId + "']");
        option.removeAttr("disabled");
        var processNo = $('#processNo_' + washTypeId + '_' + countNumber).val();
        //console.log(processNo);return false;
        var newseperatorArr = processNo.split(",");

        $.each(newseperatorArr, function (key, value) {
            var option = $("#processNo option[value='" + value + "']");
            option.removeAttr("disabled", "disabled");
        });

        $('#processNo').multiselect("refresh");
        $('#rowId_' + washTypeId + '_' + countNumber).remove();
        //$('#waterVal_'+ washTypeId+ '_' + countNumber).val('');
        var rowCount = $('tbody#processRows tr').length;
        if (rowCount == 1) {
            $('#divNoProcessdata').show();
        }
    }

    /* Edit WashType wise Process */
    function editProcess(washTypeId, countNumber) {

        $('#addItemBtn').hide();
        $('#updateItemBtn').show();
        var counterTypeId = $('#editBtn' + washTypeId + '_' + countNumber).data("id");

        //set bg color to identify editted process
        $('.item-list-' + washTypeId + '_' + countNumber).addClass('bg-default');

        var editRowId = $('#editRowsId').val();

        var option = $("#washTypeId option[value='" + washTypeId + "']");
        option.removeAttr("disabled");
        $('#washTypeId').val(washTypeId);
        var processNo = $('#processNo_' + washTypeId + '_' + countNumber).val();

        //var multiProcessId = processNo.toString();
        var newseperatorArr = processNo.split(",");
        $.each(newseperatorArr, function (key, value) {
            var option = $("#processNo option[value='" + value + "']");
            option.removeAttr("disabled", "disabled");

        });
        $("#processNo").val(newseperatorArr);
        $('#processNo').multiselect("refresh");


        $("#editRowsId").val(washTypeId + '_' + countNumber);
        $('#editBtn' + washTypeId + '_' + countNumber).attr('disabled', true);
        $('#deleteBtn' + washTypeId + '_' + countNumber).attr('disabled', true);

        if (editRowId != '') {
            $('#editBtn' + editRowId).prop('disabled', true);
            $('#deleteBtn' + editRowId).prop('disabled', true);
        }

        $('.js-source-states').select2();

    }


    /* Item Update for Wash Type wise Process Add*/
    $('#updateProcess').click(function () {
        var washTypeId = $('#washTypeId').val();
        var washTypeName = $("#washTypeId option:selected").text();

        var processId = $("#processNo option:selected").map(function () {
            var processIdvalue = this.value;
            var processIdArr = processIdvalue.split('-');
            var identifier = processIdArr[0];
            var processId = processIdArr[1];
            return identifier + '-' + processId;
        }).get();
        var processName = $("#processNo option:selected").map(function () {
            var processNamevalue = this.text;

            var processNameArr = processNamevalue.split('.');
            var serialNo = processNameArr[0];
            var processName = processNameArr[1];
            return processName;
        }).get().join(' || ');



        var countNumber = count++;
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        if (washTypeId == '0') {
            toastr.error('Error', 'Please select WashType', options);
            return false;
        }

        if (processId == '') {
            toastr.error('Error', 'Please select Process', options);
            return false;
        }

        //when i edit one row then delete previous row
        var editRow = $("#editRowsId").val();
        if (editRow != '') {
            $('#rowId_' + editRow).remove();
        }


        $("#divNoProcessdata").css({"display": "none"});
        var rowCount = $('tbody#processRows tr').length;
        var row = '<tr class="item-list-' + washTypeId + '-' + countNumber + '" id="rowId_' + washTypeId + '_' + countNumber + '">\n\
                <td>\n\<input type="hidden" name="add_btn" value="1">\n\
                <input type="hidden" id="editFlag_' + washTypeId + '_' + countNumber + '"  value="">\n\
                <input type="hidden" id="processNo_' + washTypeId + '_' + countNumber + '" name="process_no[' + washTypeId + ']" value="' + processId + '">\n\
                                <input type="hidden" id="waterVal_' + washTypeId + '" name="wash_water[' + washTypeId + ']" value="">\n\
                <input type="hidden" id="washTypeId_' + washTypeId + '_' + countNumber + '" name="wash_type_id[]"  value="' + washTypeId + '">\n\
            ' + washTypeName + '</td>\n\
            <td>' + processName + '</td>\n\
            <td class="text-center">\n\
            <button class="btn btn-xs btn-primary tooltips vcenter " id="editBtn' + washTypeId + '_' + countNumber + '" title="@lang("label.EDIT_PRODUCT")" onclick="editProcess(' + washTypeId + ',' + countNumber + ');" data-id="' + processId + '"><i class="fa fa-edit text-white"></i></button>\n\
            <button onclick="deleteItem(' + washTypeId + ',' + countNumber + ');" class="btn btn-xs btn-danger tooltips vcenter" id="deleteBtn' + washTypeId + '_' + countNumber + '"  title="Remove Item"><i class="fa fa-trash text-white"></i></button>\n\
            </td></tr>';
        $('#processRows tr:last').before(row);

        $('#addItemBtn').show();
        $('#updateItemBtn').hide();
        var multiProcessId = processId.toString();
        var seperatorArr = multiProcessId.split(",");
        var total = 0;
        $.each(seperatorArr, function (key, value) {
            var option = $("#processNo option[value='" + value + "']");
            option.attr("disabled", "disabled");
            $("#processNo option[value='" + value + "']").prop("selected", false);
            var totalnew = parseFloat($("#water-" + value).val());
            if (isNaN(totalnew)) {
                var totalnew = 0;
            }
            console.log(totalnew);
            total = total + totalnew;
        });
        $('#waterVal_' + washTypeId).val(total);
        $("#processNo").multiselect("refresh");

        var option = $("#washTypeId option[value='" + washTypeId + "']");
        option.attr("disabled", "disabled");
        $("#washTypeId option[value='" + washTypeId + "']").prop("selected", false);
        $('.js-source-states').select2();
        App.unblockUI();

    });
</script>