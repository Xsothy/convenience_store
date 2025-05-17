import Navbar from '@/components/layout/Navbar';
import React, { useEffect } from 'react';
import Footer from '@/components/layout/Footer';
import { usePage } from '@inertiajs/react';
import type { SharedData } from '@/types';
import { useCartStore } from '@/stores/useCartStore';

export default ({ children }) => {
    const carts = usePage<SharedData>().props.carts
    const cartStore = useCartStore();
    const setCarts = cartStore.setCarts;

    useEffect(() => {
        setCarts(carts);
    }, [carts]);

    return (
        <div className="bg-white">
            <Navbar />
                {children}
            <Footer />
        </div>
    );
}
