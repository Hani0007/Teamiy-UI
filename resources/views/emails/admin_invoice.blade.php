<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Subscription Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 30px;
            color: #333;
        }

        .invoice-container {
            background-color: #fff;
            max-width: 650px;
            margin: 0 auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0d6efd;
            color: white;
            padding: 20px 30px;
        }

        .invoice-header img {
            height: 50px;
        }

        .invoice-header h2 {
            margin: 0;
            font-size: 20px;
        }

        .invoice-body {
            padding: 30px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eaeaea;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f8f9fa;
        }

        .invoice-total {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
            font-size: 18px;
        }

        .invoice-footer {
            background-color: #f8f9fa;
            padding: 15px 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <img src="https://teamiy.com/wp-content/uploads/2025/08/teamiy-logo.webp" alt="Company Logo">
            <h2>Subscription Invoice</h2>
        </div>

        <div class="invoice-body">
            <p>Dear <strong>{{ $adminName }}</strong>,</p>
            <p>Thank you for subscribing to our plan. Below are your subscription details:</p>

            <table class="invoice-table">
                <tr>
                    <th>Plan Name</th>
                    <td>{{ ucwords($planName) }} Plan</td>
                </tr>
                <tr>
                    <th>Billing Cycle</th>
                    <td>{{ ucwords($cycle) }}</td>
                </tr>
                <tr>
                    <th>Total Employees</th>
                    <td>{{ $employees }}</td>
                </tr>
                <tr>
                    <th>Amount Paid</th>
                    <td>{{ $currency }}{{ $amount }}</td>
                </tr>
            </table>

            <p class="invoice-total">Total: {{ $currency }}{{ $amount }}</p>
        </div>

        <div class="invoice-footer">
            <p>Thank you for your purchase! <br> For support, contact us at <a
                    href="mailto:support@teamiy.com">support@teamiy.com</a></p>
        </div>
    </div>
</body>

</html>
