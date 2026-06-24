<template>
  <div v-if="isRegisterOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal"></div>

    <div
      class="relative bg-white w-full max-w-xl h-auto max-h-[90vh] rounded-3xl overflow-y-auto custom-scroll shadow-2xl z-10 animate-fade-in">
      
      <button @click="closeModal"
        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-20 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <div class="col-span-12 md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white h-full overflow-y-auto">

        <div v-if="isGoogleLoading" class="flex flex-col items-center justify-center space-y-4 py-12 animate-fade-in">
          <svg class="animate-spin h-12 w-12 text-[#286874]" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          <p class="text-sm font-semibold text-gray-600 tracking-wide">Đang xác thực tài khoản Google...</p>
        </div>

        <div v-else class="w-full">
          <div class="mb-6">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-center">Tạo tài khoản</h2>
          </div>

          <form @submit.prevent="handleRegister" class="space-y-3.5">
            <div class="space-y-1">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Họ và tên</label>
              <input v-model="name" type="text" placeholder="Nguyễn Văn A"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
              <p v-if="nameError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">{{ nameError }}</p>
            </div>

            <div class="space-y-1">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Email</label>
              <input v-model="email" type="email" placeholder="example@gmail.com"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
              <p v-if="emailError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">{{ emailError }}</p>
            </div>

            <div class="space-y-1">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Mật khẩu</label>

              <div class="relative w-full flex items-center">
                <input v-model="password" :type="showPassword ? 'text' : 'password'" placeholder="••••••••"
                  class="w-full pl-4 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
                <button type="button" @click="togglePassword"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 z-10 p-1 focus:outline-none flex items-center justify-center transition-colors"
                  aria-label="Thay đổi ẩn hiện mật khẩu">

                  <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>

                  <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M21 21l-18-18m18 18-3.321-3.321m3.321 3.321-1.42-1.417M17.5 17.5l-2.073-2.073M17.5 17.5A10.422 10.422 0 0 0 22.066 12c-1.292-4.338-5.31-7.5-10.066-7.5-1.956 0-3.794.53-5.378 1.458M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                </button>
              </div>
              <p v-if="passwordError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">
                {{ passwordError }}
              </p>
            </div>

            <div class="space-y-1">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Xác nhận mật khẩu</label>

              <div class="relative w-full flex items-center">
                <input v-model="confirmPassword" :type="showConfirmPassword ? 'text' : 'password'"
                  placeholder="••••••••"
                  class="w-full pl-4 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />

                <button type="button" @click="toggleConfirmPassword"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 z-10 p-1 focus:outline-none flex items-center justify-center transition-colors"
                  aria-label="Thay đổi ẩn hiện mật khẩu">

                  <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>

                  <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M21 21l-18-18m18 18-3.321-3.321m3.321 3.321-1.42-1.417M17.5 17.5l-2.073-2.073M17.5 17.5A10.422 10.422 0 0 0 22.066 12c-1.292-4.338-5.31-7.5-10.066-7.5-1.956 0-3.794.53-5.378 1.458M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                </button>
              </div>
              <p v-if="confirmPasswordError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">
                {{ confirmPasswordError }}
              </p>
            </div>

            <div class="flex flex-col gap-1 pt-1">
              <div class="flex items-start gap-2">
                <input type="checkbox" v-model="tosChecked" id="tos_register" class="mt-0.5 accent-[#286874]" />
                <label for="tos_register" class="text-xs text-gray-500 leading-normal">
                  Tôi đồng ý với <a href="#" class="text-[#286874] font-semibold hover:underline">Điều khoản dịch vụ</a>
                  của nền tảng.
                </label>
              </div>
              <p v-if="tosError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">{{ tosError }}</p>
            </div>

            <button type="submit"
              class="w-full py-3.5 bg-[#286874] text-white font-bold rounded-xl shadow-lg shadow-cyan-900/10 hover:bg-[#1e4e57] hover:shadow-xl transition-all duration-200 text-sm">
              Đăng Ký Ngay
            </button>
          </form>

          <div class="mt-6 text-center space-y-4">
            <div class="relative flex items-center justify-center">
              <div class="absolute inset-x-0 h-[1px] bg-slate-200"></div>
              <span class="relative px-3 bg-white text-xs text-gray-400 uppercase tracking-wider font-medium">Hoặc kết
                nối qua</span>
            </div>

            <div class="grid grid-cols-2 gap-3 items-center">
              <div class="flex justify-center items-center h-[44px]">
                <div id="googleRegisterButtonContainer"></div>
              </div>

              <button type="button"
                class="flex items-center justify-center gap-2 h-[44px] w-full border border-slate-200 rounded-full text-sm font-medium hover:bg-slate-50 transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5">
                  <path fill="#3b5998"
                    d="M48 24C48 10.75 37.25 0 24 0S0 10.75 0 24c0 11.98 8.78 21.9 20.25 23.7V30.94h-6.1V24h6.1v-5.29c0-6.01 3.58-9.34 9.07-9.34 2.63 0 5.38.47 5.38.47v5.91h-3.03c-2.98 0-3.91 1.85-3.91 3.75V24h6.66l-1.06 6.94h-5.6V47.7C39.22 45.9 48 35.98 48 24z" />
                </svg>
                <span class="text-gray-900 font-medium">Facebook</span>
              </button>
            </div>
          </div>

          <div class="mt-6 text-center text-sm text-gray-600">
            Bạn đã có tài khoản rồi?
            <button type="button" @click="switchToLogin"
              class="text-[#286874] font-bold hover:underline ml-1 focus:outline-none">
              Đăng nhập tại đây
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'

