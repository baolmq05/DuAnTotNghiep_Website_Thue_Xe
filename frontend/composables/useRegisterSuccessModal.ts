import { useState } from "#app";

export const useRegisterSuccessModal = () => {
  const isShow = useState('reg_success_show', () => false);

  const openRegisterSuccess = () => {
    isShow.value = true;
  };

  const closeRegisterSuccess = () => {
    isShow.value = false;
  };

  return {
    isShow,
    openRegisterSuccess,
    closeRegisterSuccess
  };
};
