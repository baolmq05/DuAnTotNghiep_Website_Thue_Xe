<template>
  <Transition
    enter-active-class="transition duration-200 ease-out"
    enter-from-class="opacity-0 scale-95 -translate-y-2"
    enter-to-class="opacity-100 scale-100 translate-y-0"
    leave-active-class="transition duration-150 ease-in"
    leave-from-class="opacity-100 scale-100 translate-y-0"
    leave-to-class="opacity-0 scale-95 -translate-y-2"
  >
    <div
      v-if="show"
      class="absolute right-0 top-14 z-[999] w-[420px] overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_20px_60px_rgba(0,0,0,.18)]"
    >
      <!-- Header -->
      <div
        class="bg-gradient-to-r from-brand-primary to-[#2a6873] px-6 py-5 text-white"
      >
        <div class="flex items-start justify-between">
          <div>
            <h2 class="text-xl font-bold">
              Thông báo
            </h2>

            <p class="mt-1 text-sm text-white/80">
              Bạn có
              <span class="font-bold text-white">
                {{ unreadCount }}
              </span>
              thông báo chưa đọc
            </p>
          </div>

          <button
            class="rounded-xl bg-white/15 px-3 py-2 text-xs font-semibold transition hover:bg-white/25"
            @click="markAllRead"
          >
            Đọc tất cả
          </button>
        </div>
      </div>

      <!-- Tabs -->
      <div class="border-b border-slate-100 bg-white px-4 py-3">
        <div class="flex gap-2">
          <button
            class="rounded-full px-4 py-2 text-sm font-semibold transition"
            :class="
              activeTab === 'all'
                ? 'bg-brand-primary text-white'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            "
            @click="activeTab = 'all'"
          >
            Tất cả
          </button>

          <button
            class="rounded-full px-4 py-2 text-sm font-semibold transition"
            :class="
              activeTab === 'unread'
                ? 'bg-brand-primary text-white'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            "
            @click="activeTab = 'unread'"
          >
            Chưa đọc
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="max-h-[220px] overflow-y-auto">

        <template v-if="filteredNotifications.length">

          <div
            v-for="notification in filteredNotifications"
            :key="notification.id"
            class="group cursor-pointer border-b border-slate-100 px-5 py-4 transition hover:bg-slate-50"
            @click="readNotification(notification)"
          >
            <div class="flex gap-4">

              <!-- Dot -->
              <div class="pt-2">
                <span
                  class="block h-3 w-3 rounded-full"
                  :class="
                    notification.is_read === '0'
                      ? 'bg-brand-primary'
                      : 'bg-slate-300'
                  "
                />
              </div>

              <!-- Content -->
              <div class="flex-1">

                <p
                  class="text-sm leading-6"
                  :class="
                    notification.is_read === '0'
                      ? 'font-semibold text-slate-800'
                      : 'text-slate-500'
                  "
                >
                  {{ notification.message }}
                </p>

                <div
                  class="mt-3 flex items-center justify-between"
                >
                  <span
                    class="text-xs text-slate-400"
                  >
                    {{ formatTimeAgo(notification.created_at) }}
                  </span>

                  <span
                    v-if="notification.is_read === '0'"
                    class="rounded-full bg-brand-primary/10 px-2 py-1 text-[11px] font-semibold text-brand-primary"
                  >
                    Mới
                  </span>

                  <span
                    v-else
                    class="text-[11px] text-slate-400"
                  >
                    Đã đọc
                  </span>
                </div>

              </div>

            </div>
          </div>

        </template>

        <!-- Empty -->
        <div
          v-else
          class="flex flex-col items-center justify-center py-16"
        >
          <div
            class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-100"
          >
            <Icon
              name="heroicons:bell-slash"
              class="h-10 w-10 text-slate-400"
            />
          </div>

          <h3 class="mt-5 text-lg font-bold text-slate-700">
            Chưa có thông báo
          </h3>

          <p
            class="mt-2 max-w-xs text-center text-sm text-slate-400"
          >
            Các thông báo về chuyến đi, thanh toán và hệ thống sẽ hiển thị tại đây.
          </p>
        </div>

      </div>

      <!-- Footer -->
      <div class="border-t border-slate-100 bg-white p-4">
        <NuxtLink
          to="/notifications"
          class="flex items-center justify-center rounded-2xl border border-brand-primary py-3 text-sm font-semibold text-brand-primary transition hover:bg-brand-primary hover:text-white"
        >
          Xem tất cả thông báo
        </NuxtLink>
      </div>

    </div>
  </Transition>
</template>
<script setup lang="ts">
import { computed, ref, watch } from "vue";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits<{
  (e: "close"): void;
}>();

const {
  notifications,
  unreadCount,
  readNotification,
  markAllRead,
  formatTimeAgo,
  fetchNotifications
} = useNotifications();

watch(() => props.show, (newVal) => {
  if (newVal) {
    fetchNotifications();
  }
});

/**
 * Tab hiện tại
 */
const activeTab = ref<"all" | "unread">("all");

/**
 * Filter theo tab
 */
const filteredNotifications = computed(() => {
  if (activeTab.value === "unread") {
    return notifications.value.filter(
      item => item.is_read === "0"
    );
  }

  return notifications.value;
});

const close = () => {
  emit("close");
};
</script>