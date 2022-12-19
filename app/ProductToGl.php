<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductToGl extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_to_gl';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
