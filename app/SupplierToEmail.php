<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierToEmail extends Model {

    protected $table = 'supplier_email';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
