<template>
  <div class="consulta-entrades">
    <h1>Consulta d'Entrades Comprades</h1>

    <form @submit.prevent="fetchTickets" class="form-container">
      <input
        id="email"
        type="email"
        v-model="email"
        placeholder="Introduïu el teu correu electrònic"
        required
        class="input-field"
      />
      <button type="submit" class="btn">Buscar</button>
    </form>

    <div v-if="loading" class="loading">Carregant...</div>
    <div v-if="errorMessage" class="error">{{ errorMessage }}</div>

    <div v-if="Object.keys(groupedSessions).length">
      <h2>Sessions Futures amb Entrades Comprades</h2>
      <ul class="sessions-list">
        <li
          v-for="(ticketsGroup, sessionId) in groupedSessions"
          :key="sessionId"
          class="session-item"
        >
          <span class="session-title">
            🎬 {{ ticketsGroup[0].session?.movie?.title ?? 'Pel·lícula no disponible' }} -
            📅 {{ formatDate(ticketsGroup[0].session?.date) }} 🕒 {{ formatTime(ticketsGroup[0].session?.time) }}
          </span>
          <button @click="selectSession(sessionId)" class="btn-secondary">
            Veure Detalls
          </button>
        </li>
      </ul>
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
        <li v-for="ticket in groupedSessions[selectedSession.id]" :key="ticket.id">
          🎟️ Seient:
          <span
            v-for="seat in ticket.seats"
            :key="seat.row + '-' + seat.number"
          >
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
    if (result.error) {
      throw new Error(result.error)
    }
    tickets.value = result.tickets
  } catch (error) {
    errorMessage.value =
      error.message || "S'ha produït un error en obtenir les entrades."
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
      if (!groups[sessionId]) {
        groups[sessionId] = []
      }
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
.consulta-entrades {
  max-width: 800px;
  margin: 2rem auto;
  padding: 1.5rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.form-container {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.input-field {
  flex: 1;
  padding: 0.7rem;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
}

.btn {
  padding: 0.7rem 1.2rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.btn:hover {
  background-color: #0056b3;
}

.btn-secondary {
  background-color: #28a745;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  color: white;
  border: none;
  cursor: pointer;
}

.btn-secondary:hover {
  background-color: #218838;
}

.loading {
  font-size: 1.2rem;
  font-weight: bold;
  color: #666;
}

.error {
  color: red;
  font-size: 1rem;
  margin-top: 1rem;
}

.no-entries {
  font-size: 1.2rem;
  color: #999;
  margin-top: 1rem;
}

.sessions-list {
  list-style: none;
  padding: 0;
  text-align: left;
}

.session-item {
  background: #f8f9fa;
  padding: 1rem;
  margin-bottom: 0.5rem;
  border-radius: 6px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.session-title {
  font-size: 1rem;
  font-weight: bold;
}

.session-details {
  background: #f1f1f1;
  padding: 1.5rem;
  border-radius: 6px;
  margin-top: 1.5rem;
  text-align: left;
}

.tickets-list {
  list-style: none;
  padding: 0;
  margin-top: 1rem;
}

.tickets-list li {
  background: #e9ecef;
  padding: 0.7rem;
  border-radius: 5px;
  margin-bottom: 0.5rem;
}
</style>
