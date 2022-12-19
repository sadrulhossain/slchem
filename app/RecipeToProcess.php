<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RecipeToProcess extends Model {

    protected $primaryKey = 'id';
    protected $table = 'recipe_to_process';

    public $timestamps = false;

}
