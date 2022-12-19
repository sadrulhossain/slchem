<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturerToEmail extends Model {

    protected $table = 'manufacturer_addressbook_email';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
