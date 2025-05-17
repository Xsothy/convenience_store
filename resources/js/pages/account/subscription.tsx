import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AccountLayout, ArtworkLayout, AppLayout } from '@/layouts';
import { Button } from '@/components/ui/button';
import UserSubscription from '@/components/UserSubscription';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { ClockIcon, CalendarIcon } from 'lucide-react';

interface SubscriptionProps {
  currentSubscription: App.Data.SubscribeData | null;
  scheduledSubscription: App.Data.SubscribeData | null;
}

export default function Subscription({ 
  currentSubscription, 
  scheduledSubscription 
}: SubscriptionProps) {
  // Format date helper
  const formatDate = (date: string | null) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };
  
  // Check if current subscription is in cancelled status (for plan switch)
  const isCurrentCancelled = currentSubscription?.status?.toString() === 'Cancelled';
  // Check if current subscription is in paused status
  const isCurrentPaused = currentSubscription?.status?.toString() === 'Paused';
  // Check if there's a plan switch happening
  const hasPlanSwitch = isCurrentCancelled && scheduledSubscription !== null;
  
  return (
    <>
      <Head title="Manage Subscription" />
      
      <div className="space-y-6">
        {hasPlanSwitch && (
          <div className="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <p className="text-blue-800">
              You've switched plans. Your current plan will remain active until {formatDate(currentSubscription?.end_at)}, 
              after which your new {scheduledSubscription?.plan?.name} plan will activate automatically.
            </p>
          </div>
        )}
        
        {/* Current subscription section */}
        {currentSubscription !== null ? (
          <Card>
            <CardHeader>
              <CardTitle>Current Subscription</CardTitle>
              <CardDescription>
                {hasPlanSwitch 
                  ? "Your subscription will remain active until the end date" 
                  : "Your active subscription details"}
              </CardDescription>
            </CardHeader>
            
            <CardContent>
              <UserSubscription subscription={currentSubscription} />
              
              {isCurrentPaused && (
                <div className="mt-4 flex items-center text-amber-700 bg-amber-50 p-3 rounded">
                  <ClockIcon className="h-5 w-5 mr-2 flex-shrink-0" />
                  <span>This subscription has been paused but remains active until {formatDate(currentSubscription.end_at)}</span>
                </div>
              )}
              
              {hasPlanSwitch && (
                <div className="mt-4 flex items-center text-purple-700 bg-purple-50 p-3 rounded">
                  <ClockIcon className="h-5 w-5 mr-2 flex-shrink-0" />
                  <span>Your subscription will be replaced by your new plan on {formatDate(currentSubscription.end_at)}</span>
                </div>
              )}
            </CardContent>
          </Card>
        ) : (
          !scheduledSubscription && (
            <Card>
              <CardHeader>
                <CardTitle>No active subscription</CardTitle>
                <CardDescription>
                  You don't have any active subscription at the moment.
                </CardDescription>
              </CardHeader>
            </Card>
          )
        )}
        
        {/* Scheduled subscription section */}
        {scheduledSubscription && (
          <Card className="border-l-4 border-blue-500">
            <CardHeader>
              <CardTitle>Upcoming Subscription</CardTitle>
              <CardDescription>
                This plan will automatically activate on {formatDate(scheduledSubscription.start_at)}
              </CardDescription>
            </CardHeader>
            
            <CardContent>
              <UserSubscription subscription={scheduledSubscription} />
              
              <div className="mt-4 flex items-center text-blue-700 bg-blue-50 p-3 rounded">
                <CalendarIcon className="h-5 w-5 mr-2 flex-shrink-0" />
                <span>Your {scheduledSubscription.plan?.name} plan is scheduled to start on {formatDate(scheduledSubscription.start_at)}</span>
              </div>
            </CardContent>
          </Card>
        )}
        
        <div className="flex justify-center mt-8">
          <Link href="/subscribe/plan">
            <Button variant={(currentSubscription || scheduledSubscription) ? "outline" : "default"}>
              {(currentSubscription || scheduledSubscription) ? "View Available Plans" : "Browse Subscription Plans"}
            </Button>
          </Link>
        </div>
      </div>
    </>
  );
}

Subscription.layout = page => (
  <AppLayout>
    <ArtworkLayout>
      <AccountLayout title={"Manage Subscription"} description={"View and manage your subscription plans and billing details."} children={page} />
    </ArtworkLayout>
  </AppLayout>
)
