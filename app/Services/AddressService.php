<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AddressService
{
    /**
     * Get addresses based on user authentication status.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAddresses()
    {
        if (Auth::check()) {
            return Address::where('user_id', Auth::id())->get();
        } else {
            $sessionId = Cookie::get('cart');
            return $sessionId ? Address::where('session_id', $sessionId)->get() : collect();
        }
    }
    
    /**
     * Find an address based on user authentication status.
     *
     * @param  int  $id
     * @return \App\Models\Address|null
     */
    public function findAddress($id)
    {
        if (Auth::check()) {
            return Address::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();
        } else {
            $sessionId = Cookie::get('cart');
            return Address::where('id', $id)
                ->where('session_id', $sessionId)
                ->first();
        }
    }
    
    /**
     * Store a new address for the user or guest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Address
     */
    public function storeAddress(Request $request)
    {
        $data = $this->prepareAddressData($request);
        
        // Determine whether to use user_id or session_id
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
            
            // Set as default if it's the first address
            $isDefault = !Address::where('user_id', Auth::id())->exists();
        } else {
            $sessionId = Cookie::get('cart') ?? $this->generateSessionId();
            if (!Cookie::has('cart')) {
                Cookie::queue('cart', $sessionId, 60 * 24 * 30); // 30 days
            }
            
            $data['session_id'] = $sessionId;
            
            // Set as default if it's the first address
            $isDefault = !Address::where('session_id', $sessionId)->exists();
        }
        
        $data['is_default'] = $isDefault;
        
        $address = new Address($data);
        $address->save();
        
        return $address;
    }
    
    /**
     * Update an existing address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Models\Address|null
     */
    public function updateAddress(Request $request, $id)
    {
        $address = $this->findAddress($id);
        
        if (!$address) {
            return null;
        }
        
        $data = $this->prepareAddressData($request);
        
        $address->fill($data);
        $address->save();
        
        return $address;
    }
    
    /**
     * Delete an address.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteAddress($id)
    {
        $address = $this->findAddress($id);
        
        if (!$address) {
            return false;
        }
        
        return $address->delete();
    }
    
    /**
     * Set an address as default.
     *
     * @param  int  $id
     * @return \App\Models\Address|null
     */
    public function setDefaultAddress($id)
    {
        if (Auth::check()) {
            // Set all user addresses to non-default
            Address::where('user_id', Auth::id())
                ->update(['is_default' => false]);
                
            // Set the selected address as default
            $address = Address::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();
        } else {
            $sessionId = Cookie::get('cart');
            
            // Set all session addresses to non-default
            Address::where('session_id', $sessionId)
                ->update(['is_default' => false]);
                
            // Set the selected address as default
            $address = Address::where('id', $id)
                ->where('session_id', $sessionId)
                ->first();
        }
        
        if (!$address) {
            return null;
        }
        
        $address->is_default = true;
        $address->save();
        
        return $address;
    }
    
    /**
     * Convert the name and other fields to a proper address data array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function prepareAddressData(Request $request)
    {
        return [
            'first_name' => $request->first_name, // Using name field as first_name
            'last_name' => $request->last_name,  //   last_name for now
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->zip ?? '12345',
            'additional_info' => $request->additional_info,
        ];
    }
    
    /**
     * Generate a unique session ID for cart tracking.
     *
     * @return string
     */
    private function generateSessionId(): string
    {
        return uniqid('cart_', true);
    }
} 