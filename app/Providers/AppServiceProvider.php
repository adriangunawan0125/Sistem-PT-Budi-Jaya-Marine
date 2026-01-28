<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\Unit;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

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
        // Pagination bootstrap
        Paginator::useBootstrap();

        // SHARE NOTIF KE SEMUA VIEW
        View::composer('*', function ($view) {

            // 1️⃣ Notif dari AdminNotification
            $adminNotifCount = AdminNotification::where('is_read', 0)->count();
            $adminNotifs = AdminNotification::orderBy('created_at', 'desc')
                                ->take(5)
                                ->get()
                                ->map(function($notif) {
                                    return (object)[
                                        'message'    => $notif->message,
                                        'data_id'    => $notif->data_id,
                                        'type'       => $notif->type,
                                        'created_at' => $notif->created_at,
                                    ];
                                });

            // 2️⃣ Tambah notif STNK hampir habis dari Unit
            $today = Carbon::today();
            $weekAhead = $today->copy()->addDays(7);

            $stnkNotifs = Unit::whereBetween('stnk_expired_at', [$today, $weekAhead])
                              ->orderBy('stnk_expired_at', 'asc')
                              ->get()
                              ->map(function($unit) {
                                  return (object)[
                                      'message'    => "STNK Unit {$unit->nama_unit} akan habis pada {$unit->stnk_expired_at->format('d/m/Y')}",
                                      'data_id'    => $unit->id,
                                      'type'       => 'unit',
                                      'created_at' => $unit->updated_at,
                                  ];
                              });

            // 3️⃣ Gabung kedua notif
            $allNotifs = $adminNotifs->concat($stnkNotifs)->sortByDesc('created_at')->take(5);
            $allNotifCount = $allNotifs->count();

            $view->with([
                'adminNotifCount' => $allNotifCount,
                'adminNotifs'     => $allNotifs,
            ]);
        });
    }
}
