<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class ClientApiController extends Controller
{
    public function addressStore(Request $request){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
        ]);
        $request->merge([
            'user_id' => auth()->user()->id,
        ]);
        $ad = Address::create($request->all());
        return back()->with('custom-data', [
            'address_id' => $ad->id
        ])->with('success', 'Address added successfully');
    }
}
