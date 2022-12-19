<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use UserGroup;

class User extends Authenticatable
{
    use Notifiable;

    protected $hidden = [
        'password', 'remember_token','conf_password',
    ];

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
	
	public function UserGroup() {
        return $this->belongsTo('UserGroup', 'group_id');
    }
}