<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Product extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product';
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

    public function productToManufacturer() {
        return $this->hasMany('App\ProductToManufacturer', 'product_id');
    }

    public function productToSupplier() {
        return $this->hasMany('App\ProductToSupplier', 'product_id');
    }

    public function productToPpe() {
        return $this->hasMany('App\ProductToPpe', 'product_id');
    }

    public function productToCertificate() {
        return $this->hasMany('App\ProductToCertificate', 'product_id');
    }

    public function productToBpl() {
        return $this->hasMany('App\ProductToBpl', 'product_id');
    }

    public function productToMpl() {
        return $this->hasMany('App\ProductToMpl', 'product_id');
    }
    public function productToGl() {
        return $this->hasMany('App\ProductToGl', 'product_id');
    }
    
    public function productToCheckInDetails() {
        return $this->hasMany('App\ProductCheckInDetails', 'product_id');
    }

    public function productToHazardCat() {
        return $this->hasMany('App\ProductToHazardCat', 'product_id');
    }
    public function productToCas() {
        return $this->hasMany('App\ProductToCas', 'product_id');
    }
    public function productToEc() {
        return $this->hasMany('App\ProductToEc', 'product_id');
    }

}
