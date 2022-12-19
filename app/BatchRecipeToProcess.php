<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BatchRecipeToProcess extends Model {

    protected $primaryKey = 'id';
    protected $table = 'batch_recipe_to_process';

    public $timestamps = false;

}
