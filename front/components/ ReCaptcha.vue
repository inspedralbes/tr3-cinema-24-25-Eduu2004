<template>
    <div>
      <div :id="elementId" class="g-recaptcha"></div>
      <div v-if="error" class="text-danger small mt-2">{{ error }}</div>
      <div v-if="loading" class="text-muted small">Carregant reCAPTCHA...</div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount } from 'vue'
  
  const props = defineProps({
    sitekey: {
      type: String,
      default: '6Lf71wArAAAAACMOAohcU-AaUYcDu799qAlHnNUo' // OK, pero mejor usar .env
    }
  })
  
  const emit = defineEmits(['update:modelValue'])
  
  // 1. Mejorar generación de ID único
  const elementId = `recaptcha-${crypto.randomUUID().slice(0, 8)}`
  const token = ref('')
  const error = ref('')
  const loading = ref(true)
  const widgetId = ref(null)
  
  // 2. Manejo mejorado de carga del script
  const loadScript = () => {
    return new Promise((resolve, reject) => {
      if (window.grecaptcha) {
        resolve()
        return
      }
  
      const script = document.createElement('script')
      script.src = `https://www.google.com/recaptcha/api.js?render=explicit&hl=ca`
      script.async = true
      script.defer = true
      script.onload = () => {
        if (!window.grecaptcha) {
          reject(new Error('reCAPTCHA no está disponible'))
          return
        }
        resolve()
      }
      script.onerror = (err) => {
        reject(new Error('Error cargando reCAPTCHA'))
      }
  
      document.head.appendChild(script)
    })
  }
  
  // 3. Renderizado seguro con verificación
  const renderWidget = () => {
    try {
      widgetId.value = window.grecaptcha.render(elementId, {
        sitekey: props.sitekey,
        theme: 'light', // Añadir tema
        size: 'normal', // Tamaño normal
        callback: (response) => {
          token.value = response
          emit('update:modelValue', response)
          error.value = ''
        },
        'expired-callback': () => {
          reset()
          error.value = 'La verificació ha caducat'
        },
        'error-callback': () => {
          reset()
          error.value = 'Error en la verificació'
        }
      })
      loading.value = false
    } catch (e) {
      error.value = 'Error inicialitzant reCAPTCHA'
      loading.value = false
    }
  }
  
  // 4. Reset mejorado
  const reset = () => {
    token.value = ''
    emit('update:modelValue', '')
    if (window.grecaptcha && widgetId.value !== null) {
      window.grecaptcha.reset(widgetId.value)
    }
  }
  
  // 5. Gestionar montaje/desmontaje
  onMounted(async () => {
    try {
      await loadScript()
      renderWidget()
    } catch (err) {
      error.value = err.message
      loading.value = false
    }
  })
  
  onBeforeUnmount(() => {
    if (window.grecaptcha && widgetId.value !== null) {
      window.grecaptcha.reset(widgetId.value)
    }
  })
  
  defineExpose({ reset })
  </script>