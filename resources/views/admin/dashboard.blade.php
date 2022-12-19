@extends('layouts.default.master')

@section('data_count')
@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}

</div>
@endif

<div class="portlet-body">
    <div class="page-bar">
        <ul class="page-breadcrumb margin-top-10">
            <li>
                <a href="{{url('dashboard')}}">@lang('label.HOME')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>@lang('label.DASHBOARD')</span>
            </li>
        </ul>
        <div class="page-toolbar margin-top-15">
            <h5 class="dashboard-date font-blue-madison bold"><span class="icon-calendar"></span> @lang('label.TODAY_IS') <span class="font-blue-madison">{!! $toDate !!}</span> </h5>   
        </div>
    </div>
    <div class="row margin-top-10">
        <!--Start Todays Total Finalized Recipe -->
        @if(!empty($userAccessArr[45][1]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php
            $counter = 0;
            $href = 'javascript:;';
            $tooltip = '';
            $title = '';
            if (!empty($activesRecipieCount) && $activesRecipieCount != 0) {
                $counter = $activesRecipieCount;
                $href = '#totalActiveRecipieModal';
                $tooltip = 'tooltips';
                $title = __('label.TOTAL_ACTIVE_RECIPE');
            }
            ?>
            <a class="dashboard-stat-v2  dashboard-stat green-meadow {{$tooltip}}" href="{!! $href !!}" id="todaysRecipieId" data-toggle="modal" title="{!! $title !!}">
                <div class="visual">
                    <i class="fa fa-bookmark"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="{!! $counter !!}">
                        <span class="font-size-22 ">
                            {!! $counter !!}
                        </span>
                    </div>
                    <div class="desc"><span class="font-size-14 bold margin-left-20">@lang('label.TOTAL_ACTIVE_RECIPE')</span></div>
                </div>
            </a>
        </div>
        @endif
        <!--End Todays Total Finalized Recipe-->

        <!--Start Todays Batch Card-->
        @if(!empty($userAccessArr[46][1]) || !empty($userAccessArr[54][1]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php
            $counter = 0;
            $href = 'javascript:;';
            $tooltip = '';
            $title = '';
            if (!empty($todaysBatchCardCount) && $todaysBatchCardCount != 0) {
                $counter = $todaysBatchCardCount;
                $href = '#todaysBatchCardModal';
                $tooltip = 'tooltips';
                $title = __('label.CLICK_HERE_TO_VIEW_TODAYS_BATCH_CARD');
            }
            ?>
            <a class="dashboard-stat-v2  dashboard-stat yellow-mint {{$tooltip}}" href="{!! $href !!}" id="todaysBatchCardId" data-toggle="modal" title="{!! $title !!}">
                <div class="visual">
                    <i class="fa fa-ticket"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="{!! $counter !!}">
                        <span class="font-size-22 ">
                            {!! $counter !!}
                        </span>
                    </div>
                    <div class="desc"><span class="font-size-14 bold margin-left-20">@lang('label.TODAYS_BATCH_CARD')</span></div>
                </div>
            </a>
        </div>
        @endif
        <!--End Todays Batch Card-->

        <!--Start Todays Batch Card with Demand Letter-->
        @if(!empty($userAccessArr[50][1]) || !empty($userAccessArr[61][1]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php
            $counter = 0;
            $href = 'javascript:;';
            $tooltip = '';
            $title = '';
            if (!empty($todaysTotalBatchCardWithDemandLetterCount) && $todaysTotalBatchCardWithDemandLetterCount != 0) {
                $counter = $todaysTotalBatchCardWithDemandLetterCount;
                $href = '#todaysBatchCardWithDemandLetterModal';
                $tooltip = 'tooltips';
                $title = __('label.CLICK_HERE_TO_VIEW_TODAYS_ BATCH_CARD_WITH_DEMAND_LETTER');
            }
            ?>
            <a class="dashboard-stat-v2  dashboard-stat blue-dark {{$tooltip}}" href="{!! $href !!}" id="todaysBatchCardWithDemandLetterId" data-toggle="modal" title="{!! $title !!}">
                <div class="visual">
                    <i class="fa fa-file-text"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="{!! $counter !!}">
                        <span class="font-size-22 ">
                            {!! $counter !!}
                        </span>
                    </div>
                    <div class="desc font-size-15"><span class="font-size-14 bold margin-left-20">@lang('label.TODAYS_ BATCH_CARD_WITH_DEMAND_LETTER')</span></div>
                </div>
            </a>
        </div>
        @endif 
        <!--End Todays Batch Card with Demand Letter-->

        <!--Start Todays Delivered Store Demand Letter-->
        @if(!empty($userAccessArr[53][1]) || !empty($userAccessArr[64][1]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

            <?php
            $counter = 0;
            $href = 'javascript:;';
            $tooltip = '';
            $title = '';
            if (!empty($todaysDeliverdStoreDemandLetterCount) && $todaysDeliverdStoreDemandLetterCount != 0) {
                $counter = $todaysDeliverdStoreDemandLetterCount;
                $href = '#todaysDeliverdStoreDemandLetterModal';
                $tooltip = 'tooltips';
                $title = __('label.CLICK_HERE_TO_VIEW_TODAYS_DELIVERD_STORE_DEMAND_LETTER');
            }
            ?>

            <a class="dashboard-stat-v2  dashboard-stat yellow-gold {{$tooltip}}" href="{!! $href !!}" id="todaysDeliverdStoreDemandLetterId" data-toggle="modal" title="{!! $title !!}">
                <div class="visual">
                    <i class="fa fa-file-text"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="{!! $counter !!}">
                        <span class="font-size-22 ">
                            {!! $counter !!}
                        </span>
                    </div>
                    <div class="desc"><span class="font-size-14 bold margin-left-20">@lang('label.TODAYS_DELIVERD_STORE_DEMAND_LETTER')</span></div>
                </div>
            </a>
        </div>
        @endif
        <!--End Todays Delivered Store Demand Letter-->
        
        <!--Start Total Active Products-->
        @if(!empty($userAccessArr[20][1]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php
            $counter = 0;
            $href = 'javascript:;';
            $tooltip = '';
            $title = '';
            if (!empty($totalActiveProductsCount) && $totalActiveProductsCount != 0) {
                $counter = $totalActiveProductsCount;
                $href = '#totalActiveProductsModal';
                $tooltip = 'tooltips';
                $title = __('label.CLICK_HERE_TO_VIEW_TOTAL_ACTIVE_PRODUCTS');
            }
            ?>

            <a class="dashboard-stat-v2  dashboard-stat purple-studio {{$tooltip}}" href="{!! $href !!}" id="totalActiveProductsId" data-toggle="modal" title="{!! $title !!}">
                <div class="visual">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="{!! $counter !!}">
                        <span class="font-size-22 ">{{$totalActiveProductsCount}}
                            {!! $counter !!}
                        </span>
                    </div>
                    <div class="desc"><span class="font-size-14 bold margin-left-20">@lang('label.TOTAL_ACTIVE_PRODUCTS')</span></div>
                </div>
            </a>
        </div>
        @endif
        <!--End Total Active Products-->

        <!--Start Low Quantity Products-->
        @if(!empty($userAccessArr[20][10]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php
            $counter = 0;
            $href = 'javascript:;';
            $tooltip = '';
            $title = '';
            if (!empty($lowQuantityProductsCount) && $lowQuantityProductsCount != 0) {
                $counter = $lowQuantityProductsCount;
                $href = '#lowQuantityProductsModal';
                $tooltip = 'tooltips';
                $title = __('label.CLICK_HERE_TO_VIEW_LOW_QUANTITY_PRODUCTS');
            }
            ?>

            <a class="dashboard-stat-v2  dashboard-stat red-haze  {{$tooltip}}" href="{!! $href !!}" id="lowQuantityProductsId" data-toggle="modal" title="{!! $title !!}">
                <div class="visual">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="{!! $counter !!}">
                        <span class="font-size-22 ">{{$lowQuantityProductsCount}}
                            {!! $counter !!}
                        </span>
                    </div>
                    <div class="desc"><span class="font-size-14 bold margin-left-20">@lang('label.LOW_QUANTITY_PRODUCTS')</span></div>
                </div>
            </a>
        </div>
        @endif
        <!--End Low Quantity Products-->

        <!--Start Todays Total Batch Card Quantity-->
        @if(!empty($userAccessArr[54][1]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php
            $counter = 0;
            $href = 'javascript:;';
            $tooltip = '';
            $title = '';
            if (!empty($todaysToalBatchCardQuantity['piecesum']) && $todaysToalBatchCardQuantity['piecesum'] != 0) {
                $counter = $todaysToalBatchCardQuantity['piecesum'];
                $href = '#todaysToalBatchCardQuantityModal';
                $tooltip = 'tooltips';
                $title = __('label.CLICK_HERE_TO_VIEW_TODAYS_TOTAL_BATCH_CARD_QUANTITY');
            }
            ?>
            <a class="dashboard-stat-v2  dashboard-stat blue-madison {{$tooltip}}" href="{!! $href !!}" id="todaysToalBatchCardQuantityId" data-toggle="modal" title="{!! $title !!}">
                <div class="visual">
                    <i class="fa fa-ticket"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="{!! $counter !!}">
                        <span  class="font-size-22 ">
                            {!! $counter !!}
                        </span>
                    </div>
                    <div class="desc"><span class="font-size-14 bold margin-left-20">@lang('label.TODAYS_TOTAL_BATCH_CARD_QUANTITY')</span></div>
                </div>
            </a>
        </div>
        @endif
        <!--End Todays Total Batch Card Quantity-->

        <!--Start Total Reconcilition Mismatch--> 
        @if(!empty($userAccessArr[70][1]))
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php
            $counter = 0;
            $href = 'javascript:;';
            $tooltip = '';
            $title = '';
            if (!empty($productStatusArr['mismatch']) && $productStatusArr['mismatch'] != 0) {
                $counter = $productStatusArr['mismatch'];
                $href = '#todaysReconciliationMismatchModal';
                $tooltip = 'tooltips';
                $title = __('label.CLICK_HERE_TO_VIEW_TOTAL_RECONCILIATION_MISMATCH');
            }
            ?>
            <a class="dashboard-stat-v2  dashboard-stat red-thunderbird {{$tooltip}}" href="{!! $href !!}" id="todaysReconciliationMismatchId" data-toggle="modal"  title="{!! $title !!}">
                <div class="visual">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                </div>
                <div class="details">
                    <div class="number" data-counter="counterup" data-value="{!! $counter !!}">
                        <span class="font-size-22 ">{!! $counter !!}</span>
                    </div>
                    <div class="desc"><span class="font-size-14 bold margin-left-20">@lang('label.TOTAL_RECONCILIATION_MISMATCH')</span></div>
                </div>
            </a>
        </div>
        @endif
        <!--End Total Reconcilition Mismatch--> 
    </div>
    <div class="row margin-top-10">

        <!--******** START :: LAST 7 DAYS CHECKIN SUMMARY ************-->
        @if(!empty($userAccessArr[40][1]) || !empty($userAccessArr[55][1]) || !empty($userAccessArr[56][1]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.LAST_ONE_WEEK_CHECKIN_SUMMARY')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="last7DaysCheckinSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--******** END :: LAST 7 DAYS CHECKIN SUMMARY ************-->
        <!--******** START :: LAST 10 GENERATED BATCH CARD SUMMARY ************-->
        @if(!empty($userAccessArr[48][1]) || !empty($userAccessArr[50][1]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.LAST_10_GENERATED_BATCH_CARD_SUMMARY')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="last10GeneratedBatchCardSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--******** END :: LAST 10 GENERATED BATCH CARD SUMMARY ************-->
        <!-- START:: Top 10 Most Consumed Product Chart -->
        @if(!empty($userAccessArr[71][1]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.TOP_10_MOST_CONSUMED_PRODUCT')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <!--                        <a class="btn btn-circle btn-default" href="{{ URL::to('paymentStatus') }}"> See All </a>-->
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="top10MostConsumedProduct" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!-- END:: Top 10 Most Consumed Product Chart -->
        <!--******** START :: LAST 15 DAYS SUBSTORE DEMAND SUMMARY ************-->
        @if(!empty($userAccessArr[52][1]) || !empty($userAccessArr[53][1]) || !empty($userAccessArr[64][1]) || !empty($userAccessArr[65][1]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.LAST_15_DAYS_SUBSTORE_DEMAND_SUMMARY')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="last15DaysSubstoreDemandSummary" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!--******** END :: LAST 15 DAYS SUBSTORE DEMAND SUMMARY ************-->
        <!-- START:: No of Products Related with Certificate -->
        @if(!empty($userAccessArr[60][1]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.NO_OF_PRODUCTS_WITH_RELATED_CERTIFICATE')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <!--                        <a class="btn btn-circle btn-default" href="{{ URL::to('paymentStatus') }}"> See All </a>-->
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="certificateRelatedProducts" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!-- END:: No of Products Related with Certificate -->
        <!-- START:: No of Products Related with Buyer -->
        @if(!empty($userAccessArr[60][1]))
        <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark">
                            @lang('label.NO_OF_PRODUCTS_WITH_RELATED_BUYER')
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <!--                        <a class="btn btn-circle btn-default" href="{{ URL::to('paymentStatus') }}"> See All </a>-->
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="buyerRelatedProducts" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        @endif
        <!-- END:: No of Products Related with Buyer -->
    </div>
</div>

<!--***************MODAL******************-->
<!--Todays Total Finalized Recipe modal-->
<div class="modal container fade" id="totalActiveRecipieModal" tabindex="-1">
    <div id="totalActiveRecipieViewModal">
        <!--    ajax will be load here-->
    </div>
</div>

<!--Todays Batch Card modal-->
<div class="modal container fade" id="todaysBatchCardModal" tabindex="-1">
    <div id="todaysBatchCardViewModal">
        <!--    ajax will be load here-->
    </div>
</div>

<!--Todays Batch Card With Demand Letter Modal-->
<div class="modal container fade" id="todaysBatchCardWithDemandLetterModal" tabindex="-1">
    <div id="todaysBatchCardWithDemandLetterViewModal">
        <!--    ajax will be load here-->
    </div>
</div>

<!--Todays Deliverd Store Demand Letter Modal-->
<div class="modal container fade" id="todaysDeliverdStoreDemandLetterModal" tabindex="-1">
    <div id="todaysDeliverdStoreDemandLetterViewModal">
        <!--    ajax will be load here-->
    </div>
</div>

<!--Total Active Products Modal-->
<div class="modal container fade" id="totalActiveProductsModal" tabindex="-1">
    <div id="totalActiveProductsViewModal">
        <!--    ajax will be load here-->
    </div>
</div>

<!--Low Quantity Products Modal-->
<div class="modal container fade" id="lowQuantityProductsModal" tabindex="-1">
    <div id="lowQuantityProductsViewModal">
        <!--    ajax will be load here-->
    </div>
</div>

<!--Todays Total Batch Card Quantity Modal-->
<div class="modal container fade" id="todaysToalBatchCardQuantityModal" tabindex="-1">
    <div id="todaysToalBatchCardQuantityViewModal">
        <!--    ajax will be load here-->
    </div>
</div>

<!--Total Reconcilition Mismatch Modal-->
<div class="modal container fade" id="todaysReconciliationMismatchModal" tabindex="-1">
    <div id="todaysReconciliationMismatchViewModal">
        <!--    ajax will be load here-->
    </div>
</div>
<!-- START:: Certificate Related Modal -->
<div class="modal container fade" id="certificateRelatedModal" tabindex="-1">
    <!--    <div class="modal-dialog modal-full">-->
    <div id="showcertificateRelatedProductModal">
    </div>
    <!--    </div>-->
</div>
<!-- END:: Certificate Related Modal -->

<!-- START:: Buyer Related Product Modal -->
<div class="modal container fade" id="buyerRelatedModal" tabindex="-1">
    <!--    <div class="modal-dialog modal-full">-->
    <div id="showBuyerRelatedProductModal">
    </div>
    <!--    </div>-->
</div>
<!-- END:: Buyer Related Product Modal -->

<script src="{{asset('public/js/apexcharts.min.js')}}" type="text/javascript"></script>
<script>
$(function () {

// Start Todays Total Finalized Recipe modal
$(".table-head-fixer-color-grey-mint").tableHeadFixer();
$(document).on("click", "#todaysRecipieId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/totalActiveRecipieView')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#totalActiveRecipieViewModal").html('');
        },
        success: function (res) {
        $("#totalActiveRecipieViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//END Todays Total Finalized Recipe modal


//set Todays Batch Card modal
$(document).on("click", "#todaysBatchCardId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/todaysBatchCardView')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#todaysBatchCardViewModal").html('');
        },
        success: function (res) {
        $("#todaysBatchCardViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//end Todays Batch Card modal

//set Todays Batch Card with Demand Letter modal
$(document).on("click", "#todaysBatchCardWithDemandLetterId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/todaysBatchCardWithDemandLetterView')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#todaysBatchCardWithDemandLetterViewModal").html('');
        },
        success: function (res) {
        $("#todaysBatchCardWithDemandLetterViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//end Todays Deliverd Demand Letter modal

//set Todays Deliverd Store Demand Letter modal
$(document).on("click", "#todaysDeliverdStoreDemandLetterId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/todaysDeliverdStoreDemandLetterView')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#todaysDeliverdStoreDemandLetterViewModal").html('');
        },
        success: function (res) {
        $("#todaysDeliverdStoreDemandLetterViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//end Todays Deliverd Store Demand Letter modal

//set Total Active Products
$(document).on("click", "#totalActiveProductsId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/totalActiveProductsView')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#totalActiveProductsViewModal").html('');
        },
        success: function (res) {
        $("#totalActiveProductsViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//end Total Active Products

//set Low Quantity Products modal
$(document).on("click", "#lowQuantityProductsId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/lowQuantityProductsView')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#lowQuantityProductsViewModal").html('');
        },
        success: function (res) {
        $("#lowQuantityProductsViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//end Low Quantity Products modal

//set Todays Total Batch Card Quantity modal
$(document).on("click", "#todaysToalBatchCardQuantityId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/todaysToatlBatchCardQuantityView')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#todaysToalBatchCardQuantityViewModal").html('');
        },
        success: function (res) {
        $("#todaysToalBatchCardQuantityViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//end Todays Total Batch Card Quantity modal

//set Todays Reconciliation Mismatch modal
$(document).on("click", "#todaysReconciliationMismatchId", function (e) {
e.preventDefault();
$.ajax({
url: "{{ URL::to('dashboard/todaysReconciliationMismatchView')}}",
        type: "POST",
        dataType: "json",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        beforeSend: function () {
        $("#todaysReconciliationMismatchViewModal").html('');
        },
        success: function (res) {
        $("#todaysReconciliationMismatchViewModal").html(res.html);
        $('.tooltips').tooltip();
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer();
        },
        error: function (jqXhr, ajaxOptions, thrownError) {
        }
}); //ajax
});
//end Todays Reconciliation Mismatch modal


//Start :: Top 10 Most Consumed Product ********************************//
var top10MostConsumedProductChartOptions = {
chart: {
type: 'bar',
        height: 400,
},
        series: [

<?php
if (!empty($sourceList)) {
    foreach ($sourceList as $sourceId => $sourceName) {
        ?>
                {
                name: '<?php echo $sourceName; ?>',
                        data: [
        <?php
        if (!empty($top10ConsumedProductArr)) {
            foreach ($top10ConsumedProductArr as $productId => $totalQuantity) {
                $quantity = !empty($sourceWiseTotalQty[$productId][$sourceId]) ? $sourceWiseTotalQty[$productId][$sourceId] : 0;
                echo '"' . $quantity . '", ';
            }
        }
        ?>
                        ]
                },
        <?php
    }
}
?>


        ],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: false,
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: "@lang('label.PRODUCTS')",
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                        hideOverlappingLabels: true,
                        showDuplicates: true,
                        trim: false,
                        minHeight: undefined,
                        maxHeight: 180,
                        offsetX: 0,
                        offsetY: 0,
                        formatter: function (val) {
                        return trimString(val);
                        },
                        format: undefined,
                },
                categories: [
<?php
if (!empty($top10ConsumedProductArr)) {
    foreach ($top10ConsumedProductArr as $productId => $totalQuantity) {
        $productName = !empty($productList[$productId]) ? $productList[$productId] : '';
        echo '"' . $productName . '", ';
    }
}
?>
                ],
        },
        yaxis: {
        title: {
        text: "@lang('label.QUANTITY') (@lang('label.KG'))"
        }
        },
        fill: {
        //opacity: 1
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.25,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.95,
                        opacityTo: 0.95,
                        stops: [50, 0, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(6)
        }
        }
        }
};
var top10MostConsumedProductChart = new ApexCharts(document.querySelector("#top10MostConsumedProduct"), top10MostConsumedProductChartOptions);
top10MostConsumedProductChart.render();
//End :: Top 10 Most Consumed Product *******************************//


//******************* Certificate Related Products ******************//
var certificateIdArr = [];
var certificateValArr = [];
<?php
if (!empty($certificateWiseProductArr)) {
    foreach ($certificateWiseProductArr as $certiificateId => $val) {
        $certificateIds = !empty($certiificateId) ? $certiificateId : '';
        $certificateVals = !empty($val) ? $val : 0;
        ?>
        certificateIdArr.push({{$certificateIds}});
        certificateValArr.push({{$certificateVals}});
        <?php
    }
}
?>

var certificateRelatedProductOptions = {
chart: {
type: 'bar',
        height: 400,
        events: {
        click:function(event, chartContext, config) {
        var dataIndex = config.dataPointIndex;
        var certificateId = certificateIdArr[dataIndex];
        var certificateVal = certificateValArr[dataIndex];
        if (typeof certificateId == 'undefined' || certificateVal == 0){
        return false;
        }
        var options = {
        closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
        };
        $.ajax({
        url: "{{ URL::to('dashboard/getCertificateRelatedProducts')}}",
                type: "POST",
                dataType: "json",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                certificate_id: certificateId,
                },
                beforeSend: function () {
                //                        App.blockUI({boxed: true});
                },
                success: function (res) {
                $("#certificateRelatedModal").modal("show");
                $("#showcertificateRelatedProductModal").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
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
                //                        App.unblockUI();
                }
        }); //ajax
        }
        },
},
        series: [{
        name: "@lang('label.NO_OF_PRODUCTS')",
                data: [
<?php
if (!empty($certificateWiseProductArr)) {
    foreach ($certificateWiseProductArr as $certificaeId => $noOfProduct) {
        ?>
                        "{{$noOfProduct}}",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val;
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: "@lang('label.CERTIFICATES')",
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                        hideOverlappingLabels: true,
                        showDuplicates: true,
                        trim: false,
                        minHeight: undefined,
                        maxHeight: 180,
                        offsetX: 0,
                        offsetY: 0,
                        formatter: function (val) {
                        return trimString(val);
                        },
                        format: undefined,
                },
                categories: [
<?php
if (!empty($certificateWiseProductArr)) {
    foreach ($certificateWiseProductArr as $certificaeId => $noOfProduct) {
        $certificateName = !empty($certificateList[$certificaeId]) ? $certificateList[$certificaeId] : '';
        echo "'$certificateName', ";
    }
}
?>
                ],
        },
        yaxis: {
        title: {
        text: "@lang('label.NO_OF_PRODUCTS')"
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  val
        }
        }
        }
};
var certificateRelatedProductChart = new ApexCharts(document.querySelector("#certificateRelatedProducts"), certificateRelatedProductOptions);
certificateRelatedProductChart.render();
//****************** END OF Certificate Related Products ************//

//last 7 days checkin summary
var last7DaysCheckinSummaryOptions = {
chart: {
height: 400,
        type: 'line',
        shadow: {
        enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
        },
        toolbar: {
        show: false
        }
},
        colors: [ '#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#1BA39C'
                , '#E35B5A', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04'
                , '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: false,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return parseFloat(val).toFixed(2)
                },
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: [ '#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE'
                                , '#1BA39C', '#E35B5A', '#F2784B', '#369EAD', '#5E738B'
                                , '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        stroke: {
        curve: 'smooth'
        },
        series: [
<?php
foreach ($lastSevenDaysCheckedinproductList as $productId => $productName) {
    ?>
            {
            name: "{{$productName}}",
                    data: [
    <?php
    foreach ($dayArr as $dayYmd => $dayjMY) {
        $quantity = !empty($lastSevenDaysCheckedinArr[$dayYmd][$productId]) ? $lastSevenDaysCheckedinArr[$dayYmd][$productId] : 0.00;
        ?>

                        "{{$quantity}}",
    <?php }
    ?>
                    ]
            },
    <?php
}
?>

        ],
        grid: {
        borderColor: '#e7e7e7',
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                },
        },
        markers: {

        size: 6
        },
        xaxis: {
        categories: [
<?php
if (!empty($dayArr)) {
    foreach ($dayArr as $dayYmd => $dayjMY) {
        ?>
                "{{$dayjMY}}",
        <?php
    }
}
?>
        ],
                title: {
                text: "@lang('label.DATE')"
                }
        },
        yaxis: {
        title: {
        text: "@lang('label.QUANTITY') (@lang('label.KG'))"
        }
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return parseFloat(val).toFixed(6)
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        }
}

var last7DaysCheckinSummary = new ApexCharts(document.querySelector("#last7DaysCheckinSummary"), last7DaysCheckinSummaryOptions);
last7DaysCheckinSummary.render();
//end of last 7 days checkin summary

//*************** start :: last 10 generated batch card summary ************************//
var last10GeneratedBatchCardSummaryOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [
<?php
if (!empty($batchCardStatusList)) {
    foreach ($batchCardStatusList as $cardIndex => $cardStatus) {
        ?>
                {
                name: "{{$cardStatus}}",
                        data: [
        <?php
        if (!empty($lastTenBatchCardList)) {
            foreach ($lastTenBatchCardList as $batchCardId => $refNo) {
                $noOfDL = !empty($lastTenBatchCardArr[$batchCardId][$cardIndex]) ? $lastTenBatchCardArr[$batchCardId][$cardIndex] : 0;
                echo "'$noOfDL', ";
            }
        }
        ?>
                        ]
                },
        <?php
    }
}
?>
        ],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: false,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE'
                , '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B'
                , '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: false,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: true,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return trimString(val);
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($lastTenBatchCardList)) {
    foreach ($lastTenBatchCardList as $batchCardId => $refNo) {
        echo "'$refNo', ";
    }
}
?>
                ],
                title: {
                text: "@lang('label.BATCH_CARDS')",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "@lang('label.NO_OF_DEMAND_LETTERS')"
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return val
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        }
};
var last10GeneratedBatchCardSummary = new ApexCharts(document.querySelector("#last10GeneratedBatchCardSummary"), last10GeneratedBatchCardSummaryOptions);
last10GeneratedBatchCardSummary.render();
//*************** end :: last 10 generated batch card summary **************************//

//*************** start :: last 15 days substore demand summary ************************//
var last15DaysSubstoreDemandSummaryOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [
<?php
if (!empty($substoreDemandStatusList)) {
    foreach ($substoreDemandStatusList as $sDIndex => $sDStatus) {
        ?>
                {
                name: "{{$sDStatus}}",
                        data: [
        <?php
        if (!empty($subDayArr)) {
            foreach ($subDayArr as $dayYmd => $dayjMY) {
                $noOfDL = !empty($lastFifteenDaysSubstoreDemandArr[$dayYmd][$sDIndex]) ? $lastFifteenDaysSubstoreDemandArr[$dayYmd][$sDIndex] : 0;
                echo "'$noOfDL', ";
            }
        }
        ?>
                        ]
                },
        <?php
    }
}
?>
        ],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: false,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE'
                , '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B'
                , '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: false,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
                rotate: - 60,
                rotateAlways: true,
                hideOverlappingLabels: true,
                showDuplicates: true,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return trimString(val);
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($subDayArr)) {
    foreach ($subDayArr as $dayYmd => $dayjMY) {
        ?>
                        "{{$dayjMY}}",
        <?php
    }
}
?>
                ],
                title: {
                text: "@lang('label.DATE')",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "@lang('label.NO_OF_DEMAND_LETTERS')"
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return val
        }
        }
        },
        legend: {
        position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                offsetY: 0,
                offsetX: - 5
        }
};
var last15DaysSubstoreDemandSummary = new ApexCharts(document.querySelector("#last15DaysSubstoreDemandSummary"), last15DaysSubstoreDemandSummaryOptions);
last15DaysSubstoreDemandSummary.render();
//*************** end :: last 15 days substore demand summary **************************//

//******************* START:: Buyer Related Products ******************//
var buyerIdArr = [];
var buyerValArr = [];
<?php
if (!empty($buyerRelatedProductCountArr)) {
    foreach ($buyerRelatedProductCountArr as $buyerId => $val) {
        $buyerIds = !empty($buyerId) ? $buyerId : '';
        $buyerVals = !empty($val) ? $val : 0;
        ?>
        buyerIdArr.push({{$buyerIds}});
        buyerValArr.push({{$buyerVals}});
        <?php
    }
}
?>

var buyerRelatedProductOptions = {
chart: {
type: 'bar',
        height: 400,
        events: {
        click:function(event, chartContext, config) {
        var dataIndex = config.dataPointIndex;
        var buyerId = buyerIdArr[dataIndex];
        var buyerVal = buyerValArr[dataIndex];
        if (typeof buyerId == 'undefined' || buyerVal == 0){
        return false;
        }
        var options = {
        closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
        };
        $.ajax({
        url: "{{ URL::to('dashboard/getBuyerRelatedProducts')}}",
                type: "POST",
                dataType: "json",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                buyer_id: buyerId,
                },
                beforeSend: function () {
                //                        App.blockUI({boxed: true});
                },
                success: function (res) {
                $("#buyerRelatedModal").modal("show");
                $("#showBuyerRelatedProductModal").html(res.html);
                $('.tooltips').tooltip();
                //table header fix
                $(".table-head-fixer-color").tableHeadFixer();
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
                //                        App.unblockUI();
                }
        }); //ajax
        }
        },
},
        series: [{
        name: "@lang('label.NO_OF_PRODUCTS')",
                data: [
<?php
if (!empty($buyerRelatedProductCountArr)) {
    foreach ($buyerRelatedProductCountArr as $buyerId => $noOfProducts) {
        ?>
                        "{{$noOfProducts}}",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#C8D046', '#7F6084', '#4B77BE', '#E35B5A', '#3598DC', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8877A9', '#555555'],
        dataLabels: {
        enabled: true,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val;
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        title: {
        text: "@lang('label.BUYERS')",
        },
                labels: {
                show: true,
                        rotate: - 60,
                        rotateAlways: true,
                        hideOverlappingLabels: true,
                        showDuplicates: true,
                        trim: false,
                        minHeight: undefined,
                        maxHeight: 180,
                        offsetX: 0,
                        offsetY: 0,
                        formatter: function (val) {
                        return trimString(val);
                        },
                        format: undefined,
                },
                categories: [
<?php
if (!empty($buyerRelatedProductCountArr)) {
    foreach ($buyerRelatedProductCountArr as $buyerId => $noOfProducts) {
        $buyerName = !empty($buyerList[$buyerId]) ? $buyerList[$buyerId] : '';
        echo "'$buyerName', ";
    }
}
?>
                ],
        },
        yaxis: {
        title: {
        text: "@lang('label.NO_OF_PRODUCTS')"
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
        tooltip: {
        y: {
        formatter: function (val) {
        return  val
        }
        }
        }
};
var buyerRelatedProductChart = new ApexCharts(document.querySelector("#buyerRelatedProducts"), buyerRelatedProductOptions);
buyerRelatedProductChart.render();
//****************** END OF Buyer Related Products ************//

});
function trimString(str){
var dot = '';
if (str.length >= 20){
dot = '...';
}

var returnStr = str.substring(0, 20) + dot;
return returnStr;
}



</script>
@endsection