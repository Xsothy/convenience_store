import React, { useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import { AppLayout, ArtworkLayout } from '@/layouts';

// This component now just redirects to the Account Subscription page
export default function ManageSubscriptions() {
  
  useEffect(() => {
    // Redirect to the account subscription page
    router.visit('/account/subscription');
  }, []);
  
  return (
    <>
      <Head title="Redirecting..." />
      <div className="container mx-auto py-10 px-4 flex justify-center items-center">
        <p>Redirecting to account management...</p>
      </div>
    </>
  );
}

ManageSubscriptions.layout = (page: React.ReactNode) => (
    <AppLayout>
        <ArtworkLayout children={page} />
    </AppLayout>
)