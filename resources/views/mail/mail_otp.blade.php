<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode 2FA Login Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .content {
            background-color: #ffffff;
            border-radius: 5px;
            padding: 30px;
            text-align: center;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .header {
            color: #5f2eea;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            background-color: #e8eaf6;
            padding: 10px;
            margin: 20px 0;
            letter-spacing: 5px;
            border-radius: 5px;
        }
        .footer {
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <a href="http://127.0.0.1:8000/"><img src="https://i.ibb.co.com/sPgWKmH/Logo-Silok2.png" width="100" height="90" alt="Logo-Silok2" border="0"></a><br />
            <div class="header">Kode 2FA</div>
            <p>Ini adalah kode verifikasi login Anda:</p>
            <div class="otp-code">{{ $mailData['otp'] }}</div>
            <p><strong>Jangan bagikan kode di atas kepada siapa pun.</strong></p>
            <p>Catatan: Kode hanya berlaku selama 1 menit.</p>
        </div>
        <div class="footer">
            Anda menerima email ini karena terdaftar di Silok dan sesuai dengan Ketentuan Penggunaan dan/atau dan tujuan sah lainnya.
            <br><br>
            © 2024 Silok. All rights reserved.
        </div>
    </div>
</body>
</html>
