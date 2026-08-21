<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo vi phạm - Thông báo kết quả xử lý</title>
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
            background-color: #16a34a;
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
            background-color: #f0fdf4;
            border-left: 4px solid #16a34a;
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
        .result-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 16px;
            color: #15803d;
            margin-top: 10px;
            font-weight: 500;
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
            <h1>Báo Cáo Vi Phạm - Đã Được Phê Duyệt & Xử Lý</h1>
        </div>

        <div class="content">
            <div class="greeting">
                Xin chào <strong>{{ $reporter->name ?? 'Quý khách' }}</strong>,
            </div>
            <p>
                Cảm ơn bạn đã phản ánh thông tin vi phạm tới hệ thống. Ban quản trị đã tiến hành kiểm tra, xác minh và chấp nhận xử lý báo cáo của bạn.
            </p>

            <div class="info-card">
                @if($report->created_at)
                <div class="info-item">
                    <span class="label">Ngày gửi báo cáo:</span> {{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y H:i') }}
                </div>
                @endif
                @if($report->trip)
                <div class="info-item">
                    <span class="label">Mã chuyến đi:</span> #{{ $report->trip->trip_code ?? $report->trip->id }}
                </div>
                @endif
                <div class="info-item">
                    <span class="label">Trạng thái xử lý:</span> <strong style="color: #16a34a;">Đã chấp nhận (Approved)</strong>
                </div>
            </div>

            <p><span class="label">Kết quả xử lý:</span></p>
            <div class="result-box">
                {{ $adminNote ?? 'Báo cáo đã được xử lý và áp dụng biện pháp xử phạt.' }}
            </div>

            <p style="margin-top: 24px;">
                Cảm ơn bạn đã chung tay cùng <strong>DRIVIO</strong> xây dựng môi trường thuê xe minh bạch và an toàn.
            </p>
        </div>
        <div class="footer">
            <p>Email này được gửi tự động từ Hệ thống Quản lý Thuê xe <strong>DRIVIO</strong>.</p>
            <p>&copy; {{ date('Y') }} DRIVIO. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
