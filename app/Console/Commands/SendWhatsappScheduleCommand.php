<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\whatsapp_schedules_produksi;
use App\Models\WhatsappScheduleLog; // Sesuaikan nama model log Anda
use App\Services\WhatsappScheduleService;
use App\Services\HolidayService;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DB;

class SendWhatsappScheduleCommand extends Command
{
    protected $signature = 'wa:send-scheduler';
    protected $description = 'Kirim Whatsapp sesuai schedule';

    public function handle(
        WhatsappScheduleService $service,
        HolidayService $holiday
    ) {
        if ($holiday->isHoliday()) {
            $this->info("Hari libur");
            return true;
        }
        DB::table('whatsapp_schedules_produksi')
            ->whereNotNull('last_run_at')
            ->whereDate('last_run_at', '<', today())
            ->update([
                'retry_count' => 0,
                'last_run_at' => null,
            ]);
        $now = now()->format('H:i');
        // $now = '15:00';
        $schedules = whatsapp_schedules_produksi::with('group')
                    ->where(function ($q) use ($now) {

                            // Jadwal normal
                            $q->where('send_time', $now)

                            // Jadwal retry
                            ->orWhere(function ($retry) {

                                $retry->where('retry_count', '>', 0)
                                    ->whereNotNull('next_retry_at')
                                    ->where('next_retry_at', '<=', now());

                            });

                        })
                    ->get();
        $this->info("Jumlah schedule : ".$schedules->count());
        $service = app(WhatsappScheduleService::class);

        foreach ($schedules as $schedule) {
            try {

                $send = $service->process($schedule);
                if (!($send['wa']['success'] ?? false)) {
                    throw new \Exception($send['wa']['error'] ?? 'WhatsApp Gateway Error');
                }
                $this->info("Sukses : {$schedule->name}");
                DB::table('whatsapp_schedules_produksi')
                        ->where('id', $schedule->id)
                        ->update([
                            'last_run_at'   => now(),
                            'retry_count'   => 0,
                            'next_retry_at' => null,
                        ]);

            } catch (\Throwable $e) {

                $this->error("Gagal : {$schedule->name}");
                $this->error($e->getMessage());

                DB::table('whatsapp_schedules_produksi')
                    ->where('id', $schedule->id)
                    ->update([
                        'last_run_at'   => now(),
                        'retry_count'   => DB::raw('retry_count + 1'),
                        'next_retry_at' => now()->addMinutes(5),
                    ]);

            }

        }



    }
}
