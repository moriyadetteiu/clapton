<?php

namespace App\Console\Commands;

use App\Models\EventDate;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class UpdateEventIsProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:event:is-progress {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update to is progress column in events table';

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
        $this->fetchTargetEvents()->each(fn ($event) => $event->updateIsProgress());

        return 0;
    }

    private function fetchTargetEvents(): Collection
    {
        return EventDate::with('event')
            ->when(
                !$this->option('all'),
                fn ($builder) => $builder->whereBetween('date', [now()->subDay(), now()->addDay()])
            )
            ->get()
            ->unique('event_id')
            ->map(fn ($eventDate) => $eventDate->event);
    }
}
