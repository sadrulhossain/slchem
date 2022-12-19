<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyProductDetails extends Model {

    protected $primaryKey = 'id';
    protected $table = 'daily_product_details';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
