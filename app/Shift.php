<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Shift extends Model {

    protected $primaryKey = 'id';
    protected $table = 'shift';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
    }

}
