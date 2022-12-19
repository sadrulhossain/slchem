<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturerToPhone extends Model {

    protected $table = 'manufacturer_addressbook_phone_number';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
