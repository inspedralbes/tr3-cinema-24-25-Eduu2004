<template>
    <section class="vh-100" style="background-color: #9A616D;">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col col-xl-10">
            <div class="card" style="border-radius: 1rem;">
              <div class="row g-0">
                <!-- Imagen lateral (sólo en pantallas md y superiores) -->
                <div class="col-md-6 col-lg-5 d-none d-md-block">
                  <img
                    src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/img1.webp"
                    alt="login form"
                    class="img-fluid"
                    style="border-radius: 1rem 0 0 1rem;"
                  />
                </div>
                <!-- Contenedor del formulario -->
                <div class="col-md-6 col-lg-7 d-flex align-items-center">
                  <div class="card-body p-4 p-lg-5 text-black">
                    <!-- Logo y título -->
                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <span class="h1 fw-bold mb-0">Logo</span>
                    </div>
    
                    <!-- Título según formulario -->
                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;" v-if="!isRegister">
                      Sign into your account
                    </h5>
                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;" v-else>
                      Create a new account
                    </h5>
    
                    <!-- Formulario de Login -->
                    <form v-if="!isRegister" @submit.prevent="login">
                      <div class="form-outline mb-4">
                        <input
                          type="email"
                          class="form-control form-control-lg"
                          v-model="loginForm.email"
                          required
                        />
                        <label class="form-label">Email address</label>
                      </div>
    
                      <div class="form-outline mb-4">
                        <input
                          type="password"
                          class="form-control form-control-lg"
                          v-model="loginForm.password"
                          required
                        />
                        <label class="form-label">Password</label>
                      </div>
    
                      <div class="pt-1 mb-4">
                        <button class="btn btn-dark btn-lg btn-block" type="submit">
                          Login
                        </button>
                      </div>
    
                      <p class="mb-5 pb-lg-2" style="color: #393f81;">
                        Don't have an account?
                        <a href="#" style="color: #393f81;" @click.prevent="toggleForm">Register here</a>
                      </p>
    
                      <a class="small text-muted" href="#!">Forgot password?</a>
                      <div v-if="loginError" class="text-danger mt-2">{{ loginError }}</div>
                    </form>
    
                    <!-- Formulario de Registro -->
                    <form v-else @submit.prevent="register">
                      <div class="form-outline mb-4">
                        <input
                          type="text"
                          class="form-control form-control-lg"
                          v-model="registerForm.name"
                          required
                        />
                        <label class="form-label">Name</label>
                      </div>
    
                      <div class="form-outline mb-4">
                        <input
                          type="text"
                          class="form-control form-control-lg"
                          v-model="registerForm.lastname"
                          required
                        />
                        <label class="form-label">Lastname</label>
                      </div>
    
                      <div class="form-outline mb-4">
                        <input
                          type="email"
                          class="form-control form-control-lg"
                          v-model="registerForm.email"
                          required
                        />
                        <label class="form-label">Email address</label>
                      </div>
    
                      <div class="form-outline mb-4">
                        <input
                          type="password"
                          class="form-control form-control-lg"
                          v-model="registerForm.password"
                          required
                        />
                        <label class="form-label">Password</label>
                      </div>
    
                      <div class="form-outline mb-4">
                        <input
                          type="password"
                          class="form-control form-control-lg"
                          v-model="registerForm.password_confirmation"
                          required
                        />
                        <label class="form-label">Confirm Password</label>
                      </div>
    
                      <div class="form-outline mb-4">
                        <input
                          type="text"
                          class="form-control form-control-lg"
                          v-model="registerForm.phone"
                        />
                        <label class="form-label">Phone</label>
                      </div>
    
                      <div class="pt-1 mb-4">
                        <button class="btn btn-dark btn-lg btn-block" type="submit">
                          Sign up
                        </button>
                      </div>
    
                      <p class="mb-5 pb-lg-2" style="color: #393f81;">
                        Already have an account?
                        <a href="#" style="color: #393f81;" @click.prevent="toggleForm">Login here</a>
                      </p>
    
                      <div v-if="registerError" class="text-danger mt-2">{{ registerError }}</div>
                    </form>
    
                    <div class="small text-muted">
                      <a href="#!">Terms of use.</a>
                      <a href="#!">Privacy policy</a>
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
    
  async function login() {
    loginError.value = ''
    try {
      const res = await fetch('http://localhost:8000/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(loginForm.value)
      })
      const data = await res.json()
      if (!res.ok) {
        loginError.value = data.message || 'Login failed'
        return
      }
      console.log('Login successful', data)
      // Redirige al index.vue (ruta '/')
      router.push('/')
    } catch (err) {
      loginError.value = 'An error occurred'
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
        registerError.value = data.message || 'Registration failed'
        return
      }
      console.log('Registration successful', data)
      // Redirige al index.vue después del registro exitoso
      router.push('/')
    } catch (err) {
      registerError.value = 'An error occurred'
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
    
  .img-fluid {
    border-radius: 1rem 0 0 1rem;
  }
    
  .text-danger {
    color: red;
  }
  </style>
  