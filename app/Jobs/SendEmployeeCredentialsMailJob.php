<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmployeeCredentialsMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employee;
    protected $password;

    /**
     * Create a new job instance.
     */
    public function __construct(User $employee, string $password)
    {
        $this->employee = $employee;
        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::send('emails.employee_login_credentials', [
            'employeeName' => $this->employee->name,
            'email'        => $this->employee->work_email,
            'password'     => $this->password,
            'companyName'  => $this->employee->company->name ?? '',
        ], function ($message) {
            $message->to($this->employee->work_email)
                ->subject('Your Employee Login Credentials');
        });
    }
}
