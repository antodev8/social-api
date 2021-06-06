<?php

namespace App\Console\Commands;

use App\Jobs\StoreSocialLogJob;
use App\Models\SocialLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class StoreSocialLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:store-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store social log';

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
        StoreSocialLogJob::dispatchAfterResponse(1, 1, SocialLog::ACTION_CREATE);
        return 0;
    }
}
