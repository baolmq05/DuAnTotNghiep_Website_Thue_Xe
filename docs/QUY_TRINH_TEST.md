# Quy Trình Kiểm Thử Toàn Diện Hệ Thống (End-to-End Testing Guide)

Tài liệu này cung cấp kịch bản kiểm thử (Test Cases) chi tiết cho toàn bộ luồng nghiệp vụ của hệ thống **Drivio - Website Thuê Xe Tự Lái**, giúp lập trình viên và kiểm thử viên dễ dàng chạy thử và xác minh tính đúng đắn từ đầu đến cuối.

## Mục Lục
1. [Giai Đoạn 1: Chuẩn Bị & Đăng Ký Tài Khoản]
2. [Giai Đoạn 2: Đăng Ký Xe & Quản Lý Xe (Host/Chủ Xe)]
3. [Giai Đoạn 3: Tìm Kiếm & Đặt Xe (Renter/Khách Thuê)]
4. [Giai Đoạn 4: Quản Lý Vòng Đời Chuyến Đi (Trip Lifecycle)]
5. [Giai Đoạn 5: Ví, Giao Dịch & Rút Tiền]
6. [Giai Đoạn 6: Chat & AI Chatbot]


## 1. Giai Đoạn 1: Chuẩn Bị & Đăng Ký Tài Khoản

Mục đích: Đảm bảo luồng đăng ký, đăng nhập và hoàn thiện hồ sơ xác thực bằng lái xe hoạt động đúng.

### Test Case 1.1: Đăng ký & Đăng nhập tài khoản mới
*   **Các bước thực hiện:**
    1. Truy cập trang đăng ký ở Frontend (`/auth/register`).
    2. Điền thông tin: Tên, Email, Mật khẩu và gửi.
    3. Xác nhận đăng ký thành công và tự động chuyển hướng đăng nhập.
    4. Kiểm tra cookie `USER_INFO` và `access_token`.
*   **Kết quả kỳ vọng:**
    *   Tài khoản mới được tạo trong bảng `users` với `role_id = 2` (User thường).
    *   Frontend nhận token JWT và hiển thị tên người dùng trên Header.

### Test Case 1.2: Xác thực bằng lái xe (Driving License)
*   **Các bước thực hiện:**
    1. Truy cập mục Hồ sơ cá nhân.
    2. Điền số bằng lái, họ tên, ngày sinh, tải lên ảnh bằng lái xe.
    3. Gửi yêu cầu xác thực.
*   **Kết quả kỳ vọng:**
    *   Ảnh tải lên thành công qua `CloudinaryService` (lưu trong thư mục `licenses`).
    *   Bảng `driving_licenses` tạo bản ghi với trạng thái `status = 0` (Chờ duyệt).
    *   `users.driving_license_id` trỏ đúng tới id vừa tạo.

---

## 2. Giai Đoạn 2: Đăng Ký Xe & Quản Lý Xe (Host/Chủ Xe)

Mục đích: Chủ xe đăng ký xe mới thành công và xe hiển thị đúng trên lịch/dashboard.

### Test Case 2.1: Đăng ký xe mới (Post a Car)
*   **Các bước thực hiện:**
    1. Đăng nhập bằng tài khoản Chủ xe.
    2. Vào mục "Đăng ký xe" (`/cars/register`).
    3. Nhập đầy đủ thông tin:
        *   Tên xe, Hãng xe, Dòng xe, Biển số xe.
        *   Địa chỉ giao nhận, Tùy chọn giao xe tận nơi (Khoảng cách, phí giao xe).
        *   Đơn giá thuê/ngày, hình ảnh xe.
    4. Gửi đăng ký xe.
*   **Kết quả kỳ vọng:**
    *   Bản ghi xe được tạo thành công trong bảng `cars` với `status = 0` (Chờ duyệt từ Admin).
    *   Bản ghi địa điểm xe được tạo trong bảng `car_locations`.

### Test Case 2.2: Lịch trình xe & Thống kê doanh thu (Dashboard)
*   **Các bước thực hiện:**
    1. Vào trang "Lịch trình xe" (`/car-calendar`).
    2. Vào trang "Thống kê chủ xe" (`/dashboard`).
