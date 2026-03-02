<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed Successfully - DelWell</title>
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
        .success-icon {
            text-align: center;
            margin: 30px 0;
            font-size: 48px;
        }
        .login-btn {
            display: inline-block;
            background: #A3B18A;
            color: white !important;
            text-decoration: none !important;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(163, 177, 138, 0.3);
        }
        .login-btn:hover {
            background: rgba(163, 177, 138, 0.9);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(163, 177, 138, 0.4);
        }
        .login-btn:visited {
            color: white !important;
        }
        .login-btn:active {
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
        .security-box {
            background: rgba(163, 177, 138, 0.05);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #A3B18A;
            margin: 25px 0;
        }
        .warning-box {
            background: rgba(255, 192, 159, 0.1);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #FFC09F;
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
            <div class="success-icon">✅</div>
            
            <h2>Password Changed Successfully!</h2>
            
            <p>Hi {{ $user->name }},</p>
            
            <p>Great news! Your DelWell password has been successfully changed. Your account is now secured with your new password.</p>
            
            <div class="security-box">
                <p style="margin: 0; font-size: 14px; color: #3A3A3A;"><strong>🔐 Security Confirmation</strong></p>
                <p style="margin: 8px 0 0 0; font-size: 13px; color: #6b7280;">
                    <strong>Changed on:</strong> {{ now()->format('F j, Y \a\t g:i A T') }}<br>
                    <strong>Account:</strong> {{ $user->email }}
                </p>
            </div>
            
            <p>You can now log in to your DelWell account using your new password and continue your journey to find meaningful connections.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('login') }}" class="login-btn" style="color: white !important; text-decoration: none !important;">
                    Log In to DelWell
                </a>
            </div>
            
            <div class="warning-box">
                <p style="margin: 0; font-size: 14px; color: #3A3A3A;"><strong>⚠️ Didn't Change Your Password?</strong></p>
                <p style="margin: 8px 0 0 0; font-size: 13px; color: #6b7280;">If you didn't make this change, please contact our support team immediately. Your account security is our top priority.</p>
            </div>
            
            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                <strong>Security Tips:</strong><br>
                • Use a unique password that you don't use elsewhere<br>
                • Keep your password confidential<br>
                • Log out from shared devices<br>
                • Contact us if you notice any suspicious activity
            </p>
        </div>
        
        <div class="footer">
            <p><strong class="brand">DelWell™ - Conscious Connections</strong></p>
            <p>Questions? Reply to this email or visit our help center.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">&copy; 2025 DelWell™. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
