<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductConsumptionDetails extends Model {

    protected $primaryKey = 'id';
    protected $table = 'pro_consumption_details';

    public $timestamps = false;

}
