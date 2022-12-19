<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class WashTypeToProcess extends Model {

    protected $primaryKey = 'id';
    protected $table = 'recipe_wash_type_to_process';

    public $timestamps = false;

}
