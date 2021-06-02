<?php

namespace App\Jobs;

use App\Models\SocialLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class StoreSocialLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $social_id;
    protected $action;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $social_id, $action)
    {
        $this->user_id = $user_id;
        $this->social_id = $social_id;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $social_log = new SocialLog();
        $social_log->social_id = $this->social_id;
        $social_log->user_id = $this->user_id;
        $social_log->action = $this->action;
        $social_log->save();
    }
}
