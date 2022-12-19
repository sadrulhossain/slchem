<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierToPhone extends Model {

    protected $table = 'supplier_phone_number';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
