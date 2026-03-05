<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\AttendanceMachine\AttendanceMachineService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncEmployeeToMachine implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employee;
    protected $deviceSn;
    protected $attendanceMachineService;

    /**
     * Create a new job instance.
     */
    public function __construct(User $employee, string $deviceSn)
    {
        $this->employee = $employee;
        $this->deviceSn = $deviceSn;
    }

    /**
     * Execute the job.
     */
    public function handle(AttendanceMachineService $attendanceMachineService)
    {
        $result = $attendanceMachineService->addEmployee(
            $this->deviceSn,
            $this->employee
        );

        // sleep(4);

        if ($result['synced']) {
            $this->employee->update(['record_sync' => 'true']);
        }
    }
}
