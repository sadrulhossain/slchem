<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductCheckInDetails extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_checkin_details';

    public $timestamps = false;

}
