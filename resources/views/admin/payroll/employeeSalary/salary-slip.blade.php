<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Salary Slip</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #0056b3; }
        .contact-info { font-size: 0.9em; color: #666; }
        .payslip-title { text-align: center; font-weight: bold; margin: 20px 0; text-decoration: underline; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; vertical-align: top; }
        .section-title { background-color: #f4f4f4; font-weight: bold; }

        .totals-row { font-weight: bold; background-color: #fafafa; }
        .net-salary-box { border: 2px solid #333; padding: 15px; margin-top: 20px; background-color: #f9f9f9; }
        .net-amount { font-size: 1.2em; color: #d9534f; }
    </style>
</head>
<body>

<div class="header">
    <h1>{{ config('app.name') }}</h1>
    <p class="contact-info">
        {{ ucwords($payroll?->employee?->company?->countries?->name) }} |
        Email: {{ $payroll?->employee?->email }}
    </p>
</div>

<div class="payslip-title">
    Payslip for
    @if($payroll->payment_type == 'monthly')
        {{ \App\Helpers\AppHelper::getMonthYear($salary_from) }}
    @else
        {{ \App\Helpers\AttendanceHelper::payslipDate($salary_from) }}
        to
        {{ \App\Helpers\AttendanceHelper::payslipDate($salary_to) }}
    @endif
</div>

<table>
    <tr>
        <td><strong>Name:</strong><br>{{ $payroll?->employee?->name }}</td>
        <td><strong>Department:</strong><br>{{ $payroll?->department?->dept_name }}</td>
    </tr>
    <tr>
        <td><strong>Payment Type:</strong><br>{{ ucfirst($payroll->payment_type) }}</td>
        <td><strong>Status:</strong><br>{{ ucfirst($payroll->status) }}</td>
    </tr>
</table>

<table>
    <tr class="section-title">
        <td colspan="4">Work Summary</td>
    </tr>
    <tr>
        <td><strong>Worked Hours:</strong> {{ $payroll->worked_hours }}</td>
        <td colspan="3"></td>
    </tr>
</table>

<table>
    <thead>
        <tr class="section-title">
            <th>Earnings</th>
            <th>Amount</th>
            <th>Deductions</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Base Salary</td>
            <td>{{ $currency }}{{ number_format($payroll->base_salary, 2) }}</td>
            <td>Tax</td>
            <td>{{ $currency }}{{ number_format($payroll->tax, 2) }}</td>
        </tr>
        <tr class="totals-row">
            <td>Gross Salary</td>
            <td>{{ $currency }}{{ number_format($payroll->base_salary, 2) }}</td>
            <td>Total Deduction</td>
            <td>{{ $currency }}{{ number_format($payroll->tax, 2) }}</td>
        </tr>
    </tbody>
</table>

<div class="net-salary-box">
    <div><strong>Net Salary:</strong>
        <span class="net-amount">
            {{ $currency }}{{ number_format($payroll->net_salary, 2) }}
        </span>
    </div>
</div>

</body>
</html>
