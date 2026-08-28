<template>
    <CommonLoadingOverlay :loading="loading" text="Đang tải dữ liệu..." />
    
    <div class="space-y-6">
        <!-- Main Key Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5">
            <!-- 1. Total Trips -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tổng chuyến</p>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                        <Icon name="lucide:car" class="w-4 h-4" />
                    </div>
                </div>
                <div class="mt-3">
                    <h2 v-if="isFetched" class="text-2xl font-black text-slate-800">
                        {{ dashboard.total_trips }}
                    </h2>
                    <div v-else class="h-8 w-16 bg-slate-100 rounded-lg animate-pulse"></div>
                </div>
            </div>

            <!-- 2. Completed Trips -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Hoàn thành</p>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <Icon name="lucide:check-circle" class="w-4 h-4" />
                    </div>
                </div>
                <div class="mt-3">
                    <h2 v-if="isFetched" class="text-2xl font-black text-emerald-600">
                        {{ dashboard.completed_trips }}
                    </h2>
                    <div v-else class="h-8 w-16 bg-slate-100 rounded-lg animate-pulse"></div>
                </div>
            </div>

            <!-- 3. Cancelled Trips -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Chuyến hủy</p>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                        <Icon name="lucide:x-circle" class="w-4 h-4" />
                    </div>
                </div>
                <div class="mt-3">
                    <h2 v-if="isFetched" class="text-2xl font-black text-rose-600">
                        {{ dashboard.cancelled_trips }}
                    </h2>
                    <div v-else class="h-8 w-16 bg-slate-100 rounded-lg animate-pulse"></div>
                </div>
            </div>

            <!-- 4. Approved Cars -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Xe duyệt</p>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-[#1e4e57]">
                        <Icon name="lucide:shield-check" class="w-4 h-4" />
                    </div>
                </div>
                <div class="mt-3">
                    <h2 v-if="isFetched" class="text-2xl font-black text-[#1e4e57]">
                        {{ dashboard.approved_cars }}
                    </h2>
                    <div v-else class="h-8 w-16 bg-slate-100 rounded-lg animate-pulse"></div>
                </div>
            </div>

            <!-- 5. Cảnh cáo vi phạm -->
            <NuxtLink to="/my-cars/reports"
                      class="bg-white rounded-2xl shadow-sm border p-5 flex flex-col justify-between hover:shadow-md transition-all group cursor-pointer"
                      :class="strikeCardBorderClass">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider" :class="strikeCardTextClass">Cảnh cáo vi phạm</p>
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" :class="strikeCardBgClass">
                        <Icon :name="strikeCardIcon" class="w-4 h-4" />
                    </div>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <div>
                        <h2 v-if="isFetched" class="text-2xl font-black" :class="strikeCardNumberClass">
                            {{ reportSummary?.active_strikes ?? 0 }} <span class="text-xs font-bold text-slate-400">/ 3</span>
                        </h2>
                        <div v-else class="h-8 w-16 bg-slate-100 rounded-lg animate-pulse"></div>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" :class="strikeBadgeClass">
                        {{ strikeStatusLabel }}
                    </span>
                </div>
            </NuxtLink>

            <!-- 6. Khiếu nại / Báo cáo -->
            <NuxtLink to="/my-cars/reports"
                      class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex flex-col justify-between hover:shadow-md transition-all group cursor-pointer">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Báo cáo vi phạm</p>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <Icon name="lucide:alert-circle" class="w-4 h-4" />
                    </div>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <div>
                        <h2 v-if="isFetched" class="text-2xl font-black text-slate-800">
                            {{ reportSummary?.reports?.total ?? 0 }}
                        </h2>
                        <div v-else class="h-8 w-16 bg-slate-100 rounded-lg animate-pulse"></div>
                    </div>
                    <span v-if="reportSummary?.reports?.pending" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                        {{ reportSummary.reports.pending }} chờ xử lý
                    </span>
                    <span v-else class="text-[10px] font-semibold text-slate-400">
                        0 chờ xử lý
                    </span>
                </div>
            </NuxtLink>
        </div>

        <!-- Active Penalties Detail Card if any -->
        <div v-if="reportSummary?.active_penalties && reportSummary.active_penalties.length > 0"
             class="bg-gradient-to-br from-rose-50 to-orange-50/50 rounded-2xl border border-rose-200/80 p-6 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-rose-200/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shadow-sm">
                        <Icon name="lucide:shield-alert" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-black text-sm text-slate-800 uppercase tracking-wide">
                            Hình phạt đang áp dụng ({{ reportSummary.active_penalties.length }})
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">
                            Các hình phạt vi phạm hiện đang có hiệu lực đối với tài khoản của bạn.
                        </p>
                    </div>
                </div>
                <NuxtLink to="/my-cars/reports" class="text-xs font-bold text-rose-700 hover:text-rose-800 flex items-center gap-1">
                    <span>Xem tất cả khiếu nại</span>
                    <Icon name="lucide:chevron-right" class="w-4 h-4" />
                </NuxtLink>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                <div v-for="penalty in reportSummary.active_penalties" :key="penalty.id"
                     class="bg-white rounded-xl p-4 border border-rose-100 shadow-2xs space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-extrabold px-2.5 py-1 rounded-lg border shadow-3xs"
                              :class="penaltyTypeBadgeClass(penalty.penalty_type)">
                            {{ penalty.penalty_type_text }}
                        </span>
                        <span v-if="penalty.trip_code" class="text-[11px] font-mono font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                            Chuyến #{{ penalty.trip_code }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-700 font-medium leading-relaxed">
                        <strong class="text-slate-900">Lý do:</strong> {{ penalty.reason }}
                    </p>
                    <div class="text-[11px] text-slate-500 font-medium pt-1 flex items-center gap-1.5 border-t border-slate-50">
                        <Icon name="lucide:clock" class="w-3.5 h-3.5 text-slate-400" />
                        <span>Thời hạn: {{ formatDate(penalty.start_at) }} &rarr; {{ penalty.end_at ? formatDate(penalty.end_at) : 'Vô thời hạn' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <Icon name="lucide:trending-up" class="w-5 h-5 text-[#1e4e57]" />
                    Doanh thu theo tháng
                </h2>
            </div>

            <div class="h-96">
                <Line v-if="isFetched && dashboard.chart?.datasets?.length > 0" :data="dashboard.chart"
                    :options="chartOptions" />
                <div v-else class="w-full h-full bg-slate-50 rounded-xl animate-pulse flex items-center justify-center text-slate-400 text-sm">
                    Đang tải biểu đồ doanh thu...
                </div>
            </div>
        </div>

        <!-- Recent Violation Reports Preview Section -->
        <div v-if="reportSummary?.recent_reports && reportSummary.recent_reports.length > 0"
             class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <Icon name="lucide:file-warning" class="w-5 h-5 text-slate-700" />
                    <h3 class="text-base font-bold text-slate-800">Báo cáo & Khiếu nại gần đây</h3>
                </div>
                <NuxtLink to="/my-cars/reports" class="text-xs font-bold text-[#1e4e57] hover:underline flex items-center gap-1">
                    Xem tất cả ({{ reportSummary.reports?.total ?? 0 }})
                    <Icon name="lucide:arrow-right" class="w-3.5 h-3.5" />
                </NuxtLink>
            </div>

            <div class="divide-y divide-slate-100">
                <div v-for="rep in reportSummary.recent_reports" :key="rep.id"
                     class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/70 px-3 rounded-xl transition-colors">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                            <img v-if="rep.car?.thumbnail" :src="rep.car.thumbnail" :alt="rep.car?.name" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                                <Icon name="lucide:car" class="w-5 h-5" />
                            </div>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-xs text-slate-800">{{ rep.car?.name || 'Xe cho thuê' }}</span>
                                <span v-if="rep.car?.license_plate" class="text-[10px] font-mono font-bold bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded border">
                                    {{ rep.car.license_plate }}
                                </span>
                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md border" :class="reportStatusClass(rep.status)">
                                    {{ rep.status_text }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 font-medium truncate mt-0.5">
                                <strong class="text-slate-700">{{ rep.report_type_text }}:</strong> {{ rep.description }}
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                Báo cáo bởi: {{ rep.reporter?.name || 'Khách hàng' }} &bull; {{ formatDate(rep.created_at) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                        <span v-if="rep.penalty" class="px-2 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            +1 Cảnh cáo
                        </span>
                        <NuxtLink :to="`/my-cars/reports`"
                                  class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-colors">
                            Chi tiết
                        </NuxtLink>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { Line } from "vue-chartjs";
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from "chart.js";
import { reportService, type OwnerReportSummary } from "~/services/report.service";

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

const reportSummary = ref<OwnerReportSummary | null>(null);

const loading = ref(true);
const loadError = ref('');
const isFetched = ref(false);

// Format date helper
const formatDate = (dateStr?: string | null) => {
    if (!dateStr) return "N/A";
    const d = new Date(dateStr);
    return d.toLocaleDateString("vi-VN", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
};

// Computed styles for strike card
const strikeCount = computed(() => reportSummary.value?.active_strikes ?? 0);
const isSuspended = computed(() => reportSummary.value?.is_account_suspended ?? false);

const strikeStatusLabel = computed(() => {
    if (isSuspended.value) return "Tạm khóa";
    if (strikeCount.value === 0) return "Tốt";
    if (strikeCount.value === 1) return "Cảnh cáo 1";
    if (strikeCount.value === 2) return "Nguy cơ cao";
    return "Vi phạm";
});

const strikeCardBorderClass = computed(() => {
    if (isSuspended.value) return "border-red-400 bg-red-50/30";
    if (strikeCount.value >= 2) return "border-orange-300 bg-orange-50/20";
    if (strikeCount.value === 1) return "border-amber-300 bg-amber-50/20";
    return "border-slate-100";
});

const strikeCardTextClass = computed(() => {
    if (isSuspended.value) return "text-red-700";
    if (strikeCount.value >= 2) return "text-orange-700";
    if (strikeCount.value === 1) return "text-amber-700";
    return "text-slate-500";
});

const strikeCardNumberClass = computed(() => {
    if (isSuspended.value) return "text-red-600";
    if (strikeCount.value >= 2) return "text-orange-600";
    if (strikeCount.value === 1) return "text-amber-600";
    return "text-emerald-600";
});

const strikeCardBgClass = computed(() => {
    if (isSuspended.value) return "bg-red-100 text-red-700";
    if (strikeCount.value >= 2) return "bg-orange-100 text-orange-700";
    if (strikeCount.value === 1) return "bg-amber-100 text-amber-700";
    return "bg-emerald-50 text-emerald-600";
});

const strikeCardIcon = computed(() => {
    if (isSuspended.value) return "lucide:shield-ban";
    if (strikeCount.value > 0) return "lucide:alert-triangle";
    return "lucide:shield-check";
});

const strikeBadgeClass = computed(() => {
    if (isSuspended.value) return "bg-red-100 text-red-800";
    if (strikeCount.value >= 2) return "bg-orange-100 text-orange-800";
    if (strikeCount.value === 1) return "bg-amber-100 text-amber-800";
    return "bg-emerald-100 text-emerald-800";
});

const penaltyTypeBadgeClass = (type: number) => {
    switch (type) {
        case 0:
            return "bg-amber-50 text-amber-700 border-amber-200";
        case 1:
            return "bg-orange-50 text-orange-700 border-orange-200";
        case 2:
            return "bg-red-50 text-red-700 border-red-200";
        default:
            return "bg-slate-100 text-slate-700 border-slate-200";
    }
};

const reportStatusClass = (status: number) => {
    switch (status) {
        case 0: // Pending
            return "bg-amber-50 text-amber-700 border-amber-200";
        case 1: // Resolved
            return "bg-rose-50 text-rose-700 border-rose-200";
        case 2: // Rejected
            return "bg-slate-100 text-slate-600 border-slate-200";
        case 3: // Cancelled
            return "bg-gray-100 text-gray-500 border-gray-200";
        default:
            return "bg-slate-100 text-slate-600 border-slate-200";
    }
};

// Fetch dashboard data & owner report summary
onMounted(async () => {
    try {
        loading.value = true;
        
        // 1. Fetch dashboard metrics
        const res = await $fetch<any>(`${baseApi}/dashboard`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        if (res?.success && res.data) {
            dashboard.value = {
                ...dashboard.value,
                ...res.data,
            };
        }

        // 2. Fetch owner report & strike summary
        const repRes = await reportService.getOwnerSummary();
        if (repRes?.success && repRes.data) {
            reportSummary.value = repRes.data;
        }

        isFetched.value = true;
    } catch (err: any) {
        loadError.value = err?.message || 'Không thể tải dữ liệu dashboard.';
    } finally {
        loading.value = false;
    }
});

// cấu hình hiển thị biểu đồ
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: "top" as const,
        },
    },
};

const formatMoney = (value: number | string) => {
    return Number(value).toLocaleString("vi-VN") + " đ";
};
</script>