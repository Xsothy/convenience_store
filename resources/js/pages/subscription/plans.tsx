import React, { useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppLayout, ArtworkLayout } from '@/layouts';
import { Check, AlertTriangle, ArrowLeft } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/react';
import SubscribeController from '@/wayfinder/actions/App/Http/Controllers/SubscribeController';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

interface SubscriptionPlansProps {
  plans: App.Data.SubscribePlanData[];
  subscription: App.Data.SubscribeData | null;
}

const SubscriptionPlans = ({ plans, subscription }: SubscriptionPlansProps) => {
  // Selected period for each plan (planId -> period)
  const [selectedPeriods, setSelectedPeriods] = useState<Record<number, App.Enums.SubscriptionPeriod>>(
    // Default all plans to monthly
    Object.fromEntries(plans.map(plan => [plan.id, 'Monthly']))
  );
  
  // Use Inertia form to handle submissions
  const { processing, submit, setData, data } = useForm<App.Data.SubscribeData>();
  
  useEffect(() => {
    setData({
      ...data,
      period: 'Monthly'
    })
  }, [])
  // Update form data when period changes
  const handlePeriodChange = (plan: App.Data.SubscribePlanData, period: App.Enums.SubscriptionPeriod) => {
    setSelectedPeriods(prev => ({
      ...prev,
      [plan.id]: period
    }));

    setData({
      ...data,
      period
    })
  };

  // Handle subscription
  const handleSubscribe = (planId: number) => {
    submit(SubscribeController.subscribe({ plan: planId }), {
      preserveScroll: true,
    });
  };

  // Calculate actual price based on chosen period
  const calculatePrice = (plan, currentPeriod) => {
    if (currentPeriod === 'Annually') {
      return plan.annual_price;
    }
    return plan.price;
  };

  // Calculate percentage saved with annual plan compared to monthly
  const calculateSavings = (plan) => {
    if (!plan.annual_price) return 0;
    
    const yearlyPrice = plan.price * 12;
    const savings = ((yearlyPrice - plan.annual_price) / yearlyPrice * 100);
    
    return Math.round(savings);
  };

  return (
    <>
      <Head title="Subscription Plans" />
      
      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          
          {subscription && (
            <div className="mb-8">
              <Alert className="bg-amber-50 border-amber-300">
                <AlertTriangle className="h-5 w-5 text-amber-600" />
                <AlertTitle className="text-amber-800 font-medium">You have an active subscription</AlertTitle>
                <AlertDescription className="text-amber-700">
                  <div className="flex flex-col sm:flex-row sm:items-center justify-between w-full">
                    <div>
                      You are currently subscribed to the <b>{subscription.plan?.name}</b> plan.
                    </div>
                    <Link href="/account/subscription" className="mt-3 sm:mt-0">
                      <Button variant="outline" size="sm" className="flex items-center gap-1">
                        <ArrowLeft className="h-4 w-4" />
                        Return to Account
                      </Button>
                    </Link>
                  </div>
                </AlertDescription>
              </Alert>
            </div>
          )}

          <div className="text-center mb-12">
            <h1 className="text-3xl font-bold">Choose Your Subscription Plan</h1>
            <p className="mt-4 text-lg text-gray-600">
              {subscription ? 'Switch to a different plan' : 'Select the plan that best fits your needs and get started today'}
            </p>
          </div>
          
          <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            {plans.map((plan) => {
              const isCurrentPlan = subscription?.plan?.id === plan.id;
              const currentPeriod = selectedPeriods[plan.id] || 'Monthly';
              const price = calculatePrice(plan, currentPeriod);
              const savingsPercent = calculateSavings(plan);
              
              return (
                <div 
                  key={plan.id} 
                  className={`relative bg-white border rounded-lg shadow-sm overflow-hidden ${
                    plan.is_popular ? 'border-primary shadow-md ring-2 ring-primary/20 scale-105' : 'border-gray-200'
                  } ${isCurrentPlan ? 'border-green-500 ring-2 ring-green-200' : ''}`}
                >
                  {plan.is_popular && (
                    <div className="absolute top-0 right-0 bg-primary text-white px-3 py-1 text-sm font-medium rounded-bl-lg">
                      Popular
                    </div>
                  )}
                  
                  {isCurrentPlan && (
                    <div className="absolute top-0 right-0 bg-green-500 text-white px-3 py-1 text-sm font-medium rounded-bl-lg">
                      Current Plan
                    </div>
                  )}
                  
                  <div className={`p-6 ${plan.is_popular ? 'bg-primary/5' : ''}`}>
                    <h3 className="text-xl font-semibold text-center">{plan.name}</h3>
                    
                    <div className="mt-6 flex justify-center">
                      <RadioGroup 
                        defaultValue="Monthly"
                        value={currentPeriod}
                        onValueChange={(value: App.Enums.SubscriptionPeriod) => handlePeriodChange(plan, value)}
                        className="flex space-x-4"
                      >
                        <div className="flex items-center space-x-2">
                          <RadioGroupItem value="Monthly" id={`monthly-${plan.id}`} />
                          <Label htmlFor={`monthly-${plan.id}`}>Monthly</Label>
                        </div>
                        <div className="flex items-center space-x-2">
                          <RadioGroupItem value="Annually" id={`annually-${plan.id}`} />
                          <Label htmlFor={`annually-${plan.id}`}>
                            Annually 
                            {savingsPercent > 0 && (
                              <span className="text-xs text-green-600 ml-1">
                                (Save {savingsPercent}%)
                              </span>
                            )}
                          </Label>
                        </div>
                      </RadioGroup>
                    </div>
                    
                    <div className="mt-6 text-center">
                      <span className="text-4xl font-bold">${price.toFixed(2)}</span>
                      <span className="text-gray-500 ml-2">
                        {currentPeriod === 'Monthly' ? '/month' : '/year'}
                      </span>
                    </div>
                    
                    <div className="mt-6">
                      <Button 
                        onClick={() => handleSubscribe(plan.id)}
                        className="w-full cursor-pointer"
                        variant={plan.is_popular ? "default" : "outline"}
                        disabled={processing}
                      >
                        Subscribe Now
                      </Button>
                    </div>
                    
                    <p className="mt-4 text-sm text-gray-500 text-center">
                      {plan.description || `${currentPeriod === 'Monthly' ? 'Monthly' : 'Annual'} subscription to our ${plan.name} plan`}
                    </p>
                  </div>
                  
                  <div className="px-6 pt-4 pb-8">
                    <h4 className="text-sm font-medium text-gray-900 mb-4">Features include:</h4>
                    <ul className="space-y-3">
                      {plan.features_list ? (
                        plan.features_list.map((feature, index) => (
                          <li key={index} className="flex items-start">
                            <div className="flex-shrink-0">
                              <Check className="h-5 w-5 text-green-500" />
                            </div>
                            <p className="ml-3 text-sm text-gray-700">{feature}</p>
                          </li>
                        ))
                      ) : (
                        <li className="text-sm text-gray-500">No features available</li>
                      )}
                    </ul>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </>
  );
};

SubscriptionPlans.layout = (page: React.ReactNode) => (
  <AppLayout>
    <ArtworkLayout children={page} />
  </AppLayout>
)

export default SubscriptionPlans;