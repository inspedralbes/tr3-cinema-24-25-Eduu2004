<template>
    <div class="admin-login container">
      <h1>Administració - Inicia Sessió</h1>
      <form @submit.prevent="login" class="form-vertical">
        <div class="form-group">
          <label>Usuari:</label>
          <input type="text" v-model="username" required />
        </div>
        <div class="form-group">
          <label>Contrasenya:</label>
          <input type="password" v-model="password" required />
        </div>
        <button type="submit" class="btn">Iniciar Sessió</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        username: '',
        password: ''
      }
    },
    methods: {
      login() {
        // Aquí s'hauria d'implementar la validació via un endpoint
        this.$router.push('/admin/sessions')
      }
    }
  }
  </script>
  
  <style scoped>
  .container {
    padding: 1rem;
    background: #fff;
    max-width: 400px;
    margin: 0 auto;
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
  