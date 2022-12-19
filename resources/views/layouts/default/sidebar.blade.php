<?php
$controllerName = Request::segment(1);
$controllerName = Request::route()->getName();
$currentControllerFunction = Route::currentRouteAction();
$currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
$controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
$routeName = strtolower(Route::getFacadeRoot()->current()->uri());

//Admin setup menus
$adminSetupMenu = [
    !empty($userAccessArr[1][1]) ? 1 : '', !empty($userAccessArr[2][1]) ? 1 : '', !empty($userAccessArr[3][1]) ? 1 : ''
    , !empty($userAccessArr[4][1]) ? 1 : '', !empty($userAccessArr[5][1]) ? 1 : '', !empty($userAccessArr[6][1]) ? 1 : ''
    , !empty($userAccessArr[7][1]) ? 1 : '', !empty($userAccessArr[8][1]) ? 1 : '', !empty($userAccessArr[9][1]) ? 1 : ''
    , !empty($userAccessArr[10][1]) ? 1 : '', !empty($userAccessArr[11][1]) ? 1 : '', !empty($userAccessArr[12][1]) ? 1 : ''
    , !empty($userAccessArr[13][1]) ? 1 : '', !empty($userAccessArr[14][1]) ? 1 : '', !empty($userAccessArr[15][1]) ? 1 : ''
    , !empty($userAccessArr[16][1]) ? 1 : '', !empty($userAccessArr[17][1]) ? 1 : '', !empty($userAccessArr[18][1]) ? 1 : ''
    , !empty($userAccessArr[19][1]) ? 1 : '', !empty($userAccessArr[20][1]) ? 1 : '', !empty($userAccessArr[20][10]) ? 1 : ''
    , !empty($userAccessArr[21][1]) ? 1 : '', !empty($userAccessArr[22][1]) ? 1 : '', !empty($userAccessArr[23][1]) ? 1 : ''
    , !empty($userAccessArr[24][1]) ? 1 : '', !empty($userAccessArr[25][1]) ? 1 : '', !empty($userAccessArr[26][1]) ? 1 : ''
    , !empty($userAccessArr[27][1]) ? 1 : '', !empty($userAccessArr[28][1]) ? 1 : '', !empty($userAccessArr[29][1]) ? 1 : ''
    , !empty($userAccessArr[30][1]) ? 1 : '', !empty($userAccessArr[31][1]) ? 1 : '', !empty($userAccessArr[32][1]) ? 1 : ''
    , !empty($userAccessArr[33][1]) ? 1 : '', !empty($userAccessArr[34][1]) ? 1 : '', !empty($userAccessArr[35][1]) ? 1 : ''
    , !empty($userAccessArr[36][1]) ? 1 : '', !empty($userAccessArr[37][1]) ? 1 : '', !empty($userAccessArr[66][1]) ? 1 : ''
    , !empty($userAccessArr[67][1]) ? 1 : '', !empty($userAccessArr[68][1]) ? 1 : ''
    , !empty($userAccessArr[69][1]) ? 1 : '', !empty($userAccessArr[69][15]) ? 1 : ''
];
$accessControlMenu = [!empty($userAccessArr[68][1]) ? 1 : '', !empty($userAccessArr[69][1]) ? 1 : '', !empty($userAccessArr[69][15]) ? 1 : ''];
$manufacturerMenu = [!empty($userAccessArr[10][1]) ? 1 : '', !empty($userAccessArr[11][1]) ? 1 : ''];
$substanceMenu = [!empty($userAccessArr[15][1]) ? 1 : '', !empty($userAccessArr[16][1]) ? 1 : ''];
$hazardMenu = [!empty($userAccessArr[18][1]) ? 1 : '', !empty($userAccessArr[19][1]) ? 1 : ''];
$productMenu = [!empty($userAccessArr[20][1]) ? 1 : '', !empty($userAccessArr[20][10]) ? 1 : ''];

//product checkin menus
$productCheckInMenu = [!empty($userAccessArr[38][2]) ? 1 : '', !empty($userAccessArr[39][2]) ? 1 : '', !empty($userAccessArr[40][1]) ? 1 : ''];

