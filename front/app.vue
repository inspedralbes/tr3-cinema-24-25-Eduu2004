<template>
  <div>
    <header>
      <nav>
        <NuxtLink to="/">Inicio</NuxtLink>
        <NuxtLink to="/sessions">Ver Sesiones</NuxtLink>
        <NuxtLink to="/tickets">Entradas</NuxtLink>
        <template v-if="!token">
          <NuxtLink to="/auth">Login / Registro</NuxtLink>
        </template>
        <template v-else>
          <button @click="logout">Logout</button>
        </template>
      </nav>
    </header>
    <main>
      <NuxtPage />
    </main>
    <footer>
      <p>&copy; 2025 Cinema App. Todos los derechos reservados.</p>
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
header {
  background-color: #0066cc;
  padding: 1rem;
}
nav {
  display: flex;
  gap: 1rem;
  align-items: center;
}
nav a {
  color: #fff;
  text-decoration: none;
}
nav span {
  color: #fff;
  font-weight: 600;
}
nav button {
  background-color: #fff;
  color: #0066cc;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
}
main {
  padding: 1rem;
  min-height: calc(100vh - 160px);
}
footer {
  background-color: #f5f5f5;
  text-align: center;
  padding: 1rem;
}
</style>
