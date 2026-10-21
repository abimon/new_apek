<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Your portfolio account</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1e293b; line-height: 1.6;">
    <h1 style="color: #2c3e50;">Your portfolio account is ready</h1>
    <p>Hello {{ $user->name }},</p>
    <p>An ApekTech portfolio account has been created for you. Sign in with the details below. For security, you will be required to replace this temporary password before accessing the system.</p>
    <p><strong>Email:</strong> {{ $user->email }}<br><strong>Temporary password:</strong> {{ $temporaryPassword }}</p>
    <p><a href="{{ $loginUrl }}" style="background: #2c3e50; color: #ffffff; display: inline-block; padding: 12px 18px; text-decoration: none;">Sign in</a></p>
    <p>Do not forward this email. If you did not expect this account, contact the system administrator.</p>
</body>
</html>
