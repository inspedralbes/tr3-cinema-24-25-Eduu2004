<template>
  <section class="vh-100" style="background-color: #fff;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block">
                <img
                  src="https://static.vecteezy.com/system/resources/previews/019/482/385/non_2x/vintage-poster-for-cinema-retro-cartoon-food-for-promotion-template-free-vector.jpg"
                  alt="formulari d'inici de sessió" class="img-fluid fixed-img" />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">
                  <h5 class="h1 fw-bold mb-0" style="letter-spacing: 1px;" v-if="!isRegister">
                    Inicia sessió al teu compte
                  </h5>
                  <h5 class="h1 fw-bold mb-0" style="letter-spacing: 1px;" v-else>
                    Crea un compte nou
                  </h5><br><br>

                  <form v-if="!isRegister" @submit.prevent="loginUser">
                    <div class="form-outline mb-4">
                      <input type="email" class="form-control form-control-lg" v-model="loginForm.email" required />
                      <label class="form-label">Correu electrònic</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" class="form-control form-control-lg" v-model="loginForm.password"
                        required />
                      <label class="form-label">Contrasenya</label>
                    </div>

                    <ReCaptcha v-if="!isRegister" ref="loginRecaptchaRef" v-model="loginRecaptchaToken"
                      :sitekey="config.public.RECAPTCHA_SITE_KEY" />
                    <div v-if="loginCaptchaError" class="text-danger mb-4">{{ loginCaptchaError }}</div><br>

                    <div class="pt-1 mb-4">
                      <button class="btn btn-dark btn-lg btn-block" type="submit">
                        Inicia sessió
                      </button>
                    </div>
                    <p class="mb-5 pb-lg-2" style="color: #393f81;">
                      No tens compte?
                      <a href="#" style="color: #393f81;" @click.prevent="toggleForm">Registra't aquí</a>
                    </p>
                    <div v-if="loginError" class="text-danger mt-2">{{ loginError }}</div>
                  </form>

                  <form v-else @submit.prevent="registerUser">
                    <div class="form-outline mb-4">
                      <input type="text" class="form-control form-control-lg" v-model="registerForm.name" required />
                      <label class="form-label">Nom</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="text" class="form-control form-control-lg" v-model="registerForm.lastname"
                        required />
                      <label class="form-label">Cognom</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="email" class="form-control form-control-lg" v-model="registerForm.email" required />
                      <label class="form-label">Correu electrònic</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="password" class="form-control form-control-lg" v-model="registerForm.password"
                        required />
                      <label class="form-label">Contrasenya</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="password" class="form-control form-control-lg"
                        v-model="registerForm.password_confirmation" required />
                      <label class="form-label">Confirma la contrasenya</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="text" class="form-control form-control-lg" v-model="registerForm.phone" />
                      <label class="form-label">Telèfon</label>
                    </div>

                    <ReCaptcha v-if="isRegister" ref="registerRecaptchaRef" v-model="registerRecaptchaToken"
                      :sitekey="config.public.RECAPTCHA_SITE_KEY" />
                    <div v-if="registerCaptchaError" class="text-danger mb-4">{{ registerCaptchaError }}</div><br>

                    <div class="pt-1 mb-4">
                      <button class="btn btn-dark btn-lg btn-block" type="submit">
                        Registra't
                      </button>
                    </div>
                    <p class="mb-5 pb-lg-2" style="color: #393f81;">
                      Ja tens compte?
                      <a href="#" style="color: #393f81;" @click.prevent="toggleForm">Inicia sessió aquí</a>
                    </p>
                    <div v-if="registerError" class="text-danger mt-2">{{ registerError }}</div>
                  </form>

                  <div class="small text-muted" v-if="isRegister">
                    <a href="#!">Termes d'ús.</a>
                    <a href="#!">Política de privacitat</a>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useState } from '#app'
import CommunicationManager from '@/stores/communicationManager'
import ReCaptcha from '../components/ReCaptcha.vue'

const router = useRouter()
const config = useRuntimeConfig()

const isRegister = ref(false)

const loginForm = ref({
  email: '',
  password: ''
})

const registerForm = ref({
  name: '',
  lastname: '',
  email: '',
  password: '',
  password_confirmation: '',
  phone: ''
})

