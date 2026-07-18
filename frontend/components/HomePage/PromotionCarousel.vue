<template>
  <section class="max-w-7xl mx-auto px-4 py-12 w-full">
    <!-- Tiêu đề -->
    <div class="text-center mb-10">
      <h2 class="text-3xl font-bold text-slate-900">Chương trình khuyến mãi</h2>

      <p class="mt-3 text-slate-500">
        Khám phá những ưu đãi hấp dẫn khi thuê xe tại Drivio
      </p>
    </div>

    <!-- Slider -->
    <div v-if="promotions.length > 0" class="relative">
      <!-- Prev -->
      <button
        v-if="promotions.length > itemsPerView"
        @click="scrollLeft"
        :disabled="currentIndex === 0"
        class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white shadow-lg items-center justify-center disabled:opacity-30"
      >
        &lt;
      </button>

      <!-- View -->
      <div
        class="overflow-hidden w-full"
        @touchstart="handleTouchStart"
        @touchend="handleTouchEnd"
      >
        <div
          class="flex w-full transition-transform duration-500 ease-in-out"
          :class="{ 'justify-center': promotions.length < itemsPerView }"
          :style="{
            transform: `translateX(-${currentIndex * (100 / itemsPerView)}%)`,
          }"
        >
          <!-- Banner -->
          <div
            v-for="promo in promotions"
            :key="promo.id"
            class="w-full sm:w-1/2 lg:w-1/3 flex-shrink-0 px-3"
          >
            <div
              class="rounded-3xl overflow-hidden shadow hover:shadow-xl transition duration-300 cursor-pointer group bg-white relative"
              @click="openModal(promo)"
            >
              <img
                :src="promo.image"
                class="w-full h-[220px] object-cover group-hover:scale-105 transition duration-500"
              />

              <div
                class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent"
              ></div>

              <div class="absolute inset-x-0 bottom-0 p-5 text-white space-y-2">
                <div
                  class="flex items-center gap-2 text-xs font-medium text-white/80"
                >
                  <Icon name="ri:time-line" class="w-4 h-4" />
                  <span
                    >{{ formatDate(promo.startDate) }} -
                    {{ formatDate(promo.endDate) }}</span
                  >
                </div>

                <h3 class="text-lg font-bold leading-6">
                  {{ promo.name }}
                </h3>

                <p
                  class="text-sm text-white/85 leading-6"
                  style="
                    line-clamp: 2;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                  "
                >
                  {{ promo.description }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Next -->
      <button
        v-if="promotions.length > itemsPerView"
        @click="scrollRight"
        :disabled="currentIndex >= promotions.length - itemsPerView"
        class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white shadow-lg items-center justify-center disabled:opacity-30"
      >
        ❯
      </button>
    </div>
    <div v-else>
      <p class="text-center text-slate-500">
        Không có chương trình khuyến mãi nào.
      </p>
    </div>
    <!-- Indicator -->
    <div v-if="promotions.length > itemsPerView" class="flex justify-center mt-6 gap-2">
      <button
        v-for="index in promotions.length - itemsPerView + 1"
        :key="index"
        @click="currentIndex = index - 1"
        class="rounded-full transition-all"
        :class="
          currentIndex === index - 1
            ? 'bg-blue-600 w-6 h-2'
            : 'bg-gray-300 w-2 h-2'
        "
      />
    </div>

    <!-- ==========================
            MODAL
    =========================== -->

    <Transition name="fade">
      <div
        v-if="showModal"
        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
        @click.self="closeModal"
      >
        <div
          class="bg-white rounded-[28px] overflow-hidden shadow-2xl max-h-[90vh] overflow-y-auto w-full max-w-xl no-scrollbar relative"
        >
          <!-- Close -->
          <div class="flex justify-end p-4 absolute top-1 right-2 z-10">
            <button
              @click="closeModal"
              class="rounded-full bg-white flex items-center justify-center"
            >
              <Icon
                name="ri:close-circle-fill"
                style="color: red"
                class="w-7 h-7"
              />
            </button>
          </div>

          <!-- Banner -->
          <img
            :src="selectedPromotion?.image"
            class="w-full h-full object-cover relative top-0"
          />
          <!-- Nội dung -->
          <div class="p-7">
            <h2 class="text-3xl font-bold">
              {{ selectedPromotion?.name }}
            </h2>

            <div class="mt-6 whitespace-pre-line leading-8 text-slate-600">
              {{ selectedPromotion?.description }}
            </div>

            <!-- Thông tin -->
            <div class="mt-8 space-y-4">
              <div class="flex justify-between border-b pb-3">
                <span class="text-slate-500"> Mã giảm giá </span>

                <span class="font-bold text-red-500">
                  {{ selectedPromotion?.code }}
                </span>
              </div>

              <div class="flex justify-between border-b pb-3">
                <span class="text-slate-500"> Giá trị </span>

                <span class="font-semibold">
                  {{ getDiscountText(selectedPromotion) }}
                </span>
              </div>

              <div class="flex justify-between border-b pb-3">
                <span class="text-slate-500"> Thời gian </span>

                <span>
                  {{ formatDate(selectedPromotion?.startDate) }}

                  -

                  {{ formatDate(selectedPromotion?.endDate) }}
                </span>
              </div>

              <div class="flex justify-between border-b pb-3">
                <span class="text-slate-500"> Giới hạn </span>

                <span>
                  {{ selectedPromotion?.usageLimit }}
                </span>
              </div>

              <div class="flex justify-between">
                <span class="text-slate-500"> Mỗi người </span>

                <span>
                  {{ selectedPromotion?.perUserLimit }}
                </span>
              </div>
            </div>

            <!-- Copy -->
            <button
              @click="copyCode(selectedPromotion.code)"
              class="mt-8 w-full bg-brand-primary hover:opacity-60 text-white py-4 rounded-2xl font-semibold transition"
            >
              Sao chép mã giảm giá
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </section>
  <LoadingOverlay :loading="loading" :text="text" />
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";
import { promotionService } from "~/services/promotion.service";
import LoadingOverlay from "~/components/Common/LoadingOverlay.vue";

const { showToast } = useToast();
/* ===========================
      STATE
=========================== */

const promotions = ref<any[]>([]);
const selectedPromotion = ref<any>(null);
const showModal = ref(false);

const loading = ref(false);
const text = ref("Đang tải dữ liệu");
/* ===========================
      FETCH API
=========================== */

const fetchPromotions = async () => {
  try {
    loading.value = true;
    const res = await promotionService.getPromotions();

    if (res.success && Array.isArray(res.data)) {
      promotions.value = res.data
        .filter((item: any) => item.status == "1")
        .map((item: any) => ({
          id: item.id,

          image:
            item.images?.length > 0
              ? item.images[0].image_url
              : "https://images.unsplash.com/photo-1517841905240-472988babdf9",

          name: item.name,
          description: item.description,
          code: item.code,

          discountType: item.discount_type,
          discountValue: Number(item.discount_value),

          startDate: item.start_date,
          endDate: item.end_date,

          usageLimit: item.usage_limit,
          perUserLimit: item.per_user_limit,

          status: item.status,
        }));
    }
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

/* ===========================
      MODAL
=========================== */

const openModal = (promotion: any) => {
  selectedPromotion.value = promotion;
  showModal.value = true;

  document.body.style.overflow = "hidden";
};

const closeModal = () => {
  showModal.value = false;
  selectedPromotion.value = null;

  document.body.style.overflow = "";
};

/* ===========================
      COPY CODE
=========================== */

const copyCode = async (code: string) => {
  try {
    await navigator.clipboard.writeText(code);
    showToast("Đã sao chép mã giảm giá!", "success");
  } catch (e) {
    showToast("Lỗi khi sao chép mã giảm giá!", "error");
  }
};

/* ===========================
      FORMAT DATE
=========================== */

const formatDate = (date: string) => {
  if (!date) return "";

  return new Date(date).toLocaleDateString("vi-VN", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
};

/* ===========================
      FORMAT MONEY
=========================== */

const formatMoney = (money: number) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
    maximumFractionDigits: 0,
  }).format(money);
};

/* ===========================
      DISCOUNT TEXT
=========================== */

const getDiscountText = (promotion: any) => {
  if (!promotion) return "";

  if (promotion.discountType === "0") {
    return `Giảm ${promotion.discountValue}%`;
  }

  return `Giảm ${formatMoney(promotion.discountValue)}`;
};

/* ===========================
      SLIDER
=========================== */

const currentIndex = ref(0);

const itemsPerView = ref(3);

const updateItemsPerView = () => {
  if (window.innerWidth >= 1024) {
    itemsPerView.value = 3;
  } else if (window.innerWidth >= 640) {
    itemsPerView.value = 2;
  } else {
    itemsPerView.value = 1;
  }

  const max = Math.max(0, promotions.value.length - itemsPerView.value);

  if (currentIndex.value > max) {
    currentIndex.value = max;
  }
};

const scrollRight = () => {
  const max = Math.max(0, promotions.value.length - itemsPerView.value);

  if (currentIndex.value < max) {
    currentIndex.value++;
  }
};

const scrollLeft = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
  }
};

