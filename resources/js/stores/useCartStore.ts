import { create } from 'zustand';
import { router } from '@inertiajs/react';

interface CartState {
    carts: App.Data.CartData[];
    setCarts: (carts: App.Data.CartData[]) => void;
    addCart: (productId: number, quantity: number, variation?: string | null) => void;
    setProductQuantity: (productId: number, quantity: number, variation?: string | null) => void;
    removeCart: (productId: number, variation?: string | null) => void;
}

export const useCartStore = create<CartState>((set) => ({
    carts: [],
    setCarts: (carts: App.Data.CartData[]) => set({ carts }),
    addCart: (productId, quantity, variation = '') => {
        router.post(route('cart.add'), {productId, quantity, variation}, {
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ['carts'] });
            }
        })
    },
    setProductQuantity: (productId, quantity, variation = '') => {
        router.post(route('cart.add'), {productId, quantity, variation, 'type': 'set'}, {
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ['carts'] });
            }
        })
    },
    removeCart: (productId, variation = '') => {
        router.post(route('cart.delete'), {productId, variation}, {
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ['carts'] });
            }
        })
    },
}));