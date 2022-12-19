<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Machine extends Model {

    protected $primaryKey = 'id';
    protected $table = 'machine';
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
    
    public function MachineModel() {
        return $this->belongsTo('App\MachineModel', 'washing_machine_type_id');
    }

}
