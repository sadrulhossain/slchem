@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i>{{__('label.DB_BACKUP')}} 
            </div>
            <div class="tools">

            </div>
        </div>
        <div class="portlet-body">
            {{ Form::open(array('group' => 'form', 'url' => 'dbBackup/filter','class' => 'form-horizontal')) }}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="fromDate">{{__('label.FROM_DATE')}} :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date backup-date">
                                {{ Form::text('from_date', Request::get('from_date'), array('id'=> 'fromDate', 'class' => 'form-control', 'placeholder' => 'Enter From Date', 'size' => '16', 'readonly' => true)) }}
                                <span class="input-group-btn">
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
                        <label class="col-md-4 control-label" for="toDate">{{__('label.TO_DATE')}} :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <div class="input-group date backup-date">
                                {{ Form::text('to_date', Request::get('to_date'), array('id'=> 'toDate', 'class' => 'form-control', 'placeholder' => 'Enter To Date', 'size' => '16', 'readonly' => true)) }}
                                <span class="input-group-btn">
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
                    <div class="text-center">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i>{{__('label.GENERATE')}} 
                        </button>
                    </div>

                </div>
            </div>

            @if (Request::get('generate') == 'true')

            <div class="table-responsive">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">{{__('label.SL_NO')}}</th>
                                <th class="text-center">{{__('label.DATE')}}</th>
                                <th class="text-center">{{__('label.FILE')}}</th>
                                @if(!empty($userAccessArr[72][17]))
                                <th class='text-center'>{{__('label.ACTION')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($filedata))
                            <?php
                            $sl = 0;
                            ?>
                            @foreach($filedata as $file)

                            <tr class="contain-center">
                                <td class="text-center">{{++$sl}}</td>
                                <td class="text-center">{{date('d F Y', strtotime(date('Y-m-d', $file['filetime'])))}}</td>
                                <td class="text-center">{{$file['filename']}}</td>
                                @if(!empty($userAccessArr[72][17]))
                                <td class="text-center">

                                    <a target="_blank" href="{{URL::to('/')}}/{{$file['filepath']}}"  id="print" class="btn green keep-download-log" data-file-name="{{!empty($file['filename']) ? $file['filename'] : ''}}" title="Download" download>
                                        <i class="fa fa-download"></i> {{ __('label.DOWNLOAD') }}
                                    </a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6">{{__('label.NO_DATA_FOUND')}}</td>
                            </tr>
                            @endif 
                        </tbody>
                    </table>

                </div>
            </div>
            @endif
        </div>

        {{ Form::close() }}

    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $('.backup-date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            isRTL: App.isRTL(),
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            todayHighlight: true,
        });
        $(document).on("click", '.keep-download-log', function () {
            var downloadedFile = $(this).attr('data-file-name');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
            url: "{{URL::to('dbBackup/download')}}",
            type: "POST",
            dataType: 'json', // what to expect back from the PHP script, if anything
            
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                downloaded_file: downloadedFile,
            },
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
    });

</script>
@stop

