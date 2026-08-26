<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 py-6">
    <!-- Banner -->
    <div class="relative h-[220px] rounded-3xl overflow-hidden flex items-center justify-center shadow-sm">
      <img
        src="https://res.cloudinary.com/djbobb5oe/image/upload/v1781623501/image-12-scaled_wllwfm.webp"
        alt="Banner địa chỉ"
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-[2px]"></div>

      <div class="relative z-10 text-center text-white px-6">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-2 tracking-tight">
          Sổ địa chỉ của tôi
        </h1>
        <p class="text-xs md:text-sm text-white/90 max-w-xl mx-auto font-medium">
          Lưu lại các địa chỉ quen thuộc (Nhà riêng, Cơ quan,...) để chọn nhanh 1-chạm khi đặt xe giao tận nơi.
        </p>
      </div>
    </div>

    <!-- Danh sách địa chỉ đã lưu -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
        <div>
          <h2 class="text-lg font-bold text-slate-800">
            Danh sách địa chỉ đã lưu
          </h2>
          <p class="text-xs text-slate-400 font-medium mt-0.5">
            Dùng để tự động tính khoảng cách và phí giao xe khi đặt chuyến
          </p>
        </div>

        <button
          v-if="!showAddForm"
          @click="openAddForm"
          class="flex items-center justify-center gap-1.5 px-4 py-2 bg-brand-primary hover:bg-brand-dark text-white text-xs font-semibold rounded-xl transition-all shadow-sm cursor-pointer"
        >
          <Icon name="lucide:plus" class="w-4 h-4" />
          <span>Thêm địa chỉ mới</span>
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="loading && savedAddresses.length === 0" class="text-center py-12 text-slate-400 text-xs">
        Đang tải danh sách địa chỉ...
      </div>

      <!-- Empty State -->
      <div
        v-else-if="savedAddresses.length === 0"
        class="text-center py-12 border border-dashed border-slate-200 rounded-xl bg-slate-50/50 flex flex-col items-center justify-center p-6"
      >
        <h3 class="text-sm font-semibold text-slate-700">Chưa có địa chỉ nào được lưu</h3>
        <p class="text-xs text-slate-400 mt-1 max-w-sm">
          Thêm địa chỉ nhà hoặc công ty để trải nghiệm đặt xe giao tận nơi nhanh chóng và thuận tiện hơn.
        </p>
        <button
          @click="openAddForm"
          class="mt-4 px-4 py-2 bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white rounded-xl text-xs font-semibold transition-all cursor-pointer"
        >
          + Thêm địa chỉ
        </button>
      </div>

      <!-- Address Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          v-for="(address, idx) in savedAddresses"
          :key="address.id"
          class="flex items-start justify-between bg-slate-50/70 hover:bg-white rounded-xl p-4 border border-slate-200/80 hover:border-brand-primary/30 transition-all group"
        >
          <div class="flex-1 min-w-0 pr-3">
            <span class="text-xs font-bold text-slate-700">Địa chỉ #{{ idx + 1 }}</span>
            <p class="text-xs text-slate-600 mt-1 leading-relaxed break-words font-medium">
              {{ address.address_name }}
            </p>
          </div>

          <div class="flex items-center gap-1 shrink-0">
            <button
              @click="editExistingAddress(address)"
              class="p-1.5 text-slate-400 hover:text-[#1e4e57] hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
              title="Chỉnh sửa"
            >
              <Icon name="lucide:edit-2" class="w-4 h-4" />
            </button>

            <button
              @click="confirmDeleteAddress(address.id)"
              class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
              title="Xóa"
            >
              <Icon name="lucide:trash-2" class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Thêm / Sửa địa chỉ (Tích hợp Goong Map Autocomplete) -->
    <div
      v-show="showAddForm"
      class="bg-white rounded-2xl p-6 shadow-md border border-slate-200 transition-all duration-300"
    >
      <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
        <div>
          <h2 class="text-base font-bold text-slate-800">
            {{ isEditMode ? "Cập nhật địa chỉ" : "Thêm địa chỉ mới" }}
          </h2>
          <p class="text-xs text-slate-400 font-medium mt-0.5">
            Nhập tên đường hoặc địa điểm để tìm kiếm địa chỉ chính xác
          </p>
        </div>
        <button
          @click="resetForm"
          class="p-1.5 hover:bg-slate-100 rounded-full transition-colors text-slate-400 hover:text-slate-600 cursor-pointer"
        >
          <Icon name="ic:outline-close" class="w-5 h-5" />
        </button>
      </div>

      <form @submit.prevent="handleFormSubmit" class="space-y-4">
        <!-- Ô Input có AutoComplete -->
        <div class="space-y-1.5 relative">
          <label class="block text-xs font-semibold text-slate-700">
            Địa chỉ <span class="text-rose-500">*</span>
          </label>

          <div class="relative">
            <input
              type="text"
              v-model="newAddress.address_name"
              @input="onAddressInput"
              @focus="onAddressInput"
              placeholder="Gõ số nhà, tên đường, tòa nhà hoặc địa danh..."
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-[#1e4e57] focus:bg-white focus:ring-2 focus:ring-[#1e4e57]/10 transition-all"
            />

            <button
              v-if="newAddress.address_name"
              type="button"
              @click="clearAddressInput"
              class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-200 transition-colors"
            >
              <Icon name="ic:outline-close" class="w-3.5 h-3.5" />
            </button>
          </div>

          <!-- Danh sách gợi ý từ Goong AutoComplete -->
          <div
            v-if="suggestions.length > 0"
            class="absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-56 overflow-y-auto divide-y divide-slate-100"
          >
            <div
              v-for="item in suggestions"
              :key="item.place_id"
              class="p-3 hover:bg-slate-50 cursor-pointer text-xs text-slate-700 transition-colors font-medium"
              @click="selectSuggestion(item)"
            >
              {{ item.description }}
            </div>
          </div>
        </div>

        <div class="flex gap-3 pt-3 border-t border-slate-100">
          <button
            type="button"
            @click="resetForm"
            class="flex-1 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition-all text-xs cursor-pointer"
          >
            Hủy
          </button>

          <button
            type="submit"
            :disabled="loading || !newAddress.address_name.trim()"
            class="flex-1 py-2.5 bg-[#1e4e57] hover:bg-[#286874] text-white font-semibold rounded-xl transition-all text-xs shadow-sm flex items-center justify-center gap-1.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span>{{ loading ? "Đang lưu..." : (isEditMode ? "Lưu thay đổi" : "Lưu địa chỉ") }}</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Confirm Delete Modal -->
    <CommonConfirmModal
      :show="showDeleteConfirm"
      title="Xóa địa chỉ"
      message="Bạn có chắc chắn muốn xóa địa chỉ này khỏi danh sách đã lưu không?"
      confirmText="Xóa"
      cancelText="Hủy"
      type="danger"
      @confirm="handleDeleteAddress"
      @close="showDeleteConfirm = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { addressService } from "~/services/address.service";

definePageMeta({
  layout: "profile",
});

const { user } = useAuth();
const { showToast } = useToast();

import { GOONG_API_KEY } from '~/constants/goong';

const loading = ref(false);
const showAddForm = ref(false);
const isEditMode = ref(false);
const editingId = ref<number | null>(null);

const savedAddresses = ref<any[]>([]);

const newAddress = ref({
  address_name: "",
});

const suggestions = ref<any[]>([]);
const isSearching = ref(false);
let debounceTimer: any = null;

const onAddressInput = () => {
  const query = newAddress.value.address_name.trim();
  if (!query || query.length < 2) {
    suggestions.value = [];
    return;
  }

  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(async () => {
    isSearching.value = true;
    try {
      const res = await fetch(
        `https://rsapi.goong.io/Place/AutoComplete?api_key=${GOONG_API_KEY}&input=${encodeURIComponent(query)}`
      );
      const data = await res.json();
      suggestions.value = data.predictions || [];
    } catch (err) {
      console.error("Lỗi tìm kiếm Goong AutoComplete:", err);
    } finally {
      isSearching.value = false;
    }
  }, 250);
};

const selectSuggestion = (item: any) => {
  newAddress.value.address_name = item.description;
  suggestions.value = [];
};

const clearAddressInput = () => {
  newAddress.value.address_name = "";
  suggestions.value = [];
};

const fetchAddresses = async () => {
  loading.value = true;
  try {
    const res = await addressService.getAddresses();
    if (res?.success) {
      savedAddresses.value = res.data || [];
    }
  } catch (err) {
    console.error(err);
    showToast("Lấy danh sách địa chỉ thất bại", "error");
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  if (!user.value) {
    showToast("Vui lòng đăng nhập để quản lý địa chỉ", "error");
    return;
  }
  await fetchAddresses();
});

const openAddForm = () => {
  resetForm();
  showAddForm.value = true;
};

const editExistingAddress = (address: any) => {
  isEditMode.value = true;
  editingId.value = address.id;
  newAddress.value = {
    address_name: address.address_name,
  };
  suggestions.value = [];
  showAddForm.value = true;
};

const resetForm = () => {
  isEditMode.value = false;
  editingId.value = null;
  newAddress.value = {
    address_name: "",
  };
  suggestions.value = [];
  showAddForm.value = false;
};

const handleFormSubmit = async () => {
  if (!user.value) {
    showToast("Vui lòng đăng nhập", "error");
    return;
  }

  const addressText = newAddress.value.address_name.trim();
  if (!addressText) {
    showToast("Vui lòng nhập địa chỉ", "error");
    return;
  }

  loading.value = true;
  try {
    if (isEditMode.value && editingId.value) {
      const res = await addressService.updateAddress(editingId.value, {
        address_name: addressText,
      });

      if (res?.success) {
        showToast("Cập nhật địa chỉ thành công", "success");
        resetForm();
        await fetchAddresses();
      } else {
        showToast(res?.message || "Cập nhật địa chỉ thất bại", "error");
      }
    } else {
      const res = await addressService.createAddress({
        address_name: addressText,
        user_id: user.value.id,
      });

      if (res?.success) {
        showToast("Thêm địa chỉ thành công", "success");
        resetForm();
        await fetchAddresses();
      } else {
        showToast(res?.message || "Thêm địa chỉ thất bại", "error");
      }
    }
  } catch (err: any) {
    console.error(err);
    showToast(
      err?.response?._data?.message || "Lưu địa chỉ thất bại",
      "error"
    );
  } finally {
    loading.value = false;
  }
};

const showDeleteConfirm = ref(false);
const addressToDelete = ref<number | null>(null);

const confirmDeleteAddress = (id: number) => {
  addressToDelete.value = id;
  showDeleteConfirm.value = true;
};

const handleDeleteAddress = async () => {
  if (addressToDelete.value === null) return;
  const id = addressToDelete.value;

  showDeleteConfirm.value = false;
  loading.value = true;

  try {
    const res = await addressService.deleteAddress(id);
    if (res?.success) {
      showToast("Xóa địa chỉ thành công", "success");
      await fetchAddresses();
    } else {
      showToast(res?.message || "Xóa địa chỉ thất bại", "error");
    }
  } catch (err) {
    console.error(err);
    showToast("Xóa địa chỉ thất bại", "error");
  } finally {
    loading.value = false;
    addressToDelete.value = null;
  }
};
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>
