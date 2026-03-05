<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->employee->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        header img {
            width: 120px;
            margin-bottom: 5px;
        }
        h2 {
            margin: 0;
            font-size: 22px;
        }
        p {
            margin: 2px 0;
            font-size: 13px;
        }
        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            margin: 25px 0 10px;
            font-size: 16px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 14px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .net-salary {
            text-align: center;
            margin-top: 30px;
            padding: 10px;
            background: #f4f4f4;
            border: 1px solid #ccc;
            font-weight: bold;
            font-size: 16px;
        }
        footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

    {{-- Company Header --}}
    <header>
        @if(!empty($payroll->employee->company->logo))
            <img src="{{ public_path('uploads/company/'. $payroll->employee->company->logo) }}" alt="Company Logo">
        @endif
        <h2>{{ $payroll->employee->company->name ?? 'Company Name' }}</h2>
        <p>{{ $payroll->employee->company->address ?? '' }}</p>
    </header>

    {{-- Employee Information --}}
    <div class="section-title">Employee Details</div>
    <table>
        <tr>
            <th>Employee Name</th><td>{{ $payroll->employee->name }}</td>
            <th>Employee Code</th><td>{{ $payroll->employee->employee_code }}</td>
        </tr>
        <tr>
            <th>Department</th><td>{{ $payroll->employee->department->dept_name ?? 'N/A' }}</td>
            <th>Joining Date</th><td>{{ $payroll->employee->joining_date }}</td>
        </tr>
        <tr>
            <th>Payroll Type</th><td>{{ ucfirst($payroll->payroll_type) }}</td>
            <th>Payment Type</th><td>{{ ucfirst($payroll->payment_type) }}</td>
        </tr>
        <tr>
            <th>Period</th>
            <td colspan="3">{{ $payroll->range['start'] ?? '' }} to {{ $payroll->range['end'] ?? '' }}</td>
        </tr>
    </table>

    {{-- Earnings Section --}}
    <div class="section-title">Earnings</div>
    <table>
        <tr>
            <th>Description</th>
            <th class="text-right">Amount ({{ $currencySymbol }})</th>
        </tr>
        <tr><td>Base Salary</td><td class="text-right">{{ number_format($payroll->base_salary, 2) }}</td></tr>
        <tr><td>Overtime Pay</td><td class="text-right">{{ number_format($payroll->overtime_pay, 2) }}</td></tr>
        <tr><td>Travel Allowance (TADA)</td><td class="text-right">{{ number_format($payroll->tada_amount, 2) }}</td></tr>
        <tr>
            <th>Total Earnings</th>
            <th class="text-right">
                {{ number_format($payroll->base_salary + $payroll->overtime_pay + $payroll->tada_amount, 2) }}
            </th>
        </tr>
    </table>

    {{-- Deductions Section --}}
    <div class="section-title">Deductions</div>
    <table>
        <tr>
            <th>Description</th>
            <th class="text-right">Amount ({{ $currencySymbol }})</th>
        </tr>
        <tr><td>Tax</td><td class="text-right">{{ number_format($payroll->tax, 2) }}</td></tr>
        <tr><td>Unpaid Leave Deduction</td><td class="text-right">{{ number_format($payroll->unpaid_leave_deduction, 2) }}</td></tr>
        <tr><td>Undertime Deduction</td><td class="text-right">{{ number_format($payroll->undertime_deduction, 2) }}</td></tr>
        <tr>
            <th>Total Deductions</th>
            <th class="text-right">
                {{ number_format($payroll->tax + $payroll->unpaid_leave_deduction + $payroll->undertime_deduction, 2) }}
            </th>
        </tr>
    </table>

    {{-- Net Salary --}}
    <div class="net-salary">
        Net Salary: {{ $currencySymbol }} {{ number_format($payroll->net_salary, 2) }}
    </div>

    <footer>
        This is a system-generated payslip. No signature is required.
    </footer>

</body>
</html>
