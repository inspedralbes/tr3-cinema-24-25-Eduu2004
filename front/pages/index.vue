<template>
  <div class="movie-container">
    <div v-if="movies.length === 0" class="loading">
      Carregant pel·lícules...
    </div>

    <div class="search-container">
      <input v-model="searchQuery" type="text" placeholder="Buscar pel·lícula..." class="search-bar" />
      <span class="search-icon">🔍</span>
    </div>

    <h1 class="title">Cartellera</h1>

    <div class="movies-grid">
      <div v-for="(movie, index) in filteredMovies" :key="movie.id" class="movie-card">
        <NuxtLink :to="`/movie/${movie.id}`" class="movie-link">
          <div class="poster-container">
            <img :src="movie.poster ? movie.poster : '/placeholder.jpg'" alt="Poster de la pel·lícula"
              class="movie-poster" />
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
import CommunicationManager from '@/stores/communicationManager'

const movies = ref([])
const searchQuery = ref("")

onMounted(async () => {
  movies.value = await CommunicationManager.fetchMovies()
})

const filteredMovies = computed(() => {
  return movies.value.filter(movie =>
    movie.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap');

.movie-container {
  background-color: #f9f9f9;
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
  padding: 12px 20px 12px 45px;
  border: 1px solid #ccc;
  font-size: 1rem;
  background-color: #fff;
  color: #333;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.search-bar::placeholder {
  color: #888;
}

.search-bar:focus {
  border-color: #e50914;
  box-shadow: 0 2px 12px rgba(229, 9, 20, 0.2);
  outline: none;
}

.search-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 1.2rem;
  color: #e50914;
}

.title {
  font-family: 'Oswald', sans-serif;
  font-size: 3rem;
  font-weight: 700;
  text-align: center;
  margin-bottom: 40px;
  color: black;
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
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
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
  transform: scale(1.05);
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
  padding: 12px 25px;
  background-color: #e50914;
  color: #fff;
  border: none;
  border-radius: 30px;
  font-size: 1rem;
  font-family: 'Oswald', sans-serif;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-buy-ticket:hover {
  background-color: #b00610;
}

.movie-info {
  padding: 20px;
  text-align: left;
}

.movie-link {
  text-decoration: none;
  color: inherit;
}

.movie-link:hover,
.movie-link:focus {
  text-decoration: none;
}

.movie-description {
  font-size: 0.95rem;
  color: #666;
  line-height: 1.5;
  text-decoration: none;
}

.movie-link:hover .movie-description {
  text-decoration: none;

}

.movie-info h3 {
  font-family: 'Oswald', sans-serif;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #333;
}

.loading {
  font-size: 1.2rem;
  text-align: center;
  color: #e50914;
  margin-top: 50px;
}
</style>