/* ===========================
      MOBILE SWIPE
=========================== */

const touchStartX = ref(0);
const touchEndX = ref(0);

const handleTouchStart = (e: TouchEvent) => {
  if (e.touches && e.touches[0]) {
    touchStartX.value = e.touches[0].clientX;
  }
};

const handleTouchEnd = (e: TouchEvent) => {
  if (e.changedTouches && e.changedTouches[0]) {
    touchEndX.value = e.changedTouches[0].clientX;

    const distance = touchStartX.value - touchEndX.value;

    if (Math.abs(distance) < 50) return;

    if (distance > 0) {
      scrollRight();
    } else {
      scrollLeft();
    }
  }
};

/* ===========================
      ESC CLOSE
=========================== */

const handleEsc = (e: KeyboardEvent) => {
  if (e.key === "Escape") {
    closeModal();
  }
};

/* ===========================
      LIFE CYCLE
=========================== */

onMounted(async () => {
  await fetchPromotions();

  updateItemsPerView();

  window.addEventListener("resize", updateItemsPerView);

  window.addEventListener("keydown", handleEsc);
});

onUnmounted(() => {
  window.removeEventListener("resize", updateItemsPerView);

  window.removeEventListener("keydown", handleEsc);
});
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.zoom-enter-active,
.zoom-leave-active {
  transition: all 0.25s ease;
}

.zoom-enter-from {
  opacity: 0;
  transform: scale(0.95);
}

.zoom-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>
