<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Contact Form Submission</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f7f7f7;">
<!-- Main Container -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f7f7f7">
    <tr>
        <td align="center" valign="top">
            <!-- Email Container -->
            <table width="600" border="0" cellspacing="0" cellpadding="0" style="margin:20px auto; background:#ffffff; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                <!-- Header with Logo -->
                <tr>
                    <td bgcolor="#000000" style="padding:25px 30px; text-align:center; border-radius:8px 8px 0 0;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center">
                                    <!-- Logo -->
                                    <img src="https://gryemen.com/img/logo.png" alt="Company Logo" width="180" style="max-width:180px; height:auto; display:block; margin:0 auto 15px;" />
                                    <!-- Title -->
                                    <h2 style="margin:10px 0 0 0; font-size:22px; font-weight:600; color:#ffffff;">New Contact Form Submission</h2>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td style="padding:30px;">
                        <!-- Sender Info -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#f9f9f9; border-left:4px solid #E60010; padding:20px; margin-bottom:25px; border-radius:0 4px 4px 0;">
                            <tr>
                                <td style="padding-bottom:15px; margin-left: 10px;display: block">
                                    <strong style="color:#E60010; display:block; margin-bottom:5px;">Name</strong>
                                    <span style="color:#555555;">{{ $name }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="margin-left: 10px;display: block">
                                    <strong style="color:#E60010; display:block; margin-bottom:5px;">Email</strong>
                                    <span style="color:#555555;">{{ $email }}</span>
                                </td>
                            </tr>
                        </table>

                        <!-- Message -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="padding-bottom:5px;">
                                    <strong style="color:#E60010; display:block; margin-bottom:5px;">Message</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="background:#f5f5f5; padding:15px; border-radius:4px; border:1px solid #e0e0e0; line-height:1.7;">
                                    {{ $messageBody }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td bgcolor="#f0f0f0" style="padding:15px 30px; text-align:center; font-size:12px; color:#777777; border-radius:0 0 8px 8px;">
                        <p style="margin:0;">This email was sent from the contact form on your website.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
