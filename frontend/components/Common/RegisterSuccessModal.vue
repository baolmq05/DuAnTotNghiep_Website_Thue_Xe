<template>
  <div v-if="isShow" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeRegisterSuccess"></div>

    <!-- Modal Content -->
    <div class="relative bg-white w-full max-w-md rounded-3xl overflow-hidden shadow-2xl z-10 p-8 border border-slate-100 flex flex-col items-center text-center animate-scale-in">
      
      <!-- Success Icon -->
      <div class="w-16 h-16 rounded-full bg-brand-light flex items-center justify-center text-brand-primary mb-6 shadow-inner animate-bounce-slow">
        <Icon name="heroicons:check-badge" class="w-10 h-10 text-brand-primary" />
      </div>

      <!-- Title -->
      <h3 class="text-2xl font-black text-brand-dark mb-3">Đăng ký thành công!</h3>
      <p class="text-sm text-slate-500 mb-8 leading-relaxed">
        Tài khoản Drivio của bạn đã được khởi tạo thành công. Bạn có muốn cập nhật thêm thông tin cá nhân trong hồ sơ ngay bây giờ không?
      </p>

      <!-- Action Buttons -->
      <div class="grid grid-cols-2 gap-4 w-full">
        <!-- Close Button -->
        <button 
          @click="closeRegisterSuccess" 
          class="py-3 px-4 border border-slate-200 text-slate-500 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-700 transition-colors focus:outline-none text-sm"
        >
          Đóng
        </button>
        <!-- Update Profile Button -->
        <button 
          @click="handleGoToProfile" 
          class="py-3 px-4 bg-brand-primary hover:bg-brand-dark text-white font-bold rounded-xl transition-all duration-200 focus:outline-none text-sm shadow-md shadow-brand-primary/10"
        >
          Cập nhật thông tin
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const { isShow, closeRegisterSuccess } = useRegisterSuccessModal()

const handleGoToProfile = () => {
  closeRegisterSuccess()
  navigateTo('/profile')
}
</script>

<style scoped>
.animate-scale-in {
  animation: scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.animate-bounce-slow {
  animation: bounceSlow 2s infinite;
}

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(10px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@keyframes bounceSlow {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-6px);
  }
}
</style>
