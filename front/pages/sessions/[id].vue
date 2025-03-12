<template>
  <div class="session-page">
    <h1 class="session-title">Compra de entradas</h1>
    <div class="session-details">
      <p v-if="movie.title">Película: <strong>{{ movie.title }}</strong></p>
      <p>Selecciona tus asientos (máx 10):</p>
    </div>
    <SeatSelection :sessionId="route.params.id" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import SeatSelection from '@/components/SeatSelection.vue'

const route = useRoute()
const movie = ref({})

onMounted(async () => {
  try {
    const res = await fetch(`http://localhost:8000/api/movies/${route.params.id}`)
    const data = await res.json()
    movie.value = data
  } catch (error) {
    console.error('Error al cargar la película:', error)
  }
})
</script>

<style scoped>
.session-page {
  background-color: #111;
  color: white;
  padding: 20px;
  min-height: 100vh;
  text-align: center;
  font-family: 'Arial', sans-serif;
}

.session-title {
  font-size: 2.5rem;
  margin-bottom: 20px;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.session-details p {
  font-size: 1.2rem;
  margin: 10px 0;
}
</style>
