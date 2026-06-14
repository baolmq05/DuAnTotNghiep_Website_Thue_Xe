<template>
  <div class="relative w-full h-full min-h-[500px]">
    <div id="goong-map-container" class="absolute inset-0"></div>

    <div
      v-if="!isMapLoaded"
      class="absolute inset-0 flex items-center justify-center bg-white"
    >
      Đang tải bản đồ...
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, nextTick, onBeforeUnmount } from "vue";

import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

const props = defineProps<{
  active?: boolean;
}>();

const isMapLoaded = ref(false);

let mapInstance: maplibregl.Map | null = null;

const GOONG_MAP_KEY = "8Gh3kHiOvTsc6QHzNT4Aq0aFjH2I69PNiFyzk5Ex";

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
      mapInstance?.remove();
      mapInstance = null;
      isMapLoaded.value = false;
    }
  },
  {
    immediate: true,
  },
);

onBeforeUnmount(() => {
  mapInstance?.remove();
});
</script>

<style scoped>
#goong-map-container {
  width: 100%;
  height: 100%;
}
</style>
