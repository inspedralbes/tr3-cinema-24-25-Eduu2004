<template>
  <div>
    <div :id="elementId" class="g-recaptcha"></div>
    <div v-if="error" class="text-danger small mt-2">{{ error }}</div>
    <div v-if="loading" class="text-muted small">Carregant reCAPTCHA...</div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'

const props = defineProps({
  sitekey: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['update:modelValue'])
const elementId = `recaptcha-${Math.random().toString(36).slice(2, 11)}`
const token = ref('')
const error = ref('')
const loading = ref(true)
const widgetId = ref(null)
const scriptLoaded = ref(false)
const isInitializing = ref(false)

const loadScript = () => {
  return new Promise((resolve, reject) => {
    if (typeof window.grecaptcha !== 'undefined') {
      scriptLoaded.value = true
      return resolve()
    }

    if (document.querySelector('#recaptcha-script')) {
      const checkInterval = setInterval(() => {
        if (typeof window.grecaptcha !== 'undefined') {
          clearInterval(checkInterval)
          scriptLoaded.value = true
          resolve()
        }
      }, 100)
      return
    }

    const script = document.createElement('script')
    script.id = 'recaptcha-script'
    script.src = `https://www.google.com/recaptcha/api.js?render=explicit&hl=ca&onload=recaptchaOnLoad`
    script.async = true
    script.defer = true

    window.recaptchaOnLoad = () => {
      scriptLoaded.value = true
      resolve()
    }

    script.onerror = () => {
      error.value = 'Error carregant reCAPTCHA'
      loading.value = false
      reject()
    }

    document.head.appendChild(script)
  })
}

const renderWidget = async () => {
  try {
    if (!scriptLoaded.value || typeof window.grecaptcha === 'undefined') {
      throw new Error('reCAPTCHA no està carregat')
    }

    if (widgetId.value !== null) {
      window.grecaptcha.reset(widgetId.value)
    }

    await nextTick()

    widgetId.value = window.grecaptcha.render(elementId, {
      sitekey: props.sitekey,
      theme: 'light',
      size: 'normal',
      callback: (response) => {
        token.value = response
        emit('update:modelValue', response)
        error.value = ''
      },
      'expired-callback': () => reset(),
      'error-callback': () => reset()
    })

    loading.value = false
  } catch (e) {
    console.error('Error renderitzant reCAPTCHA:', e)
    error.value = 'Error inicialitzant reCAPTCHA'
    loading.value = false
    setTimeout(() => renderWidget(), 500)
  }
}

const initializeRecaptcha = async () => {
  if (isInitializing.value) return
  isInitializing.value = true

  try {
    error.value = ''
    loading.value = true

    if (!scriptLoaded.value) {
      await loadScript()
    }

    await renderWidget()
  } catch (err) {
    error.value = 'Error carregant reCAPTCHA'
    console.error('Error inicialitzant:', err)
  } finally {
    isInitializing.value = false
  }
}

const reset = () => {
  token.value = ''
  emit('update:modelValue', '')
  if (scriptLoaded.value && widgetId.value !== null) {
    window.grecaptcha.reset(widgetId.value)
  }
}

onMounted(async () => {
  await initializeRecaptcha()
})

onBeforeUnmount(() => {
  if (scriptLoaded.value && widgetId.value !== null) {
    window.grecaptcha.reset(widgetId.value)
  }
})

defineExpose({ reset, initializeRecaptcha })
</script>