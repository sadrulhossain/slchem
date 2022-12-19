@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <!-- BEGIN PORTLET-->
    @include('layouts.flash')
    <!-- END PORTLET-->

    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cart-plus"></i>@lang('label.CONFIGURATION')
            </div>
            <div class="actions">
                @if(!empty($userAccessArr[6][3]))
                <a class="btn btn-default btn-sm create-new" href="{{ URL::to('configuration/1/edit') }}"> @lang('label.UPDATE_CONFIGURATION')
                    <i class="fa fa-plus create-new"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>@lang('label.CHECK_IN_CUT_OFF_TIME')</th>
                        <td>{!! date("h:i A",strtotime($configurationArr->check_in_time)) !!}</td>
                    </tr>
                    <tr>
                        <th>@lang('label.PRODUCT_SERIAL_CODE')</th>
                        <td>{!! $configurationArr->serial_code !!}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- END CONTENT BODY -->
@stop
