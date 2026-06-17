<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import {
    Card,
    CardContent,
} from '@/components/ui/card';

import { RoomService } from '@/services/room.service.js';
import { STATUS, OWNER_API } from '@/config/config.js';
import { onMounted, ref } from 'vue';

import Loading from '@/components/config/Owner/Loading.vue';
import Alert from '@/components/config/Owner/Alert.vue';

// Breadcrumb
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Thêm bài đăng',
        href: '/owner/room/create',
    },
];

// Const
const ALERT_SUCCESS = 'Đã gửi yêu cầu';
const ALERT_ERROR = 'Yêu cầu thất bại';

// Service
const roomService = new RoomService(OWNER_API.ROOM);

// State
const isCreating = ref(false);
const isAlert = ref(false);
const isError = ref(false);
const alertTitle = ref('');
const message = "Đang gửi";

const errors = ref<any>({});

// Form data
const roomObj = ref({
    title: '',
    address: '',
    acreage: '',
    description: '',
    price: '',
    category_id: 1,
    is_vip: 0,
    longitude: 123,
    latitude: 123,
    province_code: '123',
    ward_code: '123',
    status: 1
});

// Images
const thumbnail = ref<File | null>(null);
const thumbnailPreview = ref<string | null>(null);

const gallery = ref<File[]>([]);
const galleryPreview = ref<string[]>([]);

// ================= ACTION =================
const createAction = async () => {
    isCreating.value = true;
    errors.value = {};
    isError.value = false;

    try {
        const formData = new FormData();

        Object.entries(roomObj.value).forEach(([key, value]) => {
            formData.append(key, value ?? '');
        });

        if (thumbnail.value) {
            formData.append("thumbnail", thumbnail.value);
        }

        gallery.value.forEach((file, index) => {
            formData.append(`gallery[${index}]`, file);
        });

        const res = await roomService.create(formData);

        if (res.status === STATUS.CREATED) {
            alertTitle.value = ALERT_SUCCESS;
            isAlert.value = true;
            resetForm();
        }

    } catch (err: any) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors;
        } else {
            alertTitle.value = ALERT_ERROR;
            isError.value = true;
            isAlert.value = true;
        }
    } finally {
        isCreating.value = false;
        turnOffAlert();
    }
};

// ================= RESET =================
const resetForm = () => {
    roomObj.value = {
        title: '',
        address: '',
        acreage: '',
        description: '',
        price: '',
        category_id: 1,
        is_vip: 0,
        longitude: 0,
        latitude: 0,
        province_code: '',
        ward_code: '',
        status: 1
    };

    if (thumbnailPreview.value) {
        URL.revokeObjectURL(thumbnailPreview.value);
    }

    thumbnail.value = null;
    thumbnailPreview.value = null;

    galleryPreview.value.forEach(url => URL.revokeObjectURL(url));
    gallery.value = [];
    galleryPreview.value = [];
};

// ================= ALERT =================
const turnOffAlert = () => {
    setTimeout(() => {
        isAlert.value = false;
        isError.value = false;
    }, 2000);
};

// ================= IMAGE =================
const handleThumbnail = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    if (thumbnailPreview.value) {
        URL.revokeObjectURL(thumbnailPreview.value);
    }

    thumbnail.value = file;
    thumbnailPreview.value = URL.createObjectURL(file);
};

const handleGallery = (e: Event) => {
    const files = (e.target as HTMLInputElement).files;
    if (!files) return;

    for (let file of files) {
        gallery.value.push(file);
        galleryPreview.value.push(URL.createObjectURL(file));
    }
};

const removeImage = (index: number) => {
    URL.revokeObjectURL(galleryPreview.value[index]);

    gallery.value.splice(index, 1);
    galleryPreview.value.splice(index, 1);
};

// ================= MAP =================
const MAP_KEY = '8Gh3kHiOvTsc6QHzNT4Aq0aFjH2I69PNiFyzk5Ex';
const API_KEY = 'xEcFmnV3loWHnfqa9ZsEENH7Wu6lehK4QmabQk7V';
const DEFAULT_LOCATION = {
    lat: 16.047079,
    lng: 108.206230
};

const keyword = ref('');
const suggestions = ref<any[]>([]);
const mapRef = ref<any>(null);

const generateMap = async () => {
    const map = new maplibregl.Map({
        container: 'map',
        style: `https://tiles.goong.io/assets/goong_map_web.json?api_key=${MAP_KEY}`,
        center: [108.206230, 16.047079], // default
        zoom: 13
    });

    await map.on('load', () => {
        // Get Current Position
        navigator.geolocation.getCurrentPosition((position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // set center
            map.setCenter([lng, lat]);

            // marker
            new maplibregl.Marker({
                color: 'red',
            }).setLngLat([lng, lat]).addTo(map);

            // Save To Obj
            roomObj.value.latitude = lat;
            roomObj.value.longitude = lng;

            drawCircleOnMap(map, [lng, lat], 500);
        });
    });

    mapRef.value = map;
};

