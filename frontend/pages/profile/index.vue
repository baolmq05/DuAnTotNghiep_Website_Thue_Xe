<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Account Information -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h2 class="text-xl md:text-2xl font-semibold">Thông tin tài khoản</h2>

        <div class="flex items-center gap-3">
          <button @click="openEditModal"
            class="px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-xl hover:bg-brand-dark transition-colors flex items-center gap-1.5 focus:outline-none">
            <Icon name="ic:outline-edit" />
            Chỉnh sửa
          </button>

          <div class="flex items-center gap-2 px-4 py-2 border rounded-xl text-green-500 w-fit text-sm">
            <Icon name="ic:outline-stars" size="20" />
            <span>0 chuyến</span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Avatar -->
        <div class="lg:col-span-4">
          <div class="flex flex-col items-center">
            <div class="relative group">
              <img :src="user?.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100'"
                class="w-28 h-28 md:w-32 md:h-32 rounded-full object-cover border border-slate-100 shadow-sm"
                referrerpolicy="no-referrer"
                alt="User Avatar" />

              <button type="button" @click="openAvatarEditModal"
                class="absolute inset-0 rounded-full bg-slate-950/0 group-hover:bg-slate-950/45 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100 focus:outline-none">
                <span
                  class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-xs font-bold text-slate-800 shadow-lg">
                  <Icon name="ic:outline-edit" />
                  Sửa ảnh
                </span>
              </button>
            </div>

            <h3 class="mt-4 text-xl md:text-2xl font-semibold text-center">
              {{ user?.name || 'Người dùng' }}
            </h3>

            <p class="text-sm text-gray-500 mt-1">
              Tham gia: {{ user?.created_at ? new Date(user.created_at).toLocaleDateString('vi-VN') : '13/05/2026' }}
            </p>

            <div class="mt-4 border border-slate-200 bg-slate-50/50 rounded-xl px-4 py-2 flex items-center gap-1.5 shadow-sm">
              <Icon name="ic:outline-star" class="text-yellow-500" size="20" />
              <span class="font-bold text-slate-800 text-sm">
                {{ user?.rating ? parseFloat(user.rating).toFixed(1) : '0' }} sao
              </span>
            </div>
          </div>
        </div>

        <!-- Information -->
        <div class="lg:col-span-8">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-gray-500 text-sm">Ngày sinh</p>

              <p class="font-medium mt-2">
                {{ user?.DOB ? new Date(user.DOB).toLocaleDateString('vi-VN') : 'Chưa cập nhật' }}</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-gray-500 text-sm">Giới tính</p>

              <p class="font-medium mt-2">{{ user?.gender === 1 ? 'Nam' : (user?.gender === 0 ? 'Nữ' : 'Khác') }}</p>
            </div>
          </div>

          <div class="mt-6 space-y-4">
            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Số điện thoại </span>

              <span class="font-medium break-all">{{ user?.phone || 'Chưa cập nhật' }}</span>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Email </span>

              <span class="font-medium break-all">{{ user?.email || 'Chưa cập nhật' }}</span>
            </div>

            <!-- <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Facebook </span>

              <span class="font-medium"> Thêm liên kết </span>
            </div> -->

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Google </span>

              <span class="font-medium">{{ user?.name || 'Chưa liên kết' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Driving License -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap items-center gap-3">
          <h2 class="text-xl md:text-2xl font-semibold">Giấy phép lái xe</h2>

          <span v-if="!user?.driving_license"
            class="px-3 py-1 rounded-full bg-red-100 text-red-500 text-sm font-medium">
            Chưa xác thực
          </span>
          <span v-else-if="user.driving_license.status === 0"
            class="px-3 py-1 rounded-full bg-amber-100 text-amber-600 text-sm font-medium">
            Chờ duyệt
          </span>
          <span v-else-if="user.driving_license.status === 1"
            class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-600 text-sm font-medium">
            Đã xác thực
          </span>
          <span v-else-if="user.driving_license.status === 2"
            class="px-3 py-1 rounded-full bg-rose-100 text-rose-600 text-sm font-medium">
            Bị từ chối
          </span>
        </div>

        <div v-if="user?.driving_license && !isEditingLicense" class="flex gap-2">
          <button @click="startEditLicense"
            class="px-5 py-2 border rounded-xl flex items-center gap-2 w-fit hover:bg-slate-50 transition-all font-semibold text-sm focus:outline-none">
            Chỉnh sửa
            <Icon name="ic:outline-edit" />
          </button>
        </div>
      </div>

      <div v-if="user?.driving_license && user.driving_license.status === 2"
        class="mt-4 p-3 rounded-lg bg-rose-50 text-rose-700 text-sm">
        Bằng lái xe của bạn đã bị từ chối duyệt. Vui lòng cập nhật lại thông tin chính xác.
      </div>

      <div class="mt-5 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
        Hình chụp cần thấy được ảnh chân dung và số GPLX rõ ràng.
      </div>

      <!-- View state -->
      <div v-if="user?.driving_license && !isEditingLicense" class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">
        <div>
          <h3 class="font-semibold mb-4">Ảnh bằng lái xe</h3>
          <div
            class="h-[220px] md:h-[280px] rounded-xl overflow-hidden border border-slate-100 shadow-sm bg-slate-50 flex items-center justify-center">
            <img :src="user.driving_license.image" class="w-full h-full object-contain" alt="Driving License" />
          </div>
        </div>

        <div>
          <h3 class="font-semibold mb-4">Thông tin chung</h3>
          <div class="space-y-4">
            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-gray-500 text-xs">Số GPLX</p>
              <p class="font-bold mt-1 text-slate-800">{{ user.driving_license.driving_license_number }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-gray-500 text-xs">Họ và tên</p>
              <p class="font-bold mt-1 text-slate-800">{{ user.driving_license.full_name }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-gray-500 text-xs">Ngày sinh</p>
              <p class="font-bold mt-1 text-slate-800">{{ user.driving_license.DOB ? new
                Date(user.driving_license.DOB).toLocaleDateString('vi-VN') : 'Chưa cập nhật' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit / Create state -->
      <form v-else @submit.prevent="handleUpdateLicense" class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">
        <div>
          <h3 class="font-semibold mb-4">Ảnh mặt trước GPLX</h3>

          <!-- Image Select / Drop Zone -->
          <div @click="triggerLicenseFileInput" @dragover.prevent="isLicenseDragging = true"
            @dragleave.prevent="isLicenseDragging = false" @drop.prevent="onLicenseDrop"
            class="h-[220px] md:h-[280px] border-2 border-dashed rounded-xl flex flex-col items-center justify-center cursor-pointer relative overflow-hidden transition-all"
            :class="isLicenseDragging ? 'border-brand-primary bg-brand-primary/5' : (licenseImagePreview ? 'border-solid border-slate-200' : 'border-slate-300 hover:border-brand-primary')">
            <!-- Preview of selected image -->
            <img v-if="licenseImagePreview" :src="licenseImagePreview"
              class="w-full h-full object-contain absolute inset-0" />
            <div v-else-if="user?.driving_license?.image"
              class="w-full h-full absolute inset-0 bg-slate-900/40 flex items-center justify-center group">
              <img :src="user.driving_license.image" class="w-full h-full object-contain absolute inset-0" />
              <div
                class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <Icon name="ic:outline-cloud-upload" size="40" class="text-white mb-2" />
                <span class="text-white text-xs font-semibold">Tải lên ảnh mới</span>
              </div>
            </div>

            <div v-if="!licenseImagePreview && !user?.driving_license?.image"
              class="flex flex-col items-center p-4 text-center">
              <Icon name="ic:outline-cloud-upload" size="40" class="text-green-500 mb-2" />
              <p class="text-xs text-slate-500 font-medium">Kéo thả ảnh vào đây hoặc nhấp để chọn file</p>
              <p class="text-[10px] text-slate-400 mt-1">Định dạng JPG, PNG tối đa 5MB</p>
            </div>

            <!-- Hidden input -->
            <input type="file" ref="licenseFileInputRef" @change="onLicenseFileChange" accept="image/*"
              class="hidden" />
          </div>
        </div>

        <div>
          <h3 class="font-semibold mb-4">Thông tin chung</h3>

          <div class="space-y-4">
            <div>
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Số GPLX</label>
              <input v-model="licenseForm.driving_license_number" required placeholder="Nhập số GPLX"
                class="w-full bg-gray-50 rounded-xl p-3 outline-none border focus:border-brand-primary focus:bg-white transition-all text-sm font-medium" />
            </div>

            <div>
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Họ và tên</label>
              <input v-model="licenseForm.full_name" required placeholder="Họ và tên"
                class="w-full bg-gray-50 rounded-xl p-3 outline-none border focus:border-brand-primary focus:bg-white transition-all text-sm font-medium" />
            </div>

            <div>
              <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Ngày sinh</label>
              <input v-model="licenseForm.DOB" required type="date"
                class="w-full bg-gray-50 rounded-xl p-3 outline-none border focus:border-brand-primary focus:bg-white transition-all text-sm font-medium" />
            </div>

            <!-- Form actions -->
            <div class="flex gap-3 pt-2">
              <button v-if="user?.driving_license" type="button" @click="cancelEditLicense"
                class="py-2.5 px-4 border border-slate-200 text-slate-500 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-700 transition-colors focus:outline-none text-xs w-1/2">
                Hủy
              </button>
              <button type="submit" :disabled="submittingLicense"
                class="py-2.5 px-4 bg-brand-primary hover:bg-brand-dark text-white font-bold rounded-xl transition-all focus:outline-none text-xs shadow-md shadow-brand-primary/10 flex items-center justify-center gap-2"
                :class="user?.driving_license ? 'w-1/2' : 'w-full'">
                <Icon v-if="submittingLicense" name="svg-spinners:ring-resize" class="w-4 h-4" />
                <span>{{ user?.driving_license ? 'Lưu thay đổi' : 'Gửi yêu cầu duyệt' }}</span>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Payment -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center mb-6">
        <h2 class="text-xl md:text-2xl font-semibold">Thẻ thanh toán</h2>

        <button
          v-if="!user?.bank_name"
          @click="openBankModal"
          class="px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-xl hover:bg-brand-dark transition-colors focus:outline-none shadow-sm flex items-center gap-1.5"
        >
          <Icon name="ic:outline-add" />
          Thêm thẻ
        </button>
        <button
          v-else
          @click="openBankModal"
          class="px-4 py-2 border border-slate-200 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors focus:outline-none shadow-sm flex items-center gap-1.5"
        >
          <Icon name="ic:outline-edit" />
          Chỉnh sửa thẻ
        </button>
      </div>

      <div v-if="user?.bank_name" class="max-w-md">
        <!-- Bank card using custom premium styling -->
        <div class="relative overflow-hidden bg-gradient-to-br from-[#1e4e57] to-[#286874] text-white rounded-2xl p-6 shadow-md border border-[#286874]/20 animate-scale-in">
          <div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full bg-white/5 pointer-events-none"></div>
          <div class="absolute -left-10 -top-10 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>
          
          <div class="flex justify-between items-start mb-8">
            <div class="flex flex-col">
              <span class="text-xs uppercase tracking-wider text-cyan-200/80 font-bold">Ngân hàng liên kết</span>
              <span class="text-lg font-black tracking-tight mt-1">{{ user.bank_name }}</span>
            </div>
            <Icon name="lucide:landmark" class="text-cyan-100" size="32" />
          </div>
          
          <div class="mt-8">
            <span class="text-xs uppercase tracking-wider text-cyan-200/80 font-bold block mb-1">Số tài khoản</span>
            <span class="text-xl font-mono tracking-widest font-bold">{{ formatAccountNumber(user.bank_account_number) }}</span>
          </div>

          <div class="flex justify-between items-end mt-6">
            <span class="text-sm font-semibold uppercase tracking-wider text-white/90">{{ user.name }}</span>
            <span class="text-xs text-cyan-200/60 font-semibold">Drivio Verified</span>
          </div>
        </div>
      </div>

      <div v-else class="h-[220px] md:h-[300px] flex flex-col justify-center items-center text-gray-500 border border-dashed border-slate-200 rounded-2xl">
        <Icon name="ic:outline-credit-card" size="80" class="text-slate-300" />
        <p class="mt-4 text-center text-slate-400 font-medium text-sm">Bạn chưa có tài khoản ngân hàng nào</p>
      </div>
    </div>

    <!-- Car List -->

    <!-- Edit Profile Modal -->
    <div v-if="isEditModalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeEditModal"></div>

      <!-- Modal Content -->
      <div
        class="relative bg-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl z-10 p-8 border border-slate-100 flex flex-col animate-scale-in">
        <h3 class="text-xl font-black text-brand-dark mb-6">Chỉnh sửa thông tin cá nhân</h3>

        <form @submit.prevent="handleUpdateProfile" class="space-y-4">
          <!-- Name Field -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Họ và tên</label>
            <input v-model="editForm.name" type="text" required
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all" />
          </div>

          <!-- Phone Field -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Số điện thoại</label>
            <input v-model="editForm.phone" type="text" placeholder="Nhập số điện thoại"
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all" />
          </div>

          <!-- Gender & DOB Fields -->
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Giới tính</label>
              <select v-model="editForm.gender"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all">
                <option :value="null" disabled selected>Chọn giới tính</option>
                <option :value="1">Nam</option>
                <option :value="0">Nữ</option>
                <option :value="2">Khác</option>
              </select>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Ngày sinh</label>
              <input v-model="editForm.DOB" type="date"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all" />
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="grid grid-cols-2 gap-4 pt-4">
            <!-- Close Button -->
            <button type="button" @click="closeEditModal"
              class="py-3 px-4 border border-slate-200 text-slate-500 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-700 transition-colors focus:outline-none text-sm">
              Đóng
            </button>
            <!-- Submit Button -->
            <button type="submit"
              class="py-3 px-4 bg-brand-primary hover:bg-brand-dark text-white font-bold rounded-xl transition-all duration-200 focus:outline-none text-sm shadow-md shadow-brand-primary/10">
              Cập nhật thông tin
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Avatar Edit Modal -->
    <div v-if="isAvatarEditModalOpen" class="fixed inset-0 z-[1000] flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="closeAvatarEditModal"></div>

      <div class="relative z-10 w-full max-w-md rounded-3xl bg-white shadow-2xl border border-slate-100 p-5 md:p-6">
        <div class="flex items-start justify-between gap-4 mb-4">
          <div>
            <h3 class="text-xl font-black text-brand-dark">Sửa ảnh đại diện</h3>
            <p class="mt-1 text-sm text-slate-500">Tải ảnh mới lên, sau đó bấm lưu để cập nhật ảnh hồ sơ.</p>
          </div>

          <button type="button" @click="closeAvatarEditModal"
            class="h-10 w-10 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition-colors flex items-center justify-center">
            <Icon name="ic:outline-close" />
          </button>
        </div>

        <div class="space-y-4 mb-5">
          <div class="flex items-center justify-center">
            <div
              class="relative w-40 h-40 rounded-full overflow-hidden border-2 border-dashed border-slate-300 bg-slate-50 flex items-center justify-center cursor-pointer hover:border-brand-primary transition-colors"
              @click="triggerAvatarFileInput">
              <img v-if="avatarPreview" :src="avatarPreview" alt="Ảnh đại diện" referrerpolicy="no-referrer" class="w-full h-full object-cover" />

              <div v-else class="flex flex-col items-center justify-center text-center px-4">
                <Icon name="ic:outline-image" class="text-brand-primary" size="34" />
                <span class="mt-2 text-xs font-semibold text-slate-500">Chọn ảnh đại diện</span>
              </div>

              <button type="button"
                class="absolute bottom-3 left-1/2 -translate-x-1/2 rounded-full bg-white px-3 py-1.5 text-xs font-bold text-slate-800 shadow-lg hover:bg-slate-50"
                @click.stop="triggerAvatarFileInput">
                Chọn file
              </button>
            </div>
          </div>

          <input ref="avatarFileInputRef" type="file" accept="image/*" class="hidden" @change="onAvatarFileChange" />
        </div>

        <div class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
          <button type="button" @click="closeAvatarEditModal"
            class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
            Hủy
          </button>

          <button type="button" @click="handleUpdateAvatar" :disabled="isUploadingAvatar"
            class="px-4 py-2.5 rounded-xl bg-brand-primary text-white font-semibold hover:bg-brand-dark transition-colors disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <Icon v-if="isUploadingAvatar" name="svg-spinners:ring-resize" class="w-4 h-4" />
            <span>Lưu ảnh mới</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Edit Bank Modal -->
    <div v-if="isBankModalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeBankModal"></div>

      <!-- Modal Content -->
      <div
        class="relative bg-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl z-10 p-8 border border-slate-100 flex flex-col animate-scale-in">
        <h3 class="text-xl font-black text-brand-dark mb-6">Liên kết tài khoản ngân hàng</h3>

        <form @submit.prevent="handleUpdateBank" class="space-y-4">
          <!-- Bank Name Field -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tên ngân hàng</label>
            <select v-if="banksList.length > 0" v-model="bankForm.bank_name" required
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all font-semibold">
              <option value="" disabled>Chọn ngân hàng</option>
              <option v-for="bank in banksList" :key="bank.bin" :value="bank.short_name">
                {{ bank.short_name }} - {{ bank.name }}
              </option>
            </select>
            <input v-else v-model="bankForm.bank_name" type="text" required placeholder="Ví dụ: Vietcombank, Techcombank..."
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all font-semibold" />
          </div>

          <!-- Bank Account Number Field -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Số tài khoản</label>
            <input v-model="bankForm.bank_account_number" type="text" required placeholder="Nhập số tài khoản ngân hàng"
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all font-mono font-bold" />
          </div>

          <!-- Action Buttons -->
          <div class="grid grid-cols-2 gap-4 pt-4">
            <!-- Close Button -->
            <button type="button" @click="closeBankModal"
              class="py-3 px-4 border border-slate-200 text-slate-500 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-700 transition-colors focus:outline-none text-sm">
              Hủy
            </button>
            <!-- Submit Button -->
            <button type="submit" :disabled="submittingBank"
              class="py-3 px-4 bg-brand-primary hover:bg-brand-dark text-white font-bold rounded-xl transition-all duration-200 focus:outline-none text-sm shadow-md shadow-brand-primary/10 flex items-center justify-center gap-2">
              <Icon v-if="submittingBank" name="svg-spinners:ring-resize" class="w-4 h-4" />
              <span>Cập nhật</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
// import { myCarService, type Car } from '~/services/my_car.service'
import { BASE_URL } from '~/enviroment/enviroment'

definePageMeta({
  layout: 'profile',
})
useHead({
  title: 'DRIVIO - Thông tin tài khoản',
  meta: [
    { name: 'description', content: 'Thông tin tài khoản cá nhân, quản lý thông tin cá nhân' }
  ]
})

const { user, updateProfile, submitDrivingLicense, refreshProfile } = useAuth()
const { showToast } = useToast()

const isEditModalOpen = ref(false)
const isAvatarEditModalOpen = ref(false)
const loadingCars = ref(true)
// const userCars = ref<Car[]>([])

const editForm = reactive({
  name: '',
  phone: '',
  gender: 1,
  DOB: ''
})

const openEditModal = () => {
  if (user.value) {
    editForm.name = user.value.name || ''
    editForm.phone = user.value.phone || ''
    editForm.gender = user.value.gender !== undefined ? user.value.gender : 1
    editForm.DOB = user.value.DOB || ''
  }
  isEditModalOpen.value = true
}

const closeEditModal = () => {
  isEditModalOpen.value = false
}

const handleUpdateProfile = async () => {
  const res = await updateProfile({
    name: editForm.name,
    phone: editForm.phone,
    gender: editForm.gender,
    DOB: editForm.DOB
  })

  if (res.success) {
    showToast("Cập nhật thông tin thành công!", "success")
    closeEditModal()
  } else {
    showToast(res.message || "Cập nhật hồ sơ thất bại!", "error")
  }
}

// Driving license state
const isEditingLicense = ref(false)
const submittingLicense = ref(false)
const isLicenseDragging = ref(false)
const licenseFileInputRef = ref<HTMLInputElement | null>(null)
const licenseImageFile = ref<File | null>(null)
const licenseImagePreview = ref<string>('')

const licenseForm = reactive({
  driving_license_number: '',
  full_name: '',
  DOB: ''
})

const startEditLicense = () => {
  if (user.value?.driving_license) {
    licenseForm.driving_license_number = user.value.driving_license.driving_license_number || ''
    licenseForm.full_name = user.value.driving_license.full_name || ''
    licenseForm.DOB = user.value.driving_license.DOB || ''
  }
  licenseImagePreview.value = ''
  licenseImageFile.value = null
  isEditingLicense.value = true
}

const cancelEditLicense = () => {
  isEditingLicense.value = false
}

const triggerLicenseFileInput = () => {
  if (licenseFileInputRef.value) {
    licenseFileInputRef.value.click()
  }
}

const onLicenseFileChange = (e: Event) => {
  const input = e.target as HTMLInputElement
  if (input.files && input.files[0]) {
    setLicenseFile(input.files[0])
  }
}

const onLicenseDrop = (e: DragEvent) => {
  isLicenseDragging.value = false
  if (e.dataTransfer?.files && e.dataTransfer.files[0]) {
    setLicenseFile(e.dataTransfer.files[0])
  }
}

const setLicenseFile = (file: File) => {
  if (!file.type.startsWith('image/')) {
    showToast('Vui lòng chọn một tệp hình ảnh hợp lệ.', 'error')
    return
  }
  if (file.size > 5 * 1024 * 1024) {
    showToast('Dung lượng ảnh vượt quá 5MB.', 'error')
    return
  }
  licenseImageFile.value = file
  licenseImagePreview.value = URL.createObjectURL(file)
}

const handleUpdateLicense = async () => {
  if (!licenseForm.driving_license_number.trim()) {
    showToast('Vui lòng nhập số GPLX.', 'error')
    return
  }
  if (!licenseForm.full_name.trim()) {
    showToast('Vui lòng nhập họ và tên.', 'error')
    return
  }
  if (!licenseForm.DOB) {
    showToast('Vui lòng chọn ngày sinh.', 'error')
    return
  }
  if (!user.value?.driving_license && !licenseImageFile.value) {
    showToast('Vui lòng tải lên ảnh bằng lái xe.', 'error')
    return
  }

  submittingLicense.value = true
  try {
    let imageUrl = user.value?.driving_license?.image || ''

    if (licenseImageFile.value) {
      const CLOUD_NAME = "djbobb5oe"
      const UPLOAD_PRESET = "Drivio"

      const cloudinaryData = new FormData()
      cloudinaryData.append("file", licenseImageFile.value)
      cloudinaryData.append("upload_preset", UPLOAD_PRESET)

      const response = await $fetch<any>(
        `https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/upload`,
        {
          method: "POST",
          body: cloudinaryData,
        }
      )

      imageUrl = response.secure_url

      if (licenseImagePreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(licenseImagePreview.value)
      }
    }

    const formData = new FormData()
    formData.append('driving_license_number', licenseForm.driving_license_number)
    formData.append('full_name', licenseForm.full_name)
    formData.append('DOB', licenseForm.DOB)
    formData.append('image', imageUrl)

    const res = await submitDrivingLicense(formData)
    if (res.success) {
      showToast('Gửi duyệt bằng lái xe thành công!', 'success')
      isEditingLicense.value = false
      licenseImagePreview.value = ''
      licenseImageFile.value = null
    } else {
      showToast(res.message || 'Gửi duyệt bằng lái xe thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi gửi duyệt bằng lái:', err)
    showToast('Đã có lỗi xảy ra khi tải ảnh lên. Vui lòng thử lại sau.', 'error')
  } finally {
    submittingLicense.value = false
  }
}

// Helpers
const getThumbnailUrl = (images: any[] | undefined) => {
  const defaultImg = 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=600';
  if (!images || images.length === 0) {
    return defaultImg;
  }
  const primaryImg = images.find(img => img.is_thumbnail === 1) || images[0];
  const imgUrl = primaryImg.image_url || '';
  if (imgUrl.startsWith('http') || imgUrl.startsWith('/')) {
    return imgUrl;
  }
  return `${BASE_URL}storage/${imgUrl}`;
}

const formatPrice = (val: number) => {
  return val.toLocaleString('vi-VN') + 'đ';
};

onMounted(async () => {
  if (!user.value) {
    navigateTo('/')
    return
  }

  // Lấy dữ liệu mới nhất từ server để đồng bộ trạng thái duyệt bằng lái
  try {
    await refreshProfile()
  } catch (err) {
    console.error('Không thể cập nhật thông tin tài khoản mới nhất từ server:', err)
  }

  if (!user.value.driving_license) {
    isEditingLicense.value = true
  } else {
    licenseForm.driving_license_number = user.value.driving_license.driving_license_number || ''
    licenseForm.full_name = user.value.driving_license.full_name || ''
    licenseForm.DOB = user.value.driving_license.DOB || ''
  }

  // try {
  //   const res = await myCarService.getCars({ user_id: user.value.id })
  //   if (res.success && res.data) {
  //     userCars.value = res.data
  //   }
  // } catch (err) {
  //   console.error('Lỗi khi tải danh sách xe trong hồ sơ:', err)
  // } finally {
  //   loadingCars.value = false
  // }

})

// ==========================================
// AVATAR UPLOAD
// ==========================================
const isUploadingAvatar = ref(false)
const avatarFileInputRef = ref<HTMLInputElement | null>(null)
const avatarImageFile = ref<File | null>(null)
const avatarPreview = ref<string>(user.value?.avatar || '')

const triggerAvatarFileInput = () => {
  avatarFileInputRef.value?.click()
}

const onAvatarFileChange = (event: Event) => {
  const input = event.target as HTMLInputElement
  if (!input.files || !input.files[0]) return

  const file = input.files[0]
  if (!file.type.startsWith('image/')) {
    showToast('Vui lòng chọn một tệp hình ảnh hợp lệ.', 'error')
    input.value = ''
    return
  }

  if (file.size > 5 * 1024 * 1024) {
    showToast('Dung lượng ảnh vượt quá 5MB.', 'error')
    input.value = ''
    return
  }

  avatarImageFile.value = file

  if (avatarPreview.value.startsWith('blob:')) {
    URL.revokeObjectURL(avatarPreview.value)
  }

  avatarPreview.value = URL.createObjectURL(file)
  input.value = ''
}

const openAvatarEditModal = () => {
  avatarPreview.value = user.value?.avatar || ''
  avatarImageFile.value = null
  isAvatarEditModalOpen.value = true
}

const closeAvatarEditModal = () => {
  if (avatarPreview.value.startsWith('blob:')) {
    URL.revokeObjectURL(avatarPreview.value)
  }

  avatarPreview.value = user.value?.avatar || ''
  avatarImageFile.value = null
  isAvatarEditModalOpen.value = false
}

const handleUpdateAvatar = async () => {
  if (!avatarImageFile.value) {
    showToast('Vui lòng chọn ảnh trước khi lưu.', 'error')
    return
  }

  isUploadingAvatar.value = true
  try {
    const CLOUD_NAME = "djbobb5oe"
    const UPLOAD_PRESET = "Drivio"

    const cloudinaryData = new FormData()
    cloudinaryData.append("file", avatarImageFile.value)
    cloudinaryData.append("upload_preset", UPLOAD_PRESET)

    const response = await $fetch<any>(
      `https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/upload`,
      {
        method: "POST",
        body: cloudinaryData,
      }
    )

    const finalAvatarUrl = response.secure_url || ''

    // 2. Gọi API cập nhật profile lên backend database (dùng chung hàm updateProfile có sẵn của bạn)
    const res = await updateProfile({
      name: user.value?.name || '',
      phone: user.value?.phone || '',
      gender: user.value?.gender !== undefined ? user.value?.gender : 1,
      DOB: user.value?.DOB || '',
      avatar: finalAvatarUrl // Truyền field avatar mới lên Server DB
    })

    if (res.success) {
      showToast("Cập nhật ảnh đại diện thành công!", "success")
      closeAvatarEditModal()

      // 3. Cập nhật thủ công vào global state và LocalStorage nếu composable useAuth chưa tự làm
      if (user.value) {
        user.value.avatar = finalAvatarUrl
        if (typeof window !== "undefined") {
          localStorage.setItem("USER_INFO", JSON.stringify(user.value))
        }
      }
    } else {
      showToast(res.message || "Không thể lưu ảnh đại diện vào hệ thống.", "error")
    }
  } catch (error) {
    console.error("Lỗi cập nhật ảnh đại diện:", error)
    showToast("Đã xảy ra lỗi trong quá trình upload ảnh.", "error")
  } finally {
    isUploadingAvatar.value = false
  }
}

// ==========================================
// BANK ACCOUNT MANAGEMENT
// ==========================================
const isBankModalOpen = ref(false)
const submittingBank = ref(false)
const banksList = ref<any[]>([])
const bankForm = reactive({
  bank_name: '',
  bank_account_number: ''
})

const loadBanks = async () => {
  try {
    const res = await $fetch<any>('https://vietqr.app/banks.json')
    if (res && res.data) {
      banksList.value = res.data
    }
  } catch (err) {
    console.error('Lỗi khi tải danh sách ngân hàng từ VietQR:', err)
  }
}

const openBankModal = async () => {
  bankForm.bank_name = user.value?.bank_name || ''
  bankForm.bank_account_number = user.value?.bank_account_number || ''
  isBankModalOpen.value = true
  if (banksList.value.length === 0) {
    await loadBanks()
  }
}

const closeBankModal = () => {
  isBankModalOpen.value = false
}

const formatAccountNumber = (num: string) => {
  if (!num) return ''
  return num.replace(/(\d{4})/g, '$1 ').trim()
}

const handleUpdateBank = async () => {
  if (!bankForm.bank_name.trim()) {
    showToast('Vui lòng nhập tên ngân hàng.', 'error')
    return
  }
  if (!bankForm.bank_account_number.trim()) {
    showToast('Vui lòng nhập số tài khoản.', 'error')
    return
  }

  submittingBank.value = true
  try {
    const res = await updateProfile({
      name: user.value?.name || '',
      phone: user.value?.phone || '',
      gender: user.value?.gender !== undefined ? user.value?.gender : 1,
      DOB: user.value?.DOB || '',
      bank_name: bankForm.bank_name,
      bank_account_number: bankForm.bank_account_number
    })

    if (res.success) {
      showToast('Cập nhật tài khoản ngân hàng thành công!', 'success')
      closeBankModal()
    } else {
      showToast(res.message || 'Cập nhật tài khoản ngân hàng thất bại.', 'error')
    }
  } catch (err) {
    console.error('Lỗi khi cập nhật ngân hàng:', err)
    showToast('Đã xảy ra lỗi khi lưu thông tin. Vui lòng thử lại sau.', 'error')
  } finally {
    submittingBank.value = false
  }
}
</script>
