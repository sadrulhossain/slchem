<?php

namespace App\Http\Controllers;

use Validator;
use App\MfAddressBook;
use App\Manufacturer;
use App\Country;
use App\ManufacturerToPhone;
use App\ManufacturerToEmail;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class MfAddressBookController extends Controller {

    private $controller = 'MfAddressBook';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $countryArr = Country::pluck('name', 'id')->toArray();
        $targetArr = MfAddressBook::join('manufacturer', 'manufacturer.id', '=', 'manufacturer_adressbook.manufacturer_id')
                ->select('manufacturer_adressbook.*', 'manufacturer.name as mf_name')
                ->orderBy('manufacturer.name', 'asc')
                ->orderBy('manufacturer_adressbook.title', 'asc');

//        //begin filtering
        $searchText = $request->search;
        $titleArr = MfAddressBook::select('title')->get();
        $FiltercountryArr = ['0' => __('label.SELECT_COUNTRY_OPT')] + Country::pluck('name', 'id')->toArray();
        $manufacturerArr = ['0' => __('label.SELECT_MANUFACTURER_OPT')] + manufacturer::pluck('name', 'id')->toArray();
        
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('title', 'LIKE', '%' . $searchText . '%');
            });
        }
        if(!empty($request->country)){
            $targetArr = $targetArr->where('manufacturer_adressbook.country_id' , '=' ,$request->country);
        }
        if(!empty($request->manufecturer)){
            $targetArr = $targetArr->where('manufacturer_adressbook.manufacturer_id' , '=' ,$request->manufecturer);
        }
//        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/manufafAddressBook?page=' . $page);
        }


        $manufacturerPhoneArr = ManufacturerToPhone::orderBy('manufacturer_address_id', 'asc')->get();
        $manufacturerEmailArr = ManufacturerToEmail::orderBy('manufacturer_address_id', 'asc')->get();

        $phoneDataArr = $emailDataArr = [];
        foreach ($manufacturerPhoneArr as $manufacturerPhone) {
            $phoneDataArr[$manufacturerPhone->manufacturer_address_id][] = $manufacturerPhone->phone;
        }
        //echo '<pre>';print_r($phoneDataArr);exit;


        foreach ($manufacturerEmailArr as $manufacturerEmail) {
            $emailDataArr[$manufacturerEmail->manufacturer_address_id][] = $manufacturerEmail->email;
        }

        return view('mfAddressBook.index')->with(compact('targetArr', 'qpArr', 'countryArr', 'manufacturerArr','phoneDataArr','emailDataArr','titleArr','FiltercountryArr'));
    }

    public function create(Request $request) {
        $qpArr = $request->all();
        $manufacturerArr = ['0' => __('label.SELECT_MANUFACTURER_OPT')] + Manufacturer::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryArr = ['0' => __('label.SELECT_COUNTRY_OPT')] + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        return view('mfAddressBook.create')->with(compact('qpArr', 'manufacturerArr', 'countryArr'));
    }

    public function store(Request $request) {

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'manufacturer_id' => 'required|not_in:0',
                    'country_id' => 'required|not_in:0',
                    'address' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('mfAddressBook/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new MfAddressBook;
        $target->manufacturer_id = $request->manufacturer_id;
        $target->country_id = $request->country_id;
        $target->title = $request->title;
        $target->address = $request->address;

        $phoneArr = $emailArr = [];
        if ($target->save()) {

            if (!empty($request->phone)) {
                foreach ($request->phone as $key => $phone) {
                    $phoneArr[$key]['manufacturer_address_id'] = $target->id;
                    $phoneArr[$key]['phone'] = $phone;
                }
            }

            ManufacturerToPhone::where('manufacturer_address_id', $target->id)->delete();
            ManufacturerToPhone::insert($phoneArr);

            if (!empty($request->email)) {
                foreach ($request->email as $key => $email) {
                    $emailArr[$key]['manufacturer_address_id'] = $target->id;
                    $emailArr[$key]['email'] = $email;
                }
            }

            ManufacturerToEmail::where('manufacturer_address_id', $target->id)->delete();
            ManufacturerToEmail::insert($emailArr);

            Session::flash('success', __('label.MANUFACTURER_ADDRESS_BOOK_CREATED_SUCCESSFULLY'));
            return redirect('mfAddressBook');
        } else {
            Session::flash('error', __('label.MANUFACTURER_ADDRESS_BOOK_COULD_NOT_BE_CREATED'));
            return redirect('mfAddressBook/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = MfAddressBook::find($id);
        $manufacturerToPhoneArr = ManufacturerToPhone::where('manufacturer_address_id', $id)->get();
        $manufacturerToEmailArr = ManufacturerToEmail::where('manufacturer_address_id', $id)->get();

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('mfAddressBook');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $manufacturerArr = ['0' => __('label.SELECT_MANUFACTURER_OPT')] + Manufacturer::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $countryArr = ['0' => __('label.SELECT_COUNTRY_OPT')] + Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        
        return view('mfAddressBook.edit')->with(compact('target', 'qpArr', 'manufacturerArr', 'countryArr', 'manufacturerToEmailArr', 'manufacturerToPhoneArr'));
    }

    public function update(Request $request, $id) {

        $target = MfAddressBook::find($id);
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'title' => 'required|unique:manufacturer_adressbook,title,' . $id,
                    'manufacturer_id' => 'required|not_in:0',
                    'country_id' => 'required|not_in:0',
                    'address' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('mfAddressBook/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }


        $target->manufacturer_id = $request->manufacturer_id;
        $target->country_id = $request->country_id;
        $target->title = $request->title;
        $target->address = $request->address;

        $phoneArr = $emailArr = [];
        if ($target->save()) {

            if (!empty($request->phone)) {
                foreach ($request->phone as $key => $phone) {
                    $phoneArr[$key]['manufacturer_address_id'] = $id;
                    $phoneArr[$key]['phone'] = $phone;
                }
            }

            ManufacturerToPhone::where('manufacturer_address_id', $id)->delete();
            ManufacturerToPhone::insert($phoneArr);

            if (!empty($request->email)) {
                foreach ($request->email as $key => $email) {
                    $emailArr[$key]['manufacturer_address_id'] = $id;
                    $emailArr[$key]['email'] = $email;
                }
            }

            ManufacturerToEmail::where('manufacturer_address_id', $id)->delete();
            ManufacturerToEmail::insert($emailArr);


            Session::flash('success', __('label.MANUFACTURER_ADDRESS_BOOK_UPDATED_SUCCESSFULLY'));
            return redirect('mfAddressBook' . $pageNumber);
        } else {
            Session::flash('error', __('label.MANUFACTURER_ADDRESS_BOOK_COULD_NOT_BE_UPDATED'));
            return redirect('mfAddressBook/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = MfAddressBook::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Session::flash('error', __('label.MANUFACTURER_ADDRESS_BOOK_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.MANUFACTURER_ADDRESS_BOOK_COULD_NOT_BE_DELETED'));
        }
        return redirect('mfAddressBook' . $pageNumber);
    }
    
    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&country=' . $request->country . '&manufecturer=' . $request->manufecturer;
        return Redirect::to('mfAddressBook?' . $url);
    }

}
