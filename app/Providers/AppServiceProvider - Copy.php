<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Product;
use App\ProductConsumptionMaster;
use App\Recipe;
use App\BatchCard;
use App\Demand;
use DB;
use Auth;

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
                $groupId = Auth::user()->group_id;
                $countProducts = $countConsumeProduct = $countDemandLetter = $countDeliveredChem = $countAvailableQtyArr = $countSubstoreDemand = [];
				
				
                if (in_array(Auth::user()->group_id, [1])) {
                    $countProducts = Product::select(DB::raw('COUNT(id) as total'))
                            ->where('approval_status', 0)
                            ->first();

                    $countConsumeProduct = ProductConsumptionMaster::select(DB::raw('COUNT(id) as total'))
                            ->where('status', 0)
                            ->where('source', '1')
                            ->first();
					$countSubstoreDemand = ProductConsumptionMaster::select(DB::raw('COUNT(id) as total'))
                            ->where('status', 0)
                            ->where('source', '3')
                            ->first();
                }elseif (in_array(Auth::user()->group_id, [2])) {
                    $countDeliveredChem = Demand::select(DB::raw('COUNT(id) as total'))
                            ->where('status', '1')
                            ->first();
					$countSubstoreDemand = ProductConsumptionMaster::select(DB::raw('COUNT(id) as total'))
                            ->where('status', 0)
                            ->where('source', '3')
                            ->first();
                }elseif (in_array(Auth::user()->group_id, [3])) {
//                    $countDemandLetter = Demand::select(DB::raw('COUNT(id) as total'))
//                            ->where('status', '0')
//                            ->first();
//
                    $countAvailableQtyArr = Product::select(DB::raw('COUNT(id) as total'))->where('reorder_level', '>', 'available_quantity')
                                    ->where('status', '1')->where('approval_status', 1)->first();
                }

                $totalCountReq = (isset($countProducts->total) ? $countProducts->total : 0) +
                        (isset($countConsumeProduct->total) ? $countConsumeProduct->total : 0) +
                        (isset($countDemandLetter->total) ? $countDemandLetter->total : 0) +
                        (isset($countDeliveredChem->total) ? $countDeliveredChem->total : 0) +
                        (isset($countAvailableQtyArr->total) ? $countAvailableQtyArr->total : 0) +
                        (isset($countSubstoreDemand->total) ? $countSubstoreDemand->total : 0);
                $view->with(['countProducts' => $countProducts, 'countConsumeProduct' => $countConsumeProduct
                    , 'countDemandLetter' => $countDemandLetter
                    , 'countDeliveredChem' => $countDeliveredChem
                    , 'countAvailableQtyArr' => $countAvailableQtyArr
                    , 'countSubstoreDemand' => $countSubstoreDemand
                    , 'totalCountReq' => $totalCountReq]);
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
