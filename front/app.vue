<template>
  <div class="app-container">
    <header>
      <nav>
        <div class="nav-left">
          <NuxtLink to="/" class="nav-link">Inici</NuxtLink>
          <NuxtLink to="/sessions" class="nav-link">Veure Sessions</NuxtLink>
          <NuxtLink to="/tickets" class="nav-link">Consulta d'Entrades</NuxtLink>
        </div>
        <div class="nav-right">
          <template v-if="!token">
            <NuxtLink to="/auth" class="nav-link auth">Login / Registre</NuxtLink>
          </template>
          <template v-else>
            <button @click="logout" class="logout-btn">Logout</button>
          </template>
        </div>
      </nav>
    </header>
    <main>
      <NuxtPage />
    </main>
    <footer>
      <p>&copy; 2025 Cinema. Drets reservats.</p>
    </footer>
  </div>
</template>

<script setup>
import { useState } from '#app'
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const user = useState('user', () => null)
const token = useState('token', () => null)
const router = useRouter()

onMounted(() => {
  const storedToken = localStorage.getItem('access_token')
  const storedUser = localStorage.getItem('user')
  if (storedToken) {
    token.value = storedToken
  }
  if (storedUser) {
    user.value = JSON.parse(storedUser)
  }
})

function logout() {
  localStorage.removeItem('user')
  localStorage.removeItem('access_token')
  user.value = null
  token.value = null
  router.push('/auth')
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap');

.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  font-family: 'Roboto', sans-serif;
  color: #eee;
  background-color: #121212;
}

header {
  background-color: #1c1c1c;
  border-bottom: 2px solid #e50914;
  padding: 1rem 2rem;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
}

nav {
  display: flex;
  align-items: center;
}

.nav-left {
  display: flex;
  gap: 2rem;
}

.nav-right {
  margin-left: auto;
  display: flex;
  gap: 2rem;
}

.nav-link {
  color: #eee;
  text-decoration: none;
  font-weight: 500;
  position: relative;
  padding: 0.5rem;
  transition: color 0.3s ease;
  font-family: 'Oswald', sans-serif;
}

.nav-link::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -0.3rem;
  width: 0%;
  height: 3px;
  background-color: #e50914;
  transition: width 0.3s ease;
}

.nav-link:hover::after {
  width: 100%;
}

.auth {
  font-weight: 600;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  background-color: #e50914;
  color: #fff;
  transition: background-color 0.3s ease;
}

.auth:hover {
  background-color: #b00610;
}

.logout-btn {
  background-color: transparent;
  color: #eee;
  border: 1px solid #e50914;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease;
}

footer {
  background-color: #1c1c1c;
  border-top: 2px solid #e50914;
  text-align: center;
  padding: 1rem 2rem;
  font-size: 0.875rem;
  color: #bbb;
}
</style>