//adjustment menus
$adjustmentMenu = [!empty($userAccessArr[41][2]) ? 1 : '', !empty($userAccessArr[42][1]) ? 1 : '', !empty($userAccessArr[43][1]) ? 1 : ''];

//recipe menus
$recipeMenu = [!empty($userAccessArr[44][1]) ? 1 : '', !empty($userAccessArr[45][1]) ? 1 : ''];

//production menus
$productionMenu = [
    !empty($userAccessArr[46][1]) ? 1 : '', !empty($userAccessArr[47][2]) ? 1 : ''
    , !empty($userAccessArr[48][1]) ? 1 : '', !empty($userAccessArr[49][1]) ? 1 : '', !empty($userAccessArr[50][1]) ? 1 : ''
];

//substore demand menus
$substoreDemandMenu = [!empty($userAccessArr[51][2]) ? 1 : '', !empty($userAccessArr[52][1]) ? 1 : '', !empty($userAccessArr[53][1]) ? 1 : ''];


//report menus
$reportsMenu = [
    !empty($userAccessArr[54][1]) ? 1 : '', !empty($userAccessArr[55][1]) ? 1 : '', !empty($userAccessArr[56][1]) ? 1 : ''
    , !empty($userAccessArr[57][1]) ? 1 : '', !empty($userAccessArr[58][1]) ? 1 : '', !empty($userAccessArr[59][1]) ? 1 : ''
    , !empty($userAccessArr[60][1]) ? 1 : '', !empty($userAccessArr[61][1]) ? 1 : '', !empty($userAccessArr[62][1]) ? 1 : ''
    , !empty($userAccessArr[63][1]) ? 1 : '', !empty($userAccessArr[64][1]) ? 1 : '', !empty($userAccessArr[65][1]) ? 1 : ''
    , !empty($userAccessArr[70][1]) ? 1 : '', !empty($userAccessArr[71][1]) ? 1 : '', !empty($userAccessArr[73][1]) ? 1 : ''
];
$checkInReportMenu = [!empty($userAccessArr[55][1]) ? 1 : '', !empty($userAccessArr[56][1]) ? 1 : ''];
$consumptionReportMenu = [!empty($userAccessArr[58][1]) ? 1 : '', !empty($userAccessArr[59][1]) ? 1 : ''];
$productStatusReportMenu = [!empty($userAccessArr[61][1]) ? 1 : '', !empty($userAccessArr[62][1]) ? 1 : ''];
$substoreDemandReportMenu = [!empty($userAccessArr[64][1]) ? 1 : '', !empty($userAccessArr[65][1]) ? 1 : ''];
?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul id="addsidebarFullMenu" class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" >
            <!--li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li-->

            <!-- start dashboard menu -->
            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/dashboard')}}" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> @lang('label.DASHBOARD')</span>
                </a>
            </li>

            <!-- User Group wise common feature set up -->
            @if(in_array(1, $adminSetupMenu))
            <li <?php
            $current = ( in_array($controllerName, array('user', 'usergroup', 'department', 'designation', 'configuration', 'certificate', 'shade', 'processtype', 'process', 'producttoprocess', 'buyer', 'factory', 'manufacturer', 'suppliertype', 'supplier',
                        'mfaddressbook', 'productcategory', 'measureunit', 'secondaryunit', 'productfunction', 'product', 'producttosupplier', 'producttomanufacturer', 'substancecas', 'substanceec', 'hazardcategory', 'pictogram', 'ppe', 'hazardclass', 'style',
                        'garmentstype', 'washtype', 'wash', 'machinemodel', 'machine', 'hydromachine', 'dryertype', 'season', 'color'
                        , 'dryermachine', 'dryercategory', 'aclusergrouptoaccess'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle bold">
                    <i class="fa fa-cogs bold"></i>
                    <span class="title">@lang('label.ADMINISTRATIVE_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(in_array(1, $accessControlMenu))
                    <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess', 'usergroup'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.ACCESS_CONTROL')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[69][15]))
                            <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess')) && ($routeName != 'aclusergrouptoaccess/moduleaccesscontrol' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/aclUserGroupToAccess/userGroupToAccess')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER_GROUP_ACCESS')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[69][1]))
                            <li <?php $current = ($routeName == 'aclusergrouptoaccess/moduleaccesscontrol' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('aclUserGroupToAccess/moduleAccessControl/')}}" class="nav-link">
                                    <span class="title">@lang('label.MODULE_WISE_ACCESS')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[68][1]))
                            <li <?php $current = ( in_array($controllerName, array('usergroup'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/userGroup')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER_GROUP')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[1][1]))
                    <li <?php $current = ( in_array($controllerName, array('user'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/user')}}" class="nav-link ">
                            <span class="title">@lang('label.USER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[2][1]))
                    <li <?php $current = ( in_array($controllerName, array('department'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/department')}}" class="nav-link ">
                            <span class="title">@lang('label.DEPARTMENT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[3][1]))
                    <li <?php $current = ( in_array($controllerName, array('designation'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/designation')}}" class="nav-link ">
                            <span class="title">@lang('label.DESIGNATION')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[4][1]))
                    <li <?php $current = ( in_array($controllerName, array('ppe'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/ppe')}}" class="nav-link ">
                            <span class="title">@lang('label.MENU_PPE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[6][1]))
                    <li <?php $current = ( in_array($controllerName, array('configuration'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/configuration')}}" class="nav-link ">
                            <span class="title">@lang('label.CONFIGURATION')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[5][1]))
                    <li <?php $current = ( in_array($controllerName, array('productcategory'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/productCategory')}}" class="nav-link ">
                            <span class="title">@lang('label.PRODUCT_CATEGORY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[7][1]))
                    <li <?php $current = ( in_array($controllerName, array('measureunit'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/measureUnit')}}" class="nav-link ">
                            <span class="title">@lang('label.MEASUREMENT_UNIT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[8][1]))
                    <li <?php $current = ( in_array($controllerName, array('secondaryunit'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/secondaryUnit')}}" class="nav-link ">
                            <span class="title">@lang('label.SECONDARY_UNIT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[9][1]))
                    <li <?php $current = ( in_array($controllerName, array('certificate'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/certificate')}}" class="nav-link ">
                            <span class="title">@lang('label.CERTIFICATE')</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(1, $manufacturerMenu))
                    <li <?php $current = ( in_array($controllerName, array('manufacturer', 'mfaddressbook'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.MANUFACTURER')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[10][1]))
                            <li <?php $current = ( in_array($controllerName, array('manufacturer'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/manufacturer')}}" class="nav-link ">
                                    <span class="title">@lang('label.MANUFACTURER_LIST')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[11][1]))
                            <li <?php $current = ( in_array($controllerName, array('mfaddressbook'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/mfAddressBook')}}" class="nav-link">
                                    <span class="title">@lang('label.MANUFACTURER_ADDRESS_BOOK')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[12][1]))
                    <li <?php $current = ( in_array($controllerName, array('suppliertype'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/supplierType')}}" class="nav-link ">
                            <span class="title">@lang('label.SUPPLIER_TYPE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[13][1]))
                    <li <?php $current = ( in_array($controllerName, array('supplier'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/supplier')}}" class="nav-link ">
                            <span class="title">@lang('label.SUPPLIER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[14][1]))
                    <li <?php $current = ( in_array($controllerName, array('productfunction'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/productFunction')}}" class="nav-link ">
                            <span class="title">@lang('label.PRODUCT_FUNCTION')</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(1, $substanceMenu))
                    <li <?php $current = ( in_array($controllerName, array('substancecas', 'substanceec'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.SUBSTANCE')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[15][1]))
                            <li <?php $current = ( in_array($controllerName, array('substancecas'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/substanceCas')}}" class="nav-link ">
                                    <span class="title">@lang('label.CAS')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[16][1]))
                            <li <?php $current = ( in_array($controllerName, array('substanceec'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/substanceEc')}}" class="nav-link">
                                    <span class="title">@lang('label.EC')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(in_array(1, $hazardMenu))
                    <li <?php $current = ( in_array($controllerName, array('hazardcategory', 'pictogram', 'hazardclass'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.HAZARD')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[17][1]))
                            <li <?php $current = ( in_array($controllerName, array('hazardcategory'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/hazardCategory')}}" class="nav-link ">
                                    <span class="title">@lang('label.HAZARD_CATEGORY')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[18][1]))
                            <li <?php $current = ( in_array($controllerName, array('pictogram'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/pictogram')}}" class="nav-link">
                                    <span class="title">@lang('label.PICTOGRAM')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[19][1]))
                            <li <?php $current = ( in_array($controllerName, array('hazardclass'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/hazardClass')}}" class="nav-link">
                                    <span class="title">@lang('label.HAZARD_CLASS')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(in_array(1, $productMenu))
                    <li <?php $current = ( in_array($controllerName, array('product'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.PRODUCT')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[20][1]))
                            <li <?php $current = ( in_array($controllerName, array('product')) && ($routeName != 'product/approvalproduct' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/product')}}" class="nav-link ">
                                    <span class="title">@lang('label.PRODUCT_LIST')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[20][10]))
                            <li <?php $current = ( $routeName == 'product/approvalproduct' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/product/approvalProduct')}}" class="nav-link">
                                    <span class="title">@lang('label.PENDING_FOR_APPROVAL')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[21][1]))
                    <li <?php $current = ( in_array($controllerName, array('producttosupplier'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/productToSupplier')}}" class="nav-link ">
                            <span class="title">@lang('label.PRODUCT_TO_SUPPLIER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[22][1]))
                    <li <?php $current = ( in_array($controllerName, array('producttomanufacturer'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/productToManufacturer')}}" class="nav-link ">
                            <span class="title">@lang('label.PRODUCT_TO_MANUFACTURER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[23][1]))
                    <li <?php $current = ( in_array($controllerName, array('shade'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/shade')}}" class="nav-link ">
                            <span class="title">@lang('label.SHADE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[24][1]))
                    <li <?php $current = ( in_array($controllerName, array('processtype'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/processType')}}" class="nav-link ">
                            <span class="title">@lang('label.PROCESS_TYPE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[25][1]))
                    <li <?php $current = ( in_array($controllerName, array('process'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/process')}}" class="nav-link ">
                            <span class="title">@lang('label.PROCESS')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[26][1]))
                    <li <?php $current = ( in_array($controllerName, array('producttoprocess'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/productToProcess')}}" class="nav-link ">
                            <span class="title">@lang('label.PRODUCT_TO_PROCESS')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[27][1]))
                    <li <?php $current = ( in_array($controllerName, array('buyer'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/buyer')}}" class="nav-link ">
                            <span class="title">@lang('label.BUYER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[28][1]))
                    <li <?php $current = ( in_array($controllerName, array('factory'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/factory')}}" class="nav-link ">
                            <span class="title">@lang('label.FACTORY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[29][1]))
                    <li <?php $current = ( in_array($controllerName, array('garmentstype'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/garmentsType')}}" class="nav-link ">
                            <span class="title">@lang('label.GARMENTS_TYPE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[30][1]))
                    <li <?php $current = ( in_array($controllerName, array('washtype'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/washType')}}" class="nav-link ">
                            <span class="title">@lang('label.WASH_TYPE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[66][1]))
                    <li <?php $current = ( in_array($controllerName, array('season'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/season')}}" class="nav-link ">
                            <span class="title">@lang('label.SEASON')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[67][1]))
                    <li <?php $current = ( in_array($controllerName, array('color'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/color')}}" class="nav-link ">
                            <span class="title">@lang('label.COLOR')</span>
                        </a>
                    </li>
                    @endif
                    <!-- <li <?php $current = (in_array($controllerName, array('wash'))) ? 'start active open' : ''; ?> class="nav-item  {{$current}}">
                    <a href="{{url('/wash')}}" class="nav-link ">
                        <span class="title">@lang('label.WASH')</span>
                    </a>
                </li> -->
                    @if(!empty($userAccessArr[31][1]))
                    <li <?php $current = ( in_array($controllerName, array('style'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/style')}}" class="nav-link ">
                            <span class="title">@lang('label.STYLE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[32][1]))
                    <li <?php $current = ( in_array($controllerName, array('dryercategory'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('dryerCategory')}}" class="nav-link ">
                            <span class="title">@lang('label.DRYER_CATEGORY')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[33][1]))
                    <li <?php $current = ( in_array($controllerName, array('dryertype'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('dryerType')}}" class="nav-link ">
                            <span class="title">@lang('label.DRYER_TYPE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[34][1]))
                    <li <?php $current = ( in_array($controllerName, array('dryermachine'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('dryerMachine')}}" class="nav-link ">
                            <span class="title">@lang('label.DRYER_MACHINE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[35][1]))
                    <li <?php $current = ( in_array($controllerName, array('machinemodel'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/machineModel')}}" class="nav-link ">
                            <span class="title">@lang('label.WASHING_MACHINE_TYPE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[36][1]))
                    <li <?php $current = ( in_array($controllerName, array('machine'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('machine')}}" class="nav-link ">
                            <span class="title">@lang('label.WASHING_MACHINE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[37][1]))
                    <li <?php $current = ( in_array($controllerName, array('hydromachine'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('hydroMachine')}}" class="nav-link ">
                            <span class="title">@lang('label.HYDRO_MACHINE')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!-- Product check In and Initial Balance Setup -->
            @if(in_array(1, $productCheckInMenu))
            <li <?php $current = ( in_array($controllerName, array('productcheckin', 'initialbalance', 'productcheckinlist'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-check-square-o"></i>
                    <span class="title">@lang('label.PRODUCT_CHECK_IN')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[38][2]))
                    <li <?php $current = ( in_array($controllerName, array('initialbalance'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/initialBalance')}}" class="nav-link ">
                            <span class="title">@lang('label.PRODUCT_INITIAL_BALANCE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[39][2]))
                    <li <?php $current = ( in_array($controllerName, array('productcheckin')) && ($routeName != 'productcheckinlist' ) && ($routeName != 'productcheckin/approvalcheckin' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/productCheckIn')}}" class="nav-link ">
                            <span class="title">@lang('label.PRODUCT_PURCHASE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[40][1]))
                    <li <?php $current = ( $routeName == 'productcheckinlist' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/productCheckInList')}}" class="nav-link">
                            <span class="title">@lang('label.PURCHASED_ITEM_LIST')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!-- Product Adjustment/Consumption -->
            @if(in_array(1, $adjustmentMenu))
            <li <?php $current = ( in_array($controllerName, array('productconsumption', 'productconsumptionlist'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-minus-square-o"></i>
                    <span class="title">@lang('label.PRODUCT_CONSUMPTION')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[41][2]))
                    <li <?php $current = ( in_array($controllerName, array('productconsumption')) && ($routeName != 'productconsumptionlist' ) && ($routeName != 'productconsumptionapproval' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/productConsumption')}}" class="nav-link ">
                            <span class="title">@lang('label.PRODUCT_CONSUME')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[43][1]))
                    <li <?php $current = ( $routeName == 'productconsumptionlist' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/productConsumptionList')}}" class="nav-link">
                            <span class="title">@lang('label.CONSUMED_ITEM_LIST')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!-- Setup Recipe --->
            @if(in_array(1, $recipeMenu))
            <li <?php $current = ( in_array($controllerName, array('recipe', 'finalizedrecipe'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-random"></i>
                    <span class="title">@lang('label.RECIPE')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[44][1]))
                    <li <?php $current = ( in_array($controllerName, array('recipe')) && ($routeName != 'finalizedrecipe' ) && ($routeName != 'recipe/cloned' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('recipe')}}" class="nav-link ">@lang('label.RECIPE_LIST')</a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[45][1]))
                    <li <?php $current = ( $routeName == 'finalizedrecipe' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('finalizedRecipe')}}" class="nav-link ">@lang('label.FINALIZED_RECIPE_LIST')</a>
                    </li>
                    @endif
                    <!--                    <li <?php //$current = ($routeName == 'recipe/cloned') ? 'start active open' : '';           ?> class="nav-item  {{$current}}">
                    <a href="{{url('recipe/cloned')}}" class="nav-link ">@lang('label.CLONED_RECIPE_LIST')</a>
                </li>-->
                </ul>
            </li>
            @endif
            <!-- Setup Production Batch Card / Demand --->
            @if(in_array(1, $productionMenu))
            <li <?php $current = ( in_array($controllerName, array('batchcard', 'demand', 'generatedemand', 'deliverchemicals', 'deliveredchemicalslist'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-industry"></i>
                    <span class="title">@lang('label.PRODUCTION')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[46][1]))
                    <li <?php $current = ( in_array($controllerName, array('batchcard'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/batchCard')}}" class="nav-link ">
                            <span class="title"> @lang('label.BATCH_CARD')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[47][2]))
                    <li <?php $current = ( $routeName == 'generatedemand' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('generateDemand')}}" class="nav-link ">@lang('label.GENERATE_DEMAND_LETTER')</a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[48][1]))
                    <li <?php $current = ( $routeName == 'demand' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('demand')}}" class="nav-link ">@lang('label.LIST_OF_DEMAND_LETTERS')</a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[49][1]))
                    <li <?php $current = ( $routeName == 'deliverchemicals' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('deliverChemicals')}}" class="nav-link ">@lang('label.DELIVER_CHEMICALS')</a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[50][1]))
                    <li <?php $current = ( $routeName == 'deliveredchemicalslist' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('deliveredChemicalsList')}}" class="nav-link ">@lang('label.DELIVERED_CHEMICAL_LIST')</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!-- Substore Demand -->
            @if(in_array(1, $substoreDemandMenu))
            <li <?php $current = ( in_array($controllerName, array('substoredemand', 'demandtodeliver', 'delivereddemandlist'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-hdd-o"></i>
                    <span class="title">@lang('label.SUBSTORE_DEMAND')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[51][2]))
                    <li <?php $current = ( in_array($controllerName, array('substoredemand')) && ($routeName != 'substoredemand/deliverabledemand' ) && ($routeName != 'substoredemand/substoredlist' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/substoreDemand')}}" class="nav-link ">
                            <span class="title">@lang('label.GENERATE_SUBSTORE_DEMAND')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[52][1]))
                    <li <?php $current = ( $routeName == 'demandtodeliver' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('demandToDeliver')}}" class="nav-link">
                            <span class="title">@lang('label.DEMAND_TO_DELIVER')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[53][1]))
                    <li <?php $current = ( $routeName == 'delivereddemandlist' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('deliveredDemandList')}}" class="nav-link">
                            <span class="title">@lang('label.SUBSTORED_DEMAND_LIST')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <!-- start db backup menu -->
            @if(!empty($userAccessArr[72][1]))
            <li <?php $current = ( in_array($controllerName, array('dbbackup'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/dbBackup')}}" class="nav-link ">
                    <i class="fa fa-database"></i>
                    <span class="title"> @lang('label.DB_BACKUP')</span>
                </a>
            </li>
            @endif

            <!--- All Reports --->
            @if(in_array(1, $reportsMenu))
            <li
            <?php
            $current = ( in_array($controllerName, array('checkinreport', 'consumptionreport', 'ledgerreport', 'compliancereport'
                        , 'batchcardreport', 'dailycheckinreport', 'monthlycheckinreport', 'dailyconsumptionreport', 'monthlyconsumptionreport'
                        , 'dailyproductstatusreport', 'monthlyproductstatusreport', 'dailysubstorereport', 'monthlysubstorereport'
                        , 'reconciliationreport', 'detailedledgerreport', 'dbbackupdownloadlogreport'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-server"></i>
                    <span class="title">@lang('label.REPORTS')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(!empty($userAccessArr[73][1]))
                    <li <?php $current = ( in_array($controllerName, array('dbbackupdownloadlogreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/dbBackupDownloadLogReport')}}" class="nav-link ">
                            <span class="title"> @lang('label.DB_BACKUP_DOWNLOAD_LOG_REPORT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[54][1]))
                    <li <?php $current = ( $routeName == 'batchcardreport' ) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item">
                        <a href="{{url('batchCardReport')}}" class="nav-link ">
                            <span class="title"> @lang('label.BATCH_CARD_REPORT')</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(1, $checkInReportMenu))
                    <li <?php $current = ( in_array($controllerName, array('dailycheckinreport', 'monthlycheckinreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.CHECK_IN_REPORT')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[55][1]))
                            <li <?php $current = ( $routeName == 'dailycheckinreport' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('dailyCheckInReport')}}" class="nav-link ">@lang('label.DAILY_CHECK_IN')</a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[56][1]))
                            <li <?php $current = ( $routeName == 'monthlycheckinreport' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('monthlyCheckInReport')}}" class="nav-link ">@lang('label.MONTHLY_CHECK_IN')</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[57][1]))
                    <li <?php $current = ( $routeName == 'checkinreport' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('checkInReport')}}" class="nav-link ">@lang('label.STOCK_SUMMARY')</a>
                    </li>
                    @endif
                    @if(in_array(1, $consumptionReportMenu))
                    <li <?php $current = ( in_array($controllerName, array('dailyconsumptionreport', 'monthlyconsumptionreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.CONSUMPTION_REPORT')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[58][1]))
                            <li <?php $current = ( $routeName == 'dailyconsumptionreport' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('dailyConsumptionReport')}}" class="nav-link">@lang('label.DAILY_CONSUMPTION')</a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[59][1]))
                            <li <?php $current = ( $routeName == 'monthlyconsumptionreport' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('monthlyConsumptionReport')}}" class="nav-link">@lang('label.MONTHLY_CONSUMPTION')</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[60][1]))
                    <li <?php $current = ( in_array($controllerName, array('compliancereport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item">
                        <a href="{{url('/complianceReport')}}" class="nav-link ">
                            <span class="title"> @lang('label.PRODUCT_COMPLIANCE_REPORT')</span>
                        </a>
                    </li>
                    @endif
                    @if(in_array(1, $productStatusReportMenu))
                    <li <?php $current = ( in_array($controllerName, array('dailyproductstatusreport', 'monthlyproductstatusreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.PRODUCT_STATUS_REPORT')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[61][1]))
                            <li <?php $current = ( $routeName == 'dailyproductstatusreport' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/dailyProductStatusReport?generate=true&date='.date('Y-m-d'))}}" class="nav-link ">@lang('label.DAILY_PRODUCT_STATUS')</a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[62][1]))
                            <li <?php $current = ( $routeName == 'monthlyproductstatusreport' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/monthlyProductStatusReport')}}" class="nav-link ">@lang('label.MONTHLY_PRODUCT_STATUS')</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[63][1]))
<!--                    <li <?php $current = ( $routeName == 'ledgerreport' ) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item">
                        <a href="{{url('ledgerReport')}}" class="nav-link ">
                            <span class="title"> @lang('label.DAILY_LEDGER')</span>
                        </a>
                    </li>-->
                    @endif
                    @if(in_array(1, $substoreDemandReportMenu))
                    <li <?php $current = ( in_array($controllerName, array('dailysubstorereport', 'monthlysubstorereport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">@lang('label.SUBSTORE_DEMAND_REPORT')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[64][1]))
                            <li <?php $current = ( $routeName == 'dailysubstorereport' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('dailySubstoreReport')}}" class="nav-link ">@lang('label.DAILY_SUBSTORE')</a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[65][1]))
                            <li <?php $current = ( $routeName == 'monthlysubstorereport' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('monthlySubstoreReport')}}" class="nav-link ">@lang('label.MONTHLY_SUBSTORE')</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[70][1]))
                    <li <?php $current = ( in_array($controllerName, array('reconciliationreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item">
                        <a href="{{url('/reconciliationReport')}}" class="nav-link ">
                            <span class="title"> @lang('label.RECONCILIATION_REPORT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[71][1]))
                    <li <?php $current = ( in_array($controllerName, array('detailedledgerreport'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item">
                        <a href="{{url('/detailedLedgerReport')}}" class="nav-link ">
                            <span class="title"> @lang('label.DETAILED_LEDGER_REPORT')</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
        </ul>
    </div>
</div>