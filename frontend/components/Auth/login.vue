<template>
    <LoadingOverlay :loading="isLoading" :text="loadingText" />

    <div v-if="isLoginOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal"></div>

        <div
            class="relative bg-white w-full max-w-xl h-[600px] rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">

            <button @click="closeModal"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="col-span-12 md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white h-full">

                <div class="w-full">
                    <div class="mb-8">
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight text-center">Đăng nhập</h2>
                    </div>

                    <form @submit.prevent="handleLogin" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Email</label>
                            <input v-model="email" type="email" placeholder="example@gmail.com"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
                            <p v-if="emailError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">{{
                                emailError }}</p>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Mật khẩu</label>
                                <a href="#" @click.prevent="switchToForgotPassword" class="text-xs font-semibold text-[#286874] hover:underline">Quên mật
                                    khẩu?</a>
                            </div>

                            <div class="relative w-full flex items-center">
                                <input v-model="password" :type="showPassword ? 'text' : 'password'"
                                    placeholder="••••••••"
                                    class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />

                                <button type="button" @click="togglePassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 z-10 p-1 focus:outline-none flex items-center justify-center transition-colors"
                                    aria-label="Thay đổi ẩn hiện mật khẩu">

                                    <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>

                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M21 21l-18-18m18 18-3.321-3.321m3.321 3.321-1.42-1.417M17.5 17.5l-2.073-2.073M17.5 17.5A10.422 10.422 0 0 0 22.066 12c-1.292-4.338-5.31-7.5-10.066-7.5-1.956 0-3.794.53-5.378 1.458M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <p v-if="passwordError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">{{
                                passwordError }}</p>
                        </div>

                        <button type="submit"
                            class="w-full mt-2 py-3.5 bg-[#286874] text-white font-bold rounded-xl shadow-lg shadow-cyan-900/10 hover:bg-[#1e4e57] hover:shadow-xl transition-all duration-200 text-sm">
                            Đăng Nhập
                        </button>
                    </form>

                    <div class="mt-6 text-center space-y-4">
                        <div class="relative flex items-center justify-center">
                            <div class="absolute inset-x-0 h-[1px] bg-slate-200"></div>
                            <span
                                class="relative px-3 bg-white text-xs text-gray-400 uppercase tracking-wider font-medium">
                                Hoặc kết nối qua
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 items-center">
                            <div class="flex justify-center items-center h-[44px]">
                                <div id="googleButtonContainer"></div>
                            </div>

                            <button type="button" @click="loginWithFacebook"
                                class="flex items-center justify-center gap-2 h-[44px] w-full border border-slate-200 rounded-full text-sm font-medium hover:bg-slate-50 transition-colors focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5">
                                    <path fill="#3b5998"
                                        d="M48 24C48 10.75 37.25 0 24 0S0 10.75 0 24c0 11.98 8.78 21.9 20.25 23.7V30.94h-6.1V24h6.1v-5.29c0-6.01 3.58-9.34 9.07-9.34 2.63 0 5.38.47 5.38.47v5.91h-3.03c-2.98 0-3.91 1.85-3.91 3.75V24h6.66l-1.06 6.94h-5.6V47.7C39.22 45.9 48 35.98 48 24z" />
                                </svg>
                                <span class="text-gray-900 font-medium">Facebook</span>
                            </button>
                        </div>
                    </div>

                    <div class="mt-8 text-center text-sm text-gray-600">
                        Bạn chưa có tài khoản?
                        <button type="button" @click="switchToRegister"
                            class="text-[#286874] font-bold hover:underline ml-1 focus:outline-none">
                            Đăng ký ngay
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import LoadingOverlay from '@/components/Common/LoadingOverlay.vue'


// =====================================================================================
// 1. STATE & MODAL MANAGEMENT
// =====================================================================================
const { isLoginOpen, openLogin, closeLogin, switchToRegister, switchToForgotPassword } = useAuthModal()
const { login, loginWithGoogle: loginWithGoogleService, loginWithFacebook: loginWithFacebookService } = useAuth()
const { showToast } = useToast()

const isLoading = ref(false)
const loadingText = ref('')

const email = ref('')
const password = ref('')
const showPassword = ref(false)

const emailError = ref('')
const passwordError = ref('')

const clearErrors = () => {
    emailError.value = ''
    passwordError.value = ''
}

const openModal = openLogin
const closeModal = () => {
    clearErrors()
    email.value = ''
    password.value = ''
    isLoading.value = false // Tắt loading khi chủ động tắt modal
    closeLogin()
}
const togglePassword = () => {
    showPassword.value = !showPassword.value
}

