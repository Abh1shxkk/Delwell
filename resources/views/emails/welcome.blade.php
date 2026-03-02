<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DelWell - Your Journey Begins!</title>
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
        .welcome-icon {
            text-align: center;
            margin: 30px 0;
            font-size: 48px;
        }
        .dashboard-btn {
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
        .dashboard-btn:hover {
            background: rgba(255, 192, 159, 0.9);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 192, 159, 0.4);
        }
        .dashboard-btn:visited {
            color: white !important;
        }
        .dashboard-btn:active {
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
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 30px 0;
        }
        .feature {
            flex: 1;
            min-width: 250px;
            text-align: center;
            padding: 20px;
            background: rgba(163, 177, 138, 0.05);
            border-radius: 16px;
            border: 1px solid rgba(163, 177, 138, 0.1);
        }
        .feature h3 {
            color: #3A3A3A;
            font-size: 18px;
            margin-bottom: 10px;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
        }
        .feature p {
            color: #6b7280;
            font-size: 14px;
            margin: 0;
        }
        .brand {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
        }
        .code-highlight {
            background: rgba(163, 177, 138, 0.1);
            padding: 2px 8px;
            border-radius: 6px;
            font-family: monospace;
            color: #A3B18A;
            font-weight: 600;
        }
        .celebration-box {
            background: linear-gradient(135deg, rgba(255, 192, 159, 0.1), rgba(163, 177, 138, 0.1));
            padding: 25px;
            border-radius: 16px;
            border: 2px solid rgba(255, 192, 159, 0.2);
            margin: 25px 0;
            text-align: center;
        }
        .next-steps {
            background: rgba(163, 177, 138, 0.05);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #A3B18A;
            margin: 25px 0;
        }
        
        @media (max-width: 767px) {
            .container {
                max-width: 90%;
                margin: 10px auto;
                border-radius: 16px;
            }
            .header, .content, .footer {
                padding: 30px 25px;
            }
            .features {
                gap: 15px;
                flex-direction: column;
            }
            .feature {
                min-width: auto;
            }
            .header h1 {
                font-size: 2.2rem;
            }
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
            <div class="welcome-icon"></div>
            
            <h2>Welcome to DelWell, {{ $user->name }}!</h2>
            
            <div class="celebration-box">
                <h3 style="margin: 0 0 10px 0; color: #3A3A3A; font-size: 20px;">Your Email is Verified!</h3>
                <p style="margin: 0; font-size: 16px; color: #6b7280;">You're now officially part of the DelWell community and ready to start your journey toward meaningful connections!</p>
            </div>
            
            <p>Congratulations on taking the first step toward finding conscious, authentic relationships.</p>
            
            <p>Take a breath, tune in, and begin. DelWell helps you explore who you are, so you can invite the right kind of connection into your life.</p>
            
            <p>Your DelWell adventure begins now!</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('user.dashboard') }}" class="dashboard-btn" style="color: white !important; text-decoration: none !important;">
                    Explore Your Dashboard
                </a>
            </div>
            
            
            <p style="margin-top: 30px;">We're here to support you every step of the way. If you have any questions or need help getting started, don't hesitate to reach out to our friendly support team.</p>
            
            <p style="font-style: italic; color: #A3B18A; text-align: center; margin-top: 30px;">
                "The best relationships begin with authentic connections." - DelWell Team
            </p>
        </div>
        
        <div class="footer">
            <p><strong class="brand">DelWell™ - Conscious Connections</strong></p>
            <p>For questions, simply reply to this email.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">&copy; 2025 DelWell™. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
