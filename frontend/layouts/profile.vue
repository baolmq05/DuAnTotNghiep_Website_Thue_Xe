<template>
  <div class="profile-layout">
    <HeaderProfile />
    <main class="mt-[90px] sm:mt-[100px] lg:mt-[140px] pb-[80px] max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
      <button type="button"
        class="w-full mt-2 mb-4 flex items-center justify-between gap-2.5 rounded-2xl bg-white px-4 py-3 text-sm font-bold text-[#1e4e57] shadow-xs border border-[#1e4e57]/20 hover:bg-[#1e4e57]/5 transition-all lg:hidden"
        @click="isMobileSidebarOpen = true">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-[#1e4e57]/10 flex items-center justify-center text-[#1e4e57]">
            <Icon name="lucide:layout-grid" class="w-4.5 h-4.5" />
          </div>
          <span>Danh mục tài khoản</span>
        </div>
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-slate-400" />
      </button>

      <button v-if="isMobileSidebarOpen" type="button" class="fixed inset-0 z-50 bg-black/40 lg:hidden"
        aria-label="Đóng menu tài khoản" @click="closeMobileSidebar" />

      <SidebarProfile mobile :open="isMobileSidebarOpen" @close="closeMobileSidebar" />

      <div class="max-w-[1440px] mx-auto">
        <div class="grid grid-cols-12 min-h-screen">

          <!-- Sidebar -->
          <div class="hidden lg:block lg:col-span-4">
            <SidebarProfile />
          </div>

          <!-- Content -->
          <div class="col-span-12 lg:col-span-8 bg-[#F6F6F6] p-2.5 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl">
            <slot />
          </div>

        </div>
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
const isMobileSidebarOpen = ref(false)

const closeMobileSidebar = () => {
  isMobileSidebarOpen.value = false
}

import HeaderProfile from '~/components/Profile/HeaderProfile.vue'
import SidebarProfile from '~/components/Profile/SidebarProfile.vue'
import Footer from '~/components/footer.vue'
</script>
<style>
.profile-layout {
  font-family: 'Inter', sans-serif;
}

.profile-layout *,
.profile-layout *::before,
.profile-layout *::after {
  box-sizing: border-box;
}
</style>