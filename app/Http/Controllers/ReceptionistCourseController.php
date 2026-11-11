<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Member;
use Illuminate\Http\Request;

class ReceptionistCourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('coach.user', 'members')
            ->withCount('members')
            ->latest()
            ->get();

        $members = Member::all();

        return view('receptionist.courses', compact('courses', 'members'));
    }

    public function enroll(Request $request, Course $course)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        // ✅ منع enrollment جديد إلا كان الكوتش معطل
        if ($course->coach?->user?->status === 'deactivated') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'inscrire un membre : le coach de ce cours est désactivé.',
            ], 422);
        }

        // ✅ جديد — منع enrollment إلا كان الكورس وصل للحد الأقصى
        if ($course->is_full) {
            return response()->json([
                'success' => false,
                'message' => __('messages.course_full'),
            ], 422);
        }

        // تتأكد ماعندوش enrollment مكررة
        if ($course->members()->where('member_id', $request->member_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Member already enrolled in this course!',
            ], 422);
        }

        $course->members()->attach($request->member_id);

        return response()->json([
            'success' => true,
            'message' => 'Member enrolled successfully!',
        ]);
    }

    public function unenroll(Course $course, Member $member)
    {
        $course->members()->detach($member->id);

        return response()->json([
            'success' => true,
            'message' => 'Member unenrolled successfully!',
        ]);
    }
}
