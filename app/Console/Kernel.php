<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        // Dapatkan waktu saat ini
        $now = now();

        // Dapatkan semua reservasi yang statusnya 'unpaid' dan yang dibuat lebih dari 48 jam yang lalu
        $reservations = Reservation::where('status', 'unpaid')
            ->where('created_at', '<', $now->subHours(48))
            ->get();

        // Ubah status reservasi menjadi 'cancelled'
        foreach ($reservations as $reservation) {
            $reservation->status = 'cancelled';
            $reservation->save();
        }
    })->hourly(); // Jalankan tugas ini setiap jam
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    
}
