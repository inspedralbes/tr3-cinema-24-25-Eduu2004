// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: false,
  modules: [],
  css: [
    'bootstrap/dist/css/bootstrap.css',
    'normalize.css'
  ],
  
  runtimeConfig: {
    public: {
      RECAPTCHA_SITE_KEY: '6Lf71wArAAAAACMOAohcU-AaUYcDu799qAlHnNUo', 
    },
    // Clave secreta (solo accesible en server-side)
    RECAPTCHA_SECRET_KEY: '6Lf71wArAAAAANwQn4edfX7cV7cZ_QYQoEAKYMPU'
  },
  
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true }
})