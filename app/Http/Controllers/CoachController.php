<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CoachController extends Controller
{
    // عرض كل الـ Coaches
    public function index()
    {
        $coaches = Coach::with('user')->latest()->get();
        return view('admin.coaches.index', compact('coaches'));
    }

    // حفظ Coach جديد — JSON لـ React
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:8|confirmed',
            'phone'          => 'nullable|string|max:20',
            'specialization' => 'required|string|max:100',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
        ]);

        $user->assignRole('coach');

        $coach = Coach::create([
            'user_id'        => $user->id,
            'specialization' => $request->specialization,
        ]);

        return response()->json([
            'success' => true,
            'coach'   => $coach->load('user'),
        ]);
    }

    // حفظ التعديلات — JSON لـ React
    public function update(Request $request, Coach $coach)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email,' . $coach->user_id,
            'phone'          => 'nullable|string|max:20',
            'specialization' => 'required|string|max:100',
        ]);

        $coach->user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $coach->update([
            'specialization' => $request->specialization,
        ]);

        return response()->json([
            'success' => true,
            'coach'   => $coach->load('user'),
        ]);
    }

    // ✅ تعطيل/تفعيل Coach — بدل الحذف النهائي (بلا رجعة)
    public function destroy(Coach $coach)
    {
        $newStatus = $coach->user->status === 'active' ? 'deactivated' : 'active';
        $coach->user->update(['status' => $newStatus]);

        return response()->json([
            'success'   => true,
            'status'    => $newStatus,
            'coach'     => $coach->load('user'),
        ]);
    }

    // ✅ جديد — Admin كيعطي password جديدة مؤقتة، الموظف كيبدلها بعد من Profile ديالو
    public function resetPassword(Request $request, Coach $coach)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $coach->user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);
    }
}
