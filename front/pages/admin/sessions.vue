<template>
    <div class="container">
      <h1>Gestió de Sessions</h1>
      <form @submit.prevent="createSession" class="form-vertical">
        <div class="form-group">
          <label>Pel·lícula:</label>
          <select v-model="newSession.movie_id" required>
            <option disabled value="">Selecciona una pel·lícula</option>
            <option v-for="movie in movies" :key="movie.id" :value="movie.id">
              {{ movie.title }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <label>Data:</label>
          <input type="date" v-model="newSession.date" required />
        </div>
        <div class="form-group">
          <label>Hora:</label>
          <select v-model="newSession.time" required>
            <option value="16:00">16:00</option>
            <option value="18:00">18:00</option>
            <option value="20:00">20:00</option>
          </select>
        </div>
        <div class="form-group">
          <label>VIP activat:</label>
          <input type="checkbox" v-model="newSession.vip_enabled" />
        </div>
        <div class="form-group">
          <label>Dia de l'espectador:</label>
          <input type="checkbox" v-model="newSession.is_discount_day" />
        </div>
        <button type="submit" class="btn">Crear Sessió</button>
      </form>
  
      <div class="session-list">
        <h2>Sessions Existents</h2>
        <div
          v-for="session in sessions"
          :key="session.id"
          class="session-card"
        >
          <p>{{ session.movie.title }} - {{ session.date }} {{ session.time }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        movies: [],
        sessions: [],
        newSession: {
          movie_id: '',
          date: '',
          time: '16:00',
          vip_enabled: false,
          is_discount_day: false
        }
      }
    },
    async mounted() {
      try {
        let moviesRes = await fetch('/api/movies')
        moviesRes = await moviesRes.json()
        this.movies = moviesRes.data
  
        let sessionsRes = await fetch('/api/sessions')
        sessionsRes = await sessionsRes.json()
        this.sessions = sessionsRes.data
      } catch (error) {
        alert('Error en obtenir dades.')
      }
    },
    methods: {
      async createSession() {
        try {
          const res = await fetch('/api/sessions', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(this.newSession)
          })
          const data = await res.json()
          if (res.ok) {
            this.sessions.push(data.data)
            // Reinicia el formulari
            this.newSession = {
              movie_id: '',
              date: '',
              time: '16:00',
              vip_enabled: false,
              is_discount_day: false
            }
          } else {
            alert('Error al crear la sessió: ' + data.message)
          }
        } catch (error) {
          alert('Error al crear la sessió.')
        }
      }
    }
  }
  </script>
  
  <style scoped>
  .container {
    padding: 1rem;
    background: #fff;
  }
  .form-vertical {
    display: flex;
    flex-direction: column;
    max-width: 500px;
  }
  .form-group {
    margin-bottom: 1rem;
  }
  .btn {
    padding: 0.5rem 1rem;
    background-color: #0066cc;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  .session-list {
    margin-top: 2rem;
  }
  .session-card {
    border: 1px solid #ddd;
    padding: 1rem;
    margin-bottom: 1rem;
  }
  </style>
  