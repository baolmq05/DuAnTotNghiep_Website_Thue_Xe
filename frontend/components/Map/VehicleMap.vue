<template>
  <div class="relative w-full h-full min-h-[550px] flex flex-col justify-end">
    <div id="goong-map-container" class="absolute inset-0"></div>

    <div
      v-if="!isMapLoaded"
      class="absolute inset-0 flex items-center justify-center bg-white z-20"
    >
      Đang tải bản đồ...
    </div>

    <!-- Bottom Carousel of Vehicles at Selected Location -->
    <div 
      v-if="isMapLoaded && activeCar && activeLocationVehicles.length > 0"
      class="absolute bottom-6 left-0 right-0 z-10 px-4 md:px-8 flex flex-col items-center animate-fade-in pointer-events-none"
    >
      <!-- Location Header Badge when multiple vehicles share location -->
      <div 
        v-if="activeLocationVehicles.length > 1"
        class="mb-2 bg-[#1e4e57]/95 backdrop-blur-md text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg flex items-center gap-1.5 pointer-events-auto border border-white/20"
      >
        <Icon name="lucide:map-pin" class="w-3.5 h-3.5 text-amber-400" />
        <span>{{ activeLocationVehicles.length }} xe tại vị trí này</span>
      </div>

      <!-- Carousel Container with Navigation Arrows -->
      <div class="relative w-full max-w-[1340px] flex items-center justify-center pointer-events-auto group">
        
        <!-- Left Button -->
        <button
          v-if="activeLocationVehicles.length > 4 && canScrollLeft"
          @click="scrollContainer('left')"
          class="absolute -left-2 md:-left-5 z-20 w-10 h-10 rounded-full bg-white text-slate-800 shadow-xl border border-slate-200 flex items-center justify-center transition-all duration-200 hover:scale-110 active:scale-95 hover:bg-slate-50"
          title="Xem xe trước"
        >
          <Icon name="heroicons:chevron-left-20-solid" class="w-6 h-6 text-slate-700" />
        </button>

        <!-- Cards List -->
        <div 
          ref="carouselRef"
          @scroll="updateScrollButtons"
          class="flex gap-4 overflow-x-auto no-scrollbar scroll-smooth py-2 px-1 snap-x snap-mandatory w-full"
          :class="activeLocationVehicles.length <= 4 ? 'justify-center' : 'justify-start'"
        >
          <div 
            v-for="car in activeLocationVehicles"
            :key="car.id"
            :id="`map-car-card-${car.id}`"
            class="flex-shrink-0 w-[290px] sm:w-[320px] md:w-[340px] bg-white rounded-2xl border shadow-xl p-3 flex gap-3 snap-center cursor-pointer transition-all duration-200 overflow-hidden"
            :class="car.id === activeCarId ? 'ring-2 ring-[#286874] border-[#286874] bg-emerald-50/20 shadow-2xl scale-[1.02]' : 'hover:border-slate-300 hover:shadow-lg opacity-95 hover:opacity-100 bg-white'"
            @click="setActiveCar(car)"
          >
            <!-- Left Side: Image -->
            <div class="relative w-24 h-22 md:w-26 md:h-24 rounded-xl overflow-hidden shrink-0">
              <img :src="car.image" :alt="car.name" class="w-full h-full object-cover" />
              <span v-if="car.discount > 0" class="absolute top-1 left-1 bg-rose-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">
                -{{ car.discount }}%
              </span>
            </div>

            <!-- Right Side: Details -->
            <div class="flex flex-col justify-between flex-grow min-w-0 overflow-hidden">
              <div>
                <h4 class="font-extrabold text-xs md:text-sm text-slate-800 truncate leading-snug">
                  {{ car.name }}
                </h4>
                
                <!-- Rating & Trips -->
                <div class="flex items-center gap-1.5 mt-1 text-[10px] md:text-xs">
                  <span class="flex items-center gap-0.5 text-amber-500 font-bold">
                    <Icon name="heroicons:star-solid" class="w-3.5 h-3.5" />
                    {{ (car.rating || 5.0).toFixed(1) }}
                  </span>
                  <span class="text-slate-300">•</span>
                  <span class="text-slate-500 font-medium">{{ car.trips }} chuyến</span>
                </div>

                <!-- Location -->
                <div class="flex items-center gap-1 mt-1 text-[10px] md:text-xs text-slate-400">
                  <Icon name="lucide:map-pin" class="w-3 h-3 shrink-0" />
                  <span class="truncate">{{ car.location }}</span>
                </div>
              </div>

              <!-- Pricing & Action -->
              <div class="flex items-center justify-between gap-1.5 mt-1.5 pt-1.5 border-t border-slate-100 min-w-0">
                <div class="flex flex-col min-w-0">
                  <span v-if="car.discount_value > 0" class="text-slate-400 line-through text-[9px] md:text-[10px] font-normal leading-none mb-0.5">
                    {{ car.price ? car.price.toLocaleString('vi-VN') : '0' }} VNĐ
                  </span>
                  <span class="text-[#286874] font-black text-xs md:text-sm leading-tight truncate">
                    {{ ((car.price || 0) - (car.discount_value || 0)).toLocaleString('vi-VN') }} VNĐ<span class="text-slate-500 text-[10px] font-normal">/ngày</span>
                  </span>
                </div>
                
                <!-- Detail action -->
                <a 
                  :href="`/vehicles/${car.id}`"
                  class="bg-[#1e4e57] hover:bg-[#286874] text-white font-bold text-[10px] md:text-xs px-2.5 py-1.5 rounded-lg transition-colors leading-none shrink-0 whitespace-nowrap"
                  @click.stop
                >
                  Chi tiết
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Button -->
        <button
          v-if="activeLocationVehicles.length > 4 && canScrollRight"
          @click="scrollContainer('right')"
          class="absolute -right-2 md:-right-5 z-20 w-10 h-10 rounded-full bg-white text-slate-800 shadow-xl border border-slate-200 flex items-center justify-center transition-all duration-200 hover:scale-110 active:scale-95 hover:bg-slate-50"
          title="Xem xe tiếp theo"
        >
          <Icon name="heroicons:chevron-right-20-solid" class="w-6 h-6 text-slate-700" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, nextTick, onBeforeUnmount, computed } from "vue";
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

