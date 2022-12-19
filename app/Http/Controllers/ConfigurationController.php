<?php
namespace App\Http\Controllers;

use Validator;
use App\Configuration;
use Session;
use Redirect;
use Illuminate\Http\Request;


class ConfigurationController extends Controller {
    
    public function index() {
        $configurationArr = Configuration::first();
        return view('configuration.index')->with(compact('configurationArr'));
    }
    
    public function edit($id) {
        $target = Configuration::find($id);
        return view('configuration.edit')->with(compact('target'));
    }

    public function update(Request $request,$id) {
       
        // validate
        $rules = array(
            'check_in_time' => 'required',
            'serial_code' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('configuration/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput(Input::all());
        }
           
        // store
        $configuration = Configuration::find($id);
        $configuration->check_in_time =  date("H:i:s", strtotime($request->check_in_time)) ;
        $configuration->serial_code = $request->serial_code;
        
        if ($configuration->save()) {
            Session::flash('success', __('label.CONFIGURATION_HAS_BEEN_UPDATED_SUCCESSFULLY'));
            return Redirect::to('configuration');
        } else {
            Session::flash('error', __('label.CONFIGURATION_COULD_NOT_BE_CREATED'));
            return Redirect::to('configuration/' . $id . '/edit');
        }
        
    }
    
}
?>