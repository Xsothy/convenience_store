<?php

namespace App\Http\Controllers;

use App\Data\AddressData;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Models\Address;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index(Request $request, $token)
    {
        $order = Order::where('uuid', $token)
            ->where('status', Order::DRAFT_STATUS)
            ->when(Auth::check(), function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->when(!Auth::check(), function ($query) {
                return $query->where('session_id', getCurrentSession());
            })
            ->with(['ordersItems.product', 'coupon'])
            ->first();
        if (! $order) {
            abort(404);
        }
        $orderItems = $order->ordersItems;
        foreach ($orderItems as $item) {
            $item->product->append('feature_image');
        }
        $order->ordersItems = $orderItems;
        $address = AddressData::collect(Address::where('user_id', Auth::id())->get());

        return Inertia::render('checkout/index', [
            'order' => $order,
            'token' => $token,
            'address' => $address,
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function orderProcess(Request $request)
    {
        $request->validate([
            'address_id' => 'required',
            'token' => 'required',
        ]);
        $token = $request->get('token');
        $order = Order::where('uuid', $token)->first();
        if (! $order) {
            abort(404);
        }
        DB::transaction(function () use ($order, $request) {
            $order->status = Order::PENDING_STATUS;
            $order->address_id = $request->address_id;
            $order->save();
            
            // Increment coupon usage if a coupon was applied
            if ($order->coupon_id) {
                $coupon = Coupon::find($order->coupon_id);
                if ($coupon) {
                    $coupon->increment('is_used');
                }
            }
            
            Cart::query()
                ->when(Auth::check(), function ($query) {
                    return $query->where('user_id', Auth::id());
                })
                ->when(!Auth::check(), function ($query) {
                    return $query->where('session_id', getCurrentSession());
                })->delete();
                
            // Clear coupon session data after order is processed
            Session::forget('coupon_data');
        });

        return redirect('/')->with('success', 'Your order has been placed!');
    }

    public function process(Request $request)
    {
        $cart = Cart::query()
            ->when(Auth::check(), function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->when(!Auth::check(), function ($query) {
                return $query->where('session_id', getCurrentSession());
            })
            ->get();
        if ($cart->isEmpty()) {
            return back()->with('error', 'You don\'t have any items in your cart');
        }
        try {
            $preOrder = null;
            DB::transaction(function () use ($cart, &$preOrder) {
                $token = getTokenCheckout();
                while (Order::query()->where('uuid', $token)->exists()) {
                    $token = getTokenCheckout();
                }
                $preOrder = Order::create([
                    'uuid' => $token,
                    'user_id' => Auth::id(),
                    'session_id' => getCurrentSession(),
                    'status' => Order::DRAFT_STATUS
                ]);
                $total = 0;
                $cart->each(function ($item) use ($preOrder, &$total) {
                    $preOrder->ordersItems()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->qty,
                        'price' => $item->price,
                        'currency' => 'USD',
                        'options' => $item->options,
                    ]);
                    $total += $item->price * $item->qty;
                });
                $preOrder->total = $total;
                
                // Apply any coupon that might be in the session
                $couponData = Session::get('coupon_data', null);
                if ($couponData) {
                    $preOrder->coupon_id = $couponData['id'];
                    $preOrder->discount = $couponData['discount'];
                }
                
                $preOrder->save();
            }, 2);

            return redirect()->route('artworks.checkout', $preOrder?->uuid);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Something went wrong');
        }

    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon' => 'required|exists:coupons,code',
            'token' => 'required'
        ]);
        
        try {
            $coupon = Coupon::where('code', $request->coupon)->first();
            
            // Check if coupon is active
            if (!$coupon->is_activated) {
                return back()->with('error', 'This coupon is not active');
            }
            
            // Check if coupon is expired
            if ($coupon->is_limited && $coupon->end_at && Carbon::now()->isAfter($coupon->end_at)) {
                return back()->with('error', 'This coupon has expired');
            }
            
            // Check if coupon has reached usage limit
            if ($coupon->use_limit && $coupon->is_used >= $coupon->use_limit) {
                return back()->with('error', 'This coupon has reached its usage limit');
            }
            
            // Get cart total or existing order total
            $total = 0;
            $order = Order::where('uuid', $request->token)
                ->where('status', Order::DRAFT_STATUS)
                ->when(Auth::check(), function ($query) {
                    return $query->where('user_id', Auth::id());
                })
                ->when(!Auth::check(), function ($query) {
                    return $query->where('session_id', getCurrentSession());
                })
                ->first();
                
            if ($order) {
                $total = $order->total;
            } else {
                // If no order exists yet, return an error
                return back()->with('error', 'Order not found');
            }
            
            // Check if the order meets minimum amount requirement
            if ($coupon->order_total_limit && $total < $coupon->order_total_limit) {
                return back()->with('error', "This coupon requires a minimum order of $" . number_format($coupon->order_total_limit, 2));
            }
            
            // Calculate discount
            $discount = 0;
            if ($coupon->type === 'fixed') {
                $discount = min($coupon->amount, $total); // Don't discount more than the total
            } else if ($coupon->type === 'percentage') {
                $discount = ($total * $coupon->amount) / 100;
            }
            
            // Apply coupon to order
            $order->coupon_id = $coupon->id;
            $order->discount = $discount;
            $order->save();
            
            return back()->with('success', 'Coupon applied successfully');
            
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Error applying coupon: ' . $e->getMessage());
        }
    }

    public function removeCoupon(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        $order = Order::where('uuid', $request->token)
            ->where('status', Order::DRAFT_STATUS)
            ->when(Auth::check(), function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        $order->coupon_id = null;
        $order->discount = 0;
        $order->save();

        return back()->with('success', 'Coupon removed successfully');
    }
}
