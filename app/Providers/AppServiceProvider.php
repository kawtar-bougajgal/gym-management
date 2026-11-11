<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SubscriptionPlan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // كل مرة x-receptionist-sidebar غادي تترندر (يعني فـ كل
        // صفحة ديال الريسبسيونيست، بلا استثناء)، $plans غادي تكون
        // موجودة تلقائيا فـ الـ view — بلا ما نحتاجو نزيدو
        // compact('plans') فـ كل controller بوحدو.
        //
        // هادشي هو اللي كيسمح لـ #add-member-modal-global (مثبت
        // جوا السايدبار) يعرض الخطط الصحيحة فـ أي صفحة كنتي فيها:
        // Dashboard, Subscriptions, Payments, Courses, Profile...
        View::composer('components.receptionist-sidebar', function ($view) {
            $view->with('plans', SubscriptionPlan::select(
                'id', 'name', 'price', 'inscription_fee', 'duration'
            )->get());
        });
    }
}