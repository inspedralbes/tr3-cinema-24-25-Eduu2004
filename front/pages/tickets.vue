<template>
    <div class="container">
      <h1>Consulta de Compres</h1>
      <form @submit.prevent="getTickets" class="form-inline">
        <label>Correu electrònic:</label>
        <input type="email" v-model="email" required />
        <button type="submit" class="btn">Buscar</button>
      </form>
  
      <div v-if="tickets.length">
        <h2>Entrades Comprades</h2>
        <div
          v-for="ticket in tickets"
          :key="ticket.id"
          class="ticket-card"
        >
          <p><strong>Pelicula:</strong> {{ ticket.session.movie.title }}</p>
          <p><strong>Data:</strong> {{ ticket.session.date }}</p>
          <p><strong>Hora:</strong> {{ ticket.session.time }}</p>
          <p><strong>Butaca:</strong> {{ ticket.seat.row }}{{ ticket.seat.number }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: '',
        tickets: []
      }
    },
    methods: {
      async getTickets() {
        try {
          const response = await fetch(`/api/tickets?email=${this.email}`)
          const res = await response.json()
          this.tickets = res.data
        } catch (error) {
          alert('Error en obtenir les entrades.')
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
  .form-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
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
  .ticket-card {
    border: 1px solid #ddd;
    padding: 1rem;
    margin-bottom: 1rem;
  }
  </style>
  