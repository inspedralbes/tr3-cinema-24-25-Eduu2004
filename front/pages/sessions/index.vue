<template>
  <div class="container mx-auto p-4">
    <div class="sessions">
      <h2 class="text-2xl font-bold mb-4">Pròximes sessions</h2>
      <div v-for="session in sessions" :key="session.id" class="session-card p-4 bg-gray-100 rounded-lg shadow">
        <h3 class="text-xl font-semibold">{{ session.movie ? session.movie.title : 'Movie not available' }}</h3>
        <p class="text-gray-600">{{ session.date }} - {{ session.time }}</p>
        <NuxtLink :to="`/sessions/${session.id}`" class="text-blue-500">Comprar entrades</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const sessions = ref([]);

onMounted(async () => {
  try {
    const sessionsRes = await fetch('http://localhost:8000/api/sessions');
    const data = await sessionsRes.json();

    if (data.sessions) {
      sessions.value = data.sessions;
    }
  } catch (error) {
    console.error('Error fetching sessions:', error);
  }
});
</script>

<style scoped>
.container {
  max-width: 1200px;
}
.session-card {
  margin-bottom: 1rem;
}
</style>
