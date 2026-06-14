<template>
    <div v-if="isLoginOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeLogin"></div>

        <div class="relative bg-white w-full max-w-xl h-[600px] rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">

            <button @click="closeLogin"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="col-span-12 md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white h-full">

                <div class="mb-8">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight text-center">Đăng nhập</h2>
                </div>

                <form @submit.prevent="handleLogin" class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Email</label>
                        <input v-model="email" type="email" placeholder="example@gmail.com" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Mật khẩu</label>
                            <a href="#" class="text-xs font-semibold text-[#286874] hover:underline">Quên mật khẩu?</a>
                        </div>
                        
                        <div class="relative w-full flex items-center">
                            <input v-model="password" :type="showPassword ? 'text' : 'password'" placeholder="••••••••" required
                                class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />

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

                    <button type="submit"
                        class="w-full mt-2 py-3.5 bg-[#286874] text-white font-bold rounded-xl shadow-lg shadow-cyan-900/10 hover:bg-[#1e4e57] hover:shadow-xl transition-all duration-200 text-sm">
                        Đăng Nhập
                    </button>
                </form>

                <div class="mt-6 text-center space-y-4">
                    <div class="relative flex items-center justify-center">
                        <div class="absolute inset-x-0 h-[1px] bg-slate-200"></div>
                        <span class="relative px-3 bg-white text-xs text-gray-400 uppercase tracking-wider font-medium">Hoặc kết nối qua</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <button class="flex items-center justify-center gap-2 py-2.5 border border-slate-200 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                                <path fill="#4285F4" d="M46.5 24c0-1.55-.15-3.24-.47-4.75H24v9h12.75c-.55 2.96-2.22 5.48-4.75 7.17l7.37 5.71C43.68 37.1 46.5 31.22 46.5 24z" />
                                <path fill="#FBBC05" d="M10.54 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.98-6.19z" />
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.37-5.71c-2.11 1.41-4.81 2.32-8.52 2.32-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                            </svg>
                            <span>Google</span>
                        </button>

                        <button class="flex items-center justify-center gap-2 py-2.5 border border-slate-200 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5">
                                <path fill="#3b5998" d="M48 24C48 10.75 37.25 0 24 0S0 10.75 0 24c0 11.98 8.78 21.9 20.25 23.7V30.94h-6.1V24h6.1v-5.29c0-6.01 3.58-9.34 9.07-9.34 2.63 0 5.38.47 5.38.47v5.91h-3.03c-2.98 0-3.91 1.85-3.91 3.75V24h6.66l-1.06 6.94h-5.6V47.7C39.22 45.9 48 35.98 48 24z" />
                            </svg>
                            <span>Facebook</span>
                        </button>
                    </div>
                </div>

                <div class="mt-8 text-center text-sm text-gray-600">
                    Bạn chưa có tài khoản?
                    <button type="button" @click="switchToRegister" class="text-[#286874] font-bold hover:underline ml-1 focus:outline-none">
                        Đăng ký ngay
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const { isLoginOpen, openLogin, closeLogin, switchToRegister } = useAuthModal()
const { login } = useAuth()
const { showToast } = useToast()

const email = ref('')
const password = ref('')

const openModal = openLogin
const closeModal = closeLogin

const showPassword = ref(false)
const togglePassword = () => {
    showPassword.value = !showPassword.value
}

const handleLogin = async () => {
    const res = await login({
        email: email.value,
        password: password.value
    })
    
    if (res.success) {
        showToast("Đăng nhập thành công!", "success")
        closeLogin()
        // Reset form
        email.value = ''
        password.value = ''
    } else {
        showToast(res.message || "Đăng nhập thất bại!", "error")
    }
}

defineExpose({ openModal, closeModal })
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.96) translateY(8px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>