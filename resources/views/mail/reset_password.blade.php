<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
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
        .reset-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
            text-align: center;
        }
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #333;
                color: #f8f9fa;
            }
            .content {
                background-color: #444;
            }
            .reset-button {
                background-color: #0056b3;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <a href="http://127.0.0.1:8000/"><img src="https://i.ibb.co.com/sPgWKmH/Logo-Silok2.png" width="100" height="90" alt="Logo-Silok2" border="0"></a><br />
            <div class="header">Reset Your Password</div>
            <p>Anda telah meminta untuk mereset kata sandi Anda. Klik tombol di bawah ini untuk mereset:</p>
            <a href="{{ $resetLink }}" class="reset-button">Reset Kata Sandi</a>
            <p><strong>Tautan ini akan kadaluarsa dalam 60 menit.</strong></p>
            <p>Jika Anda tidak meminta reset kata sandi, harap abaikan email ini.</p>
        </div>
        <div class="footer">
            Anda menerima email ini karena Anda terdaftar di Silok dan sesuai dengan Ketentuan Penggunaan dan/atau tujuan sah lainnya.
            <br><br>
            © 2024 Silok. Hak cipta dilindungi undang-undang.
        </div>
    </div>
</body>
</html>