const loginRecaptchaRef = ref(null)
const registerRecaptchaRef = ref(null)

const loginRecaptchaToken = ref('')
const registerRecaptchaToken = ref('')
const loginCaptchaError = ref('')
const registerCaptchaError = ref('')

const loginError = ref('')
const registerError = ref('')

const token = useState('token', () => null)
const user = useState('user', () => null)

onMounted(() => {
  const storedToken = localStorage.getItem('access_token')
  const storedUser = localStorage.getItem('user')

  if (storedToken && storedUser) {
    token.value = storedToken
    user.value = JSON.parse(storedUser)
  }
})

async function loginUser() {
  loginError.value = ''
  loginCaptchaError.value = ''

  if (!loginRecaptchaToken.value) {
    loginCaptchaError.value = 'Si us plau, completa el ReCAPTCHA'
    return
  }

  try {
    const result = await CommunicationManager.login({
      ...loginForm.value,
      recaptchaToken: loginRecaptchaToken.value
    })

    console.log('Respuesta del servidor de login:', result)

    if (result.error) {
      loginError.value = result.error
      loginRecaptchaRef.value?.reset()
      loginRecaptchaToken.value = ''
    } else {
      const { access_token, user } = result.data

      if (access_token && user) {
        localStorage.setItem('access_token', access_token)
        localStorage.setItem('user', JSON.stringify(user))

        token.value = access_token
        user.value = user

        router.push('/')
      } else {
        loginError.value = 'Error: La respuesta no contiene información de usuario.'
      }
    }
  } catch (error) {
    loginError.value = 'Error en la connexió amb el servidor'
    console.error('Login error:', error)
  }
}

async function registerUser() {
  registerError.value = ''
  registerCaptchaError.value = ''

  if (!registerRecaptchaToken.value) {
    registerCaptchaError.value = 'Si us plau, completa el ReCAPTCHA'
    return
  }

  try {
    const result = await CommunicationManager.register({
      ...registerForm.value,
      recaptchaToken: registerRecaptchaToken.value
    })

    console.log('Respuesta del servidor de register:', result)

    if (result.error) {
      registerError.value = result.error
      registerRecaptchaRef.value?.reset()
      registerRecaptchaToken.value = ''
    } else {
      const { access_token, user } = result.data

      if (access_token && user) {
        localStorage.setItem('access_token', access_token)
        localStorage.setItem('user', JSON.stringify(user))

        token.value = access_token
        user.value = user

        router.push('/')
      } else {
        registerError.value = 'Error: La respuesta no contiene información de usuario.'
      }
    }
  } catch (error) {
    registerError.value = 'Error en la connexió amb el servidor'
    console.error('Register error:', error)
  }
}

async function toggleForm() {
  isRegister.value = !isRegister.value
  await nextTick()

  if (!isRegister.value && loginRecaptchaRef.value) {
    await loginRecaptchaRef.value.initializeRecaptcha()
  }

  if (isRegister.value && registerRecaptchaRef.value) {
    await registerRecaptchaRef.value.initializeRecaptcha()
  }
}
</script>

<style scoped>
.vh-100 {
  height: 100vh;
}

.card {
  border-radius: 1rem;
}

.img-fluid.fixed-img {
  height: 100vh;
  width: 300vh;
}

.text-danger {
  color: red;
}

@media (max-width: 768px) {
  .vh-100 {
    height: auto;
    min-height: 100vh;
    padding: 20px 0;
  }

  .container {
    padding: 20px;
  }

  .card {
    border-radius: 0.5rem;
    box-shadow: none;
  }

  .row.g-0 {
    flex-direction: column;
    align-items: center;
  }

  .col-md-6.col-lg-5 {
    display: none;
  }

  .col-md-6.col-lg-7 {
    width: 100%;
  }

  .card-body {
    padding: 20px;
    text-align: center;
  }

  .h1 {
    font-size: 1.8rem;
  }

  .form-outline {
    text-align: left;
  }

  .form-control {
    font-size: 1rem;
    padding: 10px;
  }

  .btn-lg {
    font-size: 1rem;
    padding: 12px;
    width: 100%;
  }

  p {
    font-size: 0.9rem;
  }
}
</style>
