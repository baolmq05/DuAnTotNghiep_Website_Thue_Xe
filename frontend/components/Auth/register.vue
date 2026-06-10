<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal"></div>

    <div class="relative bg-white w-full max-w-xl h-[600px] rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">
      
      <button @click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-20 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <div class="col-span-12 md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white h-full overflow-y-auto">
        
        <div class="mb-6">
          <h2 class="text-2xl font-black text-gray-900 tracking-tight text-center">Tạo tài khoản</h2>
        </div>

        <form @submit.prevent="handleRegister" class="space-y-3.5">
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Họ và tên</label>
            <input type="text" placeholder="Nguyễn Văn A" required
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
          </div>

          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Email hoặc Số điện thoại</label>
            <input type="text" placeholder="example@gmail.com" required
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
          </div>

          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Mật khẩu</label>
            
            <div class="relative w-full flex items-center">
              <input :type="showPassword ? 'text' : 'password'" placeholder="••••••••" required
                     class="w-full pl-4 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
              
              <button type="button" @click="togglePassword" 
                      class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 z-10 p-1 focus:outline-none flex items-center justify-center transition-colors" 
                      aria-label="Thay đổi ẩn hiện mật khẩu">
                
                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M21 21l-18-18m18 18-3.321-3.321m3.321 3.321-1.42-1.417M17.5 17.5l-2.073-2.073M17.5 17.5A10.422 10.422 0 0 0 22.066 12c-1.292-4.338-5.31-7.5-10.066-7.5-1.956 0-3.794.53-5.378 1.458M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
              </button>
            </div>
          </div>

          <div class="flex items-start gap-2 pt-1">
            <input type="checkbox" id="tos_register" required class="mt-0.5 accent-[#286874]" />
            <label for="tos_register" class="text-xs text-gray-500 leading-normal">
              Tôi đồng ý với <a href="#" class="text-[#286874] font-semibold hover:underline">Điều khoản dịch vụ</a> của nền tảng.
            </label>
          </div>

          <button type="submit" 
                  class="w-full py-3.5 bg-[#286874] text-white font-bold rounded-xl shadow-lg shadow-cyan-900/10 hover:bg-[#1e4e57] hover:shadow-xl transition-all duration-200 text-sm">
            Đăng Ký Ngay
          </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
          Bạn đã có tài khoản rồi?
          <button type="button" @click="$emit('switch-to-login')" class="text-[#286874] font-bold hover:underline ml-1 focus:outline-none">
            Đăng nhập tại đây
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const isOpen = ref(false)
const emit = defineEmits(['switch-to-login'])

const showPassword = ref(false)
const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const openModal = () => {
  isOpen.value = true
  if (typeof document !== 'undefined') {
    document.body.style.overflow = 'hidden'
  }
}
const closeModal = () => {
  isOpen.value = false
  if (typeof document !== 'undefined') {
    document.body.style.overflow = ''
  }
}
const handleRegister = () => {
  console.log('Xử lý đăng ký...')
}

defineExpose({ openModal, closeModal })
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.96) translateY(8px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>