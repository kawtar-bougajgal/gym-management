<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    // عرض كل الـ Plans
    public function index()
    {
        $plans = SubscriptionPlan::withCount('subscriptions')->latest()->get();
        return view('admin.plans.index', compact('plans'));
    }

    // حفظ Plan جديد — JSON لـ React
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'duration'        => 'required|integer|min:1',
            'price'           => 'required|numeric|min:0',
            'inscription_fee' => 'required|numeric|min:0',
        ]);

        $plan = SubscriptionPlan::create([
            'name'            => $request->name,
            'duration'        => $request->duration,
            'price'           => $request->price,
            'inscription_fee' => $request->inscription_fee,
        ]);

        return response()->json(['success' => true, 'plan' => $plan]);
    }

    // حفظ التعديلات — JSON لـ React
    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'duration'        => 'required|integer|min:1',
            'price'           => 'required|numeric|min:0',
            'inscription_fee' => 'required|numeric|min:0',
        ]);

        $plan->update([
            'name'            => $request->name,
            'duration'        => $request->duration,
            'price'           => $request->price,
            'inscription_fee' => $request->inscription_fee,
        ]);

        return response()->json(['success' => true, 'plan' => $plan]);
    }

    // حذف Plan — JSON لـ React
    public function destroy(SubscriptionPlan $plan)
    {
        if ($plan->subscriptions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete plan — it has active subscriptions!',
            ], 422);
        }

        $plan->delete();
        return response()->json(['success' => true]);
    }
}
