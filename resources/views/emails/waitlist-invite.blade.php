<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DelWell</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }
        .email-header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #667eea;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .email-body p {
            margin: 15px 0;
            font-size: 16px;
            color: #555;
        }
        .invite-code-box {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin: 30px 0;
            border: 2px dashed #667eea;
        }
        .invite-code-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .invite-code {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .steps-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        .steps-section h3 {
            color: #333;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .step-number {
            background-color: #667eea;
            color: #ffffff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
            margin-right: 15px;
        }
        .step-content {
            flex: 1;
            padding-top: 4px;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }
        .highlight-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .highlight-box p {
            margin: 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Welcome to DelWell!</h1>
            <p>Your application has been approved</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Congratulations!</h2>
            
            <p>We're thrilled to welcome you to the DelWell community! After carefully reviewing your application, we're excited to invite you to join our platform for meaningful connections and personal growth.</p>

            <!-- Invite Code Box -->
            <div class="invite-code-box">
                <div class="invite-code-label">Your Exclusive Invite Code</div>
                <div class="invite-code">{{ $application->invite_code }}</div>
                <p style="margin: 10px 0 0; font-size: 14px; color: #666;">
                    Keep this code safe - you'll need it to create your account
                </p>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ url('/invite') }}" class="cta-button">
                    Create Your Account Now →
                </a>
            </div>

            <!-- Steps Section -->
            <div class="steps-section">
                <h3>Getting Started (3 Simple Steps)</h3>
                
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <strong>Enter Your Invite Code</strong><br>
                        Click the button above or visit <a href="{{ url('/invite') }}">{{ url('/invite') }}</a> and enter your code
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <strong>Complete Your Profile</strong><br>
                        Fill out our guided 4-step onboarding to help us find your perfect matches
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <strong>Start Connecting</strong><br>
                        Discover compatible matches based on your values, lifestyle, and goals
                    </div>
                </div>
            </div>

            <!-- Important Note -->
            <div class="highlight-box">
                <p>
                    <strong>⚠️ Important:</strong> This invite code is unique to you and can only be used once. 
                    Please don't share it with others.
                </p>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #666;">
                <strong>Need help?</strong> If you have any questions or need assistance, 
                feel free to reply to this email or contact us at 
                <a href="mailto:{{ config('mail.from.address') }}" style="color: #667eea;">{{ config('mail.from.address') }}</a>
            </p>

            <p style="margin-top: 30px; font-size: 16px; color: #333;">
                We can't wait to see you thrive in our community! 🌟
            </p>

            <p style="margin-top: 20px;">
                Warm regards,<br>
                <strong>The DelWell Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 0 0 10px;">
                <strong>DelWell</strong> - Building Meaningful Connections
            </p>
            <p style="margin: 0 0 10px;">
                <a href="{{ url('/about') }}">About Us</a> • 
                <a href="{{ url('/privacy-policy') }}">Privacy Policy</a> • 
                <a href="{{ url('/contact') }}">Contact</a>
            </p>
            <p style="margin: 10px 0 0; font-size: 12px; color: #999;">
                © {{ date('Y') }} DelWell. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
