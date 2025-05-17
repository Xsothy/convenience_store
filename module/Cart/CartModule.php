<?php

namespace Module\Cart;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartModule
{
    public Collection $carts;

    public function store($productId, $quantity = 1, $variation = null): void
    {
        $request = new Request([
            'product_id' => $productId,
            'session_id' => getCurrentSession(),
        ]);

        if (Auth::check()) {
            $request->merge([
                'user_id' => Auth::id(),
            ]);
        }
        $product = Product::find($request->get('product_id'));
        $options = [];
        if ($product) {
            if ($variation) {
                $var = $this->getVariation($product, $variation);
                if ($var) {
                    $product->price = $var['price'];
                    $product->vat = $var['vat'];
                    $product->variant = $var['value'];
                }
            }
            if (isset($product->variant) && $product->variant) {
                $options['variant'] = $product->variant;
            }
            
            $request->merge([
                'item' => $product->name,
                'price' => $product->price,
                'discount' => $product->discount,
                'vat' => $product->vat,
                'total' => (($product->price + $product->vat) - $product->discount),
                'qty' => $quantity,
                'options' => $options,
            ]);
        }
        
        $query = Cart::where('product_id', $productId);
        
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', getCurrentSession());
        }
        
        // Handle options comparison for variants in a database-agnostic way
        if (!blank($options) && isset($options['variant'])) {
            // For SQLite compatibility, use string search instead of JSON functions
            $variant = $options['variant'];
            $query->where(function($q) use ($variant) {
                // Look for both exact match and substring match for JSON data
                $q->whereRaw("json_extract(options, '$.variant') = ?", [$variant])
                  ->orWhere('options', 'like', '%' . $variant . '%');
            });
        }
        
        $checkIFCartExists = $query->first();
        
        if ($checkIFCartExists) {
            $checkIFCartExists->update([
                'qty' => $checkIFCartExists->qty + $quantity,
                'total' => $checkIFCartExists->total + (($product->price + $product->vat) - $product->discount),
            ]);
        } else {
            Cart::create($request->all());
        }
    }

    public function updateProduct($productId, $quantity = 1, $variation = null): void
    {
        $request = new Request([
            'product_id' => $productId,
            'session_id' => getCurrentSession(),
        ]);

        if (Auth::check()) {
            $request->merge([
                'user_id' => Auth::id(),
            ]);
        }

        $product = Product::find($request->get('product_id'));
        $options = [];
        if ($product) {
            if ($variation) {
                $var = $this->getVariation($product, $variation);
                if ($var) {
                    $product->price = $var['price'];
                    $product->vat = $var['vat'];
                    $product->variant = $var['value'];
                }
            }
            if (isset($product->variant) && $product->variant) {
                $options['variant'] = $product->variant;
            }
            
            
            $request->merge([
                'item' => $product->name,
                'price' => $product->price,
                'discount' => $product->discount,
                'vat' => $product->vat,
                'total' => (($product->price + $product->vat) - $product->discount),
                'qty' => $quantity,
                'options' => $options,
            ]);
        }
        
        $query = Cart::where('product_id', $productId);
        
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', getCurrentSession());
        }
        
        // Handle options comparison for variants in a database-agnostic way
        if (!blank($options) && isset($options['variant'])) {
            // For SQLite compatibility, use string search instead of JSON functions
            $variant = $options['variant'];
            $query->where(function($q) use ($variant) {
                // Look for both exact match and substring match for JSON data
                $q->whereRaw("json_extract(options, '$.variant') = ?", [$variant])
                  ->orWhere('options', 'like', '%' . $variant . '%');
            });
        }
        
        $checkIFCartExists = $query->first();

        if ($checkIFCartExists) {
            $checkIFCartExists->update([
                'qty' => $quantity,
                'total' => $checkIFCartExists->total + (($product->price + $product->vat) - $product->discount),
            ]);
        } else {
            Cart::create($request->all());
        }
    }

    public function delete($productId, $variation = null): void
    {
        $request = new Request([
            'productId' => $productId,
            'session_id' => getCurrentSession(),
        ]);
        if (Auth::check()) {
            $request->merge([
                'user_id' => Auth::id(),
            ]);
        }
        
        $query = Cart::where('product_id', $productId);
        
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', getCurrentSession());
        }
        
        // Handle options comparison for variants in a database-agnostic way
        if (!empty($variation)) {
            // For SQLite compatibility, use string search instead of JSON functions
            $query->where(function($q) use ($variation) {
                // Look for both exact match and substring match for JSON data
                $q->whereRaw("json_extract(options, '$.variant') = ?", [$variation])
                  ->orWhere('options', 'like', '%' . $variation . '%');
            });
        }
        
        $query->delete();
    }

    public function getVariation($product, $variation = null)
    {
        $var = $product->productMetas?->first();
        if (isset($var->value)) {
            foreach ($var->value as $value) {
                if ($value['name'] == 'Variation') {
                    foreach ($value['values'] ?? [] as $varian) {
                        if ($varian['value'] == $variation) {
                            return $varian;
                        }
                    }
                }
            }
        }

        return null;
    }

    public function syncCart($order): void
    {
        $account = Auth::user();
        foreach ($this->carts as $cart) {
            $isDigitalDownload = $cart->options && isset($cart->options['is_digital_download']) ? $cart->options['is_digital_download'] : false;
            $order->ordersItems()->create([
                'account_id' => $account->id,
                'product_id' => $cart->product_id,
                'item' => $cart->item,
                'price' => $cart->price,
                'discount' => $cart->discount,
                'tax' => $cart->vat,
                'total' => $cart->total,
                'qty' => $cart->qty,
                'options' => $cart->options,
                'is_digital_download' => $isDigitalDownload,
                'download_status' => 'pending',
                'download_count' => 0,
            ]);
        }

        if (class_exists(Cart::class)) {
            Cart::query()->where('session_id', Cookie::get('cart'))->delete();
        }
    }
}
