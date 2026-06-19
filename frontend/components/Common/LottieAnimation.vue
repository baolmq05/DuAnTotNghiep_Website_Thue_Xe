<template>
    <div ref="container" />
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue'

const props = defineProps({
    path: String,
    loop: { type: Boolean, default: true },
    autoplay: { type: Boolean, default: true }
})

const container = ref(null)
let animation = null

onMounted(async () => {
    // import động để tránh SSR crash
    const lottie = (await import('lottie-web')).default

    animation = lottie.loadAnimation({
        container: container.value,
        renderer: 'svg',
        loop: props.loop,
        autoplay: props.autoplay,
        path: props.path
    })
})

onBeforeUnmount(() => {
    animation?.destroy()
})
</script>