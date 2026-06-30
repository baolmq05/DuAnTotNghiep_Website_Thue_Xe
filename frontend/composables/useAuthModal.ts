export const useAuthModal = () => {
  const isLoginOpen = useState('isLoginOpen', () => false)
  const isRegisterOpen = useState('isRegisterOpen', () => false)
  const isForgotPasswordOpen = useState('isForgotPasswordOpen', () => false)

  const openLogin = () => {
    isLoginOpen.value = true
    isRegisterOpen.value = false
    isForgotPasswordOpen.value = false
    if (typeof document !== 'undefined') {
      document.body.style.overflow = 'hidden'
    }
  }

  const closeLogin = () => {
    isLoginOpen.value = false
    if (typeof document !== 'undefined' && !isRegisterOpen.value && !isForgotPasswordOpen.value) {
      document.body.style.overflow = ''
    }
  }

  const openRegister = () => {
    isRegisterOpen.value = true
    isLoginOpen.value = false
    isForgotPasswordOpen.value = false
    if (typeof document !== 'undefined') {
      document.body.style.overflow = 'hidden'
    }
  }

  const closeRegister = () => {
    isRegisterOpen.value = false
    if (typeof document !== 'undefined' && !isLoginOpen.value && !isForgotPasswordOpen.value) {
      document.body.style.overflow = ''
    }
  }

  const openForgotPassword = () => {
    isForgotPasswordOpen.value = true
    isLoginOpen.value = false
    isRegisterOpen.value = false
    if (typeof document !== 'undefined') {
      document.body.style.overflow = 'hidden'
    }
  }

  const closeForgotPassword = () => {
    isForgotPasswordOpen.value = false
    if (typeof document !== 'undefined' && !isLoginOpen.value && !isRegisterOpen.value) {
      document.body.style.overflow = ''
    }
  }

  const switchToRegister = () => {
    isLoginOpen.value = false
    isForgotPasswordOpen.value = false
    isRegisterOpen.value = true
  }

  const switchToLogin = () => {
    isRegisterOpen.value = false
    isForgotPasswordOpen.value = false
    isLoginOpen.value = true
  }

  const switchToForgotPassword = () => {
    isLoginOpen.value = false
    isRegisterOpen.value = false
    isForgotPasswordOpen.value = true
  }

  return {
    isLoginOpen,
    isRegisterOpen,
    isForgotPasswordOpen,
    openLogin,
    closeLogin,
    openRegister,
    closeRegister,
    openForgotPassword,
    closeForgotPassword,
    switchToRegister,
    switchToLogin,
    switchToForgotPassword
  }
}
