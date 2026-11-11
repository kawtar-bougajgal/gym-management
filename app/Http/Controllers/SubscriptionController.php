<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 15;

        // آخر اشتراك لكل عضو
        $membersWithSubs = Member::with(['latestSubscription.plan', 'latestSubscription.member'])
            ->get()
            ->filter(fn($m) => $m->latestSubscription !== null);

        // الترتيب: Expired → Expiring → Active
        $priority = ['expired' => 0, 'expiring' => 1, 'active' => 2];

        $sortedMembers = $membersWithSubs->sort(function ($a, $b) use ($priority) {
            $cmp = $priority[$a->status] <=> $priority[$b->status];
            if ($cmp !== 0) return $cmp;

            $aTime = $a->latestSubscription->end_date->timestamp;
            $bTime = $b->latestSubscription->end_date->timestamp;

            return $a->status === 'expired' ? $bTime <=> $aTime : $aTime <=> $bTime;
        })->values();

        // Stats: دايما على الكل (بلا بحث ولا فلتر)
        $stats = [
            'total'         => $sortedMembers->count(),
            'active'        => $sortedMembers->where('status', 'active')->count(),
            'expiring_soon' => $sortedMembers->where('status', 'expiring')->count(),
            'expired'       => $sortedMembers->where('status', 'expired')->count(),
        ];

        // بحث + فلتر
        $q      = mb_strtolower(trim((string) $request->query('q', '')));
        $status = $request->query('status', 'all');

        $filtered = $sortedMembers->filter(function ($m) use ($q, $status) {
            if ($status !== 'all' && $m->status !== $status) {
                return false;
            }
            if ($q === '') {
                return true;
            }
            $sub = $m->latestSubscription;
            $pad = 'm' . str_pad($m->id, 3, '0', STR_PAD_LEFT);

            return str_contains(mb_strtolower($m->name), $q)
                || str_contains(mb_strtolower($sub->plan->name ?? ''), $q)
                || str_contains($pad, $q)
                || str_contains((string) $sub->id, $q);
        })->values();

        // Pagination
        $page  = max(1, (int) $request->query('page', 1));
        $total = $filtered->count();

        $items = $filtered->forPage($page, $perPage)
            ->map(fn($m) => $this->formatSubscription($m->latestSubscription))
            ->values();

        $meta = [
            'total'     => $total,
            'page'      => $page,
            'per_page'  => $perPage,
            'last_page' => max(1, (int) ceil($total / $perPage)),
        ];

        // طلب AJAX → كنرجعو JSON فقط
        if ($request->wantsJson()) {
            return response()->json(['data' => $items, 'meta' => $meta]);
        }

        $members = Member::all();
        $plans   = SubscriptionPlan::all();

        return view('subscriptions.index', [
            'subscriptions' => $items,
            'meta'          => $meta,
            'stats'         => $stats,
            'members'       => $members,
            'plans'         => $plans,
        ]);
    }

    // كيحول اشتراك لـ array جاهز للـ React (الحساب اللي كان فالـ Blade)
    private function formatSubscription($s): array
    {
        $end     = \Carbon\Carbon::parse($s->end_date);
        $start   = \Carbon\Carbon::parse($s->start_date);
        $total   = max(1, $start->diffInDays($end));
        $elapsed = $start->diffInDays(now());

        return [
            'id'         => $s->id,
            'member_id'  => $s->member_id,
            'member'     => $s->member->name ?? '—',
            'member_pad' => 'M' . str_pad($s->member_id, 3, '0', STR_PAD_LEFT),
            'plan'       => $s->plan->name ?? '—',
            'plan_id'    => $s->plan_id,
            'start'      => $start->format('d M Y'),
            'end'        => $end->format('d M Y'),
            'pct'        => min(100, round(($elapsed / $total) * 100)),
            'daysLeft'   => max(0, (int) now()->diffInDays($end, false)),
            'status'     => $s->computed_status,
        ];
    }

    public function create()
    {
        $members = Member::all();
        $plans   = SubscriptionPlan::all();
        return view('subscriptions.create', compact('members', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id'      => 'required|exists:members,id',
            'plan_id'        => 'required|exists:subscription_plans,id',
            'method'         => 'required|in:cash,card,bank_transfer',
            'waive_fee'      => 'sometimes|boolean',
            'waived_reason'  => 'nullable|string|max:255',
            'notes'          => 'nullable|string|max:500',
        ]);

        $plan  = SubscriptionPlan::find($request->plan_id);
        $start = now();
        $end   = now()->addDays($plan->duration);

        $waiveFee       = $request->boolean('waive_fee') && $plan->inscription_fee > 0;
        $chargedFee     = $waiveFee ? 0 : $plan->inscription_fee;
        $totalAmount    = $plan->price + $chargedFee;

        $subscription = Subscription::create([
            'member_id'  => $request->member_id,
            'plan_id'    => $request->plan_id,
            'start_date' => $start,
            'end_date'   => $end,
            'status'     => 'active',
            'amount'     => $totalAmount,
        ]);

        Payment::create([
            'member_id'       => $request->member_id,
            'subscription_id' => $subscription->id,
            'amount'          => $totalAmount,
            'inscription_fee' => $plan->inscription_fee,
            'fee_waived'      => $waiveFee,
            'waived_reason'   => $waiveFee ? $request->waived_reason : null,
            'method'          => $request->method,
            'paid_at'         => now(),
            'notes'           => $request->notes,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'subscription' => $subscription]);
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Abonnement créé avec succès!');
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['member' => fn($q) => $q->withTrashed(), 'plan']);
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $members = Member::all();
        $plans   = SubscriptionPlan::all();
        return view('subscriptions.edit', compact('subscription', 'members', 'plans'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'method'  => 'required|in:cash,card,bank_transfer',
            'paid_at' => 'required|date',
            'notes'   => 'nullable|string|max:500',
        ]);

        $plan  = SubscriptionPlan::find($request->plan_id);
        $start = $subscription->end_date->isPast() ? now() : $subscription->end_date;
        $end   = $start->copy()->addDays($plan->duration);

        $subscription->update([
            'plan_id'    => $request->plan_id,
            'start_date' => $start,
            'end_date'   => $end,
            'status'     => 'active',
            'amount'     => $plan->price,
        ]);

        Payment::create([
            'member_id'       => $subscription->member_id,
            'subscription_id' => $subscription->id,
            'amount'          => $plan->price,
            'method'          => $request->method,
            'paid_at'         => $request->paid_at,
            'notes'           => $request->notes,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Abonnement renouvelé avec succès!');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Abonnement supprimé!');
    }
}
