<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OtpMailer extends Mailable
{
    public function __construct(
        public int $otp,
        public string $name
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Verify your AMUMA account');
    }

    public function content(): Content
    {
        return new Content(
            htmlString: "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body style='margin:0;padding:0;background:linear-gradient(135deg,#1e40af 0%,#1d4ed8 50%,#2563eb 100%);font-family:Arial,sans-serif;min-height:100vh;'>
            <table width='100%' height='100%' cellpadding='0' cellspacing='0' style='min-height:100vh;'>
                <tr>
                    <td align='center' valign='middle' style='padding:60px 20px;'>
                        <table width='520' cellpadding='0' cellspacing='0'>

                            <tr>
                                <td align='center' style='padding-bottom:32px;'>
                                    <p style='margin:0;font-size:11px;color:#93c5fd;letter-spacing:6px;text-transform:uppercase;'>AMUMA</p>
                                </td>
                            </tr>

                            <tr>
                                <td style='background:#ffffff;border-radius:24px;overflow:hidden;'>

                                    <!-- Card Header -->
                                    <table width='100%' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td style='padding:48px 56px 40px;border-bottom:1px solid #f1f5f9;'>
                                                <p style='margin:0 0 6px;font-size:22px;font-weight:700;color:#0f172a;'>Verify your email</p>
                                                <p style='margin:0;font-size:14px;color:#94a3b8;line-height:1.6;'>
                                                    Hi <strong style='color:#1e40af;'>{$this->name}</strong> — use the code below to complete your sign up.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>

                                    <table width='100%' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td style='padding:40px 56px;background:#f8fafc;'>
                                                <p style='margin:0 0 16px;font-size:11px;color:#94a3b8;letter-spacing:4px;text-transform:uppercase;text-align:center;'>Your verification code</p>
                                                <table width='100%' cellpadding='0' cellspacing='0'>
                                                    <tr>
                                                        <td align='center'>
                                                            <div style='background:#ffffff;border-radius:16px;padding:28px 40px;display:inline-block;box-shadow:0 2px 16px rgba(30,64,175,0.10);'>
                                                                <p style='margin:0;font-size:48px;font-weight:800;letter-spacing:18px;color:#1e40af;font-family:\"Courier New\",monospace;text-align:center;'>{$this->otp}</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <p style='margin:20px 0 0;font-size:12px;color:#94a3b8;text-align:center;'>Expires in <strong style='color:#64748b;'>
                                                5 minutes</strong></p>
                                            </td>
                                        </tr>
                                    </table>

                                    <table width='100%' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td style='padding:32px 56px;border-top:1px solid #f1f5f9;'>
                                                <table width='100%' cellpadding='0' cellspacing='0' style='background:#fff7ed;border-radius:10px;'>
                                                    <tr>
                                                        <td style='padding:14px 18px;'>
                                                            <p style='margin:0;font-size:13px;color:#9a3412;'>
                                                                ⚠️ &nbsp;Never share this code. AMUMA will <strong>never</strong> ask for your OTP.
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <p style='margin:24px 0 0;font-size:13px;color:#cbd5e1;'>
                                                    If you didn't create an account, you can safely ignore this email.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align='center' style='padding:32px 0 0;'>
                                    <p style='margin:0;font-size:12px;color:#93c5fd;'>© 2026 AMUMA. All rights reserved.</p>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        "
        );
    }
}
