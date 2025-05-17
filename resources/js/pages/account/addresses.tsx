import React from 'react';
import { AppLayout, ArtworkLayout, AccountLayout } from '@/layouts';
import AddressManager from '@/components/shared/AddressManager';
import { addressRoutes } from '@/utils/addressRoutes';

interface AddressesProps {
  addresses: App.Data.AddressData[];
}

const Addresses = ({ addresses }: AddressesProps) => {
  return (
    <AddressManager 
      addresses={addresses}
      title="My Addresses"
      description="Manage your saved shipping addresses"
      routes={addressRoutes.authenticated}
    />
  );
};

Addresses.layout = (page: React.ReactNode) => (
  <AppLayout>
    <ArtworkLayout>
      <AccountLayout children={page} />
    </ArtworkLayout>
  </AppLayout>
);

export default Addresses;
