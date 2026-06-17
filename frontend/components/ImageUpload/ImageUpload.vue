<!--
Ví dụ khi sử dụng component
<script setup lang="ts">
import { ref } from "vue";

const form = ref({
  images: [] as string[],
});

const imageUploadRef = ref<any>(null);

const submit = async () => {
  if (imageUploadRef.value) {
    try {
      const urls = await imageUploadRef.value.upload();
      console.log("Danh sách ảnh đã upload thành công:", urls);
      alert("Upload thành công! Xem log để biết chi tiết các link ảnh.");
    } catch (error) {
      console.error("Upload thất bại:", error);
      alert("Đã xảy ra lỗi khi upload hình ảnh.");
    }
  }
};
</script>

<template>
  <div class="max-w-5xl mx-auto p-6 bg-white rounded-2xl shadow-sm border mt-10">
    <form @submit.prevent="submit" class="space-y-6">
      <ImageUpload ref="imageUploadRef" v-model="form.images" :max-files="5" />
      
      <button
        type="submit"
        class="w-full py-3 bg-brand-primary hover:bg-brand-dark text-white font-semibold rounded-xl shadow-md transition duration-200"
      >
        Lưu
      </button>
    </form>
  </div>
</template> 
 -->
<template>
    <div class="space-y-5">
        <!-- Header -->
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-50 to-white p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">
                        Hình ảnh xe
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Chọn nhiều ảnh thật để tăng độ tin cậy.
                        <br />
                        Nhấn vào một ảnh để đặt làm
                        <span class="font-semibold text-brand-primary">
                            ảnh đại diện
                        </span>.
                    </p>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-primary/10">
                    <Icon name="solar:gallery-wide-bold" class="h-8 w-8 text-brand-primary" />
                </div>
            </div>

            <div class="mt-5 flex items-center gap-3">
                <div class="rounded-full bg-brand-primary px-4 py-1.5 text-sm font-semibold text-white">
                    {{ images.length }}/{{ maxFiles }} ảnh
                </div>

                <span class="text-sm text-slate-500">
                    Hỗ trợ JPG, PNG, WEBP
                </span>
            </div>
        </div>

        <!-- Upload Area -->
        <div class="relative overflow-hidden rounded-2xl border-2 border-dashed transition-all cursor-pointer" :class="[
            isDragging
                ? 'border-brand-primary bg-brand-primary/5'
                : 'border-slate-300 hover:border-brand-primary hover:bg-slate-50',
        ]" @click="openFilePicker" @dragenter.prevent="isDragging = true" @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop">
            <input ref="fileInput" type="file" class="hidden" multiple accept="image/*" @change="handleFileChange" />

            <div class="py-16 px-6 flex flex-col items-center">

                <div class="h-24 w-24 rounded-full bg-brand-primary/10 flex items-center justify-center">
                    <Icon name="solar:cloud-upload-bold" class="w-12 h-12 text-brand-primary" />
                </div>

                <h3 class="mt-6 text-xl font-bold text-slate-900">
                    Kéo & thả ảnh vào đây
                </h3>

                <p class="mt-2 text-slate-500 text-center">
                    hoặc
                    <span class="font-semibold text-brand-primary">
                        nhấn để chọn ảnh từ máy tính
                    </span>
                </p>

                <button type="button"
                    class="mt-6 rounded-xl bg-brand-primary px-6 py-3 font-semibold text-white shadow hover:opacity-90 transition">
                    Chọn hình ảnh
                </button>

            </div>
        </div>

        <!-- Preview -->
        <!-- Preview -->
        <div v-if="images.length > 0" class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <div v-for="(image, index) in images" :key="index"
                class="group relative overflow-hidden rounded-2xl border-2 transition-all duration-300 cursor-pointer"
                :class="image.isThumbnail
                        ? 'border-brand-primary shadow-lg shadow-brand-primary/20'
                        : 'border-slate-200 hover:border-brand-primary/40'
                    " @click="setThumbnail(index)">
                <!-- Image -->
                <img :src="image.url" :alt="`Ảnh ${index + 1}`"
                    class="h-44 w-full object-cover transition-transform duration-300 group-hover:scale-105" />

                <!-- Uploading -->
                <div v-if="image.uploading" class="absolute inset-0 bg-black/60 flex items-center justify-center">
                    <div class="text-center text-white">
                        <svg class="animate-spin w-8 h-8 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="white" stroke-width="4" opacity=".25" />

                            <path fill="white" d="M4 12a8 8 0 018-8v4A4 4 0 008 12H4z" />
                        </svg>

                        <p class="text-sm">
                            Đang upload...
                        </p>
                    </div>
                </div>

                <!-- Thumbnail -->
                <div v-if="image.isThumbnail"
                    class="absolute left-3 top-3 flex items-center gap-1 rounded-full bg-brand-primary px-3 py-1 text-[11px] font-bold text-white shadow-lg">
                    <Icon name="solar:star-bold" class="w-3 h-3" />

                    Ảnh đại diện
                </div>

                <!-- Hover -->
                <div v-else
                    class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/20 transition-all duration-300">
                    <span
                        class="scale-0 group-hover:scale-100 transition-transform duration-300 rounded-full bg-black/60 px-4 py-2 text-xs font-semibold text-white">
                        Đặt làm ảnh đại diện
                    </span>
                </div>

                <!-- Delete -->
                <button type="button"
                    class="absolute top-3 right-3 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 shadow opacity-0 transition-all duration-300 group-hover:opacity-100 hover:bg-red-500 hover:text-white"
                    @click.stop="removeImage(index)">
                    <Icon name="ic:outline-close" class="w-4 h-4" />
                </button>
            </div>
        </div>

        <!-- Empty -->
        <div v-else class="rounded-2xl border border-dashed border-slate-300 py-12 text-center">
            <Icon name="solar:gallery-wide-outline" class="mx-auto mb-3 h-12 w-12 text-slate-300" />

            <p class="text-sm text-slate-500">
                Chưa có hình ảnh nào
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Hãy tải lên hình ảnh đầu tiên của xe
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";

