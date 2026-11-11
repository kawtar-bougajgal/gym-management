<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Course;
use Illuminate\Http\Request;

class ReceptionistController extends Controller
{
    public function index()
    {
        $members      = Member::with(['subscriptions.plan'])->latest()->get();
        $totalMembers = $members->count();
        $plans        = SubscriptionPlan::all();

        // ✅ Payments Today
        $paymentsToday = Payment::whereDate('paid_at', today())->sum('amount');

        // ✅ New Members This Month
        $newThisMonth = Member::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ✅ كنحسبو الحالة من آخر اشتراك لكل عضو (ماشي من أي سطر قديم فالجدول)
        // هادشي كيتفادى أن عضو جدد اشتراكو يبقى محسوب "expired" بسبب سطر قديم
        $membersWithLatest = Member::with(['latestSubscription.plan', 'latestSubscription.member'])->get();

        // ✅ Expiring Soon (آخر اشتراك، غادي ينتهي فـ 7 أيام الجاية)
        $expiringSoonSubs = $membersWithLatest
            ->filter(fn($m) => $m->status === 'expiring')
            ->map(fn($m) => $m->latestSubscription)
            ->sortBy('end_date')
            ->values();

        $expiringSoon     = $expiringSoonSubs->count();
        $expiringSoonList = $expiringSoonSubs;

        // ✅ Expired (آخر اشتراك منتهي فعلا)
        $expiredList = $membersWithLatest
            ->filter(fn($m) => $m->status === 'expired')
            ->map(fn($m) => $m->latestSubscription)
            ->sortByDesc('end_date')
            ->take(5)
            ->values();

        // ✅ Courses
        // ✅ Courses scheduled for today only, sorted by time
        $today = strtolower(now()->format('l')); // 'monday', 'tuesday', ...

        $courses = Course::with('coach.user')
            ->withCount('members')
            ->where('day', $today)
            ->orderBy('time')
            ->get();

        // ✅ Members JSON للـ Quick Search
        $membersJson = $members->map(fn($m) => [
            'id'     => $m->id,
            'name'   => $m->name,
            'phone'  => $m->phone,
            'status' => $m->status,
            'url'    => route('members.show', $m),
        ]);

        return view('receptionist.dashboard', compact(
            'members',
            'totalMembers',
            'plans',
            'paymentsToday',
            'newThisMonth',
            'expiringSoon',
            'expiringSoonList',
            'expiredList',
            'courses',
            'membersJson'
        ));
    }
}
