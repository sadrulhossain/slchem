<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class HazardClass extends Model {

    protected $primaryKey = 'id';
    protected $table = 'hazard_class';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->updated_by = Auth::user()->id;
        });

        static::updating(function($post) {
            $post->updated_by = Auth::user()->id;
        });
    }

    public function hazardClassLogo() {
        return $this->hasMany('App\HazardClassLogo', 'hazard_class_id');
    }

}
