@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i>@lang('label.DB_BACKUP_DOWNLOAD_LOG_REPORT')
            </div>
            <div class="actions">
                <span class="text-right">

                </span>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            {!! Form::open(array('group' => 'form', 'url' => 'dbBackupDownloadLogReport/filter','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="fromDate">@lang('label.FROM_DATE') </label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker">
                                {!! Form::text('from_date', Request::get('from_date'), ['id'=> 'fromDate', 'class' => 'form-control', 'placeholder' =>'yyyy-mm-dd', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="fromDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('from_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="toDate">@lang('label.TO_DATE') </label>
                        <div class="col-md-8">
                            <div class="input-group date datepicker">
                                {!! Form::text('to_date', Request::get('to_date'), ['id'=> 'toDate', 'class' => 'form-control', 'placeholder' =>'yyyy-mm-dd', 'readonly' => '','autocomplete' => 'off', 'style' => 'min-width:150px']) !!} 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="toDate">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger">{{ $errors->first('to_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit">
                            @lang('label.GENERATE')
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <!-- End Filter -->
            @if(Request::get('generate') == 'true')
            <div class="row margin-top-20">
                <div class="col-md-12 text-right">
                    @if(!empty($request->generate) && $request->generate == 'true')
                    @if(!$logInfo->isEmpty())
                    @if(!empty($userAccessArr[73][6]))
                    <a class="btn btn-inline blue-soft tooltips vcenter" target="_blank" href="{{ URL::to($request->fullUrl().'&view=print') }}"  title="@lang('label.CLICK_HERE_TO_PRINT')">
                        <i class="fa fa-print"></i> @lang('label.PRINT')
                    </a>
                    @endif
                    @endif
                    @endif
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            {{__('label.FROM_DATE')}} : <strong>{{ !empty($request->from_date) ? $request->from_date : __('label.N_A') }} |</strong> 
                            {{__('label.TO_DATE')}} : <strong>{{ !empty($request->to_date) ? $request->to_date : __('label.N_A') }} </strong>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div style="max-height: 500px;" class="tableFixHead sample webkit-scrollbar">
                        <table class="table table-bordered table-striped table-head-fixer-color " id="dataTable">
                            <thead>
                                <tr class="blue-light">
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.SL_NO')</th>
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.DATE_TIME')</th>
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.DOWNLOADED_BY')</th>
                                    <th class="text-center vcenter bold" rowspan="2">@lang('label.DOWNLOADED_FILE')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(!$logInfo->isEmpty())
                                <?php $sl = 0; ?>
                                @foreach($logInfo as $info)
                                <tr>
                                    <td class="text-center vcenter">{{++$sl}}</td>
                                    <td class="text-center vcenter">{{date('d F Y h:i:s A', strtotime($info->log_time))}}</td>
                                    <td class="vcenter">{{$info->user}}</td>
                                    <td class="vcenter">{{$info->downloaded_file}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td class="vcenter text-danger" colspan="4">@lang('label.NO_DATA_FOUND')</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>	
    </div>
</div>
<!-- Modal start -->
<!--shipment details-->
<div class="modal fade" id="modalShipmentDetails" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showShipmentDetails"></div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        //table header fix
        $("#dataTable").tableHeadFixer();

        //shipment details modal
        $(".shipment-details").on("click", function (e) {
            e.preventDefault();
            var shipmentId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('/dbBackupDownloadLogReport/shipment')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId
                },
                success: function (res) {
                    $("#showShipmentDetails").html(res.html);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });

//        $('.sample').floatingScrollbar();
    });
</script>
@stop