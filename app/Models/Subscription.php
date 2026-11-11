<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = ['member_id', 'plan_id', 'start_date', 'end_date', 'status', 'amount'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    // ✅ كيحسب الـ status من end_date بدون ما يبدل الـ database field
    public function getComputedStatusAttribute()
    {
        $end = Carbon::parse($this->end_date);

        if ($end->isPast()) {
            return 'expired';
        }

        // الفرق بين اليوم و end_date بالأيام — positive لأن end_date فـ المستقبل
        if (now()->diffInDays($end) <= 7) {
            return 'expiring';
        }

        return 'active';
    }
}
