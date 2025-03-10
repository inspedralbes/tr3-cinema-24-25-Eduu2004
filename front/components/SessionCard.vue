<template>
    <div class="card">
      <img :src="session.movie.poster" alt="Poster de la pel·lícula" class="poster" />
      <div class="card-content">
        <h2>{{ session.movie.title }}</h2>
        <p>{{ session.movie.plot }}</p>
        <nuxt-link :to="`/session/${session.id}`" class="btn">Veure Detalls</nuxt-link>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ['session']
  }
  </script>
  
  <style scoped>
  .card {
    width: 300px;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  .poster {
    width: 100%;
    height: auto;
  }
  .card-content {
    padding: 1rem;
  }
  .btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    background-color: #0066cc;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 1rem;
  }
  </style>
  