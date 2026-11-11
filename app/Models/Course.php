<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'schedule',
        'coach_id',
        'day',
        'time',
        'max_capacity', // ✅ جديد
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class);
    }

    // ✅ جديد — واش الكورس كامل (عندو حد أقصى ووصل ليه)
    public function getIsFullAttribute()
    {
        if (is_null($this->max_capacity)) {
            return false; // بلا حد = ماعمرها كاملة
        }
        return $this->members()->count() >= $this->max_capacity;
    }

    public function getFormattedScheduleAttribute()
    {
        if ($this->day && $this->time) {
            $days = [
                'monday'    => ['fr' => 'Lun', 'en' => 'Mon', 'ar' => 'الاثنين'],
                'tuesday'   => ['fr' => 'Mar', 'en' => 'Tue', 'ar' => 'الثلاثاء'],
                'wednesday' => ['fr' => 'Mer', 'en' => 'Wed', 'ar' => 'الأربعاء'],
                'thursday'  => ['fr' => 'Jeu', 'en' => 'Thu', 'ar' => 'الخميس'],
                'friday'    => ['fr' => 'Ven', 'en' => 'Fri', 'ar' => 'الجمعة'],
                'saturday'  => ['fr' => 'Sam', 'en' => 'Sat', 'ar' => 'السبت'],
                'sunday'    => ['fr' => 'Dim', 'en' => 'Sun', 'ar' => 'الأحد'],
            ];
            $locale   = app()->getLocale();
            $dayLabel = $days[$this->day][$locale] ?? $this->day;
            return "$dayLabel {$this->time}";
        }
        return $this->schedule;
    }
}
