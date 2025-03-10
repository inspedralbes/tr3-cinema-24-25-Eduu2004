<template>
    <div class="container">
      <h1>Informes d'Ocupació i Recaptació</h1>
      <form @submit.prevent="getReport" class="form-inline">
        <label>Selecciona una data:</label>
        <input type="date" v-model="selectedDate" required />
        <button type="submit" class="btn">Obtenir Informe</button>
      </form>
      <div v-if="report">
        <h2>Informe per a {{ selectedDate }}</h2>
        <div
          v-for="session in report.sessions"
          :key="session.id"
          class="report-session"
        >
          <h3>{{ session.movie.title }} - {{ session.time }}</h3>
          <p>Entrades Normal: {{ session.tickets.normal }}</p>
          <p>Entrades VIP: {{ session.tickets.vip }}</p>
          <p>Recaptació Normal: {{ session.revenue.normal }}€</p>
          <p>Recaptació VIP: {{ session.revenue.vip }}€</p>
          <p>Recaptació Total: {{ session.revenue.total }}€</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        selectedDate: '',
        report: null
      }
    },
    methods: {
      async getReport() {
        try {
          const res = await fetch(`/api/reports?date=${this.selectedDate}`)
          const data = await res.json()
          this.report = data.data
        } catch (error) {
          alert("Error en obtenir l'informe.")
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
  .report-session {
    border: 1px solid #ddd;
    padding: 1rem;
    margin-bottom: 1rem;
  }
  </style>
  