<div class="modal-header clone-modal-header">
    <button type="button" class="btn white pull-right tooltips" data-dismiss="modal" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    <h3 class="modal-title text-center">
        @lang('label.DEACTIVATION_ACTIVATION_HISTORY')
    </h3>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <div class="portlet-body">
                            <div class="mt-timeline-2">
                                <div class="mt-timeline-line border-grey-steel"></div>
                                <ul class="mt-container">
                                    <?php
                                        $iconArr = [
                                            'deactivation' => 'icon-close',
                                            'activation' => 'icon-check',
                                        ];

                                        $colorArr = [
                                            'deactivation' => 'bg-red bg-font-red',
                                            'activation' => 'bg-blue-dark bg-font-blue-dark',
                                            
                                        ];
                                        ?>

                                        @foreach($actDeactHistoryArr as $history)
                                        <li class="mt-item">
                                            <div class="mt-timeline-icon {{$colorArr[$history->action]}} border-grey-steel">
                                                <i class="{{ $iconArr[$history->action]}}"></i>
                                            </div>
                                            <div class="mt-timeline-content">
                                                <div class="mt-content-container">
                                                    @if($history->action == 'deactivation')
                                                    <strong>@lang('label.DEACTIVATED_BY') :</strong> @elseif($history->action == 'activation')
                                                    <strong>@lang('label.ACTIVATED_BY') :</strong> @endif {{$userList[$history->by]}} @if(!empty($history->cause))
                                                    <strong> @lang('label.CAUSE') :</strong> {{$history->cause}}</br>
                                                    @endif
                                                    <strong> @lang('label.AT') :</strong> {{$date = Helper::printDateFormat($history->date)}}
                                                </div>
                                            </div>
                                        </li>

                                        <!-- <li class="mt-item">
                                            <div class="mt-timeline-icon bg-red bg-font-red border-grey-steel">
                                                <i class="icon-arrow-up"></i>
                                            </div>
                                        </li> -->
                                        @endforeach
                                </ul>
                            </div>
                        </div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn dark btn-outline tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
</div>