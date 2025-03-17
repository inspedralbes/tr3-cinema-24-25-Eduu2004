<template>
  <section class="vh-100" style="background-color: #365079;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block">
                <img
                  src="https://static.vecteezy.com/system/resources/previews/019/482/385/non_2x/vintage-poster-for-cinema-retro-cartoon-food-for-promotion-template-free-vector.jpg"
                  alt="formulari d'inici de sessió"
                  class="img-fluid fixed-img"
                />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">
  
                  <h5 class="h1 fw-bold mb-0" style="letter-spacing: 1px;" v-if="!isRegister">
                    Inicia sessió al teu compte
                  </h5>
                  <h5 class="h1 fw-bold mb-0" style="letter-spacing: 1px;" v-else>
                    Crea un compte nou
                  </h5><br><br>
  
                  <form v-if="!isRegister" @submit.prevent="login">
                    <div class="form-outline mb-4">
                      <input
                        type="email"
                        class="form-control form-control-lg"
                        v-model="loginForm.email"
                        required
                      />
                      <label class="form-label">Adreça electrònica</label>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input
                        type="password"
                        class="form-control form-control-lg"
                        v-model="loginForm.password"
                        required
                      />
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
  
                  <!-- Formulari de registre -->
                  <form v-else @submit.prevent="register">
                    <div class="form-outline mb-4">
                      <input
                        type="text"
                        class="form-control form-control-lg"
                        v-model="registerForm.name"
                        required
                      />
                      <label class="form-label">Nom</label>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input
                        type="text"
                        class="form-control form-control-lg"
                        v-model="registerForm.lastname"
                        required
                      />
                      <label class="form-label">Cognom</label>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input
                        type="email"
                        class="form-control form-control-lg"
                        v-model="registerForm.email"
                        required
                      />
                      <label class="form-label">Adreça electrònica</label>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input
                        type="password"
                        class="form-control form-control-lg"
                        v-model="registerForm.password"
                        required
                      />
                      <label class="form-label">Contrasenya</label>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input
                        type="password"
                        class="form-control form-control-lg"
                        v-model="registerForm.password_confirmation"
                        required
                      />
                      <label class="form-label">Confirma la contrasenya</label>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input
                        type="text"
                        class="form-control form-control-lg"
                        v-model="registerForm.phone"
                      />
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
  
async function login() {
  loginError.value = ''
  try {
    const res = await fetch('http://localhost:8000/api/auth/login', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json', 
        'Accept': 'application/json' 
      },
      body: JSON.stringify(loginForm.value)
    })
    const data = await res.json()
    if (!res.ok) {
      loginError.value = data.message || 'Error en iniciar sessió'
      return
    }
    localStorage.setItem('access_token', data.data.access_token)
    localStorage.setItem('user', JSON.stringify(data.data.user))
    token.value = data.data.access_token
    user.value = data.data.user
    console.log('Sessió iniciada correctament', data)
    router.push('/')
  } catch (err) {
    loginError.value = 'S\'ha produït un error'
    console.error(err)
  }
}
  
async function register() {
  registerError.value = ''
  try {
    const res = await fetch('http://localhost:8000/api/auth/register', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(registerForm.value)
    })
    const data = await res.json()
    if (!res.ok) {
      registerError.value = data.message || 'Error en registrar-se'
      return
    }
    console.log('Registre realitzat correctament', data)
    localStorage.setItem('access_token', data.data.access_token)
    localStorage.setItem('user', JSON.stringify(data.data.user))
    token.value = data.data.access_token
    user.value = data.data.user
    router.push('/')
  } catch (err) {
    registerError.value = 'S\'ha produït un error'
    console.error(err)
  }
}
  
function toggleForm() {
  isRegister.value = !isRegister.value
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
