<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RecipeToProduct extends Model {

    protected $primaryKey = 'id';
    protected $table = 'recipe_to_product';

    public $timestamps = false;

}
