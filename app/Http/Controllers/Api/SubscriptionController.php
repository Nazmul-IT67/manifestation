<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    use ApiResponse;

    // Get All Subscription
    public function index()
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        try {
            $plans = SubscriptionPlan::where('is_active', true)->get();
            return $this->success($plans, 'Subscription plans retrieved successfully.', 200);
            
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    // Get Single Subscription
    public function show(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $plan = SubscriptionPlan::find($request->id);
        if (!$plan) {
            return $this->success(null, 'Data not found', 404);
        }

        return $this->success($plan, 'Plan retrieved successfully', 200);
    }

    // Store New Subscription
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly'  => 'required|numeric|min:0',
            'plan_features' => 'nullable|array',
            'badge_text'    => 'nullable|string|max:50',
            'is_active'     => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $plan = SubscriptionPlan::create([
                'name'          => $request->name,
                'price_monthly' => $request->price_monthly,
                'price_yearly'  => $request->price_yearly,
                'plan_features' => $request->plan_features,
                'badge_text'    => $request->badge_text,
                'is_active'     => $request->is_active ?? true,
            ]);

            return $this->success($plan, 'Subscription plan created successfully.', 201);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    // Update Subscription
    public function update(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }
        
        $plan = SubscriptionPlan::find($request->id);
        if (!$plan) {
            return $this->error('Subscription plan not found.', 404);
        }

        $validator = Validator::make($request->all(), [
            'name'          => 'nullable|string|max:255',
            'price_monthly' => 'nullable|numeric|min:0',
            'price_yearly'  => 'nullable|numeric|min:0',
            'plan_features' => 'nullable|array',
            'badge_text'    => 'nullable|string|max:50',
            'is_active'     => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $plan->update($request->all());
            return $this->success($plan, 'Subscription plan updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}