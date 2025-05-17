import React from 'react';
import { AppLayout, ArtworkLayout } from '@/layouts';
import {
  Tabs,
  TabsContent,
  TabsList,
  TabsTrigger,
} from '@/components/ui/tabs';
import { UserCircle2, CalendarDays, Lock, MapPin } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';
import AccountController from '@/wayfinder/actions/App/Http/Controllers/AccountController';

interface AccountLayoutProps {
  children: React.ReactNode;
  title?: string;
  description?: string;
}

export function AccountLayout({ children, title, description }: AccountLayoutProps) {
  const { url } = usePage();

  // Determine which tab should be active based on the current path
  const getActiveTab = () => {
    if (url.includes('/account/security')) {
      return 'security';
    } else if (url.includes('/account/addresses')) {
      return 'addresses';
    } else {
      return 'information';
    }
  };

  return (
    <div className="container mx-auto py-10 px-4 min-h-[60vh]">
      <h1 className="text-3xl font-bold mb-2">{title || 'Account Management'}</h1>
      <p className="text-gray-500 mb-8">
        {description || 'Manage your personal information, security settings, and subscription.'}
      </p>

      <Tabs defaultValue={getActiveTab()} className="w-full">
        <TabsList className="mb-8">
          <TabsTrigger
            value="information"
            className="flex items-center gap-2"
            asChild
          >
            <Link href={AccountController.index()}>
              <UserCircle2 className="h-4 w-4" />
              Your Information
            </Link>
          </TabsTrigger>
          <TabsTrigger
            value="addresses"
            className="flex items-center gap-2"
            asChild
          >
            <Link href={AccountController.addresses()}>
              <MapPin className="h-4 w-4" />
              Addresses
            </Link>
          </TabsTrigger>
          {/*<TabsTrigger*/}
          {/*  value="subscription"*/}
          {/*  className="flex items-center gap-2"*/}
          {/*  asChild*/}
          {/*>*/}
          {/*  <Link href="/account/subscription">*/}
          {/*    <CalendarDays className="h-4 w-4" />*/}
          {/*    Manage Subscription*/}
          {/*  </Link>*/}
          {/*</TabsTrigger>*/}
          <TabsTrigger
            value="security"
            className="flex items-center gap-2"
            asChild
          >
            <Link href={AccountController.security()}>
              <Lock className="h-4 w-4" />
              Security
            </Link>
          </TabsTrigger>
        </TabsList>

        <TabsContent value={getActiveTab()}>
          {children}
        </TabsContent>
      </Tabs>
    </div>
  );
}

// Wrapper for convenience
export default function WithAccountLayout(page: React.ReactNode, title?: string, description?: string) {
  return (
    <AppLayout>
      <ArtworkLayout>
        <AccountLayout title={title} description={description}>
          {page}
        </AccountLayout>
      </ArtworkLayout>
    </AppLayout>
  );
}
