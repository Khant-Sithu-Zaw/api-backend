<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class CleanExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:clean';
    protected $description = 'Delete all expired Sanctum tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now('Asia/Yangon');

        $expiredTokens = PersonalAccessToken::where('expires_at', '<', $now)->get();
        $count = $expiredTokens->count();

        if ($count > 0) {
            PersonalAccessToken::where('expires_at', '<', $now)->delete();
            $this->info("✅ $count expired token(s) deleted successfully.");
        } else {
            $this->info("✅ No expired tokens found.");
        }
    }
}
