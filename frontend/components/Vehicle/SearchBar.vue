<template>
    <div
        class="glass-search-bar max-w-4xl mx-auto shadow-xl rounded-2xl md:rounded-full p-4 md:p-3 flex flex-col md:flex-row items-stretch md:items-center justify-center gap-3 md:gap-0">

        <div
            class="flex-1 min-w-0 px-6 py-2 md:py-1 flex items-center gap-3 hover:bg-white/20 rounded-xl md:rounded-full transition-colors duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-primary flex-shrink-0" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <div class="flex-grow min-w-0">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Địa điểm</label>
                <input v-model="location" type="text" placeholder="Bạn muốn đi đâu?"
                    class="block w-full bg-transparent border-0 p-0 text-sm font-semibold text-slate-800 placeholder-slate-500 focus:ring-0 focus:outline-none" />
            </div>
        </div>

        <div class="hidden md:block h-8 w-px bg-slate-300/60 flex-shrink-0"></div>

        <div
            class="flex-1 min-w-0 px-6 py-2 md:py-1 flex items-center gap-3 hover:bg-white/20 rounded-xl md:rounded-full transition-colors duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 flex-shrink-0" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <div class="flex-grow min-w-0">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Ngày nhận</label>
                <input v-model="startDate" type="text" placeholder="Chọn ngày nhận xe"
                    class="block w-full bg-transparent border-0 p-0 text-sm font-semibold text-slate-800 placeholder-slate-500 focus:ring-0 focus:outline-none"
                    onfocus="this.type='date'; this.showPicker()" onblur="if(!this.value) this.type='text'" />
            </div>
        </div>

        <div class="hidden md:block h-8 w-px bg-slate-300/60 flex-shrink-0"></div>

        <div
            class="flex-1 min-w-0 px-6 py-2 md:py-1 flex items-center gap-3 hover:bg-white/20 rounded-xl md:rounded-full transition-colors duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 flex-shrink-0" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <div class="flex-grow min-w-0">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Ngày trả</label>
                <input v-model="endDate" type="text" placeholder="Chọn ngày trả xe"
                    class="block w-full bg-transparent border-0 p-0 text-sm font-semibold text-slate-800 placeholder-slate-500 focus:ring-0 focus:outline-none"
                    onfocus="this.type='date'; this.showPicker()" onblur="if(!this.value) this.type='text'" />
            </div>
        </div>

    </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'

const route = useRoute()
const router = useRouter()

const location = ref(route.query.location as string || '')
const startDate = ref(route.query.startDate as string || '')
const endDate = ref(route.query.endDate as string || '')

watch([location, startDate, endDate], ([newLocation, newStartDate, newEndDate]) => {
    router.push({
        query: {
            ...route.query,
            location: newLocation || undefined,
            startDate: newStartDate || undefined,
            endDate: newEndDate || undefined
        }
    })
})
</script>