*   **Kết quả kỳ vọng:**
    *   Trang Lịch trình xe hiển thị đúng 7 ngày trong tuần với danh sách xe của bạn.
    *   Dashboard hiển thị đúng số lượng xe hoạt động, doanh thu tháng hiện tại dạng biểu đồ cột.

---

## 3. Giai Đoạn 3: Tìm Kiếm & Đặt Xe (Renter/Khách Thuê)

Mục đích: Khách thuê tìm kiếm xe, áp mã giảm giá và gửi yêu cầu đặt xe thành công.

### Test Case 3.1: Tìm kiếm & Lọc xe
*   **Các bước thực hiện:**
    1. Truy cập trang chủ (`/`).
    2. Nhập tìm kiếm theo địa điểm hoặc hãng xe.
    3. Chọn bộ lọc: Loại xe (Số sàn/Số tự động), Khoảng giá.
*   **Kết quả kỳ vọng:**
    *   Hiển thị danh sách xe khớp chính xác với bộ lọc.

### Test Case 3.2: Đặt xe kèm Mã giảm giá (Promo Code)
*   **Các bước thực hiện:**
    1. Chọn xe đang ở trạng thái hoạt động (`status = 1`).
    2. Chọn ngày bắt đầu và ngày kết thúc thuê.
    3. Nhập mã giảm giá (ví dụ: `GIAM20`) và nhấn áp dụng.
    4. Kiểm tra số tiền giảm giá và tổng tiền.
    5. Nhấn "Gửi yêu cầu thuê xe".
*   **Kết quả kỳ vọng:**
    *   API `promotions/check` trả về giảm giá đúng theo cấu hình (ví dụ: giảm 10% tối đa 200k).
    *   Một chuyến đi mới được tạo trong bảng `trips` với trạng thái `status = 1` (Pending).
    *   Chủ xe nhận được thông báo về chuyến đi mới.


## 4. Giai Đoạn 4: Quản Lý Vòng Đời Chuyến Đi (Trip Lifecycle)

Đây là phần quan trọng nhất kiểm thử toàn bộ logic chuyển trạng thái chuyến đi (`TripStatus`).

    [*] --> Pending : Khách gửi yêu cầu
    Pending --> OwnerCancel : Chủ xe từ chối
    Pending --> WaitingPayment : Chủ xe chấp nhận
    WaitingPayment --> Confirmed : Thanh toán thành công
    Confirmed --> Ongoing : Bàn giao xe (Upload ảnh)
    Ongoing --> WaitingReturn : Hết thời gian thuê
    WaitingReturn --> Complete : Trả xe thành công (Giải ngân)

### Test Case 4.1: Duyệt chuyến xe (Pending -> Waiting Payment)
*   **Chủ xe:** Vào danh sách chuyến đi -> Nhấn "Xác nhận yêu cầu".
*   **Kết quả kỳ vọng:** `trips.status` chuyển sang `2` (WaitingPayment). Khách thuê nhận thông báo yêu cầu thanh toán.

### Test Case 4.2: Thanh toán chuyến đi (Waiting Payment -> Confirmed)
*   **Khách thuê:** Vào chi tiết chuyến đi -> Chọn "Thanh toán bằng ví Drivio" (hoặc cổng VNPay/ZaloPay).
*   **Kết quả kỳ vọng:**
    *   Số tiền được trừ từ ví khách thuê.
    *   `trips.status` chuyển sang `3` (Confirmed).
    *   Giao dịch thanh toán được ghi nhận trong bảng `transactions`.
    *   Tiền được chuyển vào trạng thái tạm giữ ở bảng `pending_balances`.

### Test Case 4.3: Bàn giao xe & Bắt đầu đi (Confirmed -> Ongoing)
*   **Chủ xe/Khách thuê:** Tải lên tối thiểu 4 ảnh ngoại quan xe khi bàn giao và nhấn "Bắt đầu chuyến đi".
*   **Kết quả kỳ vọng:**
    *   `trips.status` chuyển sang `4` (Ongoing).
    *   Ảnh bàn giao được lưu trong bảng `trip_images` với loại `handover`.