const props = defineProps<{
    active?: boolean;
    vehicles?: any[];
}>();

const isMapLoaded = ref(false);
const activeCarId = ref<number | null>(null);
const carouselRef = ref<HTMLElement | null>(null);
const canScrollLeft = ref(false);
const canScrollRight = ref(false);

const activeVehicles = computed(() => {
  if (!props.vehicles) return [];
  return props.vehicles.filter((car) => car.status == 1 || car.status === '1');
});

const activeCar = computed(() => {
  if (!activeCarId.value || !activeVehicles.value.length) return null;
  return activeVehicles.value.find(car => car.id === activeCarId.value) || null;
});

const activeLocationVehicles = computed(() => {
  if (!activeCarId.value || !activeVehicles.value.length) return [];
  
  // Find group marker that contains activeCarId
  const group = markerGroupDataList.find(g => g.carIds.includes(activeCarId.value!));
  if (group) {
    return activeVehicles.value.filter(v => group.carIds.includes(v.id));
  }

  const currentCar = activeCar.value;
  if (!currentCar) return [];
  return activeVehicles.value.filter(v => 
    (v.coords && currentCar.coords && v.coords === currentCar.coords) ||
    v.location === currentCar.location
  );
});

let mapInstance: maplibregl.Map | null = null;

interface MarkerGroupData {
    key: string;
    carIds: number[];
    marker: maplibregl.Marker;
    element: HTMLElement;
    coords: {
        lat: number;
        lng: number;
    };
}

let markerGroupDataList: MarkerGroupData[] = [];

const geocodeCache = new Map<
string,
{
    lat: number;
    lng: number;
}
>();

import { GOONG_API_KEY, GOONG_MAP_KEY } from '~/constants/goong';

const geocodeAddress = async (address: string) => {
    if (!address || address === "Chưa cập nhật")
        return null;

    if (geocodeCache.has(address))
        return geocodeCache.get(address)!;

    try {
        const res = await fetch(
            `https://rsapi.goong.io/Geocode?address=${encodeURIComponent(address)}&api_key=${GOONG_API_KEY}`
        );

        const data = await res.json();

        if (data.results?.length) {
            const location = data.results[0].geometry.location;
            const coords = {
                lat: location.lat,
                lng: location.lng
            };
            geocodeCache.set(address, coords);
            return coords;
        }
    } catch (err) {
        console.error(err);
    }

    return null;
};

const setActiveCar = (car: any) => {
    activeCarId.value = car.id;

    const group = markerGroupDataList.find(
        x => x.carIds.includes(car.id)
    );

    if (!group) return;

    mapInstance?.flyTo({
        center: [
            group.coords.lng,
            group.coords.lat
        ],
        zoom: 15,
        speed: 0.8,
        essential: true,
    });
};

