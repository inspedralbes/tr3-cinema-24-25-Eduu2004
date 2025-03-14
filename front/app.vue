<template>
  <div>
    <header>
      <nav>
        <NuxtLink to="/">Inicio</NuxtLink>
        <NuxtLink to="/sessions">Ver Sesiones</NuxtLink>
        <NuxtLink to="/tickets">Entradas</NuxtLink>
        <!-- Si el usuario no está autenticado, se muestra el enlace para login/registro -->
        <template v-if="!user">
          <NuxtLink to="/auth">Login / Registro</NuxtLink>
        </template>
        <!-- Si el usuario está autenticado, se muestra su nombre y un botón de logout -->
        <template v-else>
          <span>Bienvenido, {{ user.name }}</span>
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
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

// Variable reactiva para almacenar la información del usuario autenticado
const user = ref(null)
const router = useRouter()

// Al montar el componente, se busca la información del usuario en localStorage
onMounted(() => {
  const storedUser = localStorage.getItem('user')
  if (storedUser) {
    user.value = JSON.parse(storedUser)
  }
})

// Función para realizar el logout
function logout() {
  localStorage.removeItem('user')
  localStorage.removeItem('access_token')
  user.value = null
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
