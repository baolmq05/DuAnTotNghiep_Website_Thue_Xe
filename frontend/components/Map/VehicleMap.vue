<template>
  <div class="relative w-full h-full min-h-[550px] flex flex-col justify-end">
    <div id="goong-map-container" class="absolute inset-0"></div>

    <div
      v-if="!isMapLoaded"
      class="absolute inset-0 flex items-center justify-center bg-white z-20"
    >
      Đang tải bản đồ...
    </div>

    <!-- Bottom Carousel of Vehicles (Mioto Style) -->
    <div 
      v-if="isMapLoaded && activeCar && props.vehicles && props.vehicles.length > 0"
      class="absolute bottom-6 left-0 right-0 z-10 px-4 md:px-6 overflow-hidden animate-fade-in flex justify-center animate-fade-in"
    >
      <div 
        ref="carouselRef"
        class="flex gap-4 overflow-x-auto no-scrollbar scroll-smooth pb-3 snap-x snap-mandatory justify-center w-full"
      >
        <div 
          :key="activeCar.id"
          :id="`map-car-card-${activeCar.id}`"
          class="flex-shrink-0 w-[290px] md:w-[340px] bg-white rounded-2xl border shadow-xl p-2.5 flex gap-3 snap-center cursor-pointer transition-all duration-200 bg-emerald-50/10"
          @click="setActiveCar(activeCar)"
        >
          <!-- Left Side: Image -->
          <div class="relative w-24 h-20 md:w-28 md:h-24 rounded-xl overflow-hidden shrink-0">
            <img :src="activeCar.image" :alt="activeCar.name" class="w-full h-full object-cover" />
            <span v-if="activeCar.discount > 0" class="absolute top-1 left-1 bg-rose-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">
              -{{ activeCar.discount }}%
            </span>
          </div>

          <!-- Right Side: Details -->
          <div class="flex flex-col justify-between flex-grow min-w-0">
            <div>
              <h4 class="font-extrabold text-xs md:text-sm text-slate-800 truncate leading-snug">
                {{ activeCar.name }}
              </h4>
              
              <!-- Rating & Trips -->
              <div class="flex items-center gap-1.5 mt-1 text-[10px] md:text-xs">
                <span class="flex items-center gap-0.5 text-amber-500 font-bold">
                  <Icon name="heroicons:star-solid" class="w-3.5 h-3.5" />
                  {{ (activeCar.rating || 5.0).toFixed(1) }}
                </span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500 font-medium">{{ activeCar.trips }} chuyến</span>
              </div>

              <!-- Location -->
              <div class="flex items-center gap-1 mt-1 text-[10px] md:text-xs text-slate-400">
                <Icon name="lucide:map-pin" class="w-3 h-3 shrink-0" />
                <span class="truncate">{{ activeCar.location }}</span>
              </div>
            </div>

            <!-- Pricing -->
            <div class="items-baseline justify-between mt-1 pt-1 border-t border-slate-50">
              <div class="flex items-baseline gap-1.5">
                <span v-if="activeCar.discount_value > 0" class="text-slate-400 line-through text-[10px] md:text-xs font-normal">
                  {{ (activeCar.price / 1000).toLocaleString('vi-VN') }}K
                </span>
                <span class="text-[#286874] font-black text-xs md:text-sm">
                  {{ ((activeCar.price - activeCar.discount_value) / 1000).toLocaleString('vi-VN') }}K<span class="text-slate-500 text-[10px] md:text-xs font-normal">/ngày</span>
                </span>
              </div>
              
              <!-- Detail action -->
              <a 
                :href="`/vehicles/${activeCar.id}`"
                class="bg-[#1e4e57] hover:bg-[#286874] text-white font-bold text-[10px] md:text-xs px-2.5 py-1.5 rounded-lg transition-colors leading-none"
                @click.stop
              >
                Chi tiết
              </a>
            </div>
          </div>
        </div>
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
const carouselRef = ref<HTMLElement |null>(null);

const activeCar = computed(() => {
  if (!activeCarId.value || !props.vehicles) return null;
  return props.vehicles.find(car => car.id === activeCarId.value) || null;
});

let mapInstance: maplibregl.Map | null = null;

interface MarkerData{
    carId:number;
    marker:maplibregl.Marker;
    element:HTMLElement;
    coords:{
        lat:number;
        lng:number;
    };
}

let markerDataList:MarkerData[]=[];

const geocodeCache=new Map<
string,
{
    lat:number;
    lng:number;
}
>();

