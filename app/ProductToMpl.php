<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductToMpl extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_to_mpl';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
