<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Login Credentials</title>
</head>
<body>
    <p>Hello {{ $employee->name }},</p>

    <p>Your account has been created. Here are your login details:</p>

    <p><strong>Email:</strong> {{ $employee->email }}</p>
    <p><strong>Username:</strong> {{ $employee->username }}</p>
    <p><strong>Password:</strong> {{ $rawPassword }}</p>

    <p>Please change your password after first login for security.</p>

    <p>Regards,<br>Company HR Team</p>
</body>
</html>
