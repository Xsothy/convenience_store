// Guest Address Management Page
import React from 'react';
import { AppLayout, ArtworkLayout } from '@/layouts';
import AddressManager from '@/components/shared/AddressManager';
import { addressRoutes } from '@/utils/addressRoutes';

interface AddressesProps {
  addresses: App.Data.AddressData[];
}

const Addresses = ({ addresses }: AddressesProps) => {
  return (
    <div className="container mx-auto px-4 pb-16 pt-8">
      <AddressManager 
        addresses={addresses}
        title="Shipping Addresses"
        description="Manage your shipping addresses for checkout"
        routes={addressRoutes.unauthenticated}
      />
    </div>
  );
};

Addresses.layout = (page: React.ReactNode) => (
  <AppLayout>
    <ArtworkLayout children={page} />
  </AppLayout>
);

export default Addresses; 