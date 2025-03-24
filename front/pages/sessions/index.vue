<template>
  <div class="sessions-container">
    <h2 class="sessions-title">Pròximes sessions</h2>
    <div class="sessions-grid">
      <div v-for="session in sessions" :key="session.id" class="session-card">
        <div class="session-poster">
          <img 
            :src="session.movie?.poster || '/placeholder.jpg'" 
            :alt="session.movie ? session.movie.title : 'Poster no disponible'" 
            class="poster-image"
          />
        </div>
        <div class="session-info">
          <h3 class="movie-title">{{ session.movie ? session.movie.title : 'Película no disponible' }}</h3>
          <p class="session-time">{{ formatDate(session.date) }} - {{ session.time.slice(0, 5) }}</p>
          <NuxtLink :to="`/sessions/${session.id}`" class="btn-buy-tickets">Comprar entrades</NuxtLink>
        </div>
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
.sessions-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 20px;
  font-family: 'Roboto', sans-serif;
  background-color: #fff; /* Fons blanc */
}

.sessions-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #222; /* Títol en negre */
  margin-bottom: 30px;
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.sessions-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
  padding: 0 20px;
}

.session-card {
  background-color: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.session-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.session-poster {
  width: 100%;
  height: 350px;
  overflow: hidden;
}

.poster-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-bottom: 3px solid #e50914;
}

.session-info {
  padding: 20px;
  text-align: center;
}

.movie-title {
  font-size: 1.4rem;
  font-weight: 600;
  color: #222;
  margin-bottom: 10px;
}

.session-time {
  font-size: 1rem;
  color: #666;
  margin-bottom: 15px;
}

.btn-buy-tickets {
  display: inline-block;
  padding: 10px 20px;
  background-color: #e50914;
  color: #fff;
  border: none;
  border-radius: 25px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
  text-decoration: none;
}

.btn-buy-tickets:hover {
  background-color: #c40812;
  text-decoration: none;
}

/* Adaptacions responsives */
@media (max-width: 1200px) {
  .sessions-grid {
    grid-template-columns: repeat(3, 1fr); 
  }
}

@media (max-width: 768px) {
  .sessions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .sessions-grid {
    grid-template-columns: 1fr;
  }

  .sessions-title {
    font-size: 2rem;
  }

  .session-poster {
    height: 250px;
  }

  .movie-title {
    font-size: 1.2rem;
  }
}
</style>
