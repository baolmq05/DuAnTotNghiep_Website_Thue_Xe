<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo xử lý vi phạm chủ xe</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        .brand-header {
            background-color: #ffffff;
            padding: 20px 24px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }
        .brand-header img {
            max-height: 65px;
            width: auto;
            display: inline-block;
            vertical-align: middle;
        }
        .brand-name {
            color: #0f172a;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 3px;
            margin: 0;
        }
        .notice-banner {
            background-color: #dc2626;
            color: #ffffff;
            padding: 16px 24px;
            text-align: center;
        }
        .notice-banner h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }
        .content {
            padding: 30px 24px;
            line-height: 1.6;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 16px;
        }
        .info-card {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 16px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-item:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #4a5568;
        }
        .reason-box {
            background-color: #fff5f5;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 16px;
            color: #991b1b;
            margin-top: 10px;
            font-weight: 500;
        }
        .warning-note {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 14px;
            color: #991b1b;
            font-size: 14px;
            margin-top: 20px;
        }
        .footer {
            background-color: #f7fafc;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header với Logo Website -->
        <div class="brand-header">
            @if(file_exists(public_path('images/drivio_logo.png')))
                <img src="{{ $message->embed(public_path('images/drivio_logo.png')) }}" alt="DRIVIO Logo" />
            @elseif(file_exists(public_path('images/logo.png')))
                <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="DRIVIO Logo" />
            @else
                <h2 class="brand-name">DRIVIO</h2>
            @endif
        </div>

        <div class="notice-banner">
            <h1>Thông Báo Xử Lý Vi Phạm Chủ Xe</h1>
        </div>

        <div class="content">
            <div class="greeting">
                Xin chào chủ xe <strong>{{ $owner->name ?? 'Quý đối tác' }}</strong>,
            </div>
            <p>
                Hệ thống <strong>DRIVIO</strong> nhận được báo cáo vi phạm liên quan đến chuyến đi của bạn. Sau khi Ban quản trị kiểm tra và xác minh, hệ thống ghi nhận quyết định xử phạt vi phạm đối với tài khoản của bạn.
            </p>

            <div class="info-card">
                @if($report->trip)
                <div class="info-item">
                    <span class="label">Mã chuyến đi:</span> #{{ $report->trip->trip_code ?? $report->trip->id }}
                </div>
                @endif
                <div class="info-item">
                    <span class="label">Ngày ghi nhận:</span> {{ date('d/m/Y H:i') }}
                </div>
                <div class="info-item">
                    <span class="label">Hình thức xử phạt:</span> <strong style="color: #dc2626;">{{ $penaltyLabel }}</strong>
                </div>
            </div>

            <p><span class="label">Lý do vi phạm:</span></p>
            <div class="reason-box">
                {{ $reason ?? 'Vi phạm điều khoản dịch vụ và quy định dành cho chủ xe.' }}
            </div>

            <div class="warning-note">
                <strong>📌 Lưu ý quy định xử phạt:</strong><br>
                Quy định hệ thống tính lũy <strong>đủ 3 lần vi phạm</strong> trong 90 ngày, tài khoản của bạn sẽ tự động bị <strong>khóa vĩnh viễn</strong> truy cập hệ thống.
            </div>

            <p style="margin-top: 24px;">
                Vui lòng tuân thủ quy định vận hành để đảm bảo trải nghiệm dịch vụ tốt nhất cho khách hàng. Nếu có thắc mắc, vui lòng liên hệ bộ phận hỗ trợ đối tác của chúng tôi.
            </p>
        </div>
        <div class="footer">
            <p>Email này được gửi tự động từ Hệ thống Quản lý Thuê xe <strong>DRIVIO</strong>.</p>
            <p>&copy; {{ date('Y') }} DRIVIO. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
