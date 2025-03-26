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

                  <!-- Formulario de inicio de sesión -->
                  <form v-if="!isRegister" @submit.prevent="loginUser">
                    <div class="form-outline mb-4">
                      <input type="email" class="form-control form-control-lg" v-model="loginForm.email" required />
                      <label class="form-label">Correu electrònic</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" class="form-control form-control-lg" v-model="loginForm.password" required />
                      <label class="form-label">Contrasenya</label>
                    </div>

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

                  <!-- Formulario de registro -->
                  <form v-else @submit.prevent="registerUser">
                    <div class="form-outline mb-4">
                      <input type="text" class="form-control form-control-lg" v-model="registerForm.name" required />
                      <label class="form-label">Nom</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="text" class="form-control form-control-lg" v-model="registerForm.lastname" required />
                      <label class="form-label">Cognom</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="email" class="form-control form-control-lg" v-model="registerForm.email" required />
                      <label class="form-label">Correu electrònic</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" class="form-control form-control-lg" v-model="registerForm.password" required />
                      <label class="form-label">Contrasenya</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" class="form-control form-control-lg" v-model="registerForm.password_confirmation" required />
                      <label class="form-label">Confirma la contrasenya</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="text" class="form-control form-control-lg" v-model="registerForm.phone" />
                      <label class="form-label">Telèfon</label>
                    </div>

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
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useState } from '#app'
import CommunicationManager from '@/stores/communicationManager'
import { useReCaptcha } from 'vue-recaptcha-v3'

const router = useRouter()

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

const loginError = ref('')
const registerError = ref('')

const token = useState('token', () => null)
const user = useState('user', () => null)

const { executeRecaptcha } = useReCaptcha()

async function registerUser() {
  registerError.value = ''

  try {
    // Ejecutar reCAPTCHA para obtener el token
    const recaptchaToken = await executeRecaptcha('register')

    const result = await CommunicationManager.register({
      ...registerForm.value,
      recaptcha: recaptchaToken
    }, router)

    if (result.error) {
      registerError.value = result.error
    } else {
      console.log('Registro exitoso', result)

      // Guardar el token y el usuario en localStorage
      localStorage.setItem('access_token', result.token)
      localStorage.setItem('user', JSON.stringify(result.user))

      // Actualizar el estado reactivo
      token.value = result.token
      user.value = result.user

      // Redirigir a la página principal
      router.push('/')
    }
  } catch (error) {
    console.error('Error al registrarse:', error)
    registerError.value = "Error al registrarse"
  }
}

async function loginUser() {
  loginError.value = ''

  try {
    // Ejecutar reCAPTCHA para obtener el token
    const recaptchaToken = await executeRecaptcha('login')

    const result = await CommunicationManager.login({
      ...loginForm.value,
      recaptcha: recaptchaToken
    }, router)

    if (result.error) {
      loginError.value = result.error
    } else {
      console.log('Sesión iniciada correctamente', result)

      // Guardar el token y el usuario en localStorage
      localStorage.setItem('access_token', result.token)
      localStorage.setItem('user', JSON.stringify(result.user))

      // Actualizar el estado reactivo
      token.value = result.token
      user.value = result.user

      // Redirigir a la página principal
      router.push('/')
    }
  } catch (error) {
    console.error('Error al iniciar sesión:', error)
    loginError.value = "Error al iniciar sesión"
  }
}

function toggleForm() {
  isRegister.value = !isRegister.value
}
</script>

<style scoped>
/* Estilos de la página de login y registro */
.vh-100 {
  min-height: 100vh;
}

.card-body {
  background-color: #fff;
  border-radius: 1rem;
}

.fixed-img {
  width: 100%;
  border-top-left-radius: 1rem;
  border-bottom-left-radius: 1rem;
}

.form-outline {
  margin-bottom: 2rem;
}

.form-control {
  border-radius: 0.5rem;
}

.mb-4 {
  margin-bottom: 20px;
}

.btn {
  width: 100%;
}

.text-black {
  color: #000;
}
</style>
