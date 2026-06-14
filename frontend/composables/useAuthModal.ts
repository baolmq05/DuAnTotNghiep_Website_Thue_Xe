export const useAuthModal = () => {
  const isLoginOpen = useState('isLoginOpen', () => false)
  const isRegisterOpen = useState('isRegisterOpen', () => false)

  const openLogin = () => {
    isLoginOpen.value = true
    isRegisterOpen.value = false
    if (typeof document !== 'undefined') {
      document.body.style.overflow = 'hidden'
    }
  }

  const closeLogin = () => {
    isLoginOpen.value = false
    if (typeof document !== 'undefined' && !isRegisterOpen.value) {
      document.body.style.overflow = ''
    }
  }

  const openRegister = () => {
    isRegisterOpen.value = true
    isLoginOpen.value = false
    if (typeof document !== 'undefined') {
      document.body.style.overflow = 'hidden'
    }
  }

  const closeRegister = () => {
    isRegisterOpen.value = false
    if (typeof document !== 'undefined' && !isLoginOpen.value) {
      document.body.style.overflow = ''
    }
  }

  const switchToRegister = () => {
    isLoginOpen.value = false
    isRegisterOpen.value = true
  }

  const switchToLogin = () => {
    isRegisterOpen.value = false
    isLoginOpen.value = true
  }

  return {
    isLoginOpen,
    isRegisterOpen,
    openLogin,
    closeLogin,
    openRegister,
    closeRegister,
    switchToRegister,
    switchToLogin
  }
}
