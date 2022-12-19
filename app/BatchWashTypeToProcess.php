<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BatchWashTypeToProcess extends Model {

    protected $primaryKey = 'id';
    protected $table = 'batch_recipe_wash_type_to_process';

    public $timestamps = false;

}
