<template>
	<section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
		<div class="w-full">
			<h2 class="text-3xl text-center font-extrabold text-black tracking-tight mb-10">Bảo hiểm chuyến đi</h2>

			<!-- Representative Insurance Banner on Top (Clickable) -->
			<div @click="isModalOpen = true"
				class="mb-8 overflow-hidden rounded-3xl shadow-md border border-slate-100 hover:shadow-lg transition-all duration-300 cursor-pointer group relative">
				<img class="w-full h-[200px] sm:h-[320px] md:h-[420px] lg:h-[480px] object-cover rounded-3xl transform hover:scale-[1.01] transition-transform duration-700"
					src="https://n1-cstg.mioto.vn/g/2026/05/09/09/6L6M2Z9U.jpg" alt="Bảo hiểm Drivio Banner">
				<!-- Hover interaction overlay -->
				<div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 flex items-center justify-center">
					<div class="opacity-0 group-hover:opacity-100 bg-white/95 text-slate-900 px-6 py-3 rounded-full text-xs font-bold shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 flex items-center gap-2">
						<Icon name="lucide:info" class="w-4 h-4 text-brand-primary" />
						<span>Xem chi tiết bảo hiểm</span>
					</div>
				</div>
			</div>

			<!-- 4 Insurance Logos at the Bottom -->
			<div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
				<!-- Partner 1: MIC -->
				<div
					class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300 flex items-center justify-center h-24 sm:h-28 md:h-32 lg:h-36">
					<img src="/images/index/insurance/image1.psd.webp" alt="Bảo hiểm MIC"
						class="max-h-12 sm:max-h-16 md:max-h-20 max-w-[80%] object-contain" />
				</div>

				<!-- Partner 2: PVI -->
				<div
					class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300 flex items-center justify-center h-24 sm:h-28 md:h-32 lg:h-36">
					<img src="/images/index/insurance/image2.psd.webp" alt="Bảo hiểm PVI"
						class="max-h-12 sm:max-h-16 md:max-h-20 max-w-[80%] object-contain" />
				</div>

				<!-- Partner 3: VNI -->
				<div
					class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300 flex items-center justify-center h-24 sm:h-28 md:h-32 lg:h-36">
					<img src="/images/index/insurance/image3.psd.webp" alt="Bảo hiểm VNI"
						class="max-h-12 sm:max-h-16 md:max-h-20 max-w-[80%] object-contain" />
				</div>

				<!-- Partner 4: BSH -->
				<div
					class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300 flex items-center justify-center h-24 sm:h-28 md:h-32 lg:h-36">
					<img src="/images/index/insurance/image4.psd.webp" alt="Bảo hiểm BSH"
						class="max-h-12 sm:max-h-16 md:max-h-20 max-w-[80%] object-contain" />
				</div>
			</div>
		</div>

		<!-- Teleported Modal -->
		<Teleport to="body">
			<Transition name="modal-fade">
				<div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
					<!-- Backdrop -->
					<div @click="isModalOpen = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" />

					<!-- Modal Content -->
					<div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] modal-content border border-slate-100">
						<!-- Header -->
						<div class="px-6 py-4.5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
							<h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
								<Icon name="lucide:shield-check" class="w-5 h-5 text-brand-primary" />
								Thông tin sản phẩm bảo hiểm
							</h4>
							<button @click="isModalOpen = false"
								class="p-2 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-all duration-200">
								<Icon name="lucide:x" class="w-5 h-5" />
							</button>
						</div>

						<!-- Body -->
						<div class="p-6 md:p-8 overflow-y-auto custom-scrollbar flex-1 text-slate-700 text-sm space-y-6 leading-relaxed">
							<!-- Section 1: Intro -->
							<div>
								<h5 class="text-lg md:text-xl font-extrabold text-black mb-3">Bảo hiểm thuê xe tự lái</h5>
								<div class="space-y-3 font-medium text-slate-600">
									<p>Với nhiều năm kinh nghiệm trong lĩnh vực cho thuê xe, Drivio hiểu rằng các rủi ro đâm đụng, cháy nổ, thủy kích gây tổn thất lớn (vượt quá khả năng chi trả) luôn tiềm ẩn trong thời gian thuê xe.</p>
									<p class="flex items-start gap-2 text-slate-700">
										<span class="text-rose-500 font-bold flex-shrink-0">❌</span>
										<span>Trong khi đó, hầu hết các rủi ro phát sinh trong quá trình thuê xe tự lái sẽ <u class="font-bold text-slate-900">không thuộc phạm vi</u> của <b>Bảo hiểm thân vỏ xe theo năm</b> (hay còn gọi là Bảo hiểm 2 chiều).</span>
									</p>
									<p class="flex items-start gap-2 text-slate-700">
										<span class="text-emerald-500 font-bold flex-shrink-0">✅</span>
										<span>Xuất phát từ nhu cầu thiết yếu của khách hàng, Drivio kết hợp với các đối tác bảo hiểm hàng đầu Việt Nam cùng mang đến sản phẩm Bảo hiểm thuê xe tự lái mức phí thực sự tiết kiệm và số tiền bảo hiểm lớn (đến 100% giá trị xe) sẽ giúp bạn không còn lo lắng khi thuê xe &amp; an tâm tận hưởng hành trình của mình.</span>
									</p>
								</div>
							</div>

							<!-- Section 2: Nội dung sản phẩm -->
							<div class="border-t border-slate-100 pt-6">
								<h6 class="text-xs font-bold text-black uppercase tracking-wider border-l-4 border-brand-primary pl-3 mb-3">
									I. Nội dung sản phẩm Bảo hiểm thuê xe
								</h6>
								<p class="mb-4 text-slate-600 font-medium">
									Trong thời gian thuê xe, nếu xảy ra các sự cố va chạm ngoài ý muốn dẫn đến tổn thất về xe, khách thuê sẽ chỉ bồi thường số tiền tối đa <span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-md">2.000.000đ</span> để sửa chữa xe (mức khấu trừ), nhà bảo hiểm sẽ hỗ trợ cho các chi phí phát sinh vượt mức khấu trừ.
								</p>
								
								<!-- Styled Table -->
								<div class="overflow-x-auto border border-slate-100 rounded-2xl shadow-sm mb-4">
									<table class="w-full text-left border-collapse text-xs md:text-sm">
										<thead>
											<tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold">
												<th class="p-3">Thiệt hại xe</th>
												<th class="p-3">Khách thanh toán</th>
												<th class="p-3">Bảo hiểm thanh toán</th>
											</tr>
										</thead>
										<tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
											<tr>
												<td class="p-3">&lt;= 2 triệu</td>
												<td class="p-3 text-rose-500">&lt;= 2 triệu</td>
												<td class="p-3 text-slate-500">0 triệu</td>
											</tr>
											<tr class="bg-slate-50/30">
												<td class="p-3">&gt; 2 triệu</td>
												<td class="p-3 text-rose-500 font-bold">2 triệu</td>
												<td class="p-3 text-brand-primary font-bold">298 triệu</td>
											</tr>
											<tr class="bg-slate-50 text-slate-500 italic text-[11px] md:text-xs">
												<td colspan="3" class="p-3">
													<i>Ví dụ minh họa:</i> Xe có sự cố tổn thất 300.000.000đ
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								
								<p class="text-xs text-slate-500 flex items-center gap-1.5 bg-slate-50 p-3 rounded-xl border border-slate-100">
									<Icon name="lucide:info" class="w-4 h-4 text-brand-primary flex-shrink-0" />
									<span>Lưu ý: Khách hàng cần thực hiện đầy đủ <strong>Quy trình xử lý</strong> khi xảy ra sự cố (<strong>Mục III</strong> bên dưới).</span>
								</p>
							</div>

							<!-- Section 3: Điều khoản -->
							<div class="border-t border-slate-100 pt-6">
								<h6 class="text-xs font-bold text-black uppercase tracking-wider border-l-4 border-brand-primary pl-3 mb-3">
									II. Điều khoản Bảo hiểm
								</h6>
								<ul class="list-disc pl-5 space-y-2 mb-4 text-slate-600 font-semibold">
									<li>Bảo hiểm vật chất xe: đâm va, hỏa hoạn, cháy nổ.</li>
									<li>Miễn phí cứu hộ tối đa 70km/vụ.</li>
									<li>Bảo hiểm thủy kích (khấu trừ 20% số tiền bảo hiểm, tối thiểu 3 triệu đồng).</li>
									<li>Mức khấu trừ: 2.000.000đ/vụ.</li>
								</ul>
								
								<div class="bg-slate-50 p-4 rounded-2xl space-y-2.5 border border-slate-100 text-xs text-slate-500">
									<p class="font-bold text-slate-700 flex items-center gap-1">
										<Icon name="lucide:help-circle" class="w-4 h-4 text-brand-primary" />
										<span>Giải thích Mức khấu trừ:</span>
									</p>
									<p>Mọi sự cố ngoài ý muốn trong phạm vi bảo hiểm, số tiền tối đa khách thuê thanh toán là <u class="font-bold text-slate-800">2 triệu đồng</u>, chưa bao gồm:</p>
									<ul class="list-none space-y-1 pl-1 text-[11px]">
										<li>• Giảm trừ bồi thường/chế tài theo Quy tắc của Nhà bảo hiểm</li>
										<li>• Thiệt hại do xe không cho thuê được trong thời gian sửa chữa nếu có</li>
										<li>• Các lí do chủ quan khác nằm ngoài phạm vi bảo hiểm</li>
									</ul>
									<div class="pt-3 border-t border-slate-200 mt-2 space-y-1.5">
										<p class="flex items-center gap-1.5">
											<Icon name="lucide:file-text" class="w-3.5 h-3.5 text-slate-400" />
											<span><i>Quy tắc bảo hiểm MIC:</i> Xem thêm tại <a class="text-brand-primary hover:underline font-bold" href="https://n1-cstg.mioto.vn/m/insurance/Quy-tac-MIC-2507.pdf" target="_blank"><u>liên kết</u></a></span>
										</p>
										<p class="flex items-center gap-1.5">
											<Icon name="lucide:file-text" class="w-3.5 h-3.5 text-slate-400" />
											<span><i>Quy tắc bảo hiểm DBV (VNI):</i> Xem thêm tại <a class="text-brand-primary hover:underline font-bold" href="https://n1-cstg.mioto.vn/m/insurance/Quy-tac-VNI-2507.pdf" target="_blank"><u>liên kết</u></a></span>
										</p>
									</div>
								</div>
							</div>

							<!-- Section 4: Quy trình xử lý -->
							<div class="border-t border-slate-100 pt-6">
								<h6 class="text-xs font-bold text-black uppercase tracking-wider border-l-4 border-brand-primary pl-3 mb-3">
									III. Quy trình xử lý nếu xảy ra sự cố
								</h6>
								<ol class="space-y-4 text-slate-600 font-semibold">
									<li class="flex gap-3">
										<span class="w-5 h-5 rounded-full bg-brand-secondary text-brand-primary text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
										<span>Khách thuê <b class="text-slate-800">giữ nguyên hiện trường &amp; chụp ảnh xe đang bị sự cố.</b></span>
									</li>
									<li class="flex gap-3">
										<span class="w-5 h-5 rounded-full bg-brand-secondary text-brand-primary text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
										<span>Tại thời điểm xảy ra sự cố, khách thuê gọi đến trung tâm bồi thường của nhà bảo hiểm để đọc số hợp đồng bảo hiểm và làm theo hướng dẫn:
											<br />
											<span class="inline-flex flex-wrap gap-2 mt-1.5">
												<span class="bg-slate-100 text-slate-800 px-2.5 py-0.5 rounded-lg text-[10px] font-bold border border-slate-200">MIC: 1900 55 88 91</span>
												<span class="bg-slate-100 text-slate-800 px-2.5 py-0.5 rounded-lg text-[10px] font-bold border border-slate-200">DBV (VNI): 1900 96 96 90</span>
											</span>
										</span>
									</li>
									<li class="flex gap-3">
										<span class="w-5 h-5 rounded-full bg-brand-secondary text-brand-primary text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
										<span>Giám định viên bảo hiểm liên hệ khách thuê để hướng dẫn xử lý, xác minh thông tin và xác minh hiện trường (nếu có).</span>
									</li>
									<li class="flex gap-3">
										<span class="w-5 h-5 rounded-full bg-brand-secondary text-brand-primary text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
										<span>Giám định viên và chủ xe/khách thuê cùng mang xe ra Garage để giám định thiệt hại và ra <b class="text-slate-800">Báo giá sửa chữa.</b></span>
									</li>
									<li class="flex gap-3">
										<span class="w-5 h-5 rounded-full bg-brand-secondary text-brand-primary text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">5</span>
										<span>Trung tâm bồi thường của nhà bảo hiểm ra <b class="text-slate-800">Biên bản giám định</b> thiệt hại.</span>
									</li>
									<li class="flex gap-3">
										<span class="w-5 h-5 rounded-full bg-brand-secondary text-brand-primary text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">6</span>
										<span>Garage tiến hành sửa chữa theo báo giá.</span>
									</li>
								</ol>
							</div>
						</div>

						<!-- Footer -->
						<div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 flex-shrink-0">
							<button @click="isModalOpen = false" type="button"
								class="px-5 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors duration-200">
								Đóng
							</button>
							<button @click="isModalOpen = false" type="button"
								class="px-5 py-2 bg-brand-primary hover:bg-opacity-95 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-primary/10 transition-all duration-200">
								Đã hiểu
							</button>
						</div>
					</div>
				</div>
			</Transition>
		</Teleport>
	</section>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const isModalOpen = ref(false)
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
	width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
	background: #f1f5f9;
	border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
	background: #cbd5e1;
	border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
	background: #94a3b8;
}

/* ANIMATION TRANSITION */
.modal-fade-enter-active,
.modal-fade-leave-active {
	transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-fade-enter-active .modal-content,
.modal-fade-leave-active .modal-content {
	transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
	opacity: 0;
}

.modal-fade-enter-from .modal-content,
.modal-fade-leave-to .modal-content {
	opacity: 0;
	transform: scale(0.95) translateY(12px);
}
</style>
