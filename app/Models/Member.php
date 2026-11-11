<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Subscription;
use App\Models\Payment;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'age',
    ];

    protected $appends = ['status'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function latestSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany('end_date');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // 🔥 AUTO STATUS — كيستعمل نفس منطق Subscription::computed_status
    public function getStatusAttribute()
    {
        $sub = $this->latestSubscription;

        if (!$sub) return 'no_subscription';

        return $sub->computed_status;
    }
}
