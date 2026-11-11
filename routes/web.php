<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\Admin\AdminReceptionistController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\CoachDashboardController;
use App\Http\Controllers\ReceptionistCourseController;
use App\Models\User;

Route::get('/', function () {
    $plans = \App\Models\SubscriptionPlan::all();
    return view('welcome', compact('plans'));
});
// Language Switch
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'en', 'ar'])) {
        session()->forget('locale');  // ← امسح القديمة أولا
        session()->put('locale', $locale);
        session()->save();
    }
    return redirect()->back();
})->name('lang.switch');
Route::middleware(['auth', 'verified'])->group(function () {

    // Redirect حسب الـ role
    Route::get('/dashboard', function () {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('receptionist')) {
            return redirect()->route('receptionist.dashboard');
        } elseif ($user->hasRole('coach')) {
            return redirect()->route('coach.dashboard');
        }
        abort(403);
    })->name('dashboard');

    // ── ADMIN ──────────────────────────────────────────
    Route::middleware(['role:admin'])->group(function () {

        // Dashboard
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');

        // Subscription Plans
        Route::get('/admin/plans',             [SubscriptionPlanController::class, 'index'])->name('admin.plans.index');
        Route::post('/admin/plans',            [SubscriptionPlanController::class, 'store'])->name('admin.plans.store');
        Route::patch('/admin/plans/{plan}',    [SubscriptionPlanController::class, 'update'])->name('admin.plans.update');
        Route::delete('/admin/plans/{plan}',   [SubscriptionPlanController::class, 'destroy'])->name('admin.plans.destroy');

        // Coaches
        Route::get('/admin/coaches',            [CoachController::class, 'index'])->name('admin.coaches.index');
        Route::post('/admin/coaches',           [CoachController::class, 'store'])->name('admin.coaches.store');
        Route::patch('/admin/coaches/{coach}',  [CoachController::class, 'update'])->name('admin.coaches.update');
        Route::delete('/admin/coaches/{coach}', [CoachController::class, 'destroy'])->name('admin.coaches.destroy');
        Route::patch('/admin/coaches/{coach}/reset-password', [CoachController::class, 'resetPassword'])->name('admin.coaches.resetPassword'); // ✅ 

        // Courses
        Route::get('/admin/courses',              [AdminCourseController::class, 'index'])->name('admin.courses.index');
        Route::post('/admin/courses',             [AdminCourseController::class, 'store'])->name('admin.courses.store');
        Route::patch('/admin/courses/{course}',   [AdminCourseController::class, 'update'])->name('admin.courses.update');
        Route::delete('/admin/courses/{course}',  [AdminCourseController::class, 'destroy'])->name('admin.courses.destroy');

        // Receptionists
        Route::get('/admin/receptionists',           [AdminReceptionistController::class, 'index'])->name('admin.receptionists.index');
        Route::post('/admin/receptionists',          [AdminReceptionistController::class, 'store'])->name('admin.receptionists.store');
        Route::patch('/admin/receptionists/{user}',  [AdminReceptionistController::class, 'update'])->name('admin.receptionists.update');
        Route::delete('/admin/receptionists/{user}', [AdminReceptionistController::class, 'destroy'])->name('admin.receptionists.destroy');
        Route::patch('/admin/receptionists/{user}/reset-password', [AdminReceptionistController::class, 'resetPassword'])->name('admin.receptionists.resetPassword'); // ✅ 
    });

    // ── RECEPTIONIST ───────────────────────────────────
    Route::middleware(['role:receptionist'])->group(function () {

        // Dashboard
        Route::get('/receptionist/dashboard', [ReceptionistController::class, 'index'])->name('receptionist.dashboard');

        // Members
        Route::get('/members',               [MemberController::class, 'index'])->name('members.index');

        // 🆕 Trash — خاصها تكون قبل /members/{member} باش Laravel ما يخلطهاش مع show()
        Route::get('/members/trashed',                [MemberController::class, 'trashed'])->name('members.trashed');
        Route::patch('/members/{id}/restore',          [MemberController::class, 'restore'])->name('members.restore');
        Route::delete('/members/{id}/force-delete',    [MemberController::class, 'forceDestroy'])->name('members.forceDelete');

        Route::post('/members',              [MemberController::class, 'store'])->name('members.store');
        Route::get('/members/{member}',      [MemberController::class, 'show'])->name('members.show');

        Route::patch('/members/{member}',    [MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}',   [MemberController::class, 'destroy'])->name('members.destroy');

        // Subscriptions
        // Subscriptions
        Route::get('/subscriptions',        [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('/subscriptions',       [SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

        // Payments
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        // Courses
        Route::get('/courses', [ReceptionistCourseController::class, 'index'])->name('courses.index');
        // Course Enrollment
        Route::post('/courses/{course}/enroll', [ReceptionistCourseController::class, 'enroll'])->name('courses.enroll');
        Route::delete('/courses/{course}/unenroll/{member}', [ReceptionistCourseController::class, 'unenroll'])->name('courses.unenroll');
    });

    // ── COACH ──────────────────────────────────────────
    Route::middleware(['role:coach'])->group(function () {
        Route::get('/coach/dashboard', [CoachDashboardController::class, 'index'])->name('coach.dashboard');
        Route::get('/coach/courses', [CoachDashboardController::class, 'courses'])->name('coach.courses');
        Route::get('/coach/members', [CoachDashboardController::class, 'members'])->name('coach.members');
    });
    // ── PROFILE ────────────────────────────────────────
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
