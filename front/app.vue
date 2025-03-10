<template>
  <div>
    <header>
      <nav>
        <NuxtLink to="/">Inicio</NuxtLink>
        <NuxtLink to="/sessions">Ver Sesiones</NuxtLink>
        <NuxtLink to="/tickets">Entradas</NuxtLink>
      </nav>
    </header>
    <main>
      <NuxtPage />
    </main>
    <footer>
      <p>&copy; 2025 Cinema App. Todos los derechos reservados.</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: "App"
}
</script>

<style scoped>
header {
  background-color: #0066cc;
  padding: 1rem;
}
nav {
  display: flex;
  gap: 1rem;
}
nav a {
  color: #fff;
  text-decoration: none;
}
main {
  padding: 1rem;
  min-height: calc(100vh - 160px); 
}
footer {
  background-color: #f5f5f5;
  text-align: center;
  padding: 1rem;
}
</style>
