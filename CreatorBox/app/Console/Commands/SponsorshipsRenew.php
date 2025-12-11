<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sponsorship;
use Carbon\Carbon;

class SponsorshipsRenew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sponsorships-renew';

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
        // Carbon::setTestNow(Carbon::create(2026, 1, 31, 23, 59));   // for test

        $renewedCount = 0;
        $failedRenewals = [];

        $sponsorshipsToRenew = Sponsorship::where('status', 'active')
            ->whereDate('expires_at', '<=', now())
            ->with(['supporter', 'plan', 'creator'])
            ->get();

        foreach ($sponsorshipsToRenew as $sponsorship) {
            if (!$sponsorship->plan->is_active) {
                $sponsorship->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now()
                ]);
                $failedRenewals[] = $sponsorship->id;
                continue;
            }
    
            if ($sponsorship->supporter->coin_balance >= $sponsorship->monthly_amount) {
                $sponsorship->supporter->decrement('coin_balance', $sponsorship->monthly_amount);
                $sponsorship->creator->increment('coin_balance', $sponsorship->monthly_amount);

                $sponsorship->update([
                    'expires_at' => now()->addMonth()->endOfMonth()
                ]);

                $renewedCount++;
            } else {
                $sponsorship->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now()
                ]);
                $failedRenewals[] = $sponsorship->id;
            }
        }

        Sponsorship::where('status', 'cancelled')
            ->where('expires_at', '<=', now())
            ->delete();

        $this->info("Renewed: {$renewedCount}, Failed: " . count($failedRenewals));
    }
}
