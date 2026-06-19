<template>
  <div class="min-h-screen bg-slate-50">

    <!-- Hero -->
<section
  class="relative overflow-hidden bg-brand-primary"
>

    <div
        class="relative mx-auto flex h-[420px] max-w-7xl flex-col items-center justify-center px-6 text-center text-white"
    >
        <div
            class="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-white/15 backdrop-blur-md"
        >
            <Icon
                name="heroicons:bell"
                class="h-10 w-10"
            />
        </div>

        <h1 class="text-5xl font-bold">
            Thông báo
        </h1>

        <p class="mt-5 max-w-3xl text-lg text-white/80">
            Theo dõi toàn bộ hoạt động của tài khoản, chuyến đi,
            thanh toán và các cập nhật mới nhất từ Drivio.
        </p>
    </div>
</section>

    <!-- Content -->
<div class="mx-auto mt-10 max-w-7xl px-4 pb-16">
      <!-- Statistics -->
      <div class="grid gap-6 md:grid-cols-3">

        <div class="rounded-3xl bg-white p-6 shadow-sm">
          <div class="flex items-center justify-between">

            <div>
              <p class="text-sm text-slate-500">
                Tổng thông báo
              </p>

              <h2 class="mt-2 text-3xl font-bold text-slate-800">
                {{ notifications.length }}
              </h2>
            </div>

            <div
              class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-primary/10"
            >
              <Icon
                name="heroicons:bell"
                class="h-7 w-7 text-brand-primary"
              />
            </div>

          </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
          <div class="flex items-center justify-between">

            <div>
              <p class="text-sm text-slate-500">
                Chưa đọc
              </p>

              <h2 class="mt-2 text-3xl font-bold text-brand-primary">
                {{ unreadCount }}
              </h2>
            </div>

            <div
              class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100"
            >
              <Icon
                name="heroicons:bell-alert"
                class="h-7 w-7 text-blue-600"
              />
            </div>

          </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
          <button
            class="flex w-full items-center justify-center gap-3 rounded-2xl bg-brand-primary py-4 font-semibold text-white transition hover:opacity-90"
            @click="markAllRead"
          >
            <Icon
              name="heroicons:check-badge"
              class="h-6 w-6"
            />

            Đánh dấu tất cả đã đọc
          </button>
        </div>

      </div>

      <!-- List -->
      <div class="mt-8 rounded-3xl bg-white shadow-sm">

        <!-- Header -->
        <div
          class="flex flex-col gap-4 border-b border-slate-100 p-6 md:flex-row md:items-center md:justify-between"
        >
          <div>
            <h2 class="text-xl font-bold text-slate-800">
              Danh sách thông báo
            </h2>

            <p class="mt-1 text-sm text-slate-500">
              Quản lý tất cả thông báo của bạn.
            </p>
          </div>

          <div class="flex gap-2">

            <button
              class="rounded-full px-5 py-2 text-sm font-semibold transition"
              :class="
                activeTab === 'all'
                  ? 'bg-brand-primary text-white'
                  : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
              "
              @click="activeTab='all'"
            >
              Tất cả
            </button>

            <button
              class="rounded-full px-5 py-2 text-sm font-semibold transition"
              :class="
                activeTab === 'unread'
                  ? 'bg-brand-primary text-white'
                  : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
              "
              @click="activeTab='unread'"
            >
              Chưa đọc
            </button>

          </div>
        </div>

        <!-- Notifications -->
        <div v-if="filteredNotifications.length">

          <div
            v-for="notification in filteredNotifications"
            :key="notification.id"
            class="cursor-pointer border-b border-slate-100 p-6 transition hover:bg-slate-50"
            @click="readNotification(notification)"
          >
            <div class="flex gap-5">

              <span
                class="mt-2 h-3 w-3 shrink-0 rounded-full"
                :class="
                  notification.is_read === '0'
                    ? 'bg-brand-primary'
                    : 'bg-slate-300'
                "
              />

              <div class="flex-1">

                <p
                  class="leading-7"
                  :class="
                    notification.is_read === '0'
                      ? 'font-semibold text-slate-800'
                      : 'text-slate-500'
                  "
                >
                  {{ notification.message }}
                </p>

                <div
                  class="mt-4 flex items-center justify-between"
                >
                  <span class="text-sm text-slate-400">
                    {{ formatTimeAgo(notification.created_at) }}
                  </span>

                  <span
                    v-if="notification.is_read==='0'"
                    class="rounded-full bg-brand-primary/10 px-3 py-1 text-xs font-semibold text-brand-primary"
                  >
                    Mới
                  </span>

                  <span
                    v-else
                    class="text-xs text-slate-400"
                  >
                    Đã đọc
                  </span>

                </div>

              </div>

            </div>
          </div>

        </div>

        <!-- Empty -->
        <div
          v-else
          class="py-24 text-center"
        >
          <div
            class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-100"
          >
            <Icon
              name="heroicons:bell-slash"
              class="h-12 w-12 text-slate-400"
            />
          </div>

          <h3 class="mt-6 text-xl font-bold text-slate-700">
            Chưa có thông báo
          </h3>

          <p class="mt-2 text-slate-400">
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