const { isRegisterOpen, openRegister, closeRegister, switchToLogin } = useAuthModal()
const { register } = useAuth()
const { showToast } = useToast()
const { openRegisterSuccess } = useRegisterSuccessModal()
// trạng thái loading khi xử lý đăng ký bằng Google để ẩn form cũ đi, tránh giật giao diện
const isGoogleLoading = ref(false)

const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const tosChecked = ref(false)

// khai báo các biến để hiển thị lỗi 
const nameError = ref('')
const emailError = ref('')
const passwordError = ref('')
const confirmPasswordError = ref('')
const tosError = ref('')

// hàm xóa lỗi cũ trước khi submit form mới, tránh lỗi cũ vẫn còn hiển thị
const clearErrors = () => {
  nameError.value = ''
  emailError.value = ''
  passwordError.value = ''
  confirmPasswordError.value = ''
  tosError.value = ''
}

const openModal = openRegister
// đóng modal và reset lại các giá trị form và lỗi cũ
const closeModal = () => {
  clearErrors()
  name.value = ''
  email.value = ''
  password.value = ''
  confirmPassword.value = ''
  tosChecked.value = false

  closeRegister()
}

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const togglePassword = () => {
  showPassword.value = !showPassword.value
}
const toggleConfirmPassword = () => {
  showConfirmPassword.value = !showConfirmPassword.value
}

// hàm giải mã JWT token lấy từ Google
function decodeJWT(token) {
  let base64Url = token.split(".")[1];
  let base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
  let jsonPayload = decodeURIComponent(
    atob(base64)
      .split("")
      .map(function (c) {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
      })
      .join("")
  );
  return JSON.parse(jsonPayload);
}