const updateScrollButtons = () => {
  if (!carouselRef.value) return;
  const { scrollLeft, scrollWidth, clientWidth } = carouselRef.value;
  canScrollLeft.value = scrollLeft > 5;
  canScrollRight.value = scrollLeft + clientWidth < scrollWidth - 5;
};

const scrollContainer = (direction: 'left' | 'right') => {
  if (!carouselRef.value) return;
  const scrollAmount = carouselRef.value.clientWidth * 0.75;
  carouselRef.value.scrollBy({
    left: direction === 'left' ? -scrollAmount : scrollAmount,
    behavior: 'smooth'
  });
  setTimeout(updateScrollButtons, 350);
};

function renderMarker(markerItem: MarkerGroupData, cars: any[]) {
    const isActive = cars.some(c => c.id === activeCarId.value);
    const count = cars.length;

    markerItem.element.innerHTML = `
    <div class="relative flex items-center justify-center">
      <div
        class="
        w-12
        h-12
        rounded-full
        border-2
        border-white
        shadow-xl
        flex
        items-center
        justify-center
        transition-all
        duration-300
        ${isActive ? 'bg-[#286874] scale-110 ring-4 ring-[#286874]/40 marker-active' : 'bg-[#1e4e57] hover:bg-[#286874]'}"
      >
        <span style="font-size:16px; color: white;">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
            <path fill="currentColor" d="M16 6H6l-5 6v3h2a3 3 0 0 0 3 3a3 3 0 0 0 3-3h6a3 3 0 0 0 3 3a3 3 0 0 0 3-3h2v-3c0-1.11-.89-2-2-2h-2zM6.5 7.5h4V10h-6zm5.5 0h3.5l1.96 2.5H12zm-6 6A1.5 1.5 0 0 1 7.5 15A1.5 1.5 0 0 1 6 16.5A1.5 1.5 0 0 1 4.5 15A1.5 1.5 0 0 1 6 13.5m12 0a1.5 1.5 0 0 1 1.5 1.5a1.5 1.5 0 0 1-1.5 1.5a1.5 1.5 0 0 1-1.5-1.5a1.5 1.5 0 0 1 1.5-1.5"/>
          </svg>
        </span>
      </div>
      ${count > 1 ? `<span class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white font-black text-[11px] px-1.5 py-0.5 rounded-full shadow-lg border-2 border-white min-w-[22px] text-center leading-none">${count}</span>` : ''}
    </div>
    `;
}

const updateMarkers = async () => {
    if (!mapInstance || !isMapLoaded.value) return;

    markerGroupDataList.forEach(item => item.marker.remove());
    markerGroupDataList = [];

    const vehicles = activeVehicles.value;

    if (!vehicles.length) {
        activeCarId.value = null;
        return;
    }

    const result = await Promise.all(
        vehicles.map(async (car) => {
            let coords = null;

            if (car.coords) {
                const p = car.coords.split(",");
                if (p.length === 2) {
                    const lat = parseFloat(p[0]);
                    const lng = parseFloat(p[1]);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        coords = { lat, lng };
                    }
                }
            }

            if (!coords) {
                coords = await geocodeAddress(car.location);
            }

            if (!coords) {
                coords = {
                    lat: 10.762622 + (Math.random() - 0.5) * 0.03,
                    lng: 106.660172 + (Math.random() - 0.5) * 0.03,
                };
            }

            return {
                car,
                coords,
            };
        })
    );

    // Group vehicles by coordinate key (5 decimal places precision ~ 1m)
    const groupsMap = new Map<string, { coords: { lat: number; lng: number }; cars: any[] }>();

    result.forEach(({ car, coords }) => {
        const key = `${coords.lat.toFixed(5)}_${coords.lng.toFixed(5)}`;
        if (!groupsMap.has(key)) {
            groupsMap.set(key, { coords, cars: [] });
        }
        groupsMap.get(key)!.cars.push(car);
    });

    groupsMap.forEach(({ coords, cars }, key) => {
        if (!mapInstance) return;

        const element = document.createElement("div");
        element.className = "cursor-pointer transition-all duration-300";

        const marker = new maplibregl.Marker({
            element,
            anchor: "bottom",
        })
            .setLngLat([coords.lng, coords.lat])
            .addTo(mapInstance);

        const carIds = cars.map(c => c.id);

        const markerGroupData: MarkerGroupData = {
            key,
            carIds,
            marker,
            element,
            coords,
        };

        markerGroupDataList.push(markerGroupData);

        renderMarker(markerGroupData, cars);

        element.onclick = (e) => {
            e.stopPropagation();
            if (!activeCarId.value || !carIds.includes(activeCarId.value)) {
                activeCarId.value = cars[0].id;
            } else {
                mapInstance?.flyTo({
                    center: [coords.lng, coords.lat],
                    zoom: 15,
                    speed: 0.8,
                    essential: true,
                });
            }
        };
    });

    const bounds = new maplibregl.LngLatBounds();
    markerGroupDataList.forEach(item => {
        bounds.extend([
            item.coords.lng,
            item.coords.lat
        ]);
    });

    if (markerGroupDataList.length > 0) {
        mapInstance.fitBounds(bounds, {
            padding: {
                top: 80,
                bottom: 200,
                left: 60,
                right: 60,
            },
            maxZoom: 15,
        });
    }
};

const initMap = async () => {
  await nextTick();

  if (mapInstance) {
    mapInstance.remove();
  }

  mapInstance = new maplibregl.Map({
    container: "goong-map-container",
    style: `https://tiles.goong.io/assets/goong_map_web.json?api_key=${GOONG_MAP_KEY}`,
    center: [106.660172, 10.762622],
    zoom: 12,
  });

  mapInstance.addControl(new maplibregl.NavigationControl(), "top-right");

  mapInstance.on("click", () => {
    activeCarId.value = null;
  });

  mapInstance.on("load", () => {
    isMapLoaded.value = true;
    setTimeout(() => {
      mapInstance?.resize();
    }, 200);
  });
};

