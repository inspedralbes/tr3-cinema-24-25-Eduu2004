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

                  <!-- Formulario de login -->
                  <form v-if="!isRegister" @submit.prevent="loginUser">
                    <div class="form-outline mb-4">
                      <input type="email" class="form-control form-control-lg" v-model="loginForm.email" required />
                      <label class="form-label">Correu electrònic</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" class="form-control form-control-lg" v-model="loginForm.password" required />
                      <label class="form-label">Contrasenya</label>
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="g-recaptcha" data-sitekey="6LfFjwArAAAAAPNtnaP1epIMDtAgMQWusJP-JqlK"></div>

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

                    <!-- reCAPTCHA -->
                    <div class="g-recaptcha" data-sitekey="6LfFjwArAAAAAPNtnaP1epIMDtAgMQWusJP-JqlK"></div>

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
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import CommunicationManager from '@/stores/communicationManager'

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

const token = ref(null)
const user = ref(null)

onMounted(() => {
  // Cargar el script de reCAPTCHA de Google solo una vez
  const script = document.createElement('script')
  script.src = 'https://www.google.com/recaptcha/api.js'
  script.async = true
  script.defer = true
  
  script.onload = () => {
    console.log('reCAPTCHA script cargado exitosamente');
    // Aquí puedes asegurar que el widget de reCAPTCHA está listo para usarse
  }

  document.head.appendChild(script)
})

function toggleForm() {
  isRegister.value = !isRegister.value
  
  // Recargar el script de reCAPTCHA cuando cambiamos de formulario
  loadRecaptchaScript()
}

function loadRecaptchaScript() {
  // Eliminar el script de reCAPTCHA existente si está presente
  const existingScript = document.querySelector('script[src="https://www.google.com/recaptcha/api.js"]')
  if (existingScript) {
    existingScript.remove()
  }

  // Cargar el script de reCAPTCHA
  const script = document.createElement('script')
  script.src = 'https://www.google.com/recaptcha/api.js'
  script.async = true
  script.defer = true
  document.head.appendChild(script)
}

async function loginUser() {
  loginError.value = ''
  
  // Obtén el token del reCAPTCHA
  const captchaToken = grecaptcha.getResponse()

  // Verificamos si el token no es válido
  if (!captchaToken) {
    loginError.value = 'Por favor, verifica que no eres un robot.'
    return
  }

  // Agregar el token al formulario de login
  loginForm.value.recaptcha_token = captchaToken
  
  const result = await CommunicationManager.login(loginForm.value, router)
  
  if (result.error) {
    loginError.value = result.error
  } else {
    token.value = result.token
    user.value = result.user
    console.log('Sesion iniciada correctamente', result)
  }
}

async function registerUser() {
  registerError.value = ''
  
  // Obtén el token del reCAPTCHA
  const captchaToken = grecaptcha.getResponse()
  console.log("Captcha Token:", captchaToken);  // Verifica el token en la consola

  // Verificamos si el token no es válido
  if (!captchaToken || captchaToken.length === 0) {
    registerError.value = 'Por favor, verifica que no eres un robot.'
    return
  }

  // Agregar el token al formulario de registro
  registerForm.value.recaptcha_token = captchaToken
  
  const result = await CommunicationManager.register(registerForm.value, router)
  
  if (result.error) {
    registerError.value = result.error
  } else {
    token.value = result.token
    user.value = result.user
    console.log('Registro realizado correctamente', result)
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
</style>
