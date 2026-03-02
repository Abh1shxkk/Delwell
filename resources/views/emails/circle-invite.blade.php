<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DelWell Circle Invitation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #FFC09F 0%, #A3B18A 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .invite-section {
            background-color: #f8f9fa;
            border: 2px solid #A3B18A;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .invite-section h2 {
            margin: 0 0 10px 0;
            color: #A3B18A;
            font-size: 18px;
            font-weight: 600;
        }
        .user-name {
            font-size: 24px;
            font-weight: bold;
            color: #3A3A3A;
            margin: 10px 0;
        }
        .relationship {
            font-size: 16px;
            color: #666;
            font-style: italic;
        }
        .cta-button {
            display: inline-block;
            background-color: #FFC09F;
            color: white !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 20px 0;
            transition: background-color 0.3s ease;
        }
        .cta-button:hover {
            background-color: #e6a885;
        }
        .info-box {
            background-color: #A3B18A10;
            border-left: 4px solid #A3B18A;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .footer a {
            color: #A3B18A;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>You're Invited to Join a DelWell Circle!</h1>
            <p>Help a friend on their dating journey</p>
        </div>
        
        <div class="content">
            <p>Hi {{ $inviteeName }},</p>
            
            <p>Great news! <strong>{{ $inviterName }}</strong> has invited you to join their DelWell Circle. They value your insights and want your support on their dating journey.</p>
            
            <div class="invite-section">
                <h2>Circle Invitation From</h2>
                <div class="user-name">{{ $inviterName }}</div>
                <div class="relationship">You are their: {{ $relationship }}</div>
            </div>
            
            <div class="info-box">
                <h3 style="margin-top: 0; color: #A3B18A;">What is a DelWell Circle?</h3>
                <p>A Circle is a group of trusted friends and family who provide insights and support to help someone find meaningful relationships. Your perspective matters!</p>
            </div>
            
            <p><strong>As a Circle member, you can:</strong></p>
            <ul>
                <li>Share insights about {{ $inviterName }}'s relationship patterns</li>
                <li>Provide feedback on potential matches</li>
                <li>Offer support and guidance throughout their dating journey</li>
                <li>Help them understand their strengths and areas for growth</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="{{ $acceptUrl }}" class="cta-button">Join {{ $inviterName }}'s Circle</a>
            </div>
            
            <p><strong>How it works:</strong></p>
            <ol>
                <li>Click the button above to accept the invitation</li>
                <li>Create your DelWell account (if you don't have one)</li>
                <li>Complete a brief questionnaire about {{ $inviterName }}</li>
                <li>Provide ongoing insights and support</li>
            </ol>
            
            <p>Your participation will help {{ $inviterName }} build more meaningful connections and find the right partner. Thank you for being someone they trust!</p>
            
            <p>With appreciation,<br>
            <strong>The DelWell Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This invitation was sent by {{ $inviterName }} through DelWell. If you have any questions, please contact us at <a href="mailto:connect@hellodelwell.com">connect@hellodelwell.com</a></p>
            <p>&copy; 2025 DelWell™. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
