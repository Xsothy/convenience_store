import React, { ReactNode } from 'react';
import WeddingNavbar from '@/components/layout/WeddingNavbar';
import { Head } from '@inertiajs/react';

interface WeddingLayoutProps {
  children: ReactNode;
  title?: string;
}

export default function WeddingLayout({ children, title = 'Wedding Shop' }: WeddingLayoutProps) {
  return (
    <>
      <Head title={title} />
      
      {/* Wedding-specific navigation */}
      <WeddingNavbar />
      
      {/* Main content */}
      <main className="py-6">
        <div className="container mx-auto px-4">
          {children}
        </div>
      </main>
    </>
  );
} 