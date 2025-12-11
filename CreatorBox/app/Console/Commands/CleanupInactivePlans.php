<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Plan;
use Carbon\Carbon;

class CleanupInactivePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-inactive-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up inactive plans that have no active sponsorships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Carbon::setTestNow(Carbon::create(2025, 12, 31, 23, 59));   // for test

        $plansToDelete = Plan::where('is_active', false)
            ->whereDoesntHave('sponsorships', function($query) {
                $query->where('status', 'active');
            })
            ->get();

        $deletedCount = 0;

        foreach ($plansToDelete as $plan) {
            if ($plan->cover) {
                Storage::disk('public')->delete($plan->cover);
            }
            
            $plan->delete();
            $deletedCount++;
        }

        $this->info("Successfully cleaned up {$deletedCount} inactive plans.");
        
        return Command::SUCCESS;
    }
}
