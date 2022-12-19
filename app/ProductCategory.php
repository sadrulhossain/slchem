<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductCategory extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_category';
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

    public function parentCategory() {
        return $this->belongsTo('App\ProductCategory', 'parent_id', 'id');
    }

    public function subCategory() {
        return $this->hasMany('App\ProductCategory', 'parent_id', 'id');
    }

}
