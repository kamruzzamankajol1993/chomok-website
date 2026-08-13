<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chomok Email Verification</title>
</head>
<body style="margin:0;padding:0;background:#f6f6f6;font-family:Arial,Helvetica,sans-serif;color:#222;">
  <div style="max-width:560px;margin:0 auto;padding:32px 16px;">
    <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 2px 10px rgba(0,0,0,.06);">
      <h2 style="margin:0 0 16px;font-size:24px;">Verify your Chomok account</h2>
      <p style="margin:0 0 12px;line-height:1.6;">Hello {{ $name }},</p>
      <p style="margin:0 0 20px;line-height:1.6;">Use the following 6-digit verification code to complete your registration:</p>
      <div style="font-size:32px;font-weight:700;letter-spacing:8px;text-align:center;padding:18px;background:#f4f4f4;border-radius:10px;">{{ $otp }}</div>
      <p style="margin:20px 0 0;line-height:1.6;color:#666;">This code expires in {{ $expiresInMinutes }} minutes. If you did not request this registration, you can ignore this email.</p>
    </div>
  </div>
</body>
</html>
