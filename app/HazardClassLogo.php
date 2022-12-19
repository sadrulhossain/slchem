<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HazardClassLogo extends Model {

    protected $primaryKey = 'id';
    protected $table = 'hazard_class_logo';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
