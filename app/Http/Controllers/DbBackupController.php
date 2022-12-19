<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use URL;
use Redirect;
use Helper;
use Validator;
use Response;
use App\User;
use App\DbBackupDownLoadLog;

class DbBackupController extends Controller {

    public function index(Request $request) {
        $filedata = array();
        if ( $request->generate == 'true') {

            $date_from = $request->from_date;
            $date_to = $request->to_date;

            $log_directory = 'backup/';
            $filedata = array();
            if (is_dir($log_directory)) {
                if ($handle = opendir($log_directory)) {
                    //Notice the parentheses I added:
                    while (($file = readdir($handle)) !== FALSE) {
                        if ($file != '.' && $file != '..') {

                            $checkdate = date('Y-m-d', $this->GetCorrectMTime($log_directory . $file));

                            if ($this->isDateInRange($date_from, $date_to, $checkdate)) {

                                $filedata[] = array('filename' => $file, 'filetime' => $this->GetCorrectMTime($log_directory . $file), 'filepath' => $log_directory . $file);
                            }
                        }
                    }
                    closedir($handle);
                }
            }
        }
        return view('dbBackup.index')->with(compact('filedata'));
    }

    public function isDateInRange($startDate, $endDate, $userDate) {
        $startT = strtotime($startDate);
        $endT = strtotime($endDate);
        $userT = strtotime($userDate);
        return (($userT >= $startT) && ($userT <= $endT));
    }

    public function GetCorrectMTime($filePath) {
        $time = filemtime($filePath);

        $isDST = (date('I', $time) == 1);
        $systemDST = (date('I') == 1);
        $adjustment = 0;
        if ($isDST == false && $systemDST == true)
            $adjustment = 3600;
        else if ($isDST == true && $systemDST == false)
            $adjustment = -3600;
        else
            $adjustment = 0;
        return ($time + $adjustment);
    }


    public function filter(Request $request) {
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $messages = [
            'from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('dbBackup?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }
        return Redirect::to('dbBackup?generate=true&' . $url);
    }
    
    public function download(Request $request) {
        $data = [
            'user_id' => Auth::user()->id,
            'log_time' => date('Y-m-d H:i:s'),
            'downloaded_file' => $request->downloaded_file,
        ];
        
        if (!empty($data)) {
            DbBackupDownLoadLog::insert($data);
            return Response::json(array('heading' => 'Success', 'message' => __('label.DB_BACKUP_DOWNLOAD_LOG_KEPT_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.FAILED_TO_KEEP_DB_BACKUP_DOWNLOAD_LOG')), 401);
        }
    }

}
