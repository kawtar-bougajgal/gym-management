<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Payment;

class AdminController extends Controller
{
    public function index()
    {
        $totalMembers = Member::count();
        $newThisMonth = Member::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // مقارنة مع الشهر لي فات باش نحسبو trend حقيقي
        $newLastMonth = Member::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $newMembersTrend = $newLastMonth > 0
            ? round((($newThisMonth - $newLastMonth) / $newLastMonth) * 100)
            : null; // null = ماكاينش data كافية للمقارنة

        $activeStaff  = User::role('receptionist')->where('status', 'active')->count()
            + User::role('coach')->where('status', 'active')->count();
        $plans        = SubscriptionPlan::all();

        // Real Monthly Data (last 6 months) — مترجمة حسب اللغة الحالية
        $monthlyMembers = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyMembers->push([
                'month' => \Carbon\Carbon::parse($date)->locale(app()->getLocale())->isoFormat('MMM'),
                'members' => Member::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ]);
        }

        return view('admin.dashboard', compact(
            'totalMembers',
            'newThisMonth',
            'newMembersTrend',
            'activeStaff',
            'plans',
            'monthlyMembers'
        ));
    }

    public function reports()
    {
        // Total Revenue
        $totalRevenue = Payment::sum('amount');

        // ✅ Revenue by Month (last 6 months) — كنبنيو الشهور الستة مسبقا،
        // ونملأو بـ 0 الشهور اللي ماكاين فيها حتى payment، باش الرسم
        // البياني يبين دائما 6 أعمدة متتالية بلا "قفزات" فالشهور
        $rawRevenue = Payment::selectRaw('MONTH(paid_at) as month, YEAR(paid_at) as year, SUM(amount) as total')
            ->where('paid_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . $r->month);

        $revenueByMonth = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key  = $date->year . '-' . $date->month;
            $existing = $rawRevenue->get($key);

            $revenueByMonth->push((object) [
                'month' => $date->month,
                'year'  => $date->year,
                'total' => $existing ? $existing->total : 0,
            ]);
        }

        // Payments by Method
        $paymentsByMethod = Payment::selectRaw('method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('method')
            ->get();

        $totalMembers = Member::count();

        // ✅ كنستعملو نفس المنطق الموحد: آخر اشتراك لكل عضو، ماشي أي سطر قديم
        $allMembers = Member::with('latestSubscription')->get();

        $activeMembers  = $allMembers->filter(fn($m) => in_array($m->status, ['active', 'expiring']))->count();
        $expiredMembers = $allMembers->where('status', 'expired')->count();

        // Most Popular Plans
        $popularPlans = SubscriptionPlan::withCount('subscriptions')
            ->orderBy('subscriptions_count', 'desc')
            ->get();

        // ✅ Fees Waived — عدد المرات + مجموع الفلوس اللي كان خاصها تتخلص لو ماكانتش waived
        // كنجيبو inscription_fee الأصلية ديال الـ plan (ماشي ديال الـ Payment، لأن هاديك مسجلة 0 ملي تكون waived)
        // ✅ Fees Waived — هاد الشهر، مع مقارنة بالشهر لي فات (نفس منطق newMembersTrend فوق)
        $waivedCount = Payment::where('fee_waived', true)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->count();

        $waivedAmount = Payment::where('fee_waived', true)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('inscription_fee');

        $waivedCountLastMonth = Payment::where('fee_waived', true)
            ->whereMonth('paid_at', now()->subMonth()->month)
            ->whereYear('paid_at', now()->subMonth()->year)
            ->count();

        // ✅ فرق مطلق (ماشي نسبة مئوية) — الأرقام هنا صغيرة بطبيعتها (waivers قليلين/شهر)
        // null = ماكاينش data فالشهر لي فات (نفس منطق newMembersTrend) → الواجهة كتبين "New" بدلها
        $waivedTrend = $waivedCountLastMonth > 0
            ? $waivedCount - $waivedCountLastMonth
            : null;

        return view('admin.reports', compact(
            'totalRevenue',
            'revenueByMonth',
            'paymentsByMethod',
            'totalMembers',
            'activeMembers',
            'expiredMembers',
            'popularPlans',
            'waivedCount',
            'waivedAmount',
            'waivedTrend'
        ));
    }
}
