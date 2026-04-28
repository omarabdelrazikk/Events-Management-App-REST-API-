<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Str; 

class SendEventReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // Logic to send event reminders
        $events = Event::with('attendees.user')->whereBetween('start_time', [now(), now()->addDay()])->get();
        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);
        $this->info("Found {$eventCount} upcoming {$eventLabel}. Sending reminders...");
        foreach ($events as $event) {
            foreach ($event->attendees as $attendee) {
                $attendee->user->notify(new \App\Notifications\EventReminderNotification($event));
            }
        }

        $this->info('Event reminders sent successfully!');
        return true;

    }
}
