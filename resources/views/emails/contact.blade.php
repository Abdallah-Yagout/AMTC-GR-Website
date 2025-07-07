<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        /* Base styles */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        /* Email container */
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .email-header {
            background-color: #4a6baf;
            color: white;
            padding: 25px 30px;
            text-align: center;
        }

        .email-header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        /* Content */
        .email-content {
            padding: 30px;
        }

        .message-details {
            background-color: #f9f9f9;
            border-left: 4px solid #4a6baf;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 4px 4px 0;
        }

        .detail-row {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: 600;
            color: #4a6baf;
            display: block;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #555555;
        }

        .message-content {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
            line-height: 1.7;
        }

        /* Footer */
        .email-footer {
            background-color: #f0f0f0;
            padding: 15px 30px;
            text-align: center;
            font-size: 12px;
            color: #777777;
        }

        /* Responsive adjustments */
        @media only screen and (max-width: 480px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }

            .email-header, .email-content, .email-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header" style="background-color: #4a6baf; color: white; padding: 25px 30px; text-align: center;">
        <!-- Logo and heading container -->
        <div style="max-width: 100%; display: inline-block; text-align: center;">
            <!-- Logo image -->
            <img src="https://gryemen.com/img/logo.png"
                 alt="Your Company Logo"
                 style="max-height: 60px; width: auto; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;">

            <!-- Heading -->
            <h2 style="margin: 10px 0 0 0; font-size: 22px; font-weight: 600; color: white;">
                New Contact Form Submission
            </h2>
        </div>
    </div>

    <div class="email-content">
        <div class="message-details">
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value">{{ $name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $email }}</span>
            </div>
        </div>

        <div class="detail-row">
            <span class="detail-label">Message</span>
            <div class="message-content">
                {{ $messageBody }}
            </div>
        </div>
    </div>

    <div class="email-footer">
        <p>This email was sent from the contact form on your website.</p>
    </div>
</div>
</body>
</html>
