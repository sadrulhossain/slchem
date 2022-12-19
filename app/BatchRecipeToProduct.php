<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BatchRecipeToProduct extends Model {

    protected $primaryKey = 'id';
    protected $table = 'batch_recipe_to_product';

    public $timestamps = false;

}
