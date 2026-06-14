import { useState } from "#app";

export const useToast = () => {
  const isShow = useState('toast_show', () => false);
  const message = useState('toast_message', () => '');
  const type = useState<'success' | 'error' | 'info'>('toast_type', () => 'success');

  const showToast = (msg: string, toastType: 'success' | 'error' | 'info' = 'success') => {
    message.value = msg;
    type.value = toastType;
    isShow.value = true;
    setTimeout(() => {
      isShow.value = false;
    }, 4000);
  };

  return {
    isShow,
    message,
    type,
    showToast
  };
};
