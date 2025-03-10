<template>
    <div class="movie-grid">
      <div v-if="movies.length === 0" class="loading">
        Cargando películas...
      </div>
      
      <div class="movies">
        <div v-for="(movie, index) in movies" :key="movie.id" class="movie-card">
            <img :src="movie.poster ? movie.poster : '/placeholder.jpg'" alt="Poster de la película" class="movie-poster" />
            <div class="movie-info">
            <h3>{{ movie.title }}</h3>
            <p>{{ movie.plot }}</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  
  const movies = ref([])
  
  onMounted(async () => {
    try {
      const moviesRes = await fetch('http://localhost:8000/api/movies')
      const data = await moviesRes.json()
      movies.value = data.movies || []
    } catch (error) {
      console.error('Error cargando películas:', error)
    }
  })
  </script>
  
  <style scoped>
  .movie-grid {
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
  }
  
  .loading {
    font-size: 18px;
    color: gray;
  }
  
  .movies {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    width: 100%;
    max-width: 1200px;
    margin-top: 20px;
  }
  
  .movie-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
  }
  
  .movie-card:hover {
    transform: scale(1.05);
  }
  
  .movie-poster {
    width: 100%;
    height: auto;
  }
  
  .movie-info {
    padding: 10px;
  }
  
  .movie-info h3 {
    font-size: 18px;
    margin-bottom: 8px;
  }
  
  .movie-info p {
    font-size: 14px;
    color: gray;
  }
  </style>
  