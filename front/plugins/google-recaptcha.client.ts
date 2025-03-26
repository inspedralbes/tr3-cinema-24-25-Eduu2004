import { VueReCaptcha } from 'vue-recaptcha-v3';

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.use(VueReCaptcha, {
    siteKey: '6LdrZgArAAAAAHaf9d7ClIOAGEQblY1iSGDVW_BQ', // Tu clave pública de reCAPTCHA
  });
});
