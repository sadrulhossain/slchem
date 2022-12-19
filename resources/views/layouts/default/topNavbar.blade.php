<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{URL::to('/dashboard')}}">
                <img src="{{URL::to('/')}}/public/img/logo.png" alt="logo" /> </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
				<!--<li class="show-hide-side-menu">
                    <a title="" data-container="body" class="btn-show-hide-link">
                        <i class="btn red-sunglo" >
                            <span id="fullMenu" data-fullMenu="1">{!! __('label.FULL_SCREEN') !!}</span> 
                        </i>
                    </a>
                </li>-->
				
                 
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" id="header_notification_bar" data-container="body"  data-original-title="Pending for Approval Notification" data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                        <i class="icon-bell" ></i>
                        <span class="badge badge-green-seagreen">{!! $totalCountReq !!}</span>

                    </a>

                    <ul class="dropdown-menu">
                        <li class="external">
                            <h3>@lang('label.YOU_HAVE')
                                @if($totalCountReq != 0)
                                <span class="bold">{!!$totalCountReq!!}</span> @lang('label.APPROVAL_REQUEST')
                                @else
                                <span class="bold"></span> @lang('label.NO_REQUEST_FOR_APPROVAL')
                                @endif     
                            </h3>
                        </li>
                        @if(!empty($userAccessArr[20][10]))
                        @if($countProducts->total != 0)
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="{{url('product/approvalProduct')}}">
                                        <span class="details">
                                            <span class="badge badge-success req-number">{!! $countProducts->total !!}</span>
                                            @lang('label.PRODUCT_REQUEST_PENDING')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
						@endif
                        
                        <!-- START :: Notification for Substore Demand for Group [A,B] -->
                        @if(!empty($userAccessArr[52][1]))
                            @if($countSubstoreDemand->total != 0)
                                <li>
                                    <ul class="dropdown-menu-list" data-handle-color="#637283">
                                        <li>
                                            <a href="{{url('demandToDeliver')}}">
                                        <span class="details">
                                            <span class="badge badge-warning req-number">{!! $countSubstoreDemand->total !!}</span>
                                            @lang('label.SUBSTORE_DEMAND_PENDING')
                                        </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endif
                         <!-- END :: Notification for Substore Demand Group [A,B] -->
                         
                        @if(!empty($userAccessArr[49][1]))
                        {{--@if($countDemandLetter->total != 0)--}}
                        {{--<li>--}}
                                {{--<ul class="dropdown-menu-list"  data-handle-color="#637283">--}}
                                {{--<li>--}}
                                {{--<a href="{{url('deliverChemicals')}}">--}}
                                {{--<span class="details">--}}
                                {{--<span class="badge badge-primary req-number">{!! $countDemandLetter->total !!}</span>--}}
                                {{--@lang('label.DELIVER_CHEMICALS_PENDING')--}}
                                {{--</span>--}}
                                {{--</a>--}}
                                {{--</li>--}}
                                {{--</ul>--}}
                                {{--</li>--}}
                        {{--@endif--}}
			@endif
			@if(!empty($userAccessArr[20][10]))
                        @if($countAvailableQtyArr->total != 0)
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="{{url('product/lowQuantityProduct')}}">
                                        <span class="details">
                                            <span class="badge badge-danger req-number">{!! $countAvailableQtyArr->total !!}</span>
                                            @lang('label.LOW_QUANTITY_PRODUCT')
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @endif

                        
                    </ul>
                </li>
                
                <li class="dropdown dropdown-user">

                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php
                        $user = Auth::user(); //get current user all information
                        if (!empty($user->photo)) {
                            ?>
                            <img alt="{{$user['username']}}" class="img-circle" src="{{URL::to('/')}}/public/uploads/user/{{$user->photo}}" />
                        <?php } else { ?>
                            <img alt="{{$user['username']}}" class="img-circle" src="{{URL::to('/')}}/public/img/unknown.png" />
                        <?php } ?>
                        <span class="username username-hide-on-mobile bold">@lang('label.WELCOME') {{$user->username}}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{url('changePassword')}}">
                                <i class="icon-key"></i>@lang('label.CHANGE_PASSWORD')</a>
                        </li>

                        <!--                        <li>
                                                    <a href="#" class="tooltips" title="My Profile">
                                                        <i class="icon-user"></i>@lang('label.MY_PROFILE')</a>
                                                </li>-->
                        <!--li>
                            <a href="app_calendar.html">
                                <i class="icon-calendar"></i> My Calendar </a>
                        </li-->
                        <!--li>
                            <a href="app_inbox.html">
                                <i class="icon-envelope-open"></i> My Inbox
                                <span class="badge badge-danger"> 3 </span>
                            </a>
                        </li-->
                        <!--li>
                            <a href="app_todo.html">
                                <i class="icon-rocket"></i> My Tasks
                                <span class="badge badge-success"> 7 </span>
                            </a>
                        </li-->
                        <li class="divider"> </li>

                        <li>
                            <a class="tooltips"  title="Logout" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                <i class="icon-logout bold"></i> @lang('label.LOGOUT')
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li>
                    <a class="tooltips logout-color" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" title="Logout">
                        <i class="icon-logout"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.show-tooltip').tooltip();
        $('.tooltips').tooltip();
    });
</script>