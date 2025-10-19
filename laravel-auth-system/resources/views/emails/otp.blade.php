<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .otp-code {
            background-color: #f8f9fa;
            border: 2px dashed #007bff;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
        }
        .otp-number {
            font-size: 36px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 5px;
            margin: 10px 0;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🔐 Laravel Auth System</div>
            <h2>Email Verification Required</h2>
        </div>

        <p>Hello <strong>{{ $user->name }}</strong>,</p>

        <p>Thank you for registering with our Laravel Auth System. To complete your registration and verify your email address, please use the following One-Time Password (OTP):</p>

        <div class="otp-code">
            <p><strong>Your Verification Code:</strong></p>
            <div class="otp-number">{{ $otp }}</div>
            <p><small>Enter this code on the verification page</small></p>
        </div>

        <div class="warning">
            <strong>⚠️ Important Security Information:</strong>
            <ul>
                <li>This OTP is valid for <strong>10 minutes only</strong></li>
                <li>Do not share this code with anyone</li>
                <li>If you didn't request this verification, please ignore this email</li>
                <li>For security reasons, this code can only be used once</li>
            </ul>
        </div>

        <p>If you're having trouble with the verification process, you can request a new OTP code from the verification page.</p>

        <p>Best regards,<br>
        <strong>Laravel Auth System Team</strong></p>

        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>© {{ date('Y') }} Laravel Auth System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>