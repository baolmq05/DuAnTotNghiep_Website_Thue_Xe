<template>
  <section class="bg-gray-50 min-h-screen">
    <!-- Hero Banner -->
    <PolicyBanner />

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-16">
      <div class="grid grid-cols-12 gap-8">
        <!-- Sidebar -->
        <PolicySidebar />

        <!-- Main Content -->
        <div class="tutorial-content col-span-12 lg:col-span-9">
          <div class="category-option mb-5">
            <h1 class="font-bold text-2xl">Chính sách &amp; Quy định</h1>
          </div>
          <div class="main-content text-justify">
            <h6 class="font-bold mb-3">1. Trách nhiệm của khách thuê xe và chủ xe trong giao dịch cho thuê xe tự lái</h6>
            <p class="subtitle">Mục đích lâu dài của DRIVIO là hướng đến việc xây dựng cộng đồng chia sẻ xe ô tô văn minh
              và uy tín tại Việt Nam. Vì thế, để đảm bảo các giao dịch thuê xe trong cộng đồng được diễn ra một cách
              thuận lợi và thành công tốt đẹp thì việc quy định trách nhiệm của các bên trong tuân thủ các chính sách
              của DRIVIO và các điều khoản cam kết là rất quan trọng.</p>
            <p>A. Trách nhiệm của chủ xe<br>- Giao xe và toàn bộ giấy tờ liên quan đến xe đúng thời gian và trong tình
              trạng an toàn, vệ sinh sạch sẽ nhằm đảm bảo chất lượng dịch vụ.<br>- Các giấy tờ xe liên quan bao gồm:
              giấy đăng ký xe (bản photo công chứng), giấy kiểm định, giấy bảo hiểm xe (bản photo công chứng hoặc bản
              gốc).<br>- Chịu trách nhiệm pháp lý về nguồn gốc và quyền sở hữu của xe.</p>
            <p>B. Trách nhiệm khách thuê xe<br>- Kiểm tra kỹ xe trước khi nhận và trước khi hoàn trả xe. Kí xác nhận
              biên bản bàn giao về tình trạng xe khi nhận và khi hoàn trả.<br>- Thanh toán đầy đủ tiền thuê xe cho Chủ
              xe khi nhận xe.<br>- Tại thời điểm nhận xe khách hàng xuất trình đầy đủ các giấy tờ liên quan cho chủ xe:
              GPLX (chủ xe đối chiếu bản gốc với thông tin GPLX đã xác thực trên app DRIVIO &amp; gửi lại), CCCD gắn chip
              (chủ xe đối chiếu bản gốc với thông tin cá nhân trên VNeID &amp; gửi lại) Hoặc Hộ chiếu (passport) bản gốc
              giữ lại. Đặt cọc tài sản thế chấp tiền mặt (15 triệu hoặc theo thỏa thuận với chủ xe) hoặc xe máy có giá
              trị tương đương 15 triệu trở lên (xe máy và cavet gốc) trước khi nhận xe.<br>- <i><u>Đối với trường hợp
                  chủ xe hỗ trợ chính sách miễn thế chấp:</u></i> Khách hàng không cần để lại tài sản (xe máy hoặc 15
              triệu tiền mặt) khi thuê xe của chủ xe. Trường hợp phát sinh các chi phí khác (nếu có) trong quá trình
              thuê xe, khách hàng vui lòng thanh toán trực tiếp cho chủ xe khi làm thủ tục trả xe.<br>- Tuân thủ quy
              định và thời gian trả xe như 2 bên đã thỏa thuận.<br>- Chịu trách nhiệm đền bù mọi thất thoát về phụ tùng,
              phụ kiện của xe, đền bù 100% theo giá phụ tùng chính hãng nếu phát hiện phụ tùng bị tráo đổi, chịu 100%
              chi phí sửa chữa xe nếu có xảy ra hỏng hóc tùy theo mức độ hư tổn của xe, chi phí các ngày xe nghỉ không
              chạy được do lỗi của khách thuê xe (giá được tính bằng giá thuê trong hợp đồng) và các khoản phí vệ sinh
              xe nếu có.</p>
            <p>C. Trách nhiệm và khuyến nghị của DRIVIO<br>- DRIVIO khuyến nghị Chủ xe và Khách thuê xe cần thực hiện việc
              giao kết bằng văn bản "Hợp đồng cho thuê xe tự lái" cũng như kí kết "Biên bản bàn giao xe" nhằm đảm bảo
              quyền lợi của cả 2 bên trong trường hợp phát sinh tranh chấp.<br>- Chủ xe có thể tham khảo sử dụng mẫu
              "Hợp đồng thuê xe tự lái" và "Biên bản bàn giao xe" của DRIVIO (vui lòng cung cấp email cho bộ phận CSKH
              của DRIVIO để nhận thông tin).<br>- Chủ xe và khách thuê xe tự chịu toàn bộ trách nhiệm dân sự và hình sự
              nếu có phát sinh tranh chấp giữa hai bên nếu có. DRIVIO chỉ đóng vai trò hỗ trợ hai bên dàn xếp các vấn đề
              một cách tốt đẹp nhất, tuân theo các điều khoản, chính sách và quy chế hoạt động cả hai bên đã cam kết với
              DRIVIO.</p>
            <h6 class="font-bold mb-3">2. Trách nhiệm của DRIVIO trong các trường hợp xảy ra sự cố ngoài ý muốn như xe bị cầm cố, thế chấp, bị
              bắt giữ khi được dùng để vận chuyển ma túy, hàng quốc cấm, hoặc gây tai nạn</h6>
            <p>Hiện tại, DRIVIO chỉ đóng vai trò là sàn giao dịch thương mại điện tử về cho thuê xe ô tô, là cầu nối giữa
              các chủ xe và khách hàng có nhu cầu thuê xe. Về cơ bản, mọi thủ tục và toàn bộ các vấn đề phát sinh liên
              quan đến giao dịch cho thuê xe giữa chủ xe và khách thuê sẽ do hai bên tự thỏa thuận, kí hợp đồng và chịu
              trách nhiệm với nhau.<br>Trong trường hợp có xảy ra sự cố ngoài ý muốn như xe bị cầm cố, thế chấp, bị bắt
              khi được dùng để vận chuyển ma túy, hàng quốc cấm hoặc gây ra tai nạn, DRIVIO sẽ cố gắng hỗ trợ tốt nhất
              các chủ xe trong khả năng của mình, giới hạn ở việc hướng dẫn chủ xe các thủ tục cần thiết để trình báo
              với cơ quan công an và các cơ quan có thẩm quyền, cung cấp các thông tin nếu có liên quan đến thành viên
              thuê xe hoặc các thông tin khác nếu có yêu cầu từ cơ quan chức năng, và tiến hành khóa vĩnh viễn tài khoản
              thành viên vi phạm.<br>Để bảo vệ tốt nhất tài sản của mình và giảm thiểu tối đa các rủi ro có thể xảy ra,
              các chủ xe cần ràng buộc chặt chẽ về mặt pháp lí bằng việc giao kết bằng văn bản "Hợp đồng cho thuê xe tự
              lái", "Biên bản bàn giao xe" (có thể tham khảo sử dụng mẫu hợp đồng và biên bản bàn giao do DRIVIO đề xuất
              hoặc có thể sử dụng mẫu riêng của nhà xe), xác minh đầy đủ thông tin và các giấy tờ cá nhân của khách
              thuê, giữ lại tài sản đặt cọc và các giấy tờ cần thiết (và tuyệt đối không giao cà vẹt xe bản chính cho
              khách thuê). Các chủ xe cũng cần trang bị cho xe thiết bị định vị GPS để có thể theo dõi và kiểm tra vị
              trí xe thường xuyên nhằm có các phương án xử lí kịp thời.<br>Thời gian tới, nhằm bảo vệ một cách toàn diện
              nhất quyền lợi của các chủ xe cũng như khách thuê, DRIVIO đang xúc tiến làm việc với các đối tác bảo hiểm
              để triển khai các sản phẩm bảo hiểm đối với dịch vụ thuê xe tự lái theo ngày, áp dụng cho tất cả các giao
              dịch cho thuê được thực hiện thông qua ứng dụng DRIVIO.<br>DRIVIO hy vọng sẽ sớm hoàn thiện và triển khai
              gói giải pháp này trong thời gian sớm nhất</p>
            <h6 class="font-bold mb-3">3. Chính sách hủy chuyến</h6>
            <p>A. Dành cho khách thuê xe<br>Trong trường hợp bất khả kháng bạn cần phải hủy chuyến, khách hàng có thể
              thao tác gửi "Yêu cầu hủy chuyến" trên web/app của DRIVIO. Việc hủy chuyến cần được thực hiện sớm nhất có
              thể, vì theo chính sách hủy chuyến khách hàng có thể mất phí giữ chỗ cho chủ&nbsp;xe.</p>
            <div class="overflow-hidden rounded-lg border border-gray-300">
              <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden">
                  <thead>
                    <tr>
                      <th class="px-4 py-3 text-left font-semibold text-gray-700 border-b">
                        Thời điểm hủy chuyến
                      </th>
                      <th class="px-4 py-3 text-left font-semibold text-gray-700 border-b">
                        Phí hủy chuyến
                      </th>
                    </tr>
                  </thead>

                  <tbody class="divide-y divide-gray-200">
                    <tr>
                      <td class="px-4 py-4 text-gray-700">
                        Trong vòng 1h sau giữ chỗ
                      </td>
                      <td class="px-4 py-4">
                        <div class="flex items-center gap-2 font-medium text-green-600">
                          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                              d="M12.25 2C6.74 2 2.25 6.49 2.25 12C2.25 17.51 6.74 22 12.25 22C17.76 22 22.25 17.51 22.25 12C22.25 6.49 17.76 2 12.25 2ZM15.84 10.59L12.32 14.11C12.17 14.26 11.98 14.33 11.79 14.33C11.6 14.33 11.4 14.26 11.26 14.11L9.5 12.35C9.2 12.06 9.2 11.58 9.5 11.29C9.79 11 10.27 11 10.56 11.29L11.79 12.52L14.78 9.53C15.07 9.24 15.54 9.24 15.84 9.53C16.13 9.82 16.13 10.3 15.84 10.59Z" />
                          </svg>
                          Miễn phí
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td class="px-4 py-4 text-gray-700">
                        <div>Trước chuyến đi &gt; 7 ngày</div>
                        <div class="text-sm text-gray-500">
                          (Sau 1h giữ chỗ)
                        </div>
                      </td>
                      <td class="px-4 py-4">
                        <div class="flex items-center gap-2 font-medium text-green-600">
                          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                              d="M12.25 2C6.74 2 2.25 6.49 2.25 12C2.25 17.51 6.74 22 12.25 22C17.76 22 22.25 17.51 22.25 12C22.25 6.49 17.76 2 12.25 2ZM15.84 10.59L12.32 14.11C12.17 14.26 11.98 14.33 11.79 14.33C11.6 14.33 11.4 14.26 11.26 14.11L9.5 12.35C9.2 12.06 9.2 11.58 9.5 11.29C9.79 11 10.27 11 10.56 11.29L11.79 12.52L14.78 9.53C15.07 9.24 15.54 9.24 15.84 9.53C16.13 9.82 16.13 10.3 15.84 10.59Z" />
                          </svg>
                          10% giá trị chuyến đi
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td class="px-4 py-4 text-gray-700">
                        <div>Trong vòng 7 ngày trước chuyến đi</div>
                        <div class="text-sm text-gray-500">
                          (Sau 1h giữ chỗ)
                        </div>
                      </td>
                      <td class="px-4 py-4">
                        <div class="flex items-center gap-2 font-medium text-red-600">
                          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                              d="M12.25 2C6.74 2 2.25 6.49 2.25 12C2.25 17.51 6.74 22 12.25 22C17.76 22 22.25 17.51 22.25 12C22.25 6.49 17.76 2 12.25 2ZM14.67 13.39C14.97 13.69 14.96 14.16 14.67 14.45C14.52 14.59 14.33 14.67 14.14 14.67C13.95 14.67 13.75 14.59 13.61 14.44L12.25 13.07L10.9 14.44C10.75 14.59 10.56 14.67 10.36 14.67C10.17 14.67 9.98 14.59 9.84 14.45C9.54 14.16 9.53999 13.69 9.82999 13.39L11.2 12L9.82999 10.61C9.53999 10.31 9.54 9.84 9.84 9.55C10.13 9.26 10.61 9.26 10.9 9.56L12.25 10.93L13.61 9.56C13.9 9.26 14.37 9.26 14.67 9.55C14.96 9.84 14.97 10.31 14.67 10.61L13.3 12L14.67 13.39Z" />
                          </svg>
                          40% giá trị chuyến đi
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <br>
            <p>Trường hợp phát sinh phí hủy chuyến, DRIVIO sẽ trừ vào phí giữ chỗ của Quý khách hàng và sẽ thanh toán lại
              khoản tiền này cho Đối tác chủ xe (và ngược lại, trường hợp Đối tác chủ xe hủy chuyến phát sinh chi phí
              đền bù, DRIVIO sẽ hoàn trả lại 100% phí giữ chỗ và tiền phí đền bù cho Quý khách trong 1 - 3 ngày làm việc
              kể từ thời điểm ghi nhận thông tin số tài khoản ngân hàng).</p>
            <p>B. Dành cho Chủ xe<br>Nhằm gia tăng sự cam kết của chủ xe cũng như đảm bảo quyền lợi của khách thuê,
              trường hợp chủ xe hủy chuyến (vì lí do không giao đúng xe, không đảm bảo xe, không đúng giờ), nếu như
              không thỏa thuận được hoặc không có sự đồng ý từ phía khách thuê, thì chủ xe phải bồi thường phí hủy
              chuyến cho khách thuê theo chính sách hủy chuyến.<br></p>
            <div class="overflow-hidden rounded-lg border border-gray-300">
              <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden">
                  <thead>
                    <tr>
                      <th class="px-4 py-3 text-left font-semibold text-gray-700 border-b">
                        Thời điểm hủy chuyến
                      </th>
                      <th class="px-4 py-3 text-left font-semibold text-gray-700 border-b">
                        Phí hủy chuyến
                      </th>
                    </tr>
                  </thead>

                  <tbody class="divide-y divide-gray-200">
                    <tr>
                      <td class="px-4 py-4 text-gray-700">
                        Trong vòng 1h sau giữ chỗ
                      </td>
                      <td class="px-4 py-4">
                        <div class="flex items-center gap-2 font-medium text-green-600">
                          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                              d="M12.25 2C6.74 2 2.25 6.49 2.25 12C2.25 17.51 6.74 22 12.25 22C17.76 22 22.25 17.51 22.25 12C22.25 6.49 17.76 2 12.25 2ZM15.84 10.59L12.32 14.11C12.17 14.26 11.98 14.33 11.79 14.33C11.6 14.33 11.4 14.26 11.26 14.11L9.5 12.35C9.2 12.06 9.2 11.58 9.5 11.29C9.79 11 10.27 11 10.56 11.29L11.79 12.52L14.78 9.53C15.07 9.24 15.54 9.24 15.84 9.53C16.13 9.82 16.13 10.3 15.84 10.59Z" />
                          </svg>
                          Miễn phí
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td class="px-4 py-4 text-gray-700">
                        <div>Trước chuyến đi &gt; 7 ngày</div>
                        <div class="text-sm text-gray-500">
                          (Sau 1h giữ chỗ)
                        </div>
                      </td>
                      <td class="px-4 py-4">
                        <div class="flex items-center gap-2 font-medium text-green-600">
                          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                              d="M12.25 2C6.74 2 2.25 6.49 2.25 12C2.25 17.51 6.74 22 12.25 22C17.76 22 22.25 17.51 22.25 12C22.25 6.49 17.76 2 12.25 2ZM15.84 10.59L12.32 14.11C12.17 14.26 11.98 14.33 11.79 14.33C11.6 14.33 11.4 14.26 11.26 14.11L9.5 12.35C9.2 12.06 9.2 11.58 9.5 11.29C9.79 11 10.27 11 10.56 11.29L11.79 12.52L14.78 9.53C15.07 9.24 15.54 9.24 15.84 9.53C16.13 9.82 16.13 10.3 15.84 10.59Z" />
                          </svg>
                          10% giá trị chuyến đi
                        </div>
                      </td>
                    </tr>

                    <tr>
                      <td class="px-4 py-4 text-gray-700">
                        <div>Trong vòng 7 ngày trước chuyến đi</div>
                        <div class="text-sm text-gray-500">
                          (Sau 1h giữ chỗ)
                        </div>
                      </td>
                      <td class="px-4 py-4">
                        <div class="flex items-center gap-2 font-medium text-red-600">
                          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                              d="M12.25 2C6.74 2 2.25 6.49 2.25 12C2.25 17.51 6.74 22 12.25 22C17.76 22 22.25 17.51 22.25 12C22.25 6.49 17.76 2 12.25 2ZM14.67 13.39C14.97 13.69 14.96 14.16 14.67 14.45C14.52 14.59 14.33 14.67 14.14 14.67C13.95 14.67 13.75 14.59 13.61 14.44L12.25 13.07L10.9 14.44C10.75 14.59 10.56 14.67 10.36 14.67C10.17 14.67 9.98 14.59 9.84 14.45C9.54 14.16 9.53999 13.69 9.82999 13.39L11.2 12L9.82999 10.61C9.53999 10.31 9.54 9.84 9.84 9.55C10.13 9.26 10.61 9.26 10.9 9.56L12.25 10.93L13.61 9.56C13.9 9.26 14.37 9.26 14.67 9.55C14.96 9.84 14.97 10.31 14.67 10.61L13.3 12L14.67 13.39Z" />
                          </svg>
                          40% giá trị chuyến đi
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div><br>
            <p>Phí hủy chuyến sẽ được trừ vào tài khoản của chủ xe trên DRIVIO. Trong trường hợp số dư tài khoản của chủ
              xe nhỏ hơn số tiền phí hủy chuyến, phần chênh lệch sẽ được ghi âm vào tài khoản và sẽ cấn trừ vào thu nhập
              của chủ xe cho các chuyến xe tiếp theo.<br>Bên cạnh việc chịu phí, các chủ xe thực hiện hủy chuyến nhiều
              lần sẽ bị tạm khóa tài khoản thành viên trên ứng dụng DRIVIO.<br>Bộ phận CSKH của DRIVIO sẽ liên hệ với
              khách thuê xe thông báo về tình hình chuyến đi bị hủy, và tiến hành hoàn trả lại 100% tiền phí giữ chỗ và
              chi phí đền bù cho khách (nếu có).</p>
            <h6 class="font-bold mb-3">4. Chính sách giá</h6>
            <p>A. Dành cho khách thuê xe<br>Trên ứng dụng DRIVIO, mỗi dòng xe sẽ được cho thuê tại các mức giá khác nhau
              tùy thuộc vào sự quyết định của các chủ xe và được niêm yết công khai.<br>Về cơ bản, cơ cấu giá của một
              chuyến đi được tính bao gồm các thành phần:<br>- Đơn giá thuê: Là giá thuê niêm yết bởi chủ xe mà bạn dễ
              dàng nhìn thấy trong phần thông tin xe. Giá thuê trên DRIVIO được tính theo đơn vị nhỏ nhất là ngày. Chủ xe
              có thể điều chỉnh giá thuê khác nhau cho từng ngày, chính vì vậy, chi phí thuê xe của bạn có thể tăng hoặc
              giảm tùy vào thời điểm bạn thuê xe. Thông thường, giá thuê sẽ cao hơn trong dịp cuối tuần và các ngày lễ,
              tết.<br>- Chiết khấu: một số chủ xe có chính sách chiết khấu cho các chuyến xe kéo dài 1 tuần hoặc 1 tháng
              (mức chiết khấu bình quân từ 5-20% tùy vào quyết định của chủ xe). Vì thế, nếu bạn có nhu cầu thuê xe du
              lịch hay công tác dài ngày, hãy ưu tiên lựa chọn các chủ xe này để có được mức giá tốt hơn.<br>- Chi phí
              vận chuyển: nếu bạn không có nhiều thời gian để đến trực tiếp địa diểm của chủ xe để nhận xe, bạn có thể
              lựa chọn các chủ xe có cung cấp thêm dịch vụ giao xe tận nơi. Chi phí giao nhận xe bình quân từ
              5.000-15.000 VNĐ/km tùy vào quyết định của chủ xe được thể hiện tại phần thông tin xe và sẽ được cộng vào
              chi phí thuê xe của bạn.<br>- Mã khuyến mãi: là mã giảm giá mà DRIVIO gửi tặng đến các thành viên của mình
              cho trong các đợt khuyến mãi, hoặc dành cho các thành viên thân thiết giao dịch thường xuyên trên ứng dụng
              DRIVIO. Mã khuyến mãi này sẽ được trừ trực tiếp vào chi phí thuê xe của bạn.</p>
            <p>Bảng tóm tắt:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="rounded-lg border border-gray-200 p-4 bg-gray-50">
                <p class="font-medium text-gray-700">
                  Phí thuê xe
                </p>
                <p class="mt-2 text-gray-600">
                  = Đơn giá thuê ngày × Số ngày
                  <span class="text-sm text-gray-500 block">
                    (Giá các ngày có thể thay đổi)
                  </span>
                </p>
              </div>

              <div class="rounded-lg border border-gray-200 p-4 bg-gray-50">
                <p class="font-medium text-gray-700">
                  Chiết khấu
                </p>
                <p class="mt-2 text-gray-600">
                  = % Chiết khấu × Phí thuê xe
                </p>
              </div>

              <div class="rounded-lg border border-gray-200 p-4 bg-gray-50">
                <p class="font-medium text-gray-700">
                  Phí vận chuyển
                </p>
                <p class="mt-2 text-gray-600">
                  = Đơn giá vận chuyển × Số km
                </p>
              </div>

              <div class="rounded-lg border border-gray-200 p-4 bg-gray-50">
                <p class="font-medium text-gray-700">
                  Khuyến mãi
                </p>
                <p class="mt-2 text-gray-600">
                  = % Khuyến mãi × (Phí thuê xe − Chiết khấu + Phí vận chuyển)
                </p>
              </div>
            </div>
            <p>B. Dành cho Chủ xe<br>Các chủ xe của DRIVIO được quyền thiết lập giá thuê xe hàng ngày cho tháng hiện hành
              và tối đa 3 tháng kế tiếp. Bạn có thể sử dụng giá đề xuất của DRIVIO hoặc có thể tùy chỉnh giá cho thuê
              theo mong muốn của bạn.<br>- Giá đề xuất của DRIVIO: được định vị thấp hơn 10% so với giá thuê xe bình quân
              trên thị trường. Mức giá này được tiến hành khảo sát hàng tháng bởi bộ phận phát triển thị trường của
              DRIVIO đối với từng dòng xe khác nhau.</p>
            <h6 class="font-bold mb-3">5. Chính sách thanh toán</h6>
            <p>Sau khi nhận được sự đồng ý cho thuê xe từ phía chủ xe, bước tiếp theo, bạn cần phải thanh toán Phí giữ
              chỗ trước cho DRIVIO 40% Giá trị chuyến đi hoặc 100% Giá trị chuyến đi. Bạn có thể chọn lựa mức Phí giữ chỗ
              và hình thức thanh toán chuyển khoản qua ngân hàng trực tuyến hoặc Visa.<br>Đối với hình thức thanh toán
              Phí giữ chỗ là 40% Giá trị chuyến đi, phần Phí còn lại 60% bạn sẽ thanh toán trực tiếp cho chủ xe ngay khi
              nhận được xe.</p>
            <h6 class="font-bold mb-3">6. Chính sách thời gian giao nhận</h6>
            <p>Thời gian thuê xe mặc định trong hệ thống được thiết lập từ 9h tối đến 8h tối ngày kế tiếp. Tuy nhiên,
              khách hàng cũng có thể tùy chỉnh lựa chọn thời gian thuê xe theo nhu cầu của mình, trong vòng 24 giờ sẽ
              được tính 1 ngày.</p>
            <h6 class="font-bold mb-3">7. Chính sách kết thúc sớm chuyến đi</h6>
            <p>Trong trường hợp bạn muốn kết thúc sớm chuyến đi của mình khi vẫn chưa đến thời hạn trả xe, để có thể
              được hoàn lại tiền cho các ngày chưa sử dụng, bạn nên trao đổi trước với chủ xe và cần phải nhận được sự
              đồng ý từ phía chủ xe. Số tiền được hoàn lại sẽ do bạn và chủ xe tự thỏa thuận với nhau. Lưu ý rằng, bạn
              sẽ không được hoàn lại Phí giữ chỗ bạn đã thanh toán cho DRIVIO.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import PolicyBanner from '~/components/Banner/PolicyBanner.vue'

useSeoMeta({
  title: 'Chính Sách & Quy Định | DRIVIO — Trải Nghiệm Thuê Xe Khác Biệt',
  description: 'Các điều khoản sử dụng dịch vụ, chính sách bảo mật thông tin và quy định hủy chuyến trên nền tảng thuê xe tự lái Drivio.',
  ogTitle: 'Chính Sách & Quy Định | Drivio',
  ogDescription: 'Thông tin chi tiết về các điều khoản, quyền lợi và nghĩa vụ của khách thuê và chủ xe trên Drivio.',
  ogImage: '/images/about/policy.webp',
})
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}

p {
  @apply text-gray-600 leading-8 text-[17px] mb-4;
}

li {
  @apply text-gray-600 leading-8;
}
</style>