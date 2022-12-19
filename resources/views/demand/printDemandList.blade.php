<html>
    <head>
        <title>@lang('label.RAJAKINI_CHEMICAL_INVENTORY_STERLING_GROUP')</title>
        <link rel="shortcut icon" href="{{URL::to('/')}}/public/img/favicon.ico" />
        <link href="{{asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')}}" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="{{URL::to('/')}}/public/img/Sterling_Laundry_Logo.png" alt="sterling-laundry-logo"/><br/>
                @lang('label.DEMAND_PRINT_HEADER_TWO')
            </div>
        </div>

        <div class="col-md-12 text-center">
            <h4 class="bold">@lang('label.DEMAND_PAPER_OF_CHEMICALS')</h4>
        </div>
        <?php $i = 0; ?>
        @foreach($targetArr as $target)
        @if($i==0)
        <table class="no-border">
            <thead>        
                <tr class="info">
                    <td class="vcenter" colspan="2"><strong>@lang('label.BATCH_CARD_NO') : {!! $target->batch_card !!}</strong></td>
                    <td class="vcenter"><strong>@lang('label.RECIPE_NO') : {!! $target->recipe_no !!}</strong></td>
                    <td class="vcenter"><strong>@lang('label.SHIFT') : {!! $target->shift !!}</strong></td>
                </tr>
                <tr>
                    <td width="25%">@lang('label.DATE')</td>
                    <td width="25%">{!! $target->date !!}</td>
                    <td width="25%">@lang('label.MACHINE_NO')</td>
                    <td width="25%">{!! $target->machine_no !!}</td>
                </tr>
                <tr>
                    <td class="vcenter">@lang('label.STYLE')</td>
                    <td class="vcenter">{!! $target->style !!}</td>
                    <td class="vcenter">@lang('label.FACTORY')</td>
                    <td class="vcenter">{!! $target->factory !!}</td>
                </tr>
                <tr>
                    <td>@lang('label.BUYER')</td>
                    <td class="vcenter">{!! $target->buyer !!}</td>
                    <td>@lang('label.GARMENTS_TYPE')</td>
                    <td>{!! $target->garments_type !!}</td>
                </tr>
                <tr>
                    <td>@lang('label.WASH_LOAD_QUANTITY_IN_KG_N_IN_PCS')</td>
                    <td class="vcenter"><strong>{!! $target->wash_lot_quantity_weight !!} &amp; {!!$target->wash_lot_quantity_piece !!}</strong></td>
                    <td>@lang('label.STATUS')</td>
                    <td><span class ="label-warning">{{$statusArr[$target->status]}}</span></td>
                </tr>
        </table>
        @endif

        
        
        <div class="row">
            <div class="demand-letter-offset demand-letter">
                <table class="table table-bordered box-size demand-token">
                    <tr class="text-center">
                        <td class="demand-letter-offset demand-letter"><strong>@lang('label.TOKEN_NO') : {!! $target->token_no !!}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="no-border">
            <thead>
                <tr class="header-color">
                    <th class="text-center ">@lang('label.SL_NO')</th>
                    <th>@lang('label.NAME_OF_CHEMICALS')</th>
                   <th class="vcenter text-center bold">@lang('label.DELIVERABLE_FROM_STORE')</th>
                    <th class="text-center">@lang('label.QUANTITY_IN_KG')</th>
                    <th class="vcenter text-center">@lang('label.QUANTITY')&nbsp;@lang('label.DETAILS')</th>
                </tr>
            </thead>
            <tbody>
                @if (!$productArr[$target->id]->isEmpty())
                <?php
                $sl = 0;
                
                ?>
                @foreach($productArr[$target->id] as $product)
                <?php
                    $deliveredFromSubstore = ($product->show_in_report == '1') ? 'line-cut':'';
                ?>
                <tr>
                    <td class="text-center ">{{ ++$sl }}</td>
                    <td class="vcenter text-left {{  $deliveredFromSubstore }}">{{ $product->name }}</td>
                    <td class="vcenter text-center">
                        {!! ($product->show_in_report == '1') ? '<span class="label-danger">'.__('label.NO').'</span>' : '<span class="label-success">'.__('label.YES').'</span>' !!}
                    </td>
                    <td class="text-right {{  $deliveredFromSubstore }}">{{ Helper::numberFormat($product->total_qty,6) }}</td>
                    <td class="text-right {{  $deliveredFromSubstore }}">{{ Helper::unitConversion($product->total_qty) }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        <?php $i++; ?>
        @endforeach
        <br/>
        <table class="no-border">
            <tr>
                <td class="no-border col-md-6">
                    <br/>
                    -------------------------
                    <br/>
                    @lang('label.OFFICERS_SIGNATURE')
                </td>
                <td class="no-border text-right col-md-6">
                    <br/>
                    -------------------------<br/>
                    @lang('label.SUPERVISORS_SIGNATURE')
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <table class="no-border">
            <tr>
                <td class="no-border text-left col-xs-6">
                    @lang('label.PREPARED_ON')
                    {{ Helper::printDateFormat(date('Y-m-d H:i:s')).' by '.Auth::user()->first_name.' '.Auth::user()->last_name }}
                </td>
                <td class="no-border text-right col-xs-6">
                    @lang('label.GENERATED_BY_RAJAKINI_SOFTWARE')
                    ,<span>&nbsp;@lang('label.POWERED_BY')</span><b>&nbsp;&nbsp;@lang('label.SWAPNOLOKE')</b>
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