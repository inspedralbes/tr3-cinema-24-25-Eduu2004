<template>
  <div class="sessions-container">
    <h1 class="title">Pròximes sessions</h1>
    <div class="sessions-grid">
      <div v-for="session in sessions" :key="session.id" class="session-card">
        <NuxtLink :to="`/sessions/${session.id}`" class="session-link">
          <div class="poster-container">
            <img 
              :src="session.movie?.poster || '/placeholder.jpg'" 
              :alt="session.movie ? session.movie.title : 'Poster no disponible'" 
              class="movie-poster"
            />
            <div class="overlay">
              <button class="btn-buy-ticket">Comprar Entrada</button>
            </div>
          </div>
          <div class="session-info">
            <h3>{{ session.movie ? session.movie.title : 'Película no disponible' }}</h3>
            <p class="session-time">{{ formatDate(session.date) }} - {{ session.time.slice(0, 5) }}</p>
          </div>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import CommunicationManager from '@/stores/communicationManager'

const sessions = ref([])

onMounted(async () => {
  sessions.value = await CommunicationManager.fetchSessions()
})

function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString('ca-ES', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap');

.sessions-container {
  background-color: #f9f9f9;
  color: #333;
  padding: 40px 20px;
  min-height: 100vh;
  font-family: 'Roboto', sans-serif;
}

.title {
  font-family: 'Oswald', sans-serif;
  font-size: 3rem;
  font-weight: 700;
  text-align: center;
  margin-bottom: 40px;
  color: #000;
}

.sessions-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
  padding: 0 20px;
}

.session-card {
  background-color: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.session-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.session-link {
  text-decoration: none;
  color: inherit;
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

.session-card:hover .movie-poster {
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

.session-card:hover .overlay {
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

.session-info {
  padding: 20px;
  text-align: left;
}

.session-info h3 {
  font-family: 'Oswald', sans-serif;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #333;
}

.session-time {
  font-size: 0.95rem;
  color: #666;
  line-height: 1.5;
}
</style>
