<template>
  <div class="movie-container">
    <div v-if="movies.length === 0" class="loading">
      Cargando películas...
    </div>

    <!-- Buscador -->
    <input 
      v-model="searchQuery" 
      type="text" 
      placeholder="Buscar película..." 
      class="search-bar"
    />

    <!-- Cartelera -->
    <h1 class="title">Cartelera</h1>
    <div class="movies-grid">
      <div v-for="(movie, index) in filteredMovies" :key="movie.id" class="movie-card">
        <NuxtLink :to="`/movie/${movie.id}`" class="movie-link">
          <img :src="movie.poster ? movie.poster : '/placeholder.jpg'" alt="Poster de la película" class="movie-poster" />
          <div class="movie-info">
            <h3>{{ movie.title }}</h3>
            <p class="movie-description">{{ movie.plot }}</p>
          </div>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const movies = ref([])
const recommendedMovies = ref([])
const currentIndex = ref(0)
const searchQuery = ref("")

onMounted(async () => {
  try {
    const moviesRes = await fetch('http://localhost:8000/api/movies')
    const data = await moviesRes.json()
    movies.value = data.movies || []

    recommendedMovies.value = getRandomMovies(movies.value, 5)

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

const filteredMovies = computed(() => {
  return movies.value.filter(movie => 
    movie.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})
</script>

<style scoped>
.movie-container {
  background-color: #111;
  color: white;
  text-align: center;
  padding: 20px;
  min-height: 100vh;
  font-family: 'Arial', sans-serif;
}

.search-bar {
  width: 50%;
  padding: 12px;
  margin-bottom: 20px;
  border: 2px solid #e50914;
  border-radius: 25px;
  font-size: 1rem;
  text-align: center;
  outline: none;
  background-color: #222;
  color: white;
  transition: 0.3s ease-in-out;
}

.search-bar:focus {
  border-color: white;
  background-color: #333;
}

.title {
  font-size: 2.5rem;
  margin-bottom: 20px;
  text-transform: uppercase;
  font-weight: bold;
  letter-spacing: 2px;
}

.movies-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  justify-content: center;
  padding: 20px;
}

.movie-card {
  background-color: #222;
  border-radius: 10px;
  overflow: hidden;
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
}

.movie-card:hover {
  transform: scale(1.05);
  box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.7);
}

.movie-poster {
  width: 100%;
  height: 320px;
  object-fit: cover;
  border-bottom: 3px solid #e50914;
}

.movie-info {
  padding: 15px;
  text-align: left;
}

.movie-info h3 {
  font-size: 1.3rem;
  margin-bottom: 10px;
  color: #e50914;
}

.movie-description {
  font-size: 1rem;
  color: #ccc;
  text-decoration: none;
}

.loading {
  font-size: 1.5rem;
  color: #e50914;
}

.movie-link {
  text-decoration: none;
  color: inherit;
}
</style>
