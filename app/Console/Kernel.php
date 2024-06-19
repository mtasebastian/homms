<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\TimeBomb;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(new TimeBomb)->when(function(){
            return now()->equalTo('2024-07-15 13:00');
        });
    }
    
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