watch(
  () => props.active,
  async (active) => {
    if (active) {
      await initMap();
    } else {
      markerGroupDataList.forEach((item) => item.marker.remove());
      markerGroupDataList = [];
      mapInstance?.remove();
      mapInstance = null;
      isMapLoaded.value = false;
      activeCarId.value = null;
    }
  },
  {
    immediate: true,
  },
);

watch(
  [isMapLoaded, activeVehicles],
  ([mapLoaded, vehicles]) => {
    if (mapLoaded && vehicles) {
      updateMarkers();
    }
  },
  { immediate: true, deep: true }
);

watch(activeCarId, (newId) => {
    markerGroupDataList.forEach((item) => {
        const cars = activeVehicles.value.filter(v => item.carIds.includes(v.id));
        renderMarker(item, cars);

        if (newId && item.carIds.includes(newId)) {
            mapInstance?.flyTo({
                center: [
                    item.coords.lng,
                    item.coords.lat
                ],
                zoom: 15,
                speed: 0.8,
                essential: true,
            });
        }
    });

    if (newId) {
        nextTick(() => {
            const card = document.getElementById(
                `map-car-card-${newId}`
            );

            if (card) {
                card.scrollIntoView({
                    behavior: "smooth",
                    inline: "center",
                    block: "nearest",
                });
            }
            updateScrollButtons();
        });
    }
});

onBeforeUnmount(() => {
  markerGroupDataList.forEach((item) => item.marker.remove());
  markerGroupDataList = [];
  mapInstance?.remove();
});
</script>

<style scoped>
#goong-map-container {
  width: 100%;
  height: 100%;
}

.no-scrollbar::-webkit-scrollbar {
  display: none;
}

.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* ===========================
   Marker Icon
=========================== */

.vehicle-marker {
  transition: all .25s ease;
  transform-origin: bottom center;
}

.vehicle-marker:hover {
  transform: translateY(-5px) scale(1.05);
}

.vehicle-marker:active {
  transform: scale(.95);
}

/* ===========================
   Card Marker
=========================== */

.marker-card {

  animation: markerOpen .25s ease;

}

@keyframes markerOpen {

  from{

    opacity:0;
    transform:translateY(12px) scale(.92);

  }

  to{

    opacity:1;
    transform:translateY(0) scale(1);

  }

}

/* ===========================
   Floating Effect
=========================== */

.marker-active{

    animation: floatMarker 2.2s infinite;

}

@keyframes floatMarker{

    0%{

        transform:translateY(0);

    }

    50%{

        transform:translateY(-5px);

    }

    100%{

        transform:translateY(0);

    }

}

/* ===========================
   Carousel
=========================== */

.animate-fade-in {

    animation:fadeIn .35s ease;

}

@keyframes fadeIn{

    from{

        opacity:0;
        transform:translateY(15px);

    }

    to{

        opacity:1;
        transform:translateY(0);

    }

}

/* ===========================
   Nice Shadow
=========================== */

.shadow-map{

    box-shadow:
        0 12px 35px rgba(0,0,0,.18);

}
</style>