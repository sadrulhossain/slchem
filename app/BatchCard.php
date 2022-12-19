<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BatchCard extends Model {

    protected $primaryKey = 'id';
    protected $table = 'batch_card';
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
    
    public function Recipe() {
        return $this->belongsTo('App\Recipe', 'recipe_id');
    }
    
    public function Machine() {
        return $this->belongsTo('App\Machine', 'machine_id');
    }

}
