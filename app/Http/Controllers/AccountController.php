<?php

namespace App\Http\Controllers;

use App\Data\AddressData;
use App\Data\SubscribeData;
use App\Enums\SubscribeStatus;
use App\Models\Address;
use App\Models\Subscribe;
use App\Services\AddressService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class AccountController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Display the user information page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        return Inertia::render('account/index', [
            'user' => $user,
        ]);
    }
    
    /**
     * Display the subscription management page.
     *
     * @return \Inertia\Response
     */
    public function subscription()
    {
        $user = Auth::user();
        
        // Get current active subscription
        $currentSubscription = Subscribe::where('user_id', $user->id)
            ->where(function($query) {
                $query->where('status', SubscribeStatus::ACTIVE)
                    ->orWhere('status', SubscribeStatus::PAUSED)
                    ->orWhere('status', SubscribeStatus::CANCELLED);
            })
            ->where('end_at', '>', Carbon::now())
            ->with('plan')
            ->first();
            
        // Get scheduled subscription (if any)
        $scheduledSubscription = null;
        if ($currentSubscription && $currentSubscription->status === SubscribeStatus::CANCELLED) {
            $scheduledSubscription = Subscribe::where('user_id', $user->id)
                ->where('status', SubscribeStatus::SCHEDULED)
                ->where('start_at', '>', Carbon::now())
                ->with('plan')
                ->first();
        }

        return Inertia::render('account/subscription', [
            'currentSubscription' => $currentSubscription ? SubscribeData::from($currentSubscription) : null,
            'scheduledSubscription' => $scheduledSubscription ? SubscribeData::from($scheduledSubscription) : null,
        ]);
    }
    
    /**
     * Display the security settings page.
     *
     * @return \Inertia\Response
     */
    public function security()
    {
        return Inertia::render('account/security');
    }
    
    /**
     * Display the addresses management page.
     *
     * @return \Inertia\Response
     */
    public function addresses()
    {
        $addresses = $this->addressService->getAddresses();
        
        return Inertia::render('account/addresses', [
            'addresses' => AddressData::collect($addresses),
        ]);
    }

    /**
     * Store a new address for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:20'],
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:20'],
        ]);
        
        $address = $this->addressService->updateAddress($request, $id);
        
        if (!$address) {
            abort(404);
        }
        
        flash_success('Address updated successfully.');
        return back();
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

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        
        // Only update email if it has changed
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            // Reset email verification if email has changed
            $user->email_verified_at = null;
        }
        
        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    }
} 