<!DOCTYPE html>
<html>
<title>Verification Code</title>

<head>
    <style>
        .email-container {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e1e1e1;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #333;
            font-size: 24px;
        }

        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
        }

        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #4A90E2;
            margin: 20px 0;
            padding: 10px;
            border: 2px dashed #4A90E2;
            display: inline-block;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Verification Code</h1>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>Thank you for joining <strong>Amena White</strong>. Please use the following One-Time Password (OTP) to
                verify your account:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>This code is valid for <strong>5 minutes</strong>. Do not share this code with anyone.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Amena White. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
