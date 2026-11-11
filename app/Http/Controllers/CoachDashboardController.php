<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Coach;

class CoachDashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $coach = Coach::where('user_id', $user->id)
                      ->with(['courses.members'])
                      ->first();

        $courses = $coach?->courses ?? collect();
        
        $totalMembers = $courses->sum(fn($c) => $c->members->count());
        
        $nextCourse = $courses->first();

        return view('coach.dashboard', compact(
            'coach',
            'courses',
            'totalMembers',
            'nextCourse'
        ));
    }
    public function courses()
{
    $user  = Auth::user();
    $coach = Coach::where('user_id', $user->id)
                  ->with(['courses.members'])
                  ->first();

    $courses = $coach?->courses ?? collect();

    return view('coach.courses', compact('coach', 'courses'));
}

public function members()
{
    $user  = Auth::user();
    $coach = Coach::where('user_id', $user->id)
                  ->with(['courses.members'])
                  ->first();

    $courses = $coach?->courses ?? collect();
    
    $members = $courses->flatMap(fn($c) => $c->members->map(fn($m) => [
        'name'   => $m->name,
        'phone'  => $m->phone,
        'course' => $c->name,
    ]));

    return view('coach.members', compact('coach', 'members'));
}
}