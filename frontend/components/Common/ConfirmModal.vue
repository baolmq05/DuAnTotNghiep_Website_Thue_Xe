<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="show" class="fixed inset-0 z-[1000] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancel"></div>

        <!-- Modal content -->
        <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-6 shadow-2xl transition-all border border-slate-100">
          <div class="flex flex-col items-center text-center">
            <!-- Icon -->
            <div 
              class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl mb-4"
              :class="iconBgColor"
            >
              <Icon :name="iconName" class="h-7 w-7" :class="iconTextColor" />
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-slate-800">
              {{ title }}
            </h3>

            <!-- Message -->
            <p class="mt-3 text-sm leading-relaxed text-slate-500">
              {{ message }}
            </p>

            <!-- Actions -->
            <div class="mt-6 flex w-full gap-3">
              <button
                type="button"
                class="flex-1 rounded-xl bg-slate-100 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-200 focus:outline-none"
                @click="cancel"
              >
                {{ cancelText }}
              </button>
              
              <button
                type="button"
                class="flex-1 rounded-xl py-3 text-sm font-semibold text-white transition focus:outline-none shadow-sm"
                :class="confirmBtnColor"
                @click="confirm"
              >
                {{ confirmText }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: "Xác nhận",
  },
  message: {
    type: String,
    default: "Bạn có chắc chắn muốn thực hiện hành động này không?",
  },
  confirmText: {
    type: String,
    default: "Đồng ý",
  },
  cancelText: {
    type: String,
    default: "Hủy",
  },
  type: {
    type: String,
    default: "danger", // 'danger' | 'warning' | 'info' | 'success'
  },
});

const emit = defineEmits<{
  (e: "confirm"): void;
  (e: "cancel"): void;
  (e: "close"): void;
}>();

const confirm = () => {
  emit("confirm");
  emit("close");
};

const cancel = () => {
  emit("cancel");
  emit("close");
};

// Computeds for styling based on type
const iconName = computed(() => {
  switch (props.type) {
    case "danger": return "heroicons:exclamation-triangle";
    case "warning": return "heroicons:exclamation-circle";
    case "success": return "heroicons:check-circle";
    default: return "heroicons:information-circle";
  }
});

const iconBgColor = computed(() => {
  switch (props.type) {
    case "danger": return "bg-red-50";
    case "warning": return "bg-amber-50";
    case "success": return "bg-emerald-50";
    default: return "bg-blue-50";
  }
});

const iconTextColor = computed(() => {
  switch (props.type) {
    case "danger": return "text-red-500";
    case "warning": return "text-amber-500";
    case "success": return "text-emerald-500";
    default: return "text-blue-500";
  }
});

const confirmBtnColor = computed(() => {
  switch (props.type) {
    case "danger": return "bg-red-500 hover:bg-red-600";
    case "warning": return "bg-amber-500 hover:bg-amber-600";
    case "success": return "bg-emerald-500 hover:bg-emerald-600";
    default: return "bg-brand-primary hover:opacity-90";
  }
});
</script>
