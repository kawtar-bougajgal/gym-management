<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminReceptionistController extends Controller
{
    // عرض كل الـ Receptionists
    public function index()
    {
        $receptionists = User::role('receptionist')->latest()->get();
        return view('admin.receptionists.index', compact('receptionists'));
    }

    // حفظ Receptionist جديد — JSON لـ React
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('receptionist');

        return response()->json(['success' => true, 'user' => $user]);
    }

    // حفظ التعديلات — JSON لـ React
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json(['success' => true, 'user' => $user]);
    }

    // ✅ دابا "Delete" كيعطل/كيفعل الحساب بدل ما يمسحو نهائيا — نفس المنطق ديال Coaches
    public function destroy(User $user)
    {
        $newStatus = $user->status === 'active' ? 'deactivated' : 'active';
        $user->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'status'  => $newStatus,
            'user'    => $user,
        ]);
    }

    // ✅ جديد — Admin كيعطي password جديدة مؤقتة، الموظف كيبدلها بعد من Profile ديالو
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);
    }
}