const GOONG_MAP_KEY="8Gh3kHiOvTsc6QHzNT4Aq0aFjH2I69PNiFyzk5Ex";
const GOONG_API_KEY="xEcFmnV3loWHnfqa9ZsEENH7Wu6lehK4QmabQk7V";

const geocodeAddress=async(address:string)=>{

    if(!address || address=="Chưa cập nhật")
        return null;

    if(geocodeCache.has(address))
        return geocodeCache.get(address)!;

    try{

        const res=await fetch(
            `https://rsapi.goong.io/Geocode?address=${encodeURIComponent(address)}&api_key=${GOONG_API_KEY}`
        );

        const data=await res.json();

        if(data.results?.length){

            const location=data.results[0].geometry.location;

            const coords={
                lat:location.lat,
                lng:location.lng
            };

            geocodeCache.set(address,coords);

            return coords;

        }

    }catch(err){

        console.error(err);

    }

    return null;

};
const setActiveCar = (car: any) => {

    activeCarId.value = car.id;

    const marker = markerDataList.find(
        x => x.carId === car.id
    );

    if (!marker) return;

    mapInstance?.flyTo({

        center: [
            marker.coords.lng,
            marker.coords.lat
        ],

        zoom: 15,

        speed: 0.8,

        essential: true,

    });

};

function renderMarker(markerItem:MarkerData,car:any){
    const isActive = markerItem.carId === activeCarId.value;

    markerItem.element.innerHTML=`
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
    ${isActive ? 'bg-[#286874] scale-110 ring-4 ring-[#286874]/40 marker-active' : 'bg-[#1e4e57] hover:bg-[#286874]' }">
        <span style="font-size:16px; color: white;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Material Design Icons by Pictogrammers - https://github.com/Templarian/MaterialDesign/blob/master/LICENSE --><path fill="currentColor" d="M16 6H6l-5 6v3h2a3 3 0 0 0 3 3a3 3 0 0 0 3-3h6a3 3 0 0 0 3 3a3 3 0 0 0 3-3h2v-3c0-1.11-.89-2-2-2h-2zM6.5 7.5h4V10h-6zm5.5 0h3.5l1.96 2.5H12zm-6 6A1.5 1.5 0 0 1 7.5 15A1.5 1.5 0 0 1 6 16.5A1.5 1.5 0 0 1 4.5 15A1.5 1.5 0 0 1 6 13.5m12 0a1.5 1.5 0 0 1 1.5 1.5a1.5 1.5 0 0 1-1.5 1.5a1.5 1.5 0 0 1-1.5-1.5a1.5 1.5 0 0 1 1.5-1.5"/></svg>

</span>
    </div>
    `;
}
const updateMarkers = async () => {

    if (!mapInstance || !isMapLoaded.value) return;

    markerDataList.forEach(item => item.marker.remove());
    markerDataList = [];

    const vehicles = props.vehicles || [];

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

    result.forEach(({ car, coords }) => {

        if (!mapInstance) return;

        const element = document.createElement("div");

        element.className =
            "cursor-pointer transition-all duration-300";

        const marker = new maplibregl.Marker({

            element,
            anchor: "bottom",

        })
            .setLngLat([coords.lng, coords.lat])
            .addTo(mapInstance);

        const markerData = {

            carId: car.id,
            marker,
            element,
            coords,

        };

        markerDataList.push(markerData);

        renderMarker(markerData, car);

        element.onclick = (e) => {
            e.stopPropagation();
            activeCarId.value = car.id;
        };

    });

    const bounds = new maplibregl.LngLatBounds();

    markerDataList.forEach(item => {

        bounds.extend([
            item.coords.lng,
            item.coords.lat
        ]);

    });

    mapInstance.fitBounds(bounds, {

        padding: {

            top: 80,
            bottom: 200,
            left: 60,
            right: 60,

        },

        maxZoom: 15,

    });

    // No auto-select first vehicle on load

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
      markerDataList.forEach((item) => item.marker.remove());
      markerDataList = [];
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
  [isMapLoaded, () => props.vehicles],
  ([mapLoaded, vehicles]) => {
    if (mapLoaded && vehicles) {
      updateMarkers();
    }
  },
  { immediate: true, deep: true }
);

watch(activeCarId, (newId) => {

    markerDataList.forEach((item) => {

        const car = props.vehicles?.find(
            (v) => v.id === item.carId
        );

        if (!car) return;

        renderMarker(item, car);

        if (newId && item.carId === newId) {

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

        });
    }

});

onBeforeUnmount(() => {
  markerDataList.forEach((item) => item.marker.remove());
  markerDataList = [];
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