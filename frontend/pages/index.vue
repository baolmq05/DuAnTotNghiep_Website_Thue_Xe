<template>
	<div class="min-h-screen flex flex-col bg-white overflow-x-hidden">
		<!-- Hero Banner -->
		<HomePageHero />

		<!-- Why Choose Us / Benefits -->
		<HomePageBenefits />

		<!-- Promotions -->
		<HomePagePromotionCarousel />

		<!-- Featured Locations -->
		<HomePageFeaturedLocations />

		<!-- Insurance -->
		<HomePageInsurance />

		<!-- Book The Car Steps -->
		<HomePageBookTheCar />

		<!-- About Drivio -->
		<HomePageAbout />

		<!-- Register Owner -->
		<HomePageRegisterOwner />

		<!-- Blog -->
		<HomePageBlog />
	</div>
</template>

<script lang="ts" setup>
useHead({
	title: 'DRIVIO - Dịch vụ chia sẻ xe tự lái cao cấp hàng đầu Việt Nam',
	meta: [
		{ name: 'description', content: 'Đặt xe tự lái 4 chỗ, 7 chỗ, xe điện, xe bán tải và xe hạng sang nhanh chóng với giá cả ưu đãi. Đảm bảo thủ tục tiện lợi, hỗ trợ chu đáo.' }
	]
})
</script>

<style scoped></style>