<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Coach;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    // عرض كل الـ Courses
    public function index()
    {
        $courses = Course::with('coach.user', 'members')->withCount('members')->latest()->get();
        $coaches = Coach::with('user')->get();
        return view('admin.courses.index', compact('courses', 'coaches'));
    }

    // حفظ Course جديد — كيرجع JSON لـ React
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'day'          => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time'         => 'required|string',
            'coach_id'     => 'required|exists:coaches,id',
            'max_capacity' => 'nullable|integer|min:1',
        ]);

        $course = Course::create([
            'name'         => $request->name,
            'schedule'     => $request->day . ' ' . $request->time, // للـ backward compatibility
            'day'          => $request->day,
            'time'         => $request->time,
            'coach_id'     => $request->coach_id,
            'max_capacity' => $request->max_capacity,
        ]);

        return response()->json([
            'success' => true,
            'course'  => $course->load('coach.user'),
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'day'          => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time'         => 'required|string',
            'coach_id'     => 'required|exists:coaches,id',
            'max_capacity' => 'nullable|integer|min:1',
        ]);

        $course->update([
            'name'         => $request->name,
            'schedule'     => $request->day . ' ' . $request->time,
            'day'          => $request->day,
            'time'         => $request->time,
            'coach_id'     => $request->coach_id,
            'max_capacity' => $request->max_capacity,
        ]);

        return response()->json([
            'success' => true,
            'course'  => $course->load('coach.user'),
        ]);
    }
    // حذف Course — كيرجع JSON لـ React
    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(['success' => true]);
    }
}
