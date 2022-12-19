<div class="modal-header clone-modal-header">
    <div class="col-md-6">
        <h4 class="modal-title"><strong>@lang('label.TOTAL_ACTIVE_RECIPE')</strong></h4>
    </div>

    <div class="col-md-6">
        <button type="button" class="btn bg-red-pink bg-font-red-pink btn-outline pull-right tooltips" data-dismiss="modal">@lang('label.CLOSE')</button>

    </div>
</div>
<div class="modal-body">
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="padding-left-0">
                    <li class="list-style-item-none display-inline-block margin-top-10">
                        <span class="label label-md bold label-blue-steel">@lang('label.TOTAL_ACTIVE_RECIPE') : {!! !empty($totalActiveRecipieCount) ? $totalActiveRecipieCount : 0 !!}</span>
                    </li>

                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive max-height-500 webkit-scrollbar">
                    <table class="table table-bordered table-hover table-head-fixer-color">
                        <thead>
                            <tr class="info">
                                <th class="text-center vcenter">@lang('label.SL_NO')</th>
                                <th class="vcenter">@lang('label.REFERENCE_NO')</th>
                                <th class="vcenter" width="60">@lang('label.STYLE')</th>
                                <th class="vcenter">@lang('label.DATE')</th>
                                <th class="vcenter">@lang('label.SEASON')</th>
                                <th class="vcenter">@lang('label.COLOR')</th>
                                <th class="vcenter">@lang('label.SHADE')</th>
                                <th class="vcenter">@lang('label.WASHING_MACHINE_TYPE')</th>
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
                                <td class="text-center vcenter">{{ ++$sl }}</td>
                                <td class="vcenter">{{ $target->reference_no }}</td>
                                <td class="vcenter" width="60">{{ $target->style }}</td>
                                <td class="vcenter">{{ Helper::dateFormat($target->date) }}</td>
                                <td class="vcenter">{{ $target->season }}</td>
                                <td class="vcenter">{{ $target->color }}</td>
                                <td class="vcenter text-center">{{ $target->shade }}</td>
                                <td class="vcenter">{{ $target->machine_model }}</td>
                                

                            </tr>
                            @endforeach 
                            @else
                            <tr>
                                <td colspan="13" class="vcenter">@lang('label.NO_ACTIVE_RECIPE_FOUND')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('label.CLOSE')</button>
</div>