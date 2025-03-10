<template>
    <div class="movie-details">
      <div v-if="movie">
        <h1>{{ movie.title }}</h1>
        <img :src="movie.poster ? movie.poster : '/placeholder.jpg'" alt="Poster de la película" class="movie-poster" />
        <p>{{ movie.plot }}</p>
        <p><strong>Género:</strong> {{ movie.genre }}</p>
        <p><strong>Duración:</strong> {{ movie.runtime }} minutos</p>
      </div>
      <div v-else>
        <p>Loading...</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  
  const movie = ref(null)
  const route = useRoute()
  
  onMounted(async () => {
    const movieId = route.params.id  // Obtener el ID de la película desde los parámetros de la ruta
    try {
      const movieRes = await fetch(`http://localhost:8000/api/movies/${movieId}`)
      const data = await movieRes.json()
      movie.value = data.movie
    } catch (error) {
      console.error('Error cargando detalles de la película:', error)
    }
  })
  </script>
  
  <style scoped>
  .movie-details {
    text-align: center;
    padding: 20px;
  }
  
  .movie-poster {
    width: 50%;
    max-width: 400px;
    height: auto;
    margin-bottom: 20px;
  }
  
  h1 {
    font-size: 2rem;
  }
  
  p {
    font-size: 1.2rem;
  }
  </style>
  