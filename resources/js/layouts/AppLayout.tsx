import React, { ReactNode, useEffect } from 'react';
import { Head, usePage } from '@inertiajs/react';
import MintedHeader from '@/components/layout/MintedHeader';
import { useCartStore } from '@/stores/useCartStore';
import { SharedData } from '@/types';
import { Toaster } from '@/components/ui/sonner';
import { toast } from 'sonner';
import Footer from '@/components/layout/Footer';
import FloatingCart from '@/components/shared/FloatingCart';

interface MintedLayoutProps {
  children: ReactNode;
}

export default function MintedLayout({ children}: MintedLayoutProps) {
  const { carts, flash } = usePage<SharedData & {
    flash: {
      success?: string;
      error?: string;
      warning?: string;
      info?: string;
    }
  }>().props;
  
  const cartStore = useCartStore();
  const setCarts = cartStore.setCarts;

  useEffect(() => {
      setCarts(carts);
  }, [carts, setCarts]);
  
  // Handle flash messages with toast notifications
  useEffect(() => {
    if (flash?.success) {
      toast.success(flash.success);
    }
    
    if (flash?.error) {
      toast.error(flash.error);
    }
    
    if (flash?.warning) {
      toast.warning(flash.warning);
    }
    
    if (flash?.info) {
      toast.info(flash.info);
    }
  }, [flash]);

  return (
    <div className="min-h-screen flex flex-col">
      <Head title="Artwork Shop" />
      
      <MintedHeader />
      
      <div className="flex-grow">
        {children}
      </div>
      
      <Footer />
      <Toaster />
      
      <FloatingCart />
    </div>
  );
} 