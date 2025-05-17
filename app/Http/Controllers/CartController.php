<?php

namespace App\Http\Controllers;

use App\Data\AddressData;
use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Module\Cart\CartModule;

class CartController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Display the cart page.
     *
     * @return \Inertia\Response
     */
    public function show()
    {
        // Get addresses using the address service
        $addresses = $this->addressService->getAddresses();
        
        return Inertia::render('cart/index', [
            'addresses' => AddressData::collect($addresses)
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function add(Request $request)
    {
        info('>>>>>ProductId Add To Cart :'.json_encode($request->all()));
        $type = $request->get('type', 'add');

        try {
            DB::transaction(function () use ($request, $type) {
                switch ($type) {
                    case 'set':
                        app(CartModule::class)->updateProduct(
                            $request->get('productId'),
                            $request->get('quantity', 1),
                            $request->get('variation')
                        );
                        break;
                    default:
                        app(CartModule::class)->store(
                            $request->get('productId'),
                            $request->get('quantity', 1),
                            $request->get('variation')
                        );
                        break;
                }
            });

            flash_success('Item successfully added to your cart!');
        } catch (\Exception $e) {
            flash_error('Unable to add item to cart: '.$e->getMessage());
        }

        // return inertia back to update some-component;
        return back();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Request $request)
    {
        info('>>>>>ProductId Delete From Cart :'.json_encode($request->all()));

        try {
            DB::transaction(function () use ($request) {
                app(CartModule::class)->delete($request->get('productId'), $request->get('variation'));
            });

            flash_success('Item removed from your cart.');
        } catch (\Exception $e) {
            flash_error('Unable to remove item from cart: '.$e->getMessage());
        }

        // return inertia back to update some-component;
        return back();
    }
    
    /**
     * Display addresses page for user or guest.
     *
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function addresses()
    {
        // Get addresses using the address service
        $addresses = $this->addressService->getAddresses();
        
        return Inertia::render('cart/addresses', [
            'addresses' => AddressData::collect($addresses)
        ]);
    }
    
    /**
     * Store a new address for the user or guest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            // 'zip' => ['required', 'string', 'max:20'],
        ]);
        
        $this->addressService->storeAddress($request);
        
        flash_success('Address saved successfully.');
        return back();
    }
    
    /**
     * Update an existing address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            // 'zip' => ['required', 'string', 'max:20'],
        ]);
        
        $address = $this->addressService->updateAddress($request, $id);
        
        if (!$address) {
            abort(404);
        }
        
        flash_success('Address updated successfully.');
        return redirect()->back()->with('success', 'Address updated successfully.');
    }
    
    /**
     * Delete an address.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAddress($id)
    {
        $result = $this->addressService->deleteAddress($id);
        
        if (!$result) {
            abort(404);
        }
        
        flash_success('Address deleted successfully.');
        return back();
    }
    
    /**
     * Set an address as default.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setDefaultAddress($id)
    {
        $address = $this->addressService->setDefaultAddress($id);
        
        if (!$address) {
            abort(404);
        }
        
        flash_success('Default address updated.');
        return back();
    }
}
