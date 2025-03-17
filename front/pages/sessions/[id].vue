<template>
  <div class="session-page">
    <h1 class="session-title">Compra d'entrades</h1>

    <!-- Información de la Película -->
    <div class="movie-info">
      <img 
        :src="session.movie?.poster || '/placeholder.jpg'" 
        :alt="session.movie ? session.movie.title : 'Poster no disponible'" 
        class="poster-image"
      />
      <div class="movie-details">
        <h2>{{ movie.title }}</h2>
        <p><strong>Gènere:</strong> {{ movie.genre }}</p>
        <p><strong>Duració:</strong> {{ movie.runtime }} min</p>
        <p class="plot">{{ movie.plot }}</p>
        <p><strong>Data d'estrena:</strong> 28 de febrer de 2025</p>
      </div>
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
const session = ref({})

onMounted(async () => {
  try {
    const res = await fetch(`http://localhost:8000/api/sessions/${route.params.id}`)
    if (!res.ok) throw new Error("Error al carregar la sessió")
    const data = await res.json()
    session.value = data.data.session
    movie.value = data.data.movie
  } catch (error) {
    console.error('Error al carregar la sessió:', error)
  }
})
</script>

<style scoped>
.session-page {
  background-color: #f5f5f5;
  color: #333;
  padding: 40px 20px;
  min-height: 100vh;
  font-family: 'Roboto', sans-serif;
}

.session-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #222;
  margin-bottom: 30px;
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.movie-info {
  display: flex;
  align-items: center;
  gap: 20px;
  color: black;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
  max-width: 800px;
  margin: 0 auto 20px;
}

.poster-image {
  width: 180px;
  height: 260px;
  object-fit: cover;
  border-radius: 8px;
  border: 2px solid #e50914;
}

.movie-details {
  flex: 1;
}

.movie-details h2 {
  font-size: 1.8rem;
  font-weight: 600;
  margin-bottom: 10px;
}

.genre-tag {
  display: inline-block;
  background-color: #e50914;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.9rem;
  text-transform: uppercase;
}

.movie-details p {
  font-size: 1rem;
  margin-bottom: 8px;
}

.plot {
  font-size: 0.95rem;
  margin-top: 10px;
  line-height: 1.5;
}

.session-details {
  max-width: 800px;
  margin: 0 auto 30px;
  padding: 20px;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.session-details p {
  font-size: 1.2rem;
  color: #555;
}

@media (max-width: 768px) {
  .session-title {
    font-size: 2rem;
  }

  .movie-info {
    flex-direction: column;
    text-align: center;
  }

  .poster-image {
    width: 140px;
    height: 200px;
  }

  .movie-details h2 {
    font-size: 1.5rem;
  }

  .session-details p {
    font-size: 1rem;
  }
}
</style>
