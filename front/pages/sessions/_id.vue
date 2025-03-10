<template>
    <div class="session-detail container">
      <h1>{{ session.movie.title }}</h1>
      <p>{{ session.movie.plot }}</p>
  
      <!-- Mapa de butaques -->
      <SeatMap :seats="seats" @update-selection="updateSelection" />
  
      <!-- Formulari de compra -->
      <div class="purchase-form">
        <h3>Compra d'Entrades</h3>
        <form @submit.prevent="confirmPurchase">
          <div class="form-group">
            <label>Nom:</label>
            <input v-model="user.name" type="text" required />
          </div>
          <div class="form-group">
            <label>Cognom:</label>
            <input v-model="user.lastName" type="text" required />
          </div>
          <div class="form-group">
            <label>Telèfon:</label>
            <input v-model="user.phone" type="tel" required />
          </div>
          <button type="submit" class="btn">Confirmar Compra</button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import SeatMap from '~/components/SeatMap.vue'
  
  export default {
    components: { SeatMap },
    data() {
      return {
        session: {},
        seats: [],
        selectedSeats: [],
        user: {
          name: '',
          lastName: '',
          phone: ''
        }
      }
    },
    async fetch() {
      try {
        const sessionRes = await fetch(`/api/sessions/${this.$route.params.id}`)
        const sessionData = await sessionRes.json()
        this.session = sessionData.data
  
        const seatsRes = await fetch(`/api/sessions/${this.$route.params.id}/seats`)
        const seatsData = await seatsRes.json()
        this.seats = seatsData.data
      } catch (error) {
        console.error('Error en carregar la sessió o butaques:', error)
      }
    },
    methods: {
      updateSelection(selected) {
        this.selectedSeats = selected
      },
      async confirmPurchase() {
        if (this.selectedSeats.length === 0) {
          alert('Selecciona almenys una butaca.')
          return
        }
        try {
          const res = await fetch('/api/tickets', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              session_id: this.session.id,
              seats: this.selectedSeats,
              user: this.user
            })
          })
          const data = await res.json()
          if (res.ok) {
            alert('Compra realitzada correctament.')
          } else {
            alert('Error: ' + data.message)
          }
        } catch (error) {
          alert('Error al realitzar la compra: ' + error.message)
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
  .purchase-form {
    margin-top: 2rem;
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
  </style>
  