const drawCircleOnMap = (map, center, radiusInMeters) => {
    const points = 64;
    const coords = {
        latitude: center[1],
        longitude: center[0]
    };

    const km = radiusInMeters / 1000;
    const ret = [];

    const distanceX = km / (111.320 * Math.cos(coords.latitude * Math.PI / 180));
    const distanceY = km / 110.574;

    for (let i = 0; i < points; i++) {
        const theta = (i / points) * (2 * Math.PI);
        const x = distanceX * Math.cos(theta);
        const y = distanceY * Math.sin(theta);

        ret.push([
            coords.longitude + x,
            coords.latitude + y
        ]);
    }

    ret.push(ret[0]);

    map.addSource('circle', {
        type: 'geojson',
        data: {
            type: 'Feature',
            geometry: {
                type: 'Polygon',
                coordinates: [ret]
            }
        }
    });

    map.addLayer({
        id: 'circle-fill',
        type: 'fill',
        source: 'circle',
        paint: {
            'fill-color': '#0080ff',
            'fill-opacity': 0.3
        }
    });
};

const searchPlace = async () => {
    if (!keyword.value) {
        suggestions.value = [];
        return;
    }

    const res = await fetch(
        `https://rsapi.goong.io/Place/AutoComplete?api_key=${API_KEY}&input=${keyword.value}`
    );

    const data = await res.json();
    suggestions.value = data.predictions || [];
};

const selectPlace = async (item) => {
    keyword.value = item.description;
    suggestions.value = [];         

    const res = await fetch(
        `https://rsapi.goong.io/Place/Detail?place_id=${item.place_id}&api_key=${API_KEY}`
    );

    const data = await res.json();
    const location = data.result.geometry.location;

    const lat = location.lat;
    const lng = location.lng;

    // Change Position
    mapRef.value.setCenter([lng, lat]);
    mapRef.value.setZoom(16);

    // Marker Popup
    const popup = new maplibregl.Popup({
        offset: 25
    }).setText(item.description);

    // Marker + Popup
    new maplibregl.Marker({ color: 'red' })
        .setLngLat([lng, lat])
        .setPopup(popup)
        .addTo(mapRef.value)
        .togglePopup();

    // Save DB
    roomObj.value.latitude = lat;
    roomObj.value.longitude = lng;
    roomObj.value.address = item.description;
};

onMounted(async () => {
    await generateMap();
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Alert v-if="isAlert" :title="alertTitle" :isError="isError" />

        <Loading :message="message" v-if="isCreating" />

        <Head title="Room Create" />

        <!-- HEADER -->
        <div class="p-5 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Thêm bài đăng</h1>
            <Link href="/owner/room">
                <Button>Quay lại</Button>
            </Link>
        </div>

        <!-- CONTENT -->
        <div class="p-5">
            <Card>
                <CardContent>
                    <form class="flex gap-6">

                        <!-- LEFT -->
                        <div class="w-1/2 space-y-4">

                            <div class="mb-5">
                                <Label class="text-sm mb-3">Tiêu đề</Label>
                                <Input v-model="roomObj.title" />
                                <span class="text-red-400 text-sm">
                                    {{ errors.title?.[0] }}
                                </span>
                            </div>

                            <div class="mb-5">
                                <Label class="text-sm mb-3">Địa chỉ</Label>
                                <Input v-model="roomObj.address" />
                            </div>

                            <div class="mb-5">
                                <Label class="text-sm mb-3">Diện tích</Label>
                                <Input v-model="roomObj.acreage" type="number" />
                            </div>

                            <div class="mb-5">
                                <Label class="text-sm mb-3">Mô tả</Label>
                                <Input v-model="roomObj.description" />
                            </div>

                            <div class="mb-5">
                                <Label class="text-sm mb-3">Giá</Label>
                                <Input v-model="roomObj.price" type="number" />
                            </div>

                            <!-- MAP API -->
                            <div class="mb-3">
                                <input v-model="keyword" @input="searchPlace" placeholder="Nhập địa chỉ..."
                                    class="border p-2 w-full" />

                                <div v-if="suggestions.length" class="border bg-white">
                                    <div v-for="item in suggestions" :key="item.place_id"
                                        class="p-2 hover:bg-gray-100 cursor-pointer" @click="selectPlace(item)">
                                        {{ item.description }}
                                    </div>
                                </div>
                            </div>

                            <div id="map" style="height: 400px;"></div>

                            <Button @click="createAction" type="button" :disabled="isCreating">
                                {{ isCreating ? 'Đang gửi...' : 'Gửi yêu cầu' }}
                            </Button>

                        </div>

                        <!-- RIGHT -->
                        <div class="w-1/2 space-y-4">

                            <!-- Thumbnail -->
                            <div>
                                <Label>Ảnh đại diện</Label>

                                <label class="border rounded-lg p-6 flex items-center justify-center cursor-pointer">
                                    <input type="file" class="hidden" @change="handleThumbnail" />

                                    <img v-if="thumbnailPreview" :src="thumbnailPreview"
                                        class="w-full h-40 object-cover rounded-lg" />

                                    <span v-else class="text-gray-400">
                                        Upload ảnh đại diện
                                    </span>
                                </label>
                            </div>

                            <!-- Gallery -->
                            <div>
                                <Label>Thư viện ảnh</Label>

                                <label
                                    class="border rounded-lg p-6 flex items-center justify-center cursor-pointer mb-4">
                                    <input type="file" multiple class="hidden" @change="handleGallery" />
                                    <span class="text-gray-400">Upload thư viện</span>
                                </label>

                                <div class="grid grid-cols-3 gap-3">
                                    <div v-for="(img, index) in galleryPreview" :key="index" class="relative">
                                        <img :src="img" class="w-full h-32 object-cover rounded-lg" />

                                        <button type="button"
                                            class="absolute top-1 right-1 bg-black/50 text-white text-xs px-2 py-1 rounded hover:text-red-400"
                                            @click.prevent="removeImage(index)">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>

    </AppLayout>
</template>