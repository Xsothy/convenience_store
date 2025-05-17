import React, { ReactNode } from 'react';
import ArtworkNavbar from '@/components/layout/ArtworkNavbar';
import { Head } from '@inertiajs/react';
import UserDropdown from '@/components/layout/UserDropdown';

interface ArtworkLayoutProps {
  children: ReactNode;
  title?: string;
}

export default function ArtworkLayout({ children, title = 'Artwork Shop' }: ArtworkLayoutProps) {
  return (
    <>
      <Head title={title} />
      {/* Artwork-specific navigation */}
      <ArtworkNavbar />

      {/* Main content */}
      <main>
          {children}
      </main>
    </>
  );
}