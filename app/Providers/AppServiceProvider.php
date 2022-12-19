<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Product;
use App\ProductConsumptionMaster;
use App\Recipe;
use App\BatchCard;
use App\Demand;
use App\AclUserGroupToAccess;
use DB;
use Common;
use Auth;
use Helper;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        view()->composer('*', function ($view) {

            //get request notification number on topnavber in all views
            if (Auth::check()) {

                //ACL ACCESS LIST
                $userAccessArr = Common::userAccess();

//                echo '<pre>';
//                print_r($userAccessArr);
//                exit;
                //ENDOF ACL ACCESS LIST

                $groupId = Auth::user()->group_id;
                $countProducts = $countDemandLetter = $countAvailableQtyArr = $countSubstoreDemand = [];

                if (!empty($userAccessArr[20][10])) {
                    $countProducts = Product::select(DB::raw('COUNT(id) as total'))
                            ->where('approval_status', 0)
                            ->first();

                    $countAvailableQtyArr = Product::select(DB::raw('COUNT(id) as total'))
                                    ->whereColumn('available_quantity', '<', 'reorder_level')
                                    ->where('status', '1')->where('approval_status', 1)
                                    ->first();
                }
//                echo '<pre>';
//                print_r($countAvailableQtyArr->toArray());
//                exit;

                if (!empty($userAccessArr[42][1])) {
                    $countConsumeProduct = ProductConsumptionMaster::select(DB::raw('COUNT(id) as total'))
                            ->where('status', 0)
                            ->where('source', '1')
                            ->first();
                }


                if (!empty($userAccessArr[52][1])) {
                    $countSubstoreDemand = ProductConsumptionMaster::select(DB::raw('COUNT(id) as total'))
                            ->where('status', 0)
                            ->where('source', '3')
                            ->first();
                }

                if (!empty($userAccessArr[49][1])) {
                    $countDemandLetter = Demand::select(DB::raw('COUNT(id) as total'))
                            ->where('status', '0')
                            ->first();
                }

                $totalCountReq = (isset($countProducts->total) ? $countProducts->total : 0) +
                        (isset($countAvailableQtyArr->total) ? $countAvailableQtyArr->total : 0) +
                        (isset($countSubstoreDemand->total) ? $countSubstoreDemand->total : 0);



                $view->with(['countProducts' => $countProducts
                    , 'countDemandLetter' => $countDemandLetter
                    , 'countAvailableQtyArr' => $countAvailableQtyArr
                    , 'countSubstoreDemand' => $countSubstoreDemand
                    , 'totalCountReq' => $totalCountReq
                    , 'userAccessArr' => $userAccessArr
                ]);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
