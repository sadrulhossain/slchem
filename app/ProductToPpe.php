<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductToPpe extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_to_ppe';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
