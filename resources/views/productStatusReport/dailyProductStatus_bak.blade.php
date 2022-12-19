@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.DAILY_SATUS_REPORT')
            </div>
        </div>
        <div class="portlet-body">

            <div class="row">
                <!-- Begin Filter-->
                {!! Form::open(array('group' => 'form', 'url' => 'monthlyProductStatusReport/filter','class' => 'form-horizontal')) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3">@lang('label.DATE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-3">
                                <div class="input-group date datepicker" data-date-format="dd-mm-yyyy" data-date-end-date="+0d" >
                                    {!! Form::text('date', Request::get('date'), ['id'=> 'date', 'class' => 'form-control', 'placeholder' => 'yyyy-mm-dd', 'readonly']) !!} 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="date">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-danger">{{ $errors->first('date') }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                                    <i class="fa fa-search"></i> @lang('label.GO')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- End Filter -->
            </div>

            @if($request->generate == 'true')
            <div class="row">
                <div class="col-md-offset-8 col-md-4" id="manageEvDiv">
                    @if(!empty($userAccessArr[61][6]))
                    <a class="btn btn-md btn-success vcenter tooltips" target="_blank" title="Click here to print this report"  href="{!! URL::full().'&view=print' !!}">
                        <i class="fa fa-print"></i> @lang('label.PRINT')
                    </a>
                    @endif
                    <!--                    <a class="btn btn-icon-only btn-warning tooltips vcenter" title="Download PDF" href="{!! URL::full().'&view=pdf' !!}">
                                            <i class="fa fa-download"></i>
                                        </a>-->
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="info">
                            <th class="text-center vcenter" rowspan="2">@lang('label.SL_NO')</th>
                            <th class="vcenter" rowspan="2">@lang('label.CHEMICAL_NAME')</th>
                            <th class="vcenter" rowspan="2">@lang('label.LOCATION')</th>
                            <th class="text-center vcenter" colspan="3">@lang('label.PREVIOUS_BALANCE')</th>
                            <th class="text-center vcenter" colspan="3">@lang('label.CHECK_IN') @lang('label.TODAY')</th>
                            <th class="text-center vcenter" colspan="3">@lang('label.TOTAL')</th>
                            <th class="text-center vcenter" colspan="3">@lang('label.ISSUE') @lang('label.TODAY')</th>
                            <th class="text-center vcenter" colspan="4">@lang('label.BALANCE') @lang('label.TODAY')</th>
                        </tr>
                        <tr class="info">
                            <th class="text-center vcenter">@lang('label.BEFORE')&nbsp;@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.BEFORE')&nbsp;@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.BEFORE')&nbsp;@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.TODAY')&nbsp;@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.TOTAL')&nbsp;@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.QTY')<br />(@lang('label.KG'))</th>
                            <th class="text-center vcenter">@lang('label.QTY_DETAILS')</th>
                            <th class="text-center vcenter">@lang('label.RATE')<br />(@lang('label.TAKA'))</th>
                            <th class="text-center vcenter">@lang('label.AMOUNT')<br />(@lang('label.TAKA'))</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($targetArr))
                        <?php
                        $sl = $totalPreAmnt = $totalRcvAmnt = $totalIssueAmnt = $totalAmnt = $totalBalanceAmnt = 0;
                        ?>
                        @foreach($targetArr as $data)
                        <?php
                        $totalPreAmnt += $data['prev_date_balance_amount'];
                        $totalRcvAmnt += $data['this_date_amount'];
                        $totalAmnt += ($data['total_amount']);
                        $totalIssueAmnt += $data['issue_amount'];
                        $totalBalanceAmnt += $data['balance_amount'];
                        ?>
                        <tr>
                            <td class="text-center vcenter">{!! ++$sl !!}</td>
                            <td class="vcenter">{!! $data['name'] !!}</td>
                            <td class="vcenter">{!! $data['location'] !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['prev_date_balance_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['prev_date_balance_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['prev_date_balance_amount']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['this_date_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['this_date_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['this_date_amount']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['total_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['total_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['total_amount']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['issue_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['issue_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['issue_amount']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['balance_qty'],6) !!}</td>
                            <td class="text-center vcenter">{!! Helper::unitConversion($data['balance_qty']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['balance_rate']) !!}</td>
                            <td class="text-center vcenter">{!! Helper::numberFormat($data['balance_amount']) !!}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-right vcenter" colspan="5"><strong>@lang('label.TOTAL_TAKA')</strong></td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalPreAmnt) !!}</b></td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalRcvAmnt) !!}</b></td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalAmnt) !!}</b></td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalIssueAmnt) !!}</b></td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter">&nbsp;</td>
                            <td class="text-center vcenter"><b>{!! Helper::numberFormat($totalBalanceAmnt) !!}</b></td>
                        </tr>
                        @else
                        <tr>
                            <td class="vcenter" colspan="19">@lang('label.NO_PRODUCT_FOUND_AT_THIS_MONTH')</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            @endif
        </div>	
    </div>
</div>
<script type="text/javascript">

    $(document).on("click", "#generateStatus", function (e) {
        var date = $('#date').val();
        e.preventDefault();

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };


        $.ajax({
            url: "{{URL::to('/dailyProductStatusReport/dailyProduct')}}",
            //for cron job
            type: "GET",
            //For Day to Day Generate
//            type: "POST",
//            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            contentType: false,
            processData: false,
            //For Day to Day Generate
//            data: {
//                date: date,
//            },
            success: function () {
                toastr.success("Daily Product Status generated Successfully", options);
                $('#div').hide();
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
@stop