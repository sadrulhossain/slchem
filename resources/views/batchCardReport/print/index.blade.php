<html>
    <head>
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="row">
            <div class="col-md-12 text-center check-padding">
                <img src="{{URL::to('/')}}/public/img/Sterling_Laundry_Logo.png" alt="sterling-laundry-logo"/></br>
                @lang('label.DEMAND_PRINT_HEADER_TWO')
                <h2 class="bold">@lang('label.BATCH_CARD_REPORT')</h2>
            </div>
        </div>

        <h5 class="bold bg-blue-dark bg-font-blue-dark" style="padding: 10px;">
            <p><b>@lang('label.TOTAL_QTY')  (@lang('label.PCS')) : {{ $totalQty }}</b></p>
            <span>@lang('label.DATE') : {{!empty($request->date)? Helper::dateFormat($request->date) :__('label.ALL')}}</span> | 
            <span>@lang('label.REFERENCE_NO') : {{!empty($request->search)? $request->search : __('label.ALL')}}</span> |
            <span>@lang('label.BUYER') : {{ !empty($request->buyer_id) ? !empty($buyerArr[$request->buyer_id])? $buyerArr[$request->buyer_id] : __('label.ALL'): __('label.ALL')}}</span> |
            <span>@lang('label.RECIPE') : {{ !empty($request->recipe) ? !empty($recipeArr[$request->recipe])? $recipeArr[$request->recipe]:__('label.ALL') : __('label.ALL') }}</span> | 
            <span>@lang('label.STYLE') : {{ !empty($request->style_id) ? !empty($styleArr[$request->style_id])? $styleArr[$request->style_id] : __('label.ALL') : __('label.ALL')}}</span> | 
            <span>@lang('label.SEASON') : {{ !empty($request->season_id) ? !empty($seasonArr[$request->season_id])? $seasonArr[$request->season_id] : __('label.ALL') : __('label.ALL')}}</span> | 
            <span>@lang('label.COLOR') : {{ !empty($request->color_id) ? !empty($colorArr[$request->color_id])? $colorArr[$request->color_id] : __('label.ALL') : __('label.ALL')}}</span> | 
            <span>@lang('label.WASH_MC_NO') : {{ !empty($request->machine) ? !empty($machineArr[$request->machine])? $machineArr[$request->machine] : __('label.ALL') : __('label.ALL')}}</span> | 
            <span>@lang('label.SHIFT') : {{ !empty($request->shift) ? !empty($shiftArr[$request->shift])? $shiftArr[$request->shift] : __('label.ALL') : __('label.ALL')}}</span> | 
            <span>@lang('label.OPERATOR_NAME') : {{!empty($request->operator_name)? $request->operator_name : __('label.ALL')}}</span> |
            <span>@lang('label.WASH_TYPE') : {{ !empty($request->wash_type_id) ? !empty($washTypeArr[$request->wash_type_id])? $washTypeArr[$request->wash_type_id] : __('label.ALL') : __('label.ALL')}}</span> | 
            <span>@lang('label.FACTORY') : {{ !empty($request->factory_id) ? !empty($factoryArr[$request->factory_id])? $factoryArr[$request->factory_id] : __('label.ALL') : __('label.ALL')}}</span>
        </h5>
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="center">
                    <th class=" vcenter text-center">@lang('label.SL_NO')</th>
                    <th class=" vcenter text-center">@lang('label.DATE')</th>
                    <th class=" vcenter text-center">@lang('label.TIME')</th>
                    <th class=" vcenter text-center" width="10%">@lang('label.REFERENCE_NO')</th>
                    <th class=" vcenter text-center">@lang('label.RECIPE')</th>
                    <th class=" vcenter text-center">@lang('label.STYLE')</th>
                    <th class=" vcenter text-center">@lang('label.SEASON')</th>
                    <th class=" vcenter text-center">@lang('label.COLOR')</th>
                    <th class=" vcenter text-center">@lang('label.BUYER')</th>
                    <th class=" vcenter text-center">@lang('label.FACTORY')</th>
                    <th class=" vcenter text-center">@lang('label.WASH_TYPE')</th>
                    <th class=" vcenter text-center">@lang('label.WASH_MC_NO')</th>
                    <th class=" vcenter text-center">@lang('label.QTY') (@lang('label.PCS'))</th>
                    <th class=" vcenter">@lang('label.OPERATOR_NAME')</th>
                    <th class=" vcenter text-center">@lang('label.SHIFT_NAME')</th>
                </tr>
            </thead>
            <tbody>
                @if (!$targetArr->isEmpty())
                <?php
                $page = Input::get('page');
                $page = empty($page) ? 1 : $page;
                $sl = ($page - 1) * Session::get('paginatorCount');
                ?>
                @foreach($targetArr as $target)

                <tr>
                    <td class="vcenter vcenter text-center">{{ ++$sl }}</td>
                    <td class=" vcenter text-center">{{ Helper::dateFormat($target->date) }}</td>
                    <td class=" vcenter text-center">{{ Helper::dateFormat($target->created_at) }}<br />{{ date('h:iA',strtotime($target->created_at)) }}</td>
                    <td class=" vcenter text-center">{{ $target->reference_no }}</td>
                    <td class=" vcenter text-center" width="10%">{{ $target->recipe_reference_no }}</td>
                    <td class=" vcenter text-center">{{ $target->style }}</td>
                    <td class=" vcenter text-center">{{ $target->season }}</td>
                    <td class=" vcenter text-center">{{ $target->color }}</td>
                    <td class=" vcenter text-center">{{ !empty($target->buyer_id)? $buyerArr[$target->buyer_id]: '' }}</td>
                    <td class=" vcenter text-center">{{ !empty($target->factory_id)? $factoryArr[$target->factory_id]: '' }}</td>
                    <td class=" vcenter text-center">{{ !empty($target->wash_type_id)? $washTypeArr[$target->wash_type_id]: '' }}</td>
                    <td class=" vcenter text-center">{{ $target->Machine->machine_no }}</td>
                    <td class=" vcenter text-center">{{ $target->wash_lot_quantity_piece }}</td>
                    <td class=" vcenter">{{ $target->operator_name }}</td>
                    <td class=" vcenter text-center">{{ !empty($target->shift_id)?$shiftArr[$target->shift_id]:'' }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="15" class="vcenter">@lang('label.NO_BATCH_CARD_FOUND')</td>
                </tr>
                @endif
            </tbody>
        </table>
        <table class="no-border">
            <tr>
                <td class="no-border text-left">@lang('label.REPORT_GENERATED_ON') {{ Helper::printDateTime(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}</td>
                <td class="no-border text-right col-md-6">
                    @lang('label.GENERATED_BY_RAJAKINI_SOFTWARE'),<span>&nbsp;@lang('label.POWERED_BY')</span><b>&nbsp;&nbsp;@lang('label.SWAPNOLOKE')</b>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function (event) {
                window.print();
            });
        </script>
    </body>
</html>