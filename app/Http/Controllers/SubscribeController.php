<?php

namespace App\Http\Controllers;

use App\Data\SubscribeData;
use App\Data\SubscribePlanData;
use App\Enums\SubscribeStatus;
use App\Events\SubscriptionCreated;
use App\Models\Subscribe;
use App\Models\SubscribePlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SubscribeController extends Controller
{
    /**
     * Display the subscription plans page.
     */
    public function plan()
    {
        // Check if user has any subscription
        $subscription = Auth::user()?->subscriptions()->with('plan')->latest()->first();

        return Inertia::render('subscription/plans', [
            'plans' => SubscribePlanData::collect(
                SubscribePlan::where('is_active', true)
                    ->orderBy('price')
                    ->get()
            ),
            'subscription' => $subscription ? SubscribeData::from($subscription) : null,
        ]);
    }

    /**
     * Subscribe to a plan.
     */
    public function subscribe(SubscribePlan $plan, SubscribeData $data)
    {
        // Check if user already has any subscription (active or not)
        $existingSubscription = Auth::user()->subscriptions()->latest()->first();

        if (!$existingSubscription) {
             // For new subscribers (no existing subscription)
            $data->end_at = $data->end_at ?? now()->addMonths($data->period->months());
            $data->start_at = now();

            // Create new subscription with the validated data
            $subscription = $plan->subscribes()->create([
                'user_id' => Auth::id(),
                'period' => $data->period,
                'start_at' => $data->start_at,
                'end_at' => $data->end_at,
                'status' => SubscribeStatus::ACTIVE,
            ]);

            // Dispatch event to schedule renewal
            event(new SubscriptionCreated($subscription));

            return redirect()->route('account.subscription')->with('success', 'Successfully subscribed to the '.$plan->name.' plan');
        }

        if ($existingSubscription->plan_id == $plan->id) {
            // If trying to subscribe to the same plan, just redirect to manage
            return redirect()->route('account.subscription')
                ->with('info', 'You are already subscribed to this plan.');
        }

        // Check if there's an existing scheduled subscription
        $scheduledSubscription = Auth::user()->subscriptions()
            ->where('status', SubscribeStatus::SCHEDULED->value)
            ->first();

        // Mark the current subscription as cancelled (regardless of its current state)
        // For paused subscriptions, this ensures it remains active until end date but is marked as cancelled
        if (in_array($existingSubscription->status, [SubscribeStatus::ACTIVE, SubscribeStatus::PAUSED])) {
            $existingSubscription->update([
                'status' => SubscribeStatus::CANCELLED
            ]);
        }

        $data->end_at = Carbon::parse($existingSubscription->end_at)
            ->addMonths($data->period->months());
        $data->start_at = $existingSubscription->end_at;

        // If there's already a scheduled subscription, update it instead of creating a new one
        if ($scheduledSubscription) {
            $subscription = $scheduledSubscription;
            $subscription->update([
                'plan_id' => $plan->id,
                'period' => $data->period,
                'start_at' => $data->start_at,
                'end_at' => $data->end_at,
            ]);
        } else {
            // Create the new subscription with 'Scheduled' status
            $subscription = $plan->subscribes()->create([
                'user_id' => Auth::id(),
                'period' => $data->period,
                'start_at' => $data->start_at,
                'end_at' => $data->end_at,
                'status' => SubscribeStatus::SCHEDULED,
            ]);
        }

        // Dispatch event to schedule renewal
        event(new SubscriptionCreated($subscription));

        $currentPlanName = $existingSubscription->plan->name;
        $newPlanName = $plan->name;

        return redirect()->route('account.subscription')
            ->with('success', "You have switched from {$currentPlanName} to {$newPlanName}. Your new plan will activate on ".
            Carbon::parse($existingSubscription->end_at)->format('F j, Y').
            '. Your current plan will remain active until then.');
    }

    /**
     * Renew an existing subscription.
     */
    public function renew(SubscribePlan $plan)
    {
        // Find the user's subscription for this plan
        $subscription = Auth::user()->subscriptions()
            ->where('plan_id', $plan->id)
            ->latest()
            ->first();

        if (! $subscription) {
            return redirect()->back()->with('error', 'No subscription found for this plan');
        }

        // Ensure the subscription belongs to the authenticated user
        if ($subscription->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        // Update the end date based on current period
        $subscription->update([
            'end_at' => now()->addMonths($subscription->period->months()),
            'status' => SubscribeStatus::ACTIVE,
        ]);

        // Dispatch event to schedule renewal
        event(new SubscriptionCreated($subscription));

        return redirect()->route('account.subscription')->with('success', 'Your subscription has been renewed');
    }

    /**
     * Cancel a subscription.
     */
    public function cancel(SubscribePlan $plan)
    {
        // Find the user's subscription for this plan
        $subscription = Auth::user()->subscriptions()
            ->where('plan_id', $plan->id)
            ->where('status', SubscribeStatus::ACTIVE->value)
            ->first();

        if (! $subscription) {
            return redirect()->back()->with('error', 'No active subscription found for this plan');
        }

        // Ensure the subscription belongs to the authenticated user
        if ($subscription->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        // Check if there's a scheduled subscription (plan switch)
        $scheduledSubscription = Auth::user()->subscriptions()
            ->where('status', SubscribeStatus::SCHEDULED->value)
            ->first();

        // If there's a scheduled subscription, this is a switch; otherwise, it's a pause
        $status = $scheduledSubscription ? SubscribeStatus::CANCELLED : SubscribeStatus::PAUSED;

        // Mark the subscription as paused/cancelled but keep it active until the end date
        $subscription->update([
            'status' => $status,
        ]);

        $message = $scheduledSubscription
            ? 'Your subscription has been cancelled due to plan switch and will remain active until '.$subscription->end_at->format('F j, Y')
            : 'Your subscription has been paused and will remain active until '.$subscription->end_at->format('F j, Y');

        return redirect()->route('account.subscription')->with('success', 'Your subscription has been cancelled');
    }

    /**
     * Display the subscription management page.
     */
    public function manage()
    {
        $user = Auth::user();
        
        // Get current active subscription
        $currentSubscription = Subscribe::where('user_id', $user->id)
            ->where(function($query) {
                $query->where('status', SubscribeStatus::ACTIVE)
                    ->orWhere('status', SubscribeStatus::PAUSED)
                    ->orWhere('status', SubscribeStatus::CANCELLED);
            })
            ->where('end_at', '>', Carbon::now())
            ->with('plan')
            ->first();
            
        // Get scheduled subscription (if any)
        $scheduledSubscription = null;
        if ($currentSubscription && $currentSubscription->status === SubscribeStatus::CANCELLED) {
            $scheduledSubscription = Subscribe::where('user_id', $user->id)
                ->where('status', SubscribeStatus::SCHEDULED)
                ->where('start_at', '>', Carbon::now())
                ->with('plan')
                ->first();
        }

        return Inertia::render('subscription/manage', [
            'currentSubscription' => $currentSubscription ? SubscribeData::from($currentSubscription) : null,
            'scheduledSubscription' => $scheduledSubscription ? SubscribeData::from($scheduledSubscription) : null,
        ]);
    }
}
