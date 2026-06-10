<template>
	<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
		<div class="max-w-7xl mx-auto">
			<h2 class="text-3xl text-center font-extrabold text-black tracking-tight mb-12">Bảo hiểm chuyến đi</h2>

			<div class="grid grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
				<!-- Cột 1: Ảnh A (Left on Desktop, Left on Mobile/Tablet) -->
				<div class="col-span-1 order-1 lg:order-1">
					<div
						class="overflow-hidden rounded-3xl w-full h-[180px] sm:h-[260px] lg:h-[350px] shadow-md border border-slate-100 hover:shadow-lg transition-all duration-300">
						<img class="w-full h-full object-cover rounded-3xl transform hover:scale-105 transition-transform duration-700"
							src="https://n1-cstg.mioto.vn/g/2026/05/09/09/6L6M2Z9U.jpg"
							alt="Bảo hiểm Drivio Banner A">
					</div>
				</div>

				<!-- Cột 3: Ảnh B (Right on Desktop, Right on Mobile/Tablet) -->
				<div class="col-span-1 order-2 lg:order-3">
					<div
						class="overflow-hidden rounded-3xl w-full h-[180px] sm:h-[260px] lg:h-[350px] shadow-md border border-slate-100 hover:shadow-lg transition-all duration-300">
						<img class="w-full h-full object-cover rounded-3xl transform hover:scale-105 transition-transform duration-700"
							src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=600&h=450&q=80"
							alt="Bảo hiểm Drivio Banner B">
					</div>
				</div>

				<!-- Cột 2: Logo container (Middle on Desktop, Below images on Mobile/Tablet) -->
				<div class="col-span-2 lg:col-span-1 order-3 lg:order-2 flex flex-col justify-center mt-2 lg:mt-0">
					<div class="grid grid-cols-4 lg:grid-cols-2 gap-4">
						<!-- Partner 1: MIC -->
						<div
							class="bg-white border border-slate-100 p-3 sm:p-4 rounded-2xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300 flex items-center justify-center h-16 sm:h-20 md:h-24 lg:h-[167px]">
							<img src="/mic.svg" alt="Bảo hiểm MIC"
								class="max-h-8 sm:max-h-12 max-w-[80%] object-contain" />
						</div>

						<!-- Partner 2: PVI -->
						<div
							class="bg-white border border-slate-100 p-3 sm:p-4 rounded-2xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300 flex items-center justify-center h-16 sm:h-20 md:h-24 lg:h-[167px]">
							<img src="/pvi.svg" alt="Bảo hiểm PVI"
								class="max-h-8 sm:max-h-12 max-w-[80%] object-contain" />
						</div>

						<!-- Partner 3: VNI -->
						<div
							class="bg-white border border-slate-100 p-3 sm:p-4 rounded-2xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300 flex items-center justify-center h-16 sm:h-20 md:h-24 lg:h-[167px]">
							<img src="/vni.svg" alt="Bảo hiểm VNI"
								class="max-h-8 sm:max-h-12 max-w-[80%] object-contain" />
						</div>

						<!-- Partner 4: BSH -->
						<div
							class="bg-white border border-slate-100 p-3 sm:p-4 rounded-2xl shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300 flex items-center justify-center h-16 sm:h-20 md:h-24 lg:h-[167px]">
							<img src="/bsh.svg" alt="Bảo hiểm BSH"
								class="max-h-8 sm:max-h-12 max-w-[80%] object-contain" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
