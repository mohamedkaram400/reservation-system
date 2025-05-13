<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Mail\EmailReminder;
use App\Models\Reservation;
use Illuminate\Console\Command;
use App\Enums\ReservationStatus;
use Illuminate\Support\Facades\Auth;
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
        $utcNow = Carbon::now('UTC');

        $reservations = Reservation::with(['service', 'user'])
            ->whereBetween('reservation_date', [$utcNow, $utcNow->copy()->addHour()])
            ->where('status', '!=', ReservationStatus::Canceled)
            ->get();


        foreach ($reservations as $reservation) {
            Mail::to($reservation->user->email)->send(new EmailReminder($reservation));
        }
    }
}
