<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Mail\EmailReminder;
use App\Models\Reservation;
use Illuminate\Console\Command;
use App\Enums\ReservationStatus;
use Illuminate\Support\Facades\Mail;

class EmailReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:email-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'email reminder sent successfully';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $afterHour = Carbon::now()->subHour();
        $reservations = Reservation::with(['service', 'user'])->whereDate('reservation_date', '=', $afterHour) 
        ->where('status', '!=', ReservationStatus::Canceled)
        ->get();

        foreach ($reservations as $reservation) {
            try {
                $userEmail = $reservation->user ? $reservation->user->email : null;
                Mail::to($userEmail)->send(new EmailReminder($reservation));
            } catch (\Exception $e) {
                logger('Email failed: ' . $e->getMessage());
            }
        }
    }
}