### Test Case 4.4: Gia hạn chuyến đi (Optional Extension)
*   **Khách thuê:** Vào chi tiết chuyến đi -> Chọn "Yêu cầu gia hạn" -> Nhập số ngày gia hạn mới.
*   **Chủ xe:** Nhận thông báo -> Chọn "Đồng ý gia hạn".
*   **Khách thuê:** Thanh toán khoản tiền chênh lệch gia hạn.
*   **Kết quả kỳ vọng:**
    *   Bản ghi trong `trip_extensions` đổi trạng thái sang `3` (Đã gia hạn).
    *   Thời gian `trips.end_at` được tự động cộng thêm số ngày gia hạn.
    *   Ghi nhận giao dịch đóng tiền gia hạn bổ sung.

### Test Case 4.5: Hoàn thành chuyến đi (Waiting Return -> Complete)
*   **Chủ xe:** Kiểm tra xe lúc trả -> Tải lên ảnh xe lúc nhận lại -> Nhấn "Xác nhận hoàn thành".
*   **Kết quả kỳ vọng:**
    *   `trips.status` chuyển sang `7` (Complete).
    *   Bản ghi `pending_balances` chuyển trạng thái giải ngân.
    *   Tiền cọc/thuê xe (trừ đi 10% phí dịch vụ) được cộng trực tiếp vào ví của Chủ xe.


## 5. Giai Đoạn 5: Ví, Giao Dịch & Rút Tiền

### Test Case 5.1: Nạp tiền vào ví qua cổng thanh toán VNPay/ZaloPay
*   **Các bước thực hiện:**
    1. Vào mục "Ví của tôi" -> Chọn "Nạp tiền".
    2. Nhập số tiền (ví dụ: `500,000` VNĐ).
    3. Chọn cổng thanh toán (VNPay hoặc ZaloPay) và nhấn "Nạp tiền".
    4. Trình duyệt chuyển hướng sang trang sandbox thử nghiệm của ngân hàng -> Nhập thông tin test thành công.
*   **Kết quả kỳ vọng:**
    *   Callback/IPN của cổng thanh toán gọi về Backend xử lý thành công.
    *   Số dư ví `wallets.amount` được cộng thêm `500,000` VNĐ.
    *   Tạo bản ghi log nạp tiền thành công trong bảng `transactions`.

### Test Case 5.2: Yêu cầu rút tiền về tài khoản ngân hàng
*   **Các bước thực hiện:**
    1. Đảm bảo tài khoản đã liên kết thông tin ngân hàng ở Hồ sơ.
    2. Vào Ví -> Chọn "Rút tiền".
    3. Nhập số tiền rút và gửi yêu cầu.
*   **Kết quả kỳ vọng:**
    *   Hệ thống kiểm tra ví nếu số dư khả dụng đủ sẽ trừ trực tiếp tiền trong ví (`wallets.amount`).
    *   Tạo yêu cầu rút tiền với trạng thái `pending` trong bảng `refunds`.
    *   Admin duyệt yêu cầu rút tiền thủ công sau đó.


## 6. Giai Đoạn 6: Chat & AI Chatbot

### Test Case 6.1: Chat trực tiếp giữa Chủ xe và Khách thuê
*   **Các bước thực hiện:**
    1. Đăng nhập 2 tài khoản trên 2 trình duyệt khác nhau (hoặc ẩn danh).
    2. Vào mục "Tin nhắn" (`/chats`) -> Chọn cuộc trò chuyện tương ứng với chuyến đi.
    3. Gửi tin nhắn văn bản hoặc gửi ảnh.
*   **Kết quả kỳ vọng:**
    *   Tin nhắn được gửi và nhận tức thời bằng realtime (Broadcasting Laravel Reverb).
    *   Unread count (số tin nhắn chưa đọc) cập nhật chính xác trên sidebar.

### Test Case 6.2: Trò chuyện với Trợ lý AI (Gemini Chatbot)
*   **Các bước thực hiện:**
    1. Vào mục "Tin nhắn" -> Chọn tab "Trợ lý AI" (Chatbot Drivio).
    2. Nhập câu hỏi (ví dụ: *"Chính sách bảo hiểm xe ở đây thế nào?"*).
*   **Kết quả kỳ vọng:**
    *   Hiện chỉ báo đang nhập (`Thinking...`).
    *   Chatbot AI (sử dụng Gemini Flash 2.5) trả về câu trả lời tự động, thân thiện và chính xác về dịch vụ thuê xe.
