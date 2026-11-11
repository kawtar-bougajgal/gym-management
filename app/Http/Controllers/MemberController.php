<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with(['subscriptions.plan'])->latest()->get();
        $plans   = SubscriptionPlan::all();
        return view('members.index', compact('members', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'age'           => 'nullable|integer',
            'email'         => 'nullable|email',
            'plan_id'       => 'required|exists:subscription_plans,id',
            'method'        => 'required|in:cash,card,bank_transfer',
            'waive_fee'     => 'sometimes|boolean',
            'waived_reason' => 'nullable|string|max:255',
        ]);

        $member = Member::create(
            $request->only(['name', 'phone', 'age', 'email'])
        );

        $plan = SubscriptionPlan::find($request->plan_id);

        $waiveFee       = $request->boolean('waive_fee');
        $chargedFee     = $waiveFee ? 0 : $plan->inscription_fee;
        $totalAmount    = $plan->price + $chargedFee;

        $subscription = Subscription::create([
            'member_id'  => $member->id,
            'plan_id'    => $plan->id,
            'start_date' => now(),
            'end_date'   => now()->addDays($plan->duration),
            'status'     => 'active',
            'amount'     => $totalAmount,
        ]);

        Payment::create([
            'member_id'       => $member->id,
            'subscription_id' => $subscription->id,
            'amount'          => $totalAmount,
            'inscription_fee' => $plan->inscription_fee,
            'fee_waived'      => $waiveFee,
            'waived_reason'   => $waiveFee ? $request->waived_reason : null,
            'method'          => $request->method,
            'paid_at'         => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'member' => $member]);
        }

        return redirect()->route('members.index')
            ->with('success', 'Membre ajouté avec succès!');
    }

    public function show(Member $member)
    {
        $member->load('subscriptions.plan', 'payments');
        return view('members.show', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'age'   => 'nullable|integer',
            'email' => 'nullable|email',
        ]);

        $member->update($request->only(['name', 'phone', 'age', 'email']));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'member' => $member]);
        }

        return redirect()->route('members.index')
            ->with('success', 'Membre mis à jour!');
    }

    // ✅ ماخصهاش أي تعديل — دابا soft delete أوتوماتيكيا بفضل الـ trait
    public function destroy(Member $member)
    {
        $member->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('members.index')
            ->with('success', 'Membre supprimé!');
    }

    // 🆕 صفحة "Trash" — كتبين غير الأعضاء المحذوفين soft
    public function trashed()
    {
        $trashedMembers = Member::onlyTrashed()->latest('deleted_at')->get();
        return view('members.trashed', compact('trashedMembers'));
    }

    // 🆕 رجوع عضو محذوف
    public function restore($id)
    {
        $member = Member::onlyTrashed()->findOrFail($id);
        $member->restore();

        return redirect()->route('members.trashed')
            ->with('success', 'Membre restauré avec succès!');
    }

    // 🆕 حذف نهائي (بلا رجعة)
    public function forceDestroy($id)
    {
        $member = Member::onlyTrashed()->findOrFail($id);
        $member->forceDelete();

        return redirect()->route('members.trashed')
            ->with('success', 'Membre supprimé définitivement!');
    }
}
