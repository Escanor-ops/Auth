<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body>
    <h1>Email Verification</h1>
    <p>{{!! $body !!}}</p>
    <p>Hello {{ $fromName }},</p>
    <p>Welcome to our community! To complete your registration, please verify your email address by clicking the button below:</p>
    <p>
        <a href="{{ $actionLink }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px;">Verify Email</a>
    </p>
    <p>If you didn't sign up for our app, you can safely ignore this email.</p>
    <p>Best regards,<br>Your App Team</p>
</body>
</html>
