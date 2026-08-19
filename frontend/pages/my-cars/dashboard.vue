<template>
    <CommonLoadingOverlay :loading="loading" text="Đang tải dữ liệu..." />
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-gray-500">Tổng số chuyến</p>
            <h2 v-if="isFetched" class="text-3xl font-bold mt-2">
                {{ dashboard.total_trips }}
            </h2>
            <div v-else class="h-9 w-16 bg-gray-200 rounded animate-pulse mt-2"></div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-gray-500">Chuyến hoàn thành</p>
            <h2 v-if="isFetched" class="text-3xl font-bold mt-2 text-green-600">
                {{ dashboard.completed_trips }}
            </h2>
            <div v-else class="h-9 w-16 bg-gray-200 rounded animate-pulse mt-2"></div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-gray-500">Chuyến bị hủy</p>
            <h2 v-if="isFetched" class="text-3xl font-bold mt-2 text-red-600">
                {{ dashboard.cancelled_trips }}
            </h2>
            <div v-else class="h-9 w-16 bg-gray-200 rounded animate-pulse mt-2"></div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-gray-500">Xe đã được duyệt</p>
            <h2 v-if="isFetched" class="text-3xl font-bold mt-2 text-blue-600">
                {{ dashboard.approved_cars }}
            </h2>
            <div v-else class="h-9 w-16 bg-gray-200 rounded animate-pulse mt-2"></div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">
            Doanh thu theo tháng
        </h2>

        <div class="h-96">
            <Line v-if="isFetched && dashboard.chart?.datasets?.length > 0" :data="dashboard.chart"
                :options="chartOptions" />
            <div v-else class="w-full h-full bg-gray-100 rounded-xl animate-pulse"></div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Line } from "vue-chartjs";
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from "chart.js";

// đăng ký các thành phần của chart
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

definePageMeta({
    layout: "my-cars",
});

const config = useRuntimeConfig();
const baseApi = config.public.apiBase || 'http://127.0.0.1:8000/api';
const token = useCookie('USER_TOKEN').value || '';
const router = useRouter();

if (!token && process.client) {
    router.push('/');
}

const dashboard = ref({
    total_trips: 0,
    completed_trips: 0,
    cancelled_trips: 0,
    approved_cars: 0,
    revenue: 0,
    chart: {
        labels: [],
        datasets: [],
    },
});

const loading = ref(true);
const loadError = ref('');
const isFetched = ref(false);

// lấy dữ liệu dashboard từ API
await useLazyFetch(() => `${baseApi}/dashboard`, {
    server: false,
    immediate: !!token,
    watch: false,
    headers: {
        Authorization: `Bearer ${token}`
    },
    onRequest() {
        loading.value = true;
        loadError.value = '';
        isFetched.value = false;
    },
    // xử lý dữ liệu trả về từ API và cập nhật state
    onResponse({ response }) {
        const payload = response._data;
        if (payload?.success && payload.data) {
            dashboard.value = {
                ...dashboard.value,
                ...payload.data,
            };
            setTimeout(() => {
                isFetched.value = true;
                loading.value = false;
            }, 500);
        } else {
            loadError.value = payload?.message || 'Không thể tải dữ liệu dashboard.';
            loading.value = false;
        }
    },
    onResponseError({ response }) {
        loadError.value = response?._data?.message || 'Không thể tải dữ liệu dashboard.';
        setTimeout(() => {
            loading.value = false;
        }, 500);
    }
});

// cấu hình hiển thị biểu đồ
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: "top",
        },
    },
};

const formatMoney = (value) => {
    return Number(value).toLocaleString("vi-VN") + " đ";
};
</script>