// callback xử lý khi nhấn nút Google bên giao diện Đăng ký
const handleGoogleRegisterResponse = async (response) => {
  // bắt đầu quá trình đăng ký bằng Google, bật trạng thái loading để ẩn form và tránh giật giao diện
  isGoogleLoading.value = true
  const responsePayload = decodeJWT(response.credential);

  try {
    const res = await $fetch('http://127.0.0.1:8000/api/auth/google', {
      method: 'POST',
      body: { token: response.credential }
    })

    if (res.success) {
      showToast(`Xin chào ${responsePayload.name}! Kết nối Google thành công.`, "success")

      if (typeof window !== 'undefined') {
        // lưu dữ liệu theo đúng chuẩn USER_TOKEN và USER_INFO của useAuth()
        localStorage.setItem("USER_TOKEN", res.access_token);
        document.cookie = `USER_TOKEN=${res.access_token}; path=/; max-age=${60 * 60 * 24 * 7};`;

        localStorage.setItem("USER_INFO", JSON.stringify(res.user));
        document.cookie = `USER_INFO=${encodeURIComponent(JSON.stringify(res.user))}; path=/; max-age=${60 * 60 * 24 * 7};`;
      }

      // đóng modal đăng ký và mở modal thành công
      setTimeout(() => {
        window.location.reload()
      }, 300) // Tải lại trang nhanh sau 0.3 giây
    } else {
      isGoogleLoading.value = false // Tắt loading nếu thất bại
      showToast(res.message || "Đăng ký bằng Google thất bại!", "error")
    }
  } catch (error) {
    isGoogleLoading.value = false // Tắt loading nếu lỗi kết nối
    console.error(error)
    showToast("Không thể kết nối đến máy chủ xác thực!", "error")
  }
}

// gắn callback riêng của màn hình Register lên window toàn cục
if (typeof window !== 'undefined') {
  window.handleGoogleRegisterResponse = handleGoogleRegisterResponse
}

// hàm vẽ nút Google chính chủ riêng cho Form Đăng ký
const initGoogleRegister = () => {
  if (typeof window !== 'undefined' && window.google) {
    window.google.accounts.id.initialize({
      client_id: "185759480527-40i2hmmvf7u25fbtsa32jmbbhtje8i3v.apps.googleusercontent.com",
      callback: window.handleGoogleRegisterResponse,
      auto_prompt: false
    });

    const container = document.getElementById("googleRegisterButtonContainer");
    if (container) {
      window.google.accounts.id.renderButton(container, {
        type: "standard",
        theme: "outline",
        size: "large",
        text: "signup_with",
        shape: "pill",
        width: 220,
        logo_alignment: "left"
      });
    }
  }
}

// lắng nghe trạng thái đóng/mở của Modal thông qua Watcher để khởi tạo nút Google khi cần thiết
watch(() => isRegisterOpen.value, async (isOpen) => {
  if (isOpen && !isGoogleLoading.value) {
    await nextTick();
    setTimeout(() => {
      initGoogleRegister();
    }, 150);
  }
}, {
  immediate: true
});

const handleRegister = async () => {
  clearErrors() // Xóa lỗi cũ
  let isErrors = false

  if (!name.value) {
    nameError.value = 'Họ và tên không được để trống'
    isErrors = true
  }

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

  if (!confirmPassword.value) {
    confirmPasswordError.value = 'Vui lòng xác nhận lại mật khẩu'
    isErrors = true
  } else if (password.value !== confirmPassword.value) {
    confirmPasswordError.value = 'Mật khẩu xác nhận không trùng khớp'
    isErrors = true
  }

  if (!tosChecked.value) {
    tosError.value = 'Bạn phải đồng ý với điều khoản dịch vụ để tiếp tục'
    isErrors = true
  }

  if (isErrors) {
    return
  }

  const res = await register({
    name: name.value,
    email: email.value,
    password: password.value,
    confirm_password: confirmPassword.value
  })

  if (res.success) {
    closeModal()
    openRegisterSuccess()
    name.value = ''
    email.value = ''
    password.value = ''
    confirmPassword.value = ''
    tosChecked.value = false
  } else {
    // Xử lý lỗi từ API và hiển thị thông báo lỗi tương ứng
    if (res.errors) {
      if (res.errors.email) {
        emailError.value = res.errors.email[0]
      }
      if (res.errors.name) nameError.value = res.errors.name[0]
      if (res.errors.password) passwordError.value = res.errors.password[0]
    } else {
      showToast(res.message || "Đăng ký thất bại!", "error")
    }
  }
}

defineExpose({ openModal, closeModal })
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.custom-scroll::-webkit-scrollbar {
  display: none;
}

.custom-scroll {
  -ms-overflow-style: none;
  /* IE and Edge */
  scrollbar-width: none;
  /* Firefox */
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