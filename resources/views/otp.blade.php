<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f8fc;
            font-family: -apple-system, BlinkMacSystemFont,
                'Segoe UI', Roboto, Arial, sans-serif;
            color: #1f2937;
        }

        .wrapper {
            width: 100%;
            padding: 40px 0;
        }

        .card {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
            border-top: 4px solid #2563eb;
        }

        h1 {
            font-size: 22px;
            font-weight: 600;
            letter-spacing: -0.3px;
            margin: 0 0 16px;
            color: #2563eb;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
            margin: 0 0 14px;
            font-weight: 400;
        }

        .password {
            margin: 24px 0;
            padding: 14px;
            background-color: #f0f5ff;
            border-radius: 6px;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: #1e40af;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 22px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
        }

        .note {
            margin-top: 24px;
            font-size: 13px;
            color: #6b7280;
        }

        .footer {
            margin-top: 28px;
            font-size: 13px;
            color: #9ca3af;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="card">
            <h1>{{ config('app.company_name') }}</h1>

            <p>Hi {{ $name }},</p>

            <p>
                Use the following One-Time Password (OTP) to {{ $usage }}.
            </p>

            <div class="password">
                {{ $otp_code }}
            </div>

            <p>
                This OTP is valid for 10 minutes. Please do not share it with anyone.
            </p>

            <p class="note">
                If you did not request this, please ignore this email or contact support.
            </p>

            <div class="footer">
                Regards,<br>
                Goodlife IT Department Team
            </div>
        </div>
    </div>
</body>

</html>
