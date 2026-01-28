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
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pagination bootstrap
        Paginator::useBootstrap();

        // SHARE NOTIF KE SEMUA VIEW
        View::composer('*', function ($view) {

            // 1️⃣ Notif dari AdminNotification (hanya yang belum dibaca)
            $adminNotifsQuery = AdminNotification::where('is_read', 0)->orderBy('created_at', 'desc');
            $adminNotifCount = $adminNotifsQuery->count();
            $adminNotifs = $adminNotifsQuery->take(5)->get()->map(function($notif) {
                return (object)[
                    'id'         => $notif->id,
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
                                      'id'         => null, // bukan dari DB notif, jadi ga dihitung badge
                                      'message'    => "STNK Unit {$unit->nama_unit} akan habis pada {$unit->stnk_expired_at->format('d/m/Y')}",
                                      'data_id'    => $unit->id,
                                      'type'       => 'unit',
                                      'created_at' => $unit->updated_at,
                                  ];
                              });

            // 3️⃣ Gabung kedua notif, urutkan berdasarkan created_at
            $allNotifs = $adminNotifs->concat($stnkNotifs)->sortByDesc('created_at')->take(5);

            $view->with([
                'adminNotifCount' => $adminNotifCount, // badge = notif DB belum dibaca
                'adminNotifs'     => $allNotifs,
            ]);
        });
    }
}
