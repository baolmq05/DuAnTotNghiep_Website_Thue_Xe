<template>
  <div class="space-y-8">
    <CommonLoadingOverlay :loading="loading" text="Đang tải dữ liệu báo cáo & vi phạm..." />

    <!-- 1. Header Page Title & Summary -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
      <div class="space-y-1">
        <div class="flex items-center gap-2.5">
          <div class="w-10 h-10 rounded-2xl bg-[#1e4e57]/10 text-[#1e4e57] flex items-center justify-center">
            <Icon name="lucide:shield-alert" class="w-5 h-5" />
          </div>
          <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
              Quản lý Báo cáo & Vi phạm
            </h2>
            <p class="text-xs text-slate-500 font-medium">
              Theo dõi lịch sử khiếu nại từ khách hàng, mức độ cảnh cáo và trạng thái tài khoản chủ xe của bạn.
            </p>
          </div>
        </div>
      </div>

      <!-- Quick Status Badge -->
      <div class="flex items-center gap-3 self-start md:self-auto">
        <div class="px-4 py-2 rounded-2xl border flex items-center justify-center" :class="accountStatusBoxClass">
          <span class="text-xs font-bold">{{ accountStatusText }}</span>
        </div>
      </div>
    </div>

    <!-- 2. Overview Metric Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
      <!-- Card 1: Mức cảnh cáo hiện tại -->
      <div class="bg-white rounded-3xl p-6 border shadow-sm flex flex-col justify-between" :class="strikeMeterBorderClass">
        <div>
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Mức cảnh cáo hiện tại</span>
            <div class="w-8 h-8 rounded-xl flex items-center justify-center" :class="strikeMeterBgClass">
              <Icon :name="strikeMeterIcon" class="w-4 h-4" />
            </div>
          </div>
          <div class="mt-4 flex items-baseline gap-2">
            <span class="text-4xl font-black" :class="strikeMeterNumberClass">
              {{ summary?.active_strikes ?? 0 }}
            </span>
            <span class="text-xs font-bold text-slate-400">/ 3 mức tối đa</span>
          </div>
          <!-- 3-Segment Progress Bar -->
          <div class="grid grid-cols-3 gap-1.5 mt-4">
            <div class="h-2 rounded-full transition-all duration-500"
                 :class="(summary?.active_strikes ?? 0) >= 1 ? 'bg-amber-500' : 'bg-slate-100'"></div>
            <div class="h-2 rounded-full transition-all duration-500"
                 :class="(summary?.active_strikes ?? 0) >= 2 ? 'bg-orange-500' : 'bg-slate-100'"></div>
            <div class="h-2 rounded-full transition-all duration-500"
                 :class="(summary?.active_strikes ?? 0) >= 3 || summary?.is_account_suspended ? 'bg-rose-500' : 'bg-slate-100'"></div>
          </div>
        </div>
        <p class="text-[11px] font-semibold mt-4 pt-3 border-t border-slate-100" :class="strikeMeterSubtextClass">
          {{ strikeMeterMessage }}
        </p>
      </div>

      <!-- Card 2: Lịch sử xử lý vi phạm -->
      <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Lịch sử xử lý vi phạm</span>
            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
              <Icon name="lucide:history" class="w-4 h-4" />
            </div>
          </div>
          <div class="mt-4 flex items-baseline gap-2">
            <span class="text-4xl font-black text-slate-800">
              {{ summary?.total_strikes ?? 0 }}
            </span>
            <span class="text-xs font-bold text-slate-400">lần ghi nhận</span>
          </div>
        </div>
        <div class="text-[11px] text-slate-500 font-medium mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
          <span>Cảnh cáo: <strong>{{ summary?.penalties_breakdown?.warnings ?? 0 }}</strong></span>
          <span>Khóa TK: <strong>{{ summary?.penalties_breakdown?.account_suspensions ?? 0 }}</strong></span>
        </div>
      </div>

      <!-- Card 3: Báo cáo Chờ xử lý (Pending Reports) -->
      <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Đang chờ Admin duyệt</span>
            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
              <Icon name="lucide:clock" class="w-4 h-4" />
            </div>
          </div>
          <div class="mt-4 flex items-baseline gap-2">
            <span class="text-4xl font-black text-amber-600">
              {{ summary?.reports?.pending ?? 0 }}
            </span>
            <span class="text-xs font-bold text-slate-400">khiếu nại</span>
          </div>
        </div>
        <p class="text-[11px] text-slate-500 font-medium mt-4 pt-3 border-t border-slate-100">
          Admin đang trong quá trình thẩm định bằng chứng.
        </p>
      </div>

      <!-- Card 4: Tổng Báo cáo đã nhận (Total Reports) -->
      <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Tổng báo cáo</span>
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#1e4e57] flex items-center justify-center">
              <Icon name="lucide:file-text" class="w-4 h-4" />
            </div>
          </div>
          <div class="mt-4 flex items-baseline gap-2">
            <span class="text-4xl font-black text-slate-800">
              {{ summary?.reports?.total ?? 0 }}
            </span>
            <span class="text-xs font-bold text-slate-400">khiếu nại gửi tới</span>
          </div>
        </div>
        <div class="text-[11px] text-slate-500 font-medium mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
          <span class="text-rose-600">Bị phạt: <strong>{{ summary?.reports?.resolved ?? 0 }}</strong></span>
          <span class="text-emerald-600">Bác bỏ: <strong>{{ summary?.reports?.rejected ?? 0 }}</strong></span>
        </div>
      </div>
    </div>

    <!-- 3. Active Penalties Alert Box (if any active strike exists) -->
    <div v-if="summary?.active_penalties && summary.active_penalties.length > 0"
         class="bg-gradient-to-br from-rose-50 via-white to-orange-50/40 rounded-3xl border border-rose-200 p-6 sm:p-7 shadow-sm space-y-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-rose-500 text-white flex items-center justify-center shadow-md shadow-rose-500/20">
          <Icon name="lucide:ban" class="w-5 h-5" />
        </div>
        <div>
          <h3 class="text-base font-black text-slate-800 uppercase tracking-wide">
            Hình phạt đang có hiệu lực ({{ summary.active_penalties.length }})
          </h3>
          <p class="text-xs text-slate-500 font-medium">
            Các hình phạt bên dưới đang ảnh hưởng trực tiếp đến trạng thái hoạt động của tài khoản.
          </p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div v-for="penalty in summary.active_penalties" :key="penalty.id"
             class="bg-white rounded-2xl p-5 border border-rose-100 shadow-2xs space-y-3">
          <div class="flex items-center justify-between gap-2">
            <span class="text-xs font-extrabold px-3 py-1 rounded-xl border shadow-3xs"
                  :class="penaltyTypeBadgeClass(penalty.penalty_type)">
              {{ penalty.penalty_type_text }}
            </span>
            <NuxtLink v-if="penalty.trip_id" :to="`/trips/${penalty.trip_id}`"
                      class="text-xs font-mono font-bold text-[#1e4e57] hover:underline bg-[#1e4e57]/10 px-2.5 py-1 rounded-lg">
              Mã chuyến: #{{ penalty.trip_code || penalty.trip_id }} &rarr;
            </NuxtLink>
          </div>

          <p class="text-xs text-slate-700 font-medium leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
            <strong class="text-slate-900 block mb-0.5">Lý do vi phạm:</strong>
            {{ penalty.reason }}
          </p>

          <div class="flex items-center justify-between text-xs text-slate-500 font-medium pt-2 border-t border-slate-100">
            <div class="flex items-center gap-1.5">
              <Icon name="lucide:calendar" class="w-3.5 h-3.5 text-slate-400" />
              <span>Bắt đầu: <strong>{{ formatDate(penalty.start_at) }}</strong></span>
            </div>
            <div class="flex items-center gap-1.5">
              <Icon name="lucide:clock" class="w-3.5 h-3.5 text-rose-500" />
              <span>Hết hạn: <strong class="text-rose-600">{{ penalty.end_at ? formatDate(penalty.end_at) : 'Vô thời hạn' }}</strong></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. Guidelines & Policy Info (Minimal & Clean) -->
    <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 sm:p-5 space-y-2.5">
      <div class="flex items-center gap-2 text-slate-800 font-bold text-xs sm:text-sm">
        <Icon name="lucide:info" class="w-4 h-4 text-slate-500" />
        <span>Quy định xử lý vi phạm dành cho Chủ xe</span>
      </div>
      <p class="text-xs text-slate-600 leading-relaxed">
        Nhằm đảm bảo trải nghiệm thuê xe minh bạch và uy tín, nền tảng áp dụng quy trình xử lý vi phạm theo 3 mức độ. Các lỗi vi phạm bao gồm: Giao sai xe không đúng hợp đồng, tự ý hủy chuyến cận giờ mà không có lý do chính đáng, xe không đảm bảo an toàn hoặc từ chối hỗ trợ khách hàng.
      </p>
      <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5 text-xs text-slate-600 pt-2 border-t border-slate-200/70 font-medium">
        <div class="flex items-center gap-1.5">
          <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
          <span><strong>Mức 1:</strong> Cảnh cáo</span>
        </div>
        <div class="flex items-center gap-1.5">
          <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
          <span><strong>Mức 2:</strong> Hạn chế hiển thị</span>
        </div>
        <div class="flex items-center gap-1.5">
          <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
          <span><strong>Mức 3:</strong> Khóa tài khoản</span>
        </div>
      </div>
    </div>

    <!-- 5. Reports Listing Section -->
    <div class="bg-white rounded-3xl border border-slate-100 p-6 sm:p-7 shadow-sm space-y-6">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100">
        <div>
          <h3 class="text-lg font-black text-slate-800">Danh sách Báo cáo từ Khách hàng</h3>
          <p class="text-xs text-slate-400 font-medium mt-0.5">
            Xem chi tiết khiếu nại, minh chứng hình ảnh và phản hồi xử lý từ Admin
          </p>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full md:w-72">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Tìm theo biển số, tên xe, mã chuyến..."
            class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:border-[#1e4e57] focus:bg-white transition-all"
            @keyup.enter="handleSearch"
          />
          <Icon name="lucide:search" class="w-4 h-4 text-slate-400 absolute left-3 top-3" />
        </div>
      </div>

      <!-- Status Filter Tabs -->
      <div class="flex flex-wrap items-center gap-2">
        <button
          v-for="tab in filterTabs"
          :key="tab.value"
          @click="selectFilter(tab.value)"
          class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 border shadow-2xs"
          :class="activeFilter === tab.value
            ? 'bg-[#1e4e57] border-[#1e4e57] text-white shadow-sm'
            : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'"
        >
          <span>{{ tab.label }}</span>
          <span class="text-[10px] px-1.5 py-0.5 rounded-lg"
                :class="activeFilter === tab.value ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">
            {{ getFilterCount(tab.value) }}
          </span>
        </button>
      </div>

      <!-- Reports List Content -->
      <div v-if="reportsLoading" class="space-y-4">
        <div v-for="i in 3" :key="i" class="animate-pulse bg-slate-50 rounded-2xl h-44 p-6 border border-slate-100">
          <div class="flex gap-4">
            <div class="w-24 h-24 bg-slate-200 rounded-xl"></div>
            <div class="flex-1 space-y-3">
              <div class="h-4 bg-slate-200 rounded w-1/3"></div>
              <div class="h-3 bg-slate-200 rounded w-1/2"></div>
              <div class="h-12 bg-slate-200 rounded w-full"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="reports.length === 0"
           class="py-16 text-center space-y-3 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto shadow-sm">
          <Icon name="lucide:shield-check" class="w-7 h-7" />
        </div>
        <h4 class="text-sm font-bold text-slate-700">Không có báo cáo vi phạm nào</h4>
        <p class="text-xs text-slate-400 max-w-sm mx-auto">
          Tuyệt vời! Hiện tại bạn không có báo cáo vi phạm nào thuộc bộ lọc này. Hãy tiếp tục duy trì dịch vụ tốt nhé!
        </p>
      </div>

      <!-- Loaded Reports Cards -->
      <div v-else class="space-y-5">
        <div
          v-for="rep in reports"
          :key="rep.id"
          class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all space-y-4"
        >
          <!-- Top Row: Car Info, Violation Type & Status -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
              <div class="w-14 h-14 rounded-2xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                <img v-if="rep.car?.thumbnail" :src="rep.car.thumbnail" :alt="rep.car?.name" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                  <Icon name="lucide:car" class="w-6 h-6" />
                </div>
              </div>
              <div>
                <div class="flex items-center gap-2 flex-wrap">
                  <h4 class="font-extrabold text-sm text-slate-800">{{ rep.car?.name || 'Xe cho thuê' }}</h4>
                  <span v-if="rep.car?.license_plate"
                        class="bg-slate-100 text-slate-700 text-[10px] font-mono font-bold px-2 py-0.5 rounded border border-slate-200">
                    {{ rep.car.license_plate }}
                  </span>
                  <NuxtLink v-if="rep.trip?.id" :to="`/trips/${rep.trip.id}`"
                            class="text-[10px] font-mono font-bold text-[#1e4e57] hover:underline bg-[#1e4e57]/10 px-2 py-0.5 rounded">
                    Chuyến #{{ rep.trip.trip_code || rep.trip.id }}
                  </NuxtLink>
                </div>
                <p class="text-xs text-slate-400 font-medium mt-0.5 flex items-center gap-1.5">
                  <span>Khách thuê: <strong>{{ rep.reporter?.name || 'Khách hàng' }}</strong></span>
                  <span>&bull;</span>
                  <span>Ngày gửi: {{ formatDate(rep.created_at) }}</span>
                </p>
              </div>
            </div>

            <!-- Status & Warning Tags -->
            <div class="flex items-center gap-2 self-start sm:self-center flex-wrap">
              <span class="px-3 py-1 rounded-xl text-xs font-bold border" :class="reportStatusClass(rep.status)">
                {{ rep.status_text }}
              </span>
              <span v-if="rep.penalty" class="px-3 py-1 rounded-xl text-xs font-extrabold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1">
                <Icon name="lucide:ban" class="w-3.5 h-3.5 text-rose-600" />
                +1 Cảnh cáo ({{ rep.penalty.penalty_type_text }})
              </span>
            </div>
          </div>

          <!-- Middle: Violation Type & Description -->
          <div class="space-y-2">
            <div class="flex items-center gap-2">
              <span class="text-xs font-extrabold text-rose-700 bg-rose-50 px-2.5 py-0.5 rounded-lg border border-rose-100">
                {{ rep.report_type_text }}
              </span>
              <h5 class="text-xs sm:text-sm font-bold text-slate-800">{{ rep.title }}</h5>
            </div>
            <p class="text-xs text-slate-650 font-medium leading-relaxed bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100 whitespace-pre-line">
              {{ rep.description }}
            </p>
          </div>

          <!-- Evidence Images Gallery -->
          <div v-if="rep.images && rep.images.length > 0" class="space-y-1.5">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Hình ảnh minh chứng từ khách:</span>
            <div class="flex flex-wrap gap-2.5 pt-1">
              <div
                v-for="img in rep.images"
                :key="img.id"
                @click="openImageModal(img.image_url)"
                class="w-20 h-20 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 cursor-zoom-in hover:opacity-85 transition-all shadow-2xs group relative"
              >
                <img :src="img.image_url" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                <div class="absolute inset-0 bg-black/15 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                  <Icon name="lucide:zoom-in" class="w-4 h-4 text-white drop-shadow" />
                </div>
              </div>
            </div>
          </div>

          <!-- Admin Feedback Note & Resolution Details -->
          <div v-if="rep.admin_note || rep.resolved_at"
               class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 space-y-1.5 animate-fade-in">
            <div class="flex items-center justify-between">
              <h5 class="text-xs font-bold text-[#1e4e57] uppercase tracking-wider flex items-center gap-1.5">
                <Icon name="lucide:shield-check" class="w-4 h-4 text-[#1e4e57]" />
                Kết luận xử lý từ Quản trị viên (Admin)
              </h5>
              <span v-if="rep.resolved_at" class="text-[10px] text-slate-400 font-semibold">
                Xử lý lúc: {{ formatDate(rep.resolved_at) }}
              </span>
            </div>
            <p v-if="rep.admin_note" class="text-xs text-slate-700 font-medium leading-relaxed">
              {{ rep.admin_note }}
            </p>
          </div>

          <!-- Penalty Detail Attached to this Report -->
          <div v-if="rep.penalty"
               class="bg-gradient-to-r from-rose-50 to-orange-50/40 border border-rose-200 rounded-2xl p-4 space-y-2">
            <div class="flex items-center justify-between">
              <h5 class="text-xs font-extrabold text-rose-700 uppercase tracking-wider flex items-center gap-1.5">
                <Icon name="lucide:alert-octagon" class="w-4 h-4 text-rose-600" />
                Chi tiết hình phạt áp dụng
              </h5>
              <span class="text-[11px] font-bold text-slate-500">
                Hiệu lực: {{ formatDate(rep.penalty.start_at) }} &rarr; {{ rep.penalty.end_at ? formatDate(rep.penalty.end_at) : 'Vô thời hạn' }}
              </span>
            </div>
            <p class="text-xs text-slate-700 font-medium">
              <strong>Hình thức:</strong> {{ rep.penalty.penalty_type_text }} &bull; <strong>Lý do:</strong> {{ rep.penalty.reason }}
            </p>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="flex items-center justify-between pt-4 border-t border-slate-100">
        <span class="text-xs text-slate-500 font-medium">
          Hiển thị trang <strong>{{ pagination.current_page }}</strong> / {{ pagination.last_page }} (Tổng {{ pagination.total }} báo cáo)
        </span>
        <div class="flex items-center gap-2">
          <button
            :disabled="pagination.current_page <= 1"
            @click="changePage(pagination.current_page - 1)"
            class="px-3 py-1.5 rounded-xl border text-xs font-bold text-slate-700 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
          >
            Trang trước
          </button>
          <button
            :disabled="pagination.current_page >= pagination.last_page"
            @click="changePage(pagination.current_page + 1)"
            class="px-3 py-1.5 rounded-xl border text-xs font-bold text-slate-700 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
          >
            Trang sau
          </button>
        </div>
      </div>
    </div>

    <!-- Image Lightbox Modal -->
    <div
      v-if="selectedImage"
      class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4"
      @click="selectedImage = null"
    >
      <div class="relative max-w-4xl max-h-[90vh] bg-slate-950 rounded-3xl overflow-hidden shadow-2xl p-2" @click.stop>
        <button
          @click="selectedImage = null"
          class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition-colors"
        >
          <Icon name="lucide:x" class="w-5 h-5" />
        </button>
        <img :src="selectedImage" class="max-w-full max-h-[85vh] object-contain rounded-2xl mx-auto" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import {
  reportService,
  type OwnerReportSummary,
  type OwnerReportItem,
  type OwnerReportsPagination,
} from "~/services/report.service";

definePageMeta({
  layout: "my-cars",
});

const token = useCookie("USER_TOKEN").value || "";
const router = useRouter();

if (!token && process.client) {
  router.push("/");
}

// State
const loading = ref(true);
const reportsLoading = ref(false);
const summary = ref<OwnerReportSummary | null>(null);
const reports = ref<OwnerReportItem[]>([]);
const pagination = ref<OwnerReportsPagination | null>(null);

const activeFilter = ref<string>("all");
const searchQuery = ref<string>("");
const currentPage = ref<number>(1);
const selectedImage = ref<string | null>(null);

// Filter Tabs Configuration
const filterTabs = [
  { label: "Tất cả", value: "all" },
  { label: "Chờ xử lý", value: "0" },
  { label: "Đã xử lý (Áp dụng phạt)", value: "1" },
  { label: "Đã từ chối / Bác bỏ", value: "2" },
  { label: "Đã thu hồi", value: "3" },
];

const getFilterCount = (tabValue: string) => {
  if (!summary.value?.reports) return 0;
  switch (tabValue) {
    case "all":
      return summary.value.reports.total;
    case "0":
      return summary.value.reports.pending;
    case "1":
      return summary.value.reports.resolved;
    case "2":
      return summary.value.reports.rejected;
    case "3":
      return summary.value.reports.cancelled;
    default:
      return 0;
  }
};

// Account Status computed values
const accountStatusText = computed(() => {
  if (summary.value?.is_account_suspended) return "Tài khoản đang bị Tạm Khóa";
  return "Tài khoản Hoạt Động Bình Thường";
});

const accountStatusBoxClass = computed(() => {
  if (summary.value?.is_account_suspended) return "bg-rose-50 border-rose-200 text-rose-700";
  return "bg-emerald-50 border-emerald-200 text-emerald-700";
});

const accountStatusDotClass = computed(() => {
  if (summary.value?.is_account_suspended) return "bg-rose-500 animate-pulse";
  return "bg-emerald-500";
});

// Strike Meter computed visual styles
const strikeMeterBorderClass = computed(() => {
  if (summary.value?.is_account_suspended) return "border-rose-300 bg-rose-50/20";
  const strikes = summary.value?.active_strikes ?? 0;
  if (strikes >= 2) return "border-orange-300 bg-orange-50/20";
  if (strikes === 1) return "border-amber-300 bg-amber-50/20";
  return "border-slate-100";
});

const strikeMeterBgClass = computed(() => {
  if (summary.value?.is_account_suspended) return "bg-rose-100 text-rose-700";
  const strikes = summary.value?.active_strikes ?? 0;
  if (strikes >= 2) return "bg-orange-100 text-orange-700";
  if (strikes === 1) return "bg-amber-100 text-amber-700";
  return "bg-emerald-50 text-emerald-600";
});

const strikeMeterNumberClass = computed(() => {
  if (summary.value?.is_account_suspended) return "text-rose-600";
  const strikes = summary.value?.active_strikes ?? 0;
  if (strikes >= 2) return "text-orange-600";
  if (strikes === 1) return "text-amber-600";
  return "text-emerald-600";
});

const strikeMeterIcon = computed(() => {
  if (summary.value?.is_account_suspended) return "lucide:shield-ban";
  const strikes = summary.value?.active_strikes ?? 0;
  if (strikes > 0) return "lucide:alert-triangle";
  return "lucide:shield-check";
});

const strikeMeterMessage = computed(() => {
  if (summary.value?.is_account_suspended) {
    return "Tài khoản bị tạm khóa do đã đạt mức giới hạn vi phạm.";
  }
  const strikes = summary.value?.active_strikes ?? 0;
  if (strikes === 0) return "Trạng thái rất tốt! Bạn chưa có cảnh cáo vi phạm nào.";
  if (strikes === 1) return "Tài khoản đang có 1 lần cảnh cáo. Hãy cẩn trọng trong các chuyến sau.";
  if (strikes === 2) return "Cảnh báo cao: Chỉ cần thêm 1 lần vi phạm, tài khoản sẽ bị tạm khóa!";
  return "Đang có vi phạm hiệu lực.";
});

const strikeMeterSubtextClass = computed(() => {
  if (summary.value?.is_account_suspended) return "text-rose-700 font-bold";
  const strikes = summary.value?.active_strikes ?? 0;
  if (strikes >= 2) return "text-orange-700 font-bold";
  if (strikes === 1) return "text-amber-700 font-bold";
  return "text-emerald-700";
});

// Helpers for badges and dates
const formatDate = (dateStr?: string | null) => {
  if (!dateStr) return "N/A";
  const d = new Date(dateStr);
  return d.toLocaleDateString("vi-VN", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
};

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

// Fetch data
const fetchSummary = async () => {
  try {
    const res = await reportService.getOwnerSummary();
    if (res.success && res.data) {
      summary.value = res.data;
    }
  } catch (err) {
    console.error("Error loading owner report summary:", err);
  }
};

const fetchReports = async () => {
  try {
    reportsLoading.value = true;
    const params: any = {
      page: currentPage.value,
      per_page: 10,
    };
    if (activeFilter.value !== "all") {
      params.status = activeFilter.value;
    }
    if (searchQuery.value.trim()) {
      params.search = searchQuery.value.trim();
    }

    const res = await reportService.getOwnerReports(params);
    if (res.success && res.data) {
      reports.value = res.data;
      pagination.value = res.pagination;
    }
  } catch (err) {
    console.error("Error loading owner reports:", err);
  } finally {
    reportsLoading.value = false;
  }
};

const selectFilter = (tabValue: string) => {
  activeFilter.value = tabValue;
  currentPage.value = 1;
  fetchReports();
};

const handleSearch = () => {
  currentPage.value = 1;
  fetchReports();
};

const changePage = (newPage: number) => {
  currentPage.value = newPage;
  fetchReports();
  window.scrollTo({ top: 400, behavior: "smooth" });
};

const openImageModal = (url: string) => {
  selectedImage.value = url;
};

onMounted(async () => {
  loading.value = true;
  await Promise.all([fetchSummary(), fetchReports()]);
  loading.value = false;
});
</script>
