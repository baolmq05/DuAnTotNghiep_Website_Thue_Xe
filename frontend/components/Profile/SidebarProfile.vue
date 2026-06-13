<script setup lang="ts">
const route = useRoute();

const props = withDefaults(
  defineProps<{
    mobile?: boolean;
    open?: boolean;
  }>(),
  {
    mobile: false,
    open: false,
  }
);

const emit = defineEmits<{
  close: [];
}>();

const mainMenus = [
  {
    title: "Tài khoản của tôi",
    icon: "ic:outline-person",
    href: "/profile",
  },
  {
    title: "Quản lý cho thuê",
    icon: "ic:outline-directions-car",
    href: "/profile/rental",
  },
  {
    title: "Xe yêu thích",
    icon: "ic:outline-favorite-border",
    href: "/profile/favorite",
  },
  {
    title: "Chuyến của tôi",
    icon: "ic:outline-work-history",
    href: "/profile/my-trips",
  },
  {
    title: "Đơn hàng Thuê xe dài hạn",
    icon: "ic:outline-receipt-long",
    href: "/profile/orders",
  },
  {
    title: "Quà tặng",
    icon: "ic:outline-card-giftcard",
    href: "/profile/gifts",
  },
  {
    title: "Địa chỉ của tôi",
    icon: "ic:outline-location-on",
    href: "/profile/address",
  },
];

const settingMenus = [
  {
    title: "Chính sách bảo vệ dữ liệu",
    icon: "ic:outline-security",
    href: "/profile/privacy-policy",
  },
  {
    title: "Đổi mật khẩu",
    icon: "ic:outline-lock",
    href: "/profile/change-password",
  },
  {
    title: "Yêu cầu xoá tài khoản",
    icon: "ic:outline-delete-outline",
    href: "/profile/delete-account",
  },
];

const isActive = (path: string) => {
  return route.path === path;
};

const logout = () => {
  console.log("logout");
  if (props.mobile) {
    emit("close");
  }
};

const onNavigate = () => {
  if (props.mobile) {
    emit("close");
  }
};

const asideClass = computed(() => {
  if (props.mobile) {
    return [
      "fixed inset-y-0 left-0 z-[60] w-[85vw] max-w-[340px] bg-white border-r border-gray-200 overflow-y-auto shadow-2xl transition-transform duration-300",
      props.open ? "translate-x-0" : "-translate-x-full",
    ];
  }

  return "w-full h-full bg-white border-r border-gray-200 sticky top-[140px]";
});
</script>

<template>
  <aside :class="asideClass">
    <div class="px-2 py-1">
      <div
        v-if="mobile"
        class="mb-6 flex items-right justify-end"
      >
        <button
          type="button"
          class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
          @click="emit('close')"
        >
          <Icon
            name="ic:outline-close"
            size="24"
          />
        </button>
      </div>

      <!-- Title -->
      <h2
        class="font-bold leading-none mb-10"
        :class="mobile ? 'text-[28px]' : 'text-[40px]'"
      >
        Xin chào bạn!
      </h2>

      <!-- Main Menu -->
      <ul class="space-y-1">
        <li
          v-for="item in mainMenus"
          :key="item.href"
        >
          <NuxtLink
            :to="item.href"
            @click="onNavigate"
            class="group flex items-center gap-4 py-3 px-4 text-sm sm:text-lg transition-all duration-200"
            :class="
              isActive(item.href)
                ? 'border-l-4 border-[#5FCF86] font-medium text-black'
                : 'text-[#666] hover:bg-gray-50'
            "
          >
            <Icon
              :name="item.icon"
              size="24"
              class="shrink-0"
              :class="
                isActive(item.href)
                  ? 'text-black'
                  : 'text-gray-500 group-hover:text-black'
              "
            />

            <span>
              {{ item.title }}
            </span>
          </NuxtLink>
        </li>
      </ul>

      <!-- Divider -->
      <div class="border-t border-gray-200 my-6" />

      <!-- Settings -->
      <ul class="space-y-1">
        <li
          v-for="item in settingMenus"
          :key="item.href"
        >
          <NuxtLink
            :to="item.href"
            @click="onNavigate"
            class="group flex items-center gap-4 py-2 px-4 text-sm sm:text-lg text-[#666] hover:bg-gray-50 transition-all duration-200"
          >
            <Icon
              :name="item.icon"
              size="24"
              class="text-gray-500 group-hover:text-black"
            />

            <span>
              {{ item.title }}
            </span>
          </NuxtLink>
        </li>
      </ul>

      <!-- Divider -->
      <div class="border-t border-gray-200 my-6" />

      <!-- Logout -->
      <button
        class="flex items-center gap-4 px-4 py-2 text-sm sm:text-lg text-[#FF5A5F] hover:bg-red-50 rounded-lg transition-all"
        @click="logout"
      >
        <Icon
          name="ic:outline-logout"
          size="24"
        />

        <span>Đăng xuất</span>
      </button>
    </div>
  </aside>
</template>