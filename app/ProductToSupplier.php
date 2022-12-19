<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductToSupplier extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_to_supplier';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
        });
    }

}
