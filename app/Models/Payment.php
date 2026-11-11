<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'member_id',
        'subscription_id',
        'amount',
        'inscription_fee',
        'fee_waived',
        'waived_reason',
        'method',
        'paid_at',
        'notes',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
