<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PosWorker;

class StaffCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staff:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        PosWorker::where('worker_status',1)
                    ->update(['worker_checkin' => 0,
                                'worker_turn' => 0]);
        $this->info('Command run successfly!');
    }
}
