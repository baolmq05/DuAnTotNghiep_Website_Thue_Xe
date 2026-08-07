<template>
	<div class="min-h-screen flex flex-col bg-white overflow-x-hidden">
		<!-- Hero Banner -->
		<HomePageHero />

		<!-- View History -->
		<HomePageViewHistory />

		<!-- Why Choose Us / Benefits -->
		<HomePageBenefits />

		<!-- Promotions -->
		<HomePagePromotionCarousel />

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
useSeoMeta({
	title: 'DRIVIO — Thuê Xe Tự Lái Uy Tín, Giá Tốt Hàng Đầu Việt Nam',
	description: 'Thuê xe tự lái 4 chỗ, 7 chỗ, xe điện, xe hạng sang giá tốt tại DRIVIO. Đặt xe online nhanh chóng, bảo hiểm đầy đủ, giao xe tận nơi. Hỗ trợ 24/7.',
	keywords: 'thuê xe tự lái, thuê xe ô tô, đặt xe online, cho thuê xe, drivio, thuê xe giá rẻ, thuê xe Cần Thơ',
	ogTitle: 'DRIVIO — Nền Tảng Thuê Xe Tự Lái Hàng Đầu',
	ogDescription: 'Đặt xe tự lái đa dạng dòng xe, giá tốt, bảo hiểm toàn diện tại DRIVIO.',
	ogImage: '/images/og/home-banner.jpg',
	ogType: 'website',
	twitterCard: 'summary_large_image',
})

useHead({
	link: [
		{ rel: 'canonical', href: 'https://drivio.vn' }
	]
})
</script>

<style scoped></style>