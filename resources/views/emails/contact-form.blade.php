<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background: #f4f7fa;
            padding: 40px 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .header p {
            font-size: 15px;
            opacity: 0.95;
            margin: 0;
        }
        .content {
            padding: 40px 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        .info-card {
            background: #f8f9fb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid #e8eaed;
        }
        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e8eaed;
        }
        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #667eea;
            min-width: 100px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            flex: 1;
            color: #333333;
            font-size: 15px;
        }
        .info-value a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .info-value a:hover {
            text-decoration: underline;
        }
        .message-box {
            background: linear-gradient(135deg, #f8f9fb 0%, #ffffff 100%);
            border-left: 4px solid #667eea;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
        }
        .message-label {
            font-weight: 600;
            color: #667eea;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: block;
        }
        .message-text {
            color: #333333;
            font-size: 15px;
            line-height: 1.7;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .meta-info {
            background: #fef9f5;
            border-radius: 12px;
            padding: 20px;
            margin-top: 24px;
            border: 1px solid #fce8d6;
        }
        .meta-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }
        .meta-label {
            font-size: 13px;
            color: #666666;
            font-weight: 500;
        }
        .meta-value {
            font-size: 13px;
            color: #333333;
            font-weight: 600;
        }
        .action-box {
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            border-radius: 12px;
            padding: 24px;
            margin-top: 30px;
            text-align: center;
            border: 1px solid #bae6fd;
        }
        .action-box p {
            color: #0369a1;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.6;
            margin: 0;
        }
        .action-button {
            display: inline-block;
            background: #667eea;
            color: white !important;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .footer {
            background: #f8f9fb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e8eaed;
        }
        .footer-logo {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 12px;
        }
        .footer p {
            color: #666666;
            font-size: 13px;
            margin: 8px 0;
        }
        .footer-links {
            margin-top: 16px;
        }
        .footer-link {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            margin: 0 10px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 30px 20px;
            }
            .info-item {
                flex-direction: column;
            }
            .info-label {
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>New Contact Form Submission</h1>
            <p>Someone has reached out through your website</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Contact Information -->
            <div class="section-title">Contact Information</div>
            <div class="info-card">
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $contactData['name'] }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <a href="mailto:{{ $contactData['email'] }}">{{ $contactData['email'] }}</a>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Subject</div>
                    <div class="info-value">{{ $contactData['subject'] }}</div>
                </div>
            </div>
            
            <!-- Message -->
            <div class="section-title">Message</div>
            <div class="message-box">
                <span class="message-label">Message Content</span>
                <div class="message-text">{{ $contactData['message'] }}</div>
            </div>
            
            <!-- Metadata -->
            <div class="meta-info">
                @if(isset($contactData['ip_address']))
                <div class="meta-item">
                    <span class="meta-label">IP Address</span>
                    <span class="meta-value">{{ $contactData['ip_address'] }}</span>
                </div>
                @endif
                <div class="meta-item">
                    <span class="meta-label">Submitted On</span>
                    <span class="meta-value">{{ $contactData['submitted_at'] }}</span>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="action-box">
                <p><strong>⏰ Response Required</strong></p>
                <p>Please respond to this inquiry within 24 hours to maintain excellent customer service.</p>
                <a href="mailto:{{ $contactData['email'] }}" class="action-button">Reply to Customer</a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">DelWell™</div>
            <p>© {{ date('Y') }} DelWell. All Rights Reserved.</p>
            <p>This is an automated notification from your DelWell contact form.</p>
            <div class="footer-links">
                <a href="#" class="footer-link">Privacy Policy</a>
                <a href="#" class="footer-link">Terms of Service</a>
                <a href="#" class="footer-link">Contact Support</a>
            </div>
        </div>
    </div>
</body>
</html>
