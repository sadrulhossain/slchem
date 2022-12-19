<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Country extends Model {

    protected $primaryKey = 'id';
    protected $table = 'country';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
