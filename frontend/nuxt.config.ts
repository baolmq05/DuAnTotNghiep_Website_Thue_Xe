// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: ['@nuxtjs/tailwindcss', '@nuxt/fonts', '@nuxt/icon'],
  css: ['~/assets/css/tailwind.css'],
  app: {
    head: {
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/logo.png' }
      ],
      script: [
        {
          src: 'https://accounts.google.com/gsi/client',
          async: true,
          defer: true
        },
      ]
    }
  },
  fonts: {
    families: [
      {
        name: 'Inter',
        provider: 'google',
        weights: [300, 400, 500, 600, 700, 800],
        display: 'swap',
        fallbacks: ['sans-serif']
      }
    ]
  },
  tailwindcss: {
    config: {
      theme: {
        extend: {
          colors: {
            brand: {
              dark: '#10414F',       // Mã 1: Deep forest teal
              primary: '#286874',    // Mã 2: Primary brand teal
              secondary: 'rgb(248, 240, 232)', // Mã 2.5: Secondary beige
              accent: '#A77E52',     // Mã 3: Gold/Bronze accent
              light: '#FEE3CE',      // Mã 4: Warm cream/peach
            }
          }
        }
      }
    }
  }
})