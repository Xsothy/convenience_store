import React from 'react';
import { Badge } from "@/components/ui/badge";
import { CheckCircle2, CalendarDays, Download } from 'lucide-react';

interface UserSubscriptionProps {
  subscription: any; // Using any to avoid TypeScript errors with the plan structure
}

export default function UserSubscription({ subscription }: UserSubscriptionProps) {
  if (!subscription || !subscription.plan) {
    return null;
  }
  
  // Format date helper
  const formatDate = (date: string | null) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };
  
  // Get subscription status
  const getStatusBadge = () => {
    const status = subscription.status?.toString();
    
    if (status === 'Active') {
      return <Badge className="bg-green-100 text-green-800 border-0">Active</Badge>;
    } else if (status === 'Paused') {
      return <Badge className="bg-amber-100 text-amber-800 border-0">Paused</Badge>;
    } else if (status === 'Cancelled') {
      return <Badge className="bg-red-100 text-red-800 border-0">Cancelled</Badge>;
    } else if (status === 'Scheduled') {
      return <Badge className="bg-blue-100 text-blue-800 border-0">Scheduled</Badge>;
    } else {
      return <Badge className="bg-gray-100 text-gray-800 border-0">{status}</Badge>;
    }
  };

  // Helper to safely get plan price
  const getPlanPrice = () => {
    const plan = subscription.plan;
    if (!plan) return '$0.00';
    
    // Try different property names for price
    const price = plan.price || plan.amount || 0;
    return `$${Number(price).toFixed(2)}`;
  };
  
  // Helper to safely get plan period
  const getPlanPeriod = () => {
    const plan = subscription.plan;
    if (!plan) return 'month';
    
    // Try different property names for period
    const period = plan.period || plan.billing_period || 'month';
    return typeof period === 'string' ? period.toLowerCase() : 'month';
  };

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-2">
          <h3 className="text-lg font-medium">{subscription.plan.name} Plan</h3>
          {getStatusBadge()}
        </div>
        <div className="text-lg font-semibold">
          {getPlanPrice()}/{getPlanPeriod()}
        </div>
      </div>
      
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div className="flex items-center gap-3">
          <CalendarDays className="h-5 w-5 text-primary" />
          <div>
            <p className="text-sm text-muted-foreground">Start Date</p>
            <p className="font-medium">{formatDate(subscription.start_at)}</p>
          </div>
        </div>
        
        <div className="flex items-center gap-3">
          <CalendarDays className="h-5 w-5 text-primary" />
          <div>
            <p className="text-sm text-muted-foreground">Renewal Date</p>
            <p className="font-medium">{formatDate(subscription.end_at)}</p>
          </div>
        </div>
      </div>
      
      <div className="border-t border-gray-100 pt-4 mt-4">
        <h4 className="text-sm font-medium mb-3">Plan Benefits</h4>
        <div className="space-y-2">
          {/* Free Shipping Benefit */}
          {subscription.plan.free_shipping && (
            <div className="flex items-center gap-2">
              <CheckCircle2 className="h-4 w-4 text-green-600" />
              <span>Free shipping on all orders</span>
            </div>
          )}
          
          {/* Discount Benefit */}
          {(subscription.plan.discount_percent || subscription.plan.discount) && (
            <div className="flex items-center gap-2">
              <CheckCircle2 className="h-4 w-4 text-green-600" />
              <span>
                {(subscription.plan.discount_percent || subscription.plan.discount)}% discount on all purchases
              </span>
            </div>
          )}
          
          {/* Digital Downloads Benefit */}
          {subscription.digital_downloads_remaining !== undefined && (
            <div className="flex items-center gap-2">
              <Download className="h-4 w-4 text-primary" />
              <span>
                {subscription.digital_downloads_remaining > 0 
                  ? `${subscription.digital_downloads_remaining} digital downloads remaining` 
                  : 'All digital downloads used'}
              </span>
            </div>
          )}
        </div>
      </div>
    </div>
  );
} 