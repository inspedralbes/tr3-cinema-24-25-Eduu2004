<template>
  <div class="cinema">
    <h3>Mapa de butaques</h3>
    <ul class="showcase">
      <li>
        <div class="seat"></div>
        <small>Gris: disponibles</small>
      </li>
      <li>
        <div class="seat selected"></div>
        <small>Verd: seleccionades per l'usuari</small>
      </li>
      <li>
        <div class="seat occupied"></div>
        <small>Vermell: ocupades</small>
      </li>    
    </ul>
    <p class="map-info">L'usuari pot seleccionar fins a 10 butaques (màxim per sessió).</p>

    <!-- Pati de butaques amb files A–L i 10 butaques per fila -->
    <div class="pati">
      <div class="pati-grid">
        <div class="pati-row" v-for="row in patiRows" :key="row.letter">
          <div class="row-label">{{ row.letter }}</div>
          <div class="row-seats">
            <div 
              class="seat"
              v-for="seat in row.seats" 
              :key="seat.id"
              :class="{ occupied: seat.status === 'Ocupada', selected: selectedSeats.includes(seat.id) }"
              @click="toggleSeatSelection(seat)"
            >
              {{ seat.number }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <p class="text">
      Has seleccionat <span id="count">{{ selectedSeats.length }}</span> butaques per un preu total de 
      <span id="total">{{ totalPrice }}</span> €
    </p>

    <!-- Missatges d'error i d'èxit -->
    <div v-if="errorMessage" class="text-red-500">{{ errorMessage }}</div>
    <div v-if="successMessage" class="text-green-500">{{ successMessage }}</div>
    
    <!-- Formulari d'usuari -->
    <div class="user-form">
      <h3 class="text-lg font-bold mt-6">Dades personals</h3>
      <input v-model="userData.name" type="text" placeholder="Nom" class="input-field" />
      <input v-model="userData.surname" type="text" placeholder="Cognom" class="input-field" />
      <input v-model="userData.phone" type="text" placeholder="Telèfon" class="input-field" />
    </div>
    
    <!-- Botons -->
    <div class="buttons">
      <button @click="confirmPurchase" class="btn buy">Comprar Entrades</button>
      <button @click="clearSelection" class="btn clear">Netejar selecció</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Array de files per al pati (A–L) amb 10 butaques per fila
const rowLetters = ['A','B','C','D','E','F','G','H','I','J','K','L']
const patiRows = rowLetters.map(letter => ({
  letter,
  seats: Array.from({ length: 10 }, (_, i) => ({
    id: `p-${letter}-${i + 1}`,
    row: letter,
    number: i + 1,
    status: 'Disponible' // Tots com a disponibles per defecte
  }))
}))

// Variables reactives per a la selecció
const selectedSeats = ref([])

// Preu fix: 6€ per butaca
const totalPrice = computed(() => selectedSeats.value.length * 6)

// Dades d'usuari i missatges
const userData = ref({ name: '', surname: '', phone: '' })
const errorMessage = ref('')
const successMessage = ref('')

// Funció per seleccionar/deseleccionar un seient
function toggleSeatSelection(seat) {
  if (seat.status === 'Ocupada') return
  if (selectedSeats.value.includes(seat.id)) {
    selectedSeats.value = selectedSeats.value.filter(id => id !== seat.id)
  } else if (selectedSeats.value.length < 10) {
    selectedSeats.value.push(seat.id)
  } else {
    showErrorMessage('Només pots seleccionar 10 butaques.')
  }
}

function confirmPurchase() {
  if (selectedSeats.value.length === 0) {
    showErrorMessage('Selecciona almenys una butaca.')
    return
  }
  showSuccessMessage('Entrades comprades correctament!')
  selectedSeats.value = []
}

function clearSelection() {
  selectedSeats.value = []
  errorMessage.value = ''
}

function showErrorMessage(message) {
  errorMessage.value = message
  setTimeout(() => {
    errorMessage.value = ''
  }, 5000)
}

function showSuccessMessage(message) {
  successMessage.value = message
  setTimeout(() => {
    successMessage.value = ''
  }, 5000)
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css?family=Montserrat&display=swap");
@import url("https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css");

.cinema {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 2rem auto;
  max-width: 800px;
  font-family: 'Roboto', sans-serif;
}

.showcase {
  display: flex;
  justify-content: space-between;
  list-style-type: none;
  background: rgba(0,0,0,0.1);
  padding: 5px 10px;
  border-radius: 5px;
  color: #777;
  width: 100%;
  max-width: 400px;
  margin-bottom: 10px;
}

.showcase li {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 10px;
}

.showcase li small {
  margin-left: 2px;
}

.map-info {
  font-size: 0.9rem;
  margin-bottom: 20px;
}

/* Estils per al pati de butaques i centrant el seu contingut */
.pati {
  width: 100%;
  margin-bottom: 20px;
  display: flex;
  justify-content: center;
}

.pati-grid {
  display: flex;
  flex-direction: column;
  gap: 5px;
  align-items: center;
}

.pati-row {
  display: flex;
  align-items: center;
  margin-bottom: 5px;
}

.pati-row .row-label {
  width: 20px;
  font-weight: bold;
  margin-right: 5px;
  text-align: center;
}

.pati-row .row-seats {
  display: flex;
  gap: 3px;
}

.pati-row .seat {
  background-color: #808080; /* Gris: disponibles */
  height: 25px;
  width: 25px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 0.7rem;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}

/* Estils per als seients globals */
.seat {
  background-color: #808080; /* Gris: disponibles */
  height: 25px;
  width: 30px;
  margin: 5px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 0.8rem;
}

.seat.selected {
  background-color: #28a745; /* Verd: seleccionades */
}

.seat.occupied {
  background-color: #dc3545; /* Vermell: ocupades */
  color: #fff;
}

.seat:not(.occupied):hover {
  cursor: pointer;
  transform: scale(1.1);
}

p.text {
  margin: 40px 0;
  font-size: 1rem;
}

p.text span {
  color: #28a745;
  font-weight: 600;
}

/* Formulari i botons */
.user-form {
  width: 100%;
  margin-top: 1rem;
}

.input-field {
  display: block;
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.buttons {
  margin-top: 1rem;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.btn {
  padding: 0.6rem 1rem;
  border-radius: 5px;
  color: #fff;
  font-weight: 600;
  cursor: pointer;
  outline: none;
  border: none;
}

.buy {
  background-color: #007bff;
}

.buy:hover {
  background-color: #0056b3;
}

.clear {
  background-color: #6c757d;
}

.clear:hover {
  background-color: #495057;
}

</style>
