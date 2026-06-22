<template>
  <div class="chat-layout h-screen flex flex-col overflow-hidden bg-gray-50">
    <HeaderProfile />
    
    <!-- 
      HeaderProfile is absolute top-0, left-0, right-0.
      Its height is 80px (py-6 is 48px + content height ~32px).
      So we add pt-[80px] to push the content down.
      On mobile, HeaderProfile also adds a fixed bottom navigation of height 80px,
      so we add pb-[80px] on mobile (md:pb-0) to avoid content overlapping it.
    -->
    <main class="flex-1 flex flex-col min-h-0 pt-[80px] pb-[80px] md:pb-0 overflow-hidden">
      <div class="max-w-7xl mx-auto w-full h-full flex flex-col px-4 sm:px-6 lg:px-8 overflow-hidden">
        <slot />
      </div>
    </main>
    <AuthLogin />
    <AuthRegister />
    <CommonToast />
    <CommonRegisterSuccessModal />
  </div>
</template>

<script lang="ts" setup>
import HeaderProfile from '~/components/Profile/HeaderProfile.vue'
</script>

<style>
.chat-layout {
  font-family: 'Inter', sans-serif;
}

.chat-layout *,
.chat-layout *::before,
.chat-layout *::after {
  box-sizing: border-box;
}
</style>