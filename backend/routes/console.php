<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Commands & Task Scheduler (FRD Rules)
|--------------------------------------------------------------------------
|
| FR-COIN-02: Reset Kuota Koin Awal Bulan (Cron: 0 0 1 * *)
| FR-NOTIF-01: Reminder Jatuh Tempo via WhatsApp (Daily at 08:00)
|
*/

// Reset massal saldo koin kembali ke kuota default awal pada tanggal 1 setiap awal bulan pukul 00:00
Schedule::command('coins:reset-monthly')
    ->monthlyOn(1, '00:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/coins_reset.log'));

// Pengiriman pesan pengingat jatuh tempo H-3, H-1, Hari-H via WhatsApp setiap hari pukul 08:00
Schedule::command('reminders:due-date')
    ->dailyAt('08:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/wa_reminders.log'));
