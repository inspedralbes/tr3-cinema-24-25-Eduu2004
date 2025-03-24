<template>
  <div class="movie-container">
    <div v-if="movies.length === 0" class="loading">
      Cargando películas...
    </div>

    <div class="search-container">
      <input 
        v-model="searchQuery" 
        type="text" 
        placeholder="Buscar película..." 
        class="search-bar"
      />
      <span class="search-icon">🔍</span>
    </div>

    <h1 class="title">Cartelera</h1>

    <div class="movies-grid">
      <div v-for="(movie, index) in filteredMovies" :key="movie.id" class="movie-card">
        <NuxtLink :to="`/movie/${movie.id}`" class="movie-link">
          <div class="poster-container">
            <img 
              :src="movie.poster ? movie.poster : '/placeholder.jpg'" 
              alt="Poster de la película" 
              class="movie-poster" 
            />
            <div class="overlay">
              <button class="btn-buy-ticket">Comprar Entrada</button>
            </div>
          </div>
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
import { fetchMovies } from '@/stores/communicationManager'

const movies = ref([])
const searchQuery = ref("")

onMounted(async () => {
  movies.value = await fetchMovies()
})

const filteredMovies = computed(() => {
  return movies.value.filter(movie => 
    movie.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})
</script>

<style scoped>
.movie-container {
  background-color: #f5f5f5;
  color: #333;
  padding: 40px 20px;
  min-height: 100vh;
  font-family: 'Roboto', sans-serif;
}

.search-container {
  position: relative;
  max-width: 600px;
  margin: 0 auto 40px;
}

.search-bar {
  width: 100%;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  border-radius: 25px;
  font-size: 1rem;
  background-color: #fff;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.search-bar:focus {
  border-color: #007bff;
  box-shadow: 0 2px 12px rgba(0, 123, 255, 0.2);
  outline: none;
}

.search-icon {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #888;
}

.title {
  font-size: 2.5rem;
  font-weight: 700;
  text-align: center;
  margin-bottom: 40px;
  color: #222;
}

.movies-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
  padding: 0 20px;
}

.movie-card {
  background-color: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.movie-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.poster-container {
  position: relative;
  width: 100%;
  height: 350px;
  overflow: hidden;
}

.movie-poster {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.movie-card:hover .movie-poster {
  transform: scale(1.1);
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.movie-card:hover .overlay {
  opacity: 1;
}

.btn-buy-ticket {
  padding: 10px 20px;
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

.movie-info {
  padding: 20px;
  text-align: left;
}

.movie-info h3 {
  font-size: 1.4rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #222;
}

.movie-description {
  font-size: 0.95rem;
  color: #666;
  line-height: 1.5;
}

.loading {
  font-size: 1.2rem;
  text-align: center;
  color: #007bff;
  margin-top: 50px;
}
</style>