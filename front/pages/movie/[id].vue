<template>
  <div class="movie-container">
    <div v-if="movie" class="movie-details">
      <div class="poster-container">
        <img 
          :src="movie.poster ? movie.poster : '/placeholder.jpg'" 
          alt="Poster de la película" 
          class="movie-poster" 
        />
      </div>

      <div class="info-container">
        <h1 class="title">{{ movie.title }}</h1>
        <p class="plot">{{ movie.plot }}</p>

        <div class="movie-meta">
          <p><strong>🎭 Género:</strong> {{ movie.genre }}</p>
          <p><strong>⏳ Duración:</strong> {{ movie.runtime }} minutos</p>
        </div>

        <button class="btn-buy-ticket">Comprar Entrada</button>
      </div>
    </div>

    <div v-else class="loading">
      <p>Cargando película...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import CommunicationManager from '@/stores/communicationManager'

const movie = ref(null)
const route = useRoute()

onMounted(async () => {
  const movieId = route.params.id  
  try {
    const result = await CommunicationManager.getMovieDetails(movieId)
    if (result.error) {
      throw new Error(result.error)
    }
    movie.value = result.movie
  } catch (error) {
    console.error('Error cargando detalles de la película:', error)
  }
})
</script>

<style scoped>
.movie-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: #f5f5f5;
  color: #333;
  font-family: 'Roboto', sans-serif;
  padding: 40px 20px;
}

.movie-details {
  display: flex;
  align-items: flex-start;
  gap: 40px;
  max-width: 1000px;
  width: 100%;
  background-color: #fff;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.poster-container {
  flex: 1;
  max-width: 400px;
}

.movie-poster {
  width: 100%;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.info-container {
  flex: 2;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #222;
  margin-bottom: 10px;
}

.plot {
  font-size: 1.1rem;
  line-height: 1.6;
  color: #666;
}

.movie-meta {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 10px;
}

.movie-meta p {
  font-size: 1rem;
  color: #444;
}

.movie-meta strong {
  color: #007bff;
}

.btn-buy-ticket {
  align-self: flex-start;
  padding: 12px 24px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 25px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-buy-ticket:hover {
  background-color: #0056b3;
}

.loading {
  text-align: center;
  font-size: 1.2rem;
  color: #007bff;
}
</style>
