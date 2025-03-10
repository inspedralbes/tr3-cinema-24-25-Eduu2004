<template>
  <div class="movie-grid">
    <div v-if="movies.length === 0" class="loading">
      Cargando películas...
    </div>

    <!-- Cartellera -->
    <h1>Cartellera</h1>
    <div class="movies">
      <div v-for="(movie, index) in movies" :key="movie.id" class="movie-card">
        <NuxtLink :to="`/movie/${movie.id}`">
          <img :src="movie.poster ? movie.poster : '/placeholder.jpg'" alt="Poster de la película" class="movie-poster" />
          <div class="movie-info">
            <h3>{{ movie.title }}</h3>
            <p>{{ movie.plot }}</p>
          </div>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const movies = ref([])
const recommendedMovies = ref([])
const currentIndex = ref(0)

onMounted(async () => {
  try {
    const moviesRes = await fetch('http://localhost:8000/api/movies')
    const data = await moviesRes.json()
    movies.value = data.movies || []

    // Obtener películas aleatorias para el slider
    recommendedMovies.value = getRandomMovies(movies.value, 5)

    // Cambiar película recomendada cada 2 segundos
    setInterval(() => {
      currentIndex.value = (currentIndex.value + 1) % recommendedMovies.value.length
    }, 2000)
  } catch (error) {
    console.error('Error cargando películas:', error)
  }
})

function getRandomMovies(arr, count) {
  const shuffled = [...arr].sort(() => 0.5 - Math.random())
  return shuffled.slice(0, count)
}
</script>