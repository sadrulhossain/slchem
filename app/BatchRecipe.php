<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BatchRecipe extends Model {

    protected $primaryKey = 'id';
    protected $table = 'batch_recipe';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
        });
    }

}
