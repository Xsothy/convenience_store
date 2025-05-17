
import React, { useEffect, useState } from 'react';
import { ShoppingBag, X } from 'lucide-react';
import { Link, router } from '@inertiajs/react';
import { useCartStore } from '@/stores/useCartStore';

const CartButton = () => {
  const [isCartOpen, setIsCartOpen] = useState(false);

  const cartStore = useCartStore();
  const carts = cartStore.carts;
  const [cartItems, setCartItems] = useState(cartStore.carts);

  const totalItems = cartItems?.length
  const subtotal = carts?.reduce((total, item) => total + (item.price * (item.qty || 0)), 0);

  useEffect(() => {
    setCartItems(carts);
  }, [carts]);

  const removeItem = (id: number) => {
    router.post(route('cart.delete'), {productId: id}, {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['carts'] });
        }
    });
  };

  return (
    <div className="relative">
      {/* Cart Button */}
      <button
        className="relative text-gray-700 transition-colors duration-300 hover:text-black"
        onClick={() => setIsCartOpen(!isCartOpen)}
        aria-label="Cart"
      >
        <ShoppingBag className="cursor-pointer" size={20} />
        {totalItems > 0 && (
          <span className="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-black text-xs text-white">
            {totalItems}
          </span>
        )}
      </button>

      {/* Cart Dropdown */}
      <div
        className={`glass-background absolute right-0 top-full z-50 mt-2 w-80 rounded-lg border border-gray-200 bg-white p-4 shadow-lg transition-all duration-300 sm:w-96 ${
          isCartOpen ? 'opacity-100' : 'pointer-events-none opacity-0'
        }`}
      >
        <div className="mb-4 flex items-center justify-between">
          <h3 className="text-lg font-medium">Your Cart</h3>
          <button
            onClick={() => setIsCartOpen(false)}
            className="text-gray-400 transition-colors hover:text-black"
            aria-label="Close cart"
          >
            <X size={18} />
          </button>
        </div>

        {cartItems.length === 0 ? (
          <div className="py-4 text-center text-gray-500">
            Your cart is empty
          </div>
        ) : (
          <>
            <div className="max-h-60 overflow-y-auto">
              {cartItems.map((item) => (
                <div key={item.id} className="mb-3 flex items-center gap-3 border-b border-gray-100 pb-3">
                  <img
                    src={item.product?.thumbnail_image as string}
                    alt={item.product?.name as string}
                    className="h-16 w-16 rounded-md object-cover"
                  />
                  <div className="flex-1">
                    <h4 className="text-sm font-medium">{item.product?.name}</h4>
                    <p className="text-sm font-medium">${item.price.toFixed(2)}</p>
                  </div>
                    <div>
                        <span className="text-gray-600">${item.price.toFixed(2)}</span>
                        *
                        <span className="text-gray-600">{item.qty}</span>
                    </div>
                  <button
                    onClick={() => removeItem(item.product?.id as number)}
                    className="text-gray-400 transition-colors hover:text-black cursor-pointer"
                    aria-label={`Remove ${item.product?.name} from cart`}
                  >
                    <X size={16} />
                  </button>
                </div>
              ))}
            </div>
            <div className="mt-4 border-t border-gray-100 pt-3">
              <div className="mb-4 flex justify-between">
                <span className="font-medium">Subtotal</span>
                <span className="font-medium">${subtotal.toFixed(2)}</span>
              </div>
                {/*<CartDialog />*/}
                <Link
                    href="/cart"
                    className="block w-full rounded-lg bg-black py-2 text-center font-medium text-white transition-colors hover:bg-gray-800"
                    onClick={() => setIsCartOpen(false)}
                >
                    View Cart
                </Link>
              <button
                className="mt-2 w-full text-center text-sm text-gray-500 underline transition-colors hover:text-black"
                onClick={() => setIsCartOpen(false)}
              >
                Continue Shopping
              </button>
            </div>
          </>
        )}
      </div>
    </div>
  );
};

export default CartButton;
