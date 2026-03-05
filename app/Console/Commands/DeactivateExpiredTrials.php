<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredTrials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trials:deactivate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate expired trial admins and their users daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $expiredAdmins = Admin::whereNull('parent_id')
            ->where('is_active', 1)
            ->whereDate('trial_expiry', '<', $today)
            ->get(['id']);

        if ($expiredAdmins->isEmpty()) {
            Log::info('No expired trials found today.');
            return Command::SUCCESS;
        }

        foreach ($expiredAdmins as $expiredAdmin) {
            try {
                $childAdminIds = Admin::where('is_active', 1)
                    ->where(function ($query) use ($expiredAdmin) {
                        $query->where('parent_id', $expiredAdmin->id)
                            ->orWhereIn('parent_id', function ($q) use ($expiredAdmin) {
                                $q->select('id')->from('admins')->where('parent_id', $expiredAdmin->id);
                            });
                    })
                    ->pluck('id');

                if ($childAdminIds->isEmpty()) {
                    Log::info("No sub-admins found for expired admin ID {$expiredAdmin->id}.");
                    continue;
                }

                Admin::whereIn('id', $childAdminIds)->update(['is_active' => 0]);
                User::whereIn('admin_id', $childAdminIds)->update(['is_active' => 0]);

                Log::info("Deactivated sub-admins and users under expired admin ID {$expiredAdmin->id}.");
            } catch (\Exception $e) {
                Log::error("Failed to deactivate records for expired admin ID {$expiredAdmin->id}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
