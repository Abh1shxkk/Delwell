<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - DelWell</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            margin: 0;
            padding: 20px 0;
            background: linear-gradient(135deg, #F9F7F3 0%, #f5f3ef 50%, #F9F7F3 100%);
            background-attachment: fixed;
            color: #3A3A3A;
            min-height: 100vh;
        }
        
        @media (min-width: 768px) {
            body {
                background-image: 
                    radial-gradient(circle at 25% 25%, rgba(163, 177, 138, 0.03) 0%, transparent 50%),
                    radial-gradient(circle at 75% 75%, rgba(255, 192, 159, 0.03) 0%, transparent 50%),
                    linear-gradient(135deg, #F9F7F3 0%, #f5f3ef 50%, #F9F7F3 100%);
            }
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant Garamond', serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(163, 177, 138, 0.2);
        }
        .header {
            background: linear-gradient(135deg, #A3B18A, #FFC09F);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            font-family: 'Cormorant Garamond', serif;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #3A3A3A;
            font-size: 28px;
            margin-bottom: 20px;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
        }
        .content p {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .reset-btn {
            display: inline-block;
            background: #FFC09F;
            color: white !important;
            text-decoration: none !important;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 192, 159, 0.3);
        }
        .reset-btn:hover {
            background: rgba(255, 192, 159, 0.9);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 192, 159, 0.4);
        }
        .reset-btn:visited {
            color: white !important;
        }
        .reset-btn:active {
            color: white !important;
        }
        .footer {
            background-color: rgba(163, 177, 138, 0.1);
            padding: 30px;
            text-align: center;
            border-top: 1px solid rgba(163, 177, 138, 0.2);
        }
        .footer p {
            color: #3A3A3A;
            font-size: 14px;
            margin: 5px 0;
        }
        .brand {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
        }
        .warning-box {
            background: rgba(255, 192, 159, 0.1);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #FFC09F;
            margin: 25px 0;
        }
        .info-box {
            background: rgba(163, 177, 138, 0.05);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #A3B18A;
            margin: 25px 0;
        }
        
        @media (max-width: 600px) {
            .container {
                margin: 0 10px;
                border-radius: 12px;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .header h1 {
                font-size: 2rem;
            }
            .content h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="brand">DelWell™</h1>
            <p>Conscious Connections</p>
        </div>
        
        <div class="content">
            <h2>Reset Your Password 🔐</h2>
            
            <p>Hi {{ $user->name }},</p>
            
            <p>We received a request to reset your DelWell password. If you made this request, click the button below to create a new password:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}" class="reset-btn" style="color: white !important; text-decoration: none !important;">
                    Reset My Password
                </a>
            </div>
            
            <div class="warning-box">
                <p style="margin: 0; font-size: 14px; color: #3A3A3A;"><strong>⏰ Important Security Information</strong></p>
                <p style="margin: 8px 0 0 0; font-size: 13px; color: #6b7280;">This password reset link will expire in <strong>24 hours</strong> for your security. If you didn't request this password reset, you can safely ignore this email - your account remains secure.</p>
            </div>
            
            <div class="info-box">
                <p style="margin: 0; font-size: 14px; color: #3A3A3A;"><strong>🔗 Link Not Working?</strong></p>
                <p style="margin: 8px 0 0 0; font-size: 13px; color: #6b7280;">Copy and paste this URL into your browser:</p>
                <p style="margin: 8px 0 0 0; font-size: 12px; color: #A3B18A; word-break: break-all; font-family: monospace; background: white; padding: 8px; border-radius: 6px;">{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}</p>
            </div>
            
            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">If you're having trouble with your account or didn't request this password reset, please contact our support team immediately.</p>
        </div>
        
        <div class="footer">
            <p><strong class="brand">DelWell™ - Conscious Connections</strong></p>
            <p>Questions? Reply to this email or visit our help center.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">&copy; 2025 DelWell™. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
