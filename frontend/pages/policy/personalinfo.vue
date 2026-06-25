<template>
    <section class="bg-gray-50 min-h-screen">
        <!-- Hero Banner -->
        <PolicyBanner />

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 lg:px-8 py-16">
            <div class="grid lg:grid-cols-12 gap-8">
                <!-- Sidebar -->
                <aside class="lg:col-span-3">
                    <div class="sticky top-24 bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                        <h3 class="font-semibold text-gray-900 mb-5">Nội dung</h3>
                        <ul class="space-y-2">
                            <li>
                                <NuxtLink 
                                    to="/policy" 
                                    class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                                    exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                                >
                                    Chính sách và quy định
                                </NuxtLink>
                            </li>
                            <li>
                                <NuxtLink 
                                    to="/policy/term" 
                                    class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                                    exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                                >
                                    Nguyên tắc chung
                                </NuxtLink>
                            </li>
                            <li>
                                <NuxtLink 
                                    to="/policy/personalinfo" 
                                    class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                                    exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                                >
                                    Chính sách BVDLCN
                                </NuxtLink>
                            </li>
                            <li>
                                <NuxtLink 
                                    to="/policy/candidateprivacy" 
                                    class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                                    exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                                >
                                    DLCN của ứng viên
                                </NuxtLink>
                            </li>
                            <li>
                                <NuxtLink 
                                    to="/policy/resolveconflic" 
                                    class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                                    exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                                >
                                    Giải quyết khiếu nại
                                </NuxtLink>
                            </li>
                        </ul>
                    </div>
                </aside>
                <!-- Main Content -->
                <div class="tutorial-content col-span-9">
                    <div class="category-option mb-5">
                        <h1 class="font-bold text-2xl">Chính sách bảo vệ dữ liệu cá nhân</h1>
                    </div>
                    <div class="main-content text-justify">
                        <p>Mioto luôn coi trọng sự riêng tư và bảo vệ dữ liệu cá nhân của Khách hàng. Chúng tôi hiểu
                            rằng, thông qua Chính sách bảo vệ dữ liệu cá nhân, Khách hàng sẽ biết một cách minh bạch về
                            các nội dung liên quan đến cách thức chúng tôi xử lý và bảo vệ dữ liệu cá nhân của Khách
                            hàng trong quá trình tìm kiếm, tiếp cận, mua, đăng ký, sử dụng sản phẩm, dịch vụ của Mioto
                            nhằm đảm bảo dữ liệu cá nhân luôn riêng tư và an toàn theo quy định của pháp luật.</p>
                        <p>Chính sách bảo vệ dữ liệu cá nhân đồng thời là Thông báo xử lý dữ liệu cá nhân, Quy định bảo
                            vệ dữ liệu cá nhân giữa Mioto và Khách hàng. Chính sách này được thiết kế để giúp Khách hàng
                            hiểu được cách thức chúng tôi thu thập, sử dụng, tiết lộ và/hoặc xử lý dữ liệu cá nhân mà
                            Khách hàng đã cung cấp cho chúng tôi và/hoặc lưu giữ về Khách hàng, cho dù là hiện nay hoặc
                            trong tương lai, cũng như để giúp Khách hàng đưa ra quyết định sáng suốt trước khi cung cấp
                            cho chúng tôi bất kỳ dữ liệu cá nhân nào. Chính sách này có thể thay đổi khi cần thiết và
                            phiên bản cập nhật mới nhất sẽ được đăng tải trên nền tảng của chúng tôi. Vì vậy, chúng tôi
                            khuyến nghị Khách hàng nên định kỳ kiểm tra Chính sách trên nền tảng.</p>
                        <h6 class="font-bold mb-3">1. Định nghĩa</h6>
                        <p>- Mioto: Là Công ty Cổ phần Mioto Asia, Giấy chứng nhận đăng ký doanh nghiệp số 0317307544,
                            là ứng dụng/website đã được đăng ký với Bộ Công Thương.</p>
                        <p>- Khách hàng: là (i) cá nhân và/hoặc (ii) tổ chức có cung cấp dữ liệu cá nhân cho Mioto khi
                            tìm kiếm, tiếp cận, mua, đăng ký sử dụng, sử dụng Sản phẩm, hàng hoá, dịch vụ.</p>
                        <p>- Sản phẩm, hàng hoá, dịch vụ: là bất kỳ sản phẩm, hàng hoá, dịch vụ nào do Mioto cung cấp
                            và/hoặc do Mioto hợp tác với đối tác mà Khách hàng tìm kiếm, tiếp cận, mua, đăng ký sử dụng,
                            sử dụng.</p>
                        <p>- Dữ liệu cá nhân: là thông tin dưới dạng ký hiệu, chữ viết, chữ số, hình ảnh, âm thanh hoặc
                            dạng tương tự trên môi trường điện tử gắn liền với Khách hàng cụ thể hoặc giúp xác định
                            Khách hàng cụ thể. Dữ liệu cá nhân bao gồm Dữ liệu cá nhân cơ bản và Dữ liệu cá nhân nhạy
                            cảm. Dữ liệu cá nhân cơ bản bao gồm: (i) Họ, chữ đệm và tên khai sinh, tên gọi khác (nếu
                            có); (ii) Ngày, tháng, năm sinh; ngày, tháng, năm chết hoặc mất tích; (iii) Giới tính; (iv)
                            Nơi sinh, nơi đăng ký khai sinh, nơi thường trú, nơi tạm trú, nơi ở hiện tại, quê quán, địa
                            chỉ liên hệ; (v) Quốc tịch; (vi) Hình ảnh của cá nhân; (vii) Số điện thoại, số chứng minh
                            nhân dân, số căn cước công dân, số định danh cá nhân, số hộ chiếu, số giấy phép lái xe, số
                            biển số xe, số mã số thuế cá nhân, số bảo hiểm xã hội, số thẻ bảo hiểm y tế; (viii) Tình
                            trạng hôn nhân; (ix) Thông tin về mối quan hệ gia đình (cha mẹ, con cái,vợ, chồng, …) và
                            thông tin về tổ chức, cá nhân có liên quan ví dụ như người giám hộ, người phụ thuộc, người
                            đại diện, người ủy quyền, người liên hệ, …; (x) Thông tin về tài khoản số của cá nhân; (xi)
                            Các thông tin khác gắn liền với Khách hàng cụ thể hoặc giúp xác định Khách hàng cụ thể không
                            thuộc phạm vi Dữ liệu cá nhân nhạy cảm theo quy định pháp luật. (xii) Dữ liệu cá nhân cơ bản
                            khác theo quy định pháp luật tại từng thời điểm.</p>
                        <p>- Dữ liệu cá nhân nhạy cảm: là Dữ liệu cá nhân gắn liền với quyền riêng tư của Khách hàng mà
                            khi bị xâm phạm sẽ gây ảnh hưởng trực tiếp tới quyền và lợi ích hợp pháp của Khách hàng, bao
                            gồm các loại dữ liệu sau: (i) Thông tin liên quan tới việc Khách hàng sử dụng dịch vụ trung
                            gian thanh toán, bao gồm: thông tin định danh khách hàng theo quy định pháp luật, thông tin
                            về tài khoản, thông tin thẻ ngân hàng, dữ liệu về lịch sử giao dịch của tài khoản ngân hàng;
                            thông tin tài chính, tín dụng và các thông tin về hoạt động, lịch sử giao dịch tài chính,
                            chứng khoán, bảo hiểm của khách hàng tại các tổ chức tín dụng, chi nhánh ngân hàng nước
                            ngoài, tổ chức cung ứng dịch vụ trung gian thanh toán, chứng khoán, bảo hiểm, các tổ chức
                            được phép khác; (ii) Dữ liệu về vị trí của Khách hàng được xác định qua dịch vụ định vị;
                            (iii) Dữ liệu cá nhân khác được pháp luật quy định là đặc thù và cần có biện pháp bảo mật
                            cần thiết; (iv) Các thông tin khác gắn liền với Khách hàng cụ thể hoặc giúp xác định Khách
                            hàng cụ thể được xác định là Dữ liệu cá nhân nhạy cảm theo quy định của pháp luật.</p>
                        <p>- Xử lý Dữ liệu cá nhân: là một hoặc nhiều hoạt động tác động tới Dữ liệu cá nhân, như: thu
                            thập, ghi, phân tích, xác nhận, lưu trữ, chỉnh sửa, công khai, kết hợp, truy cập, truy xuất,
                            thu hồi, mã hoá, giải mã, sao chép, chia sẻ, truyền đưa, cung cấp, chuyển giao, xóa, hủy dữ
                            liệu cá nhân hoặc các hành động khác có liên quan theo quy định pháp luật tại từng thời
                            điểm.</p>
                        <p>- Bên Kiểm soát Dữ liệu cá nhân: là tổ chức, cá nhân quyết định mục đích và phương tiện Xử lý
                            Dữ liệu cá nhân.</p>
                        <p>- Bên Xử lý Dữ liệu cá nhân: là tổ chức, cá nhân thực hiện việc Xử lý Dữ liệu cá nhân thay
                            mặt cho Bên Kiểm soát dữ liệu cá nhân, thông qua một hợp đồng với Bên Kiểm soát dữ liệu cá
                            nhân.</p>
                        <p>- Bên Kiểm soát và xử lý Dữ liệu cá nhân: là tổ chức, cá nhân đồng thời quyết định mục đích,
                            phương tiện và trực tiếp xử lý Dữ liệu cá nhân.</p>
                        <h6 class="font-bold mb-3">2. Việc thu thập dữ liệu cá nhân</h6>
                        <p>Chúng tôi sẽ/có thể thu thập dữ liệu cá nhân của Khách hàng khi Khách hàng cung cấp cho chúng
                            tôi, ngoại trừ một số thông tin nhất định mà chúng tôi có thể thu thập từ bên thứ ba hoặc
                            các nguồn có sẵn khác (bao gồm cả nguồn công khai). Ví dụ:</p>
                        <p>- Khi đăng ký và/hoặc sử dụng các dịch vụ hoặc nền tảng của chúng tôi (như họ và tên, số điện
                            thoại, thông tin liên hệ);</p>
                        <p>- Khi cung cấp thông tin để đánh giá việc Khách hàng có đủ điều kiện để cung cấp, sử dụng
                            dịch vụ hay gửi bất kỳ biểu mẫu nào, bao gồm đơn đăng ký hoặc các mẫu đơn khác liên quan đến
                            bất kỳ sản phẩm và dịch vụ nào của chúng tôi, bằng hình thức trực tuyến hay dưới hình thức
                            khác (như thông tin giấy phép lái xe, thông tin phương tiện);</p>
                        <p>- Khi ký kết bất kỳ thỏa thuận nào hoặc cung cấp các tài liệu hoặc thông tin khác liên quan
                            đến tương tác giữa Khách hàng với chúng tôi/khách hàng của chúng tôi, hoặc khi sử dụng các
                            sản phẩm và dịch vụ của chúng tôi;</p>
                        <p>- Khi tương tác với chúng tôi, chẳng hạn như thông qua các cuộc gọi điện thoại (có thể được
                            ghi âm lại), thư từ, fax, gặp gỡ trực tiếp, thông qua tính năng giao tiếp trong ứng dụng của
                            chúng tôi, các nền tảng, ứng dụng truyền thông xã hội và email;</p>
                        <p>- Khi sử dụng các dịch vụ điện tử của chúng tôi, hoặc tương tác với chúng tôi qua nền tảng
                            hoặc trang web hoặc các dịch vụ của chúng tôi. Trường hợp này bao gồm thông qua tập tin
                            cookie mà chúng tôi có thể triển khai khi tương tác với các nền tảng hoặc trang web của
                            chúng tôi;</p>
                        <p>- Khi liên kết tài khoản Mioto với tài khoản mạng xã hội của Khách hàng hoặc các tài khoản
                            bên ngoài khác hoặc sử dụng các tính năng mạng xã hội khác, phù hợp với các chính sách của
                            nhà cung cấp;</p>
                        <p>- Khi thực hiện các giao dịch thanh toán hoặc giao dịch khác thông qua dịch vụ của chúng tôi;
                        </p>
                        <p>- Khi cung cấp ý kiến phản hồi hoặc gửi khiếu nại cho chúng tôi;</p>
                        <p>- Khi gửi dữ liệu cá nhân của Khách hàng cho chúng tôi vì bất kỳ lý do gì.</p>
                        <p>Các trường hợp trên không nhằm mục đích liệt kê đầy đủ các trường hợp và chỉ đưa ra một số
                            trường hợp phổ biến về thời điểm dữ liệu cá nhân của Khách hàng có thể bị thu thập. Trong
                            một số trường hợp, Khách hàng có thể cung cấp Dữ liệu cá nhân của các cá nhân khác cho chúng
                            tôi. Khi Khách hàng thêm họ như người liên hệ khẩn cấp, sử dụng trò chuyện trong ứng dụng,
                            hoặc Khách hàng thêm họ như người thụ hưởng bất kỳ việc sử dụng dịch vụ của chúng tôi. Việc
                            cung cấp như vậy, Khách hàng cam đoan và bảo đảm rằng Khách hàng đã có được sự chấp thuận
                            của họ để dữ liệu cá nhân của họ được thu thập, sử dụng và tiết lộ như quy định trong Chính
                            sách này.</p>
                        <h6 class="font-bold mb-3">3. Phạm vi, mục đích thu thập và sử dụng thông tin</h6>
                        <h6 class="font-bold mb-3">3.1. Phạm vi thu thập</h6>
                        <p>- Đối với Khách hàng là Chủ xe: Họ tên, email, số căn cước công dân/căn cước, mã số thuế, số
                            điện thoại, địa chỉ thường trú, địa chỉ tạm trú, thông tin số tài khoản ngân hàng và thông
                            tin thanh toán, các hình ảnh và thông tin về sản phẩm muốn đăng tải trên nền tảng.</p>
                        <p>- Đối với Khách hàng là Khách thuê xe: Họ tên, email, số căn cước công dân/căn cước, thông
                            tin giấy phép lái xe ô tô, số điện thoại, địa chỉ thường trú, địa chỉ tạm trú, tài khoản
                            ngân hàng và thông tin thanh toán để hỗ trợ quá trình đặt chỗ với nhà cung cấp là đối tác
                            chủ xe của chúng tôi, xử lý yêu cầu hoàn tiền hoặc yêu cầu bảo hiểm và hủy đặt chỗ.</p>
                        <p>- Khách hàng sẽ tự chịu trách nhiệm về bảo mật và lưu giữ mọi hoạt động sử dụng dịch vụ dưới
                            tên đăng ký, mật khẩu và hộp thư điện tử của mình. Ngoài ra, Khách hàng có trách nhiệm thông
                            báo kịp thời cho Mioto về những hành vi sử dụng trái phép, lạm dụng, vi phạm bảo mật, lưu
                            giữ tên đăng ký và mật khẩu của bên thứ ba để có biện pháp giải quyết phù hợp.</p>
                        <p>- Khách hàng đồng ý không cung cấp cho chúng tôi bất cứ thông tin nào không chính xác hoặc
                            gây hiểu nhầm và Khách hàng đồng ý sẽ thông báo cho chúng tôi về bất cứ thông tin nào không
                            chính xác hoặc khi có sự thay đổi thông tin.</p>
                        <p>- Nếu Khách hàng đăng nhập để trở thành người dùng các nền tảng của chúng tôi sử dụng tài
                            khoản mạng xã hội (“Tài khoản Mạng Xã hội”), liên kết tài khoản của Khách hàng với Tài khoản
                            Mạng Xã hội của Khách hàng hoặc sử dụng bất cứ tính năng mạng xã hội nào, chúng tôi có quyền
                            truy cập thông tin về Khách hàng mà Khách hàng đã cung cấp một cách tự nguyện cho nhà cung
                            cấp dịch vụ Tài khoản Mạng Xã hội của Khách hàng và tuân theo các chính sách của các nhà
                            cung cấp dịch vụ này, và chúng tôi sẽ quản lý và sử dụng các dữ liệu cá nhân này của Khách
                            hàng theo các quy định của Chính sách này tại mọi thời điểm.</p>
                        <p>- Về dữ liệu nhạy cảm, vui lòng lưu ý rằng các dữ liệu này có thể là bắt buộc để thực hiện
                            một số giao dịch đặt chỗ hoặc để xử lý hồ sơ của Khách hàng, và chúng tôi có thể không thể
                            hoàn tất các giao dịch đặt chỗ nếu thiếu thông tin này.</p>
                        <h6 class="font-bold mb-3">3.2. Mục đích thu thập và sử dụng thông tin</h6>
                        <p>Mioto thu thập và sử dụng thông tin cá nhân của Khách hàng cho các mục đích sau đây:</p>
                        <p class="fontWeight-7">Cung cấp dịch vụ cho Khách hàng:</p>
                        <p>- Xác minh danh tính Khách hàng; tạo lập, quản lý, vận hành, cập nhật và bảo mật tài khoản
                            trực tuyến mà Khách hàng đã hoặc có thể thiết lập trên nền tảng của Mioto.</p>
                        <p>- Liên hệ xác nhận thông tin khi Khách hàng đăng ký và sử dụng dịch vụ trên nền tảng nhằm đảm
                            bảo quyền và lợi ích hợp pháp của Khách hàng.</p>
                        <p>- Hỗ trợ việc đặt xe, cung cấp xe và các dịch vụ liên quan cho Khách hàng.</p>
                        <p>- Xử lý các đơn đặt hàng, yêu cầu giao dịch và các hoạt động phát sinh từ việc Khách hàng sử
                            dụng nền tảng.</p>
                        <p>- Cung cấp các sản phẩm, dịch vụ theo nhu cầu của Khách hàng thông qua nền tảng Mioto.</p>
                        <p>- Nâng cao chất lượng sản phẩm, dịch vụ và trải nghiệm người dùng mà Mioto cung cấp.</p>
                        <p>- Liên hệ để tiếp nhận, xử lý và phản hồi các yêu cầu, thắc mắc, khiếu nại, tranh chấp của
                            Khách hàng, trực tiếp hoặc thông qua bên thứ ba cung cấp dịch vụ chăm sóc khách hàng được
                            Mioto ủy quyền hợp pháp.</p>
                        <p>- Thực hiện các hoạt động nội bộ cần thiết để duy trì, vận hành và cung cấp dịch vụ, bao gồm
                            nhưng không giới hạn ở việc khắc phục lỗi kỹ thuật, lỗi phần mềm và sự cố vận hành.</p>
                        <p>- Ngăn chặn, điều tra và xử lý các hành vi gian lận, hành vi vi phạm Điều khoản dịch vụ, hành
                            vi trái pháp luật, thiếu sót hoặc hành vi sai trái khác, cho dù có hoặc không phát sinh từ
                            việc Khách hàng sử dụng dịch vụ của Mioto.</p>
                        <p class="fontWeight-7">Phối hợp với đại lý/đơn vị bảo hiểm trong phạm vi cần thiết để cung cấp
                            dịch vụ cho Khách hàng.</p>
                        <p>- Kết nối Khách hàng với đại lý/đơn vị bảo hiểm để cung cấp hợp đồng bảo hiểm trong quá trình
                            sử dụng dịch vụ thuê xe.</p>
                        <p>- Hỗ trợ việc xác lập, duy trì và thực hiện hợp đồng bảo hiểm giữa Khách hàng và đơn vị bảo
                            hiểm;</p>
                        <p>- Tiếp nhận, xác minh và xử lý thông tin về sự cố, tai nạn, hư hỏng xe để phục vụ giám định,
                            bồi thường bảo hiểm;</p>
                        <p>- Hỗ trợ giải quyết khiếu nại, tranh chấp liên quan đến bảo hiểm phát sinh trong quá trình sử
                            dụng dịch vụ.</p>
                        <p>- Việc xử lý và chia sẻ dữ liệu cá nhân được thực hiện trên cơ sở sự đồng ý của Khách hàng,
                            phù hợp với mục đích đã thông báo, trong phạm vi cần thiết và tuân thủ quy định pháp luật về
                            bảo vệ dữ liệu cá nhân.</p>
                        <p class="fontWeight-7">Quảng cáo các dịch vụ và hoạt động thương mại khác phù hợp nhu cầu của
                            Khách hàng:</p>
                        <p>- Gửi thông báo liên quan đến quá trình trao đổi thông tin giữa Khách hàng và Mioto, bao gồm
                            nhưng không giới hạn ở các chương trình khuyến mại, khảo sát ý kiến, hoạt động tiếp thị và
                            quảng cáo sản phẩm, dịch vụ.</p>
                        <p>- Thực hiện hoạt động quảng cáo, giới thiệu sản phẩm, hàng hoá, dịch vụ và các hoạt động
                            thương mại khác phù hợp với nhu cầu của Khách hàng hoặc theo đánh giá của Mioto là Khách
                            hàng có thể quan tâm.</p>
                        <p class="fontWeight-7">Thực hiện các nghĩa vụ của Mioto theo quy định pháp luật:</p>
                        <p>- Thực hiện các quy trình tìm hiểu, xác minh, sàng lọc (bao gồm nhưng không giới hạn ở việc
                            kiểm tra lý lịch) nhằm tuân thủ các nghĩa vụ theo quy định pháp luật, yêu cầu của cơ quan
                            nhà nước có thẩm quyền hoặc theo các thủ tục quản lý rủi ro nội bộ của Mioto.</p>
                        <p>- Lập dữ liệu thống kê, phân tích, nghiên cứu nhằm phục vụ công tác báo cáo, quản lý nội bộ
                            và/hoặc đáp ứng yêu cầu theo quy định của pháp luật.</p>
                        <p>- Trường hợp có yêu cầu hợp pháp từ cơ quan nhà nước có thẩm quyền, Mioto có trách nhiệm hợp
                            tác và cung cấp thông tin cá nhân của Khách hàng theo đúng quy định, bao gồm nhưng không
                            giới hạn ở yêu cầu từ cơ quan thuế, tòa án, viện kiểm sát hoặc cơ quan công an; ngoài các
                            trường hợp này, không tổ chức hoặc cá nhân nào được phép xâm phạm thông tin cá nhân của
                            Khách hàng.</p>
                        <p>- Bảo vệ quyền, lợi ích hợp pháp, tài sản của Mioto và các đối tác liên quan; việc cung cấp
                            thông tin cá nhân của Khách hàng (nếu có) chỉ được thực hiện trong phạm vi cần thiết và phù
                            hợp với quy định pháp luật Việt Nam.</p>
                        <p>- Ngăn ngừa, phát hiện và xử lý các hành vi xâm nhập trái phép, giả mạo, phá hoại tài khoản
                            Khách hàng hoặc các hành vi ảnh hưởng đến an toàn, bảo mật của nền tảng.</p>
                        <h6 class="font-bold mb-3">4. Sự đồng ý và rút lại sự đồng ý cho phép thu thập, xử lý dữ liệu cá nhân</h6>
                        <p>Việc Mioto thu thập, sử dụng, tiết lộ và xử lý dữ liệu cá nhân của Khách hàng được thực hiện
                            trên cơ sở có căn cứ pháp lý hợp lệ, bao gồm nhưng không giới hạn ở: (i) sự đồng ý của Khách
                            hàng; (ii) nghĩa vụ pháp lý mà Mioto phải thực hiện theo quy định pháp luật; (iii) việc thực
                            hiện hợp đồng mà Khách hàng là một bên; hoặc (iv) các căn cứ hợp pháp khác theo quy định của
                            pháp luật về bảo vệ dữ liệu cá nhân.</p>
                        <p>Sự đồng ý của Khách hàng (trong trường hợp pháp luật yêu cầu phải có sự đồng ý) được thể hiện
                            một cách rõ ràng, cụ thể và có thể chứng minh, thông qua một hoặc nhiều hình thức sau, tùy
                            từng mục đích xử lý dữ liệu: (i) Hành vi tích chọn, xác nhận điện tử trên nền tảng của
                            Mioto; (ii) Ký kết hợp đồng, thỏa thuận, văn bản chấp thuận; (iii) Xác nhận thông qua phương
                            tiện điện tử, ứng dụng, email hoặc hình thức tương đương khác theo quy định pháp luật.</p>
                        <p>Đối với từng mục đích xử lý dữ liệu cá nhân không bắt buộc theo quy định pháp luật, Mioto chỉ
                            thực hiện sau khi có sự đồng ý hợp lệ của Khách hàng. Trong nhiều trường hợp, việc khách
                            hàng từ chối cung cấp hoặc cung cấp không đầy đủ, không chính xác dữ liệu cá nhân của khách
                            hàng có thể xem là hành vi vi phạm nghĩa vụ pháp lý về việc cung cấp dữ liệu cá nhân, và có
                            thể dẫn đến Mioto không thể hợp tác với Khách hàng để cung cấp sản phẩm, dịch vụ hoặc không
                            thể cung cấp sản phẩm, dịch vụ một cách đầy đủ và chất lượng theo tuyên bố.</p>
                        <p>Đối với mục đích tiếp thị và quảng cáo, Mioto sẽ chỉ gửi thông tin quảng cáo khi Khách hàng
                            đã chủ động tích chọn (opt-in) đồng ý nhận. Khách hàng có thể từ chối nhận thông tin tiếp
                            thị bất kỳ lúc nào bằng cách: (i) tắt thông báo trong mục Cá nhân &gt;&gt; Chính sách bảo vệ
                            dữ liệu trên ứng dụng Mioto; hoặc (iii) liên hệ trực tiếp với chúng tôi qua email:
                            contact@mioto.vn. Việc từ chối nhận thông tin tiếp thị không ảnh hưởng đến quyền sử dụng
                            dịch vụ của Khách hàng.</p>
                        <p>Khách hàng có quyền yêu cầu rút lại sự đồng ý cho phép xử lý dữ liệu cá nhân, yêu cầu hạn chế
                            xử lý dữ liệu cá nhân của mình hoặc yêu cầu Mioto xóa dữ liệu cá nhân của Khách hàng bằng
                            việc gửi thông báo bằng văn bản cho Mioto. Tuy nhiên, Khách hàng lưu ý rằng việc thực hiện
                            yêu cầu cầu rút lại sự đồng ý, yêu cầu hạn chế xử lý dữ liệu không áp dụng đối với hoạt động
                            xử lý dữ liệu của Khách hàng trước thời điểm Khách hàng yêu cầu rút lại sự đồng ý hoặc yêu
                            cầu hạn chế xử lý dữ liệu cá nhân, đồng thời dữ liệu cá nhân của khách hàng có thể vẫn sẽ
                            tiếp tục được lưu trữ theo quy định của pháp luật hiện hành.</p>
                        <h6 class="font-bold mb-3">5. Chia sẻ dữ liệu cá nhân của Khách hàng</h6>
                        <p>Trong một số trường hợp, chúng tôi cần chia sẻ dữ liệu cá nhân của Khách hàng với các bên
                            liên quan nhằm phục vụ cho việc cung cấp dịch vụ của chúng tôi. Việc chia sẻ này có thể bao
                            gồm việc chuyển giao hoặc tiết lộ dữ liệu trong nội bộ Mioto và với các bên thứ ba:</p>
                        <p>- Nếu Khách hàng là chủ xe, chúng tôi có thể chia sẻ tên tài khoản người dùng, thông tin số
                            điện thoại, thông tin phương tiện, địa điểm xe với Khách hàng là khách thuê xe; chúng tôi có
                            thể chia sẻ thông tin phương tiện hoặc các thông tin cá nhân khác cho đại lý/công ty bảo
                            hiểm và/hoặc đến các khách thuê xe cho mục đích khiếu nại bảo hiểm;</p>
                        <p>- Nếu Khách hàng là khách thuê xe, chúng tôi có thể chia sẻ tên tài khoản người dùng, thông
                            tin số điện thoại, thông tin giấy phép lái xe, địa điểm giao/nhận xe với chủ xe, đại lý/công
                            ty bảo hiểm;</p>
                        <p>- Nếu Khách hàng để lại xếp hạng và đánh giá cho bất kỳ đối tác chủ xe/khách thuê nào, ảnh hồ
                            sơ, phản hồi, nội dung và tên người dùng của Khách hàng có thể được hiển thị công khai cho
                            cộng đồng và những người dùng khác;</p>
                        <p>- Nếu Khách hàng sử dụng dịch vụ trò chuyện trong ứng dụng của chúng tôi, chúng tôi có thể
                            chia sẻ tên tài khoản và ảnh hồ sơ của Khách hàng với các bên tham gia trò chuyện còn lại;
                        </p>
                        <p>- Nếu Khách hàng sử dụng dịch vụ thanh toán, chúng tôi có thể chia sẻ dữ liệu cá nhân của
                            Khách hàng với một các nhân khác nếu Khách hàng ủy quyền và cho phép kết nối phương thức
                            thanh toán dịch vụ bên thứ ba với dịch vụ thanh toán của chúng tôi nhằm mục đích cho phép
                            Khách hàng thanh toán trên Mioto;</p>
                        <p>- Đối tác kinh doanh, nhà quản lý kênh phân phối, trung gian hoặc các đơn vị kết nối khác
                            tham gia phân phối dịch vụ của chúng tôi, bao gồm việc kết nối và quản lý đặt chỗ của Khách
                            hàng với chủ xe/khách thuê hay các đơn vị dịch vụ khác;</p>
                        <p>- Cơ quan chính phủ và cơ quan giám sát nhằm tuân thủ các nghĩa vụ báo cáo theo quy định pháp
                            luật và các yêu cầu cung cấp dữ liệu từ các cơ quan chức năng (ở địa phương), bao gồm cả cơ
                            quan thuế và cơ quan tư pháp/điều tra như công an, viện kiểm sát, tòa án và các tổ chức
                            trọng tài/hòa giải. Việc này có thể được thực hiện để hoàn thành mục đích pháp lý hoặc bất
                            kì trường hợp nào, ví dụ: khi điều này là cần thiết để phản ứng với một tình huống khẩn cấp
                            đe dọa tính mạng, sức khỏe hoặc sự an toàn của một thực thể hoặc khi điều này là cần thiết
                            cho lợi ích công cộng;</p>
                        <p>- Nhà cung cấp dịch vụ và các bên thứ ba khác hỗ trợ chúng tôi trong hoạt động kinh doanh
                            hàng ngày như hạ tầng điện toán đám mây, hỗ trợ Khách hàng và liên lạc, phát hiện và phòng
                            chống gian lận, xử lý yêu cầu bảo hiểm, và các tổ chức tài chính hỗ trợ Khách hàng trong một
                            số quy trình thanh toán hoặc cung cấp dịch vụ tài chính cho chúng tôi;</p>
                        <p>- Các bên chuyên môn khác như công ty luật hoặc kiểm toán viên. Dữ liệu cá nhân của Khách
                            hàng chỉ được chia sẻ trong phạm vi cần thiết và các bên thứ ba này sẽ xử lý dữ liệu của
                            Khách hàng theo trách nhiệm chuyên môn của họ;</p>
                        <p>- Đối tác tiếp thị và quảng cáo để đảm bảo rằng các quảng cáo phù hợp sẽ được hiển thị đúng
                            đối tượng;</p>
                        <p>- Ban quản trị Mioto, Bộ phận kinh doanh, Bộ phận Chăm sóc khách hàng và các cá nhân được
                            phân công thực hiện các công việc có liên quan: Việc tiếp cận thông tin của các đối tượng
                            này nhằm cung cấp dịch vụ cũng như hỗ trợ khách hàng trong quá trình sử dụng các dịch vụ
                            được cung cấp bởi Mioto;</p>
                        <p>- Chúng tôi có thể chia sẻ dữ liệu cá nhân của Khách hàng với các bên khác, liên quan đến
                            hoạt động hợp nhất, mua bán, sát nhập, hợp tác, tái cấu trúc, tài chính hoặc bất kỳ giao
                            dịch kinh doanh nào khác. Dữ liệu cá nhân của Khách hàng vẫn sẽ chịu trách nhiệm bởi Mioto
                            theo Chính sách đã tồn tại từ trước mà Khách hàng đã đồng ý;</p>
                        <p>- Các bên khác khi có sự đồng ý hoặc theo hướng dẫn của khách hàng: Ngoài các tiết lộ như
                            được mô tả trong Chính sách thông tin cá nhân này, Mioto có thể chia sẻ thông tin cá nhân
                            của Khách hàng với các bên thứ ba khi khách hàng đồng ý hoặc yêu cầu việc chia sẻ như vậy.
                        </p>
                        <p>Tùy từng trường hợp, các bên thứ ba có thể là Bên xử lý dữ liệu cá nhân hoặc Bên kiểm soát và
                            xử lý dữ liệu cá nhân theo quy định pháp luật và thỏa thuận giữa các bên.</p>
                        <h6 class="font-bold mb-3">6. Lưu trữ dữ liệu cá nhân của Khách hàng</h6>
                        <p>Chúng tôi sẽ lưu trữ dữ liệu cá nhân của Khách hàng, bao gồm cả dữ liệu cá nhân nhạy cảm,
                            trong thời gian cần thiết phù hợp với mục đích mà dữ liệu được thu thập và xử lý, đồng thời
                            tuân thủ các quy định pháp luật hiện hành để thực hiện các quyền hợp pháp, bảo vệ lợi ích
                            của chúng tôi hoặc của Khách hàng, như giải quyết các vấn đề phát sinh hoặc bảo vệ trước các
                            khiếu nại, và để tuân thủ các nghĩa vụ pháp lý hoặc quy định khi có yêu cầu pháp lý, thuế,
                            báo cáo, hoặc kiểm toán mà chúng tôi phải tuân thủ, ví dụ như quy định phòng chống rửa tiền
                            yêu cầu chúng tôi lưu trữ hồ sơ giao dịch của Khách hàng trong một khoảng thời gian nhất
                            định trước khi có thể xóa chúng.</p>
                        <p>Khi chúng tôi không còn cần sử dụng thông tin của Khách hàng nữa, chúng tôi sẽ xóa thông tin
                            đó khỏi hệ thống và hồ sơ của mình nếu có thể, và/hoặc vô hiệu hoá hoặc thực hiện các biện
                            pháp hợp lý khác để ẩn danh thông tin sao cho Khách hàng không còn có thể bị nhận diện từ dữ
                            liệu đó.</p>
                        <p>Thời gian lưu trữ thông tin: Dữ liệu cá nhân của Khách hàng sẽ được lưu trữ trong khoảng thời
                            gian cần thiết để thực hiện các Mục đích được nêu trong Chính sách này, trừ khi pháp luật
                            yêu cầu hoặc cho phép một khoảng thời gian lưu giữ lâu hơn. Thời gian lưu trữ khác nhau tùy
                            thuộc vào các yếu tố như bản chất của dữ liệu, lý do thu thập và xử lý, cũng như các yêu cầu
                            pháp lý hoặc kinh doanh liên quan. Khi dữ liệu cá nhân của Khách hàng không còn cần thiết
                            cho dịch vụ hoặc các mục đích, chúng tôi sẽ thực hiện các biện pháp xóa, hủy, ẩn danh hoặc
                            ngăn chặn việc truy cập hoặc sử dụng, trừ trường hợp cần thiết cho việc tuân thủ pháp luật,
                            bảo đảm an toàn, an ninh, phòng ngừa và phát hiện gian lận, phù hợp với các quy định pháp
                            luật hiện hành.</p>
                        <p>Vui lòng lưu ý rằng những thông tin Khách hàng đã chia sẻ với người dùng khác, như đánh giá
                            hoặc nội dung được đăng tải, có thể tiếp tục hiển thị công khai trên nền tảng Mioto ngay cả
                            sau khi Khách hàng đã xóa tài khoản.</p>
                        <h6 class="font-bold mb-3">7. Hậu quả, thiệt hại không mong muốn có khả năng xảy ra</h6>
                        <p>Mioto áp dụng nhiều biện pháp bảo vệ thông tin, bảo đảm an toàn thông tin của Khách hàng,
                            công nghệ bảo mật thông tin khác nhau nhằm bảo vệ và ngăn chặn việc dữ liệu cá nhân của
                            Khách hàng bị truy cập, sử dụng hoặc chia sẻ ngoài ý muốn. Tuy nhiên, không một dữ liệu nào
                            có thể được bảo mật hoàn toàn. Do vậy, Mioto không thể cam kết bảo mật một cách tuyệt đối dữ
                            liệu cá nhân của Khách hàng trong một số trường hợp như: (i) Lỗi phần cứng, phần mềm trong
                            quá trình xử lý dữ liệu làm mất dữ liệu của Khách hàng; (ii) Lỗ hổng bảo mật nằm ngoài khả
                            năng kiểm soát của Mioto, hệ thống bị hacker tấn công gây lộ lọt dữ liệu; (iii) Khách hàng
                            tự làm lộ lọt dữ liệu cá nhân do: bất cẩn hoặc bị lừa đảo; truy cập các website/tải các ứng
                            dụng có chứa phần mềm độc hại, bị hacker chiếm quyền điều khiển.</p>
                        <p>Mioto khuyến cáo Khách hàng bảo mật các thông tin liên quan đến mật khẩu đăng nhập vào tài
                            khoản của mình, mã OTP và không chia sẻ nội dung này với bất kỳ người nào khác; bảo quản
                            thiết bị cá nhân (máy điện thoại, máy tính bảng, máy tính cá nhân…) trong quá trình sử dụng;
                            Khách hàng nên đăng xuất khỏi tài khoản của mình khi không có nhu cầu sử dụng.</p>
                        <p>Khách hàng cần biết rõ rằng bất kỳ thời điểm nào Khách hàng tiết lộ và công khai dữ liệu cá
                            nhân của Khách hàng, dữ liệu đó có thể bị người khác thu thập và sử dụng bởi các mục đích
                            nằm ngoài tầm kiểm soát của Khách hàng và Mioto.</p>
                        <p>Trong trường hợp máy chủ lưu trữ dữ liệu bị tấn công dẫn đến bị mất, lộ, lọt dữ liệu cá nhân
                            của Khách hàng, Mioto sẽ có trách nhiệm thông báo vụ việc cho cơ quan chức năng điều tra xử
                            lý kịp thời và thông báo cho Khách hàng được biết theo quy định pháp luật.</p>
                        <p>Không gian mạng không phải là một môi trường an toàn tuyệt đối. Khi Khách hàng truyền tải dữ
                            liệu cá nhân qua không gian mạng, Khách hàng chỉ nên sử dụng các hệ thống an toàn để truy
                            cập trang thông tin điện tử, ứng dụng hoặc thiết bị. Khách hàng có trách nhiệm giữ thông tin
                            xác thực truy cập của mình cho từng trang thông tin điện tử, ứng dụng hoặc thiết bị an toàn
                            và bí mật.</p>
                        <h6 class="font-bold mb-3">8. Phương tiện và công cụ để người dùng tiếp cận, chỉnh sửa và hủy bỏ dữ liệu cá nhân của
                            mình</h6>
                        <p>Khách hàng có quyền tự kiểm tra, cập nhật, điều chỉnh hoặc hủy bỏ thông tin cá nhân của mình
                            bằng cách đăng nhập vào tài khoản và chỉnh sửa thông tin cá nhân hoặc yêu cầu Mioto thực
                            hiện việc này.</p>
                        <p>Khách hàng có quyền gửi khiếu nại về việc lộ thông tin các nhân cho bên thứ ba đến Mioto. Khi
                            tiếp nhận những phản hồi này, Mioto sẽ xác nhận lại thông tin, trả lời lý do và hướng dẫn
                            Khách hàng khôi phục và bảo mật lại thông tin.</p>
                        <p>Email: contact@mioto.vn</p>
                        <h6 class="font-bold mb-3">9. Thu thập các dữ liệu khác</h6>
                        <p>Như với hầu hết các trang web và các ứng dụng di động khác, thiết bị của Khách hàng gửi thông
                            tin có thể gồm có dữ liệu về Khách hàng, được một máy chủ web ghi lại khi Khách hàng sử dụng
                            nền tảng của chúng tôi. Thông tin này thông thường bao gồm nhưng không giới hạn địa chỉ IP,
                            hệ điều hành của máy tính/thiết bị di động, loại trình duyệt, loại thiết bị di động, các đặc
                            điểm của thiết bị di động, mã định danh thiết bị thống nhất (UDID) hoặc mã định danh thiết
                            bị di động (MEID) của thiết bị di động của Khách hàng, địa chỉ tham chiếu của trang web (nếu
                            có), các trang mà Khách hàng đã truy cập đến trên trang web hoặc ứng dụng di động của chúng
                            tôi và thời gian truy cập và đôi khi là "cookie" (có thể vô hiệu hoá bằng cách sử dụng tùy
                            chọn trình duyệt của Khách hàng) để giúp trang web ghi nhớ lần truy cập cuối cùng của Khách
                            hàng. Nếu Khách hàng đăng nhập, thông tin này được liên kết với tài khoản cá nhân của Khách
                            hàng. Thông tin này cũng được đưa vào các số liệu thống kê ẩn danh để giúp chúng tôi hiểu
                            được khách truy cập sử dụng trang web của chúng tôi như thế nào.</p>
                        <p>Các ứng dụng di động của chúng tôi có thể thu thập thông tin chính xác về địa chỉ của thiết
                            bị di động của Khách hàng sử dụng các công nghệ như GPS, Wi-Fi,…. Chúng tôi thu thập, sử
                            dụng, công bố và/hoặc xử lý các thông tin này cho một hoặc nhiều mục đích bao gồm nhưng
                            không giới hạn các dịch vụ được cung cấp dựa trên vị trí mà Khách hàng yêu cầu hoặc chuyển
                            các nội dung có liên quan đến Khách hàng dựa trên vị trí của Khách hàng hoặc cho phép Khách
                            hàng chia sẻ vị trí của Khách hàng cho các Người sử dụng khác như là một phần của các Dịch
                            vụ được cung cấp bởi các ứng dụng di động của chúng tôi. Đối với phần lớn các thiết bị di
                            động, Khách hàng có thể rút lại sự cho phép để chúng tôi được thu thập các thông tin này dựa
                            trên vị trí của Khách hàng thông qua các cài đặt trên thiết bị. Nếu Khách hàng có câu hỏi
                            nào về cách thức vô hiệu hoá các dịch vụ vị trí trên thiết bị di động của Khách hàng, vui
                            lòng liên hệ với các nhà cung cấp dịch vụ thiết bị di động hoặc nhà sản xuất thiết bị của
                            Khách hàng.</p>
                        <p>Như khi Khách hàng xem các trang trên trang web hoặc ứng dụng di động của chúng tôi, khi
                            Khách hàng xem các nội dung và quảng cáo và truy cập vào phần mềm khác trên Nền tảng của
                            chúng tôi hoặc thông qua Dịch vụ, phần lớn các thông tin tương tự được gửi đến cho chúng tôi
                            (bao gồm nhưng không giới hạn, địa chỉ IP, hệ điều hành, v.v..); nhưng, thay vì các số lượt
                            xem trang, thiết bị của Khách hàng gửi đến chúng tôi các thông tin về nội dung, quảng cáo
                            được xem và/hoặc phần mềm được cài đặt bởi các dịch vụ và nền tảng và thời điểm.</p>
                        <h6 class="font-bold mb-3">10. Cookies</h6>
                        <p>Đôi khi chúng tôi hoặc các nhà cung cấp dịch vụ được cho phép và các đối tác quảng cáo của
                            chúng tôi có thể sử dụng “cookies” hoặc các tính năng khác để cho phép chúng tôi hoặc các
                            bên thứ ba thu thập hoặc chia sẻ thông tin liên quan đến việc sử dụng của Khách hàng đối với
                            dịch vụ hoặc nền tảng của chúng tôi. Các tính năng này sẽ giúp chúng tôi cải thiện nền tảng
                            và các dịch vụ chúng tôi cung cấp, giúp chúng tôi đề xuất các dịch vụ và tính năng mới,
                            và/hoặc cho phép chúng tôi và các đối tác quảng cáo của chúng tôi cung cấp các nội dung có
                            liên quan hơn đến Khách hàng. “Cookies” là các mã danh định được lưu trữ trên máy tính hoặc
                            thiết bị di động của Khách hàng lưu trữ các dữ liệu về máy tính hoặc thiết bị, bằng cách nào
                            và khi nào các dịch vụ hoặc nền tảng được sử dụng hay truy cập, bởi bao nhiêu người và để
                            theo dõi những hoạt động trong các nền tảng của chúng tôi. Chúng tôi có thể liên kết thông
                            tin cookies với dữ liệu cá nhân. Cookies cũng liên kết với thông tin về những nội dung Khách
                            hàng đã chọn và các trang web mà Khách hàng đã xem. Thông tin này được sử dụng để theo dõi
                            nhu cầu, để chuyển tải nội dung phù hợp với sở thích của Khách hàng, để cho phép các đối tác
                            cung cấp dịch vụ quảng cáo cung cấp dịch vụ quảng cáo trên các trang thông qua mạng Internet
                            và để thực hiện phân tích dữ liệu và hoặc theo dõi việc sử dụng Dịch vụ.</p>
                        <p>Khách hàng có thể từ chối sử dụng cookies bằng cách chọn các thiết lập thích hợp trên trình
                            duyệt hoặc thiết bị của Khách hàng. Tuy nhiên, vui lòng lưu ý rằng nếu Khách hàng thực hiện
                            thao tác này Khách hàng có thể không sử dụng được các chức năng đầy đủ của nền tảng hoặc các
                            dịch vụ của chúng tôi.</p>
                        <h6 class="font-bold mb-3">11. Thông tin về trẻ em</h6>
                        <p>Các dịch vụ này không dành cho trẻ em dưới 18 tuổi. Chúng tôi không cố tình thu thập hay lưu
                            giữ bất kỳ dữ liệu cá nhân hay thông tin không nhận dạng cá nhân nào của bất kỳ ai dưới 18
                            tuổi, bất kỳ phần nào của nền tảng của chúng tôi hoặc các dịch vụ khác cũng không dành cho
                            trẻ em dưới 18 tuổi. Bố/mẹ hoặc người giám hộ của trẻ em dưới 18 tuổi vui lòng giám sát và
                            đảm bảo thông tin cá nhân của trẻ dưới 18 tuổi mà mình đang giám hộ không đăng tải thông tin
                            cá nhân cho Mioto. Trong trường hợp thông tin cá nhân của của trẻ em dưới 18 tuổi do quý phụ
                            huynh giám hộ được cung cấp cho Mioto, Bố/mẹ hoặc người giám hộ theo đồng ý với việc xử lý
                            thông tin của trẻ em dưới 18 tuổi có liên quan, và đồng ý chịu sự điều chỉnh của Chính sách
                            này thay mặt cho người được giám hộ. Chúng tôi sẽ khóa bất kỳ tài khoản nào được sử dụng bởi
                            đối tượng trẻ em như vậy và sẽ gỡ và/hoặc xóa bất kỳ dữ liệu cá nhân nào mà chúng tôi cho là
                            đã được gửi bởi bất kỳ trẻ em nào dưới 18 tuổi.</p>
                        <h6>12. Liên kết đến các trang web khác</h6>
                        <p>Khách hàng có thể liên kết tài khoản Mioto của mình với các dịch vụ bên thứ ba, và một số
                            phần của nền tảng Mioto có thể chứa liên kết đến các trang web thuộc sở hữu của bên thứ ba.
                            Chúng tôi không sở hữu hoặc kiểm soát các trang web đó. Khi Khách hàng tương tác với các bên
                            thứ ba này, chúng tôi không chịu trách nhiệm đối với các hoạt động xử lý dữ liệu của họ và
                            khuyến nghị Khách hàng nên đọc kỹ chính sách quyền riêng tư của từng trang web Khách hàng
                            truy cập có thu thập dữ liệu cá nhân của Khách hàng.</p>
                        <h6 class="font-bold mb-3">13. Nghĩa vụ của Khách hàng</h6>
                        <p>- Tuân thủ các quy định pháp luật, quy định của Mioto liên quan đến xử lý dữ liệu cá nhân của
                            Khách hàng;</p>
                        <p>- Cung cấp cũng như đảm bảo có quyền cung cấp hợp pháp một cách đầy đủ, trung thực, chính xác
                            dữ liệu cá nhân, các thông tin khác theo yêu cầu của Mioto khi tìm kiếm, tiếp cận, mua, đăng
                            ký sử dụng và sử dụng sản phẩm, dịch vụ và khi có thay đổi về các thông tin này. Chúng tôi
                            sẽ tiến hành bảo mật dữ liệu cá nhân của Khách hàng căn cứ trên thông tin Khách hàng đã cung
                            cấpcăn cứ trên thông tin Khách hàng đã cung cấp. Do đó, nếu có bất kỳ thông tin sai lệch
                            nào, chúng tôi sẽ không chịu trách nhiệm trong trường hợp thông tin đó làm ảnh hưởng hoặc
                            hạn chế quyền lợi của Khách hàng. Trường hợp không thông báo, nếu có phát sinh rủi ro, tổn
                            thất thì Khách hàng chịu trách nhiệm về những sai sót hay hành vi lợi dụng, lừa đảo khi sử
                            dụng sản phẩm, dịch vụ do lỗi của Khách hàng hoặc do không cung cấp đúng, đầy đủ, chính xác,
                            kịp thời sự thay đổi thông tin; bao gồm cả thiệt hại về tài chính, chi phí phát sinh do
                            thông tin cung cấp sai hoặc không thống nhất.</p>
                        <p>- Phối hợp với chúng tôi, cơ quan nhà nước có thẩm quyền hoặc bên thứ ba trong trường hợp
                            phát sinh các vấn đề ảnh hưởng đến tính bảo mật dữ liệu cá nhân của Khách hàng.</p>
                        <p>- Tự bảo vệ dữ liệu cá nhân của mình; yêu cầu các tổ chức, cá nhân khác có liên quan bảo vệ
                            dữ liệu cá nhân của mình; chủ động áp dụng các biện pháp nhằm bảo vệ dữ liệu cá nhân của
                            Khách hàng trong quá trình sử dụng sản phẩm, dịch vụ của Mioto; thông báo kịp thời cho chúng
                            tôi khi phát hiện thấy có sai sót, nhầm lẫn về dữ liệu cá nhân hoặc nghi ngờ dữ liệu cá nhân
                            đang bị xâm phạm.</p>
                        <p>- Tôn trọng, bảo vệ dữ liệu cá nhân của người khác.</p>
                        <p>- Tự chịu trách nhiệm đối với những thông tin, dữ liệu mà Khách hàng tạo lập, cung cấp trên
                            không gian mạng; tự chịu trách nhiệm trong trường hợp dữ liệu cá nhân bị rò rỉ, xâm phạm do
                            lỗi của mình.</p>
                        <p>- Thường xuyên cập nhật các quy định, chính sách liên quan đến việc bảo vệ và xử lý dữ liệu
                            cá nhân của Mioto trong từng thời kỳ được thông báo tới Khách hàng qua nền tảng.</p>
                        <p>- Thực hiện quy định pháp luật về bảo vệ dữ liệu cá nhân và tham gia phòng, chống các hành vi
                            vi phạm quy định về bảo vệ dữ liệu cá nhân.</p>
                        <p>- Các nghĩa vụ khác theo quy định pháp luật.</p>
                        <h6 class="font-bold mb-3">14. Cơ chế tiếp nhận và giải quyết khiếu nại liên quan đến việc dữ cá nhân khách hàng bị sử
                            dụng sai mục đích hoặc phạm vi đã thông báo</h6>
                        <p>Khách hàng có quyền gửi khiếu nại liên quan đến việc thực hiện Chính sách bảo vệ dữ liệu cá
                            nhân đến Mioto. Khi tiếp nhận, Mioto sẽ xác nhận lại thông tin, phản hồi lý do và hướng dẫn
                            Khách hàng khôi phục và bảo mật lại thông tin. Các bước thực hiện như sau:</p>
                        <p>Bước 1: Tiếp nhận khiếu nại</p>
                        <p>Khách hàng gửi khiếu nại cho Mioto theo các kênh như email, điện thoại hoặc gửi văn bản trực
                            tiếp đến địa chỉ của Mioto. Bộ phận CSKH của Mioto có trách nhiệm tiếp nhận và yêu cầu khách
                            hàng nêu rõ nội dung khiếu nại và cung cấp các thông tin, tài liệu có liên quan.</p>
                        <p>Bước 2: Xác minh nội dung khiếu nại</p>
                        <p>Sau khi tiếp nhận, Bộ phận CSKH sẽ căn cứ trên nội dung khiếu nại, thực hiện phân loại và
                            chuyển nội dung khiếu nại đến Bộ phận phụ trách để xử lý.</p>
                        <p>Bước 3: Tổng hợp kết quả xử lý khiếu nại và thực hiện phản hồi cho khách hàng (gọi điện thoại
                            và/hoặc gửi email cho khách hàng).</p>
                        <p>Thời hạn xử lý yêu cầu: Mioto cam kết xử lý và phản hồi tất cả các yêu cầu liên quan đến
                            quyền của chủ thể dữ liệu trong thời hạn luật định kể từ khi nhận được yêu cầu hợp lệ. Trong
                            trường hợp yêu cầu phức tạp cần thêm thời gian xử lý, Mioto sẽ thông báo bằng văn bản và nêu
                            rõ thời hạn hoàn thành dự kiến theo quy định pháp luật hiện hành.</p>
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