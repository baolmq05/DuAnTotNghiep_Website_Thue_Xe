// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: ['@nuxtjs/tailwindcss', '@nuxt/fonts'],
  fonts: {
    provider: 'google', // sets default provider
    families: [
      {
        name: 'Roboto', // the 'canonical' name of the font used to look it up in a provider database
        provider: 'local', // you can override the provider on a per-family basis
        // provider specific options can be provided
        src: '~/public/roboto.woff2', // you can specify a source within your project
        // specific configuration will be used to generate `@font-face` definitions
        subsets: ['latin', 'greek'],
        display: 'swap', // or 'block'
        weight: [400, 700],
        style: 'normal',
        // and produce CSS overrides to reduce layout shift (using fontaine)
        fallbacks: ['Arial'],
      }
    ]
  }
})