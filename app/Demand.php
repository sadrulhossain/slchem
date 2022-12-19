<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Demand extends Model {

    protected $primaryKey = 'id';
    protected $table = 'demand';
    public $timestamps = false;

    public function BatchCard() {
        return $this->belongsTo('App\BatchCard', 'batch_card_id');
    }

}