<template>
  <div class="movie-container">
    <div v-if="movie" class="movie-details">
      <div class="movie-poster-container">
        <img :src="movie.poster ? movie.poster : '/placeholder.jpg'" alt="Poster de la película" class="movie-poster" />
      </div>
      <div class="movie-info">
        <h1>{{ movie.title }}</h1>
        <p class="plot">{{ movie.plot }}</p>
        <div class="movie-meta">
          <p><strong>🎭 Género:</strong> {{ movie.genre }}</p>
          <p><strong>⏳ Duración:</strong> {{ movie.runtime }} minutos</p>
        </div>
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

const movie = ref(null)
const route = useRoute()

onMounted(async () => {
  const movieId = route.params.id  
  try {
    const response = await fetch(`http://localhost:8000/api/movies/${movieId}`)
    const data = await response.json()
    movie.value = data.movie
  } catch (error) {
    console.error('Error cargando detalles de la película:', error)
  }
})
</script>

<style scoped>
/* 📽 Estilos generales */
.movie-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: linear-gradient(to bottom, #111, #222);
  color: #fff;
  font-family: 'Arial', sans-serif;
  padding: 40px;
}

/* 🖼 Poster de la película */
.movie-poster-container {
  flex: 1;
  max-width: 400px;
}

.movie-poster {
  width: 100%;
  border-radius: 12px;
  box-shadow: 0px 8px 16px rgba(255, 255, 255, 0.2);
}

/* ℹ Información de la película */
.movie-details {
  display: flex;
  align-items: center;
  gap: 40px;
  max-width: 900px;
  background: rgba(0, 0, 0, 0.8);
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.5);
}

.movie-info {
  flex: 2;
}

h1 {
  font-size: 2.5rem;
  margin-bottom: 10px;
  text-transform: uppercase;
  color: #ffcc00;
}

.plot {
  font-size: 1.2rem;
  margin-bottom: 15px;
  line-height: 1.5;
  color: #ddd;
}

.movie-meta p {
  font-size: 1.1rem;
  margin-bottom: 5px;
}

/* ⏳ Estilos de carga */
.loading {
  text-align: center;
  font-size: 1.5rem;
}
</style>
