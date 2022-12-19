<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class DbBackupDownLoadLog extends Model {

    protected $primaryKey = 'id';
    protected $table = 'db_backup_download_log';
    public $timestamps = false;

}
