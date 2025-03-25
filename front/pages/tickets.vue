<template>
  <div class="consulta-container">

    <h1 class="title">Consulta d'Entrades Comprades</h1>

    <div v-if="loading" class="loading">Carregant...</div>
    <div class="search-container">
      <input id="email" type="email" v-model="email" placeholder="Introduïu el teu correu electrònic" required
        class="search-bar" />
      <span class="search-icon">🔍</span>
      <button @click.prevent="fetchTickets" class="btn-search">Buscar</button>
    </div>
    <div v-if="errorMessage" class="error">{{ errorMessage }}</div>

    <div v-if="Object.keys(groupedSessions).length">
      <h2 class="sub-title">Sessions Futures amb Entrades Comprades</h2>
      <div class="sessions-grid">
        <div v-for="(ticketsGroup, sessionId) in groupedSessions" :key="sessionId" class="session-card">
          <div class="session-info">
            <h3 class="session-title">
              🎬 {{ ticketsGroup[0].session?.movie?.title ?? 'Pel·lícula no disponible' }}
            </h3>
            <p class="session-meta">
              📅 {{ formatDate(ticketsGroup[0].session?.date) }}
              - 🕒 {{ formatTime(ticketsGroup[0].session?.time) }}
            </p>
            <button @click="selectSession(sessionId)" class="btn-details">
              Veure Detalls
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="!loading && !errorMessage" class="no-entries">
      Buscant correu electrònic...
    </div>

    <div v-if="selectedSession" class="session-details">
      <h3>Detalls de la Sessió</h3>
      <p><strong>Pel·lícula:</strong> {{ selectedSession.movie?.title || 'No disponible' }}</p>
      <p><strong>Data:</strong> {{ formatDate(selectedSession.date) }}</p>
      <p><strong>Hora:</strong> {{ formatTime(selectedSession.time) }}</p>
      <h4>Entrades Comprades</h4>
      <ul class="tickets-list">
        <li v-for="ticket in groupedSessions[selectedSession.id]" :key="ticket.id" class="ticket-item">
          🎟️ Seient:
          <span v-for="seat in ticket.seats" :key="seat.row + '-' + seat.number">
            {{ seat.row }}{{ seat.number }}
          </span>
          - 💰 Preu: {{ ticket.price }} €
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { format, parseISO } from 'date-fns'
import CommunicationManager from '@/stores/communicationManager'

const email = ref('')
const tickets = ref([])
const errorMessage = ref('')
const loading = ref(false)
const selectedSession = ref(null)

async function fetchTickets() {
  loading.value = true
  errorMessage.value = ''
  try {
    const result = await CommunicationManager.getTickets(email.value)
    if (result.error) throw new Error(result.error)
    tickets.value = result.tickets
  } catch (error) {
    errorMessage.value = error.message || "S'ha produït un error en obtenir les entrades."
    tickets.value = []
  } finally {
    loading.value = false
  }
}

const groupedSessions = computed(() => {
  const groups = {}
  tickets.value.forEach(ticket => {
    if (ticket.session) {
      const sessionId = ticket.session.id
      if (!groups[sessionId]) groups[sessionId] = []
      groups[sessionId].push(ticket)
    }
  })
  return groups
})

function selectSession(sessionId) {
  const group = groupedSessions.value[sessionId]
  if (group && group.length > 0) {
    selectedSession.value = group[0].session
  }
}

function formatDate(dateString) {
  if (!dateString) return ''
  return format(parseISO(dateString), 'dd/MM/yyyy')
}

function formatTime(timeString) {
  if (!timeString) return ''
  return timeString.slice(0, 5)
}
</script>

<style scoped>
.consulta-container {
  background-color: #f9f9f9;
  color: #333;
  padding: 40px 20px;
  min-height: 100vh;
}

.search-container {
  position: relative;
  max-width: 600px;
  margin: 0 auto 40px;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.search-bar {
  flex: 1;
  padding: 12px 20px 12px 45px;
  border: 1px solid #ccc;
  font-size: 1rem;
  background-color: #fff;
  color: #333;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
  border-radius: 5px;
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
  left: 25px;
  font-size: 1.2rem;
  color: #e50914;
}

.btn-search {
  background-color: #e50914;
  color: #fff;
  padding: 0.7rem 1.2rem;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-search:hover {
  background-color: #b00610;
}

.title {
  font-size: 3rem;
  font-weight: 700;
  text-align: center;
  margin-bottom: 40px;
  color: #000;
}

.loading {
  font-size: 1.2rem;
  text-align: center;
  color: #e50914;
  margin-top: 50px;
}

.error {
  color: red;
  font-size: 1rem;
  margin: 1rem auto;
  text-align: center;
}

.sub-title {
  font-size: 2rem;
  font-weight: 700;
  text-align: center;
  margin-bottom: 30px;
  color: #333;
}

.sessions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  padding: 0 20px;
}

.session-card {
  background-color: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.session-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.session-info {
  text-align: center;
}

.session-title {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #333;
}

.session-meta {
  font-size: 0.95rem;
  color: #666;
  margin-bottom: 15px;
}

.btn-details {
  padding: 10px 20px;
  background-color: #e50914;
  color: #fff;
  border: none;
  border-radius: 25px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-details:hover {
  background-color: #b00610;
}

.no-entries {
  font-size: 1.2rem;
  text-align: center;
  color: #999;
  margin-top: 1rem;
}

.session-details {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  margin: 40px auto 0;
  max-width: 600px;
  padding: 20px;
  text-align: left;
}

.session-details h3 {
  font-size: 1.5rem;
  margin-bottom: 15px;
  color: #333;
}

.session-details p {
  margin-bottom: 10px;
  color: #555;
}

.tickets-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.ticket-item {
  background-color: #f8f9fa;
  padding: 0.7rem;
  border-radius: 5px;
  margin-bottom: 0.5rem;
}
</style>
