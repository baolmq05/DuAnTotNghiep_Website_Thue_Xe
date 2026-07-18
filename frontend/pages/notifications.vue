<template>
  <div class="min-h-screen bg-slate-50">

    <!-- Hero -->
    <section class="relative overflow-hidden bg-brand-primary">

      <div
        class="relative mx-auto mt-5 flex h-[240px] max-w-4xl flex-col items-center justify-center px-6 text-center text-white">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-white/15 backdrop-blur-md">
          <Icon name="heroicons:bell" class="h-7 w-7" />
        </div>

        <h1 class="text-3xl font-bold">
          Thông báo
        </h1>

        <p class="mt-2 max-w-2xl text-sm text-white/80">
          Theo dõi toàn bộ hoạt động của tài khoản, chuyến đi,
          thanh toán và các cập nhật mới nhất từ Drivio.
        </p>
      </div>
    </section>

    <!-- Content -->
    <div class="mx-auto mt-8 max-w-4xl px-4 pb-16">
      <!-- Statistics -->
      <div class="grid gap-4 md:grid-cols-3">

        <div class="rounded-2xl bg-white p-4 shadow-sm">
          <div class="flex items-center justify-between">

            <div>
              <p class="text-xs text-slate-500">
                Tổng thông báo
              </p>

              <h2 class="mt-1 text-2xl font-bold text-slate-800">
                {{ notifications.length }}
              </h2>
            </div>

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-primary/10">
              <Icon name="heroicons:bell" class="h-5 w-5 text-brand-primary" />
            </div>

          </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-sm">
          <div class="flex items-center justify-between">

            <div>
              <p class="text-xs text-slate-500">
                Chưa đọc
              </p>

              <h2 class="mt-1 text-2xl font-bold text-brand-primary">
                {{ unreadCount }}
              </h2>
            </div>

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
              <Icon name="heroicons:bell-alert" class="h-5 w-5 text-blue-600" />
            </div>

          </div>
        </div>

        <div class="rounded-2xl bg-white p-4 shadow-sm flex items-center">
          <button
            class="flex w-full items-center justify-center gap-2 rounded-xl bg-brand-primary py-3 text-sm font-semibold text-white transition hover:opacity-90"
            @click="markAllRead">
            <Icon name="heroicons:check-badge" class="h-5 w-5" />

            Đánh dấu tất cả đã đọc
          </button>
        </div>

      </div>

      <!-- List -->
      <div class="mt-6 rounded-2xl bg-white shadow-sm">

        <!-- Header -->
        <div class="flex flex-col gap-3 border-b border-slate-100 p-4 md:flex-row md:items-center md:justify-between">
          <div>
            <h2 class="text-lg font-bold text-slate-800">
              Danh sách thông báo
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
              Quản lý tất cả thông báo của bạn.
            </p>
          </div>

          <div class="flex gap-2">

            <button class="rounded-full px-4 py-1.5 text-xs font-semibold transition" :class="activeTab === 'all'
                ? 'bg-brand-primary text-white'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
              " @click="activeTab = 'all'">
              Tất cả
            </button>

            <button class="rounded-full px-4 py-1.5 text-xs font-semibold transition" :class="activeTab === 'unread'
                ? 'bg-brand-primary text-white'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
              " @click="activeTab = 'unread'">
              Chưa đọc
            </button>

          </div>
        </div>

        <!-- Notifications -->
        <div v-if="filteredNotifications.length">

          <div v-for="notification in filteredNotifications" :key="notification.id"
            class="cursor-pointer border-b border-slate-100 p-4 transition hover:bg-slate-50"
            @click="readNotification(notification)">
            <div class="flex gap-3 items-start">

              <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full" :class="notification.is_read === '0'
                  ? 'bg-brand-primary'
                  : 'bg-slate-300'
                " />

              <div class="flex-1">

                <p class="text-sm leading-6" :class="notification.is_read === '0'
                    ? 'font-semibold text-slate-800'
                    : 'text-slate-500'
                  ">
                  {{ notification.message }}
                </p>

                <div class="mt-2 flex items-center justify-between">
                  <span class="text-xs text-slate-400">
                    {{ formatTimeAgo(notification.created_at) }}
                  </span>

                  <span v-if="notification.is_read === '0'"
                    class="rounded-full bg-brand-primary/10 px-2 py-0.5 text-[10px] font-semibold text-brand-primary">
                    Mới
                  </span>

                  <span v-else class="text-[10px] text-slate-400">
                    Đã đọc
                  </span>

                </div>

              </div>

            </div>
          </div>

        </div>

        <!-- Empty -->
        <div v-else class="py-16 text-center">
          <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
            <Icon name="heroicons:bell-slash" class="h-8 w-8 text-slate-400" />
          </div>

          <h3 class="mt-4 text-lg font-bold text-slate-700">
            Chưa có thông báo
          </h3>

          <p class="mt-1 text-sm text-slate-400">
            Mọi thông báo của bạn sẽ xuất hiện tại đây.
          </p>
        </div>

      </div>

    </div>

  </div>
</template>
<script setup lang="ts">
import { computed, ref, onMounted } from "vue";

definePageMeta({
  layout: "default",
});

useHead({
  title: "Thông báo",
});

const {
  notifications,
  unreadCount,
  readNotification,
  markAllRead,
  formatTimeAgo,
  fetchNotifications
} = useNotifications();

/**
 * Tab hiện tại
 */
const activeTab = ref<"all" | "unread">("all");

/**
 * Danh sách theo tab
 */
const filteredNotifications = computed(() => {
  if (activeTab.value === "unread") {
    return notifications.value.filter(
      item => item.is_read === "0"
    );
  }

  return notifications.value;
});

onMounted(() => {
  fetchNotifications();
});
</script>