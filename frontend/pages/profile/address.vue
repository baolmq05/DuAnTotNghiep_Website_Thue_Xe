<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 py-6">
    <!-- <div class="relative h-[200px] bg-[#AE7C54] rounded-2xl flex items-center justify-center text-white">
      <h1 class="text-3xl font-bold">Quản lý địa chỉ</h1>
    </div> -->
    <div
  class="relative h-[250px] rounded-2xl overflow-hidden flex items-center justify-center"
>
  <!-- Background -->
  <img
    src="https://res.cloudinary.com/djbobb5oe/image/upload/v1781623501/image-12-scaled_wllwfm.webp"
    alt="Banner địa chỉ"
    class="absolute inset-0 w-full h-full object-cover"
  />

  <!-- Overlay -->
  <div class="absolute inset-0 bg-black/40"></div>

  <!-- Content -->
  <div class="relative z-10 text-center text-white px-6">
    <h1 class="text-4xl md:text-5xl font-bold mb-3">
      Địa chỉ của tôi
    </h1>

    <p class="text-sm md:text-lg text-white/90 max-w-2xl">
      Quản lý danh sách địa chỉ đã lưu để đặt xe nhanh chóng và thuận tiện hơn.
    </p>
  </div>
</div>
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl md:text-2xl font-semibold">Địa chỉ đã lưu</h2>
        <button v-if="!showAddForm" @click="openAddForm"
          class="flex items-center gap-1 px-4 py-2 bg-blue-50 text-blue-600 font-medium rounded-xl border border-blue-200 hover:bg-blue-100 hover:border-blue-300 transition">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Thêm mới
        </button>
      </div>

      <div v-if="loading && savedAddresses.length === 0" class="text-center py-6 text-gray-500">
        Đang tải danh sách địa chỉ...
      </div>
      <div v-else-if="savedAddresses.length === 0"
        class="text-center py-8 text-gray-400 border border-dashed rounded-xl bg-gray-50">
        Bạn chưa lưu địa chỉ nào.
      </div>
      <div v-else class="space-y-4">
        <div v-for="address in savedAddresses" :key="address.id"
          class="flex justify-between items-center bg-gray-50 rounded-xl p-4 border">
          <div class="flex-1">
            <p class="text-gray-800 pr-[60px]">
              {{ address.address_name }}
            </p>
          </div>

          <div class="flex items-center gap-4">
            <button @click="editExistingAddress(address)" class="text-yellow-600 hover:text-yellow-700">
              <Icon name="lucide:edit" class="text-2xl cursor-pointer" />
            </button>

            <button @click="confirmDeleteAddress(address.id)" class="text-red-500 hover:text-red-700">
              <Icon name="lucide:trash-2" class="text-2xl cursor-pointer" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-show="showAddForm" class="bg-white rounded-2xl p-4 md:p-6 shadow-sm transition-all duration-300">
      <h2 class="text-xl md:text-2xl font-semibold mb-6">
        {{ isEditMode ? "Cập nhật địa chỉ" : "Thêm địa chỉ mới" }}
      </h2>

<form @submit.prevent="handleFormSubmit" class="space-y-4">
  <div>
    <label class="block text-sm text-gray-500 mb-1">
      Địa chỉ
    </label>

    <textarea
      v-model="newAddress.address_name"
      placeholder="Nhập địa chỉ"
      class="w-full bg-gray-50 rounded-xl p-3 outline-none border focus:border-blue-500 transition min-h-[120px]"
    ></textarea>
  </div>

  <div class="flex gap-3">
    <button
      type="submit"
      :disabled="loading"
      class="px-6 py-3 bg-green-500 text-white rounded-xl"
    >
      {{ loading ? "Đang lưu..." : "Lưu địa chỉ" }}
    </button>

    <button
      type="button"
      @click="resetForm"
      class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl"
    >
      Hủy
    </button>
  </div>
</form>
    </div>

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

const loading = ref(false);
const showAddForm = ref(false);
const isEditMode = ref(false);
const editingId = ref<number | null>(null);

const savedAddresses = ref<any[]>([]);

const newAddress = ref({
  address_name: "",
});

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

  showAddForm.value = true;
};

const resetForm = () => {
  isEditMode.value = false;
  editingId.value = null;

  newAddress.value = {
    address_name: "",
  };

  showAddForm.value = false;
};

const handleFormSubmit = async () => {
  if (!user.value) {
    showToast("Vui lòng đăng nhập", "error");
    return;
  }

  if (!newAddress.value.address_name.trim()) {
    showToast("Vui lòng nhập địa chỉ", "error");
    return;
  }

  loading.value = true;

  try {
    if (isEditMode.value && editingId.value) {
      const res = await addressService.updateAddress(editingId.value, {
        address_name: newAddress.value.address_name,
      });

      if (res?.success) {
        showToast("Cập nhật địa chỉ thành công", "success");
        resetForm();
        await fetchAddresses();
      } else {
        showToast(
          res?.message || "Cập nhật địa chỉ thất bại",
          "error"
        );
      }
    } else {
      const res = await addressService.createAddress({
        address_name: newAddress.value.address_name,
        user_id: user.value.id,
      });

      if (res?.success) {
        showToast("Thêm địa chỉ thành công", "success");
        resetForm();
        await fetchAddresses();
      } else {
        showToast(
          res?.message || "Thêm địa chỉ thất bại",
          "error"
        );
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
      showToast(
        res?.message || "Xóa địa chỉ thất bại",
        "error"
      );
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

input,
textarea,
select {
  @apply text-gray-700;
}
</style>
