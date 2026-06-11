
<template>
  <div class="profile-layout">
    <HeaderProfile />
    <main class="mt-[140px] pb-[80px]">
      <button
        type="button"
        class="mx-4 mb-4 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 lg:hidden"
        @click="isMobileSidebarOpen = true"
      >
        <Icon
          name="ic:outline-menu"
          size="20"
        />
        Menu tài khoản
      </button>

      <button
        v-if="isMobileSidebarOpen"
        type="button"
        class="fixed inset-0 z-50 bg-black/40 lg:hidden"
        aria-label="Đóng menu tài khoản"
        @click="closeMobileSidebar"
      />

      <SidebarProfile
        mobile
        :open="isMobileSidebarOpen"
        @close="closeMobileSidebar"
      />

      <div class="max-w-[1440px] mx-auto">
        <div class="grid grid-cols-12 min-h-screen">

          <!-- Sidebar -->
          <div class="hidden lg:block lg:col-span-4">
            <SidebarProfile />
          </div>

          <!-- Content -->
          <div class="col-span-12 lg:col-span-8 bg-[#F6F6F6] p-4 sm:p-6 lg:p-8">
            <slot />
          </div>

        </div>
      </div>
    </main>

    <Footer />
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