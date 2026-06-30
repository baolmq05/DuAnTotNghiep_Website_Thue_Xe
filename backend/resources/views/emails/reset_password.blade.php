<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mã OTP đặt lại mật khẩu</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #e1e7e5;
        }
        .header {
            background-color: #286874;
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        .content p {
            margin: 0 0 20px 0;
            font-size: 16px;
        }
        .otp-container {
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            display: inline-block;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #286874;
            background-color: #f0f7f8;
            padding: 12px 30px;
            border-radius: 8px;
            border: 1px dashed #286874;
        }
        .footer {
            background-color: #f8faf9;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #777777;
            border-top: 1px solid #e1e7e5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Khôi Phục Mật Khẩu</h1>
        </div>
        <div class="content">
            <p>Xin chào,</p>
            <p>Chúng tôi nhận được yêu cầu khôi phục mật khẩu cho tài khoản của bạn. Vui lòng sử dụng mã OTP dưới đây để hoàn tất việc đặt lại mật khẩu:</p>
            <div class="otp-container">
                <span class="otp-code">{{ $otp }}</span>
            </div>
            <p>Mã OTP này có hiệu lực trong vòng <strong>15 phút</strong>. Nếu bạn không gửi yêu cầu này, vui lòng bỏ qua email này, tài khoản của bạn vẫn an toàn.</p>
            <p>Trân trọng,<br>Đội ngũ hỗ trợ {{ config('app.name') }}</p>
        </div>
        <div class="footer">
            Đây là email tự động, vui lòng không trả lời email này.
        </div>
    </div>
</body>
</html>
