import { useState } from "#app";
import { computed } from "vue";

export interface ToastItem {
  id: number;
  message: string;
  type: 'success' | 'error' | 'warning' | 'info';
  duration?: number;
}

export const useToast = () => {
  const toasts = useState<ToastItem[]>('global_toasts', () => []);

  // Backward-compatibility computed refs
  const isShow = computed(() => toasts.value.length > 0);
  const message = computed(() => toasts.value[toasts.value.length - 1]?.message || '');
  const type = computed(() => toasts.value[toasts.value.length - 1]?.type || 'success');

  const removeToast = (id: number) => {
    toasts.value = toasts.value.filter(t => t.id !== id);
  };

  const showToast = (msg: string, toastType: 'success' | 'error' | 'warning' | 'info' = 'success', duration = 4500) => {
    const id = Date.now() + Math.random();
    toasts.value.push({ id, message: msg, type: toastType, duration });

    setTimeout(() => {
      removeToast(id);
    }, duration);
  };

  return {
    toasts,
    isShow,
    message,
    type,
    showToast,
    removeToast
  };
};

