<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductToCertificate extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_to_certificate';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
