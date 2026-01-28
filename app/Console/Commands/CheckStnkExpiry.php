<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Unit;
use Carbon\Carbon;

class CheckStnkExpiring extends Command
{
    protected $signature = 'stnk:check';
    protected $description = 'Cek unit yang STNK akan habis dalam 7 hari';

    public function handle()
    {
        $today = Carbon::today();
        $weekAhead = $today->copy()->addDays(7);

        $unitsExpiring = Unit::whereBetween('stnk_expired_at', [$today, $weekAhead])->get();

        foreach($unitsExpiring as $unit){
            $this->info("Unit {$unit->nama_unit} STNK-nya akan habis pada {$unit->stnk_expired_at}");
        }
    }
}
