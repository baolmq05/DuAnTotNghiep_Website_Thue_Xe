<template>
  <div class="profile-no-sidebar-layout">
    <HeaderProfile />
    <main class="mt-[140px] pb-[80px] max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-[#F6F6F6] rounded-3xl p-4 sm:p-6 lg:p-8">
        <slot />
      </div>
    </main>
    <Footer />
    <AuthLogin />
    <AuthRegister />
    <AuthForgotPassword />
    <CommonToast />
    <CommonRegisterSuccessModal />
  </div>
</template>

<script lang="ts" setup>
import HeaderProfile from '~/components/Profile/HeaderProfile.vue'
import Footer from '~/components/footer.vue'
</script>

<style>
.profile-no-sidebar-layout {
  font-family: 'Inter', sans-serif;
}

.profile-no-sidebar-layout *,
.profile-no-sidebar-layout *::before,
.profile-no-sidebar-layout *::after {
  box-sizing: border-box;
}
</style>
