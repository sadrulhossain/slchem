<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductToBpl extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_to_bpl';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
