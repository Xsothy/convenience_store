import React from 'react';
import { ShoppingBag } from 'lucide-react';
import { Link, usePage } from '@inertiajs/react';
import { SharedData } from '@/types';

const FloatingCart: React.FC = () => {
  // Get cart items count from the shared data
  const totalItems = usePage<SharedData>().props.carts.reduce(
    (acc, cart) => acc + (cart.qty ?? 0), 
    0
  );


  return (
    <div className="fixed bottom-6 right-6 z-50 flex gap-3 md:hidden">
      
      {/* Floating cart button */}
      <Link
        href="/cart"
        className="flex h-14 w-14 items-center justify-center rounded-full bg-black text-white shadow-lg transition-transform hover:scale-105 active:scale-95"
        aria-label="View cart"
      >
        <ShoppingBag size={22} />
        {totalItems > 0 && (
          <span className="absolute -right-1 -top-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
            {totalItems}
          </span>
        )}
      </Link>
    </div>
  );
};

export default FloatingCart; 