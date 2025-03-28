<template>
  <div class="movie-container">
    <div v-if="movie" class="movie-details">
      <div class="poster-container">
        <img :src="movie.poster ? movie.poster : '/placeholder.jpg'" alt="Poster de la pel·lícula"
          class="movie-poster" />
      </div>

      <div class="info-container">
        <h1 class="title">{{ movie.title }}</h1>
        <p class="plot">{{ movie.plot }}</p>

        <div class="movie-meta">
          <p><strong>🎭 Gènere:</strong> {{ movie.genre }}</p>
          <p><strong>⏳ Duració:</strong> {{ movie.runtime }} minuts</p>
        </div>

        <button v-if="hasSession" @click="goToSession" class="btn-buy-ticket">
          Comprar Entrada
        </button>
        <p v-else class="no-session-msg">
          No hi ha sessió disponible per a aquesta pel·lícula
        </p>
      </div>
    </div>

    <div v-else class="loading">
      <p>Carregant pel·lícula...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import CommunicationManager from '@/stores/communicationManager'

const movie = ref(null)
const sessionId = ref(null)
const hasSession = ref(false)
const route = useRoute()
const router = useRouter()

onMounted(async () => {
  const movieId = route.params.id

  if (!movieId) {
    throw new Error('Movie ID is missing')
  }

  try {
    const result = await CommunicationManager.getMovieDetails(movieId)
    if (result.error) throw new Error(result.error)
    movie.value = result.movie

    const sessions = await CommunicationManager.fetchSessions()
    const session = sessions.find(session => session.movie_id === Number(movieId))

    if (session) {
      sessionId.value = session.id
      hasSession.value = true
    }
  } catch (error) {
    console.error('Error cargando detalles de la película:', error)
  }
})

const goToSession = () => {
  if (sessionId.value) {
    router.push(`/sessions/${sessionId.value}`);

  }
}
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
  color: #e50914;
}

.btn-buy-ticket {
  align-self: flex-start;
  padding: 12px 24px;
  background-color: #e50914;
  color: #fff;
  border: none;
  border-radius: 25px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-buy-ticket:hover {
  background-color: #aa0d0d;
}

.no-session-msg {
  font-size: 1rem;
  font-weight: bold;
  color: #e50914;
  background-color: #ffe5e5;
  padding: 10px;
  border-radius: 8px;
  text-align: center;
}

.loading {
  text-align: center;
  font-size: 1.2rem;
  color: #e50914;
}
@media (max-width: 768px) {
  .movie-container {
    padding: 20px;
  }

  .movie-details {
    flex-direction: column;
    gap: 20px;
    padding: 20px;
  }

  .poster-container {
    max-width: 100%;
    text-align: center;
  }

  .movie-poster {
    max-width: 80%;
    height: auto;
  }

  .info-container {
    gap: 15px;
  }

  .title {
    font-size: 2rem;
    text-align: center;
  }

  .plot {
    font-size: 1rem;
    text-align: justify;
  }

  .movie-meta {
    align-items: center;
  }

  .movie-meta p {
    text-align: center;
  }

  .btn-buy-ticket {
    width: 100%;
    text-align: center;
    font-size: 1.2rem;
    padding: 14px;
  }

  .no-session-msg {
    font-size: 1rem;
    text-align: center;
    padding: 12px;
  }
}

</style>
