<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyProduct extends Model {

    protected $primaryKey = 'id';
    protected $table = 'daily_product';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
