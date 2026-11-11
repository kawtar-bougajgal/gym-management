<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['member' => fn($q) => $q->withTrashed(), 'subscription.plan'])
            ->latest('paid_at')
            ->get();

        // Stats عملية بسيطة للريسبشنيست — بلا أرقام الإيرادات الحساسة (هادو خاص Admin Reports)
        $now = now();
        $todayStart = $now->copy()->startOfDay();
        $thisMonthStart = $now->copy()->startOfMonth();

        $stats = [
            'today_count'      => Payment::where('paid_at', '>=', $todayStart)->count(),
            'this_month_count' => Payment::where('paid_at', '>=', $thisMonthStart)->count(),
        ];

        return view('payments.index', compact('payments', 'stats'));
    }

    public function create()
    {
        $members = Member::all();
        return view('payments.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'       => 'required|exists:members,id',
            'subscription_id' => 'required|exists:subscriptions,id',
            'amount'          => 'required|numeric|min:1',
            'method'          => 'required|in:cash,card,bank_transfer',
            'paid_at'         => 'required|date',
            'notes'           => 'nullable|string|max:500',
        ]);

        Payment::create($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Paiement enregistré avec succès!');
    }
}