interface UploadImage {
    url: string;
    uploading: boolean;
    isThumbnail: boolean;
    file?: File;
}

const props = defineProps({
    modelValue: {
        type: Array as () => string[],
        default: () => [],
    },

    maxFiles: {
        type: Number,
        default: 10,
    },
});

const emit = defineEmits<{
    (e: "update:modelValue", value: string[]): void;
}>();

const fileInput = ref<HTMLInputElement | null>(null);

const isDragging = ref(false);

const images = ref<UploadImage[]>([]);

const CLOUD_NAME = "djbobb5oe";
const UPLOAD_PRESET = "Drivio";
watch(
    () => props.modelValue,
    (value) => {
        const currentUrls = images.value.map((item) => item.url);
        const isSame = JSON.stringify(value) === JSON.stringify(currentUrls);
        if (isSame) return;

        images.value = value.map((url, index) => ({
            url,
            uploading: false,
            isThumbnail: index === 0,
        }));
    },
    {
        immediate: true,
    }
);

/**
 * Emit dữ liệu về component cha
 */
const emitImages = () => {
    emit(
        "update:modelValue",
        images.value.map((item) => item.url)
    );
};

/**
 * Mở cửa sổ chọn file
 */
const openFilePicker = () => {
    if (images.value.length >= props.maxFiles) {
        alert(`Chỉ được tải tối đa ${props.maxFiles} ảnh.`);
        return;
    }

    fileInput.value?.click();
};

/**
 * Chọn file
 */
const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (!target.files) return;

    addFiles(Array.from(target.files));

    target.value = "";
};

/**
 * Kéo thả
 */
const handleDrop = (event: DragEvent) => {
    isDragging.value = false;

    if (!event.dataTransfer?.files) return;

    addFiles(Array.from(event.dataTransfer.files));
};

/**
 * Thêm file vào danh sách preview (chưa upload)
 */
const addFiles = (files: File[]) => {
    const imageFiles = files.filter((file) => file.type.startsWith("image/"));

    if (images.value.length + imageFiles.length > props.maxFiles) {
        alert(`Bạn chỉ được upload tối đa ${props.maxFiles} ảnh.`);
        return;
    }

    imageFiles.forEach((file) => {
        const preview = URL.createObjectURL(file);
        images.value.push({
            url: preview,
            uploading: false,
            isThumbnail: images.value.length === 0,
            file,
        });
    });

    emitImages();
};

/**
 * Upload 1 file lên Cloudinary
 */
const uploadSingleFile = async (image: UploadImage) => {
    if (!image.file) return;

    image.uploading = true;

    try {
        const formData = new FormData();
        formData.append("file", image.file);
        formData.append("upload_preset", UPLOAD_PRESET);

        const response = await $fetch<any>(
            `https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/upload`,
            {
                method: "POST",
                body: formData,
            }
        );

        // Thu hồi preview URL cũ
        if (image.url.startsWith("blob:")) {
            URL.revokeObjectURL(image.url);
        }

        image.url = response.secure_url;
        delete image.file;
    } catch (error) {
        console.error("Lỗi upload ảnh:", error);
        throw error;
    } finally {
        image.uploading = false;
    }
};

/**
 * Thực hiện upload tất cả các ảnh chưa upload lên Cloudinary
 */
const upload = async (): Promise<string[]> => {
    const pendingImages = images.value.filter((img) => img.file);

    if (pendingImages.length > 0) {
        const uploadTasks = pendingImages.map(uploadSingleFile);
        await Promise.all(uploadTasks);
        emitImages();
    }

    return images.value.map((img) => img.url);
};

/**
 * Chọn ảnh đại diện (Thumbnail)
 */
const setThumbnail = (index: number) => {
    const target = images.value[index];
    if (!target) return;

    images.value.forEach((item) => {
        item.isThumbnail = false;
    });

    target.isThumbnail = true;

    // Đưa thumbnail lên đầu danh sách
    const spliced = images.value.splice(index, 1);
    const thumbnail = spliced[0];
    if (thumbnail) {
        images.value.unshift(thumbnail);
    }

    emitImages();
};

/**
 * Xóa ảnh
 */
const removeImage = (index: number) => {
    const removed = images.value[index];
    if (!removed) return;

    // Thu hồi preview URL nếu có để tránh rò rỉ bộ nhớ
    if (removed.url.startsWith("blob:")) {
        URL.revokeObjectURL(removed.url);
    }

    images.value.splice(index, 1);

    // Nếu xóa thumbnail thì ảnh đầu tiên sẽ là thumbnail mới
    if (removed.isThumbnail && images.value[0]) {
        images.value[0].isThumbnail = true;
    }

    emitImages();
};

// Expose phương thức upload ra ngoài cho component cha gọi
defineExpose({
    upload,
});
</script>