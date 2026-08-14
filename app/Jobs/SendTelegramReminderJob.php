<?php

namespace App\Jobs;

use App\Models\chemical___move;
use App\Models\database___chanel_telegram;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class SendTelegramReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maksimal retry jika gagal.
     */
    public int $tries = 3;

    /**
     * Jeda retry (detik).
     */
    public int $backoff = 60;

    /**
     * Channel Telegram.
     */

    public function __construct(
        public int $chemicalId
    ) {
    }

    public function handle(TelegramService $telegramService): void
    {
        $chemical = chemical___move::find($this->chemicalId);

        if (!$chemical) {
            Log::warning("Reminder gagal: Chemical {$this->chemicalId} tidak ditemukan.");
            return;
        }

        // Sudah selesai digunakan
        if ($chemical->status === 'Done') {
            Log::info("Reminder dibatalkan: Chemical {$chemical->id} sudah Done.");
            return;
        }

        // Sudah pernah dikirim
        if ($chemical->reminder_sent_at) {
            Log::info("Reminder dibatalkan: Chemical {$chemical->id} sudah pernah dikirim.");
            return;
        }

        // Sudah expired
        if ($chemical->expired_at->isPast()) {
            Log::info("Reminder dibatalkan: Chemical {$chemical->id} sudah expired.");
            return;
        }

        $message = sprintf(
            "⚠️ POTLIFE WARNING\n\n".
            "Line : %s\n".
            "Chemical : %s\n".
            "Lot Number : %s\n\n".
            "Potlife akan EXPIRED dalam waktu 20 menit lagi.\n".
            "Segera habiskan sisa Chemical di teko.",
            $chemical->line,
            $chemical->code_chemical,
            $chemical->lot_number
        );
        $text = $chemical->line;
        if (str_contains($text, 'LINE')) {
            $area = 'POTLIFE STOCKFIT PWI2';
        } elseif (str_contains($text, 'B1')) {
            $area = 'POTLIFE B1 PWI2';
        } elseif (str_contains($text, 'B2')) {
            $area = 'POTLIFE B2 PWI2';
        } else {
            $area = null; // atau default lainnya
        }

       $id_chanel = Cache::remember(
            'telegram_channel_'.$area,
            now()->addDays(30),
            function () use ($area) {
                return database___chanel_telegram::where('system', $area)->first();
            }
        );

            Log::info(json_encode([
                'text' => $text,
                'area' => $area,
                'id_chanel' => $id_chanel,
            ], JSON_PRETTY_PRINT));
        // Jika method ini throw Exception,
        // Laravel otomatis retry sesuai $tries dan $backoff
        $response = $telegramService->sendMessage(
            $id_chanel->id_chanel,
            $message
        );
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        $messageId = data_get($response, 'result.message_id');

        Log::info('Message ID = '.$messageId);

        $chemical->update([
            'message_id' => $messageId,
            'reminder_sent_at' => now(),
        ]);

        Log::info("Reminder berhasil dikirim. Chemical ID: {$chemical->id}");
    }

    /**
     * Dipanggil jika semua retry gagal.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error(
            "Reminder Chemical {$this->chemicalId} gagal setelah {$this->tries} percobaan. Error: {$exception->getMessage()}"
        );
    }
}