// =====================================================================================
// 2. TRADITIONAL LOGIN
// =====================================================================================
const handleLogin = async () => {
    clearErrors()
    let isErrors = false

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!email.value.trim()) {
        emailError.value = 'Email không được để trống'
        isErrors = true
    } else if (!emailRegex.test(email.value)) {
        emailError.value = 'Email không đúng định dạng'
        isErrors = true
    }

    if (!password.value) {
        passwordError.value = 'Mật khẩu không được để trống'
        isErrors = true
    } else if (password.value.length < 6) {
        passwordError.value = 'Mật khẩu phải có ít nhất 6 ký tự'
        isErrors = true
    } else if (password.value.length > 16) {
        passwordError.value = 'Mật khẩu tối đa là 16 ký tự'
        isErrors = true
    }

    if (isErrors) return

    // Bật Loading khi xử lý form đăng nhập truyền thống
    loadingText.value = 'Đang đăng nhập vào hệ thống...'
    isLoading.value = true

    try {
        const res = await login({
            email: email.value,
            password: password.value
        })

        if (res.success) {
            showToast("Đăng nhập thành công!", "success")
            closeModal()
            email.value = ''
            password.value = ''
            setTimeout(() => { window.location.reload() }, 500)
        } else {
            if (res.errors) {
                if (res.errors.email) emailError.value = res.errors.email[0]
                if (res.errors.password) passwordError.value = res.errors.password[0]
            } else {
                showToast(res.message || "Tài khoản không tồn tại hoặc sai mật khẩu!", "error")
            }
        }
    } catch (error) {
        console.error(error)
        showToast("Có lỗi xảy ra trong quá trình đăng nhập!", "error")
    } finally {
        isLoading.value = false // Đảm bảo đóng loading
    }
}

// =====================================================================================
// 3. GOOGLE SIGN-IN LOGIN
// =====================================================================================
function decodeJWT(token) {
    let base64Url = token.split(".")[1];
    let base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    let jsonPayload = decodeURIComponent(
        atob(base64)
            .split("")
            .map(function (c) { return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2); })
            .join("")
    );
    return JSON.parse(jsonPayload);
}

// Hàm callback xử lý dữ liệu sau khi đăng nhập Google thành công
const handleCredentialResponse = async (response) => {
    // Kích hoạt overlay cho Google mượt mà
    loadingText.value = 'Đang xác thực tài khoản Google...'
    isLoading.value = true

    const responsePayload = decodeJWT(response.credential);

    try {
        const res = await loginWithGoogleService(response.credential);

        if (res.success) {
            showToast(`Xin chào ${responsePayload.name}! Đăng nhập thành công.`, "success")
            setTimeout(() => { window.location.reload() }, 300)
        } else {
            isLoading.value = false
            showToast(res.message || "Đăng nhập Google thất bại!", "error")
        }
    } catch (error) {
        isLoading.value = false
        console.error(error)
        showToast("Không thể kết nối đến máy chủ hoặc lỗi ghi Database!", "error")
    }
}

// Gắn callback Google toàn cục lên window
if (typeof window !== 'undefined') {
    window.handleCredentialResponse = handleCredentialResponse
}

// Hàm khởi tạo và hiển thị nút Google Sign-In chính chủ
const initGoogleSignIn = () => {
    if (typeof window !== 'undefined' && window.google) {
        window.google.accounts.id.initialize({
            client_id: "185759480527-40i2hmmvf7u25fbtsa32jmbbhtje8i3v.apps.googleusercontent.com",
            callback: window.handleCredentialResponse,
            auto_prompt: false
        });

        const container = document.getElementById("googleButtonContainer");
        if (container) {
            window.google.accounts.id.renderButton(container, {
                type: "standard",
                theme: "outline",
                size: "large",
                text: "signin_with",
                shape: "pill",
                width: 220,
                logo_alignment: "left"
            });
        }
    }
}

watch(() => isLoginOpen.value, async (isOpen) => {
    if (process.client && isOpen && !isLoading.value) {
        await nextTick();
        setTimeout(() => { 
            initGoogleSignIn(); 
        }, 200);
    }
}, { immediate: true });

// =====================================================================================
// 4. FACEBOOK SIGN-IN LOGIN
// =====================================================================================
function loginWithFacebook() {
    if (process.client && window.FB) {
        window.FB.login(function (response) {
            if (response.authResponse) {
                // Kích hoạt overlay cho Facebook mượt mà
                loadingText.value = 'Đang xác thực tài khoản Facebook...'
                isLoading.value = true

                const accessToken = response.authResponse.accessToken;
                loginWithFacebookService(accessToken)
                    .then((loginRes) => {
                        if (loginRes.success) {
                            showToast(`Xin chào ${loginRes.user?.name || ''}! Đăng nhập Facebook thành công.`, "success");
                            setTimeout(() => { window.location.reload(); }, 300);
                        } else {
                            isLoading.value = false
                            showToast(loginRes.message || "Đăng nhập Facebook thất bại!", "error");
                        }
                    })
                    .catch((error) => {
                        isLoading.value = false
                        console.error(error);
                        showToast("Đăng nhập bằng Facebook thất bại!", "error");
                    });
            } else {
                showToast("Người dùng đã hủy hoặc không cấp quyền đăng nhập.", "error");
            }
        }, { scope: 'public_profile,email' });
    } else {
        showToast("Facebook SDK chưa sẵn sàng. Vui lòng thử lại sau!", "error");
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