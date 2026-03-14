<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserSubscriptionController extends Controller
{
    use ApiResponse;

    // Current Subscription
    public function currentSubscription()
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }
        
        $subscription = UserSubscription::with('plan')
            ->where('user_id', auth()->id())->first();

        if (!$subscription) {
            return $this->error(null, 'No active subscription found.', 404);
        }

        return $this->success($subscription, 'Current subscription details.', 200);
    }

    // Purchase Subscription
    public function purchaseSubscription(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required|exists:subscription_plans,id',
            'type'            => 'required|in:monthly,yearly',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $user = auth()->user();
            $existingSub = UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->first();

            if ($existingSub) {
                $formattedDate = \Carbon\Carbon::parse($existingSub->end_date)->format('d M, Y');
                return $this->error("You have already purchased a plan. It will expire on {$formattedDate}.", 400);
            }

            $plan = SubscriptionPlan::findOrFail($request->subscription_id);

            $startDate = Carbon::now();
            $endDate = ($request->type == 'monthly') 
                ? Carbon::now()->addMonth() 
                : Carbon::now()->addYear();

            $subscription = UserSubscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'subscription_id' => $plan->id,
                    'start_date'      => $startDate,
                    'end_date'        => $endDate,
                    'status'          => 'active',
                ]
            );

            return $this->success($subscription, 'Subscription activated successfully.', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

}
