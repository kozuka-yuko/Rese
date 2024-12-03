<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderEmail;
use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\ReminderMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to users at 8 AM on the day of their reservation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reservations = Reservation::whereDate('date', Carbon::today())->get();

        foreach ($reservations as $reservation) {
            SendReminderEmail::dispatch($reservation, $reservation->user->email);
        }

        return Command::SUCCESS;
    }
}
