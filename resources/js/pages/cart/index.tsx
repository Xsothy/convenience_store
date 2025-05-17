// Cart page
import React, { useEffect, useState } from 'react';
import { Plus, Minus, ShoppingBag, Trash2 } from 'lucide-react';
import { Link, router } from '@inertiajs/react';
import { ArtworkLayout, AppLayout } from '@/layouts';
import { useCartStore } from '@/stores/useCartStore';
import { useDebounced } from '@/hooks/use-debounced';
import ArtworkController from '@/wayfinder/actions/App/Http/Controllers/ArtworkController';
import CheckoutController from '@/wayfinder/actions/App/Http/Controllers/CheckoutController';

const Index = (props: { addresses?: App.Data.AddressData[] }) => {
  const cartStore = useCartStore();
  const carts = cartStore.carts;
  const [cartItems, setCartItems] = useState(carts);
  // Apply a 10% discount for demonstration purposes
  const applyDiscount = false;

  const debouncedUpdateQty = useDebounced(function(id: number, newQuantity: number, variation?: string) {
    cartStore.setProductQuantity(id, newQuantity, variation || '');
  }, 1000);

  useEffect(() => {
    setCartItems(carts);
  }, [carts]);

  const updateQuantity = (id: number, productId: number, newQuantity: number, variation: null|string = null) => {
    if (newQuantity < 1) return;
    setCartItems(cartItems.map((item) => {
      if (item.id === id) {
        if(item?.options && typeof item.options === 'object' && 'variant' in item.options){
          return { ...item, qty: newQuantity };
        }
        return { ...item, qty: newQuantity};
      }
      return item;
    }));
    //make async request after use stop click for 500ms
    debouncedUpdateQty(productId, newQuantity, variation || undefined);
  };

  const removeItem = (id: number, variation: null|string = null) => {
    cartStore.removeCart(id, variation || '');
  };

  // Calculate totals
  const subtotal = cartItems.reduce((total, item) => {
    return total + (item.price * (item.qty || 0));
  }, 0);
  
  const shipping = subtotal > 100 ? 0 : 10;
  const discount = applyDiscount ? subtotal * 0.1 : 0;
  const total = subtotal + shipping - discount;

  const checkout = () => {
    router.post(CheckoutController.process().url);
  };

  // Extract product name safely
  const getProductName = (product: any): string => {
    if (!product) return '';
    if (typeof product.name === 'string') return product.name;
    return '';
  };

  return (
    <>
        {cartItems.length === 0 ? (
          <div className="mb-16 px-6 pb-6 rounded-lg border border-gray-200 bg-gray-50 p-8 text-center">
            <div className="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100">
              <ShoppingBag size={32} className="text-gray-400" />
            </div>
            <h2 className="mb-2 text-xl font-semibold">Your cart is empty</h2>
            <p className="mb-6 text-gray-600">Looks like you haven't added any products to your cart yet.</p>
            <Link
              href="/"
              className="inline-block rounded-full bg-black px-6 py-3 font-medium text-white transition-colors hover:bg-gray-800"
            >
              Start Shopping
            </Link>
          </div>
        ) : (
          <div className="mb-16 px-6 pb-6 grid grid-cols-1 gap-8 lg:grid-cols-3">
            {/* Cart Items */}
            <div className="lg:col-span-2">
              <div className="rounded-lg border border-gray-200">
                <div className="hidden border-b border-gray-200 p-4 sm:grid sm:grid-cols-12">
                  <div className="col-span-5 font-medium">Product</div>
                  <div className="col-span-2 font-medium">Options</div>
                  <div className="col-span-1 text-center font-medium">Price</div>
                  <div className="col-span-2 text-center font-medium">Quantity</div>
                  <div className="col-span-1 text-right font-medium">Total</div>
                  <div className="col-span-1 text-right font-medium">Action</div>
                </div>

                {cartItems.map((item) => (
                  <div
                    key={item.id}
                    className="grid grid-cols-1 border-b border-gray-200 p-4 last:border-b-0 sm:grid-cols-12 sm:items-center"
                  >
                    {/* Product */}
                    <div className="col-span-5 flex items-center">
                      <div className="mr-4 h-20 w-20 flex-shrink-0 bg-gray-50 rounded overflow-hidden">
                        <img 
                          src={item.product?.thumbnail_image as string} 
                          alt={getProductName(item.product)} 
                          className="h-full w-full object-cover transition-transform hover:scale-105" 
                        />
                      </div>
                      <div className="flex-1 min-w-0">
                        <Link href={ArtworkController.show({ slug: item.product?.slug })} className="font-medium hover:underline text-gray-900 line-clamp-2">
                          {getProductName(item.product)}
                        </Link>
                        
                        {/* Mobile Price (visible only on mobile) */}
                        <div className="mt-2 flex items-center justify-between sm:hidden">
                          <span className="text-gray-900 font-medium">${item.price.toFixed(2)}</span>
                          <button
                            onClick={() => removeItem(item.product?.id as number, item?.options && typeof item.options === 'object' && 'variant' in item.options ? item.options.variant as string : null)}
                            className="text-red-500 hover:text-red-700 p-1"
                            aria-label="Remove item"
                          >
                            <Trash2 size={18} />
                          </button>
                        </div>
                        
                        {/* Mobile Options (visible only on mobile) */}
                        <div className="mt-1 text-sm text-gray-500 sm:hidden">
                          {item?.options && typeof item.options === 'object' && (
                            <>
                              {('variant' in item.options) && <div className="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-800 mr-2">Size: {item.options.variant as string}</div>}
                              {('is_digital_download' in item.options) && item.options.is_digital_download && 
                                <div className="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">Digital Download</div>
                              }
                            </>
                          )}
                        </div>
                      </div>
                    </div>

                    {/* Options - Desktop */}
                    <div className="col-span-2 hidden text-md text-gray-700 sm:block">
                      {item?.options && typeof item.options === 'object' && (
                        <div className="space-y-1">
                          {('variant' in item.options) && 
                            <div className="inline-flex items-center px-2 py-0.5 rounded-full text-md bg-gray-100 text-gray-800">
                              Size: {String(item.options.variant)}
                            </div>
                          }
                          {('is_digital_download' in item.options) && (item.options.is_digital_download as boolean) && 
                            <div className="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">
                              Digital Download
                            </div>
                          }
                          {!('variant' in item.options) && !('is_digital_download' in item.options) && 
                            <div className="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-500">Standard</div>
                          }
                        </div>
                      )}
                      {(!item?.options || typeof item.options !== 'object') && 
                        <div className="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-500">Standard</div>
                      }
                    </div>

                    {/* Price */}
                    <div className="col-span-1 hidden text-center sm:block">
                      ${item?.price?.toFixed(2)}
                    </div>

                    {/* Quantity */}
                    <div className="col-span-2 mt-4 sm:mt-0 sm:text-center">
                      <div className="inline-flex items-center rounded-md border border-gray-200 shadow-sm">
                        <button
                          onClick={() => {
                            const newQty = (item.qty || 1) - 1;
                            updateQuantity(item.id as number, item.product?.id as number, newQty, item?.options && typeof item.options === 'object' && 'variant' in item.options ? item.options.variant as string : null);
                          }}
                          className="flex h-8 w-8 items-center justify-center rounded-l-md transition-colors hover:bg-gray-100 border-r border-gray-200"
                          aria-label="Decrease quantity"
                          disabled={(item.qty || 1) <= 1}
                        >
                          <Minus size={14} className={(item.qty || 1) <= 1 ? "text-gray-300" : "text-gray-600"} />
                        </button>
                        <span className="flex h-8 w-10 items-center justify-center text-center text-sm font-medium">
                          {item.qty || 1}
                        </span>
                        <button
                          onClick={() => {
                            const newQty = (item.qty || 1) + 1;
                            updateQuantity(item.id as number, item.product?.id as number, newQty, item?.options && typeof item.options === 'object' && 'variant' in item.options ? item.options.variant as string : null);
                          }}
                          className="flex h-8 w-8 items-center justify-center rounded-r-md transition-colors hover:bg-gray-100 border-l border-gray-200"
                          aria-label="Increase quantity"
                        >
                          <Plus size={14} className="text-gray-600" />
                        </button>
                      </div>
                    </div>

                    {/* Total - Desktop */}
                    <div className="col-span-1 hidden sm:block text-right font-medium">
                      ${((item?.price || 0) * (item.qty || 1)).toFixed(2)}
                    </div>
                    
                    {/* Remove - Desktop */}
                    <div className="col-span-1 hidden sm:flex justify-end">
                      <button
                        onClick={() => removeItem(item.product?.id as number, item?.options && typeof item.options === 'object' && 'variant' in item.options ? item.options.variant as string : null)}
                        className="text-red-500 hover:text-red-700 p-1 transition-colors"
                        aria-label="Remove item"
                      >
                        <Trash2 size={18} />
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Order Summary */}
            <div>
              <div className="rounded-lg border border-gray-200 p-6 shadow-sm bg-white sticky top-4">
                <h2 className="mb-4 text-xl font-bold">Order Summary</h2>

                <div className="mb-6 space-y-4">
                  <div className="flex justify-between">
                    <span className="text-gray-600">Subtotal</span>
                    <span className="font-medium">${subtotal.toFixed(2)}</span>
                  </div>

                  <div className="flex justify-between">
                    <span className="text-gray-600">Shipping</span>
                    <span className="font-medium">{shipping === 0 ? 'Free' : `$${shipping.toFixed(2)}`}</span>
                  </div>

                  {applyDiscount && (
                    <div className="flex justify-between text-green-600">
                      <span>Discount (10%)</span>
                      <span className="font-medium">-${discount.toFixed(2)}</span>
                    </div>
                  )}

                  <div className="border-t border-gray-200 pt-4">
                    <div className="flex justify-between font-bold text-lg">
                      <span>Total</span>
                      <span>${total.toFixed(2)}</span>
                    </div>
                    <p className="text-xs text-gray-500 mt-1">Taxes calculated at checkout</p>
                  </div>
                </div>

                {/* Shipping Address Section */}
                {props.addresses && (
                  <div className="mb-4 border-t border-gray-200 pt-4">
                    <div className="flex items-center justify-between mb-2">
                      <h3 className="font-medium">Shipping Address</h3>
                      <Link 
                        href={route('cart.addresses')}
                        className="text-sm text-blue-600 hover:text-blue-800 hover:underline"
                      >
                        Manage Addresses
                      </Link>
                    </div>
                    
                    {props.addresses.length > 0 ? (
                      <div className="bg-gray-50 rounded-md p-3 text-sm">
                        {/* Show default address if any */}
                        {props.addresses.find(a => a.is_default) ? (
                          (() => {
                            const defaultAddress = props.addresses.find(a => a.is_default);
                            return (
                              <>
                                <div className="font-medium">{defaultAddress?.first_name} {defaultAddress?.last_name}</div>
                                <div>{defaultAddress?.address}</div>
                                <div>
                                  {defaultAddress?.city}, {defaultAddress?.state} {defaultAddress?.postal_code}
                                </div>
                                {defaultAddress?.country && <div>{defaultAddress?.country}</div>}
                                {defaultAddress?.phone && <div className="text-gray-500 mt-1">{defaultAddress?.phone}</div>}
                              </>
                            );
                          })()
                        ) : (
                          <div className="text-gray-500">
                            You have {props.addresses.length} saved address(es). Set one as default.
                          </div>
                        )}
                      </div>
                    ) : (
                      <Link
                        href={route('cart.addresses')}
                        className="flex items-center justify-center w-full py-2 border border-dashed border-gray-300 rounded-md bg-gray-50 text-gray-500 hover:bg-gray-100 transition-colors"
                      >
                        <Plus className="w-4 h-4 mr-2" /> Add shipping address
                      </Link>
                    )}
                  </div>
                )}

                <button onClick={checkout} className="w-full cursor-pointer rounded-md bg-black py-3 font-medium text-white transition-colors hover:bg-gray-800">
                  Proceed to Checkout
                </button>
                
                <div className="mt-4 flex items-center justify-center">
                  <Link href="/" className="text-sm text-gray-600 hover:text-black flex items-center">
                    <ShoppingBag className="mr-1" size={16} />
                    Continue Shopping
                  </Link>
                </div>
              </div>
            </div>
          </div>
        )}
    </>
  );
};

Index.layout = page => (
  <AppLayout>
    <ArtworkLayout children={page} />
  </AppLayout>
)

export default Index; 