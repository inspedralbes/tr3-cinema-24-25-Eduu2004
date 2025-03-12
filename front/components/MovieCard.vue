<template>
    <div class="movie-card">
      <img :src="movie.poster" alt="Poster" />
      <h3>{{ movie.title }}</h3>
      <NuxtLink :to="`/movie/${movie.id}`">Ver detalles</NuxtLink>
    </div>
  </template>
  
  <script setup>
  defineProps(['movie']);
  </script>
  
  <style>
  .movie-card {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
  }
  </style>